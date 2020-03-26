<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>{$web_title}-<?php echo WEB_TITLE;?></title>
<meta name="keywords" content="{$web_keywords}" />
<meta name="description" content="{$web_description}" />
<meta name="viewport"
	content="width=device-width,initial-scale=1,minimum-scale=1,maximum-scale=1,user-scalable=no" />
<meta content="yes" name="apple-mobile-web-app-capable" />
<meta content="black" name="apple-mobile-web-app-status-bar-style" />
<meta content="telephone=no" name="format-detection" />
<meta content="email=no" name="format-detection" />
<link rel="stylesheet" type="text/css" href="__WAP_CSS__/common.css" />
<link rel="stylesheet" type="text/css" href="__WAP_CSS__/down.css" />
<script type="text/javascript" src="__WAP_JS__/jquery.min.js"></script>
<script type="text/javascript" src="__WAP_JS__/resize.js"></script>
</head>
<body>
	<div class="container bg">
		<h2><?php echo APP_NAME;?>APP下载</h2>
		<div class="btn">
			<a href="<?php echo DOWN_IOS;?>" class="block iphone"></a>
			<a href="<?php echo DOWN_ANDROID;?>" class="block andriod"></a>
			<!-- <a href="http://a.app.qq.com/o/simple.jsp?pkgname=com.work.diandianzhuan" class="block andriod"></a> -->
		</div>
	</div>
</body>
<script type="text/javascript">
		window.addEventListener('load',function(){
			if(isWeiXin()){
				//显示遮罩层
				$('body').addClass('show');
				/* var u = navigator.userAgent;
				if (u.indexOf('Android') > -1 || u.indexOf('Linux') > -1) 
				{
					//安卓手机
					
				} else if (u.indexOf('iPhone') > -1) {
					//苹果手机
					//显示遮罩层
					$('body').addClass('show');
				} else if (u.indexOf('Windows Phone') > -1) {
					//winphone手机
					
				} */
			}
			function isWeiXin(){
			  //window.navigator.userAgent属性包含了浏览器类型、版本、操作系统类型、浏览器引擎类型等信息，这个属性可以用来判断浏览器类型
			  var ua = window.navigator.userAgent.toLowerCase();
			  //通过正则表达式匹配ua中是否含有MicroMessenger字符串
			  if(ua.match(/MicroMessenger/i) == 'micromessenger'){
			  	 return true;
			  }else{
			 	 return false;
			  }
			}
		})
</script>
</html>