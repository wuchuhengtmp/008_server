<title>{$msg['cat_name']}</title>
<meta name="keywords" content="{$msg['keywords']}" />
<meta name="description" content="$msg['description']}" />

<div class="bannerTwo banner6" style="background: url('{$bannerMsg['img']}') top center no-repeat"></div>
<!--bannerTwo-->
<div class="container content clearfix">
	<div class="fl">
		<h3>
			业绩介绍
			<span class="en">Performance</span>
		</h3>
		<ul class="item">
			<li class="first current bg">
				<a href="javascript:;">业绩介绍</a>
			</li>
		</ul>
	</div>
	<!--fl-->
	<div class="fr">
		<div class="breadcrumb">
			当前位置：
			<a href="/index.php">首页</a>
			<span class="line">></span>
			<a href="javascript:;"><span class="locat">业绩介绍</span></a>
		</div>
		<h4 class="conhd">
			业绩介绍
			<span class="en">Performance introduction</span>
		</h4>
		<div class="performance">
			<ul class="perflist">
			    <?php 
			    foreach ($list as $l)
			    {
			    	$title=msubstr($l['title'],0, 28);
			    	$description=msubstr($l['description'],0, 80);
			    	echo '<li>
					<img src="'.$l['img'].'" class="lefter" />
					<div class="perfCon">
						<h4>'.$title.'</h4>
						<p>'.$description.'</p>
						<a href="__CONTROLLER__/caseview/cat_id/'.$l['cat_id'].'/id/'.$l['article_id'].'" class="btnSee">查看更多>></a>
					</div>
				</li>';
			    }
			    ?>
			</ul>
			<div class="pages">
				{$page}
			</div>
		</div>
		<!--contact-->
	</div>
	<!--fr-->
</div>
<!--container-->
