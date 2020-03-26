<title>{$msg['cat_name']}</title>
<meta name="keywords" content="{$msg['keywords']}" />
<meta name="description" content="{$msg['description']}" />

<div class="bannerTwo" style="background: url('{$bannerMsg['img']}') top center no-repeat"></div>
<!--bannerTwo-->
<div class="container content clearfix">
	<div class="fl">
		<h3>
			产品中心
			<span class="en">Product Center</span>
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
		    	if($cl['parent_id']==6)
		    	{
		    		echo '<li class="'.$class.'">
				         <a href="__ACTION__/cat_id/'.$cl['cat_id'].'">'.$cl['cat_name'].'</a>
			          </li>';
		    	}
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
			<a href="__ACTION__">产品中心</a>
			<span class="line">></span>
			<span class="locat">{$msg['cat_name']}</span>
		</div>
		<h4 class="conhd">
			{$msg['cat_name']}
			<span class="en">Product Center</span>
		</h4>
		<div class="consub">
		    <?php 
		    foreach ($SubCatList as $sl)
		    {
		    	if($sl['cat_id']==$sub_cat_id)
		    	{
		    		$sub_class='current';
		    	}else {
		    		$sub_class='';
		    	}
		    	echo '<a href="__ACTION__/cat_id/'.$cat_id.'/sub_cat_id/'.$sl['cat_id'].'" class="'.$sub_class.'">'.$sl['cat_name'].' </a>';
		    }
		    ?>
		</div>
		<!--consub-->
		<ul class="list clearfix products">
		    <?php 
		    foreach ($list as $l)
		    {
		    	$title=msubstr($l['title'],0,13);
		    	$description=$l['description'];
		    	echo '<li>
		               <a href="__CONTROLLER__/productview/cat_id/'.$cat_id.'/sub_cat_id/'.$sl['cat_id'].'/id/'.$l['article_id'].'">
					        <img src="'.$l['img'].'" />
					        <div class="listext">
						      <h5>'.$title.'</h5>
						         <p class="ellipsis">'.$description.'</p>
					        </div>
				      </a>
			         </li>';
		    }
		    ?>
		</ul>
		<div class="pages">
			{$page}
		</div>
	</div>
	<!--fr-->
</div>
<!--container-->


