<?php 

function storeSurvey() {
  DB::insert("surveys", [
    "user_id" => Auth::user()["id"],
    "title" => $_POST["title"],
    "note" => $_POST["note"]
  ]);
  $id = DB::lastInsertId();
  Session::set("toast", ["success", "アンケートを新規作成しました"]);
  redirect("/surveys/{$id}");
}

function updateSurvey($vars) {
  $id = $vars["id"];
  $survey = Fetch::find("surveys", $id);
  if ($survey["user_id"] !== Auth::user()["id"]) abort(403);
  DB::update("surveys", $id, [
    "title" => $_POST["title"],
    "note" => $_POST["note"]
  ]);
  Session::set("toast", ["success", "アンケートの設定を変更しました"]);
  back();
}

function updateGreeting($vars) {
  $id = $vars["id"];
  $survey = Fetch::find("surveys", $id);
  if ($survey["user_id"] !== Auth::user()["id"]) abort(403);
  DB::update("surveys", $id, [
    "greeting" => $_POST["greeting"]
  ]);
  Session::set("toast", ["success", "アンケートのグリーティングを変更しました"]);
  back();
}

function updateEnding($vars) {
  $id = $vars["id"];
  $survey = Fetch::find("surveys", $id);
  if ($survey["user_id"] !== Auth::user()["id"]) abort(403);
  DB::update("surveys", $id, [
    "ending" => $_POST["ending"]
  ]);
  Session::set("toast", ["success", "アンケートのエンディングを変更しました"]);
  back();
}