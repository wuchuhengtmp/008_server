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

<script type="text/javascript">
function changeshow(id,status)
{
	if(id!='') {
		$.ajax({
			type:"POST",
			url:'/dmooo.php/GoodsAttributeValue/changeshow',
			dataType:"html",
			data:"id="+id+"&status="+status,
			success:function(msg)
			{
			    if(msg=='1')
				{
					swal({
						title:"修改状态成功！",
						text:"",
						type:"success"
					},function(){location.reload();})
				}else {
					swal({
						title:"修改状态失败！",
						text:"",
						type:"error"
					},function(){location.reload();})
				}
			}
		});
	}
}

function del(goods_attribute_id,goods_attribute_value_id,value)
{
	if(goods_attribute_id!='' && goods_attribute_value_id!='' && value!='')
	{
		swal({
			title:"确定要删除该商品分类属性值吗？",
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
				url:'/dmooo.php/GoodsAttributeValue/del',
				dataType:"html",
				data:"goods_attribute_id="+goods_attribute_id+"&goods_attribute_value_id="+goods_attribute_value_id+"&value="+value,
				success:function(msg)
				{
					if(msg=='2') {
						swal({
							title:"该商品分类属性值已被具体商品使用，不准删除！",
							text:"",
							type:"error"
						},function(){location.reload();})
					}
				    if(msg=='1') {
						swal({
							title:"删除成功！",
							text:"",
							type:"success"
						},function(){location.reload();})
					}
				    if(msg=='0') {
						swal({
							title:"删除失败！",
							text:"",
							type:"success"
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
						<h3>当前位置： 商城系统 &raquo; 商品分类属性管理 &raquo; 商品分类属性值管理<a class="pull-right" href="__MODULE__/GoodsAttribute/index/goods_cat_id/{$GoodsAttributeMsg.goods_cat_id}">返回商品分类属性配置 <i class="fa fa-angle-double-right"></i></a></h3>
					</div>
					<div class="ibox-content">
						<div class="row">
							<a class="btn btn-primary pull-right" href="__CONTROLLER__/add/goods_attribute_id/{$GoodsAttributeMsg.goods_attribute_id}">添加商品分类属性值</a>
       					</div>
						<div class="">
							<form action="__CONTROLLER__/changesort/goods_attribute_id/{$GoodsAttributeMsg.goods_attribute_id}" method="post">
							<table class="table table-bordered table-striped table-hover">
								<thead>
									<tr>
										  <th>ID</th>
                                          <th>商品分类属性值名称</th>
                                          <th>商品分类属性值配图</th>
                                          <th>是否显示</th>
                                          <th>排序</th>
                                          <th>操作</th>
									</tr>
								</thead>
								<tbody>
									<foreach name="list" item="l">
                                       <tr>
                                          <td>{$l['goods_attribute_value_id']}</td>
                                          <td>{$l['name']}</td>
                                          <td>
                                          <?php 
                                          if($l['img']) {
                                          	echo '<img src="'.$l['img'].'" height="50px">';
                                          }
                                          ?>
                                          </td>
                                          <td>
                                            <if condition='$l[is_show] eq Y'>
                                               <button type="button" class="btn btn-primary btn-sm" onclick="changeshow({$l.goods_attribute_value_id},'N');">显示</button>
                                			<else/>
                                			   <button type="button" class="btn btn-danger btn-sm" onclick="changeshow({$l.goods_attribute_value_id},'Y');">不显示</button>
                                			</if>
                                		  </td>
                                          <td class="has-warning"><input name="sort[{$l.goods_attribute_value_id}]" value="{$l.sort}" class="form-control" style="width:50px;text-align:center"/></td>
                                          <td>
                                		     <a href="__CONTROLLER__/edit/goods_attribute_value_id/{$l.goods_attribute_value_id}" title="修改">
                                			     <i class="fa fa-file-text-o text-danger" style="font-size:2.0rem"></i>&nbsp;
                                		     </a>
                                		     <a href="javascript:;" onclick="del({$l.goods_attribute_id},{$l.goods_attribute_value_id},'{$l.name}');" title="删除">
                                			     <i class="fa fa-trash-o text-danger" style="font-size:2.0rem"></i>&nbsp;
                                			 </a>
                                		  </td>
                                	  </tr>
                                	  </foreach>
									  <tr>
										<td colspan="6">
	        								<input type="submit" class="btn btn-primary pull-right" value="统一排序">
		  								</td>
	   								 </tr>
								</tbody>
							</table>
							</form>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</body>
</html>