<?php 

function storeOption() {
  global $pdo;
  $stmt = $pdo->prepare("INSERT INTO options (faq_id, title, dial) VALUES (:faq_id, :title, :dial)");
  $faq = Fetch::find("faqs", $_POST["faq_id"]);
  $survey = Fetch::find("surveys", $faq["survey_id"]);
  if ($survey["user_id"] !== Auth::user()["id"]) abort(403);
  $stmt->execute([
    ":faq_id" => $faq["id"],
    ":title" => $_POST["title"],
    ":dial" => Fetch::maxDialInFaqId($faq["id"]) + 1
  ]);
  $id = $pdo->lastInsertId();
  Session::set("toast", ["success", "選択肢を新規作成しました"]);
  redirect("/options/{$id}");
}

function updateoption($vars) {
  $id = $vars["id"];
  $option = Fetch::find("options", $id);
  $faq = Fetch::find("faqs", $option["faq_id"]);
  $survey = Fetch::find("surveys", $faq["survey_id"]);
  if ($survey["user_id"] !== Auth::user()["id"]) abort(403);
  global $pdo;
  $stmt = $pdo->prepare("UPDATE options SET title = :title, is_last = :is_last, next_faq_id = :next_faq_id WHERE id = :id");
  $stmt->execute([
    ":id" => $id,
    ":title" => $_POST["title"],
    ":is_last" => !$_POST["next_faq_id"] ? 1 : 0, // 0であればtrue
    ":next_faq_id" => $_POST["next_faq_id"] ? $_POST["next_faq_id"] : null // 0でなければidを代入
  ]);
  Session::set("toast", ["success", "選択肢の設定を変更しました"]);
  back();
}

function upOption($vars) {
  $id = $vars["id"];
  // ここから
}