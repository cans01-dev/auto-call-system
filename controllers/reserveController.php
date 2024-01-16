<?php 

function reserves($vars) {
  $month = $_GET["month"] ?? date("n");
  $year = $_GET["year"] ?? date("Y");

  $calendar = new Calendar($month, $year);
  $current = $calendar->getCurrent();
  $prev = $calendar->getPrev();
  $next = $calendar->getNext();

  require_once "./views/pages/reserve/reserves.php";
}

function reservesCreate() {
  require_once "./views/pages/reserve/reservesCreate.php";
}

function reserve() {
  require_once "./views/pages/reserve/reserve.php";
}


?>