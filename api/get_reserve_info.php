<?php

# コマンドラインから実行

require dirname(__DIR__)."/config.php";
require dirname(__DIR__)."/models/Fetch.php";
require dirname(__DIR__)."/models/DB.php";
require __DIR__."/api_functions.php";

$pdo = new_pdo();
$array = [];
$date_pattern = "/^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[12][0-9]|3[01])$/";

if ($date = @$_GET["date"]) {
  if (!preg_match($date_pattern, $date)) {
    error_response("Invalid date input");
  }
} else {
  $date = date("Y-m-d");
}

if ($user_id = @$_GET["user_id"]) {
  if (!$user = Fetch::find("users", $user_id)) {
    error_response("User not found");
  }
} else {
  error_response("User_id is not defined");
}

if (!$reserves = Fetch::get2("reserves", [
  ["date", "=", $date]
])) {
  error_response("Reservation not set");
}

foreach ($reserves as $reserve) {
  $array["data"][] = gen_reserve_info_array($reserve);
  $file_path = "/storage/outputs/ac{$reserve["id"]}_{$reserve["date"]}.json";

  DB::update("reserves", $reserve["id"], [
    "status" => "1",
    "reserve_file" => basename($file_path)
  ]);
}
$array["message"] = "success";

$array_json = json_encode($array, JSON_PRETTY_PRINT);

file_put_contents(dirname(__DIR__).$file_path, $array_json);

header("200 OK");
// exit($array_json);
exit('<pre>'.$array_json.'</pre>');

?>