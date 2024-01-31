<?php

require "../config.php";
require "../models/Fetch.php";
require "../models/DB.php";

try {
	$pdo = new PDO(
		DB_PREFIX."dbname=".DB_NAME.";host=".DB_HOST,
		DB_USERNAME,
		DB_PASSWORD
	);
} catch (PDOException $e) {
	exit($e->getMessage());
}

$credentials = explode(":", base64_decode(substr($_SERVER["HTTP_AUTHORIZATION"], 6)));

if ($credentials[0] === "admin" && $credentials[1] === "test") {
  if ($file = $_FILES["file"]) {
    if (is_uploaded_file($file["tmp_name"])) {
      $file_path = "./uploads/{$file["name"]}";
      if (move_uploaded_file($file["tmp_name"], $file_path)) {
        echo "{$file["name"]} is uploaded" . PHP_EOL;
        header("200 OK");

        $array = json_decode(file_get_contents($file_path));
        
        $reserve = Fetch::find("reserves", $array->ac_id);

        print_r($array->ac_id);
      } else {
        echo "failed to move file";
      }
    } else {
      echo "file is not uploaded";
    }
  } else {
    echo "file is not defined";
  }
} else {
  echo "認証情報が不正";
}
exit();
