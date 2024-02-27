<?php 

function survey($vars) {
  $survey_id = $vars["id"];
  $month = $_GET["month"] ?? date("n");
  $year = $_GET["year"] ?? date("Y");

  $survey = Fetch::find("surveys", $survey_id);
  $survey["endings"] = Fetch::get("endings", $survey["id"], "survey_id");
  $survey["faqs"] = Fetch::get("faqs", $survey["id"], "survey_id", "order_num");
  $survey["reserves"] = Fetch::reservesBySurveyIdAndYearMonth($survey["id"], $month, $year);
  $survey["favorites"] = Fetch::get("favorites", $survey["id"], "survey_id");
  if (Auth::user()["status"] !== 1) {
    if (!Allow::survey($survey)) abort(403);
  }

  # calendar
  $schedules = [];
  foreach ($survey["reserves"] as $reserve) {
    $reserve["areas"] = Fetch::areasByReserveId($reserve["id"]);
    $ts = strtotime($reserve["date"]);
    $schedules[date("d", $ts)] = $reserve;
  }
  $calendar = new Calendar($month, $year, $schedules);

  # area
  $sql = "SELECT DISTINCT a.title, a.id FROM areas as a
          JOIN reserves_areas as ra ON a.id = ra.area_id
          JOIN reserves as r ON ra.reserve_id = r.id
          WHERE r.survey_id = {$survey_id}";
  $areas = Fetch::query($sql, "fetchAll");
  foreach ($areas as $key => $area) {
    $stations = Fetch::get("stations", $area["id"], "area_id");
    $areas[$key]["all_numbers"] = count($stations) * 10000;
    $areas[$key]["called_numbers"] = 0;
    $areas[$key]["responsed_numbers"] = 0;
    foreach ($stations as $station) {
      $sql = "SELECT COUNT(*) FROM calls as c JOIN reserves as r ON c.reserve_id = r.id
              WHERE r.survey_id = {$survey_id} AND number LIKE '{$station["prefix"]}%'";
      $areas[$key]["called_numbers"] += Fetch::query($sql, "fetchColumn");

      $sql = "SELECT COUNT(*) FROM calls as c JOIN reserves as r ON c.reserve_id = r.id
              WHERE r.survey_id = {$survey_id} AND number LIKE '{$station["prefix"]}%' AND c.status = 1";
      $areas[$key]["responsed_numbers"] += Fetch::query($sql, "fetchColumn");
    }
    $areas[$key]["progress_rate"] = $areas[$key]["called_numbers"] / $areas[$key]["all_numbers"];
    if ($areas[$key]["called_numbers"]) {
      $areas[$key]["response_rate"] = $areas[$key]["responsed_numbers"] / $areas[$key]["called_numbers"];
    } else {
      $areas[$key]["response_rate"] = 0;
    }
  }

  # billing
  $survey_reserves = Fetch::get("reserves", $survey_id, "survey_id");
  $months = [];
  foreach ($survey_reserves as $reserve) {
    $month = date("Y-m", strtotime($reserve["date"]));
    if (!in_array($month, $months)) {
      $months[] = $month;
    }
  }
  foreach ($months as $month) {
    $ts = strtotime($month."-01");
    $month = date("m", $ts);
    $year = date("Y", $ts);
    $sql = "SELECT SUM(c.duration) FROM calls as c JOIN reserves as r
            WHERE r.survey_id = {$survey["id"]} AND MONTH(r.date) = {$month} AND YEAR(r.date) = {$year}";
    $total_duration = Fetch::query($sql, "fetchColumn");
    $survey["billings"][] = [
      "timestamp" => $ts,
      "total_duration" => $total_duration
    ];
  }

  require_once "./views/pages/survey.php";
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
  file_put_contents(dirname(__DIR__)."/storage/outputs/{$file_name}", text_to_speech($_POST["greeting"], $survey["voice_name"]));
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
  $ending_id = DB::lastInsertId();
  $file_name = uniqid("e{$ending_id}_") . ".wav";
  file_put_contents(dirname(__DIR__)."/storage/outputs/{$file_name}", text_to_speech($_POST["text"], $survey["voice_name"]));
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
  file_put_contents(dirname(__DIR__)."/storage/outputs/{$file_name}", text_to_speech($_POST["text"], $survey["voice_name"]));
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