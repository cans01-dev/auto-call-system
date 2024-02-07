<?php 

function storeSurvey() {
  $surveys = Fetch::find2("surveys", [["user_id", "=" , Auth::user()["id"]]]);
  if (count($surveys) > 1) {
    Session::set("toast", ["danger", "現在のユーザーでは一つ以上のアンケートを作成することはできません"]);
    back();
  }
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
  if (!Allow::survey($survey)) abort(403);
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
  if (!Allow::survey($survey)) abort(403);
  $file_name = "s{$survey["id"]}_greeting.wav";
  file_put_contents(dirname(__DIR__)."/storage/outputs/{$file_name}", text_to_speech($_POST["greeting"]));
  // 2/7 👆質問、エンディングを更新したら音声ファイルを生成してファイル名をDBに保存するとこから
  DB::update("surveys", $id, [
    "greeting" => $_POST["greeting"],
    "greeting_voice_file" => $file_name
  ]);
  Session::set("toast", ["success", "アンケートのグリーティングを変更しました"]);
  back();
}

function storeEnding() {
  $survey = Fetch::find("surveys", $_POST["survey_id"]);
  if (!Allow::survey($survey)) abort(403);
  DB::insert("endings", [
    "survey_id" => $_POST["survey_id"],
    "title" => $_POST["title"],
    "text" => $_POST["text"]
  ]);
  Session::set("toast", ["success", "エンディングを作成しました"]);
  back();
}

function updateEnding($vars) {
  $id = $vars["id"];
  $ending = Fetch::find("endings", $id);
  if (!Allow::ending($ending)) abort(403);
  DB::update("endings", $id, [
    "title" => $_POST["title"],
    "text" => $_POST["text"]
  ]);
  Session::set("toast", ["success", "エンディングのテキストを変更しました"]);
  back();
}

function deleteEnding($vars) {
  $id = $vars["id"];
  $ending = Fetch::find("endings", $id);
  if (!Allow::ending($ending)) abort(403);
  DB::delete("endings", $id);
  Session::set("toast", ["success", "エンディングを削除しました"]);
  back();
}