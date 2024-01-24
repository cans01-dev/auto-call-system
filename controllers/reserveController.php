<?php 

function storeReserve() {
  global $pdo;
  $stmt = $pdo->prepare("INSERT INTO reserves (survey_id, date, start, end) VALUES (:survey_id, :date, :start, :end)");
  $stmt->execute([
    ":survey_id" => $_POST["survey_id"],
    ":date" => $_POST["date"],
    ":start" => DEFAULT_START_TIME,
    ":end" => DEFAULT_END_TIME
  ]);
  Session::set("toast", ["success", "予約を作成しました"]);
  $id = $pdo->lastInsertId();
  redirect("/reserves/{$id}");
}


