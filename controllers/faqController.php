<?php 

function storeFaq() {
  $survey = Fetch::find("surveys", $_POST["survey_id"]);
  if ($survey["user_id"] !== Auth::user()["id"]) abort(403);
  DB::insert("faqs", [
    "survey_id" => $survey["id"],
    "title" => $_POST["title"] 
  ]);
  $id = DB::lastInsertId();
  Session::set("toast", ["success", "質問を新規作成しました"]);
  redirect("/faqs/{$id}");
}

function updatefaq($vars) {
  $id = $vars["id"];
  $faq = Fetch::find("faqs", $id);
  $survey = Fetch::find("surveys", $faq["survey_id"]);
  if ($survey["user_id"] !== Auth::user()["id"]) abort(403);
  DB::update("faqs", $id, [
    "title" => $_POST["title"],
    "text" => $_POST["text"]
  ]);
  Session::set("toast", ["success", "質問の設定を変更しました"]);
  back();
}

function deleteFaq($vars) {
  $id = $vars["id"];
  $faq = Fetch::find("faqs", $id);
  $survey = Fetch::find("surveys", $faq["survey_id"]);
  global $pdo;
  $stmt = $pdo->prepare("DELETE FROM faqs WHERE id = :id");
  $stmt->execute([":id" => $id]);
  Session::set("toast", ["info", "選択肢を削除しました"]);
  redirect("/surveys/{$survey["id"]}");
}