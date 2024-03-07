<?php

require "../vendor/autoload.php";
require "../config.php";
require "../models/Fetch.php";
require "../models/DB.php";
require "../models/Mail.php";
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
  ]) && !@$_POST["ignore"]) {
    DB::insert("receive_result_log", [
      "reserve_id" => $reserve["id"],
      "status" => 3,
      "message" => "この結果ファイルは既に受信されています"
    ]);
    header("HTTP/1.1 422 Unprocessable Entity");
    exit();
  }

  DB::beginTransaction();
  try {
    foreach ($array["calls"] as $call) {
      DB::insert("calls", [
        "reserve_id" => $reserve["id"],
        "number" => $call["number"],
        "status" => $call["status"],
        "duration" => $call["duration"],
        "time" => $call["time"]
      ]);
      $call_id = DB::lastInsertId();
      
      if ($call["status"] === 1) {
        foreach ($call["answers"] as $answer) {
          if (isset($answer["option_id"])) {
            DB::insert("answers", [
              "call_id" => $call_id,
              "faq_id" => $answer["id"],
              "option_id" => $answer["option_id"]
            ]);
          }
        }
      }
    }
    DB::commit();    
  } catch (Exception $e) {
    DB::rollback();
    DB::insert("receive_result_log", [
      "reserve_id" => $reserve["id"],
      "status" => 3,
      "message" => "予期せぬエラー"
    ]);
    header("HTTP/1.1 500 Internal Server Error");
    exit();
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

  $survey = Fetch::find("surveys", $reserve["survey_id"]);
  $survey["url"] = url("/surveys/{$survey["id"]}");
  $user = Fetch::find("users", $survey["user_id"]);
  $user["send_emails"] = array_column(Fetch::get2("send_emails", [
    ["user_id", "=", $user["id"]],
    ["enabled", "=", 1],
  ]), "email");
  $user["send_emails"][] = $user["email"];
  $reserve["url"] = url("/reserves/{$reserve["id"]}");

  $mail = new Mail();
  $mail->setFrom('info@e-ivr.net', 'AutoCallシステム');
  foreach ($user["send_emails"] as $address) $mail->addAddress($address);

  $mail->isHTML(true);
  $mail->Subject = "{$reserve["date"]}結果";
  $mail->Body    = <<<EOL
    <h1>{$reserve["date"]}結果</h1>
    <h2>予約</h2>
    <dl>
      <dt>アンケート</dt>
      <dd><a href="{$survey["url"]}">{$survey["title"]}</a></dd>
      <dt>日付</dt>
      <dd><a href="{$reserve["date"]}">{$reserve["date"]}</a></dd>
      <dt>時間</dt>
      <dd>{$reserve["start"]} ~ {$reserve["end"]}</dd>
      <dt>url</dt>
      <dd>
        <a href="{$reserve["url"]}">ここから結果の詳細を確認できます</a><br>
        <b>URLのページからCSVファイルを生成・取得できます</b>
      </dd>
    </dl>
    <p></p>
  EOL;
  $mail->send();


  header("HTTP/1.1 200 OK");
}