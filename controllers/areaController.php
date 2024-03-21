<?php 

function area($vars) {
  $area_id = $vars["id"];
  $area = Fetch::find("areas", $area_id);
  $area["stations"] = Fetch::get("stations", $area["id"], "area_id");
  $survey = Fetch::find("surveys", $area["survey_id"]);
  if (Auth::user()["status"] !== 1) if (!Allow::survey($survey)) abort(403);
  
  require_once "./views/pages/area.php";
}

function storeArea() {
  $survey = Fetch::find("surveys", $_POST["survey_id"]);
  if (!Allow::survey($survey)) abort(403);

  DB::insert("areas", [
    "title" => $_POST["title"],
    "survey_id" => $survey["id"]
  ]);
  $area_id = DB::lastInsertId();

  Session::set("toast", ["success", "マイエリアを新規登録しました"]);
  redirect("/areas/{$area_id}");
}

function updateArea($vars) {
  $area = Fetch::find("areas", $vars["id"]);
  if (!Allow::area($area)) abort(403);
  
  DB::update("areas", $area["id"], [
    "title" => $_POST["title"]
  ]);

  Session::set("toast", ["success", "マイエリアを更新しました"]);
  back();
}

function deleteArea($vars) {
  $area = Fetch::find("areas", $vars["id"]);
  if (!Allow::area($area)) abort(403);

  DB::delete("areas", $area["id"]);

  Session::set("toast", ["info", "マイエリアを削除しました"]);
  redirect($_POST["redirect"]);
}

function storeStation($vars) {
  $area = Fetch::find("areas", $vars["id"]);
  if (!Allow::area($area)) abort(403);

  DB::insert("stations", [
    "prefix" => $_POST["prefix"],
    "area_id" => $area["id"]
  ]);

  Session::set("toast", ["success", "マイエリアに局番を追加しました"]);
  back();
}

function deleteStation($vars) {
  $station = Fetch::find("stations", $vars["id"]);
  if (!Allow::station($station)) abort(403);

  DB::delete("stations", $station["id"]);

  Session::set("toast", ["info", "マイエリアから局番を削除しました"]);
  back();
}