<div id = "analyze" >
	
</div>

<div id="content" style = "text-align:center;">
	<h2 style = "text-align:center;" class = "title">总数: <?= sprintf("%.2f", $data['total'])?></h2>
	<form method="post" action="del.php?action=multi">
		<ul>
			<li class=" title1 contact-list">
				<span>姓名</span>
				<span>金额</span>
				<span>时间</span>
			</li>
			<?php foreach ($data['rows'] as $row) : ?>
				<li class="contact-list">
					<span><?= $row['cname']?></span>
					<span><?= sprintf("%.2f", $row['cbirth'])?></span>
					<span><?= date("Y-m-d H:i:s", strtotime($row['aadd']))?></span>
				</li>
			<?php endforeach;?>
		</ul>
	</form>
</div>