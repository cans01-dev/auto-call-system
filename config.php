<?php 

define("DB_PREFIX", "mysql:");
define("DB_NAME", "auto_call_system");
define("DB_HOST", "localhost");
define("DB_USERNAME", "root");
define("DB_PASSWORD", "");

define("DB_DSN", DB_PREFIX."dbname=".DB_NAME.";host=".DB_HOST);

define("PAGE_TITLE", "AutoCallシステム");

define("RESERVATION_DEADLINE_HOUR", 9);