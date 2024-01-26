<?php 

function storeFavorite() {
  global $pdo;
  $stmt = $pdo->prepare("INSERT INTO favorites (survey_id, title, color, start, end) VALUES (:survey_id, :title, :color, :start, :end)");
  $stmt->execute([
    ":survey_id" => $_POST["survey_id"],
    ":title" => $_POST["title"],
    ":color" => $_POST["color"],
    ":start" => $_POST["start"],
    ":end" => $_POST["end"]
  ]);
  $id = $pdo->lastInsertId();
  Session::set("toast", ["success", "お気に入り設定を追加しました"]);
  redirect("/favorites/{$id}");
}

function updateFavorite($vars) {
  $id = $vars["id"];
  $favorite = Fetch::find("favorites", $id);
  $survey = Fetch::find("surveys", $favorite["survey_id"]);
  global $pdo;
  $stmt = $pdo->prepare("UPDATE favorites SET title = :title, color = :color, start = :start, end = :end WHERE id = :id");
  $stmt->execute([
    ":title" => $_POST["title"],
    ":color" => $_POST["color"],
    ":start" => $_POST["start"],
    ":end" => $_POST["end"],
    ":id" => $id
  ]);
  Session::set("toast", ["success", "予約の基本設定を変更しました"]);
  back();
}

function deleteFavorite($vars) {
  $id = $vars["id"];
  $favorite = Fetch::find("favorites", $id);
  $survey = Fetch::find("surveys", $favorite["survey_id"]);
  global $pdo;
  $stmt = $pdo->prepare("DELETE FROM favorites WHERE id = :id");
  $stmt->execute([":id" => $id]);
  Session::set("toast", ["info", "お気に入り登録を削除しました"]);
  redirect("/surveys/{$survey["id"]}");
}

function storeFavoritesAreas() {
  global $pdo;
  $stmt = $pdo->prepare("INSERT INTO favorites_areas (favorite_id, area_id) VALUES (:favorite_id, :area_id)");
  $stmt->execute([
    ":favorite_id" => $_POST["favorite_id"],
    ":area_id" => $_POST["area_id"]
  ]);
  Session::set("toast", ["success", "エリアを追加しました"]);
  back();
}

function deleteFavoritesAreas($vars) {
  $id = $vars["id"];
  global $pdo;
  $stmt = $pdo->prepare("DELETE FROM favorites_areas WHERE id = :id");
  $stmt->execute([
    ":id" => $id,
  ]);
  Session::set("toast", ["danger", "エリアを削除しました"]);
  back();
}