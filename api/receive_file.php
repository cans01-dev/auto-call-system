<?php

require "../config.php";
require "../models/Fetch.php";
require "../models/DB.php";
require "../functions.php";
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

if (authenticate()) {
  if ($file_path = upload_file($_FILES["file"])) {
    try {
      $array = json_decode(file_get_contents($file_path), true);
      $reserve = Fetch::find("reserves", $array["id"]);
      $pdo->beginTransaction();
      foreach ($array["calls"] as $call) {
        DB::insert("calls", [
          "reserve_id" => $reserve["id"],
          "number" => $call["number"],
          "status" => $call["status"],
          // "last_label" => "stage3",
          "duration" => hour_to_sec($call["duration"]),
          "time" => $call["time"]
        ]);
        $call_id = DB::lastInsertId();
        foreach ($call["answers"] as $answer) {
          DB::insert("answers", [
            "call_id" => $call_id,
            "faq_id" => $answer["id"],
            "option_id" => $answer["option_id"]
          ]);
        }
      }
      $pdo->commit();
      echo "completed!!";
    } catch (Exception $e) {
      $pdo->rollBack();
      echo $e;
    }
  } else {
    echo "ファイルのアップロードに失敗しました";
  }
} else {
  echo "認証情報が不正";
}