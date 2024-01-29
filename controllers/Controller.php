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
  $id = $vars["id"];
  $month = $_GET["month"] ?? date("n");
  $year = $_GET["year"] ?? date("Y");

  $survey = Fetch::find("surveys", $id);
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
  $current = $calendar->getCurrent();
  $prev = $calendar->getPrev();
  $next = $calendar->getNext();

  require_once "./views/pages/survey.php";
}

function faq($vars) {
  $id = $vars["id"];
  $faq = Fetch::find("faqs", $id);
  $survey = Fetch::find("surveys", $faq["survey_id"]);
  $options = Fetch::get("options", $faq["id"], "faq_id", "dial");
  foreach ($options as $option) {
    if ($option["next_faq_id"]) {
      $index = array_search($option, $options);
      $options[$index]["next_faq"] = Fetch::find("faqs", $option["next_faq_id"]);
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
  $surveyFaqs = array_filter(Fetch::get("faqs", $survey["id"], "survey_id"), function($surveyFaq) use($faq) {
    return $surveyFaq["id"] !== $faq["id"];
  });
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