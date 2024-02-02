<?php

function authenticate() {
  [$username, $password] = explode(":", base64_decode(substr($_SERVER["HTTP_AUTHORIZATION"], 6)));
  return $username === "admin" && $password === "test";
}

function upload_file($file) {
  if ($file) {
    if (is_uploaded_file($file["tmp_name"])) {
      $file_path = dirname(__DIR__)."/storage/uploads/{$file["name"]}";
      if (move_uploaded_file($file["tmp_name"], $file_path)) {
        return $file_path;
      }
    }
  }
  return false;
}

function send_file($file_path, $url, $header) {
  $curl_file = new CURLFile($file_path);

  $ch = curl_init();
  curl_setopt_array($ch, [
    CURLOPT_URL => $url,
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_HTTPHEADER => $header,
    CURLOPT_POST => true,
    CURLOPT_POSTFIELDS => [
      'file' => $curl_file
    ],
  ]);
  $response = curl_exec($ch);
  curl_close($ch);

  return $response;
}