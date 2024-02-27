<?php

function call($vars) {
  $call_id = $vars["id"];
  $sql = "SELECT *, c.id as id, c.status as status FROM calls as c
          JOIN reserves as r ON c.reserve_id = r.id
          WHERE c.id = {$call_id}
          GROUP BY c.id";
  $call = Fetch::query($sql, "fetch");
  if (!Allow::call($call)) abort(403);
  
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
  if (!Allow::survey($survey)) abort(403);

  $page = @$_GET["page"] ?? 1;
  $limit = 100;
  $start = @$_GET["start"] ?? "2024-01-01";
  $end = @$_GET["end"] ?? "2034-01-01";
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
      $sql = "SELECT * FROM answers as a
              JOIN options as o ON a.option_id = o.id
              WHERE a.call_id = {$call["id"]}
              AND a.faq_id = {$faq["id"]}
              AND (o.next_faq_id <> o.faq_id OR next_ending_id IS NOT NULL)";
      $calls[$k]["faqs"][$k2] = Fetch::query($sql, "fetch");;
    }
  }

  if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $csvFileName = '/tmp/' . time() . rand() . '.csv';
    $fileName = time() . rand() . '.csv';
    $res = fopen($csvFileName, 'w');
    if ($res === FALSE) {
      throw new Exception('ファイルの書き込みに失敗しました。');
    }
  
    $header = ["id", "name", "email", "password"];
    fputcsv($res, $header);
  
    foreach($calls as $dataInfo) {
      mb_convert_variables('SJIS', 'UTF-8', $dataInfo);
      fputcsv($res, $dataInfo);
    }
  
    fclose($res);
    header('Content-Type: application/octet-stream');
    header('Content-Disposition: attachment; filename=' . $fileName); 
    header('Content-Length: ' . filesize($csvFileName)); 
    header('Content-Transfer-Encoding: binary');
    readfile($csvFileName);  
    exit;
  } else {
    require_once "./views/pages/calls.php";
  }

}

function answers($vars) {
  $survey = Fetch::find("surveys", $vars["id"]);
  if (!Allow::survey($survey)) abort(403);

  $page = @$_GET["page"] ?? 1;
  $limit = 100;
  $start = @$_GET["start"] ?? "2024-01-01";
  $end = @$_GET["end"] ?? "2034-01-01";
  $number = "%" . @$_GET["number"] . "%";

  $sql = "SELECT o.id FROM options as o JOIN faqs as f ON o.faq_id = f.id WHERE f.survey_id = {$survey["id"]}";
  $options = array_str($options_arr = @$_GET["options"] ?? array_column(Fetch::query($sql, "fetchAll"), "id"));

  $offset = (($page) - 1) * $limit;
  $sql = "SELECT COUNT(*) FROM answers as a
          JOIN calls as c ON a.call_id = c.id
          JOIN reserves as r ON c.reserve_id = r.id
          WHERE r.survey_id = {$survey["id"]}
          AND r.date BETWEEN '{$start}' AND '{$end}'
          AND a.option_id IN({$options})";
  $pgnt = pagenation($limit, Fetch::query($sql, "fetchColumn"), $page);
  
  $sql = "SELECT *,
            f.title as faq_title,
            o.title as option_title,
            c.id as call_id
          FROM answers as a
          JOIN calls as c ON a.call_id = c.id
          JOIN reserves as r ON c.reserve_id = r.id
          JOIN options as o ON a.option_id = o.id
          JOIN faqs as f ON a.faq_id = f.id
          WHERE r.survey_id = {$survey["id"]}
          AND r.date BETWEEN '{$start}' AND '{$end}'
          AND a.option_id IN({$options})
          ORDER BY c.time DESC
          LIMIT {$limit} OFFSET {$offset}";
  $answers = Fetch::query($sql, "fetchAll");

  $faqs = Fetch::get("faqs", $survey["id"], "survey_id", "order_num");

  foreach ($faqs as $k => $faq) {
    $sql = "SELECT * FROM options
    WHERE faq_id = {$faq["id"]}
    AND (next_faq_id <> faq_id OR next_ending_id IS NOT NULL)
    ORDER BY dial";
    $faqs[$k]["options"] = Fetch::query($sql, "fetchAll");
  }

  require_once "./views/pages/answers.php";
}
