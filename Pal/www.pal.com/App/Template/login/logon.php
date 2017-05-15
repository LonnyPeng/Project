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
</head>
<body>
	<div id="login-box">
		<form method="post" action="/login/logindo">
			<h1>
				<span class="leaf-left"><i class="fa fa-leaf"></i></span>
				<span>管理员登陆</span>
				<span class="leaf-right"><i class="fa fa-leaf"></i></span>
			</h1><br />
			<b>用户名<i class="fa fa-user"></i>:</b>
			<input type="text" name="username" value="" />
			<b>密码<i class="fa fa-key"></i>:</b>
			<input type="password" name="userpwd" value="" />
			<button class="button" type="submit">
				&nbsp;
				<i class="fa fa-power-off"></i>
				&nbsp;
			</button>
		</form>
	</div>
	<script type="text/javascript">
		$('#login-box form').submit(function () {
			if (!this.username.value) {
				$.waring('Please enter user name.');
				return false;
			} 
			if (!this.userpwd.value) {
				$.waring('Please enter password.');
				return false;
			}

			$(this).ajaxAuto();
		});
	</script>
</body>
</html>
