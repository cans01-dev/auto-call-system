<?php

function numberList($vars) {
  $number_list = Fetch::find("number_lists", $vars["id"]);
  $survey = Fetch::find("surveys", $number_list["survey_id"]);

  $page = @$_GET["page"] ?? 1;
  $limit = 100;
  $sql = "SELECT *, n.id as id, a.title as area_title, n.number as number FROM numbers as n
          LEFT OUTER JOIN (
            SELECT c.id as call_id, c.number FROM calls as c
            JOIN reserves as r ON c.reserve_id = r.id
            WHERE r.survey_id = {$survey["id"]}
          ) as c ON n.number = c.number
          LEFT OUTER JOIN stations as s ON n.number LIKE s.prefix+'%'
          LEFT OUTER JOIN areas as a ON s.area_id = a.id
          WHERE n.number_list_id = {$number_list["id"]}";
  $numbers = Fetch::query($sql, "fetchAll");

  if (!Allow::survey($survey)) abort(403);

  require "./views/pages/numberList.php";
}

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
  $number_list = Fetch::find("number_lists", $vars["id"]);
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
  redirect("/surveys/{$number_list["survey_id"]}");
}

function storeNumber($vars) {
  $number_list = Fetch::find("number_lists", $vars["id"]);
  if (!Allow::number_list($number_list)) abort(403);

  # 電話番号の整形
  $number = $_POST["number"];
  if (!preg_match('/^0[789]0-[0-9]{4}-[0-9]{4}$/', $number)) {
    if ($number[0] !== "0") $number = "0" . $number;
    if (strlen($number) < 13) $number = substr_replace(substr_replace($number, "-", 3, 0), "-", 8, 0);
  }

  if (Fetch::find2("numbers", [
    ["number_list_id", "=", $number_list["id"]],
    ["number", "=", $number]
  ])) {
    Session::set("toast", ["danger", "この電話番号はすでに追加されています"]);
    back();
  }
  DB::insert("numbers", [
    "number_list_id" => $number_list["id"],
    "number" => $number
  ]);

  Session::set("toast", ["success", "電話番号を追加しました"]);
  back();
}

function storeNumberCsv($vars) {
  $number_list = Fetch::find("number_lists", $vars["id"]);
  if (!Allow::number_list($number_list)) abort(403);

  if (!$file_path = upload_file($_FILES["file"])) exit("ファイルのアップロードに失敗しました");
  $fp = fopen($file_path, "r");
  [$success, $error, $dup] = [0, 0, 0];
  while($line = fgetcsv($fp)){
    # 電話番号の整形
    $number = $line[0];
    if (!preg_match('/^0[789]0-[0-9]{4}-[0-9]{4}$/', $number)) {
      if ($number[0] !== "0") $number = "0" . $number;
      if (strlen($number) < 13) $number = substr_replace(substr_replace($number, "-", 3, 0), "-", 8, 0);
    }

    if (preg_match('/^0[789]0-[0-9]{4}-[0-9]{4}$/', $number)) {
      if (!Fetch::find2("numbers", [
        ["number_list_id", "=", $number_list["id"]],
        ["number", "=", $number]
      ])) {
        DB::insert("numbers", [
          "number_list_id" => $number_list["id"],
          "number" => $number
        ]);
        $success++;
        continue;
      }
      $dup++;
      continue;
    }
    $error++;
  }
  fclose($fp);

  Session::set("storeNumberCsvResult", [
    "success" => $success,
    "error" => $error,
    "dup" => $dup
  ]);
  Session::set("toast", ["success", "成功: {$success}件の電話番号を追加しました"]);
  back();
}

function deleteNumber($vars) {
  $number = Fetch::find("numbers", $vars["id"]);
  if (!Allow::number($number)) abort(403);

  DB::delete("numbers", $number["id"]);

  Session::set("toast", ["info", "電話番号を削除しました"]);
  back();
}