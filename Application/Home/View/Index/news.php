<title>{$msg['cat_name']}</title>
<meta name="keywords" content="{$msg['keywords']}" />
<meta name="description" content="{$msg['description']}" />

<div class="bannerTwo banner5" style="background: url('{$bannerMsg['img']}') top center no-repeat"></div>
<!--bannerTwo-->
<div class="container content clearfix">
	<div class="fl">
		<h3>
			新闻中心
			<span class="en">NEWS Center</span>
		</h3>
		<ul class="item">
		    <?php 
		    $i=0;
		    foreach ($catlist as $cl)
		    {
		    	$i++;
		    	if($i==1)
		    	{
		    		$class='first';
		    	}
		    	if($cl['cat_id']==$cat_id)
		    	{
		    		$class='current';
		    	}
		    	echo '<li class="'.$class.'">
				         <a href="__CONTROLLER__/news/cat_id/'.$cl['cat_id'].'">'.$cl['cat_name'].'</a>
			          </li>';
		    	$class='';
		    }
		    ?>
		</ul>
	</div>
	<!--fl-->
	<div class="fr">
		<div class="breadcrumb">
			当前位置：
			<a href="/index.php">首页</a>
			<span class="line">></span>
			<a href="__CONTROLLER__/news">新闻中心</a>
			<span class="line">></span>
			<span class="locat">{$msg['cat_name']}</span>
		</div>
		<h4 class="conhd">
			{$msg['cat_name']}
			<span class="en">Recruitment Information</span>
		</h4>
		<div class="newsCenter">
			<ul class="newslist">
			    <?php 
			    foreach ($list as $l)
			    {
			    	$title=msubstr($l['title'],0, 28);
			    	$description=msubstr($l['description'],0, 80);
			    	echo '<li>
					<img src="'.$l['img'].'" class="lefter" />
					<div class="newsCon">
						<h4>'.$title.'</h4>
						<p>'.$description.'</p>
						<a href="__CONTROLLER__/newsview/cat_id/'.$l['cat_id'].'/id/'.$l['article_id'].'" class="btnSee">查看更多>></a>
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