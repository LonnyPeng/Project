<?php

require_once LIB . "Pdo.class.php";
require_once LIB . "Page.class.php";
require_once LIB . "Funcs.class.php";

class InitController
{
	public function __construct()
	{
		if ($_SESSION['controller'] == 'login' && in_array($_SESSION['action'], array('logon', 'logindo'))) {
			null;
		} else {
			if(!isset($_SESSION['uid'])) {
				header("Location: /login/logon");
			}
		}

		$_SESSION['layout'] = true;
	}

	public function db()
	{
		$dsn = "mysql:host=" . DB_HOST . ":" . DB_PORT . ";dbname=" . DB_NAME;
		$username = DB_USER;
		$password = DB_PASS;

		return new Mysql($dsn, $username, $password);
	}

	public function page($total)
	{
		return new Page($total);
	}

	public function funcs()
	{
		return new Funcs();
	}
}