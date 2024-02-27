<?php 

function favorite($vars) {
  $id = $vars["id"];
  $favorite = Fetch::find("favorites", $id);
  $favorite["areas"] = Fetch::areasByFavoriteId($favorite["id"]);
  $survey = Fetch::find("surveys", $favorite["survey_id"]);
  if (!Allow::favorite($favorite)) abort(403);

  require_once "./views/pages/favorite.php";
}

function storeFavorite() {
  $survey = Fetch::find("surveys", $_POST["survey_id"]);
  if (!Allow::survey($survey)) abort(403);
  if (strtotime($_POST["start"]) + 3600 > strtotime($_POST["end"])) {
    Session::set("toast", ["danger", "エラー！<br>開始・終了時間は".(MIN_INTERVAL / 3600)."時間以上の間隔をあけてください"]);
    redirect("/surveys/{$_POST["survey_id"]}#calendar");
  }
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
  if (!Allow::favorite($favorite)) abort(403);
  if (strtotime($_POST["start"]) + 3600 > strtotime($_POST["end"])) {
    Session::set("toast", ["danger", "エラー！<br>開始・終了時間は".(MIN_INTERVAL / 3600)."時間以上の間隔をあけてください"]);
    back();
  }
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
  if (!Allow::favorite($favorite)) abort(403);
  DB::delete("favorites", $id);
  Session::set("toast", ["info", "お気に入り登録を削除しました"]);
  redirect("/surveys/{$favorite["survey_id"]}");
}

function storeFavoritesAreasByWord() {
  $favorite = Fetch::find("favorites", $_POST["favorite_id"]);
  if (!Allow::favorite($favorite)) abort(403);
  $areas = Fetch::get2("areas", [["title", "LIKE", "%{$_POST["word"]}%"]]);
  $count = 0;
  foreach ($areas as $area) {
    if (!Fetch::find2("favorites_areas", [
      ["favorite_id", "=", $_POST["favorite_id"]],
      ["area_id", "=", $area["id"]],
    ])) {
      $count++;
      DB::insert("favorites_areas", [
        "favorite_id" => $_POST["favorite_id"],
        "area_id" => $area["id"]
      ]);
    }
  }
  Session::set("toast", ["success", "{$count}件のエリアを追加しました"]);
  redirect("/favorites/{$_POST["favorite_id"]}#area");
}

function storeFavoritesAreas() {
  $favorite = Fetch::find("favorites", $_POST["favorite_id"]);
  if (!Allow::favorite($favorite)) abort(403);
  DB::insert("favorites_areas", [
    "favorite_id" => $_POST["favorite_id"],
    "area_id" => $_POST["area_id"]
  ]);
  Session::set("toast", ["success", "エリアを追加しました"]);
  redirect("/favorites/{$_POST["favorite_id"]}#area");
}

function deleteFavoritesAreas($vars) {
  $id = $vars["id"];
  $fa = Fetch::find("favorites_areas", $id);
  if (!Allow::fa($fa)) abort(403);
  DB::delete("favorites_areas", $id);
  Session::set("toast", ["danger", "エリアを削除しました"]);
  redirect("/favorites/{$fa["favorite_id"]}#area");
}