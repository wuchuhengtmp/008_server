<title>{$msg['title']}</title>
<meta name="keywords" content="{$msg['keywords']}" />
<meta name="description" content="{$msg['description']}" />

<!--bannerTwo-->
<div class="bannerTwo banner8" style="background: url('{$bannerMsg['img']}') top center no-repeat"></div>
<!--bannerTwo-->
<div class="container content clearfix">
	<div class="fl">
		<h3>
			关于我们
			<span class="en">ABOUT US</span>
		</h3>
		<ul class="item">
		    <?php 
		    $i=0;
		    foreach ($list as $l)
		    {
		    	$i++;
		    	if($i==1)
		    	{
		    		$class='first';
		    	}
		    	if($l['article_id']==$id)
		    	{
		    		$class='current';
		    	}
		    	echo '<li class="'.$class.'">
				         <a href="__ACTION__/id/'.$l['article_id'].'">'.$l['title'].'</a>
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
			<a href="__ACTION__">关于我们</a>
			<span class="line">></span>
			<span class="locat">{$msg['title']}</span>
		</div>
		<h4 class="conhd">
			{$msg['title']}
			<span class="en">ABOUT US</span>
		</h4>
		<div class="about">
			{$msg['content']}
		</div>
		<!--about-->
	</div>
	<!--fr-->
</div>
<!--container-->