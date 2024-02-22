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

function sendContact() {
  // お問い合わせの送信 or DB保存の処理

  Session::set("toast", ["warning", "！この機能はまだ実装されていません"]);
  back();
}

function genReserve() {
  $date = $_POST["date"];
  if (MODE === MODE_DEVELOPMENT) {
    $res = exec("php api/gen_reserve_info.php {$date}");
  } else {
    $res = exec("php8.2 ../api/gen_reserve_info.php {$date}");
  }
  Session::set("toast", ["info", $res]);
  back();
}