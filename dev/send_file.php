<?php

require "../config.php";
require "../models/Fetch.php";

try {
	$pdo = new PDO(
		DB_PREFIX."dbname=".DB_NAME.";host=".DB_HOST,
		DB_USERNAME,
		DB_PASSWORD
	);
} catch (PDOException $e) {
	exit($e->getMessage());
}

$reserves = Fetch::get2("reserves", [
  ["date", "=", @$_GET["date"] ?? date("Y-m-d")]
]);

foreach ($reserves as $reserve) {
  $survey = Fetch::find("surveys", $reserve["survey_id"]);
  $faqs = Fetch::get("faqs", $survey["id"], "survey_id");
  $areas = Fetch::areasByReserveId($reserve["id"]);

  $array = [
    "ac_id" => $reserve["id"],
    "user_id" => $survey["user_id"],
    "date" => $reserve["date"],
    "start" => substr($reserve["start"], 0, -3),
    "end" => substr($reserve["end"], 0, -3),
    // greeting
    // ending
  ];

  # faqs
  $faqs_array = [];
  foreach ($faqs as $i => $faq) {
    $f = [
      "label" => "stage{$i}",
      // voice
    ];
      
    $options = Fetch::get("options", $faq["id"], "faq_id");
    $options_array = [];
    foreach($options as $i => $option) {
      $options_array["{$option["dial"]}"] = "aaa";
    }
    $f["options"] = $options_array;
    $faqs_array[] = $f;
  }
  $array["faqs"] = $faqs_array;

  # numbers
  $numbers_array = [];
  $numbers_length = round((strtotime($reserve["end"]) - strtotime($reserve["start"])) / 3600 * NUMBERS_PER_HOUR);

  echo $reserve["id"] . "<br>";
  $stations = [];
  foreach ($areas as $area) {
    echo $area["title"] . "<br>";
    foreach (Fetch::get("stations", $area["id"], "area_id") as $station) {
      $stations[] = $station;
    }
  }

  $stations_max = count($stations) - 1;
  while (count($numbers_array) < $numbers_length) {
    $station = $stations[rand(0, $stations_max)];
    // Fetch::find("surveys", 1);

    $prefix = $station["prefix"];
    $n5 = rand(0, 9);
    $n6789 = sprintf('%04d', rand(0, 9999));

    $number = "{$prefix}{$n5}-{$n6789}";
    $numbers_array[] = $number;

    // echo "{$number} ({$station["title"]} - {$station["prefix"]})" . PHP_EOL;
  }
  $array["numbers"] = $numbers_array;

  $array_json = json_encode($array, JSON_PRETTY_PRINT);
  file_put_contents("outputs/ac{$reserve["id"]}.json", $array_json);
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
  $curl_file = new CURLFile($_FILES['file']["tmp_name"], $_FILES['file']["type"], $_FILES['file']["name"]);

  $ch = curl_init();
  
  curl_setopt_array($ch, [
    CURLOPT_URL => "http://localhost:8080/dev/receive_file.php",
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_HTTPHEADER => [
      "Authorization: Basic YWRtaW46dGVzdA=="
    ],
    CURLOPT_POST => true,
    CURLOPT_POSTFIELDS => [
      'file' => $curl_file
    ],
  ]);
  
  echo curl_exec($ch);
  
  curl_close($ch);

  exit();
}

?>

<form enctype="multipart/form-data" method="post">
  <input type="file" name="file">
  <button type="submit">送信</button>
</form>

<form>
  <input type="date" name="date">
  <button type="submit">確定</button>
</form>