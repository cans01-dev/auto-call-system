<?php 

$dispatcher = FastRoute\simpleDispatcher(function(FastRoute\RouteCollector $r) {
	$r->addRoute("GET", "/", "index");
	$r->addRoute("GET", "/login", "login");
	$r->addRoute("GET", "/surveys/create", "surveysCreate");
	$r->addRoute("GET", "/surveys/{surveyId:\d+}", "survey");
	$r->addRoute("GET", "/reserves/{reserveId:\d+}", "reserve");
	$r->addRoute("GET", "/calls/{callId:\d+}", "call");
	$r->addRoute("GET", "/faqs/{faqId:\d+}", "faq");
	$r->addRoute("GET", "/options/{optionId:\d+}", "option");	
	$r->addRoute("GET", "/settings/{settingId:\d+}", "setting");
	$r->addRoute("GET", "/account", "account");
	$r->addRoute("GET", "/send-emails/{sendEmailId:\d+}", "sendEmail");

	$r->addRoute("POST", "/login", "loginPost");
	$r->addRoute("POST", "/logout", "logout");
});

