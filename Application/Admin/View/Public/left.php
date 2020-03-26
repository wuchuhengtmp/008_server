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
			<li>
				<a href="#"> 
					<i class="fa fa-th-list"></i> 
					<span class="nav-label">样式DIY设置</span> 
					<span class="fa arrow"></span>
				</a>
				<ul class="nav nav-second-level">
					<li><a class="J_menuItem" href="__MODULE__/DiyModule/index">功能模块管理</a></li>
				</ul>
			</li>
			<li>
				<a href="#"> 
					<i class="fa fa-file-text"></i> 
					<span class="nav-label">内容管理</span> 
					<span class="fa arrow"></span>
				</a>
				<ul class="nav nav-second-level">
					<li><a class="J_menuItem" href="__MODULE__/ArticleCat/index">文章管理</a></li>
					<li><a class="J_menuItem" href="__MODULE__/MessageCat/index">留言管理</a></li>
					<!-- <li><a class="J_menuItem" href="__MODULE__/HrefCat/index">友情链接</a></li> -->
					<li><a class="J_menuItem" href="__MODULE__/BannerCat/index">Banner/广告管理</a></li>
					<!-- <li><a class="J_menuItem" href="__MODULE__/Qq/index">QQ客服管理</a></li>
					<li><a class="J_menuItem" href="__MODULE__/Job/index">招聘管理</a></li> -->
				</ul>
			</li>
			<li>
				<a href="#"> 
					<i class="fa fa-bar-chart-o"></i> 
					<span class="nav-label">淘宝管理系统</span> 
					<span class="fa arrow"></span>
				</a>
				<ul class="nav nav-second-level">
					<li><a class="J_menuItem" href="__MODULE__/TaobaoCat/index">淘宝商品分类管理</a></li>
					<li><a class="J_menuItem" href="__MODULE__/TbCat/index">淘宝官方商品分类管理</a></li>
					<li><a class="J_menuItem" href="__MODULE__/TbOrder/index">淘宝订单管理</a></li>
					<li><a class="J_menuItem" href="__MODULE__/TbOrder/treatOrder">处理遗漏淘宝订单</a></li>
					<li><a class="J_menuItem" href="__MODULE__/TbOrder/allotOrder">处理无归属淘宝订单</a></li>
					<li><a class="J_menuItem" href="__MODULE__/TbOrder/allotOrderAll">批量处理无归属淘宝订单</a></li>
				</ul>
			</li>
			<li>
				<a href="#"> 
					<i class="fa fa-area-chart"></i> 
					<span class="nav-label">拼多多管理系统</span> 
					<span class="fa arrow"></span>
				</a>
				<ul class="nav nav-second-level">
					<li><a class="J_menuItem" href="__MODULE__/PddCat/index">拼多多商品分类管理</a></li>
					<li><a class="J_menuItem" href="__MODULE__/PddOrder/index">拼多多订单管理</a></li>
					<li><a class="J_menuItem" href="__MODULE__/PddOrder/treatOrder">处理拼多多遗漏订单</a></li>
				</ul>
			</li>
			<li>
				<a href="#"> 
					<i class="fa fa-line-chart"></i> 
					<span class="nav-label">京东管理系统</span> 
					<span class="fa arrow"></span>
				</a>
				<ul class="nav nav-second-level">
					<li><a class="J_menuItem" href="__MODULE__/JingdongCat/index">京东商品分类管理</a></li>
					<li><a class="J_menuItem" href="__MODULE__/JingdongOrder/index">京东订单管理</a></li>
					<li><a class="J_menuItem" href="__MODULE__/JingdongOrder/treatOrder">处理遗漏京东订单</a></li>
				</ul>
			</li>
            <li>
                <a href="#">
                    <i class="fa fa-area-chart"></i>
                    <span class="nav-label">唯品会管理系统</span>
                    <span class="fa arrow"></span>
                </a>
                <ul class="nav nav-second-level">
                    <li><a class="J_menuItem" href="__MODULE__/VipCat/index">唯品会商品分类管理</a></li>
                    <li><a class="J_menuItem" href="__MODULE__/VipOrder/index">唯品会订单管理</a></li>
                    <li><a class="J_menuItem" href="__MODULE__/VipOrder/treatOrder">处理遗漏唯品会订单</a></li>
                </ul>
            </li>
            <li>
                <a href="#">
                    <i class="fa fa-area-chart"></i>
                    <span class="nav-label">黑卡管理系统</span>
                    <span class="fa arrow"></span>
                </a>
                <ul class="nav nav-second-level">
                    <li><a class="J_menuItem" href="__MODULE__/CardCat/index">黑卡分类</a></li>
                    <li><a class="J_menuItem" href="__MODULE__/CardPrivilege/index">黑卡列表</a></li>
                </ul>
            </li>
			<li>
				<a href="#"> 
					<i class="fa fa-users"></i> 
					<span class="nav-label">社区/论坛系统</span> 
					<span class="fa arrow"></span>
				</a>
				<ul class="nav nav-second-level">
					<li><a class="J_menuItem" href="__MODULE__/BbsBoard/index">版块管理</a></li>
					<li><a class="J_menuItem" href="__MODULE__/BbsArticle/checkPending">待审核帖子列表</a></li>
					<li><a class="J_menuItem" href="__MODULE__/BbsArticle/checkPass">已审核帖子列表</a></li>
					<li><a class="J_menuItem" href="__MODULE__/BbsArticle/checkRefused">审核不通过帖子列表</a></li>
				</ul>
			</li>
			<li>
				<a href="#"> 
					<i class="fa fa fa-bar-chart-o"></i> 
					<span class="nav-label">营销中心</span> 
					<span class="fa arrow"></span>
				</a>
				<ul class="nav nav-second-level">
					<li><a class="J_menuItem" href="__MODULE__/BbsArticle/sendGoods">爆款商品推送</a></li>
					<li><a class="J_menuItem" href="__MODULE__/BbsArticle/sendArticle">文章推送</a></li>
					<li><a class="J_menuItem" href="__MODULE__/TbGoods/index">淘宝推荐商品管理</a></li>
					<li><a class="J_menuItem" href="__MODULE__/HotSearch/index">热门搜索设置</a></li>
					<li><a class="J_menuItem" href="__MODULE__/Rookie/index">拉新活动</a></li>
					<li><a class="J_menuItem" href="__MODULE__/TbGoodsFree/index">淘宝0元购商品管理</a></li>
					<li><a class="J_menuItem" href="https://pub.alimama.com/promo/search/index.htm?spm=a219t.7900221/1.1998910419.de727cf05.2a8f75a54Spltq&toPage=1&queryType=2" target="_blank">淘宝商品超级搜索</a></li>
				</ul>
			</li>
			<li>
				<a href="#"> 
					<i class="fa fa fa-bar-chart-o"></i> 
					<span class="nav-label">任务中心</span> 
					<span class="fa arrow"></span>
				</a>
				<ul class="nav nav-second-level">
					<li><a class="J_menuItem" href="__MODULE__/PointSet/infoSet">完善资料</a></li>
					<li><a class="J_menuItem" href="__MODULE__/System/userSet">分享好友</a></li>
					<li><a class="J_menuItem" href="javascript:alert('待开发');">实名认证</a></li>
					<li><a class="J_menuItem" href="__MODULE__/PointSet/set">积分系统</a></li>
				</ul>
			</li>
			<li>
				<a href="#"> 
					<i class="fa fa-shopping-cart"></i> 
					<span class="nav-label">商城系统</span> 
					<span class="fa arrow"></span>
				</a>
				<ul class="nav nav-second-level">
					<li><a class="J_menuItem" href="__MODULE__/Brand/index">厂家/品牌管理</a></li>
					<li><a class="J_menuItem" href="__MODULE__/GoodsCat/index">商品管理</a></li>
					<li><a class="J_menuItem" href="__MODULE__/Goods/recycle">商品回收站</a></li>
					<li><a class="J_menuItem" href="__MODULE__/ConsigneeAddress/index">收货地址管理</a></li>
					<li><a class="J_menuItem" href="__MODULE__/Invoice/index">发票信息管理</a></li>
					<li><a class="J_menuItem" href="__MODULE__/Bank/index">银行管理</a></li>
				</ul>
			</li>
			<li>
				<a href="#"> 
					<i class="fa fa-reorder"></i> 
					<span class="nav-label">订单管理</span> 
					<span class="fa arrow"></span>
				</a>
				<ul class="nav nav-second-level">
					<li><a class="J_menuItem" href="__MODULE__/Order/unpaid">未付款订单</a></li>
					<li><a class="J_menuItem" href="__MODULE__/Order/paid">已付款订单</a></li>
					<li><a class="J_menuItem" href="__MODULE__/Order/send">已发货订单</a></li>
					<li><a class="J_menuItem" href="__MODULE__/Order/finish">已确认收货订单</a></li>
					<li><a class="J_menuItem" href="__MODULE__/Order/comment">已评价/已结束订单</a></li>
					<li><a class="J_menuItem" href="__MODULE__/Order/refund">申请退款订单</a></li>
					<li><a class="J_menuItem" href="__MODULE__/Order/refundSuccess">退款成功订单</a></li>
					<li><a class="J_menuItem" href="__MODULE__/Order/refundFail">拒绝退款订单</a></li>
					<!-- 
					<li><a class="J_menuItem" href="__MODULE__/Order/add">人工录入订单</a></li>
					<li><a class="J_menuItem" href="__MODULE__/Order/StatisticsNum">订单数量统计</a></li>
					<li><a class="J_menuItem" href="__MODULE__/Order/StatisticsMoney">订单金额统计</a></li>
					 -->
				</ul>
			</li>
			<li>
				<a href="#"> 
					<i class="fa fa-user"></i> 
					<span class="nav-label">会员管理</span> 
					<span class="fa arrow"></span>
				</a>
				<ul class="nav nav-second-level">
					<li><a class="J_menuItem" href="__MODULE__/UserGroup/index">会员组管理</a></li>
					<li><a class="J_menuItem" href="__MODULE__/User/index">会员列表</a></li>
					<li><a class="J_menuItem" href="__MODULE__/UserBalanceRecord/index">会员余额变动记录</a></li>
					<li><a class="J_menuItem" href="__MODULE__/UserPointRecord/index">会员积分变动记录</a></li>
					<li><a class="J_menuItem" href="__MODULE__/UserAuthCode/index">会员授权码管理</a></li>
					<li><a class="J_menuItem" href="__MODULE__/User/everyday">每日注册会员统计</a></li>
					<li><a class="J_menuItem" href="__MODULE__/User/statistics">会员统计</a></li>
					<li><a class="J_menuItem" href="__MODULE__/User/index2">活跃会员统计</a></li>
					<li><a class="J_menuItem" href="__MODULE__/User/export">导出会员列表</a></li>
				</ul>
			</li>
			<li>
				<a href="#"> 
					<i class="fa fa-user-secret"></i> 
					<span class="nav-label">代理商系统</span> 
					<span class="fa arrow"></span>
				</a>
				<ul class="nav nav-second-level">
					<li><a class="J_menuItem" href="__MODULE__/Agent/index">查看用户列表</a></li>
				</ul>
			</li>
			<li>
				<a href="#"> 
					<i class="fa fa-dollar"></i> 
					<span class="nav-label">资金管理</span> 
					<span class="fa arrow"></span>
				</a>
				<ul class="nav nav-second-level">
					<li><a class="J_menuItem" href="__MODULE__/UserDrawApply/checkPending">用户待审核提现申请</a></li>
					<li><a class="J_menuItem" href="__MODULE__/UserDrawApply/checked">用户已审核提现申请</a></li>
				</ul>
			</li>
			<li>
				<a href="#"> 
					<i class="fa fa-gear"></i> 
					<span class="nav-label">系统设置</span> 
					<span class="fa arrow"></span>
				</a>
				<ul class="nav nav-second-level">
					<li><a class="J_menuItem" href="__MODULE__/System/webset">站点设置</a></li>
					<li><a class="J_menuItem" href="__MODULE__/System/sensitive">敏感词过滤</a></li>
					<li><a class="J_menuItem" href="__MODULE__/System/feeset">费用规则设置</a></li>
					<li><a class="J_menuItem" href="__MODULE__/System/accountSet">应用账号配置</a></li>
					<li><a class="J_menuItem" href="__MODULE__/System/drawSet">提现设置</a></li>
				</ul>
			</li>
			<li>
				<a href="#"> 
					<i class="fa fa-user-plus"></i> 
					<span class="nav-label">管理员管理</span> 
					<span class="fa arrow"></span>
				</a>
				<ul class="nav nav-second-level">
					<li><a class="J_menuItem" href="__MODULE__/Admin/index">管理员列表</a></li>
					<li><a class="J_menuItem" href="__MODULE__/AdminGroup/index">组别管理</a></li>
					<li><a class="J_menuItem" href="__MODULE__/AuthRule/index">权限管理</a></li>
					<li><a class="J_menuItem" href="__MODULE__/Admin/changepwd">修改密码</a></li>
					<li><a class="J_menuItem" href="__MODULE__/TbOrder/task">淘宝订单卡顿处理（长期订单不同步使用）</a></li>
				</ul>
			</li>
		</ul>
	</div>
</nav>