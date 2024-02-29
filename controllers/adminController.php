<?php

function users() {
  $users = Fetch::all("users");

  require_once "./views/pages/users.php";
}

function receive_result_log() {
  $sql = "SELECT *, l.id as id, r.id as reserve_id, s.id as survey_id, l.status as status, r.date as reserve_date FROM receive_result_log as l
          LEFT JOIN reserves as r ON l.reserve_id = r.id
          LEFT JOIN surveys as s ON r.survey_id = s.id
          LEFT JOIN users as u ON s.user_id = u.id
          ORDER BY l.created_at DESC";

  $logs = Fetch::query($sql, "fetchAll");

  require_once "./views/pages/receive_result_log.php";
}

function gen_reserve_log() {
  $sql = "SELECT *, l.id as id, r.id as reserve_id, s.id as survey_id, l.status as status, r.date as reserve_date FROM gen_reserve_log as l
          LEFT JOIN reserves as r ON l.reserve_id = r.id
          LEFT JOIN surveys as s ON r.survey_id = s.id
          LEFT JOIN users as u ON s.user_id = u.id
          ORDER BY l.created_at DESC";

  $logs = Fetch::query($sql, "fetchAll");

  require_once "./views/pages/gen_reserve_log.php";
}

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

function adminUpdateUser() {
  $user = Fetch::find("users", $_POST["user_id"]);

  if (!password_verify($_POST["admin_password"], Auth::user()["password"])) {
    Session::set("toast", ["danger", "変更を行う管理者のパスワードが異なります"]);
    back();
  }
  DB::update("users", $user["id"], [
    "status" => $_POST["new_status"],
    "number_of_lines" => $_POST["number_of_lines"]
  ]);
  Session::set("toast", ["success", "{$user["email"]}のユーザー情報を更新しました"]);
  back();
}

function storeUser() {
  if ($_POST["password"] !== $_POST["password_confirm"]) {
    Session::set("toast", ["danger", "パスワードの再入力が正しくありません"]);
    back();
  }

  DB::insert("users", [
    "email" => $_POST["email"],
    "password" => password_hash($_POST["password"], PASSWORD_DEFAULT),
    "status" => $_POST["status"],
    "number_of_lines" => $_POST["number_of_lines"]
  ]);
  $user_id = DB::lastInsertId();

  DB::insert("surveys", [
    "user_id" => $user_id,
    "title" => "アンケート１",
    "note" => "デフォルトのアンケート",
    "greeting" => "こんにちは、これはサンプルのアンケートです",
    "voice_name" => VOICES[0]["name"]
  ]);
  $survey_id = DB::lastInsertId();

  DB::insert("faqs", [
    "survey_id" => $survey_id,
    "title" => "デフォルトの質問",
    "text" => "これはデフォルトの質問です、もう一度お聞きになりたい方は０を押してください。",
    "order_num" => 0
  ]);
  $faq_id = DB::lastInsertId();

  DB::insert("options", [
    "faq_id" => $faq_id,
    "title" => "聞き直し",
    "dial" => 0,
    "next_faq_id" => $faq_id
  ]);

  DB::insert("favorites", [
    "survey_id" => $survey_id,
    "title" => "予約パターン１",
    "color" => "#DCF2F1",
    "start" => "17:00:00",
    "end" => "21:00:00"
  ]);
  $favorite_id = DB::lastInsertId();

  DB::insert("favorites_areas", [
    "favorite_id" => $favorite_id,
    "area_id" => 1
  ]);
  
  $dirname = dirname(__DIR__) . "/storage/users/{$user_id}";
  mkdir($dirname);
  
  $survey = Fetch::find("surveys", $survey_id);
  avfrg($survey);

  Session::set("toast", ["success", "ユーザーを新規作成しました"]);
  back();
}

function deleteUser($vars) {
  $user_id = $vars["id"];
  $user = Fetch::find("users", $user_id);

  DB::delete("users", $user["id"]);

  Session::set("toast", ["success", "{$user["email"]}のアカウントを削除しました"]);
  back();
}