<div id="editpwd">
	<h2 class="title">修改密码</h2>
	<form method = "post" action="/login/editpwddo">
		<span>旧密码：<input type="text" name="oldpwd" /></span>
		<br />
		<span>新密码：<input type="text" name="newpwd1" /></span>
		<br />
		<span>重&nbsp;复：<input type="text" name="newpwd2" /></span>
		<br />
		<button class="button" type="submit">确&nbsp;定</button>
	</form>
</div>

<script type="text/javascript">
	$('#editpwd form').submit(function () {
		if (!this.oldpwd.value) {
			$.waring('Please enter old password.');
			return false;
		} 
		if (!this.newpwd1.value) {
			$.waring('Please enter new password.');
			return false;
		}
		if (!this.newpwd2.value) {
			$.waring('Please enter too new password.');
			return false;
		}
		if (this.newpwd1.value != this.newpwd2.value) {
			$.waring('Please enter too new password.');
			return false;
		}

		$(this).ajaxAuto();
	});
</script>