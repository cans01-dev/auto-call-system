<?php

function receive_result_log() {
  $page = @$_GET["page"] ?? 1;
  $limit = 100;
  $offset = (($page) - 1) * $limit;

  $pgnt = pagenation($limit, Fetch::query(
    "SELECT COUNT(*) FROM receive_result_log",
    "fetchColumn"
  ), $page);

  $sql = "SELECT *, l.id as id, r.id as reserve_id, s.id as survey_id, l.status as status, r.date as reserve_date FROM receive_result_log as l
          LEFT JOIN reserves as r ON l.reserve_id = r.id
          LEFT JOIN surveys as s ON r.survey_id = s.id
          LEFT JOIN users as u ON s.user_id = u.id
          ORDER BY l.created_at DESC
          LIMIT {$limit} OFFSET {$offset}";
  $logs = Fetch::query($sql, "fetchAll");

  require_once "./views/pages/admin/receive_result_log.php";
}

function gen_reserve_log() {
  $page = @$_GET["page"] ?? 1;
  $limit = 100;
  $offset = (($page) - 1) * $limit;

  $pgnt = pagenation($limit, Fetch::query(
    "SELECT COUNT(*) FROM gen_reserve_log",
    "fetchColumn"
  ), $page);

  $sql = "SELECT *, l.id as id, r.id as reserve_id, s.id as survey_id, l.status as status, r.date as reserve_date FROM gen_reserve_log as l
          LEFT JOIN reserves as r ON l.reserve_id = r.id
          LEFT JOIN surveys as s ON r.survey_id = s.id
          LEFT JOIN users as u ON s.user_id = u.id
          ORDER BY l.created_at DESC
          LIMIT {$limit} OFFSET {$offset}";
  $logs = Fetch::query($sql, "fetchAll");

  require_once "./views/pages/admin/gen_reserve_log.php";
}