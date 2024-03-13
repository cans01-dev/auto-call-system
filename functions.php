<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;

/**
 * オートコールシステムのみ利用
 */
function array_str($array, $delimiter=", ") {
  $str = "";
  $last = array_slice($array, -1)[0];
  foreach ($array as $item) {
    $str .= $item;
    if ($item !== $last) $str .= $delimiter;
  }
  return $str;
}

function make_times($min=0, $max=86400, $step=60) {
  $array = [];
  for ($ts = $min; $ts <= $max; $ts += $step) {
    array_push($array, $ts - 60*60*9);
  }
  return $array;
}

function hour_to_sec(string $str): int
{
  $t = explode(":", $str);
  $h = (int)$t[0];
  if (isset($t[1])) {
    $m = (int)$t[1];
  } else {
    $m = 0;
  }
  if (isset($t[2])) {
    $s = (int)$t[2];
  } else {
    $s = 0;
  }
  return ($h * 60 * 60) + ($m * 60) + $s;
}

function text_to_speech($text, $voice_name) {
  $google_tts_api_url = "https://texttospeech.googleapis.com/v1/text:synthesize?key=".$_ENV["GOOGLE_API_KEY"];
  
  $ch = curl_init();
  curl_setopt_array($ch, [
    CURLOPT_URL => $google_tts_api_url,
    CURLOPT_HTTPHEADER => ["Content-Type: application/json"],
    CURLOPT_CUSTOMREQUEST => "POST",
    CURLOPT_SSL_VERIFYPEER => false,
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_POSTFIELDS => json_encode([
      "audioConfig" => [
        "audioEncoding" => "LINEAR16",
        "pitch" => 0,
        "speakingRate" => 1,
        "sampleRateHertz" => 8000
      ],
      "input" => [
        "text" => $text,
      ],
      "voice" => [
        "languageCode" => "ja-JP",
        "name" => $voice_name
      ]
    ])
  ]);
  $response = curl_exec($ch);
  curl_close($ch);
  
  $array = json_decode($response, true);

  return base64_decode($array["audioContent"]);
}

function user_dir($file_name, $user_id=null) {
  if (!$user_id) $user_id = Auth::user()["id"];
  return __DIR__."/storage/users/{$user_id}/{$file_name}";
}

# all_voice_file_regen
function avfrg($survey) {
  $survey["endings"] = Fetch::get("endings", $survey["id"], "survey_id");
  $survey["faqs"] = Fetch::get("faqs", $survey["id"], "survey_id", "order_num");

  $file_name = uniqid("s{$survey["id"]}g_") . ".wav";
  file_put_contents(user_dir($file_name, $survey["user_id"]), text_to_speech($survey["greeting"], $survey["voice_name"]));
  DB::update("surveys", $survey["id"], ["greeting_voice_file" => $file_name]);

  foreach ($survey["endings"] as $ending) {
    $file_name = uniqid("e{$ending["id"]}_") . ".wav";
    file_put_contents(user_dir($file_name, $survey["user_id"]), text_to_speech($ending["text"], $survey["voice_name"]));  
    DB::update("endings", $ending["id"], ["voice_file" => $file_name]);
  }

  foreach ($survey["faqs"] as $faq) {
    $file_name = uniqid("f{$faq["id"]}_") . ".wav";
    file_put_contents(user_dir($file_name, $survey["user_id"]), text_to_speech($faq["text"], $survey["voice_name"]));  
    DB::update("faqs", $faq["id"], ["voice_file" => $file_name]);
  }
}

function with_basic($url) {
  $a = strrpos($url, "://") + 3;
  return substr_replace($url, "autocall:password@", $a, 0);
}

function store_number_csv($fp, $number_list_id) {
  [$success, $error, $dup] = [0, 0, 0];
  $numbers = [];
  while($line = fgetcsv($fp)){
    $number = $line[0];
    if (!preg_match('/^0[789]0-[0-9]{4}-[0-9]{4}$/', $number)) {
      if ($number[0] !== "0") $number = "0" . $number;
      if (strlen($number) < 13) $number = substr_replace(substr_replace($number, "-", 3, 0), "-", 8, 0);
    }

    if (preg_match('/^0[789]0-[0-9]{4}-[0-9]{4}$/', $number)) {
      if (!Fetch::find2("numbers", [
        ["number_list_id", "=", $number_list_id],
        ["number", "=", $number]
      ])) {
        $numbers[] = $number;
        $success++;
        continue;
      }
      $dup++;
      continue;
    }
    $error++;
  }

  $result = ["success" => $success, "error" => $error, "dup" => $dup];
  return [$numbers, $result];
}

/**
 * ーーここまでーー
 */

function escape($str) {
  return htmlspecialchars($str, ENT_QUOTES, 'UTF-8');
}

function url_param_change($par=Array(),$op=0) {
  $url = parse_url($_SERVER["REQUEST_URI"]);
  if(isset($url["query"])) parse_str($url["query"],$query);
  else $query = Array();
  foreach($par as $key => $value){
      if($key && is_null($value)) unset($query[$key]);
      else $query[$key] = $value;
  }
  $query = str_replace("=&", "&", http_build_query($query));
  $query = preg_replace("/=$/", "", $query);
  return $query ? (!$op ? "?" : "").htmlspecialchars($query, ENT_QUOTES) : "";
}

function url($path = '') {
  $protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http';
  $url = $protocol . '://' . $_SERVER['HTTP_HOST'] . $path;
  return $url;
}

function asset($path) {
  return url("assets/{$path}");
}

function redirect($path) {
  $url = url($path);
  header("Location: $url");
  exit;
}

function back($hash=null) {
  $url = $_SERVER["HTTP_REFERER"];
  if ($hash) $url .= "#{$hash}";
  header("Location: $url");
  exit;
}

function abort($code) {
  switch ($code) {
    case 419:
      header("HTTP/1.1 419 Page Expired");
      exit;
    case 403:
      header("HTTP/1.1 403 Forbidden");
      exit;
    case 500:
      header("HTTP/1.1 500 Internal server error");
      exit;
  }
}

function csrf() {
  global $token;
  return <<<EOM
    <input type="hidden" name="token" value="{$token}">
  EOM;
}

function method($method) {
  return <<<EOM
    <input type="hidden" name="_method" value="{$method}">
  EOM;
}

function pagenation($posts_par_page, $posts_sum, $page) {
  $first = 1;
  $last = ceil($posts_sum / $posts_par_page);
  $offset = $posts_par_page * ($page - 1);
  return [
    "current" => $page,
    "pprev" => $page - 2 < $first ? false : $page - 2,
    "prev" => $page - 1 < $first ? false : $page - 1,
    "next" => $page + 1 > $last ? false : $page + 1,
    "nnext" => $page + 2 > $last ? false : $page + 2,
    "first" => $page - 2 < $first ? false : $first,
    "last" => $page + 2 > $last ? false : $last,
    "last_page" => $last,
    "offset" => $offset,
    "sum" => $posts_sum,
    "current_start" => $offset + 1,
    "current_end" => $posts_sum < $offset + $posts_par_page ? $posts_sum : $offset + $posts_par_page,
  ];
}

function upload_file($file, $file_path=null) {
  if (!$file_path) $file_path = __DIR__ . "/storage/uploads/{$file["name"]}";
  if ($file) {
    if (is_uploaded_file($file["tmp_name"])) {
      if (move_uploaded_file($file["tmp_name"], $file_path)) {
        return $file_path;
      }
    }
  }
  return false;
}