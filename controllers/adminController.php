<?php

function adminUpdatePassword() {
  $user = Fetch::find("users", $_POST["user_id"]);

  if (!password_verify($_POST["admin_password"], Auth::user()["password"])) {
    Session::set("toast", ["danger", "変更を行う管理者のパスワードが異なります"]);
    back();
  }
  if ($_POST["new_password"] !== $_POST["new_password_confirm"]) {
    Session::set("toast", ["danger", "新しいパスワードの再入力が正しくありません"]);
    back();
  }
  DB::update("users", $user["id"], [
    "password" => password_hash($_POST["new_password"], PASSWORD_DEFAULT)
  ]);
  Session::set("toast", ["success", "{$user["email"]}のパスワードを変更しました"]);
  back();
}

function adminUpdateStatus() {
  $user = Fetch::find("users", $_POST["user_id"]);

  if (!password_verify($_POST["admin_password"], Auth::user()["password"])) {
    Session::set("toast", ["danger", "変更を行う管理者のパスワードが異なります"]);
    back();
  }
  DB::update("users", $user["id"], [
    "status" => $_POST["new_status"]
  ]);
  Session::set("toast", ["success", "{$user["email"]}のステータスを変更しました"]);
  back();
}

function storeUser() {
  if ($_POST["password"] !== $_POST["password_confirm"]) {
    Session::set("toast", ["danger", "パスワードの再入力が正しくありません"]);
    back();
  }
  DB::insert("users", [
    "email" => $_POST["email"],
    "password" => $_POST["password"],
    "status" => $_POST["status"],
  ]);
  Session::set("toast", ["success", "ユーザーを新規作成しました"]);
  back();
}