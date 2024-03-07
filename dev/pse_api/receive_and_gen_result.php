<?php

require "../../vendor/autoload.php";
$dotenv = Dotenv\Dotenv::createImmutable(dirname(__DIR__, 2))->load();

require dirname(__DIR__, 2)."/config.php";
require dirname(__DIR__, 2)."/models/Fetch.php";
require dirname(__DIR__, 2)."/models/DB.php";
require dirname(__DIR__, 2)."/functions.php";
require dirname(__DIR__, 2)."/api/functions.php";

$pdo = new_pdo();

function gen_result_sample($reserve_info, array $status_rand_array): array {
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
  $faqs = Fetch::get("faqs", $survey_id, "survey_id", "order_num");

  # calls
  foreach ($numbers as $number) {
    $status = $status_rand_array[array_rand($status_rand_array)];
    $answers = [];
    $duration = 0;
    if ($status === 1) {
      $time = date("H:i:s", rand(strtotime($start), strtotime($end)));
      $duration = rand(10, 150);
      # answers
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
  
  $file_name = "ac_res{$reserve["id"]}_{$reserve["date"]}.json";
  $json = json_encode($result, JSON_PRETTY_PRINT);
  $file_path = dirname(__DIR__, 2)."/storage/outputs/{$file_name}";
  return [$json, $file_path];
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
  if (!$file_path = upload_file($_FILES["file"])) exit("ファイルのアップロードに失敗しました");
  $reserve_info = json_decode(file_get_contents($file_path), true);
  echo "予約情報ファイルを正常に受信しました" . PHP_EOL;

  [$json, $file_path] = gen_result_sample($reserve_info, [1,2,2,3,3,4,4,6,6]);
  
  file_put_contents($file_path, $json);
  $download = url('/storage/outputs/' . basename($file_path));
  echo "サンプル結果ファイルを生成しました: <a href='{$download}' download>{$file_path}</a>" . PHP_EOL;
}

?>

<h2>予約情報ファイルを受信、サンプル結果ファイルを生成</h2>
<form method="post" enctype="multipart/form-data">
  <label>予約情報ファイル</label>
  <input type="file" name="file">
  <button type="submit">実行</button>
</form>