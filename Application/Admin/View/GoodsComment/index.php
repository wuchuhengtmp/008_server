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

<script type="text/javascript">
function del(id)
{
	if(id!='') {
		swal({
			title:"确定要删除该评论吗？",
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
				url:'/dmooo.php/GoodsComment/del',
				dataType:"html",
				data:"id="+id,
				success:function(msg)
				{
				    if(msg=='1')
					{
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
						<h3>当前位置： 商城系统 &raquo; 商品评论管理<a class="pull-right" href="__MODULE__/Goods/index/cat_id/{$msg.cat_id}">返回商品列表 <i class="fa fa-angle-double-right"></i></a></h3>
					</div>
					<div class="ibox-content">
						<form action="__ACTION__/goods_id/{$msg.goods_id}" method="get" role="form" class="form-inline pull-left">
                        	<input type="hidden" name="p" value="1"> 
                        	评论内容：<input type="text" placeholder="" name="content" class="form-control">
                        	<button class="btn btn-primary" type="submit">查询</button>
                        </form>
						<div class="ibox">
							<h3><strong>商品名称：{$msg.goods_name}</strong></h3>
							<table class="table table-bordered table-striped table-hover">
								<thead>
									<tr>
										<th>ID</th>
                                        <th>评论用户</th>
                                        <th>评分</th>
                                        <th>等级</th>
                                        <th width="40%">评论内容</th>
                                        <th>评论时间</th>
                                        <th>操作</th>
									</tr>
								</thead>
								<tbody>
									<?php 
									$User=new \Common\Model\UserModel();
									?>
									<foreach name="list" item="l">
                                       <tr>
                                          <td>{$l['goods_comment_id']}</td>
                                          <td>
                                          <?php 
                                          $UserMsg=$User->getUserDetail($l['user_id']);
                                          if($UserMsg['detail']['nickname']) {
                                          	$username=$UserMsg['detail']['nickname'];
                                          }else {
                                          	$username=$UserMsg['username'];
                                          }
                                          echo $username;
                                          ?>
                                          </td>
                                          <td>{$l['score']}颗星</td>
                                          <td>
                                          <?php 
                                          switch ($l['grade']) {
                                          	case '1':
                                          		$grade='好评';
                                          		break;
                                          	case '2':
                                          		$grade='中评';
                                          		break;
                                          	case '3':
                                          		$grade='差评';
                                          		break;
                                          }
                                          echo $grade;
                                          ?>
                                          </td>
                                          <td>
                                          <?php 
                                           $content=htmlspecialchars_decode(html_entity_decode($l['content']));
                                           $content=str_replace("&#39;", '"', $content);
                                           echo $content;
                                           ?>
                                          </td>
                                		  <td>{$l['comment_time']}</td>
                                		  <td>
                                		    <a href="javascript:;" onclick="del({$l.goods_comment_id});" title="删除">
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