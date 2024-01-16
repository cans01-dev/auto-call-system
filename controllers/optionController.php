<?php 

function options($vars) {
  $month = $_GET["month"] ?? date("n");
  $year = $_GET["year"] ?? date("Y");

  $calendar = new Calendar($month, $year);
  $current = $calendar->getCurrent();
  $prev = $calendar->getPrev();
  $next = $calendar->getNext();

  require_once "./views/pages/option/options.php";
}

function optionsCreate() {
  require_once "./views/pages/option/optionsCreate.php";
}

function option() {
  require_once "./views/pages/option/option.php";
}


?>