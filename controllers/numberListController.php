<?php

function storeNumberList($vars) {
  $survey = Fetch::find("surveys", $vars["id"]);
  if (!Allow::survey($survey)) abort(403);
  DB::insert("number_lists", [
    "title" => $_POST["title"],
    "survey_id" => $survey["id"]
  ]);
  $number_list_id = DB::lastInsertId();

  if (!$file_path = upload_file($_FILES["file"])) exit("ファイルのアップロードに失敗しました");
  $fp = fopen($file_path, "r");
  $count = 0;
  while($line = fgetcsv($fp)){
    if (preg_match('/^0[789]0-[0-9]{4}-[0-9]{4}$/', $number = $line[0])) {
      DB::insert("numbers", [
        "number_list_id" => $number_list_id,
        "number" => $number
      ]);
      $count++;
    }
  }
  fclose($fp);

  Session::set("toast", ["success", "{$count}件の電話番号をマイリストを登録しました"]);
  back();
}

function updateNumberList($vars) {
  $number_list = Fetch::find("number_list", $vars["id"]);
  if (!Allow::number_list($number_list)) abort(403);
  DB::update("number_lists", $number_list["id"], [
    "title" => $_POST["title"]
  ]);

  Session::set("toast", ["info", "マイリストを更新しました"]);
  back();
}

function deleteNumberList($vars) {
  $number_list = Fetch::find("number_lists", $vars["id"]);
  if (!Allow::number_list($number_list)) abort(403);
  DB::delete("number_lists", $number_list["id"]);

  Session::set("toast", ["info", "マイリストを削除しました"]);
  back();
}