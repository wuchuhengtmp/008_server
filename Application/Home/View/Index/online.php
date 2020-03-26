<link rel="stylesheet" type="text/css" href="__HOME_CSS__/dmoo.css" />

<div class="dBanner">
	<img src="{$bannerMsg.img}" alt="{$bannerMsg.title}"/>
</div>
<!--dBanner-->
<div class="dcontainer">
	<div class="leaveMessage">
		<div class="lmhd clearfix">
			<div class="breadcrumb fr">
				当前位置：
				<a href="/index.php">首页</a>
				<span class="line">></span>
				<span class="locat">在线留言</span>
			</div>
			<div class="fl lmhdlefter">
				<h2>在线留言</h2>
				<p class="en">ONLINE MASSAGE</p>
			</div>
		</div>
		<!--lmhd-->
		<div class="lmbd">
			<div class="lmbhd clearfix">
				<i class="icm fl"></i>
				<div class="lmbhInfo">
					<span class="fr">请填写真实有效的个人信息，便于我们与您联系&nbsp;</span>
					<h3>报名信息</h3>
				</div>
			</div>
			<!--lmbhd-->
			<form action="__ACTION__" method="post">
            <input type="hidden" name="cat_id" value="1">
			<ul class="lmessagelist">
				<li class="clearfix">
					<label for="" class="lb fl">姓名</label>
					<input type="text" name="linkman" value="" class="inptxt" />
				</li>
				<li class="clearfix">
					<label for="" class="lb fl">电话</label>
					<input type="text" name="phone" value="" class="inptxt" />
				</li>
				<li class="clearfix">
					<label for="" class="lb fl">备注</label>
					<textarea name="content" rows="" cols="" class="textarea"></textarea>
				</li>
				<li class="clearfix">
					<label for="" class="lb fl">验证码</label>
					<input type="text" name="auth" value="" class="inptxt inptxt2 fl" />
					<span class="zcode">
						<img src="__CONTROLLER__/verify" alt="点击刷新验证码" onclick="this.src='__CONTROLLER__/verify?'+Math.random()"/>
					</span>
				</li>
				<li class="tc">
					<input type="submit" value="确认提交" class="btnSubmit" />
				</li>
			</ul>
			</form>
		</div>
		<!--lmbd-->
	</div>
	<!--leaveMessage-->
</div>
<!--dcontainer-->
