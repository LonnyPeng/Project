<?php

define("ROOT", __DIR__ . "/../../");

define("APP", ROOT . "App/");
define("LIB", ROOT . "lib/");
define("HTTP_HOST", "http://" . $_SERVER['HTTP_HOST'] . "/");
define("RESOURCE", HTTP_HOST . "static/");

define("CONFIG", APP . "Config/");
define("CONTROLLER", APP . "Controller/");
define("TEMPLATE", APP . "Template/");

define("IMG", RESOURCE . "img/");
define("CSS", RESOURCE . "css/");
define("JS", RESOURCE . "js/");
define("OTHER", RESOURCE . "other/");

define("DB_HOST", "127.0.0.1");
define("DB_PORT", "3306");
define("DB_USER", "root");
define("DB_PASS", "root");
define("DB_NAME", "db_pal");

define("TITLE", $_SERVER['HTTP_HOST']);

