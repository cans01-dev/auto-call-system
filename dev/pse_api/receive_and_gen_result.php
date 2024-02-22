<?php

require dirname(__DIR__, 2)."/config.php";
require dirname(__DIR__, 2)."/models/Fetch.php";
require dirname(__DIR__, 2)."/models/DB.php";
require dirname(__DIR__, 2)."/functions.php";
require dirname(__DIR__, 2)."/api/functions.php";

$pdo = new_pdo();

if ($_SERVER["REQUEST_METHOD"] === "POST") {
  if (!$file_path = upload_file($_FILES["file"])) exit("ファイルのアップロードに失敗しました");
  $reserve_info = json_decode(file_get_contents($file_path), true);
  echo "予約情報ファイルを正常に受信しました" . PHP_EOL;

  [$json, $file_path] = gen_result_sample($reserve_info, [1,1,1,1,2,3,4,6]);
  
  file_put_contents($file_path, $json);
  echo "サンプル結果ファイルを生成しました: {$file_path}" . PHP_EOL;
}

?>

<h2>予約情報ファイルを受信、サンプル結果ファイルを生成、送信</h2>
<form method="post" enctype="multipart/form-data">
  <label>予約情報ファイル</label>
  <input type="file" name="file">
  <button type="submit">実行</button>
</form>