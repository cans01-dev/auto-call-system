<?php 

# ログインしなくても使えるルートハンドラ
$publicHandler = [
	"login",
	"loginPost"
];

$dispatcher = FastRoute\simpleDispatcher(function(FastRoute\RouteCollector $r) {
	$r->addRoute("GET", "/", "index");
	$r->addRoute("GET", "/login", "login");
	$r->addGroup("/surveys", function (FastRoute\RouteCollector $r) {
		$r->addRoute("GET", "", "surveys");
		$r->addRoute("GET", "/create", "surveysCreate");
		$r->addGroup("/{surveyId:\d+}", function (FastRoute\RouteCollector $r) {
			$r->addRoute("GET", "", "survey");
			$r->addGroup("/faqs", function (FastRoute\RouteCollector $r) {
				$r->addRoute("GET", "", "faqs");
				$r->addRoute("GET", "/create", "faqsCreate");
				$r->addGroup("/{faqId:\d+}", function (FastRoute\RouteCollector $r) {
					$r->addRoute("GET", "", "faq");
					$r->addGroup("/options", function (FastRoute\RouteCollector $r) {
						$r->addRoute("GET", "/{optionId:\d+}", "option");
						$r->addRoute("GET", "/{optionId:\d+}/create", "optionCreate");
					});	
				});
			});
			$r->addGroup("/reserves", function (FastRoute\RouteCollector $r) {
				$r->addRoute("GET", "", "reserves");
				$r->addRoute("GET", "/create", "reservesCreate");
				$r->addGroup("/{reserveId:\d+}", function (FastRoute\RouteCollector $r) {
					$r->addRoute("GET", "", "reserve");
				});
			});
			$r->addGroup("/result", function (FastRoute\RouteCollector $r) {
				$r->addRoute("GET", "", "results");
				$r->addGroup("/{reserveId:\d+}", function (FastRoute\RouteCollector $r) {
					$r->addRoute("GET", "", "result");
					$r->addRoute("GET", "/calls/{callId:\d+}", "call");
				});
			});
		});
	});
	$r->addGroup("/account", function (FastRoute\RouteCollector $r) {
		$r->addRoute("GET", "", "account");
		$r->addGroup("/send-emails", function (FastRoute\RouteCollector $r) {
			$r->addRoute("GET", "/{sendEmailId:\d+}", "sendEmail");
			$r->addRoute("GET", "/create", "sendEmailCreate");
		});
	});
	$r->addRoute("POST", "/login", "loginPost");
	$r->addRoute("POST", "/logout", "logout");
});

?>