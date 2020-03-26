//发送手机验证码-通用
function sendDefualtCode()
{
	sendCode('sendDefault');
}

//发送手机验证码-注册
function sendRegisterCode()
{
	sendCode('sendRegister');
}

//发送手机验证码-找回密码
function sendFindpwdCode()
{
	sendCode('sendFindpwd');
}

//发送手机验证码-供应商找回密码
function sendSuppierFindpwdCode()
{
	sendCode('sendSuppierFindpwd');
}

//发送手机验证码
function sendCode(action)
{
	  var phone = $("#phone").val();
	  if(check(phone)==true)
	  {
		  $.ajax({
				 type:"POST",
				 url:"/index.php?c=Sms&a="+action,
				 dataType:"html",
				 data:"phone="+phone,
				 success:function(msg)
				 {
					 $('#phoneAjax').html(msg);
				 }
		  });
	  }
}

//检验手机号
function check(phone)
{
	  if(phone=='')
	  {
		  alert('手机号码不能为空');
		  return false;
	  }
	  var pcheck = /1[3578]\d{9}|1[47|66|77|88|91|98|99]\d{8}/;
	  if(!pcheck.test(phone))
	  {
		  alert('请输入有效的手机号码！');
		  return false;
	  }else {
		  return true;
	  }
}