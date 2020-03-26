$(document).ready(function(){
	//检验用户名
	$('#username').blur(function(){
		username=$('#username').val();
		/*if(username=='')
		{
			$('#nameAjax').html('用户名不能为空！');
			return false;
		}else {
			$('#nameAjax').html('');
		}*/
		if(username)
		{
			$.ajax({
				type:"POST",
				url:"add",
				dataType:"html",
				data:"username="+username,
				success:function(msg)
				{
					$('#nameAjax').html(msg);
				}
			});
		}
	});
	
	//检验密码
	$('#password2').blur(function(){
		password=$('#password').val();
		password2=$('#password2').val();
		if(password=='' || password2=='')
		{
			$('#pwdAjax').html('密码不能为空！');
			return false;
		}else {
			$('#pwdAjax').html('');
		}
		$.ajax({
			type:"POST",
			url:"add",
			dataType:"html",
			data:"password="+password+"&password2="+password2,
			success:function(msg)
			{
				$('#pwdAjax').html(msg);
			}
		});
	});
	
	//检验EMAIL
	$('#email').blur(function(){
		email=$('#email').val();
		if(email!='')
		{
			$.ajax({
				type:"POST",
				url:"add",
				dataType:"html",
				data:"email="+email,
				success:function(msg)
				{
					$('#emailAjax').html(msg);
				}
			});
		}else {
			$('#emailAjax').html('');
		}
	});
	
	//检验手机号码
	$('#phone').blur(function(){
		phone=$('#phone').val();
		if(phone!='')
		{
			$.ajax({
				type:"POST",
				url:"add",
				dataType:"html",
				data:"phone="+phone,
				success:function(msg)
				{
					$('#phoneAjax').html(msg);
				}
			});
		}else {
			$('#phoneAjax').html('');
		}
	});
	
	//检验推荐人
	$('#referrer_phone').blur(function(){
		referrer_phone=$('#referrer_phone').val();
		if(referrer_phone!='')
		{
			$.ajax({
				type:"POST",
				url:"add",
				dataType:"html",
				data:"referrer_phone="+referrer_phone,
				success:function(msg)
				{
					$('#referrer_phoneAjax').html(msg);
				}
			});
		}else {
			$('#referrer_phoneAjax').html('');
		}
	});
	
	//提交注册
	$('#sub').click(function(){
		username=$('#username').val();
		password=$('#password').val();
		password2=$('#password2').val();
		email=$('#email').val();
		phone=$('#phone').val();
		remark=$('#remark').val();
		group_id=$('#group_id').val();
		if(group_id=='')
		{
			$('#gAjax').html('X请选择会员组');
			return false;
		}
		is_freeze=$('input:radio[name="is_freeze"]:checked').val();
		//推荐人
		referrer_phone=$('#referrer_phone').val();
		$.ajax({
			type:"POST",
			url:"add",
			dataType:"html",
			data:"username="+username+"&password="+password+"&password2="+password2+"&email="+email+"&phone="+phone+"&remark="+remark+"&group_id="+group_id+"&is_freeze="+is_freeze+"&referrer_phone="+referrer_phone,
			success:function(msg)
			{
				if(msg=='1')
				{
					alert('新增会员成功！');
					location.href='/dmooo.php/User/index/group_id/'+group_id;
				}else {
					alert('操作失败！');
					location.reload();
				}
			}
		});
	});
});