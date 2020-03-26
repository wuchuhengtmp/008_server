<title>{$msg['title']}</title>
<meta name="keywords" content="{$msg['keywords']}" />
<meta name="description" content="{$msg['description']}" />

<!--bannerTwo-->
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
			<span class="locat">{$catmsg['cat_name']}</span>
		</div>
		<h4 class="conhd">
			{$msg['title']}
		</h4>
		<div class="about">
			{$msg['content']}
		</div>
		<!--about-->
	</div>
	<!--fr-->
</div>
<!--container-->