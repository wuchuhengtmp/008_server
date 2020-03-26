<title>{$msg['title']}</title>
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
				         <a href="__CONTROLLER__/product/cat_id/'.$cl['cat_id'].'">'.$cl['cat_name'].'</a>
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
			<a href="__CONTROLLER__/product">产品中心</a>
			<span class="line">></span>
			<span class="locat">{$catmsg['cat_name']}</span>
		</div>
		<h4 class="conhd">
			{$catmsg['cat_name']}
			<span class="en">Product Center</span>
		</h4>
		<div class="productDet">
			<div class="pdhd clearfix">
				<img src="{$msg['img']}" class="lefter" />
				<div class="pdhdCon">
					<h4>{$msg['title']}</h4>
					<p>{$msg['description']}</p>
					<a href="{$msg['file']}" target="_blank" class="btnRead">查看电子样本</a>
				</div>
				<!--pdhdCon-->
			</div>
			<!--pdhd-->
			<div class="pdbd">
				<h4>介绍详情</h4>
				{$msg['content']}
			</div>
			<!--pdbd-->
		</div>
		<!--productDet-->
	</div>
	<!--fr-->
</div>
<!--container-->


