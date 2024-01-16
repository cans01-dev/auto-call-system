<?php 

function index() {
  setPageTitle('トップ');
  require_once './views/pages/top.php';
}

function login() {
  setPageTitle('ログイン');
  Session::set("token", bin2hex(random_bytes(32)));
  require_once "./views/pages/login.php";
}

function loginPost() {
  if ($_POST["token"] === Session::get("token")) {
    if (Auth::login($_POST["email"], $_POST["password"])) {
      toastMeg("success", "ログインしました");
      redirect("/");
    } else {
      toastMeg("danger", "メールアドレスもしくはパスワードが異なります");
      redirect("/login");
    }
  } else {
    // トークンエラー
  }
}

function logout() {
  Auth::logout();
  toastMeg("success", "ログアウトしました");
  redirect("/login");
}

?>