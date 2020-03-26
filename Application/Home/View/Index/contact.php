<link rel="stylesheet" type="text/css" href="__HOME_CSS__/dmoo.css" />

<div class="dBanner">
	<img src="{$bannerMsg.img}" alt="{$bannerMsg.title}"/>
</div>
<!--dBanner-->
<div class="dcontainer2 clearfix">
	<div class="dcontent">
		<div class="dconL fl">
			<h2>
				<span class="block">联系我们</span>
				<span class="en">CONTACT US</span>
			</h2>
			<ul class="conlist">
				<li>
				<?php 
				foreach ($list as $l)
				{
					if($l['article_id']==$article_id)
					{
						$css='current';
					}else {
						$css='';
					}
					echo '<a href="__ACTION__/id/'.$l['article_id'].'" class="clearfix block '.$css.'">
						<span class="fr">>></span>
						'.$l['title'].'
					</a>';
				}
				?>
				</li>
			</ul>
		</div>
		<div class="dconR fr">
			<div class="conhd clearfix">
				<div class="breadcrumb fr">
					当前位置：
					<a href="/index.php">首页</a>
					<span class="line">></span>
					<span class="locat">{$msg.title}</span>
				</div>
				<span class="line2"></span>
			</div>
			<!--conhd-->
			<div class="conbd">
				{$msg.content}
				<div class="map">
					<include file="Index:map" />
				</div>
			</div>
			<!--conbd-->
		</div>
		<!--conR-->
	</div>
</div>
<!--dcontainer-->