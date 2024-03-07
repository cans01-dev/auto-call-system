<?php

function new_pdo() {
  try {
    $pdo = new PDO(
      $_ENV["DB_PREFIX"]."dbname=".$_ENV["DB_NAME"].";host=".$_ENV["DB_HOST"],
      $_ENV["DB_USERNAME"],
      $_ENV["DB_PASSWORD"]
    );
    return $pdo;
  } catch (PDOException $e) {
    exit($e->getMessage());
  }  
}

function authenticate($username, $password, $http_authorization) {
  $credentials = explode(":", substr($http_authorization, 6));
  return $credentials[0] === $username && $credentials[1] === $password;
}

function send_file(CURLFile $curl_file, $url, $header) {
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
  $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
  curl_close($ch);

  return [$response, $http_code];
}

function error_response($message) {
  header("400 Bad Request");
  exit(json_encode(["message: {$message}"], JSON_PRETTY_PRINT));
}