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

function upload_file($file, $file_path=null) {
  if (!$file_path) $file_path = dirname(__DIR__)."/storage/uploads/{$file["name"]}";
  if ($file) {
    if (is_uploaded_file($file["tmp_name"])) {
      if (move_uploaded_file($file["tmp_name"], $file_path)) {
        return $file_path;
      }
    }
  }
  return false;
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

function gen_result_sample($reserve_info, array $status_rand_array): array {
  [
    "id" => $reserve_id,
    "user_id" => $user_id,
    "start" => $start,
    "end" => $end,
    "numbers" => $numbers
  ] = $reserve_info;

  $result = [
    "id" => $reserve_id,
    "user_id" => $user_id,
    "calls" => []
  ];

  $reserve = Fetch::find("reserves", $reserve_id);
  $survey_id = $reserve["survey_id"];
  $faqs = Fetch::get("faqs", $survey_id, "survey_id", "order_num");
  $endings = Fetch::get("endings", $survey_id, "survey_id");

  # calls
  foreach ($numbers as $number) {
    $status = $status_rand_array[array_rand($status_rand_array)];
    $answers = [];
    if ($status === 1) {
      $time = date("H:i:s", rand(strtotime($start), strtotime($end)));
      $duration = rand(10, 150);
      # answers
      $next_id = $faqs[0]["id"];
      $next_type = "faq";
      while (true) {
        if ($next_type === "faq") {
          $faq = $faqs[array_search($next_id, array_column($faqs, "id"))];
          $options = Fetch::get("options", $faq["id"], "faq_id");
          $option = $options[array_rand($options)];
          $next_type = $option["next_ending_id"] ? "ending" : "faq";
          $next_id = $next_type === "ending" ? $option["next_ending_id"] : $option["next_faq_id"];
          $answers[] = [
            "faq_id" => $faq["id"],
            "option_id" => $option["id"],
          ];
        } else {
          $ending = $endings[array_search($next_id, array_column($endings, "id"))];
          $answers[] = [
            "ending_id" => $ending["id"]
          ];
          break;
        }
      }
    }
    $result["calls"][] = [
      "number" => $number,
      "status" => $status,
      "duration" => $duration ?? null,
      "time" => $time ?? null,
      "answers" => $answers ?? null
    ];
  }
  
  $file_name = "ac_res{$reserve["id"]}_{$reserve["date"]}.json";
  $json = json_encode($result, JSON_PRETTY_PRINT);
  $file_path = dirname(__DIR__)."/storage/outputs/{$file_name}";
  return [$json, $file_path];
}

function error_response($message) {
  header("400 Bad Request");
  exit(json_encode(["message: {$message}"], JSON_PRETTY_PRINT));
}