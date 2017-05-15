<!DOCTYPE html>
<html lang="zh-CN">
<head>
	<meta charset="UTF-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no" />
	<meta http-equiv="content-language" content="zh-CN" />
	<title><?= isset($data['title']) ? $data['title'] : TITLE?></title>
	<link rel="stylesheet" href="<?= CSS . "common.css"?>" />
	<link rel="stylesheet" href="<?= CSS . "font-awesome.css"?>" />
	<link rel="stylesheet" href="<?= CSS . "default.css"?>" />
	<script type="text/javascript" src="<?= JS . "jquery-3.1.1.min.js"?>"></script>
	<script type="text/javascript" src="<?= JS . "core.popup.js"?>"></script>
	<script type="text/javascript" src="<?= JS . "core.ajaxauto.js"?>"></script>
	<script type="text/javascript" src="<?= JS . "calendar.js"?>"></script>
</head>
<body>
	<div id="top">
		<span class="title">通信录管理</span>
		<span class="logo"><i class="fa fa-snowflake-o"></i></span>
		<span class="right">
			<a href="/">列表</a>
			|
			<a href="/default/add">添加</a>
			|
			<a href="/default/analyze">分析</a>
			|
			<a href="/login/editpwd">修改密码</a>
			|
			<a href="/login/logout">注销</a>
		</span>
	</div>