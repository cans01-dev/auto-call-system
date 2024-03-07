<?php

function stats($vars) {
  $survey_id = $vars["id"];
  $survey = Fetch::find("surveys", $survey_id);

  # area
  $areas = Fetch::query(
    "SELECT *, a.survey_id as survey_id FROM areas as a
    JOIN reserves_areas as ra ON a.id = ra.area_id
    JOIN reserves as r ON ra.reserve_id = r.id
    WHERE r.survey_id = {$survey["id"]}
    GROUP BY a.id",
    "fetchAll"
  );

  foreach ($areas as $key => $area) {
    // エリアごとに応答率など算出
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
  require_once "./views/pages/stats.php";
}