<?php

require "../config.php";
require "../models/Fetch.php";
require "../models/DB.php";
require "../functions.php";

try {
	$pdo = new PDO(
		DB_PREFIX."dbname=".DB_NAME.";host=".DB_HOST,
		DB_USERNAME,
		DB_PASSWORD
	);
} catch (PDOException $e) {
	exit($e->getMessage());
}

function authenticate() {
  [$username, $password] = explode(":", base64_decode(substr($_SERVER["HTTP_AUTHORIZATION"], 6)));
  return $username === "admin" && $password === "test";
}

function upload_file($file) {
  if ($file) {
    if (is_uploaded_file($file["tmp_name"])) {
      $file_path = dirname(__DIR__)."/storage/uploads/{$file["name"]}";
      if (move_uploaded_file($file["tmp_name"], $file_path)) {
        return $file_path;
      }
    }
  }
  return false;
}


if (authenticate()) {
  if ($file_path = upload_file($_FILES["file"])) {
    $array = json_decode(file_get_contents($file_path), true);
    
    $reserve = Fetch::find("reserves", $array["id"]);
    
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
  } else {
    echo "ファイルのアップロードに失敗しました";
    abort(500);
  }
} else {
  echo "認証情報が不正";
  abort(403);
}
exit();
