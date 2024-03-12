<?php

function adminReserves() {
  $page = @$_GET["page"] ?? 1;
  $limit = 100;
  $start = @$_GET["start"] ?? date('Y-m-d', strtotime('first day of this month'));
  $end = @$_GET["end"] ?? date('Y-m-d', strtotime('last day of this month'));
  $offset = (($page) - 1) * $limit;
  
  $user_id = @$_GET["user_id"];
  $and_s_user_id = $user_id ? "AND s.user_id = {$user_id}" : "";

  $sql = "SELECT COUNT(*) FROM reserves as r
          JOIN surveys as s ON r.survey_id = s.id
          JOIN users as u ON s.user_id = u.id
          WHERE r.date BETWEEN '{$start}' AND '{$end}'
          {$and_s_user_id}";
  $pgnt = pagenation($limit, Fetch::query($sql, "fetchColumn"), $page);

  $sql = "SELECT *, r.status as status, r.id as id, u.id as user_id FROM reserves as r
          JOIN surveys as s ON r.survey_id = s.id
          JOIN users as u ON s.user_id = u.id
          WHERE r.date BETWEEN '{$start}' AND '{$end}'
          {$and_s_user_id}
          ORDER BY r.date DESC
          LIMIT {$limit} OFFSET {$offset}";
  $reserves = Fetch::query($sql, "fetchAll");

  require_once "./views/pages/admin/reserves.php";
}

function adminReserveForwardConfirmed($vars) {
  $reserve = Fetch::find("reserves", $vars["id"]);

  [$json, $file_path] = gen_reserve_info($reserve);

  DB::update("reserves", $reserve["id"], [
    "status" => "1",
    "reserve_file" => basename($file_path)
  ]);
  file_put_contents($file_path, $json);

  Session::set("toast", ["success", "予約情報ファイルを生成しステータスを確定済にしました"]);
  back();
}

function adminReserveBackReserved($vars) {
  $reserve = Fetch::find("reserves", $vars["id"]);

  DB::update("reserves", $reserve["id"], [
    "status" => 0,
    "result_file" => null
  ]);

  Session::set("toast", ["success", "ステータスを予約済に戻しました"]);
  back();
}

function adminReserveForwardCollected($vars) {
  $file_path = upload_file($_FILES["file"]);
  $json = file_get_contents($file_path);
  $array = json_decode($json, true);

  if ($array["id"] == $vars["id"]) {
    receive_result($json);
    DB::update("reserves", $array["id"], [
      "status" => "4",
      "reserve_file" => basename($file_path)
    ]);
    Session::set("toast", ["success", "結果ファイルを処理しステータスを集計済にしました"]);
    back();  
  }

  Session::set("toast", ["error", "指定された予約と結果ファイルの予約が異なります。"]);
  back();
}

function adminReserveBackConfirmed($vars) {
  $reserve = Fetch::find("reserves", $vars["id"]);

  DB::query("DELETE FROM calls WHERE reserve_id = {$reserve["id"]}");
  DB::update("reserves", $reserve["id"], [
    "status" => 1,
    "result_file" => null
  ]);

  Session::set("toast", ["success", "結果データを削除しステータスを確定済に戻しました"]);
  back();
}
