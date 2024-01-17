<?php

function faq($vars) {
  ["faqId" => $faqId] = $vars;

  require_once "./views/pages/faq.php";
}