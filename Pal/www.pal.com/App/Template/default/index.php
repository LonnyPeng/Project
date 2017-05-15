<div id="content">
	<form method="post" action="del.php?action=multi">
		<ul>
			<li class=" title1 contact-list">
				<label>&nbsp;&nbsp;</label>
				<span>姓名</span>
				<span>性别</span>
				<span>金额</span>
				<span>电话</span>
				<span>地址</span>
				<span>时间</span>
				<span>操作</span>
			</li>
			<?php foreach ($data['rows'] as $row) : ?>
				<li class="contact-list">
					<input type="checkbox" name="ck[]" value="<?= $row['cid']?>" />
					<span><?= $row['cname']?></span>
					<span><?= $row['csex'] ? '男' : '女'?></span>
					<span><?= sprintf("%.2f", $row['cbirth'])?></span>
					<span><?= $row['ctel']?></span>
					<span><?= $row['caddr']?></span>
					<span><?= date("Y-m-d H:i:s", strtotime($row['aadd']))?></span>
					<span>
						<a href="/default/add?id=<?= $row['cid']?>"><i class="fa fa-edit"></i></a>
						|
						<a href="/default/delete?id=<?= $row['cid']?>"><i class="fa fa-trash"></i></a>
					</span>
				</li>
			<?php endforeach;?>
		</ul>
		<div class="paging">
			<button class="button" type="submit">删除多项</button> | <?= $data['page'];?>	
		</div>
	</form>
</div>