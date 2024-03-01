<?php 

function faq($vars) {
  $id = $vars["id"];
  $faq = Fetch::find("faqs", $id);
  $faq["options"] = Fetch::get("options", $faq["id"], "faq_id", "dial");
  $survey = Fetch::find("surveys", $faq["survey_id"]);
  $survey["faqs"] = Fetch::get("faqs", $survey["id"], "survey_id", "order_num");
  if (!Allow::faq($faq)) abort(403);

  foreach ($faq["options"] as $option) {
    if ($option["next_faq_id"]) {
      $index = array_search($option, $faq["options"]);
      $faq["options"][$index]["next_faq"] = Fetch::find("faqs", $option["next_faq_id"]);
    }
  }

  require_once "./views/pages/faq.php";
}

function storeFaq() {
  $survey = Fetch::find("surveys", $_POST["survey_id"]);
  if (!Allow::survey($survey)) abort(403);
  $faqs = Fetch::get("faqs", $survey["id"], "survey_id");
  DB::insert("faqs", [
    "survey_id" => $survey["id"],
    "title" => $_POST["title"],
    "text" => $_POST["text"],
    "order_num" => count($faqs) ? max(array_column($faqs, "order_num")) + 1 : 0
  ]);
  $faq_id = DB::lastInsertId();
  $file_name = uniqid("f{$faq_id}_") . ".wav";
  file_put_contents(user_dir($file_name, $survey["user_id"]), text_to_speech($_POST["text"], $survey["voice_name"]));
  DB::update("faqs", $faq_id, [
    "voice_file" => $file_name
  ]);
  DB::insert("options", [
    "faq_id" => $faq_id,
    "title" => "聞き直し",
    "dial" => 0,
    "next_faq_id" => $faq_id
  ]);
  Session::set("toast", ["success", "質問を新規作成しました"]);
  redirect("/faqs/{$faq_id}");
}

function updatefaq($vars) {
  $id = $vars["id"];
  $faq = Fetch::find("faqs", $id);
  $survey = Fetch::find("surveys", $faq["survey_id"]);
  if (!Allow::faq($faq)) abort(403);
  $file_name = uniqid("f{$faq["id"]}_") . ".wav";
  file_put_contents(user_dir($file_name, $survey["user_id"]), text_to_speech($_POST["text"], $survey["voice_name"]));
  DB::update("faqs", $id, [
    "title" => $_POST["title"],
    "text" => $_POST["text"],
    "voice_file" => $file_name
  ]);
  Session::set("toast", ["success", "質問の設定を変更しました"]);
  back();
}

function deleteFaq($vars) {
  $id = $vars["id"];
  $faq = Fetch::find("faqs", $id);
  if (!Allow::faq($faq)) abort(403);

  $target_options = Fetch::get2("options", [
    ["next_faq_id", "=", $faq["id"]]
  ]);
  foreach ($target_options as $to) {
    $row_count = DB::update("options", $to["id"], [
      "next_faq_id" => $to["faq_id"]
    ]);
  }

  DB::delete("faqs", $id);

  $target_faqs = Fetch::get2("faqs", [
    ["survey_id", "=", $faq["survey_id"]],
    ["order_num", ">", $faq["order_num"]]
  ]);
  foreach ($target_faqs as $tf) {
    DB::update("faqs", $tf["id"], [
      "order_num" => $tf["order_num"] - 1
    ]);
  }

  $msg = $row_count ? "<br>{$row_count}件の選択肢を「聞き直し」に変更しました" : null;
  Session::set("toast", ["success", "質問を削除しました{$msg}"]);
  redirect("/surveys/{$faq["survey_id"]}");
}

function orderFaq($vars) {
  $faq_id = $vars["id"];
  $to = $_POST["to"];
  $faq1 = Fetch::find("faqs", $faq_id);
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

  // 並び替えたときのリダイレクトはssy
  Session::set("toast", ["success", "質問の並び順を変更しました"]);
  back("faq{$faq_id}");
}
