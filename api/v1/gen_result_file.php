<?php

require "../config.php";
require "../models/Fetch.php";
require "../models/DB.php";
require "./api_functions.php";

function gen_result_info_array($reserve_info, array $status_rand_array): array
{
  [
    "id" => $reserve_id,
    "user_id" => $user_id,
    "start" => $start,
    "end" => $end,
    "numbers" => $numbers
  ] = $reserve_info;

  $result = [
    "id" => $reserve_id,
    "user_id" => $user_id,
    "calls" => []
  ];

  $reserve = Fetch::find("reserves", $reserve_id);
  $survey_id = $reserve["survey_id"];
  $faqs = Fetch::get("faqs", $survey_id, "survey_id");
  $endings = Fetch::get("endings", $survey_id, "survey_id");

  foreach ($numbers as $number) {
    $status = $status_rand_array[array_rand($status_rand_array)];
    $answers = [];

    if ($status === 1) {
      $time = date("H:i:s", rand(strtotime($start), strtotime($end)));
      $duration = rand(10, 150);
      
      $next_id = $faqs[0]["id"];
      $next_type = "faq";
      while (true) {
        if ($next_type === "faq") {
          $faq = $faqs[array_search($next_id, array_column($faqs, "id"))];
          $options = Fetch::get("options", $faq["id"], "faq_id");
          $option = $options[array_rand($options)];
          $next_type = $option["next_ending_id"] ? "ending" : "faq";
          $next_id = $next_type === "ending" ? $option["next_ending_id"] : $option["next_faq_id"];
          $answers[] = [
            "faq_id" => $faq["id"],
            "option_id" => $option["id"],
          ];
        } else {
          $ending = $endings[array_search($next_id, array_column($endings, "id"))];
          $answers[] = [
            "ending_id" => $ending["id"]
          ];
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

    $file_name = "ac_res{$reserve["id"]}_{$reserve["date"]}.json";
  }

  return [$result, $file_name];
}

$pdo = new_pdo();

if ($_SERVER["REQUEST_METHOD"] === "POST") {
  $file_path = upload_file($_FILES["file"]);
  echo "{$file_path} is uploaded!<br>";

  $ri = json_decode(file_get_contents($file_path), true);

  [$result, $file_name] = gen_result_info_array($ri, [1,1,1,1,2,3,4,6]);

  $array_json = json_encode($result, JSON_PRETTY_PRINT);
  $file_path = dirname(__DIR__)."/storage/outputs/{$file_name}";
  file_put_contents($file_path, $array_json);
  echo "{$file_path} created!<br>";
}

?>

<h3>サンプル結果ファイル生成</h3>
<form method="post" enctype="multipart/form-data">
  <label>予約情報ファイル</label>
  <input type="file" name="file" required>
  <button>生成</button>
</form>
<div style="border: 1px solid #ccc; padding: 12px;">
  <h4>生成結果</h4>
  <pre style="font-size: 2em;">
    <?= $array_json ?? "" ?>
  </pre>
</div>