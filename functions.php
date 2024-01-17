<?php

function escape($str) {
  return htmlspecialchars($str, ENT_QUOTES, 'UTF-8');
}

/**
 * URLパラメータを書き換え・追加・削除する
 * 引用元: https://dgcolor.info/blog/87/
 * @param Array | 書き換え・追加・削除するパラメータの配列
 * @return string | 変更後のURLパラメータ
 */
function url_param_change($par=Array(),$op=0){
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

# ページネーションに関する情報を取得する
function get_page_numbers($posts_par_page, $posts_sum, $page) {
  $first = 1;
  $last = ceil($posts_sum / $posts_par_page);
  $offset = $posts_par_page * ($page - 1);
  return [
    "current" => $page,
    "prev" => $page - 1 < $first ? false : $page - 1,
    "next" => $page + 1 > $last ? false : $page + 1,
    "first" => $page - 2 < $first ? false : $first,
    "last" => $page + 2 > $last ? false : $last,
    "offset" => $offset,
    "current_start" => $offset + 1,
    "current_end" => $posts_sum < $offset + $posts_par_page ? $posts_sum : $offset + $posts_par_page,
  ];
}

# サイト内のパスを指定してURLを取得
function url($path = '') {
  $protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http';
  $url = $protocol . '://' . $_SERVER['HTTP_HOST'] . $path;
  return $url;
}

# 指定したページにリダイレクト
function redirect($path) {
  $url = url($path);
  header("Location: $url");
  exit;
}

/**
 * オートコールシステムのみ利用
 */
function navActive($str) {
  global $handler;
  return $handler === $str ? "active" : "link-body-emphasis";
}
function navActiveSurvey($int) {
  global $surveyId;
  return $surveyId == $int ? "active" : "link-body-emphasis";
}

