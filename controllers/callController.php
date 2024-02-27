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

  $sql = "SELECT o.id FROM options as o JOIN faqs as f ON o.faq_id = f.id WHERE f.survey_id = {$survey["id"]}";
  $options = array_str($options_arr = @$_GET["options"] ?? array_column(Fetch::query($sql, "fetchAll"), "id"));

  require_once "./views/pages/calls.php";
}

function answers($vars) {
  $survey = Fetch::find("surveys", $vars["id"]);
  if (!Allow::survey($survey)) abort(403);

  $page = @$_GET["page"] ?? 1;
  $limit = 50; //
  $start = @$_GET["start"] ?? "2024-01-01";
  $end = @$_GET["end"] ?? "2034-01-01";

  $sql = "SELECT o.id FROM options as o JOIN faqs as f ON o.faq_id = f.id WHERE f.survey_id = {$survey["id"]}";
  $options = array_str($options_arr = @$_GET["options"] ?? array_column(Fetch::query($sql, "fetchAll"), "id"));

  $offset = (($page) - 1) * 50;
  $sql = "SELECT COUNT(DISTINCT c.id) FROM calls as c
          JOIN reserves as r ON c.reserve_id = r.id
          LEFT OUTER JOIN answers as a ON c.id = a.call_id
          WHERE r.survey_id = {$survey["id"]}
          AND r.date BETWEEN '{$start}' AND '{$end}'
          AND a.option_id IN({$options})";

  $count = Fetch::query($sql, "fetchColumn");
  $pgnt = pagenation($limit, $count, $page);

  $sql = "SELECT *, c.id as id, c.status as status FROM calls as c
          JOIN reserves as r ON c.reserve_id = r.id
          LEFT OUTER JOIN answers as a ON c.id = a.call_id
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

  require_once "./views/pages/calls.php";
}
