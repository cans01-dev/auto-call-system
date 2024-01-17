<?php 

function index() {
  require_once './views/pages/top.php';
}

function login() {
  Session::set("token", bin2hex(random_bytes(32)));
  require_once "./views/pages/login.php";
}

function loginPost() {
  if ($_POST["token"] === Session::get("token")) {
    if (Auth::login($_POST["email"], $_POST["password"])) {
      Session::set("toast", ["success", "ログインしました"]);
      redirect("/");
    } else {
      Session::set("toast", ["danger", "メールアドレスもしくはパスワードが異なります"]);
      redirect("/login");
    }
  } else {
    // トークンエラー
  }
}

function logout() {
  Auth::logout();
  Session::set("toast", ["success", "ログアウトしました"]);
  redirect("/login");
}

