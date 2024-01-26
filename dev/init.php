<?php

/**
 * エリアと局番のデータをシーディングするファイル「php dev/init.php」で実行
 */

require __DIR__ . "/../config.php";
require __DIR__ . "/../models/Fetch.php";

# DB接続
try {
	$pdo = new PDO(DB_DSN, DB_USERNAME, DB_PASSWORD);
} catch (PDOException $e) {
	exit($e->getMessage());
}

# ファイル読み込み
$fp = fopen(__DIR__ . "/stations.csv", "r");
$stations = [];
while ($line = fgetcsv($fp)) {
  array_push($stations, $line);
}
fclose($fp);

# グルーピング
$areas = [];
foreach ($stations as $station) {
  $title =  mb_convert_encoding($station[1], 'UTF-8', 'SJIS');
  if (array_search($title, $areas) === false) {
    array_push($areas, $title);
  }
}

# エリアデータ挿入
$pdo->beginTransaction();
foreach ($areas as $title) {
  $stmt = $pdo->prepare("INSERT INTO areas (title) VALUES (:title)");
  $stmt->execute([
    ":title" => $title,
  ]);
  $id = DB::lastInsertId();
  echo "{$id} => {$title}" . PHP_EOL;
}
$pdo->commit();

# 局番データ挿入
$pdo->beginTransaction();
foreach ($stations as $station) {
  $title = mb_convert_encoding($station[1], "UTF-8", "SJIS");
  $prefix = $station[0];
  $area = Fetch::find("areas", $title, "title");
  $stmt = $pdo->prepare("INSERT INTO stations (area_id, title, prefix) VALUES (:area_id, :title, :prefix)");
  $stmt->execute([
    ":area_id" => $area["id"],
    ":title" => $title,
    ":prefix" => $prefix,
  ]);
  $id = DB::lastInsertId();
  echo "{$area["id"]} => {$title} => {$prefix}" . PHP_EOL;
}
$pdo->commit();