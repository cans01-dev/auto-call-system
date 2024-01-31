<?php 

define("PAGE_TITLE", "AutoCallシステム");

# 予約の締め切り
define("RESERVATION_DEADLINE_HOUR", 9);

# 開始・終了時間の指定のステップ（秒）
define("TIME_STEP", 900);

# 開始・終了時間の最小の間隔（秒）
define("MIN_INTERVAL", 3600);

# 開始・終了時間の制限
define("MIN_TIME", 60*60*9);
define("MAX_TIME", 60*60*21);

# あらかじめ生成しておく電話番号の数（一時間あたり）
define("NUMBERS_PER_HOUR", 10000);

# ステータスコードごとのテキストとカラーの指定
define("RESERVATION_STATUS", [
  0 => ["text" => "予約済", "bg" => "primary"],
  1 => ["text" => "確定済", "bg" => "info"],
  2 => ["text" => "実行中", "bg" => "light"],
  3 => ["text" => "集計中", "bg" => "secondary"],
  4 => ["text" => "集計済", "bg" => "dark"],
  9 => ["text" => "お気に入り", "bg" => "black"]
]);