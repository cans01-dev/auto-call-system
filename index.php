<?php

require "./vendor/autoload.php";
require "./models/Fetch.php";
require "./models/Auth.php";
require "./models/Session.php";
require "./models/Calendar.php";
require "./models/Components.php";
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
	$fetch = new Fetch($pdo);
	$auth = new Auth();
} catch (PDOException $e) {
	exit($e->getMessage());
}

# 見せかけのHTTPメソッド有効化
$useExtMethod = $_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["_method"]);
$httpMethod = $_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["_method"])
							? $_POST["_method"]
							: $_SERVER["REQUEST_METHOD"];

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
	case FastRoute\Dispatcher::NOT_FOUND:
		require_once "./views/pages/404.php";
		break;
	case FastRoute\Dispatcher::METHOD_NOT_ALLOWED:
		$allowedMethods = $routeInfo[1];
		require_once "./views/pages/405.php";
		break;
	case FastRoute\Dispatcher::FOUND:
		$handler = $routeInfo[1];
		$vars = $routeInfo[2];

		# 認証が必要な場合はログインページにリダイレクト
		if ($auth->currentUser || in_array($handler, $publicHandlers)) {
			echo !empty($vars) ? $handler($vars) : $handler();
			Session::delete("toast");
		} else {
			Session::set("toast", ["success", "ログインしてください"]);
			redirect("/login");
		}
		break;
}