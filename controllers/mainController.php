<?php
 
function loginPost() {
  if (Auth::attempt($_POST["email"], $_POST["password"])) {
    session_regenerate_id(true);
    Session::set("toast", ["success", "ログインしました"]);
    redirect("/");
  }
  Session::set("toast", ["danger", "メールアドレスもしくはパスワードが異なります"]);
  redirect("/login");
}

function logout() {
  Auth::logout();
  Session::set("toast", ["success", "ログアウトしました"]);
  redirect("/login");
}

