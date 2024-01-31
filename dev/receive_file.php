<?php

$credentials = explode(":", base64_decode(substr($_SERVER["HTTP_AUTHORIZATION"], 6)));

if ($credentials[0] === "admin" && $credentials[1] === "test") {
  if ($file = $_FILES["file"]) {
    echo "post.file is setted" . "<br>";
    if (is_uploaded_file($file["tmp_name"])) {
      echo "file.tmp_name is uploaded" . "<br>";
      if (move_uploaded_file($file["tmp_name"], "./uploads/" . $file["name"])) {
        echo "file.name is uploaded" . "<br>";
        print_r($file["name"]);

        header("200 OK");

        // 
        exit();
      }
    }
  }
} else {
  echo "認証情報が不正";
  exit();
}
