<?php

# コマンドラインから実行
require dirname(__DIR__)."/vendor/autoload.php";
$dotenv = Dotenv\Dotenv::createImmutable(dirname(__DIR__))->load();

require dirname(__DIR__)."/config.php";
require dirname(__DIR__)."/models/Fetch.php";
require dirname(__DIR__)."/models/DB.php";
require dirname(__DIR__)."/functions.php";
require __DIR__."/functions.php";

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
