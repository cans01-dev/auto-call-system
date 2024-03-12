<?php

function stats($vars) {
  $survey = Fetch::find("surveys", $vars["id"]);

  $stats["collected_reserves"] = Fetch::query(
    "SELECT COUNT(*) FROM reserves
    WHERE survey_id = {$survey["id"]}
    AND status = 4",
    "fetchColumn"
  );
  $stats["all_calls"] = Fetch::query(
    "SELECT COUNT(*) FROM calls as c
    JOIN reserves as r ON c.reserve_id = r.id
    WHERE r.survey_id = {$survey["id"]}",
    "fetchColumn"
  );
  $stats["responsed_calls"] = Fetch::query(
    "SELECT COUNT(*) FROM calls as c
    JOIN reserves as r ON c.reserve_id = r.id
    WHERE r.survey_id = {$survey["id"]}
    AND c.status = 1",
    "fetchColumn"
  );
  $stats["success_calls"] = Fetch::query(
    "SELECT COUNT(*) FROM answers as a
    JOIN calls as c ON a.call_id = c.id
    JOIN reserves as r ON c.reserve_id = r.id
    WHERE r.survey_id = {$survey["id"]} AND a.option_id IN (
      SELECT o.id FROM options as o JOIN faqs as f ON o.faq_id = f.id
      WHERE f.survey_id = {$survey["id"]} AND o.next_ending_id = {$survey["success_ending_id"]}
    )",
    "fetchColumn"
  );
  $stats["all_actions"] = Fetch::query(
    "SELECT COUNT(*) FROM answers as a
    JOIN options as o ON a.option_id = o.id
    JOIN calls as c ON a.call_id = c.id
    JOIN reserves as r ON c.reserve_id = r.id
    WHERE r.survey_id = {$survey["id"]}
    AND (o.next_faq_id <> o.faq_id OR o.next_ending_id IS NOT NULL)",
    "fetchColumn"
  );
  $stats["action_calls"] = count(Fetch::query(
    "SELECT * FROM answers as a
    JOIN calls as c ON a.call_id = c.id
    JOIN reserves as r ON c.reserve_id = r.id
    WHERE r.survey_id = {$survey["id"]}
    GROUP BY c.id
    HAVING COUNT(*) > 0",
    "fetchAll"
  ));


  # area
  $def_areas = Fetch::query(
    "SELECT *, a.id as id, a.survey_id as survey_id FROM areas as a
    JOIN reserves_areas as ra ON a.id = ra.area_id
    JOIN reserves as r ON ra.reserve_id = r.id
    WHERE r.survey_id = {$survey["id"]}
    AND a.survey_id IS NULL
    GROUP BY a.id",
    "fetchAll"
  );
  $my_areas = Fetch::get("areas", $survey["id"], "survey_id");
  $my_lists = Fetch::get("number_lists", $survey["id"], "survey_id");

  # billing
  $survey_reserves = Fetch::get("reserves", $survey["id"], "survey_id");
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

function statsArea($vars) {
  $survey = Fetch::find("surveys", $vars["survey_id"]);
  $area = Fetch::find("areas", $vars["area_id"]);
  $area["stations"] = Fetch::get("stations", $area["id"], "area_id");
  if (Auth::user()["status"] !== 1) if (!Allow::survey($survey)) abort(403);

  $stats["all_numbers"] = count($area["stations"]) * 10000;
  $stats["called_numbers"] = 0;
  $stats["responsed_numbers"] = 0;
  foreach ($area["stations"] as $station) {
    $sql = "SELECT COUNT(*) FROM calls as c JOIN reserves as r ON c.reserve_id = r.id
            WHERE r.survey_id = {$survey["id"]} AND number LIKE '{$station["prefix"]}%'";
    $stats["called_numbers"] += Fetch::query($sql, "fetchColumn");

    $sql = "SELECT COUNT(*) FROM calls as c JOIN reserves as r ON c.reserve_id = r.id
            WHERE r.survey_id = {$survey["id"]} AND number LIKE '{$station["prefix"]}%' AND c.status = 1";
    $stats["responsed_numbers"] += Fetch::query($sql, "fetchColumn");
  }
  $stats["progress_rate"] = $stats["called_numbers"] / $stats["all_numbers"];
  if ($stats["called_numbers"]) {
    $stats["response_rate"] = $stats["responsed_numbers"] / $stats["called_numbers"];
  } else {
    $stats["response_rate"] = 0;
  }
  $area["stats"] = $stats;

  require_once "./views/pages/area.php";
}