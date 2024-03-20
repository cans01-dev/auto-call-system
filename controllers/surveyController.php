<?php 

function survey($vars) {
  $survey = Fetch::find("surveys", $vars["id"]);
  $survey["endings"] = Fetch::get("endings", $survey["id"], "survey_id");
  $survey["faqs"] = Fetch::get("faqs", $survey["id"], "survey_id", "order_num");
  if (Auth::user()["status"] !== 1) if (!Allow::survey($survey)) abort(403);

  require_once "./views/pages/survey.php";
}

function surveyAsset($vars) {
  $survey = Fetch::find("surveys", $vars["id"]);

  $survey["favorites"] = Fetch::get("favorites", $survey["id"], "survey_id");

  $survey["areas"] = Fetch::get("areas", $survey["id"], "survey_id");
  foreach ($survey["areas"] as $k => $myArea) $survey["areas"][$k]["stations"]
    = Fetch::query("SELECT * FROM stations WHERE area_id = {$myArea["id"]}", "fetchAll");

  $survey["number_lists"] = Fetch::get("number_lists", $survey["id"], "survey_id");
  foreach ($survey["number_lists"] as $k => $number_list) $survey["number_lists"][$k]["numbers"]
    = Fetch::query("SELECT * FROM numbers WHERE number_list_id = {$number_list["id"]}", "fetchAll");

  require_once "./views/pages/asset.php";
  Session::set("referer", ["link" => $_SERVER["REQUEST_URI"], "text" => "アセット"]);
}

function calendar($vars) {
  $month = $_GET["month"] ?? date("n");
  $year = $_GET["year"] ?? date("Y");
  $c_mode = @$_GET["calendar"] ?? "month";

  $survey = Fetch::find("surveys", $vars["id"]);
  $survey["reserves"] = Fetch::query(
    "SELECT *, r.status as status, r.id as id FROM reserves as r
    WHERE r.survey_id = {$survey["id"]}
    AND MONTH(r.date) = {$month}
    AND YEAR(r.date) = {$year}
    ORDER BY r.date DESC",
    "fetchAll"
  );
  $survey["favorites"] = Fetch::get("favorites", $survey["id"], "survey_id");
  if (Auth::user()["status"] !== 1) if (!Allow::survey($survey)) abort(403);

  $schedules = [];
  foreach ($survey["reserves"] as $reserve) {
    $reserve["areas"] = Fetch::areasByReserveId($reserve["id"]);
    $ts = strtotime($reserve["date"]);
    $schedules[date("d", $ts)] = $reserve;
  }
  $calendar = new Calendar($month, $year, $schedules);

  require_once "./views/pages/calendar.php";
  Session::set("referer", ["link" => $_SERVER["REQUEST_URI"], "text" => "カレンダー {$year}年 {$month}月"]);
}

function storeSurvey() {
  $surveys = Fetch::find2("surveys", [["user_id", "=" , Auth::user()["id"]]]);
  if ($surveys && count($surveys) > 1) {
    Session::set("toast", ["danger", "現在のユーザーでは一つ以上のアンケートを作成することはできません"]);
    back();
  }
  DB::insert("surveys", [
    "user_id" => Auth::user()["id"],
    "title" => $_POST["title"],
    "note" => $_POST["note"],
    "voice_name" => VOICES[0]["name"]
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
    "note" => $_POST["note"],
    "voice_name" => $_POST["voice_name"],
    "success_ending_id" => $_POST["success_ending_id"]
  ]);
  Session::set("toast", ["success", "アンケートの設定を変更しました"]);
  back();
}

function updateGreeting($vars) {
  $id = $vars["id"];
  $survey = Fetch::find("surveys", $id);
  if (!Allow::survey($survey)) abort(403);
  $file_name = uniqid("s{$survey["id"]}g_") . ".wav";
  file_put_contents(user_dir($file_name, $survey["user_id"]), text_to_speech($_POST["greeting"], $survey["voice_name"]));
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
    "survey_id" => $survey["id"],
    "title" => $_POST["title"],
    "text" => $_POST["text"]
  ]);
  $ending_id = DB::lastInsertId();
  $file_name = uniqid("e{$ending_id}_") . ".wav";
  file_put_contents(user_dir($file_name, $survey["user_id"]), text_to_speech($_POST["text"], $survey["voice_name"]));
  DB::update("endings", $ending_id, [
    "voice_file" => $file_name
  ]);
  Session::set("toast", ["success", "エンディングを作成しました"]);
  back();
}

function updateEnding($vars) {
  $id = $vars["id"];
  $ending = Fetch::find("endings", $id);
  $survey = Fetch::find("surveys", $ending["survey_id"]);
  if (!Allow::survey($survey)) abort(403);
  $file_name = uniqid("e{$ending["id"]}_") . ".wav";
  file_put_contents(user_dir($file_name, $survey["user_id"]), text_to_speech($_POST["text"], $survey["voice_name"]));
  DB::update("endings", $id, [
    "title" => $_POST["title"],
    "text" => $_POST["text"],
    "voice_file" => $file_name
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

function allVoiceFileReGen($vars) {
  $survey_id = $vars["id"];
  $survey = Fetch::find("surveys", $survey_id);
  if (!Allow::survey($survey)) abort(403);

  avfrg($survey);

  Session::set("toast", ["success", "全ての音声ファイルを更新しました"]);
  back();
}