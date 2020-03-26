<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link href="__ADMIN_CSS__/bootstrap.min14ed.css?v=3.3.6" rel="stylesheet">
<link href="__ADMIN_CSS__/font-awesome.min93e3.css?v=4.4.0" rel="stylesheet">
<link href="__ADMIN_CSS__/plugins/iCheck/custom.css" rel="stylesheet">
<link href="__ADMIN_CSS__/animate.min.css" rel="stylesheet">
<link href="__ADMIN_CSS__/style.min862f.css?v=4.1.0" rel="stylesheet">

<script src="__ADMIN_JS__/jquery.min.js?v=2.1.4"></script>
<!-- Sweet Alert -->
<link href="__ADMIN_CSS__/plugins/sweetalert/sweetalert.css" rel="stylesheet">
<script src="__ADMIN_JS__/plugins/sweetalert/sweetalert.min.js"></script>
<!-- Sweet Alert -->

<link rel="stylesheet" type="text/css" href="__CSS__/page.css" />

<script type="text/javascript">
function del(id)
{
	if(id!='') {
		swal({
			title:"确定要删除该授权码吗？",
			text:"",
			type:"warning",
			showCancelButton:true,
			cancelButtonText:"取消",
			confirmButtonColor:"#DD6B55",
			confirmButtonText:"删除",
			closeOnConfirm:false
		},function(){
			$.ajax({
				type:"POST",
				url:'/dmooo.php/UserAuthCode/del',
				dataType:"html",
				data:"id="+id,
				success:function(msg)
				{
					if(msg=='2')
					{
						swal({
							title:"对不起，该授权码已被使用，不准删除！",
							text:"",
							type:"error"
						},function(){location.reload();})
					}
				    if(msg=='1')
					{
						swal({
							title:"删除成功！",
							text:"",
							type:"success"
						},function(){location.reload();})
					}
					if(msg=='0'){
						swal({
							title:"操作失败！",
							text:"",
							type:"error"
						},function(){location.reload();})
					}
				}
			});
		})
	}
}
</script>
</head>

<body class="gray-bg">
	<div class="wrapper wrapper-content animated fadeInRight">
		<div class="row">
			<div class="col-sm-12">
				<div class="ibox float-e-margins">
					<div class="ibox-title">
						<h3>当前位置：会员管理 &raquo; 会员授权码管理</h3>
					</div>
					<div class="ibox-content">
						<form action="__ACTION__" method="get" role="form" class="form-inline pull-left">
                        	<input type="hidden" name="p" value="1"> 
                        	授权码：<input type="text" placeholder="" name="auth_code" class="form-control" style="width:100px">
                        	<button class="btn btn-primary" type="submit">查询</button>
                        </form>
						<a class="btn btn-primary pull-right" href="__CONTROLLER__/add">添加授权码</a>
						<div class="ibox">
							<table class="table table-bordered table-striped table-hover">
								<thead>
									<tr>
										<th>ID</th>
                                        <th>授权码</th>
                                        <th>所属用户</th>
                                        <th>是否使用</th>
                                        <th>操作</th>
									</tr>
								</thead>
								<tbody>
									<?php 
									$User=new \Common\Model\UserModel();
									?>
									<foreach name="list" item="l">
                            	      <tr>
                            	          <td>{$l['id']}</td>
                            			  <td>{$l['auth_code']}</td>
                            			  <td>
                            			  <?php 
                            			  if($l['user_id']) {
                            			  	$userMsg=$User->getUserDetail($l['user_id']);
                            			  	echo $userMsg['account'];
                            			  }
                            			  ?>
                            			  </td>
                            			  <td>
                            			  <?php 
                            			  if($l['is_used']=='Y')
                            			  {
                            			  	echo '已使用';
                            			  }else {
                            			  	echo '未使用';
                            			  }
                            			  ?>
                            			  </td>
                            			  <td>
                            			      <a href="__CONTROLLER__/edit/id/{$l.id}" title="修改">
                            			         <i class="fa fa-file-text-o text-danger" style="font-size:2.0rem"></i>&nbsp;
                            			      </a>
                            			      <a href="javascript:;" onclick="del({$l.id});" title="删除">
                            			         <i class="fa fa-trash-o text-danger" style="font-size:2.0rem"></i>&nbsp;
                            		          </a>
                            			 </td>
                            		  </tr>
                            		  </foreach>
								</tbody>
							</table>
							<div class="pages">{$page}</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</body>
</html>