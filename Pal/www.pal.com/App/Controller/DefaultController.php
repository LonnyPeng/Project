<?php

class DefaultController extends InitController
{
	public function indexAction()
	{
		$sql = "SELECT COUNT(*) FROM contract";
		$total = $this->db()->getOne($sql);

		$sql = "SELECT *
				FROM contract 
				ORDER BY cid";
		$rows = $this->db()->getAll($sql);
		$page = $this->page($total)->fpage(array(8, 3, 4, 5, 6, 7, 0, 1, 2));

		return array(
			'rows' => $rows,
			'title' => '通讯录管理程序',
			'page' => $page,
		);
	}

	public function addAction()
	{
		$row = array();
		$id = isset($_GET['id']) && $_GET['id'] ? $_GET['id'] : '';
		if ($id) {
			$sql = "SELECT * 
					FROM contract 
					WHERE cid = ?";
			$row = $this->db()->getRow($sql, $id);
		}
		
		return array(
			'id' => $id,
			'row' => $row,
			'title' => '添加',
		);
	}

	public function adddoAction()
	{
		if (!$this->funcs()->isAjax()) {
			return false;
		}
		
		$id = isset($_GET['id']) && $_GET['id'] ? $_GET['id'] : '';

		$username = trim($_POST["username"]);
		$usersex = trim($_POST["usersex"]);
		$userbirth = trim($_POST["userbirth"]);
		$usertel = trim($_POST["usertel"]);
		$useraddr = trim($_POST["useraddr"]);
		$aadd = trim($_POST['aadd']) ? date("Y-m-d H:i:s", strtotime(trim($_POST['aadd']))) : date('Y-m-d H:i:s');

		$set = "cname = :cname,csex = :csex,cbirth = :cbirth,ctel = :ctel,caddr = :caddr,aadd = :aadd";
		$map = array(
			'cname' => $username,
			'csex' => $usersex,
			'cbirth' => $userbirth,
			'ctel' => $usertel,
			'caddr' => $useraddr,
			'aadd' => $aadd,
		);
		if($id) {
			$map['cid'] = $id;
			$sql = "UPDATE contract SET {$set} WHERE cid = :cid";
		} else {
			$sql = "INSERT INTO contract SET {$set}";
		}

		$status = $this->db()->exec($sql, $map);
		
		header("Location: /");

		return false;
	}

	public function deleteAction()
	{
		if(isset($_GET['action'])) {
			$dels = join(",", $_POST['ck']);
			$sql = "DELETE FROM contract WHERE cid IN ({$dels})";
			$this->db()->exec($sql);
		} elseif (isset($_GET["id"])) {
			$id = intval($_GET["id"]);
			$sql = "DELETE FROM contract  WHERE cid = ?";
			$this->db()->exec($sql, $id);
		}

		header("Location: /");

		return false;
	}

	public function analyzeAction()
	{
		$sql = "SELECT cname, cbirth, aadd
				FROM contract 
				ORDER BY cid";
		$rows = $this->db()->getAll($sql);

		$sql = "SELECT SUM(cbirth) FROM contract";
		$total = $this->db()->getOne($sql);

		return array(
			'rows' => $rows,
			'total' => $total,
			'title' => '数据分析',
		);
	}
}