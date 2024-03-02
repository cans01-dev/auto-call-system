<?php 

$dispatcher = FastRoute\simpleDispatcher(function(FastRoute\RouteCollector $r) {
	$r->addRoute("GET", "/", "index");
	$r->addRoute("GET", "/home", "home");
	$r->addRoute("GET", "/login", "login");
	$r->addRoute("GET", "/surveys/{id:\d+}", "survey");
	$r->addRoute("GET", "/surveys/{id:\d+}/calls", "calls");
	$r->addRoute("GET", "/surveys/{id:\d+}/stats", "stats");
	$r->addRoute("GET", "/reserves/{id:\d+}", "reserve");
	$r->addRoute("GET", "/calls/{id:\d+}", "call");
	$r->addRoute("GET", "/faqs/{id:\d+}", "faq");
	$r->addRoute("GET", "/favorites/{id:\d+}", "favorite");
	$r->addRoute("GET", "/users/{id:\d+}", "account");
	$r->addRoute("GET", "/send-emails/{id:\d+}", "sendEmail");
	$r->addRoute("GET", "/areas/{id:\d+}", "area");
	$r->addRoute("GET", "/support", "support");
	$r->addRoute("GET", "/admin/users", "users");
	$r->addRoute("GET", "/admin/receive_result_log", "receive_result_log");
	$r->addRoute("GET", "/admin/gen_reserve_log", "gen_reserve_log");
	$r->addRoute("GET", "/admin/reserves", "adminReserves");

	$r->addRoute("POST", "/login", "loginPost");
	$r->addRoute("POST", "/logout", "logout");

	$r->addRoute("PUT", "/users/{id:\d+}/email", "updateEmail");
	$r->addRoute("PUT", "/users/{id:\d+}/password", "updatePassword");

	$r->addRoute("POST", "/send-emails", "storeSendEmail");
	$r->addRoute("PUT", "/send-emails/{id:\d+}", "updateSendEmail");
	$r->addRoute("DELETE", "/send-emails/{id:\d+}", "deleteSendEmail");

	$r->addRoute("POST", "/surveys", "storeSurvey");
	$r->addRoute("PUT", "/surveys/{id:\d+}", "updateSurvey");
	$r->addRoute("PUT", "/surveys/{id:\d+}/greeting", "updateGreeting");
	$r->addRoute("POST", "/surveys/{id:\d+}/all_voice_file_re_gen", "allVoiceFileReGen");
	$r->addRoute("POST", "/surveys/{id:\d+}/number_lists", "storeNumberList");
	$r->addRoute("DELETE", "/number_lists/{id:\d+}", "deleteNumberList");

	$r->addRoute("POST", "/endings", "storeEnding");
	$r->addRoute("PUT", "/endings/{id:\d+}", "updateEnding");
	$r->addRoute("DELETE", "/endings/{id:\d+}", "deleteEnding");

	$r->addRoute("POST", "/faqs", "storeFaq");
	$r->addRoute("PUT", "/faqs/{id:\d+}", "updateFaq");
	$r->addRoute("DELETE", "/faqs/{id:\d+}", "deleteFaq");
	$r->addRoute("POST", "/faqs/{id:\d+}/order", "orderFaq");
	
	$r->addRoute("POST", "/options", "storeOption");
	$r->addRoute("PUT", "/options/{id:\d+}", "updateOption");
	$r->addRoute("DELETE", "/options/{id:\d+}", "deleteOption");
	$r->addRoute("POST", "/options/{id:\d+}/order", "orderOption");

	$r->addRoute("POST", "/reserves", "storeReserve");
	$r->addRoute("PUT", "/reserves/{id:\d+}", "updateReserve");
	$r->addRoute("DELETE", "/reserves/{id:\d+}", "deleteReserve");

	$r->addRoute("POST", "/reserves/{id:\d+}/areas", "storeReservesAreas");
	$r->addRoute("POST", "/reserves/{id:\d+}/areas_by_word", "storeReservesAreasByWord");
	$r->addRoute("DELETE", "/reserves_areas/{id:\d+}", "deleteReservesAreas");

	$r->addRoute("POST", "/favorites", "storeFavorite");
	$r->addRoute("PUT", "/favorites/{id:\d+}", "updateFavorite");
	$r->addRoute("DELETE", "/favorites/{id:\d+}", "deleteFavorite");
	
	$r->addRoute("POST", "/favorites/{id:\d+}/areas", "storeFavoritesAreas");
	$r->addRoute("POST", "/favorites/{id:\d+}/areas_by_word", "storeFavoritesAreasByWord");
	$r->addRoute("DELETE", "/favorites_areas/{id:\d+}", "deleteFavoritesAreas");
	
	$r->addRoute("POST", "/areas", "storeArea");
	$r->addRoute("PUT", "/areas/{id:\d+}", "updateArea");
	$r->addRoute("DELETE", "/areas/{id:\d+}", "deleteArea");
	$r->addRoute("POST", "/areas/{id:\d+}/stations", "storeStation");
	
	$r->addRoute("DELETE", "/stations/{id:\d+}", "deleteStation");

	$r->addRoute("POST", "/support/contact", "sendContact");
	$r->addRoute("POST", "/surveys/{id:\d+}/calls", "callsCsv");

	$r->addRoute("POST", "/admin/users", "storeUser");
	$r->addRoute("PUT", "/admin/users/{id:\d+}/password", "adminUpdatePassword");
	$r->addRoute("PUT", "/admin/users/{id:\d+}", "adminUpdateUser");
	$r->addRoute("DELETE", "/admin/users/{id:\d+}", "deleteUser");
	$r->addRoute("DELETE", "/admin/receive_result_log/{id:\d+}", "cancelResultInfo");
	$r->addRoute("POST", "/admin/gen_reserve", "genReserve");
});

