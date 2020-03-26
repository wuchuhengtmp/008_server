<title>{$msg['cat_name']}</title>
<meta name="keywords" content="{$msg['keywords']}" />
<meta name="description" content="{$msg['description']}" />

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
			<span class="locat">{$msg['cat_name']}</span>
		</div>
		<h4 class="conhd">
			{$msg['cat_name']}
			<span class="en">Video</span>
		</h4>
		<div class="video">
			<ul class="list clearfix">
			    <?php 
			    foreach ($list as $l)
			    {
			    	$title=msubstr($l['title'],0, 15);
			    	echo '<li>
					<a href="__CONTROLLER__/videoview/cat_id/'.$l['cat_id'].'/id/'.$l['article_id'].'">
						<img src="'.$l['img'].'" />
						<div class="videoicon">
							<i class="icon ic13"></i>
						</div>
					</a>
					<a href="__CONTROLLER__/videoview/cat_id/'.$l['cat_id'].'/id/'.$l['article_id'].'" class="videotxt">'.$title.'</a>
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