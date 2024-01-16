<?php 

function results($vars) {
  ["serveyId" => $serveyId] = $vars;

  require_once "./views/pages/survey/result/results.php";
}

function result($vars) {
  ["reserveId" => $reserveId] = $vars;

  require_once "./views/pages/survey/result/result.php";
}

function call($vars) {
  ["reserveId" => $reserveId, "callId" => $callId] = $vars;

  require_once "./views/pages/survey/result/call.php.php";
}


?>