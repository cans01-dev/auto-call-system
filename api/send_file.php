<?php

require "../config.php";
require "../models/Fetch.php";
require "../models/DB.php";
require "./api_functions.php";

function gen_reserve_info_array($reserve) {
  $survey = Fetch::find("surveys", $reserve["survey_id"]);
  $faqs = Fetch::get("faqs", $survey["id"], "survey_id");
  $endings = Fetch::get("endings", $survey["id"], "survey_id");
  $areas = Fetch::areasByReserveId($reserve["id"]);

  echo $survey["title"] . "<br>";
  if ($reserve["status"]) exit("エラー: 予約のステータスが予約済ではない");
  if (empty($areas)) exit("エラー: エリアが指定されてない");

  $stations = [];
  foreach ($areas as $area) {
    foreach (Fetch::get("stations", $area["id"], "area_id") as $station) {
      $stations[] = $station;
    }
  }

  $r = [
    "id" => $reserve["id"],
    "user_id" => $survey["user_id"],
    "date" => $reserve["date"],
    "start" => substr($reserve["start"], 0, -3),
    "end" => substr($reserve["end"], 0, -3),
    "faqs" => [],
    "endings" => [],
    "numbers" => [],
    // greeting
  ];

  # faqs
  $faqs_array = [];
  foreach ($faqs as $faq) {
    $f = [
      "label" => "stage" . $faq["order_num"] + 1,
      "faq_id" => "{$faq["id"]}",
      "options" => []
      // voice
    ];
      
    $options = Fetch::get("options", $faq["id"], "faq_id");
    foreach($options as $option) {
      $next_type = $option["next_ending_id"] ? "ending" : "faq";
      $next_id = $next_type === "ending" ? $option["next_ending_id"] : $option["next_faq_id"];

      $f["options"]["{$option["dial"]}"] = "{$next_type}{$next_id}";
    }
    $r["faqs"][] = $f;
  }

  # endings
  foreach ($endings as $i => $ending) {
    $e = [
      "label" => "ending" . $i + 1,
      "ending_id" => "{$ending["id"]}"
      // voice
    ];
    $r["endings"][] = $e;
  }

  # numbers
  $numbers_length = round((strtotime($reserve["end"]) - strtotime($reserve["start"])) / 3600 * NUMBERS_PER_HOUR);
  $stations_max = count($stations) - 1;

  while (count($r["numbers"]) < $numbers_length) {
    $station = $stations[rand(0, $stations_max)];
    $prefix = $station["prefix"];
    $n5 = rand(0, 9);
    $n6789 = sprintf('%04d', rand(0, 9999));

    $number = "{$prefix}{$n5}-{$n6789}";

    // 重複チェック

    $r["numbers"][] = $number;
  }

  return $r;
}


$pdo = new_pdo();

// $reserves = Fetch::all("reserves");

if ($_SERVER["REQUEST_METHOD"] === "POST") {
  $reserves = Fetch::get2("reserves", [
    ["date", "=", @$_POST["date"] ?? date("Y-m-d")]
  ]);

  foreach ($reserves as $reserve) {
    $reserve_info_array = gen_reserve_info_array($reserve);

    $array_json = json_encode($reserve_info_array, JSON_PRETTY_PRINT);
    $file_path = "/storage/outputs/ac{$reserve["id"]}_{$reserve["date"]}.json";
    file_put_contents(dirname(__DIR__).$file_path, $array_json);
    echo "{$file_path} created!<br>";

    // $url = SEND_FILE_URL;
    // send_file(dirname(__DIR__).$file_path, $url, [
    //   "Authorization: " . SEND_FILE_AUTHORIZATION
    // ]);
    // echo "sent file to {$url}";

    DB::update("reserves", $reserve["id"], [
      "status" => "1",
      "reserve_file" => $file_path
    ]);
    echo <<<EOL
    [UPDATE reserve({$reserve["id"]})]
    status => "1",
    reserve_file => {$file_path}
    EOL;
  }
}

?>

<h3>予約情報ファイル生成・送信</h3>
<form method="post">
  <label>日付</label>
  <input type="date" name="date">
  <button type="submit">確定</button>
</form>
<div style="border: 1px solid #ccc; padding: 12px;">
  <h4>生成結果</h4>
  <pre style="font-size: 2em;">
    <?= $array_json ?? "" ?>
  </pre>
</div>