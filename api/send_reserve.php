<?php

# コマンドラインから実行

require dirname(__DIR__)."/config.php";
require dirname(__DIR__)."/models/Fetch.php";
require dirname(__DIR__)."/models/DB.php";
require __DIR__."/api_functions.php";

$pdo = new_pdo();

$date = $argv[1];

$reserves = Fetch::get2("reserves", [
  ["date", "=", $date ?? date("Y-m-d")]
]);

foreach ($reserves as $reserve) {
  $reserve_info_array = gen_reserve_info_array($reserve);

  $array_json = json_encode($reserve_info_array, JSON_PRETTY_PRINT);
  $file_path = "/storage/outputs/ac{$reserve["id"]}_{$reserve["date"]}.json";
  file_put_contents(dirname(__DIR__).$file_path, $array_json);
  echo "予約情報ファイルを生成しました: {$file_path}" . PHP_EOL;

  DB::update("reserves", $reserve["id"], [
    "status" => "1",
    "reserve_file" => basename($file_path)
  ]);
  echo <<<EOL
  [UPDATE reserve({$reserve["id"]})]
  status => "1",
  reserve_file => {$file_path}
  EOL;

  $url = SEND_FILE_URL;
  $curl_file = new CURLFile(dirname(__DIR__).$file_path);
  [$response, $http_code] = send_file($curl_file, $url, [
    "Authorization: " . SEND_FILE_AUTHORIZATION
  ]);
  echo <<<EOL
  sent file to {$url}"
  [response ({$url})]
  http code: {$http_code}
  {$response}
  EOL;
}