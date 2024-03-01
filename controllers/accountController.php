<?php 

function account($vars) {
  $user_id = $vars["id"];
  $user = Fetch::find("users", $user_id);
  $user["sendEmails"] = Fetch::get("send_emails", $user["id"], "user_id");
  if (Auth::user()["status"] !== 1) if (!Allow::user($user)) abort(403);
  
  require_once "./views/pages/account.php";
}

function sendEmail($vars) {
  $id = $vars["id"];
  $sendEmail = Fetch::find("send_emails", $id);
  if ($sendEmail["user_id"] !== Auth::user()["id"]) abort(403);
  require_once "./views/pages/sendEmail.php";
}

function updateEmail() {
  DB::update("users", Auth::user()["id"], [
    "email" => $_POST["email"]
  ]);
  Session::set("toast", ["success", "メールアドレスを変更しました"]);
  back();
}

function updatePassword() {
  if (!password_verify($_POST["old_password"], Auth::user()["password"])) {
    Session::set("toast", ["danger", "現在のパスワードが異なります"]);
    back();
  }
  if ($_POST["new_password"] !== $_POST["new_password_confirm"]) {
    Session::set("toast", ["danger", "新しいパスワードの再入力が正しくありません"]);
    back();
  }
  DB::update("users", Auth::user()["id"], [
    "password" => password_hash($_POST["new_password"], PASSWORD_DEFAULT)
  ]);
  Session::set("toast", ["success", "パスワードを変更しました"]);
  back();
}

function storeSendEmail() {
  DB::insert("send_emails", [
    "user_id" => Auth::user()["id"],
    "email" => $_POST["email"],
    "enabled" => "1"
  ]);
  Session::set("toast", ["success", "送信先メールアドレスを追加しました"]);
  back();
}

function updateSendEmail($vars) {
  $id = $vars["id"];
  $sendEmail = Fetch::find("send_emails", $id);
  if (!Allow::sendEmail($sendEmail)) abort(403);
  DB::update("send_emails", $id, [
    "email" => $_POST["email"],
    "enabled" => $_POST["enabled"]
  ]);
  Session::set("toast", ["success", "送信先メールアドレスを変更しました{$_POST["enabled"]}"]);
  back();
}

function deleteSendEmail($vars) {
  $id = $vars["id"];
  $sendEmail = Fetch::find("send_emails", $id);
  if (!Allow::sendEmail($sendEmail)) abort(403);
  DB::delete("send_emails", $id);
  Session::set("toast", ["info", "送信先メールアドレスを削除しました"]);
  redirect("/account");
}