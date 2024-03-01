<?php 

function area($vars) {
  $area_id = $vars["id"];
  $area = Fetch::find("areas", $area_id);
  $area["stations"] = Fetch::get("stations", $area["id"], "area_id");
  $survey = Fetch::find("surveys", $area["survey_id"]);
  if (!Allow::survey($survey)) abort(403);
  
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
  // redirect("/areas/{$area_id}");
  back();
}