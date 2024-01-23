<?php

function index() {
  redirect("/home");
}

function home() {
  $surveys = Fetch::surveysByUserId(Auth::user()["id"]);
  require_once "./views/pages/home.php";
}

function login() {
  require_once "./views/pages/login.php";
}

function account() {
  $sendEmails = Fetch::sendEmailsByUserId(Auth::user()["id"]);
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
  $survey = Fetch::find("surveys", $id);
  $faqs = Fetch::faqsBySurveyId($survey["id"]);
  if ($survey["user_id"] !== Auth::user()["id"]) abort(403);

  $month = $_GET["month"] ?? date("n");
  $year = $_GET["year"] ?? date("Y");

  $schedulesSample = [
    12 => ["text" => "vavdavdavdvad", "status" => 1],
    14 => ["text" => "vavdavdavdvad", "status" => 1],
    25 => ["text" => "vavdavdavdvad", "status" => 0]
  ];

  $calendar = new Calendar($month, $year, $schedulesSample);
  $current = $calendar->getCurrent();
  $prev = $calendar->getPrev();
  $next = $calendar->getNext();

  require_once "./views/pages/survey.php";
}

function faq($vars) {
  $id = $vars["id"];
  $faq = Fetch::find("faqs", $id);
  $survey = Fetch::find("surveys", $faq["survey_id"]);
  $options = Fetch::optionsByFaqId($faq["id"]);
  if ($survey["user_id"] !== Auth::user()["id"]) abort(403);

  require_once "./views/pages/faq.php";
}

function option($vars) {
  $id = $vars["id"];
  $option = Fetch::find("options", $id);
  require_once "./views/pages/option.php";
}

function reserve() {
  require_once "./views/pages/reserve.php";
}

function result($vars) {
  ["reserveId" => $reserveId] = $vars;

  require_once "./views/pages/result.php";
}

// function call($vars) {
//   require_once "./views/pages/call.php.php";
// }

function setting() {
  require_once "./views/pages/setting.php";
}