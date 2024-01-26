<?php 

function storeFavorite() {
  DB::insert("favorites", [
    "survey_id" => $_POST["survey_id"],
    "title" => $_POST["title"],
    "color" => $_POST["color"],
    "start" => $_POST["start"],
    "end" => $_POST["end"]
  ]);
  $id = DB::lastInsertId();
  Session::set("toast", ["success", "お気に入り設定を追加しました"]);
  redirect("/favorites/{$id}");
}

function updateFavorite($vars) {
  $id = $vars["id"];
  $favorite = Fetch::find("favorites", $id);
  $survey = Fetch::find("surveys", $favorite["survey_id"]);
  DB::update("favorites", $id, [
    "title" => $_POST["title"],
    "color" => $_POST["color"],
    "start" => $_POST["start"],
    "end" => $_POST["end"],
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
  DB::insert("favorites_areas", [
    "favorite_id" => $_POST["favorite_id"],
    "area_id" => $_POST["area_id"]
  ]);
  Session::set("toast", ["success", "エリアを追加しました"]);
  redirect("/favorites/{$_POST["favorite_id"]}#area");
}

function deleteFavoritesAreas($vars) {
  $id = $vars["id"];
  $ra = Fetch::find("favorites_areas", $id);
  $ra["favorite"] = Fetch::find("favorites", $ra["favorite_id"]);
  global $pdo;
  $stmt = $pdo->prepare("DELETE FROM favorites_areas WHERE id = :id");
  $stmt->execute([
    ":id" => $id,
  ]);
  Session::set("toast", ["danger", "エリアを削除しました"]);
  redirect("/favorites/{$ra["favorite"]["id"]}#area");
}