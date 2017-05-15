<?php
session_start();
header("Content-Type: text/html; charset=UTF-8");

require_once __DIR__ . "/App/Config/config.php";
require_once CONTROLLER . "/InitController.php";

// get controller and action
$url = strtolower($_SERVER['REQUEST_URI']);
if (in_array($url, array("", "/", "/index.php", "index.html"))) {
	$url = array(
		0 => 'default',
		1 => 'index',
	);
} else {
	$url = explode("?", $url)[0];
	$url = explode("/", $url);
	$url = array_values(array_diff($url, array("")));
	if (count($url) !== 2) {
		echo "404 NO FOUND PAGE!";
		exit;
	}
}

$_SESSION['controller'] = $url[0];
$_SESSION['action'] = $url[1];

$controller = ucfirst($url[0]) . "Controller";
$action = $url[1] . "Action";

// to load controller
$controllerClass = CONTROLLER . "{$controller}.php";
if (!file_exists($controllerClass)) {
	echo "No {$controller} Class";
	exit;
}

require_once $controllerClass;
if (!class_exists($controller)) {
	echo "No {$controller} Class";
	exit;
}
$controller = new $controller();

if (!method_exists($controller, $action)) {
	echo "No {$action} Action";
	exit;
}

// Default Theme
$data = $controller->$action();
if ($data === false) {
	header("Location: /");
	exit;
}

$actionTemplate = TEMPLATE . "{$url[0]}/{$url[1]}.php";
if (!file_exists($actionTemplate)) {
	echo "No {$url[0]}/{$url[1]} Template";
	exit;
}


if ($_SESSION['layout']) {
	require_once TEMPLATE . "/layout/header.php";
}

require_once $actionTemplate;

if ($_SESSION['layout']) {
	require_once TEMPLATE . "/layout/footer.php";
}


