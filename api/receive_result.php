<?php

require "../config.php";
require "../models/Fetch.php";
require "../models/DB.php";
require "../functions.php";
require "./functions.php";

$pdo = new_pdo();

if ($_SERVER["REQUEST_METHOD"] === "POST") {
  if (!$file_path = upload_file($_FILES["file"])) {
    DB::insert("receive_result_log", [
      "status" => 2,
      "message" => "ファイルのアップロードに失敗"
    ]);
    header("HTTP/1.1 500 Internal Server Error");
    exit();
  }

  $array = json_decode(file_get_contents($file_path), true);

  $reserve = Fetch::find("reserves", $array["id"]);

  if (Fetch::find2("receive_result_log", [
    ["reserve_id", "=", $reserve["id"]],
    ["status", "=", 1]
  ])) {
    DB::insert("receive_result_log", [
      "reserve_id" => $reserve["id"],
      "status" => 3,
      "message" => "この結果ファイルは既に受信されています"
    ]);
    header("HTTP/1.1 500 Internal Server Error");
    exit();
  }

  foreach ($array["calls"] as $call) {
    DB::insert("calls", [
      "reserve_id" => $reserve["id"],
      "number" => $call["number"],
      "status" => $call["status"],
      "duration" => $call["duration"],
      "time" => $call["time"]
    ]);
    $call_id = DB::lastInsertId();

    foreach ($call["answers"] as $answer) {
      if (isset($answer["faq_id"])) {
        DB::insert("answers", [
          "call_id" => $call_id,
          "faq_id" => $answer["faq_id"],
          "option_id" => $answer["option_id"]
        ]);
      }
    }
  }

  DB::update("reserves", $reserve["id"], [
    "result_file" => basename($file_path),
    "status" => "4"
  ]);

  DB::insert("receive_result_log", [
    "reserve_id" => $reserve["id"],
    "status" => 1,
    "message" => "成功"
  ]);

  header("HTTP/1.1 200 OK");
}