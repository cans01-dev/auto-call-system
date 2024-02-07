<?php

require "../config.php";
require "../models/Fetch.php";
require "../models/DB.php";
require "../functions.php";
require "./api_functions.php";

$pdo = new_pdo();

if ($_SERVER["REQUEST_METHOD"] === "POST") {
  if (!authenticate("admin", "test", $_SERVER["HTTP_AUTHORIZATION"])) exit("エラー: 認証情報が不正");
  if (!$file_path = upload_file($_FILES["file"])) exit("ファイルのアップロードに失敗しました");
  $reserve_info = json_decode(file_get_contents($file_path), true);
  echo "予約情報ファイルを正常に受信しました" . PHP_EOL;

  [$result_info_array, $file_name] = gen_result_info_array($reserve_info, [1,1,1,1,2,3,4,6]);
  $array_json = json_encode($result_info_array, JSON_PRETTY_PRINT);
  $file_path = dirname(__DIR__)."/storage/outputs/{$file_name}";
  file_put_contents($file_path, $array_json);
  echo "サンプル結果ファイルを生成しました: {$file_path}" . PHP_EOL;

  $url = url("/api/receive_result.php");
  $curl_file = new CURLFile($file_path);
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