<?php

function index() {
  redirect("/home");
}

function home() {
  $surveys = Fetch::get("surveys", Auth::user()["id"], "user_id");
  require_once "./views/pages/home.php";
}

function login() {
  require_once "./views/pages/login.php";
}

function account() {
  $sendEmails = Fetch::get("send_emails", Auth::user()["id"], "user_id");
  require_once "./views/pages/account.php";
}

function sendEmail($vars) {
  $id = $vars["id"];
  $sendEmail = Fetch::find("send_emails", $id);
  if ($sendEmail["user_id"] !== Auth::user()["id"]) abort(403);
  require_once "./views/pages/sendEmail.php";
}

function survey($vars) {
  $survey_id = $vars["id"];
  $month = $_GET["month"] ?? date("n");
  $year = $_GET["year"] ?? date("Y");

  $survey = Fetch::find("surveys", $survey_id);
  $survey["endings"] = Fetch::get("endings", $survey["id"], "survey_id");
  $survey["faqs"] = Fetch::get("faqs", $survey["id"], "survey_id", "order_num");
  $survey["reserves"] = Fetch::reservesBySurveyIdAndYearMonth($survey["id"], $month, $year);
  $survey["favorites"] = Fetch::get("favorites", $survey["id"], "survey_id");

  if ($survey["user_id"] !== Auth::user()["id"]) abort(403);

  $schedules = [];
  foreach ($survey["reserves"] as $reserve) {
    $reserve["areas"] = Fetch::areasByReserveId($reserve["id"]);
    $ts = strtotime($reserve["date"]);
    $schedules[date("d", $ts)] = $reserve;
  }

  $calendar = new Calendar($month, $year, $schedules);

  global $pdo;
  $stmt = $pdo->prepare("SELECT * FROM areas WHERE id IN (
    SELECT area_id FROM reserves_areas WHERE reserve_id IN (
      SELECT id FROM reserves WHERE survey_id = :survey_id
    )
  )");
  $stmt->execute([":survey_id" => $survey_id]);
  $areas = $stmt->fetchAll(PDO::FETCH_ASSOC);

  foreach ($areas as $key => $area) {
    $stations = Fetch::get("stations", $area["id"], "area_id");
    $areas[$key]["all_numbers"] = count($stations) * 10000;
    $areas[$key]["called_numbers"] = 0;
    $areas[$key]["responsed_numbers"] = 0;
    foreach ($stations as $station) {
      $stmt = $pdo->prepare("SELECT * FROM calls WHERE reserve_id IN (
        SELECT id FROM reserves WHERE survey_id = :survey_id
      ) AND number LIKE :number");
      $stmt->execute([
        ":survey_id" => $survey_id,
        ":number" => "{$station["prefix"]}%"
      ]);
      $calls = $stmt->fetchAll(PDO::FETCH_ASSOC);
      $areas[$key]["called_numbers"] += count($calls);

      $stmt = $pdo->prepare("SELECT * FROM calls WHERE reserve_id IN (
        SELECT id FROM reserves WHERE survey_id = :survey_id
      ) AND number LIKE :number AND status = :status");
      $stmt->execute([
        ":survey_id" => $survey_id,
        ":number" => "{$station["prefix"]}%",
        ":status" => 1
      ]);
      $calls = $stmt->fetchAll(PDO::FETCH_ASSOC);
      $areas[$key]["responsed_numbers"] += count($calls);
    }
    $areas[$key]["progress_rate"] = $areas[$key]["called_numbers"] / $areas[$key]["all_numbers"];
    if ($areas[$key]["called_numbers"]) {
      $areas[$key]["response_rate"] = $areas[$key]["responsed_numbers"] / $areas[$key]["called_numbers"];
    } else {
      $areas[$key]["response_rate"] = 0;
    }
  }

  $tss = [];
  for ($i = 0; $i > -5; $i--) $tss[] = mktime(0, 0, 0, date("m") + $i, 1, date("Y"));

  foreach ($tss as $ts) {
    $reserves = Fetch::reservesBySurveyIdAndYearMonth($survey_id, date("m", $ts), date("Y", $ts));
    $calls = $reserves ? Fetch::callsByReserves($reserves) : [];
    
    $total_duration = $calls ? array_sum(array_column($calls, "duration")) : 0;
    $survey["billings"][] = [
      "timestamp" => $ts,
      "total_duration" => $total_duration
    ];
  }

  require_once "./views/pages/survey.php";
}

function faq($vars) {
  $id = $vars["id"];
  $faq = Fetch::find("faqs", $id);
  $faq["options"] = Fetch::get("options", $faq["id"], "faq_id", "dial");
  $survey = Fetch::find("surveys", $faq["survey_id"]);
  $survey["faqs"] = Fetch::get("faqs", $survey["id"], "survey_id", "order_num");
  foreach ($faq["options"] as $option) {
    if ($option["next_faq_id"]) {
      $index = array_search($option, $faq["options"]);
      $faq["options"][$index]["next_faq"] = Fetch::find("faqs", $option["next_faq_id"]);
    }
  }
  if ($survey["user_id"] !== Auth::user()["id"]) abort(403);

  require_once "./views/pages/faq.php";
}

function option($vars) {
  $id = $vars["id"];
  $option = Fetch::find("options", $id);
  $faq = Fetch::find("faqs", $option["faq_id"]);
  $survey = Fetch::find("surveys", $faq["survey_id"]);
  $survey["faqs"] = Fetch::get("faqs", $survey["id"], "survey_id");
  if ($survey["user_id"] !== Auth::user()["id"]) abort(403);
  require_once "./views/pages/option.php";
}

function reserve($vars) {
  $id = $vars["id"];
  $reserve = Fetch::find("reserves", $id);
  $reserve["areas"] = Fetch::areasByReserveId($reserve["id"]);
  $survey = Fetch::find("surveys", $reserve["survey_id"]);

  if ($survey["user_id"] !== Auth::user()["id"]) abort(403);
  require_once "./views/pages/reserve.php";
}

function favorite($vars) {
  $id = $vars["id"];
  $favorite = Fetch::find("favorites", $id);
  $favorite["areas"] = Fetch::areasByFavoriteId($favorite["id"]);
  $survey = Fetch::find("surveys", $favorite["survey_id"]);

  if ($survey["user_id"] !== Auth::user()["id"]) abort(403);
  require_once "./views/pages/favorite.php";
}

function result($vars) {
  $id = $vars["id"];
  $reserve = Fetch::find("reserves", $id);
  $selectedAreas = Fetch::areasByReserveId($reserve["id"]);
  $survey = Fetch::find("surveys", $reserve["survey_id"]);

  if ($survey["user_id"] !== Auth::user()["id"]) abort(403);
  require_once "./views/pages/result.php";
}

function call($vars) {
  require_once "./views/pages/call.php.php";
}