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

function gen_reserve_info($reserve) {
  $survey = Fetch::find("surveys", $reserve["survey_id"]);
  $user = Fetch::find("users", $survey["user_id"]);
  $faqs = Fetch::get("faqs", $survey["id"], "survey_id", "order_num");
  $endings = Fetch::get("endings", $survey["id"], "survey_id");

  # file_path
  $f_date = str_replace("-", "_", $reserve["date"]);
  $file_path = user_dir("user{$user["id"]}_{$f_date}.json", $user["id"]);

  # basis
  $array = [
    "id" => $reserve["id"],
    "user_id" => $user["id"],
    "date" => $reserve["date"],
    "greeting" => $survey["greeting_voice_file"],
    "start" => substr($reserve["start"], 0, -3),
    "end" => substr($reserve["end"], 0, -3),
    "faqs" => [],
    "endings" => [],
    "numbers" => []
  ];

  # faqs
  $faqs = Fetch::get("faqs", $survey["id"], "survey_id", "order_num");
  foreach ($faqs as $faq) {
    $f = [
      "faq_id" => $faq["id"],
      "voice" => $faq["voice_file"],
      "options" => []
    ];
    $options = Fetch::get("options", $faq["id"], "faq_id");
    foreach($options as $option) {
      $next_type = $option["next_ending_id"] ? "ending" : "faq";
      $next_id = $next_type === "ending" ? $option["next_ending_id"] : $option["next_faq_id"];
      $f["options"]["{$option["dial"]}"] = [
        "option_id" => $option["id"],
        "next_type" => $next_type,
        "next_id" => $next_id
      ];
    }
    $array["faqs"][] = $f;
  }

  # endings
  $endings = Fetch::get("endings", $survey["id"], "survey_id");
  foreach ($endings as $ending) {
    $array["endings"][] = [
      "ending_id" => $ending["id"],
      "voice" => $ending["voice_file"]
    ];
  }

  # numbers
  if ($reserve["number_list_id"]) {
    $numbers = Fetch::get("numbers", $reserve["number_list_id"], "number_list_id");
    foreach ($numbers as $number) {
      $same_number = Fetch::query("
        SELECT * FROM calls as c JOIN reserves as r ON c.reserve_id = r.id
        WHERE r.survey_id = {$survey["id"]} AND number = {$number["number"]}
      ", "fetch");
      if ($same_number) continue;
      $array["numbers"][] = $number["number"];
    }
  } else {
    $areas = Fetch::areasByReserveId($reserve["id"]);
    $numbers_length = round((strtotime($reserve["end"]) - strtotime($reserve["start"])) / 3600 * NUMBERS_PER_HOUR * $user["number_of_lines"]);

    foreach ($areas as $area) {
      $stations = Fetch::get("stations", $area["id"], "area_id");
      foreach ($stations as $station) {
        $sql = "SELECT * FROM calls as c
                JOIN reserves as r ON c.reserve_id = r.id
                WHERE r.survey_id = {$survey["id"]}
                AND c.number LIKE '{$station["prefix"]}%'";
        $calls = Fetch::query($sql, "fetchAll");
        $called_number_n56789_int_arr = [];
        foreach ($calls as $call) {
          $n56789 = substr(str_replace("-", "", $call["number"]), 6, 5);
          $called_number_n56789_int_arr[] = intval($n56789);
        }
        $uncalled_n56789_int_arr = array_diff(array_diff(range(0, 99999), $called_number_n56789_int_arr));

        foreach ($uncalled_n56789_int_arr as $n56789_int) {
          $n56789 = sprintf('%05d', intval($n56789_int));
          $n5 = substr($n56789, 0, 1);
          $n6789 = substr($n56789, 1, 4);
          $number = "{$station["prefix"]}{$n5}-{$n6789}";
          
          $array["numbers"][] = $number;

          if (count($array["numbers"]) >= $numbers_length) break 3;
        }
      }
    }
  }

  $json = json_encode($array, JSON_PRETTY_PRINT);
  return [$json, $file_path];
}

function receive_result($json) {
  $array = json_decode($json, true);
  $reserve = Fetch::find("reserves", $array["id"]);

  DB::beginTransaction();
  try {
    foreach ($array["calls"] as $call) {
      DB::insert("calls", [
        "reserve_id" => $reserve["id"],
        "number" => $call["number"],
        "status" => $call["status"],
        "duration" => $call["duration"],
        "time" => $call["time"]
      ]);
      $call_id = DB::lastInsertId();
      
      if ($call["status"] === 1) {
        foreach ($call["answers"] as $answer) {
          if (isset($answer["option_id"])) {
            DB::insert("answers", [
              "call_id" => $call_id,
              "faq_id" => $answer["id"],
              "option_id" => $answer["option_id"]
            ]);
          }
        }
      }
    }
    DB::commit(); 
    return true;   
  } catch (Exception $e) {
    DB::rollback();
    return false;
  }
}