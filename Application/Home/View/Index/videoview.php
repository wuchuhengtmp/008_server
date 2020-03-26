<title>{$msg['title']}</title>
<meta name="keywords" content="{$msg['keywords']}" />
<meta name="description" content="{$msg['description']}" />

<!--bannerTwo-->
<div class="bannerTwo banner4" style="background: url('{$bannerMsg['img']}') top center no-repeat"></div>
<!--bannerTwo-->
<div class="container content clearfix">
	<div class="fl">
		<h3>
			视频中心
			<span class="en">Video Center</span>
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
				         <a href="__CONTROLLER__/video/cat_id/'.$cl['cat_id'].'">'.$cl['cat_name'].'</a>
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
			<a href="__CONTROLLER__/video">视频中心</a>
			<span class="line">></span>
			<span class="locat">{$catmsg['cat_name']}</span>
		</div>
		<h4 class="conhd">
			{$msg['title']}
		</h4>
		<div class="about">
		    <?php
		    $content=htmlspecialchars_decode(html_entity_decode($msg['content']));
		    $content=str_replace("&#39;", '"', $content);
		    echo $content;
		    ?>
			
		</div>
		<!--about-->
	</div>
	<!--fr-->
</div>
<!--container-->