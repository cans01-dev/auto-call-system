<?php

function stats($vars) {
  $survey_id = $vars["id"];

  $survey = Fetch::find("surveys", $survey_id);

  # area
  global $pdo;
  $sql = "SELECT DISTINCT a.title, a.id, a.survey_id FROM areas as a
          JOIN reserves_areas as ra ON a.id = ra.area_id
          JOIN reserves as r ON ra.reserve_id = r.id
          WHERE r.survey_id = {$survey_id}";
  $areas = $pdo->query($sql)->fetchAll(PDO::FETCH_ASSOC);

  $sql = "SELECT COUNT(*) as called_numbers,
    SUM(c.status = 1) as responsed_numbers,
    a.id as id,
    a.title as title
    FROM calls as c
    JOIN reserves as r ON c.reserve_id = r.id
    JOIN stations as s ON c.number LIKE s.prefix + '%'
    JOIN areas as a ON s.area_id = a.id
    WHERE r.survey_id = {$survey_id}
    GROUP BY a.id";
  $areas = $pdo->query($sql)->fetchAll();

  foreach ($areas as $key => $area) {
    $areas[$key]["all_numbers"] = Fetch::query(
      "SELECT COUNT(*) FROM stations WHERE area_id = {$area["id"]}",
      "fetchColumn"
    ) * 100000;
    $areas[$key]["called_numbers"] = 0;
    $areas[$key]["responsed_numbers"] = 0;

    $areas[$key]["progress_rate"] = $areas[$key]["called_numbers"] / $areas[$key]["all_numbers"];
    if ($areas[$key]["called_numbers"]) {
      $areas[$key]["response_rate"] = $areas[$key]["responsed_numbers"] / $areas[$key]["called_numbers"];
    } else {
      $areas[$key]["response_rate"] = 0;
    }
  }

  // foreach ($areas as $key => $area) {
  //   $stations = Fetch::get("stations", $area["id"], "area_id");
  //   $areas[$key]["all_numbers"] = count($stations) * 10000;
  //   $areas[$key]["called_numbers"] = 0;
  //   $areas[$key]["responsed_numbers"] = 0;

  //   $sql = "SELECT COUNT(*) FROM calls as c
  //           JOIN reserves as r ON c.reserve_id = r.id
  //           JOIN stations as s ON c.number LIKE s.prefix + '%'
  //           WHERE r.survey_id = {$survey_id}
  //           AND s.area_id = {$area["id"]}";
  //   $areas[$key]["called_numbers"] = $pdo->query($sql)->fetchColumn();

  //   $sql = "SELECT COUNT(*) FROM calls as c
  //           JOIN reserves as r ON c.reserve_id = r.id
  //           JOIN stations as s ON c.number LIKE s.prefix + '%'
  //           WHERE r.survey_id = {$survey_id}
  //           AND s.area_id = {$area["id"]}
  //           AND c.status = 1";
  //   $areas[$key]["responsed_numbers"] = $pdo->query($sql)->fetchColumn();
    // foreach ($stations as $station) {
      // $sql = "SELECT COUNT(*) FROM calls as c JOIN reserves as r ON c.reserve_id = r.id
      //         WHERE r.survey_id = {$survey_id} AND number LIKE '{$station["prefix"]}%'";
      // $areas[$key]["called_numbers"] += $pdo->query($sql)->fetchColumn();

      // $sql = "SELECT COUNT(*) FROM calls as c JOIN reserves as r ON c.reserve_id = r.id
      //         WHERE r.survey_id = {$survey_id} AND number LIKE '{$station["prefix"]}%' AND c.status = 1";
      // $areas[$key]["responsed_numbers"] += $pdo->query($sql)->fetchColumn();
    // }
    // $areas[$key]["progress_rate"] = $areas[$key]["called_numbers"] / $areas[$key]["all_numbers"];
    // if ($areas[$key]["called_numbers"]) {
    //   $areas[$key]["response_rate"] = $areas[$key]["responsed_numbers"] / $areas[$key]["called_numbers"];
    // } else {
    //   $areas[$key]["response_rate"] = 0;
    // }
  // }

  # billing
  $survey_reserves = Fetch::get("reserves", $survey_id, "survey_id");
  $months = [];
  foreach ($survey_reserves as $reserve) {
    $month = date("Y-m", strtotime($reserve["date"]));
    if (!in_array($month, $months)) {
      $months[] = $month;
    }
  }

  $sql = "SELECT SUM(c.duration) as total_duration, MONTH(r.date) as month, YEAR(r.date) as year FROM calls as c
          JOIN reserves as r ON c.reserve_id = r.id
          WHERE r.survey_id = {$survey["id"]}
          GROUP BY MONTH(r.date), YEAR(r.date)";
  $months = Fetch::query($sql, "fetchAll");

  foreach ($months as $month) {
    $ts = strtotime($month["year"] . "-" . $month["month"] . "-01");
    $total_duration = $month["total_duration"];
    $survey["billings"][] = [
      "timestamp" => $ts,
      "total_duration" => $total_duration
    ];
  }
  // $total_duration = Fetch::query($sql, "fetchColumn");

  // foreach ($months as $month) {
  //   $ts = strtotime($month."-01");
  //   $month = date("m", $ts);
  //   $year = date("Y", $ts);
  //   $sql = "SELECT SUM(c.duration) FROM calls as c JOIN reserves as r
  //           WHERE r.survey_id = {$survey["id"]} AND MONTH(r.date) = {$month} AND YEAR(r.date) = {$year}";
  //   $total_duration = Fetch::query($sql, "fetchColumn");
  //   $survey["billings"][] = [
  //     "timestamp" => $ts,
  //     "total_duration" => $total_duration
  //   ];
  // }
  
  require_once "./views/pages/stats.php";
}