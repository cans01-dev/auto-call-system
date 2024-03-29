<?php

/**
 * オートコールシステムのみ利用
 */
function array_str($array, $delimiter=", ") {
  $str = "";
  $last = array_slice($array, -1)[0];
  foreach ($array as $item) {
    $str .= $item;
    if ($item !== $last) $str .= ", ";
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

function text_to_speech($text) {
  $google_api_key = "AIzaSyCVOtglUcy3xRxk-x1qI2m8e-JmJ_RZZJU";
  $google_tts_api_url = "https://texttospeech.googleapis.com/v1/text:synthesize?key=".$google_api_key;
  
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
        "speakingRate" => 1 
      ],
      "input" => [
        "text" => $text,
      ],
      "voice" => [
        "languageCode" => "ja-JP",
        "name" => "ja-JP-Standard-A"
      ]
    ])
  ]);
  $response = curl_exec($ch);
  curl_close($ch);
  
  $array = json_decode($response, true);

  return base64_decode($array["audioContent"]);
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
      require_once "views/pages/500.php";
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