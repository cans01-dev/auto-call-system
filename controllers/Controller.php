<?php

function index() {
  redirect("/home");
}

function home() {
  $surveys = Fetch::get("surveys", Auth::user()["id"], "user_id");

  $page = @$_GET["page"] ?? 1;
  $limit = 100;
  $start = @$_GET["start"] ?? date('Y-m-d', strtotime('first day of this month'));
  $end = @$_GET["end"] ?? date('Y-m-d', strtotime('last day of this month'));
  $offset = (($page) - 1) * $limit;
  $user_id = Auth::user()["id"];

  $sql = "SELECT COUNT(*) FROM reserves as r
          JOIN surveys as s ON r.survey_id = s.id
          JOIN users as u ON s.user_id = u.id
          WHERE r.date BETWEEN '{$start}' AND '{$end}
          AND u.id = {$user_id}'";
  $pgnt = pagenation($limit, Fetch::query($sql, "fetchColumn"), $page);

  $sql = "SELECT *, r.status as status, r.id as id, u.id as user_id FROM reserves as r
          JOIN surveys as s ON r.survey_id = s.id
          JOIN users as u ON s.user_id = u.id
          WHERE r.date BETWEEN '{$start}' AND '{$end}'
          AND u.id = {$user_id}
          ORDER BY r.date DESC
          LIMIT {$limit} OFFSET {$offset}";
  $reserves = Fetch::query($sql, "fetchAll");

  require_once "./views/pages/home.php";
}

function login() {
  require_once "./views/pages/login.php";
}

function support() {
  $parser = new Parsedown();
  $markdown = $parser->text(file_get_contents(dirname(__DIR__)."/assets/markdown/support.md"));
  require_once "./views/pages/support.php";
}

function loginPost() {
  if (Auth::attempt($_POST["email"], $_POST["password"])) {
    // $path = $_POST["redirect"] ? parse_url($_POST["redirect"], PHP_URL_PASS) : "/";
    session_regenerate_id(true);
    Session::set("toast", ["success", "ログインしました"]);
    // redirect($path);
    redirect("/");
  }
  Session::set("toast", ["danger", "メールアドレスもしくはパスワードが異なります"]);
  back();
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
  if ($_ENV["MODE"] === MODE_DEVELOPMENT) {
    $res = exec("php api/gen_reserve_info.php {$date}");
  } else {
    $res = exec("php8.2 api/gen_reserve_info.php {$date}");
  }
  Session::set("toast", ["info", $res]);
  back();
}