<?php 

function storeFaq() {
  $survey = Fetch::find("surveys", $_POST["survey_id"]);
  if (!Allow::survey($survey)) abort(403);
  $faqs = Fetch::get("faqs", $survey["id"], "survey_id");
  DB::insert("faqs", [
    "survey_id" => $survey["id"],
    "title" => $_POST["title"],
    "order_num" => count($faqs) ? max(array_column($faqs, "order_num")) + 1 : 0
  ]);
  $id = DB::lastInsertId();
  Session::set("toast", ["success", "質問を新規作成しました"]);
  redirect("/faqs/{$id}");
}

function updatefaq($vars) {
  $id = $vars["id"];
  $faq = Fetch::find("faqs", $id);
  if (!Allow::faq($faq)) abort(403);
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
  if (!Allow::faq($faq)) abort(403);
  DB::delete("faqs", $id);
  Session::set("toast", ["info", "選択肢を削除しました"]);
  redirect("/surveys/{$faq["survey_id"]}");
}

function orderFaq($vars) {
  $id = $vars["id"];
  $to = $_POST["to"];
  $faq1 = Fetch::find("faqs", $id);
  if (!Allow::faq($faq1)) abort(403);
  if ($to === "up") {
    $faq2 = Fetch::find2("faqs", [
      ["order_num", "=", $faq1["order_num"] - 1], 
      ["survey_id", "=", $faq1["survey_id"]]
    ]);
  } elseif ($to === "down") {
    $faq2 = Fetch::find2("faqs", [
      ["order_num", "=", $faq1["order_num"] + 1], 
      ["survey_id", "=", $faq1["survey_id"]]
    ]);
  }
  if (!$faq2) abort(500);
  DB::exchangeColumn("faqs", $faq1, $faq2, "order_num");
  Session::set("toast", ["success", "質問の並び順を変更しました"]);
  back("faqs");
}
