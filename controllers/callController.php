<?php

function call($vars) {
  $call_id = $vars["id"];
  $sql = "SELECT *, c.id as id, c.status as status FROM calls as c
          JOIN reserves as r ON c.reserve_id = r.id
          WHERE c.id = {$call_id}
          GROUP BY c.id";
  $call = Fetch::query($sql, "fetch");
  if (Auth::user()["status"] !== 1) if (!Allow::call($call)) abort(403);
  
  $reserve = Fetch::find("reserves", $call["reserve_id"]);
  $survey = Fetch::find("surveys", $reserve["survey_id"]);

  $sql = "SELECT *, f.id as id FROM answers as a
          JOIN faqs as f ON a.faq_id = f.id
          JOIN calls as c ON a.call_id = c.id
          WHERE c.id = {$call["id"]}";
  $answers = Fetch::query($sql, "fetchAll");

  require_once "./views/pages/call.php";
}

function calls($vars) {
  $survey = Fetch::find("surveys", $vars["id"]);
  if (Auth::user()["status"] !== 1) if (!Allow::survey($survey)) abort(403);

  $page = @$_GET["page"] ?? 1;
  $limit = 100;
  $start = @$_GET["start"] ?? date('Y-m-d', strtotime('first day of this month'));
  $end = @$_GET["end"] ?? date('Y-m-d', strtotime('last day of this month'));
  $number = "%" . @$_GET["number"] . "%";
  $action_count = @$_GET["action_count"] ?? 0;
  $status = array_str($status_arr = @$_GET["status"] ?? [1, 2, 3, 4, 6]);

  $offset = (($page) - 1) * $limit;
  $sql = "SELECT * FROM calls as c
          JOIN reserves as r ON c.reserve_id = r.id
          LEFT OUTER JOIN answers as a ON c.id = a.call_id
          WHERE r.survey_id = {$survey["id"]}
          AND r.date BETWEEN '{$start}' AND '{$end}'
          AND c.status IN({$status})
          AND c.number LIKE '{$number}'
          GROUP BY c.id
          HAVING COUNT(a.id) >= {$action_count}";
  $pgnt = pagenation($limit, count(Fetch::query($sql, "fetchAll")), $page);
  
    $stats["all_calls"] = count(Fetch::query($sql, "fetchAll"));

    $stats["responsed_calls"] = count(Fetch::query(
      "SELECT * FROM calls as c
      JOIN reserves as r ON c.reserve_id = r.id
      LEFT OUTER JOIN answers as a ON c.id = a.call_id
      WHERE r.survey_id = {$survey["id"]}
      AND r.date BETWEEN '{$start}' AND '{$end}'
      AND c.status = 1
      AND c.number LIKE '{$number}'
      GROUP BY c.id
      HAVING COUNT(a.id) >= {$action_count}",
      "fetchAll"
    ));
    $stats["success_calls"] = count(Fetch::query(
      "SELECT * FROM calls as c
      JOIN reserves as r ON c.reserve_id = r.id
      LEFT OUTER JOIN answers as a ON c.id = a.call_id
      WHERE r.survey_id = {$survey["id"]}
      AND r.date BETWEEN '{$start}' AND '{$end}'
      AND c.status IN({$status})
      AND c.number LIKE '{$number}'
      AND a.option_id IN (
        SELECT o.id FROM options as o JOIN faqs as f ON o.faq_id = f.id
        WHERE f.survey_id = {$survey["id"]} AND o.next_ending_id = {$survey["success_ending_id"]}
      )
      GROUP BY c.id
      HAVING COUNT(a.id) >= {$action_count}",
      "fetchAll"
    ));
    $stats["all_actions"] = array_sum(array_column(Fetch::query(
      "SELECT COUNT(a.id) as count FROM calls as c
      JOIN answers as a ON c.id = a.call_id
      JOIN options as o ON a.option_id = o.id
      JOIN reserves as r ON c.reserve_id = r.id
      WHERE r.survey_id = {$survey["id"]}
      AND (o.next_faq_id <> o.faq_id OR o.next_ending_id IS NOT NULL)
      AND r.date BETWEEN '{$start}' AND '{$end}'
      AND c.number LIKE '{$number}'
      GROUP BY c.id
      HAVING COUNT(a.id) >= {$action_count}",
      "fetchAll"
    ), "count"));
    $stats["action_calls"] = count(Fetch::query(
      "SELECT * FROM calls as c
      JOIN answers as a ON c.id = a.call_id
      JOIN reserves as r ON c.reserve_id = r.id
      WHERE r.survey_id = {$survey["id"]}
      AND r.date BETWEEN '{$start}' AND '{$end}'
      AND c.number LIKE '{$number}'
      GROUP BY c.id
      HAVING COUNT(a.id) >= {$action_count} AND COUNT(a.id) > 0",
      "fetchAll"
    ));
    $stats["total_duration"] = Fetch::query(
      "SELECT SUM(c.duration) as total_duration FROM calls as c
      JOIN reserves as r ON c.reserve_id = r.id
      LEFT OUTER JOIN answers as a ON c.id = a.call_id
      WHERE r.survey_id = {$survey["id"]}
      AND r.date BETWEEN '{$start}' AND '{$end}'
      AND c.status IN({$status})
      AND c.number LIKE '{$number}'
      GROUP BY c.id
      HAVING COUNT(a.id) >= {$action_count}",
      "fetchColumn"
    );

  $sql = "SELECT *,
            c.id as id,
            c.status as status,
            (
              SELECT COUNT(*) FROM answers as a
              JOIN options as o ON a.option_id = o.id
              WHERE call_id = c.id
              AND (o.next_faq_id <> o.faq_id OR o.next_ending_id IS NOT NULL)
            ) as action_count
          FROM calls as c
          JOIN reserves as r ON c.reserve_id = r.id
          WHERE r.survey_id = {$survey["id"]}
          AND r.date BETWEEN '{$start}' AND '{$end}'
          AND c.status IN({$status})
          AND c.number LIKE '{$number}'
          GROUP BY c.id
          HAVING action_count >= {$action_count}
          ORDER BY c.time DESC
          LIMIT {$limit} OFFSET {$offset}";
  $calls = Fetch::query($sql, "fetchAll");

  $faqs = Fetch::get("faqs", $survey["id"], "survey_id", "order_num");

  foreach ($calls as $k => $call) {
    foreach ($faqs as $k2 => $faq) {
      $sql = "SELECT *, o.title as option_title, f.title as faq_title FROM answers as a
              JOIN options as o ON a.option_id = o.id
              JOIN faqs as f ON a.faq_id = f.id
              WHERE a.call_id = {$call["id"]}
              AND a.faq_id = {$faq["id"]}
              AND (o.next_faq_id <> o.faq_id OR next_ending_id IS NOT NULL)";
      $calls[$k]["faqs"][$k2] = Fetch::query($sql, "fetch");;
    }
  }
  require_once "./views/pages/calls.php";
}

function callsCsv($vars) {
  $survey = Fetch::find("surveys", $vars["id"]);
  if (Auth::user()["status"] !== 1) if (!Allow::survey($survey)) abort(403);

  $start = @$_GET["start"] ?? "2024-01-01";
  $end = @$_GET["end"] ?? "2034-01-01";
  $number = "%" . @$_GET["number"] . "%";
  $action_count = @$_GET["action_count"] ?? 0;
  $status = array_str($status_arr = @$_GET["status"] ?? [1, 2, 3, 4, 6]);

  $sql = "SELECT *,
            c.id as id,
            c.status as status,
            (
              SELECT COUNT(*) FROM answers as a
              JOIN options as o ON a.option_id = o.id
              WHERE call_id = c.id
              AND (o.next_faq_id <> o.faq_id OR o.next_ending_id IS NOT NULL)
            ) as action_count
          FROM calls as c
          JOIN reserves as r ON c.reserve_id = r.id
          WHERE r.survey_id = {$survey["id"]}
          AND r.date BETWEEN '{$start}' AND '{$end}'
          AND c.status IN({$status})
          AND c.number LIKE '{$number}'
          GROUP BY c.id
          HAVING action_count >= {$action_count}
          ORDER BY c.time DESC";
  $calls = Fetch::query($sql, "fetchAll");

  $faqs = Fetch::get("faqs", $survey["id"], "survey_id", "order_num");

  foreach ($calls as $k => $call) {
    foreach ($faqs as $k2 => $faq) {
      $sql = "SELECT *, o.title as option_title, f.title as faq_title FROM answers as a
              JOIN options as o ON a.option_id = o.id
              JOIN faqs as f ON a.faq_id = f.id
              WHERE a.call_id = {$call["id"]}
              AND a.faq_id = {$faq["id"]}
              AND (o.next_faq_id <> o.faq_id OR next_ending_id IS NOT NULL)";
      $calls[$k]["faqs"][$k2] = Fetch::query($sql, "fetch");;
    }
  }

  $time = time();
  $csvFileName = dirname(__DIR__) . "/storage/outputs/calls_{$time}.csv";
  $fileName = "calls_{$time}.csv";
  $res = fopen($csvFileName, 'w');
  if ($res === FALSE) {
    throw new Exception('ファイルの書き込みに失敗しました。');
  }

  // $header = array_keys($calls[0]);
  // $header = array_splice($header, 0, -1);
  $header = ["電話番号", "コールID", "予約URL", "ステータス", "通話成立時間", "時間", "アクション数"];
  foreach ($faqs as $faq) $header[] = $faq["title"];
  mb_convert_variables('SJIS', 'UTF-8', $header);
  fputcsv($res, $header);
  
  foreach($calls as $k => $call) {
    $dataInfo = [
      $call["number"],
      url("/calls/{$call["id"]}"),
      url("/reserves/{$call["reserve_id"]}"),
      CALL_STATUS[$call["status"]]["text"],
      $call["duration"],
      $call["time"],
      $call["action_count"]
    ];
    foreach ($call["faqs"] as $faq) {
      $dataInfo[] = @$faq["option_title"];
    }
    unset($dataInfo["faqs"]);
    mb_convert_variables('SJIS', 'UTF-8', $dataInfo);
    fputcsv($res, $dataInfo);
  }

  fclose($res);
  header('Content-Type: application/octet-stream');
  header('Content-Disposition: attachment; filename=' . $fileName); 
  header('Content-Length: ' . filesize($csvFileName)); 
  header('Content-Transfer-Encoding: binary');
  readfile($csvFileName);
  return;
}