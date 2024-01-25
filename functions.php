<?php

/**
 * オートコールシステムのみ利用
 */
function array_print($array, $key) {
  $str = "";
  foreach ($array as $item) {
    $str .= $item[$key];
  }
  return $str;
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

function back() {
  $url = $_SERVER["HTTP_REFERER"];
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