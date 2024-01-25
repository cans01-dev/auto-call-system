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

function updateReserve($vars) {
  $id = $vars["id"];
  $reserve = Fetch::find("reserves", $id);
  $survey = Fetch::find("surveys", $reserve["survey_id"]);
  global $pdo;
  $stmt = $pdo->prepare("UPDATE reserves SET start = :start, end = :end");
  $stmt->execute([
    ":start" => $_POST["start"],
    ":end" => $_POST["end"]
  ]);
  Session::set("toast", ["success", "予約の基本設定を変更しました"]);
  back();
}

function deleteReserve($vars) {
  $id = $vars["id"];
  $reserve = Fetch::find("reserves", $id);
  $survey = Fetch::find("surveys", $reserve["survey_id"]);
  global $pdo;
  $stmt = $pdo->prepare("DELETE FROM reserves WHERE id = :id");
  $stmt->execute([
    ":id" => $id
  ]);
  Session::set("toast", ["success", "予約を削除しました"]);
  redirect("/surveys/{$survey["id"]}");
}

function storeReservesAreas() {
  global $pdo;
  $stmt = $pdo->prepare("INSERT INTO reserves_areas (reserve_id, area_id) VALUES (:reserve_id, :area_id)");
  $stmt->execute([
    ":reserve_id" => $_POST["reserve_id"],
    ":area_id" => $_POST["area_id"]
  ]);
  Session::set("toast", ["success", "エリアを追加しました"]);
  back();
}

function deleteReservesAreas($vars) {
  $id = $vars["id"];
  global $pdo;
  $stmt = $pdo->prepare("DELETE FROM reserves_areas WHERE id = :id");
  $stmt->execute([
    ":id" => $id,
  ]);
  Session::set("toast", ["danger", "エリアを削除しました"]);
  back();
}

