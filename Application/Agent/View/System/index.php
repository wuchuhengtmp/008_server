<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="renderer" content="webkit">
<meta http-equiv="Cache-Control" content="no-siteapp" />
<title><?php echo WEB_TITLE;?>-代理商系统</title>
<!--[if lt IE 9]>
    <meta http-equiv="refresh" content="0;ie.html" />
<![endif]-->
<link rel="shortcut icon" href="favicon.ico">
<link href="__ADMIN_CSS__/bootstrap.min14ed.css?v=3.3.6" rel="stylesheet">
<link href="__ADMIN_CSS__/font-awesome.min93e3.css?v=4.4.0" rel="stylesheet">
<link href="__ADMIN_CSS__/animate.min.css" rel="stylesheet">
<link href="__ADMIN_CSS__/style.min862f.css?v=4.1.0" rel="stylesheet">
</head>

<body class="fixed-sidebar full-height-layout gray-bg" style="overflow: hidden">
	<div id="wrapper">
		<!--左侧导航开始-->
		<include file="Public:left" />
		<!--左侧导航结束-->
		<!--右侧部分开始-->
		<div id="page-wrapper" class="gray-bg dashbard-1">
			<include file="Public:header" />
			<div class="row J_mainContent" id="content-main">
                <iframe class="J_iframe" name="iframe0" width="100%" height="100%" src="__MODULE__/System/index_show" frameborder="0" data-id="index_v1.html" seamless></iframe>
            </div>
			<!-- <div class="row J_mainContent" id="content-main">
			{__CONTENT__}
			</div> -->
			<include file="Public:footer" />
		</div>
		<!--右侧部分结束-->
		<!--右侧边栏开始-->
		<div id="right-sidebar">
			<div class="sidebar-container">
				<div class="tab-content">
					<div id="tab-1" class="tab-pane active">
						<div class="sidebar-title">
							<h3><i class="fa fa-comments-o"></i> 主题设置</h3>
							<small><i class="fa fa-tim"></i>你可以从这里选择和预览主题的布局和样式，这些设置会被保存在本地，下次打开的时候会直接应用这些设置。</small>
						</div>
						<div class="skin-setttings">
							<div class="setings-item">
								<span>收起左侧菜单</span>
								<div class="switch">
									<div class="onoffswitch">
										<input type="checkbox" name="collapsemenu" class="onoffswitch-checkbox" id="collapsemenu"> 
										<label class="onoffswitch-label" for="collapsemenu"> 
										<span class="onoffswitch-inner"></span> 
										<span class="onoffswitch-switch"></span>
										</label>
									</div>
								</div>
							</div>
							<div class="setings-item">
								<span>固定顶部</span>
								<div class="switch">
									<div class="onoffswitch">
										<input type="checkbox" name="fixednavbar" class="onoffswitch-checkbox" id="fixednavbar"> 
										<label class="onoffswitch-label" for="fixednavbar"> 
										<span class="onoffswitch-inner"></span> 
										<span class="onoffswitch-switch"></span>
										</label>
									</div>
								</div>
							</div>
							<div class="setings-item">
								<span> 固定宽度 </span>
								<div class="switch">
									<div class="onoffswitch">
										<input type="checkbox" name="boxedlayout" class="onoffswitch-checkbox" id="boxedlayout"> 
										<label class="onoffswitch-label" for="boxedlayout"> 
										<span class="onoffswitch-inner"></span> 
										<span class="onoffswitch-switch"></span>
										</label>
									</div>
								</div>
							</div>
							<div class="title">皮肤选择</div>
							<div class="setings-item default-skin nb">
								<span class="skin-name "> <a href="#" class="s-skin-0"> 默认皮肤 </a> </span>
							</div>
							<div class="setings-item blue-skin nb">
								<span class="skin-name "> <a href="#" class="s-skin-1"> 蓝色主题 </a> </span>
							</div>
							<div class="setings-item yellow-skin nb">
								<span class="skin-name "> <a href="#" class="s-skin-3"> 黄色/紫色主题 </a> </span>
							</div>
						</div>
					</div>
				</div>

			</div>
		</div>
		<!--右侧边栏结束-->
	</div>
	<script src="__ADMIN_JS__/jquery.min.js?v=2.1.4"></script>
	<script src="__ADMIN_JS__/bootstrap.min.js?v=3.3.6"></script>
	<script src="__ADMIN_JS__/plugins/metisMenu/jquery.metisMenu.js"></script>
	<script src="__ADMIN_JS__/plugins/slimscroll/jquery.slimscroll.min.js"></script>
	<script src="__ADMIN_JS__/plugins/layer/layer.min.js"></script>
	<script src="__ADMIN_JS__/hplus.min.js?v=4.1.0"></script>
	<script type="text/javascript" src="__ADMIN_JS__/contabs.min.js"></script>
	<script src="__ADMIN_JS__/plugins/pace/pace.min.js"></script>
	
    <script src="__ADMIN_JS__/content.min.js"></script>
    <script src="__ADMIN_JS__/welcome.min.js"></script>
</body>


</html>
