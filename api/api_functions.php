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
  $credentials = explode(":", base64_decode(substr($http_authorization, 6)));
  return $credentials[0] === $username && $credentials[1] === $password;
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

function gen_result_info_array($reserve_info, array $status_rand_array): array {
  [
    "id" => $reserve_id,
    "user_id" => $user_id,
    "start" => $start,
    "end" => $end,
    "greeting_voice_file" => $greeting_voice_file,
    "numbers" => $numbers
  ] = $reserve_info;

  $result = [
    "id" => $reserve_id,
    "user_id" => $user_id,
    "greeting" => $greeting_voice_file,
    "calls" => []
  ];

  $reserve = Fetch::find("reserves", $reserve_id);
  $survey_id = $reserve["survey_id"];
  $faqs = Fetch::get("faqs", $survey_id, "survey_id");
  $endings = Fetch::get("endings", $survey_id, "survey_id");

  foreach ($numbers as $number) {
    $status = $status_rand_array[array_rand($status_rand_array)];
    $answers = [];

    if ($status === 1) {
      $time = date("H:i:s", rand(strtotime($start), strtotime($end)));
      $duration = rand(10, 150);
      
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

    $file_name = "ac_res{$reserve["id"]}_{$reserve["date"]}.json";
  }

  return [$result, $file_name];
}

function gen_reserve_info_array($reserve) {
  $survey = Fetch::find("surveys", $reserve["survey_id"]);
  $faqs = Fetch::get("faqs", $survey["id"], "survey_id");
  $endings = Fetch::get("endings", $survey["id"], "survey_id");
  $areas = Fetch::areasByReserveId($reserve["id"]);

  if ($reserve["status"]) exit("エラー: 予約のステータスが予約済ではない");
  if (empty($areas)) exit("エラー: エリアが指定されてない");

  $stations = [];
  foreach ($areas as $area) {
    foreach (Fetch::get("stations", $area["id"], "area_id") as $station) {
      $stations[] = $station;
    }
  }

  $r = [
    "id" => $reserve["id"],
    "user_id" => $survey["user_id"],
    "date" => $reserve["date"],
    "start" => substr($reserve["start"], 0, -3),
    "end" => substr($reserve["end"], 0, -3),
    "faqs" => [],
    "endings" => [],
    "numbers" => [],
    // greeting
  ];

  # faqs
  foreach ($faqs as $faq) {
    $f = [
      "faq_id" => "{$faq["id"]}",
      "options" => []
    ];
      
    $options = Fetch::get("options", $faq["id"], "faq_id");
    foreach($options as $option) {
      $next_type = $option["next_ending_id"] ? "ending" : "faq";
      $next_id = $next_type === "ending" ? $option["next_ending_id"] : $option["next_faq_id"];
      // $next = 
      $f["options"]["{$option["dial"]}"] = [
        "option_id" => $option["id"],
        "next_type" => $next_type,
        "next_index" => $next_id
      ];
    }
    $r["faqs"][] = $f;
  }

  # endings
  foreach ($endings as $ending) {
    $e = [
      "ending_id" => "{$ending["id"]}"
      // voice
    ];
    $r["endings"][] = $e;
  }

  # numbers
  $numbers_length = round((strtotime($reserve["end"]) - strtotime($reserve["start"])) / 3600 * NUMBERS_PER_HOUR);
  $stations_max = count($stations) - 1;

  while (count($r["numbers"]) < $numbers_length) {
    $station = $stations[rand(0, $stations_max)];
    $prefix = $station["prefix"];
    $n5 = rand(0, 9);
    $n6789 = sprintf('%04d', rand(0, 9999));

    $number = "{$prefix}{$n5}-{$n6789}";

    // 重複チェック

    $r["numbers"][] = $number;
  }

  return $r;
}