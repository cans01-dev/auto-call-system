<?php

require "../config.php";
require "../models/Fetch.php";
require "../models/DB.php";
require "../functions.php";
require "./api_functions.php";

$pdo = new_pdo();

if ($_SERVER["REQUEST_METHOD"] === "POST") {
  if (authenticate()) {
    if ($file_path = upload_file($_FILES["file"])) {
      $array = json_decode(file_get_contents($file_path), true);
      $reserve = Fetch::find("reserves", $array["id"]);
      $pdo->beginTransaction();
      foreach ($array["calls"] as $call) {
        echo <<<EOL
        [call]
        reserve_id => {$reserve["id"]},
        number => {$call["number"]},
        status => {$call["status"]},
        duration => {$call["duration"]},
        time => {$call["time"]}
        <br>
        EOL;

        DB::insert("calls", [
          "reserve_id" => $reserve["id"],
          "number" => $call["number"],
          "status" => $call["status"],
          "duration" => $call["duration"],
          "time" => $call["time"]
        ]);
        $call_id = DB::lastInsertId();

        foreach ($call["answers"] as $answer) {
          if (isset($answer["faq_id"])) { // Answersにエンディングを含めるのか？
            DB::insert("answers", [
              "call_id" => $call_id,
              "faq_id" => $answer["faq_id"],
              "option_id" => $answer["option_id"]
            ]);
            echo <<<EOL
            - [answer]
            call_id => {$call_id},
            faq_id => {$answer["faq_id"]},
            option_id => {$answer["option_id"]}
            <br>
            EOL;
          }
        }
      }
      $pdo->commit();
      echo "completed!!";
    } else {
      echo "ファイルのアップロードに失敗しました";
    }
  } else {
    echo "認証情報が不正";
  }
}