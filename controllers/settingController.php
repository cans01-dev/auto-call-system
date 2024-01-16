<?php 

function settings($vars) {
  $month = $_GET["month"] ?? date("n");
  $year = $_GET["year"] ?? date("Y");

  $calendar = new Calendar($month, $year);
  $current = $calendar->getCurrent();
  $prev = $calendar->getPrev();
  $next = $calendar->getNext();

  require_once "./views/pages/setting/settings.php";
}

function settingsCreate() {
  require_once "./views/pages/setting/settingsCreate.php";
}

function setting() {
  require_once "./views/pages/setting/setting.php";
}


?>