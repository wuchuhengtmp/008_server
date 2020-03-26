<nav class="navbar-default navbar-static-side" role="navigation">
	<div class="nav-close">
		<i class="fa fa-times-circle"></i>
	</div>
	<div class="sidebar-collapse">
		<ul class="nav" id="side-menu">
			<li class="nav-header" style="text-align: center">
				<div class="dropdown profile-element">
					<span><img alt="image" class="img-circle" src="__ADMIN_IMG__/logo.png" width="72"/></span> 
					<a class="dropdown-toggle" data-toggle="dropdown" href="#"> 
					<span class="clear"> 
					<span class="block m-t-xs"><strong class="font-bold"><?php echo WEB_TITLE;?></strong></span>
					</span>
					</a>
				</div>
				<div class="logo-element">后台</div>
			</li>
			<li>
				<a href="__MODULE__/System/index"> 
					<i class="fa fa-home"></i> <span class="nav-label">主页</span>
				</a>
			</li>
			<li class="active">
				<a href="#"> 
					<i class="fa fa-th-list"></i> 
					<span class="nav-label">功能列表</span> 
					<span class="fa arrow"></span>
				</a>
				<ul class="nav nav-second-level">
					<li><a class="J_menuItem" href="__MODULE__/Banner/index/cat_id/1">首页广告图管理</a></li>
					<li><a class="J_menuItem" href="__MODULE__/Banner/index/cat_id/4">分享海报管理</a></li>
					<li><a class="J_menuItem" href="__MODULE__/TbGoods/index">淘宝推荐商品管理</a></li>
					<li><a class="J_menuItem" href="__MODULE__/User/index">团队成员管理</a></li>
					<li><a class="J_menuItem" href="__MODULE__/TbOrder/index">淘宝订单管理</a></li>
					<li><a class="J_menuItem" href="__MODULE__/PddOrder/index">拼多多订单管理</a></li>
					<li><a class="J_menuItem" href="__MODULE__/JingdongOrder/index">京东订单管理</a></li>
					<li><a class="J_menuItem" href="__MODULE__/Index/changePwd">修改密码</a></li>
				</ul>
			</li>
		</ul>
	</div>
</nav>