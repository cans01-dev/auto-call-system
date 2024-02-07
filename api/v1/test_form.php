<?php 

require "api_functions.php";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
  $curl_file = new CURLFile($_FILES['file']["tmp_name"], $_FILES['file']["type"], $_FILES['file']["name"]);
  
  $response = send_file($curl_file, "http://localhost:8080/api/receive_file.php", [
    "Authorization: Basic YWRtaW46dGVzdA=="
  ]);
}

?>

<h3>結果ファイル受信テスト</h3>
<form enctype="multipart/form-data" method="post">
  <input type="file" name="file" required>
  <button type="submit">送信</button>
</form>
<div style="border: 1px solid #ccc; padding: 12px;">
  <h4>レスポンス</h4>
  <?= $response ?? "" ?>
</div>