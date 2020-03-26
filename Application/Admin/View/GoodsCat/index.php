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
$(document).ready(function(){
	$('.topCat').click(function(){
		var status=$(this).html();
		if(status=='+')
		{
			//改变符号
			$(this).html('-');
			//显示子分类
			$(this).parents('tr').nextUntil(".tr_top").show();
		}else {
			//改变符号
			$(this).html('+');
			//显示子分类
			$(this).parents('tr').nextUntil(".tr_top").hide();
		}
	});
	
	$(".i-checks").iCheck({
		checkboxClass:"icheckbox_square-green",
		radioClass:"iradio_square-green",
	});
});

function changestatus(id,status)
{
	if(id!='') {
		$.ajax({
			type:"POST",
			url:"/dmooo.php/GoodsCat/changestatus",
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

function changetop(id,status)
{
	if(id!='') {
		$.ajax({
			type:"POST",
			url:'/dmooo.php/GoodsCat/changetop',
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

function delcat(id)
{
	if(id!='') {
		swal({
			title:"确定要删除该商品分类吗？",
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
				url:"/dmooo.php/GoodsCat/del",
				dataType:"html",
				data:"cat_id="+id,
				success:function(msg)
				{
					if(msg=='3') {
						swal({
							title:"该分类下还有二级分类，需要先删除二级分类才能删除该分类！",
							text:"",
							type:"error"
						},function(){location.reload();})
					}
					if(msg=='2') {
						swal({
							title:"该分类下还有商品，不准直接删除！",
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
<style>
.topCat{
color:red;font-size:24px;font-weight: bold;
}
.tr_sub{
display:none;
}
</style>
</head>

<body class="gray-bg">
	<div class="wrapper wrapper-content animated fadeInRight">
		<div class="row">
			<div class="col-sm-12">
				<div class="ibox float-e-margins">
					<div class="ibox-title">
						<h3>当前位置： 商品管理 &raquo; 商品分类列表</h3>
					</div>
					<div class="ibox-content">
						<div class="row">
							<a class="btn btn-primary pull-right" href="__CONTROLLER__/add">添加商品分类</a>
       					</div>
						<div class="">
							<form action="__CONTROLLER__/changesort" method="post">
							<table class="table table-bordered table-striped table-hover">
								<thead>
									<tr>
										   <th>ID</th>
                                           <th>商品分类名称</th>
                                           <th>商品分类图标</th>
                                           <th>上架/下架</th>
                                           <th>是否推荐</th>
                                           <th>创建时间</th>
                                           <th>商品列表</th>
                                           <th>排序</th>
                                           <th>分类属性配置</th>
                                           <th>操作</th>
									</tr>
								</thead>
								<tbody>
									<?php
									$Goods=new \Common\Model\GoodsModel();
									$GoodsAttribute=new \Common\Model\GoodsAttributeModel();
									?>
									<foreach name="catlist" item="v">
									<?php
									$cat_id=$v['cat_id'];
									$goods_num=$Goods->where("cat_id=$cat_id and is_delete='N'")->count();
									//顶级分类
									if($v['parent_id']==0) {
									    $topCat='<span class="topCat">+</span> ';
									    $tr_class='tr_top';
									}else {
									    $topCat='';
									    $tr_class='tr_sub';
									}
									?>
                            	   <tr class="{$tr_class}">
                            	       <td>{$v.cat_id}</td>
                            	       <td style='text-align:left;padding-left:<if condition="$v.leftpin neq 0">{$v.leftpin}px</if>' >{$topCat}{$v.lefthtml}{$v.cat_name}</td>
                            		   <td>
                            		   <?php 
                            		   if($v['img'])
                            		   {
                            		   	echo '<img src="'.$v['img'].'" height="50px">';
                            		   }
                            		   ?>
                            		   </td>
                            		   <td>
                            		        <if condition='$v.is_show eq Y'>
                            					<button type="button" class="btn btn-primary btn-sm" onclick="changestatus({$v.cat_id},'N');">显示</button>
                            				<else/>
                            					<button type="button" class="btn btn-danger btn-sm" onclick="changestatus({$v.cat_id},'Y');">隐藏</button>
                            				</if>
                            			</td>
                            			<td>
                            		    	<if condition='$v.is_top eq Y'>
                            					<button type="button" class="btn btn-primary btn-sm" onclick="changetop({$v.cat_id},'N');">&nbsp;&nbsp;置顶&nbsp;&nbsp;&nbsp;</button>
                            				<else/>
                            					<button type="button" class="btn btn-danger btn-sm" onclick="changetop({$v.cat_id},'Y');">&nbsp;不置顶&nbsp;</button>
                            				</if>
                            		  	</td>
                            			<td>{$v.create_time}</td>
                            			<td><a href="__MODULE__/Goods/index/cat_id/{$v.cat_id}">查看商品列表（<?php echo $goods_num; ?>件）</a></td>
                            			<td class="has-warning"><input name="sort[{$v.cat_id}]" value="{$v.sort}" class="form-control" style="width:50px;text-align:center"/></td>
                            			<?php 
                            			//商品分类下的属性个数
                            			$attribute_num=$GoodsAttribute->getGoodsCatAttributeNum($cat_id);
                            			?>
                            			<td><a href="__MODULE__/GoodsAttribute/index/goods_cat_id/{$v.cat_id}">商品分类属性配置（{$attribute_num}类）</a></td>
                            			<td>
                            				<a href="__CONTROLLER__/edit/cat_id/{$v.cat_id}" title="修改">
                            					 <i class="fa fa-file-text-o text-danger" style="font-size:2.0rem"></i>&nbsp;
                            			    </a>
                            			    <a href="javascript:;" onclick="delcat({$v.cat_id});" title="删除">
                            					 <i class="fa fa-trash-o text-danger" style="font-size:2.0rem"></i>&nbsp;
                            				</a>
                            			</td>
                            		</tr>
                            		</foreach>
									<tr>
										<td colspan="10">
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