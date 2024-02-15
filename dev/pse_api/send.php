<?php

require dirname(__DIR__, 2)."/config.php";
require dirname(__DIR__, 2)."/models/Fetch.php";
require dirname(__DIR__, 2)."/models/DB.php";
require dirname(__DIR__, 2)."/functions.php";
require dirname(__DIR__, 2)."/api/functions.php";

$pdo = new_pdo();

if ($_SERVER["REQUEST_METHOD"] === "POST") {
  $curl_file = new CURLFile($_FILES['file']["tmp_name"], $_FILES['file']["type"], $_FILES['file']["name"]);
  
  $response = send_file($curl_file, "http://localhost:8080/api/receive_result.php", [
    "Authorization: Basic YWRtaW46dGVzdA=="
  ]);
}

?>

<h3>結果ファイル送信</h3>
<form enctype="multipart/form-data" method="post">
  <input type="file" name="file" required>
  <button type="submit">送信</button>
</form>
<div style="border: 1px solid #ccc; padding: 12px;">
  <h4>レスポンス</h4>
  <?= print_r($response) ?? "" ?>
</div>