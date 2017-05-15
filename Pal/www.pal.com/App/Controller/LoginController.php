<?php

class LoginController extends InitController
{
	public function logonAction()
	{
		$_SESSION['layout'] = false;

		return array(
			'title' => '通讯录管理程序',
		);
	}

	public function logindoAction()
	{
		if (!$this->funcs()->isAjax()) {
			return false;
		}

		$username = trim($_POST["username"]);
		$userpwd = md5(trim($_POST["userpwd"]));

		$sql = "SELECT COUNT(*)
				FROM admin 
				WHERE aname = ? 
				AND apwd = ?";
		$status = $this->db()->getOne($sql, $username, $userpwd);
		if ($status) {
			$_SESSION['uid'] = true;
			header("Location: /");
		} else {
			header("Location: /login/logon");
		}
	}

	public function logoutAction()
	{
		unset($_SESSION['uid']);
		header("Location: /login/logon");

		return false;
	}

	public function editpwdAction()
	{
		return array(
			'title' => '通讯录管理程序',
		);
	}

	public function editpwddoAction()
	{
		if (!$this->funcs()->isAjax()) {
			return false;
		}

		$oldpwd = md5(trim($_POST["oldpwd"]));
		$newpwd = md5(trim($_POST["newpwd1"]));

		$sql = "UPDATE admin 
				SET apwd = ? 
				WHERE apwd = ?";
		$status = $this->db()->exec($sql, $newpwd, $oldpwd);
		if ($status) {
			header("Location: /");
		} else {
			header("Location: /login/editpwd");
		}

		return false;
	}
}