<?php

# コマンドラインから実行
require "./vendor/autoload.php";
$dotenv = Dotenv\Dotenv::createImmutable(dirname(__DIR__))->load();

require dirname(__DIR__)."/config.php";
require dirname(__DIR__)."/models/Fetch.php";
require dirname(__DIR__)."/models/DB.php";
require dirname(__DIR__)."/functions.php";
require __DIR__."/functions.php";

function gen_reserve_info($reserve) {
  $survey = Fetch::find("surveys", $reserve["survey_id"]);
  $user = Fetch::find("users", $survey["user_id"]);
  $faqs = Fetch::get("faqs", $survey["id"], "survey_id", "order_num");
  $endings = Fetch::get("endings", $survey["id"], "survey_id");

  # file_path
  $f_date = str_replace("-", "_", $reserve["date"]);
  $file_path = user_dir("user{$user["id"]}_{$f_date}.json", $user["id"]);

  # basis
  $array = [
    "id" => $reserve["id"],
    "user_id" => $user["id"],
    "date" => $reserve["date"],
    "greeting" => $survey["greeting_voice_file"],
    "start" => substr($reserve["start"], 0, -3),
    "end" => substr($reserve["end"], 0, -3),
    "faqs" => [],
    "endings" => [],
    "numbers" => []
  ];

  # faqs
  $faqs = Fetch::get("faqs", $survey["id"], "survey_id", "order_num");
  foreach ($faqs as $faq) {
    $f = [
      "faq_id" => $faq["id"],
      "voice" => $faq["voice_file"],
      "options" => []
    ];
    $options = Fetch::get("options", $faq["id"], "faq_id");
    foreach($options as $option) {
      $next_type = $option["next_ending_id"] ? "ending" : "faq";
      $next_id = $next_type === "ending" ? $option["next_ending_id"] : $option["next_faq_id"];
      $f["options"]["{$option["dial"]}"] = [
        "option_id" => $option["id"],
        "next_type" => $next_type,
        "next_id" => $next_id
      ];
    }
    $array["faqs"][] = $f;
  }

  # endings
  $endings = Fetch::get("endings", $survey["id"], "survey_id");
  foreach ($endings as $ending) {
    $array["endings"][] = [
      "ending_id" => $ending["id"],
      "voice" => $ending["voice_file"]
    ];
  }

  # numbers
  if ($reserve["number_list_id"]) {
    $numbers = Fetch::get("numbers", $reserve["number_list_id"], "number_list_id");
    foreach ($numbers as $number) {
      $same_number = Fetch::query("
        SELECT * FROM calls as c JOIN reserves as r ON c.reserve_id = r.id
        WHERE r.survey_id = {$survey["id"]} AND number = {$number["number"]}
      ", "fetch");
      if ($same_number) continue;
      $array["numbers"][] = $number["number"];
    }
  } else {
    $areas = Fetch::areasByReserveId($reserve["id"]);
    $numbers_length = round((strtotime($reserve["end"]) - strtotime($reserve["start"])) / 3600 * NUMBERS_PER_HOUR * $user["number_of_lines"]);

    foreach ($areas as $area) {
      $stations = Fetch::get("stations", $area["id"], "area_id");
      foreach ($stations as $station) {
        $sql = "SELECT MAX(c.number) FROM calls as c
                JOIN reserves as r ON c.reserve_id = r.id
                WHERE r.survey_id = {$survey["id"]}
                AND c.number LIKE '{$station["prefix"]}%'";
        $last_n56789 = substr(str_replace("-", "", Fetch::query($sql, "fetchColumn")), 7, 12);

        while (true) {
          $n56789_int = intval($last_n56789) + 1;
          if ($n56789_int > 99999) break;

          $n56789 = sprintf('%05d', intval($last_n56789) + 1);
          $n5 = substr($n56789, 0, 1);
          $n6789 = substr($n56789, 1, 4);
          $number = "{$station["prefix"]}{$n5}-{$n6789}";
          
          $array["numbers"][] = $number;
          $last_n56789 = $n56789;

          if (count($array["numbers"]) >= $numbers_length) break 3;
        }
      }
    }
  }

  $json = json_encode($array, JSON_PRETTY_PRINT);
  return [$json, $file_path];
}

$pdo = new_pdo();

if (isset($argv[1])) {
  $date = $argv[1];
  if (!preg_match("/^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[12][0-9]|3[01])$/", $date)) {
    DB::insert("gen_reserve_log", [
      "status" => 2,
      "message" => "日付の形式が正しくありません"
    ]);
    exit("日付の形式が正しくありません");
  }
} else {
  $date = date("Y-m-d");
}

if (!$reserves = Fetch::get2("reserves", [["date", "=", $date]])) {
  DB::insert("gen_reserve_log", [
    "status" => 3,
    "message" => "予約がありません"
  ]);
  exit("予約がありません");
}

$i = 0;
foreach ($reserves as $reserve) {
  $survey = Fetch::find("surveys", $reserve["survey_id"]);
  $user = Fetch::find("users", $survey["user_id"]);
  $areas = Fetch::areasByReserveId($reserve["id"]);
  if ($user["status"] === 2 || !$areas) continue;

  [$json, $file_path] = gen_reserve_info($reserve);

  DB::update("reserves", $reserve["id"], [
    "status" => "1",
    "reserve_file" => basename($file_path)
  ]);
  file_put_contents($file_path, $json);

  DB::insert("gen_reserve_log", [
    "reserve_id" => $reserve["id"],
    "status" => 1,
    "message" => "成功"
  ]);
  $i++;
}

echo "{$i}件の予約情報ファイルを生成しました";
