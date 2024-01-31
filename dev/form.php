<?php 

if ($_SERVER["REQUEST_METHOD"] === "POST") {
  $curl_file = new CURLFile($_FILES['file']["tmp_name"], $_FILES['file']["type"], $_FILES['file']["name"]);

  $ch = curl_init();
  
  curl_setopt_array($ch, [
    CURLOPT_URL => "http://localhost:8080/dev/receive_file.php",
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_HTTPHEADER => [
      "Authorization: Basic YWRtaW46dGVzdA=="
    ],
    CURLOPT_POST => true,
    CURLOPT_POSTFIELDS => [
      'file' => $curl_file
    ],
  ]);
  
  echo curl_exec($ch);
  
  curl_close($ch);

  exit();
}

?>

<form enctype="multipart/form-data" method="post">
  <input type="file" name="file">
  <button type="submit">送信</button>
</form>