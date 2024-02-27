<?php

function index() {
  redirect("/home");
}

function home() {
  $survey = Fetch::find("surveys", Auth::user()["id"], "user_id");
  redirect("/surveys/{$survey["id"]}");

  // $surveys = Fetch::get("surveys", Auth::user()["id"], "user_id");
  // require_once "./views/pages/home.php";
}

function login() {
  require_once "./views/pages/login.php";
}

function support() {
  $parser = new Parsedown();
  $markdown = $parser->text(file_get_contents(dirname(__DIR__)."/assets/markdown/support.md"));
  require_once "./views/pages/support.php";
}
