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
  $user = Auth::user();
  $user["send_emails"] = array_column(Fetch::get("send_emails", $user["id"], "user_id"), "email");
  $user["url"] = url("/users/{$user["id"]}");
  $contact["type"] = CONTACT_TYPE[$_POST["type"]]["text"];
  $contact["text"] = $_POST["text"];

  $mail = new Mail();
  $mail->setFrom('info@e-ivr.net', 'AutoCallシステム');
  $mail->addAddress("autocall@e-ivr.net");
  $mail->isHTML(true);
  $mail->Subject = 'お問い合わせフォーム';
  $mail->Body    = <<<EOL
    <h1>お問い合わせフォーム</h1>
    <h2>ユーザー</h2>
    <dl>
      <dt>email</dt>
      <dd>{$user["email"]}</dd>
      <dt>url</dt>
      <dd><a href="{$user["url"]}">{$user["url"]}</a></dd>
    </dl>
    <h2>お問い合わせ</h2>
    <dl>
      <dt>type</dt>
      <dd>{$contact["type"]}</dd>
      <dt>text</dt>
      <dd>{$contact["text"]}</dd>
    </dl>
  EOL;
  $mail->send();

  Session::set("toast", ["success", "お問い合わせを送信しました"]);
  back();
}

function genReserve() {
  $date = $_POST["date"];
  if (MODE === MODE_DEVELOPMENT) {
    $res = exec("php api/gen_reserve_info.php {$date}");
  } else {
    $res = exec("php8.2 api/gen_reserve_info.php {$date}");
  }
  Session::set("toast", ["info", $res]);
  back();
}