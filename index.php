<?php

require "./vendor/autoload.php";
use FastRoute\Dispatcher;

require "./models/Fetch.php";
require "./models/Auth.php";
require "./models/Session.php";
require "./models/Calendar.php";
require "./models/Components.php";
require "./controllers/Controller.php";
require "./controllers/accountController.php";
require "./controllers/faqController.php";
require "./controllers/mainController.php";
require "./controllers/reserveController.php";
require "./controllers/resultController.php";
require "./controllers/surveyController.php";
require "./controllers/settingController.php";
require "./controllers/optionController.php";

require "./functions.php";
require "./config.php";
require "./router.php";

date_default_timezone_set("Asia/Tokyo");

session_start();

# DB接続
try {
	$pdo = new PDO(DB_DSN, DB_USERNAME, DB_PASSWORD);
} catch (PDOException $e) {
	exit($e->getMessage());
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
	# CSRF検証
	if ($_POST["token"] !== Session::get("token")) abort(419);
	# 見せかけのHTTPメソッド有効化
	$httpMethod = isset($_POST["_method"]) ? $_POST["_method"] : $_SERVER["REQUEST_METHOD"];
} else {
	$httpMethod = $_SERVER["REQUEST_METHOD"];
}

# CSRFトークン発行
if (!Session::get("token")) {
	$token = bin2hex(openssl_random_pseudo_bytes(24));
	Session::set("token", $token);
} else {
	$token = Session::get("token");
}

# URLの解析
$uri = $_SERVER["REQUEST_URI"];
if (false !== $pos = strpos($uri, "?")) {
	$uri = substr($uri, 0, $pos);
}
$uri = rawurldecode($uri);
$routeInfo = $dispatcher->dispatch($httpMethod, $uri);

# ログインしなくても使えるルートハンドラ
$publicHandlers = ["login", "loginPost"];

# ルーティングの実行
switch ($routeInfo[0]) {
	case Dispatcher::NOT_FOUND:
		require_once "./views/pages/404.php";
		break;
	case Dispatcher::METHOD_NOT_ALLOWED:
		$allowedMethods = $routeInfo[1];
		require_once "./views/pages/405.php";
		break;
	case Dispatcher::FOUND:
		$handler = $routeInfo[1];
		$vars = $routeInfo[2];

		# 認証が必要な場合はログインページにリダイレクト
		if (Auth::check() || in_array($handler, $publicHandlers)) {
			echo !empty($vars) ? $handler($vars) : $handler();
			Session::delete("toast");
		} else {
			Session::set("toast", ["success", "ログインしてください"]);
			redirect("/login");
		}
		break;
}