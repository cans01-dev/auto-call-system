<?php 

function result($vars) {
  ["reserveId" => $reserveId] = $vars;

  require_once "./views/pages/result.php";
}

function call($vars) {
  ["reserveId" => $reserveId, "callId" => $callId] = $vars;

  require_once "./views/pages/call.php.php";
}