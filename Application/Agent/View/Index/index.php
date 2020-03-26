<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>登录</title>
<link rel="stylesheet" type="text/css" href="__ADMIN_CSS__/login.css"/>
</head>
<?php
if($_COOKIE['remember']=='ok')
{
  $checked='checked';
}else {
  $checked="";
}
?>
<body class="login">
	<div class="loginBox">
		<div class="lbhd">
			<img src="__ADMIN_IMG__/logo.png" alt="logo" width="72"/>
		</div><!--lbhd-->
		<div class="lbbd">
			<h4>代理商管理系统</h4>
			<form class="loginform" action="__CONTROLLER__/loginin" method="post">
			<ul>
				<li class="clearfix">
					<label for="" class="lefter">账&nbsp;&nbsp;&nbsp;号</label>
					<input type="text" class="iptxt" name="adminuser" value="<?php echo $_COOKIE['loginname'];?>"/>
				</li>
				<li class="clearfix">
					<label for="" class="lefter">密&nbsp;&nbsp;&nbsp;码</label>
					<input type="password" class="iptxt" name="adminpwd" value="<?php echo $_COOKIE['loginpwd'];?>"/>
				</li>
				<li class="clearfix">
					<label for="" class="lefter">验证码</label>
					<input type="text" class="iptxt iptxt2" name="auth"/>
					<p class="zcode"><img src="__CONTROLLER__/verify" alt="点击刷新验证码" onclick="this.src='__CONTROLLER__/verify?'+Math.random()" style="width:92px;height:39px"/></p>
				</li>
				<li>
					<input type="submit" class="btnsub" value="登  录" />
				</li>
			</ul>
			
			<div class="forget">
				<label for=""><input type="checkbox" class="ch" name="remember" value="ok"  class="remember" <?php echo $checked;?> />记住密码</label>
			<p style="font-size:18px;color:red;margin-top:30px">{$error}</p>
			</div><!--forget-->
			</form>
		</div><!--lbbd-->
	</div><!--loginBox-->
</body>
</html>
