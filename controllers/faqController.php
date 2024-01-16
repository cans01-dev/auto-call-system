<?php

function faqs($vars) {
  ["surveyId" => $surveyId] = $vars;

  require_once "./views/pages/faq/faqs.php";
}

function faqsCreate($vars) {
  ["surveyId" => $surveyId] = $vars;
  
  require_once "./views/pages/faq/faqsCreate.php";
}

function faq($vars) {
  ["surveyId" => $surveyId, "faqId" => $faqId] = $vars;

  require_once "./views/pages/faq/faq.php";
}

// function option() {
//   require_once "./views/pages/faq/option/option.php";
// }

// function optionCreate() {
//   require_once "./views/pages/faq/option/optionCreate.php";
// }


?>