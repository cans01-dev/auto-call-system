<?php 

function updateEmail() {
  global $pdo;
  $stmt = $pdo->prepare("UPDATE users SET email = :email WHERE id = :id");
  $stmt->execute([
    ":id" => Auth::user()["id"],
    ":email" => $_POST["email"]
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
  global $pdo;
  $stmt = $pdo->prepare("UPDATE users SET password = :password WHERE id = :id");
  $stmt->execute([
    ":id" => Auth::user()["id"],
    ":password" => password_hash($_POST["new_password"], PASSWORD_DEFAULT)
  ]);
  Session::set("toast", ["success", "パスワードを変更しました"]);
  back();
}

function storeSendEmail() {
  global $pdo;
  $stmt = $pdo->prepare("INSERT INTO send_emails (user_id, email) VALUES (:user_id, :email)");
  $stmt->execute([
    ":user_id" => Auth::user()["id"],
    ":email" => $_POST["email"]
  ]);
  Session::set("toast", ["success", "送信先メールアドレスを追加しました"]);
  back();
}

function updateSendEmail($vars) {
  $id = $vars["id"];
  $sendEmail = Fetch::find("send_emails", $id);
  global $pdo;
  $stmt = $pdo->prepare("UPDATE send_emails SET email = :email WHERE id = :id");
  $stmt->execute([
    ":id" => $id,
    ":email" => $_POST["email"]
  ]);
  Session::set("toast", ["success", "送信先メールアドレスを変更しました"]);
  back();
}

function deleteSendEmail($vars) {
  $id = $vars["id"];
  $sendEmail = Fetch::find("send_emails", $id);
  global $pdo;
  $stmt = $pdo->prepare("DELETE FROM send_emails WHERE id = :id");
  $stmt->execute([":id" => $id]);
  Session::set("toast", ["info", "送信先メールアドレスを削除しました"]);
  redirect("/account");
}