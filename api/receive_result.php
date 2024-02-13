<?php

require "../config.php";
require "../models/Fetch.php";
require "../models/DB.php";
require "../functions.php";
require "./api_functions.php";

$pdo = new_pdo();

if ($_SERVER["REQUEST_METHOD"] === "POST") {
  if (!authenticate("admin", "test", $_SERVER["HTTP_AUTHORIZATION"])) error_response("Authentication failed");
  if (!$file_path = upload_file($_FILES["file"])) exit();
  $array = json_decode(file_get_contents($file_path), true);

  $reserve = Fetch::find("reserves", $array["id"]);

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

  header("200 OK");
  exit(json_encode(["message" => "success"]));
}