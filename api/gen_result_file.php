<?php

require "../config.php";
require "../models/Fetch.php";
require "../models/DB.php";
require "./api_functions.php";

try {
  $pdo = new PDO(
    DB_PREFIX."dbname=".DB_NAME.";host=".DB_HOST,
    DB_USERNAME,
    DB_PASSWORD
  );
} catch (PDOException $e) {
  exit($e->getMessage());
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
  $file_path = upload_file($_FILES["file"]);
  echo "{$file_path} is uploaded!";
  $ri = json_decode(file_get_contents($file_path), true);

  $result = [
    "id" => $ri["id"],
    "user_id" => $ri["user_id"],
    "calls" => []
  ];

  $reserve = Fetch::find("reserves", $ri["id"]);
  $survey_id = $reserve["survey_id"];
  $faqs = Fetch::get("faqs", $survey_id, "survey_id");

  for ($i=0; $i<5; $i++) {
    $number = $ri["numbers"][array_rand($ri["numbers"])];
    $s_arr = [1,1,1,1,2,3,4,6];
    $status = $s_arr[array_rand($s_arr)];
    if ($status === 1) {
      $time = date("H:i:s", rand(strtotime($ri["start"]), strtotime($ri["end"])));
      $duration = rand(10, 150);
      $answers = [];
      
      $next_id = $faqs[0]["id"];
      $next_type = "faq";
      while (true) {
        if ($next_type === "faq") {
          $faq = $faqs[array_search(, $faqs)];
          $options = Fetch::get("options", $faq["id"], "faq_id");
          $option = $options[array_rand($options)];
          $next_type = $option["next_ending_id"] ? "ending" : "faq";
          $next_id = $next_type === "ending" ? $option["next_ending_id"] : $option["next_faq_id"];
          $answers[] = [
            "id" => $faq["id"],
            "option_id" => $option["id"],
          ];
        } else {
          break;
        }
      }
    }
    
    $result["calls"][] = [
      "number" => $number,
      "status" => $status,
      "duration" => $duration ?? null,
      "time" => $time ?? null,
      "answers" => $answers ?? null
    ];
  }

  $array_json = json_encode($result, JSON_PRETTY_PRINT);
  $file_path = "/storage/outputs/ac_res{$reserve["id"]}_{$reserve["date"]}.json";
  file_put_contents(dirname(__DIR__).$file_path, $array_json);
  echo "{$file_path} created!<br>";
}

?>

<form method="post" enctype="multipart/form-data">
  <label>結果ファイルを生成する元の予約情報ファイルを選択</label>
  <input type="file" name="file" required>
  <button>生成</button>
</form>

<pre>
{
  "id": 1, // ac_id -> id
  "user_id": 1,
  "calls": [
    {
      "number": "080-1234-5678",
      "status": 1,
      // "last_label" 削除
      "duration": "00:01:23", // keep_time -> duration
      "time": "18:12:25", // call_time -> time
      "answers": {
        "id": 12, // faq_id
        "option_id": 32,
        // "label" 削除
        // "dial" 削除
      }
    },
  ]
}
</pre>