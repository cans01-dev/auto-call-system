<?php 

function surveys() {
  require_once "./views/pages/survey/surveys.php";
}

function survey($vars) {
  ["surveyId" => $surveyId] = $vars;

  require_once "./views/pages/survey/survey.php";
}

function surveysCreate() {
  require_once "./views/pages/survey/surveysCreate.php";
}

?>