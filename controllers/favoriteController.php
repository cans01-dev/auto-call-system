<?php 

function storeFavorite() {
  global $pdo;
  $stmt = $pdo->prepare("INSERT INTO favorites (survey_id, reserve_id, title, color) VALUES (:survey_id, :reserve_id, :title, :color)");
  $stmt->execute([
    ":survey_id" => $_POST["survey_id"],
    ":reserve_id" => $_POST["reserve_id"],
    ":title" => $_POST["title"],
    ":color" => $_POST["color"]
  ]);
  $id = $pdo->lastInsertId();
  Session::set("toast", ["success", "お気に入り設定を追加しました"]);
  redirect("/favorites/{$id}");
}

function deleteFavorite($vars) {
  $id = $vars["id"];
  $favorite = Fetch::find("favorites", $id);
  $survey = Fetch::find("surveys", $favorite["survey_id"]);
  global $pdo;
  $stmt = $pdo->prepare("DELETE FROM favorites WHERE id = :id");
  $stmt->execute([":id" => $id]);
  Session::set("toast", ["info", "お気に入り登録を削除しました"]);
  redirect("/surveys/{$survey["id"]}");
}