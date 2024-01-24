<?php

require "../config.php";

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

# グルーピング
$areas = [];
foreach ($stations as $station) {
  $title =  mb_convert_encoding($station[1], 'UTF-8', 'SJIS');
  if (array_search($title, $areas) === false) {
    array_push($areas, $title);
  }
}

$pdo->beginTransaction();
foreach ($areas as $title) {
  $stmt = $pdo->prepare("INSERT INTO areas (title) VALUES (:title)");
  $stmt->execute([
    ":title" => $title,
  ]);
  
  $id = $pdo->lastInsertId();
  echo "{$id} => {$title}" . PHP_EOL;
}
$pdo->commit();