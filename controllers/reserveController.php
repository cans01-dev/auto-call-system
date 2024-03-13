<?php 

function reserve($vars) {
  $id = $vars["id"];
  $reserve = Fetch::find("reserves", $id);
  $reserve["areas"] = Fetch::areasByReserveId($reserve["id"]);
  $reserve["date_ts"] = strtotime($reserve["date"]);
  if ($reserve["number_list_id"]) {
    $reserve["number_list"] = Fetch::find("number_lists", $reserve["number_list_id"]);
    $reserve["number_list"]["count"]
      = Fetch::query("SELECT COUNT(*) FROM numbers WHERE number_list_id = {$reserve["number_list"]["id"]}", "fetchColumn");
  }
  if (Auth::user()["status"] !== 1) if (!Allow::reserve($reserve)) abort(403);

  $survey = Fetch::find("surveys", $reserve["survey_id"]);
  $survey["areas"] = Fetch::get("areas", $survey["id"], "survey_id");
  $survey["number_lists"] = Fetch::get("number_lists", $reserve["survey_id"], "survey_id");
  foreach ($survey["areas"] as $k => $myArea) $survey["areas"][$k]["count"]
    = Fetch::query("SELECT COUNT(*) FROM stations WHERE area_id = {$myArea["id"]}", "fetchColumn");
  foreach ($survey["number_lists"] as $k => $number_list) $survey["number_lists"][$k]["count"]
    = Fetch::query("SELECT COUNT(*) FROM numbers WHERE number_list_id = {$number_list["id"]}", "fetchColumn");

  if ($reserve["status"] === 4 && $calls = Fetch::get("calls", $reserve["id"], "reserve_id")) {
    $stats["all_calls"] = Fetch::query(
      "SELECT COUNT(*) FROM calls WHERE reserve_id = {$reserve["id"]}",
      "fetchColumn"
    );
  
    $stats["responsed_calls"] = Fetch::query(
      "SELECT COUNT(*) FROM calls WHERE reserve_id = {$reserve["id"]} AND status = 1",
      "fetchColumn"
    );
  
    $stats["success_calls"] = Fetch::query(
      "SELECT COUNT(*) FROM answers as a JOIN calls as c ON a.call_id = c.id
      WHERE c.reserve_id = {$reserve["id"]} AND a.option_id IN (
        SELECT o.id FROM options as o JOIN faqs as f ON o.faq_id = f.id
        WHERE f.survey_id = {$survey["id"]} AND o.next_ending_id = {$survey["success_ending_id"]}
      )",
      "fetchColumn"
    );
  
    $stats["all_actions"] = Fetch::query(
      "SELECT COUNT(*) FROM answers as a
      JOIN options as o ON a.option_id = o.id
      JOIN calls as c ON a.call_id = c.id
      WHERE c.reserve_id = {$reserve["id"]}
      AND (o.next_faq_id <> o.faq_id OR o.next_ending_id IS NOT NULL)",
      "fetchColumn"
    );

    $stats["action_calls"] = count(Fetch::query(
      "SELECT * FROM answers as a
      JOIN calls as c ON a.call_id = c.id
      WHERE c.reserve_id = {$reserve["id"]}
      GROUP BY c.id
      HAVING COUNT(*) > 0",
      "fetchAll"
    ));

    $stats["total_duration"] = Fetch::query(
      "SELECT SUM(c.duration) as total_duration FROM calls as c
      WHERE c.reserve_id = {$reserve["id"]}",
      "fetchColumn"
    );

    $reserve["stats"] = $stats;
    
    $survey["faqs"] = Fetch::get("faqs", $survey["id"], "survey_id", "order_num");
    foreach ($survey["faqs"] as $key => $faq) {
      $options = Fetch::get("options", $faq["id"], "faq_id");
      $survey["faqs"][$key]["options"] = $options;
      foreach($options as $key2 => $option) {
        $sql = "SELECT COUNT(*) FROM answers as a JOIN calls as c ON a.call_id = c.id
                WHERE c.reserve_id = {$reserve["id"]}
                AND a.faq_id = {$faq["id"]}
                AND a.option_id = {$option["id"]}";
        $result = Fetch::query($sql, "fetchColumn");
        $survey["faqs"][$key]["options"][$key2]["count"] = $result;
      }
    }
  }

  require_once "./views/pages/reserve.php";
}

function storeReserve() {
  $survey = Fetch::find("surveys", $_POST["survey_id"]);
  if (!Allow::survey($survey)) abort(403);

  if ($favorite = Fetch::find("favorites", @$_POST["favorite_id"])) {
    $favorite["areas"] = Fetch::areasByFavoriteId($favorite["id"]);
    DB::insert("reserves", [
      "survey_id" => $_POST["survey_id"],
      "date" => $_POST["date"],
      "start" => $favorite["start"],
      "end" => $favorite["end"],
      "number_list_id" => $favorite["number_list_id"]
    ]);
    $reserve_id = DB::lastInsertId();
    foreach ($favorite["areas"] as $area) {
      DB::insert("reserves_areas", [
        "reserve_id" => $reserve_id,
        "area_id" => $area["id"]
      ]);
    }
    $ts = strtotime($_POST["date"]);
    $month = date("n", $ts);
    $year = date("Y", $ts);
    Session::set("toast", ["success", "パターンから予約を作成しました"]);
    redirect("/surveys/{$_POST["survey_id"]}/calendar?month={$month}&year={$year}");

  } else {
    $start = $_POST["start"];
    $end = $_POST["end"];
    if (strtotime($start) + 3600 > strtotime($end)) {
      Session::set("toast", ["danger", "エラー！<br>開始・終了時間は".(MIN_INTERVAL / 3600)."時間以上の間隔をあけてください"]);
      redirect("/surveys/{$_POST["survey_id"]}/calendar");
    }
    DB::insert("reserves", [
      "survey_id" => $_POST["survey_id"],
      "date" => $_POST["date"],
      "start" => $start,
      "end" => $end
    ]);
    $reserve_id = DB::lastInsertId();
  }
  Session::set("toast", ["success", "予約を作成しました"]);
  redirect("/reserves/{$reserve_id}");
}

function updateReserve($vars) {
  $id = $vars["id"];
  $reserve = Fetch::find("reserves", $id);
  if (!Allow::reserve($reserve)) abort(403);
  if (strtotime($_POST["start"]) + 3600 > strtotime($_POST["end"])) {
    Session::set("toast", ["danger", "エラー！<br>開始・終了時間は".(MIN_INTERVAL / 3600)."時間以上の間隔をあけてください"]);
    back();
  }
  DB::update("reserves", $reserve["id"], [
    "start" => $_POST["start"],
    "end" => $_POST["end"],
    "number_list_id" => $_POST["number_list_id"] ? $_POST["number_list_id"] : null
  ]);
  Session::set("toast", ["success", "予約の基本設定を変更しました"]);
  back();
}

function deleteReserve($vars) {
  $id = $vars["id"];
  $reserve = Fetch::find("reserves", $id);
  if (!Allow::reserve($reserve)) abort(403);
  DB::delete("reserves", $id);
  Session::set("toast", ["success", "予約を削除しました"]);
  redirect("/surveys/{$reserve["survey_id"]}");
}

function storeReservesAreas($vars) {
  $reserve = Fetch::find("reserves", $vars["id"]);
  if (!Allow::reserve($reserve)) abort(403);
  DB::insert("reserves_areas", [
    "reserve_id" => $reserve["id"],
    "area_id" => $_POST["area_id"]
  ]);
  Session::set("toast", ["success", "エリアを追加しました"]);
  back();
}

function storeReservesAreasByWord($vars) {
  $reserve = Fetch::find("reserves", $vars["id"]);
  if (!Allow::reserve($reserve)) abort(403);
  $word = $_POST["word"];
  $areas = Fetch::get2("areas", [["title", "LIKE", "%{$word}%"]]);
  $count = 0;
  foreach ($areas as $area) {
    if (!Fetch::find2("reserves_areas", [
      ["reserve_id", "=", $reserve["id"]],
      ["area_id", "=", $area["id"]],
    ])) {
      $count++;
      DB::insert("reserves_areas", [
        "reserve_id" => $reserve["id"],
        "area_id" => $area["id"]
      ]);
    }
  }
  Session::set("toast", ["success", "{$count}件のエリアを追加しました"]);
  redirect("/reserves/{$reserve["id"]}#area");
}

function deleteReservesAreas($vars) {
  $id = $vars["id"];
  $ra = Fetch::find("reserves_areas", $id);
  if (!Allow::ra($ra)) abort(403);
  $ra["reserve"] = Fetch::find("reserves", $ra["reserve_id"]);
  DB::delete("reserves_areas", $id);
  Session::set("toast", ["danger", "エリアを削除しました"]);
  redirect("/reserves/{$ra["reserve"]["id"]}#area");
}

