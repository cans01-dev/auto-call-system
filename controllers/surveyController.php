<?php 

function surveys() {
  require_once "./views/pages/surveys.php";
}

function survey($vars) {
  ["surveyId" => $surveyId] = $vars;

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

function surveysCreate() {
  require_once "./views/pages/surveysCreate.php";
}