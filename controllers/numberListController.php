<?php

function numberList($vars) {
  $number_list = Fetch::find("number_lists", $vars["id"]);
  $survey = Fetch::find("surveys", $number_list["survey_id"]);
  if (Auth::user()["status"] !== 1) if (!Allow::survey($survey)) abort(403);

  $stats["all_numbers"] = Fetch::query(
    "SELECT COUNT(*) FROM numbers as n
    WHERE number_list_id = {$number_list["id"]}",
    "fetchColumn"
  );

  $stats["all_calls"] = Fetch::query(
    "SELECT COUNT(*) FROM calls as c
    JOIN reserves as r ON c.reserve_id = r.id
    WHERE r.number_list_id = {$number_list["id"]}",
    "fetchColumn"
  );

  $stats["responsed_calls"] = Fetch::query(
    "SELECT COUNT(*) FROM calls as c
    JOIN reserves as r ON c.reserve_id = r.id
    WHERE r.number_list_id = {$number_list["id"]}
    AND c.status = 1",
    "fetchColumn"
  );

  $stats["success_calls"] = Fetch::query(
    "SELECT COUNT(*) FROM answers as a JOIN calls as c ON a.call_id = c.id
    JOIN reserves as r ON c.reserve_id = r.id
    WHERE r.number_list_id = {$number_list["id"]}
    AND a.option_id IN (
      SELECT o.id FROM options as o JOIN faqs as f ON o.faq_id = f.id
      WHERE f.survey_id = {$survey["id"]} AND o.next_ending_id = {$survey["success_ending_id"]}
    )",
    "fetchColumn"
  );

  $stats["all_actions"] = Fetch::query(
    "SELECT COUNT(*) FROM answers as a
    JOIN options as o ON a.option_id = o.id
    JOIN calls as c ON a.call_id = c.id
    JOIN reserves as r ON c.reserve_id = r.id
    WHERE r.number_list_id = {$number_list["id"]}
    AND (o.next_faq_id <> o.faq_id OR o.next_ending_id IS NOT NULL)",
    "fetchColumn"
  );

  $stats["action_calls"] = count(Fetch::query(
    "SELECT * FROM answers as a
    JOIN calls as c ON a.call_id = c.id
    JOIN reserves as r ON c.reserve_id = r.id
    WHERE r.number_list_id = {$number_list["id"]}
    GROUP BY c.id
    HAVING COUNT(*) > 0",
    "fetchAll"
  ));

  $stats["total_duration"] = Fetch::query(
    "SELECT SUM(c.duration) as total_duration FROM calls as c
    JOIN reserves as r ON c.reserve_id = r.id
    WHERE r.number_list_id = {$number_list["id"]}",
    "fetchColumn"
  );

  $number_list["stats"] = $stats;

  require "./views/pages/numberList.php";
}

function storeNumberList($vars) {
  $survey = Fetch::find("surveys", $vars["id"]);
  if (!Allow::survey($survey)) abort(403);
  if (count(Fetch::get("number_lists", $survey["id"], "survey_id")) > 9) {
    Session::set("toast", ["danger", "エラー！<br>予約パターンは最大10個までしか登録できません"]);
    back();
  }
  DB::insert("number_lists", [
    "title" => $_POST["title"],
    "survey_id" => $survey["id"]
  ]);
  $number_list_id = DB::lastInsertId();

  Session::set("toast", ["success", "マイリストを作成しました"]);
  redirect("/number_lists/{$number_list_id}");
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
  redirect($_POST["redirect"]);
}

function storeNumber($vars) {
  $number_list = Fetch::find("number_lists", $vars["id"]);
  if (!Allow::number_list($number_list)) abort(403);

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


  $fp = fopen($file_path, "r");
  [$numbers, $error_numbers, $dup_numbers] = store_number_csv($fp, $number_list["id"]);
  fclose($fp);

  foreach (array_chunk($numbers, 10000) as $chunk) {
    $insert_values = array_str(
      array_map(
        function ($number) use ($number_list) {
          return "({$number_list["id"]}, '{$number}')";
        },
        $chunk
      )
    );
    DB::query("INSERT INTO numbers (number_list_id, number) VALUES {$insert_values}");
  }

  $success_count = count($numbers);
  Session::set("storeNumberCsvResult", [$numbers, $error_numbers, $dup_numbers]);
  Session::set("toast", ["success", "成功: {$success_count}件の電話番号を追加しました"]);
  back();
}

function deleteNumber($vars) {
  $number = Fetch::find("numbers", $vars["id"]);
  if (!Allow::number($number)) abort(403);

  DB::delete("numbers", $number["id"]);

  Session::set("toast", ["info", "電話番号を削除しました"]);
  back();
}