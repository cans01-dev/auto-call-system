<?php 

function storeOption() {
  $faq = Fetch::find("faqs", $_POST["faq_id"]);
  if (!Allow::faq($faq)) abort(403);
  $options = Fetch::get("options", $faq["id"], "faq_id");
  DB::insert("options", [
    "faq_id" => $faq["id"],
    "title" => $_POST["title"],
    "dial" => count($options) ? max(array_column($options, "dial")) + 1 : 0
  ]);
  $id = DB::lastInsertId();
  Session::set("toast", ["success", "選択肢を新規作成しました"]);
  back();
}

function updateOption($vars) {
  $id = $vars["id"];
  $option = Fetch::find("options", $id);
  if (!Allow::option($option)) abort(403);
  DB::update("options", $id, [
    "title" => $_POST["title"],
    "is_last" => !$_POST["next_faq_id"] ? 1 : 0,
    "next_faq_id" => $_POST["next_faq_id"] ? $_POST["next_faq_id"] : null
  ]);
  Session::set("toast", ["success", "選択肢の設定を変更しました"]);
  back();
}

function orderOption($vars) {
  $id = $vars["id"];
  $to = $_POST["to"];
  $option1 = Fetch::find("options", $id);
  if (!Allow::option($option1)) abort(403);

  if ($to === "up") {
    $option2 = Fetch::find2("options", [
      ["dial", "=", $option1["dial"] - 1], 
      ["faq_id", "=", $option1["faq_id"]]
    ]);
  } elseif ($to === "down") {
    $option2 = Fetch::find2("options", [
      ["dial", "=", $option1["dial"] + 1], 
      ["faq_id", "=", $option1["faq_id"]]
    ]);;
  }
  if (!$option2) abort(500);
  DB::exchangeColumn("options", $option1, $option2, "dial");
  Session::set("toast", ["success", "選択肢のダイヤルを変更しました"]);
  back("options");
}

function deleteOption($vars) {
  $id = $vars["id"];
  $option = Fetch::find("options", $id);
  if (!Allow::option($option)) abort(403);
  DB::delete("options", $id);
  Session::set("toast", ["info", "選択肢を削除しました"]);
  redirect("/faqs/{$option["faq_id"]}");
}