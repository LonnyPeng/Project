<div id="add">
	<h2 class="title">添加信息</h2>
	<form method="post" action="/default/adddo<?= $data['id'] ? "?id={$data['id']}" : ''?>">
		<table cellpadding="0" cellspacing="0">
			<tr>
				<td>姓名：</td>
				<td><input type="text" name="username" placeholder="张三" value="<?= isset($data['row']['cname']) ? $data['row']['cname'] : ''?>"/></td>
			</tr>
			<tr>
				<td>性别：</td>
				<td>
					男<input type="radio" name="usersex" value="1" <?= isset($data['row']['csex']) ? ($data['row']['csex'] ? "checked='checked'" : '') : "checked='checked'"?> />
					女<input type="radio" name="usersex" value="0" <?= isset($data['row']['csex']) && !$data['row']['csex'] ? "checked='checked'" : "";?> />
				</td>
			</tr>
			<tr>
				<td>金额：</td>
				<td><input type="text" name="userbirth" placeholder="100" value="<?= isset($data['row']['cbirth']) ? $data['row']['cbirth'] : ''?>"/></td>
			</tr>
			<tr>
				<td>电话：</td>
				<td><input type="text" name="usertel" placeholder="15036615495" value="<?= isset($data['row']['ctel']) ? $data['row']['ctel'] : ''?>"></td>
			</tr>
			<tr>
				<td>地址：</td>
				<td><input type="text" name="useraddr" value="<?= isset($data['row']['caddr']) ? $data['row']['caddr'] : ''?>"></td>
			</tr>
			<tr>
				<td>日期：</td>
				<td><input type="text" onclick="new Calendar().show(this);" name="aadd" readonly="readonly" value="<?= isset($data['row']['aadd']) ? $data['row']['aadd'] : ''?>"/></td>
			</tr>
			<tr>
				<td colspan="2" style="text-align:center;">
					<button class="button" type="submit">提&nbsp;交</button>
				</td>
			</tr>
		</table>
	</form>
</div>

<script type="text/javascript">
	$('#add form').submit(function () {
		if (!this.username.value) {
			$.waring('Please enter user name.');
			return false;
		} 
		if (!this.userbirth.value) {
			$.waring('Please enter money.');
			return false;
		}

		$(this).ajaxAuto();
	});
</script>