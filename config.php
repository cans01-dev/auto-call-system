<?php 

define("DB_PREFIX", "mysql:");
define("DB_NAME", "auto_call_system");
define("DB_HOST", "localhost");
define("DB_USERNAME", "root");
define("DB_PASSWORD", "");

define("PAGE_TITLE", "AutoCallシステム");

# 予約情報ファイルの送信先URL
define("SEND_FILE_URL", "http://localhost:8080/dev/receive_file.php");

# 予約情報ファイル送信のBasic認証情報
define("SEND_FILE_AUTHORIZATION", "Basic YWRtaW46dGVzdA==");

# 予約の締め切り
define("RESERVATION_DEADLINE_HOUR", 9);

# 開始・終了時間の指定のステップ（秒）
define("TIME_STEP", 900);

# 開始・終了時間の最小の間隔（秒）
define("MIN_INTERVAL", 3600);

# 開始・終了時間の制限
define("MIN_TIME", 60*60*9);
define("MAX_TIME", 60*60*21);

# Calls Per Hour あらかじめ生成しておく電話番号の数（一時間あたり）
define("NUMBERS_PER_HOUR", 100);

# ステータスコードごとのテキストとカラーの指定
define("RESERVATION_STATUS", [
  0 => ["text" => "予約済", "bg" => "primary"],
  1 => ["text" => "確定済", "bg" => "info"],
  2 => ["text" => "実行中", "bg" => "light"],
  3 => ["text" => "集計中", "bg" => "secondary"],
  4 => ["text" => "集計済", "bg" => "dark"],
  9 => ["text" => "お気に入り", "bg" => "black"]
]);

# 予約パターン作成時のカラーパレット
define("COLOR_PALLET", ["#DCF2F1", "#7FC7D9", "#365486", "#0F1035"]);

# 課金表示画面に適用される秒数あたりの料金
define("PRICE_PER_SECOND", 0.138);

