<?php 
switch (CONTROLLER_NAME.'/'.ACTION_NAME)
{
	//首页
	case 'Index/index':
		$hcss1='current';
		break;
	//关于我们
	case 'Index/about':
		$hcss2='current';
		break;
	//产品中心
	case 'Index/product':
		$hcss3='current';
		break;
	case 'Index/productview':
		$hcss3='current';
		break;
	//业绩介绍
    case 'Index/cases':
		$hcss4='current';
		break;
	case 'Index/caseview':
		$hcss4='current';
		break;
	//新闻中心
	case 'Index/news':
		$hcss5='current';
		break;
	case 'Index/newsview':
		$hcss5='current';
		break;
	//视频中心
	case 'Index/video':
		$hcss6='current';
		break;
	case 'Index/videoview':
		$hcss6='current';
		break;
	//在线留言
	case 'Index/online':
		$hcss7='current';
		break;
	//联系我们
	case 'Index/contact':
		$hcss8='current';
		break;
	default:
		break;
}
?>
<include file="$qq_file" />
<!--top-->
<div class="top clearfix">
	<div class="container">
		<ul class="nav fr">
			<li>
				<a href="/index.php" class="{$hcss1} an">首页</a>
			</li>
			<li>
				<a href="__MODULE__/Index/about" class="{$hcss2} an">关于我们</a>
				<ul class="subnav">
				    <?php 
				    //文章列表
				    $Article=new \Common\Model\ArticleModel();
				    $aboutlist=$Article->getArticleList(1,'asc');
				    foreach ($aboutlist as $al)
				    {
				    	echo '<li>
						        <a href="__MODULE__/Index/about/id/'.$al['article_id'].'">'.$al['title'].'</a>
					          </li>';
				    }
				    ?>
				</ul>
			</li>
			<li>
				<a href="__MODULE__/Index/product" class="{$hcss3} an">产品中心</a>
				<ul class="subnav">
				    <?php
				    //根据父级ID获取子分类
				    $ArticleCat=new \Common\Model\ArticleCatModel();
				    $productlist=$ArticleCat->getSubCatList(6);
				    foreach ($productlist as $pl)
				    {
				    	if($pl['parent_id']==6)
				    	{
				    		echo '<li style="width:130px">
						        <a href="__MODULE__/Index/product/cat_id/'.$pl['cat_id'].'">'.$pl['cat_name'].'</a>
					          </li>';
				    	}
				    }
				    ?>
				</ul>
			</li>
			<li>
				<a href="__MODULE__/Index/cases" class="{$hcss4} an">业绩介绍</a>
			</li>
			<li>
				<a href="__MODULE__/Index/news" class="{$hcss5} an">新闻中心</a>
			</li>
			<li>
				<a href="__MODULE__/Index/video" class="{$hcss6} an">视频中心</a>
			</li>
			<li>
				<a href="__MODULE__/Index/online" class="{$hcss7} an">在线留言</a>
			</li>
			<li>
				<a href="__MODULE__/Index/contact" class="{$hcss8} an">联系我们</a>
			</li>
		</ul>
		<h1 class="logo">
			<a href="">
				<img src="__HOME_IMG__/logo.png" />
			</a>
		</h1>
	</div>
</div>
<!--top-->