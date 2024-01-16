<?php 

function results($vars) {
  ["surveyId" => $surveyId] = $vars;

  require_once "./views/pages/result/results.php";
}

function result($vars) {
  ["reserveId" => $reserveId] = $vars;

  require_once "./views/pages/result/result.php";
}

function call($vars) {
  ["reserveId" => $reserveId, "callId" => $callId] = $vars;

  require_once "./views/pages/result/call.php.php";
}


?>