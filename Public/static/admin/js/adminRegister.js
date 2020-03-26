$(document).ready(function(){
	//检验用户名
	$('#adminname').blur(function(){
		adminname=$('#adminname').val();
		if(adminname=='')
		{
			$('#nameAjax').html('用户名不能为空！');
			return false;
		}else {
			$('#nameAjax').html('');
		}
		$.ajax({
			type:"POST",
			url:"add",
			dataType:"html",
			data:"adminname="+adminname,
			success:function(msg)
			{
				$('#nameAjax').html(msg);
			}
		});
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
	
	//提交注册
	$('#sub').click(function(){
		adminname=$('#adminname').val();
		password=$('#password').val();
		password2=$('#password2').val();
		email=$('#email').val();
		phone=$('#phone').val();
		province=$('#province').val();
		city=$('#city').val();
		group_id=$('#group_id').val();
		if(group_id=='')
		{
			$('#gAjax').html('X请选择管理员组');
			return false;
		}
		status=$('input:radio[name="status"]:checked').val();
		if(adminname=='' || password=='' || password2=='' || status=='')
		{
			alert('注册信息请填写完整');
			return false;
		}
		$.ajax({
			type:"POST",
			url:"add",
			dataType:"html",
			data:"adminname="+adminname+"&password="+password+"&password2="+password2+"&email="+email+"&phone="+phone+"&group_id="+group_id+"&province="+province+"&city="+city+"&status="+status,
			success:function(msg)
			{
				if(msg=='1')
				{
					alert('新增管理员成功！');
					location.href='index';
				}else {
					alert('操作失败！');
					location.reload();
				}
			}
		});
	});
});