<?php

function new_pdo() {
  try {
    $pdo = new PDO(
      DB_PREFIX."dbname=".DB_NAME.";host=".DB_HOST,
      DB_USERNAME,
      DB_PASSWORD
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

// function upload_file($file, $file_path=null) {
//   if (!$file_path) $file_path = dirname(__DIR__)."/storage/uploads/{$file["name"]}";
//   if ($file) {
//     if (is_uploaded_file($file["tmp_name"])) {
//       if (move_uploaded_file($file["tmp_name"], $file_path)) {
//         return $file_path;
//       }
//     }
//   }
//   return false;
// }

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