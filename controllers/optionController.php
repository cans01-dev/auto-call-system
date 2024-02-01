<?php 

function storeOption() {
  $faq = Fetch::find("faqs", $_POST["faq_id"]);
  if (!Allow::faq($faq)) abort(403);
  $options = Fetch::get("options", $faq["id"], "faq_id");
  DB::insert("options", [
    "faq_id" => $faq["id"],
    "title" => $_POST["title"],
    "dial" => count($options) ? max(array_column($options, "dial")) + 1 : 0,
    "next_faq_id" => $faq["id"]
  ]);
  $id = DB::lastInsertId();
  Session::set("toast", ["success", "選択肢を新規作成しました"]);
  back();
}

function updateOption($vars) {
  $id = $vars["id"];
  $option = Fetch::find("options", $id);
  if (!Allow::option($option)) abort(403);
  $next_type = substr($_POST["next"], 0, 1);
  $next_id = substr($_POST["next"], 1);
  DB::update("options", $id, [
    "title" => $_POST["title"],
    "next_ending_id" => $next_type == "e" ? $next_id : null,
    "next_faq_id" => $next_type == "f" ? $next_id : null
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

  $target_options = Fetch::get2("options", [
    ["faq_id", "=", $option["faq_id"]],
    ["dial", ">", $option["dial"]]
  ]);
  foreach ($target_options as $to) {
    DB::update("options", $to["id"], [
      "dial" => $to["dial"] - 1
    ]);
  }

  Session::set("toast", ["info", "選択肢を削除しました"]);
  redirect("/faqs/{$option["faq_id"]}");
}