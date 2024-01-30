<?php 

function storeReserve() {
  $survey = Fetch::find("surveys", $_POST["survey_id"]);
  if (!Allow::survey($survey)) abort(403);
  $start = $_POST["start"] ?? DEFAULT_START_TIME;
  $end = $_POST["end"] ?? DEFAULT_END_TIME;
  if (strtotime($start) + 3600 > strtotime($end)) {
    Session::set("toast", ["danger", "エラー！<br>開始・終了時間は".(MIN_INTERVAL / 3600)."時間以上の間隔をあけてください"]);
    redirect("/surveys/{$_POST["survey_id"]}#calendar");
  }
  DB::insert("reserves", [
    "survey_id" => $_POST["survey_id"],
    "date" => $_POST["date"],
    "start" => $start,
    "end" => $end
  ]);
  $id = DB::lastInsertId();

  if ($favorite = Fetch::find("favorites", @$_POST["favorite_id"])) {
    $favorite["areas"] = Fetch::areasByFavoriteId($favorite["id"]);
    DB::update("reserves", $id, [
      "start" => $favorite["start"],
      "end" => $favorite["end"]
    ]);
    foreach ($favorite["areas"] as $area) {
      DB::insert("reserves_areas", [
        "reserve_id" => $id,
        "area_id" => $area["id"]
      ]);
    }
    Session::set("toast", ["success", "パターンから予約を作成しました"]);
    redirect("/surveys/{$_POST["survey_id"]}#calendar");
  }
  Session::set("toast", ["success", "予約を作成しました"]);
  redirect("/reserves/{$id}");
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
    "end" => $_POST["end"]
  ]);
  Session::set("toast", ["success", "予約の開始・終了時間を変更しました"]);
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

function storeReservesAreas() {
  $reserve = Fetch::find("reserves", $_POST["reserve_id"]);
  if (!Allow::reserve($reserve)) abort(403);
  DB::insert("reserves_areas", [
    "reserve_id" => $_POST["reserve_id"],
    "area_id" => $_POST["area_id"]
  ]);
  Session::set("toast", ["success", "エリアを追加しました"]);
  redirect("/reserves/{$_POST["reserve_id"]}#area");
}

function storeReservesAreasByWord() {
  $reserve = Fetch::find("reserves", $_POST["reserve_id"]);
  if (!Allow::reserve($reserve)) abort(403);
  $word = $_POST["word"];
  $areas = Fetch::get2("areas", [["title", "LIKE", "%{$word}%"]]);
  $count = 0;
  foreach ($areas as $area) {
    if (!Fetch::find2("reserves_areas", [
      ["reserve_id", "=", $_POST["reserve_id"]],
      ["area_id", "=", $area["id"]],
    ])) {
      $count++;
      DB::insert("reserves_areas", [
        "reserve_id" => $_POST["reserve_id"],
        "area_id" => $area["id"]
      ]);
    }
  }
  Session::set("toast", ["success", "{$count}件のエリアを追加しました"]);
  redirect("/reserves/{$_POST["reserve_id"]}#area");
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

