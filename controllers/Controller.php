<?php

function index() {
  redirect("/home");
}

function home() {
  $survey = Fetch::find("surveys", Auth::user()["id"], "user_id");
  redirect("/surveys/{$survey["id"]}");

  // $surveys = Fetch::get("surveys", Auth::user()["id"], "user_id");
  // require_once "./views/pages/home.php";
}

function login() {
  require_once "./views/pages/login.php";
}

function account() {
  $sendEmails = Fetch::get("send_emails", Auth::user()["id"], "user_id");
  require_once "./views/pages/account.php";
}

function sendEmail($vars) {
  $id = $vars["id"];
  $sendEmail = Fetch::find("send_emails", $id);
  if ($sendEmail["user_id"] !== Auth::user()["id"]) abort(403);
  require_once "./views/pages/sendEmail.php";
}

function survey($vars) {
  $survey_id = $vars["id"];
  $month = $_GET["month"] ?? date("n");
  $year = $_GET["year"] ?? date("Y");

  $survey = Fetch::find("surveys", $survey_id);
  $survey["endings"] = Fetch::get("endings", $survey["id"], "survey_id");
  $survey["faqs"] = Fetch::get("faqs", $survey["id"], "survey_id", "order_num");
  $survey["reserves"] = Fetch::reservesBySurveyIdAndYearMonth($survey["id"], $month, $year);
  $survey["favorites"] = Fetch::get("favorites", $survey["id"], "survey_id");
  if (Auth::user()["status"] !== 1) {
    if (!Allow::survey($survey)) abort(403);
  }

  # calendar
  $schedules = [];
  foreach ($survey["reserves"] as $reserve) {
    $reserve["areas"] = Fetch::areasByReserveId($reserve["id"]);
    $ts = strtotime($reserve["date"]);
    $schedules[date("d", $ts)] = $reserve;
  }
  $calendar = new Calendar($month, $year, $schedules);

  # area
  global $pdo;
  $sql = "SELECT DISTINCT a.title, a.id FROM areas as a
          JOIN reserves_areas as ra ON a.id = ra.area_id
          JOIN reserves as r ON ra.reserve_id = r.id
          WHERE r.survey_id = {$survey_id}";
  $areas = $pdo->query($sql)->fetchAll(PDO::FETCH_ASSOC);
  foreach ($areas as $key => $area) {
    $stations = Fetch::get("stations", $area["id"], "area_id");
    $areas[$key]["all_numbers"] = count($stations) * 10000;
    $areas[$key]["called_numbers"] = 0;
    $areas[$key]["responsed_numbers"] = 0;
    foreach ($stations as $station) {
      $sql = "SELECT COUNT(*) FROM calls as c JOIN reserves as r ON c.reserve_id = r.id
              WHERE r.survey_id = {$survey_id} AND number LIKE '{$station["prefix"]}%'";
      $areas[$key]["called_numbers"] += $pdo->query($sql)->fetchColumn();

      $sql = "SELECT COUNT(*) FROM calls as c JOIN reserves as r ON c.reserve_id = r.id
              WHERE r.survey_id = {$survey_id} AND number LIKE '{$station["prefix"]}%' AND c.status = 1";
      $areas[$key]["responsed_numbers"] += $pdo->query($sql)->fetchColumn();
    }
    $areas[$key]["progress_rate"] = $areas[$key]["called_numbers"] / $areas[$key]["all_numbers"];
    if ($areas[$key]["called_numbers"]) {
      $areas[$key]["response_rate"] = $areas[$key]["responsed_numbers"] / $areas[$key]["called_numbers"];
    } else {
      $areas[$key]["response_rate"] = 0;
    }
  }

  # billing
  $survey_reserves = Fetch::get("reserves", $survey_id, "survey_id");
  $months = [];
  foreach ($survey_reserves as $reserve) {
    $month = date("Y-m", strtotime($reserve["date"]));
    if (!in_array($month, $months)) {
      $months[] = $month;
    }
  }
  foreach ($months as $month) {
    $ts = strtotime($month."-01");
    $month = date("m", $ts);
    $year = date("Y", $ts);
    $sql = "SELECT SUM(c.duration) FROM calls as c JOIN reserves as r
            WHERE r.survey_id = {$survey["id"]} AND MONTH(r.date) = {$month} AND YEAR(r.date) = {$year}";
    $total_duration = $pdo->query($sql)->fetchColumn();
    $survey["billings"][] = [
      "timestamp" => $ts,
      "total_duration" => $total_duration
    ];
  }

  require_once "./views/pages/survey.php";
}

function faq($vars) {
  $id = $vars["id"];
  $faq = Fetch::find("faqs", $id);
  $faq["options"] = Fetch::get("options", $faq["id"], "faq_id", "dial");
  $survey = Fetch::find("surveys", $faq["survey_id"]);
  $survey["faqs"] = Fetch::get("faqs", $survey["id"], "survey_id", "order_num");
  if (!Allow::faq($faq)) abort(403);

  foreach ($faq["options"] as $option) {
    if ($option["next_faq_id"]) {
      $index = array_search($option, $faq["options"]);
      $faq["options"][$index]["next_faq"] = Fetch::find("faqs", $option["next_faq_id"]);
    }
  }

  require_once "./views/pages/faq.php";
}

function option($vars) {
  $id = $vars["id"];
  $option = Fetch::find("options", $id);
  $faq = Fetch::find("faqs", $option["faq_id"]);
  $survey = Fetch::find("surveys", $faq["survey_id"]);
  $survey["faqs"] = Fetch::get("faqs", $survey["id"], "survey_id");
  if (!Allow::option($option)) abort(403);
  require_once "./views/pages/option.php";
}

function reserve($vars) {
  $id = $vars["id"];
  $reserve = Fetch::find("reserves", $id);
  $reserve["areas"] = Fetch::areasByReserveId($reserve["id"]);
  $survey = Fetch::find("surveys", $reserve["survey_id"]);
  if (!Allow::reserve($reserve)) abort(403);

  require_once "./views/pages/reserve.php";
}

function favorite($vars) {
  $id = $vars["id"];
  $favorite = Fetch::find("favorites", $id);
  $favorite["areas"] = Fetch::areasByFavoriteId($favorite["id"]);
  $survey = Fetch::find("surveys", $favorite["survey_id"]);
  if (!Allow::favorite($favorite)) abort(403);

  require_once "./views/pages/favorite.php";
}

function result($vars) {
  $id = $vars["id"];
  $reserve = Fetch::find("reserves", $id);
  $survey = Fetch::find("surveys", $reserve["survey_id"]);
  if (Auth::user()["status"] !== 1) {
    if (!Allow::reserve($reserve)) abort(403);
  }
  global $pdo;

  $sql = "SELECT COUNT(*) FROM calls WHERE reserve_id = {$reserve["id"]}";
  $survey["called_numbers"] = $pdo->query($sql)->fetchColumn();

  $sql = "SELECT COUNT(*) FROM calls WHERE reserve_id = {$reserve["id"]} AND status = 1";
  $survey["responsed_numbers"] = $pdo->query($sql)->fetchColumn();

  $survey["response_rate"] = $survey["responsed_numbers"] / $survey["called_numbers"];

  $sql = "SELECT COUNT(*) FROM answers as a JOIN calls as c ON a.call_id = c.id
          WHERE c.reserve_id = {$reserve["id"]} AND a.option_id IN (
            SELECT o.id FROM options as o JOIN faqs as f ON o.faq_id = f.id
            WHERE f.survey_id = {$survey["id"]} AND o.next_ending_id = {$survey["success_ending_id"]}
          )";
  $survey["success_numbers"] = $pdo->query($sql)->fetchColumn();

  $survey["success_rate"] =  $survey["success_numbers"] / $survey["responsed_numbers"];
  
  $survey["faqs"] = Fetch::get("faqs", $survey["id"], "survey_id", "order_num");
  foreach ($survey["faqs"] as $key => $faq) {
    $options = Fetch::get("options", $faq["id"], "faq_id");
    $survey["faqs"][$key]["options"] = $options;
    foreach($options as $key2 => $option) {
      $sql = "SELECT COUNT(*) FROM answers as a JOIN calls as c ON a.call_id = c.id
              WHERE c.reserve_id = {$reserve["id"]}
              AND a.faq_id = {$faq["id"]}
              AND a.option_id = {$option["id"]}";
      $result = $pdo->query($sql)->fetchColumn();
      $survey["faqs"][$key]["options"][$key2]["count"] = $result;
    }
  }

  require_once "./views/pages/result.php";
}

function call($vars) {
  global $pdo;
  $call_id = $vars["id"];
  $sql = "SELECT *, c.id as id, c.status as status FROM calls as c
          JOIN reserves as r ON c.reserve_id = r.id
          WHERE c.id = {$call_id}
          GROUP BY c.id";
  $call = Fetch::query($sql, "fetch");
  if (!Allow::call($call)) abort(403);
  
  $reserve = Fetch::find("reserves", $call["reserve_id"]);
  $survey = Fetch::find("surveys", $reserve["survey_id"]);

  $sql = "SELECT *, f.id as id FROM faqs as f
          LEFT JOIN (
            SELECT * FROM answers
            WHERE call_id = {$call["id"]}
          ) as a ON f.id = a.faq_id
          WHERE f.survey_id = {$survey["id"]}
          GROUP BY f.id
          ORDER BY f.order_num";
  $faqs = Fetch::query($sql, "fetchAll");

  require_once "./views/pages/call.php";
}

function calls($vars) {
  global $pdo;
  $survey = Fetch::find("surveys", $vars["id"]);
  if (!Allow::survey($survey)) abort(403);

  $page = @$_GET["page"] ?? 1;
  $limit = 50; //
  $start = @$_GET["start"] ?? "2024-01-01";
  $end = @$_GET["end"] ?? "2034-01-01";
  $number = "%" . @$_GET["number"] . "%";
  $status = array_str($status_arr = @$_GET["status"] ?? [1, 2, 3, 4, 6]);
  
  $offset = (($page) - 1) * 50;
  $sql = "SELECT COUNT(DISTINCT c.id) FROM calls as c
          -- LEFT JOIN answers as a ON c.id = a.call_id
          JOIN reserves as r ON c.reserve_id = r.id
          WHERE r.survey_id = {$survey["id"]}
          AND r.date BETWEEN '{$start}' AND '{$end}'
          AND c.status IN({$status})
          AND c.number LIKE '{$number}'";
  $count = $pdo->query($sql)->fetchColumn();
  $pgnt = pagenation($limit, $count, $page);

  $sql = "SELECT *, c.id as id, c.status as status FROM calls as c
          -- LEFT JOIN answers as a ON c.id = a.call_id
          JOIN reserves as r ON c.reserve_id = r.id
          WHERE r.survey_id = {$survey["id"]}
          AND r.date BETWEEN '{$start}' AND '{$end}'
          AND c.status IN({$status})
          AND c.number LIKE '{$number}'
          GROUP BY c.id
          ORDER BY c.time DESC
          LIMIT {$limit} OFFSET {$offset}";
  $calls = $pdo->query($sql)->fetchAll(PDO::FETCH_ASSOC);

  require_once "./views/pages/calls.php";
}

function support() {
  $parser = new Parsedown();
  $markdown = $parser->text(file_get_contents(dirname(__DIR__)."/assets/markdown/support.md"));
  require_once "./views/pages/support.php";
}

# admin
function users() {
  $users = Fetch::all("users");

  require_once "./views/pages/users.php";
}

function receive_result_log() {
  $sql = "SELECT *, l.id as id, r.id as reserve_id, s.id as survey_id, l.status as status, r.date as reserve_date FROM receive_result_log as l
          LEFT JOIN reserves as r ON l.reserve_id = r.id
          LEFT JOIN surveys as s ON r.survey_id = s.id
          LEFT JOIN users as u ON s.user_id = u.id";

  $logs = Fetch::query($sql, "fetchAll");

  require_once "./views/pages/receive_result_log.php";
}

function gen_reserve_log() {
  $sql = "SELECT *, l.id as id, r.id as reserve_id, s.id as survey_id, l.status as status, r.date as reserve_date FROM gen_reserve_log as l
          LEFT JOIN reserves as r ON l.reserve_id = r.id
          LEFT JOIN surveys as s ON r.survey_id = s.id
          LEFT JOIN users as u ON s.user_id = u.id";

  $logs = Fetch::query($sql, "fetchAll");

  require_once "./views/pages/gen_reserve_log.php";
}