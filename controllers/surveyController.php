<?php 

function storeSurvey() {
  global $pdo;
  $stmt = $pdo->prepare("INSERT INTO surveys (user_id, title, note) VALUES (:user_id, :title, :note)");
  $stmt->execute([
    ":user_id" => Auth::user()["id"],
    ":title" => $_POST["title"],
    ":note" => $_POST["note"]
  ]);
  $id = $pdo->lastInsertId();
  Session::set("toast", ["success", "アンケートを新規作成しました"]);
  redirect("/surveys/{$id}");
}

function updateSurvey($vars) {
  $id = $vars["id"];
  $survey = Fetch::find("surveys", $id);
  if ($survey["user_id"] !== Auth::user()["id"]) abort(403);
  global $pdo;
  $stmt = $pdo->prepare("UPDATE surveys SET title = :title, note = :note WHERE id = :id");
  $stmt->execute([
    ":id" => $id,
    ":title" => $_POST["title"],
    ":note" => $_POST["note"]
  ]);
  Session::set("toast", ["success", "アンケートの設定を変更しました"]);
  back();
}