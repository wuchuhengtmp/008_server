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
<script src="__ADMIN_JS__/plugins/iCheck/icheck.min.js"></script>
<!-- Sweet Alert -->
<link href="__ADMIN_CSS__/plugins/sweetalert/sweetalert.css" rel="stylesheet">
<script src="__ADMIN_JS__/plugins/sweetalert/sweetalert.min.js"></script>
<!-- Sweet Alert -->

<link rel="stylesheet" type="text/css" href="__CSS__/page.css" />
<script type="text/javascript" src="__JS__/area.js"></script>

<script type="text/javascript">
function del(id)
{
	if(id!='') {
		swal({
			title:"确定要删除该收货地址吗？",
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
				url:'/dmooo.php/ConsigneeAddress/del',
				dataType:"html",
				data:"id="+id,
				success:function(msg)
				{
				    if(msg=='1'){
						swal({
							title:"删除成功！",
							text:"",
							type:"success"
						},function(){location.reload();})
					}else {
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
						<h3>当前位置： 商城系统 &raquo; 收货地址管理</h3>
					</div>
					<div class="ibox-content">
						<form action="__ACTION__" method="get" role="form" class="form-inline pull-left">
                        	<input type="hidden" name="p" value="1"> 
                        	地区：<select name="province" id="province" class="form-control"></select>
                                   <select name="city" id="city" runat="server" class="form-control"></select>
                                   <select name="county" id="county" runat="server" class="form-control"></select>
                                   <script type="text/javascript">var opt0 = ["","",""];</script>
                                   <script type="text/javascript">setup()</script>
                        	详细地址：<input type="text" placeholder="" name="detail_address" class="form-control" style="width:90px">
                        	收件人：<input type="text" placeholder="" name="consignee" class="form-control" style="width:90px">
                        	联系电话：<input type="text" placeholder="" name="contact_number" class="form-control" style="width:90px">
                        	所属用户：<input type="text" placeholder="" name="user_account" class="form-control" style="width:90px">
                        	<button class="btn btn-primary" type="submit">查询</button>
                        </form>
						<a class="btn btn-primary pull-right" href="__CONTROLLER__/add">添加收货地址</a>
						<div class="">
							<table class="table table-bordered table-striped table-hover">
								<thead>
									<tr>
										<th>ID</th>
                                        <th>地区</th>
                                        <th>详细地址</th>
                                        <th>收件人</th>
                                        <th>联系电话</th>
                                        <th>邮编</th>
                                        <th>所属用户</th>
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
                                          <td>{$l['province']}-{$l['city']}-{$l['county']}</td>
                                          <td>{$l['detail_address']}</td>
                                          <td>{$l['consignee']}</td>
                                          <td>{$l['contact_number']}</td>
                                          <td>{$l['postcode']}</td>
                                          <td>
                                          <?php
                                          //获取用户信息
                                          $UserMsg=$User->getUserDetail($l['user_id']);
                                          echo $UserMsg['account'];
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