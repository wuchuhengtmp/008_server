<script type="text/javascript" src="__JS__/daojishi.js"></script>
<script type="text/javascript" src="__JS__/sms.js"></script>
	
<body class="bg">
	<div class="container">
		<div class="login">
			<img src="/Public/static/admin/img/logo.png" class="imgResponsive" />
			<form action="__CONTROLLER__/register" method="post">
			<input type="hidden" name="referrer_id" value="{$referrer_id}">
			<ul class="ulform">
				<li>
					<label class="lb">
						<i class="icon ic1"></i>
					</label>
					<input type="text" class="inptxt" name="phone" id="phone" placeholder="请输入手机号码" />
				</li>
				<li>
					<label class="lb">
						<i class="icon ic2"></i>
					</label>
					<input type="text" class="inptxt inptxt2" name="code" placeholder="请输入验证码" />
					<input type="text" onclick="sendRegisterCode();time(this)" id="btn" value="获取验证码" class="btnSend" readonly="readonly"/>
				</li>
				<li>
					<label class="lb">
						<i class="icon ic3"></i>
					</label>
					<input type="password" class="inptxt" name="pwd1" placeholder="请输入密码" />
				</li>
				<!-- <li>
					<label class="lb">
						<i class="icon ic3"></i>
					</label>
					<input type="password" class="inptxt" name="pwd2" placeholder="请再次输入密码" />
				</li>
				<li>
					<label class="lb">
						<i class="icon ic4"></i>
					</label>
					<input type="text" class="inptxt" name="referrer_phone" value="{$referrer_phone}" placeholder="请输入邀请人手机号" />
				</li> -->
				<li>
				   <a href="javascript:;" id="phoneAjax" style="color:red"></a>
				</li>
				<li>
					<input type="submit" value="注册" class="btnReg" />
				</li>
				<li>
					<a href="__MODULE__/Index/down/inviteCode/{$auth_code}" class="btnReg" style="display: block;text-align: center;background: #999;">已注册立即下载</a>
				</li>
			</ul>
			</form>
		</div>
	</div>
</body>