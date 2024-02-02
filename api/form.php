<?php 

if ($_SERVER["REQUEST_METHOD"] === "POST") {
  $curl_file = new CURLFile($_FILES['file']["tmp_name"], $_FILES['file']["type"], $_FILES['file']["name"]);

  $ch = curl_init();
  
  curl_setopt_array($ch, [
    CURLOPT_URL => "http://localhost:8080/api/receive_file.php",
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_HTTPHEADER => [
      "Authorization: Basic YWRtaW46dGVzdA=="
    ],
    CURLOPT_POST => true,
    CURLOPT_POSTFIELDS => [
      'file' => $curl_file
    ],
  ]);
  
  $response =  curl_exec($ch);
  
  curl_close($ch);
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Document</title>
</head>
<body>
  <h3>結果ファイル送信テスト</h3>
  <form enctype="multipart/form-data" method="post">
    <input type="file" name="file" required>
    <button type="submit">送信</button>
  </form>
  <div style="border: 1px solid #ccc; padding: 12px;">
    <?= $response ?? "" ?>
  </div>
  <hr>
  <a href="/api/send_file.php">予約情報ファイル生成ページ</a>
  <a href="/api/gen_result_file.php">結果ファイル生成ページ</a>
</body>
</html>