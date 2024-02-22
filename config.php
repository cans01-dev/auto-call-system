<?php 

define("MODE_DEVELOPMENT", 1);
define("MODE_PRODUCT", 2);

define("MODE", MODE_DEVELOPMENT);

if (MODE === MODE_DEVELOPMENT) {
  define("DB_PREFIX", "mysql:");
  define("DB_NAME", "auto_call_system");
  define("DB_HOST", "localhost");
  define("DB_USERNAME", "root");
  define("DB_PASSWORD", "");
} else {
  define("DB_PREFIX", "mysql:");
  define("DB_NAME", "autocall_main");
  define("DB_HOST", "localhost");
  define("DB_USERNAME", "autocall_main");
  define("DB_PASSWORD", "cans01dev");
}

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

# Calls Per Hour あらかじめ生成しておく電話番号の数（一時間あたり）
define("NUMBERS_PER_HOUR", 100);

# ステータスコードごとのテキストとカラーの指定
define("RESERVATION_STATUS", [
  0 => ["text" => "予約済", "bg" => "primary"],
  1 => ["text" => "確定済", "bg" => "info"],
  // 2 => ["text" => "実行中", "bg" => "light"],
  // 3 => ["text" => "集計中", "bg" => "secondary"],
  4 => ["text" => "集計済", "bg" => "dark"],
  9 => ["text" => "お気に入り", "bg" => "black"]
]);

define("CALL_STATUS", [
  1 => ["text" => "接続",	"bg" => "primary"],
  2 => ["text" => "通話中",	"bg" => "secondary"],
  3 => ["text" => "不在",	"bg" => "dark"],
  4 => ["text" => "不正な電話番号",	"bg" => "black"],
  6 => ["text" => "不通番号", "bg" => "black"]
]);

define("USER_STATUS", [
  0 => ["text" => "一般", "bg" => "primary"],
  1 => ["text" => "管理者", "bg" => "info"],
  2 => ["text" => "利用停止", "bg" => "secondary"]
]);

define("CONTACT_TYPE", [
  0 => ["text" => "機能についてのご質問"],
  1 => ["text" => "バグ、エラーの報告"],
  2 => ["text" => "その他のご連絡"]
]);

define("VOICES", [
  ["name" => "ja-JP-Standard-A", "gender" => "FEMALE"],
  ["name" => "ja-JP-Standard-B", "gender" => "FEMALE"],
  ["name" => "ja-JP-Standard-C", "gender" => "MALE"],
  ["name" => "ja-JP-Standard-D", "gender" => "MALE"]
]);


define("USER_STATUS_GENERAL", 0);
define("USER_STATUS_ADMIN", 1);

# 予約パターン作成時のカラーパレット
define("COLOR_PALLET", ["#DCF2F1", "#7FC7D9", "#365486", "#0F1035"]);

# 課金表示画面に適用される秒数あたりの料金
define("PRICE_PER_SECOND", 0.138);

define("GOOGLE_API_KEY", "AIzaSyCVOtglUcy3xRxk-x1qI2m8e-JmJ_RZZJU");