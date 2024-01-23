<?php 

function storeFaq() {
  global $pdo;
  $stmt = $pdo->prepare("INSERT INTO faqs (survey_id, title) VALUES (:survey_id, :title)");
  $survey = Fetch::find("surveys", $_POST["survey_id"]);
  if ($survey["user_id"] !== Auth::user()["id"]) abort(403);
  $stmt->execute([
    ":survey_id" => $survey["id"],
    ":title" => $_POST["title"]
  ]);
  $id = $pdo->lastInsertId();
  Session::set("toast", ["success", "質問を新規作成しました"]);
  redirect("/faqs/{$id}");
}

function updatefaq($vars) {
  $id = $vars["id"];
  $faq = Fetch::find("faqs", $id);
  $survey = Fetch::find("surveys", $faq["survey_id"]);
  if ($survey["user_id"] !== Auth::user()["id"]) abort(403);
  global $pdo;
  $stmt = $pdo->prepare("UPDATE faqs SET title = :title, text = :text WHERE id = :id");
  $stmt->execute([
    ":id" => $id,
    ":title" => $_POST["title"],
    ":text" => $_POST["text"]
  ]);
  Session::set("toast", ["success", "質問の設定を変更しました"]);
  back();
}