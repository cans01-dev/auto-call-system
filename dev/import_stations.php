<?php

require "../config.php";
require "../models/Fetch.php";

# DB接続
try {
	$pdo = new PDO(DB_DSN, DB_USERNAME, DB_PASSWORD);
} catch (PDOException $e) {
	exit($e->getMessage());
}

# ファイル読み込み
$fp = fopen("stations.csv", "r");
$stations = [];
while ($line = fgetcsv($fp)) {
  array_push($stations, $line);
}
fclose($fp);

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
  
  $id = $pdo->lastInsertId();
  echo "{$area["id"]} => {$title} => {$prefix}" . PHP_EOL;
}
$pdo->commit();