<?php 

function storeOption() {
  global $pdo;
  $stmt = $pdo->prepare("INSERT INTO options (faq_id, title) VALUES (:faq_id, :title)");
  $faq = Fetch::find("faqs", $_POST["faq_id"]);
  $survey = Fetch::find("surveys", $faq["survey_id"]);
  if ($survey["user_id"] !== Auth::user()["id"]) abort(403);
  $stmt->execute([
    ":faq_id" => $faq["id"],
    ":title" => $_POST["title"]
  ]);
  $id = $pdo->lastInsertId();
  Session::set("toast", ["success", "選択肢を新規作成しました"]);
  redirect("/options/{$id}");
}

function updateoption($vars) {
  $id = $vars["id"];
  $option = Fetch::find("options", $id);
  $survey = Fetch::find("surveys", $option["survey_id"]);
  if ($survey["user_id"] !== Auth::user()["id"]) abort(403);
  global $pdo;
  $stmt = $pdo->prepare("UPDATE options SET title = :title, text = :text WHERE id = :id");
  $stmt->execute([
    ":id" => $id,
    ":title" => $_POST["title"],
    ":text" => $_POST["text"]
  ]);
  Session::set("toast", ["success", "質問の設定を変更しました"]);
  back();
}