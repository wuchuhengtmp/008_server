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
$(document).ready(function(){
	$(".i-checks").iCheck({
		checkboxClass:"icheckbox_square-green",
		radioClass:"iradio_square-green",
	});
	
	//取消全选
	$('#unselect').click(function(){
		$("input:checkbox").removeAttr("checked");
		$(".i-checks").iCheck({
			checkboxClass:"icheckbox_square-green",
			radioClass:"iradio_square-green",
		});
	});
	//全选
	$('#selectall').click(function(){
		$("input:checkbox").prop("checked","checked");
		$(".i-checks").iCheck({
			checkboxClass:"icheckbox_square-green",
			radioClass:"iradio_square-green",
		});
	});

	//批量转移
	$('#transfer').click(function(){
		var all_id='';
		$(":checkbox").each(function(){
			if($(this).prop("checked")) 
			{
				all_id+=$(this).val()+',';
			}
		});
		if(all_id!='') {
			var cat_id=$('#cat_id').val();
			swal({
				title:"确定转移这些商品吗？",
				text:"",
				type:"warning",
				showCancelButton:true,
				cancelButtonText:"取消",
				confirmButtonColor:"#DD6B55",
				confirmButtonText:"转移",
				closeOnConfirm:false
			},function(){
				$.ajax({
					type:"POST",
					url:"/dmooo.php/Goods/transfer",
					dataType:"html",
					data:"all_id="+all_id+'&cat_id='+cat_id,
					success:function(msg)
					{
						if(msg=='1')
						{
							swal({
								title:"批量转移成功！",
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
		}else {
			swal({title:"",text:"请选择需要转移的商品！"})
			return false;
		}
	});
	
});

function changeshow(id,status)
{
	if(id!='')
	{
		$.ajax({
			type:"POST",
			url:'/dmooo.php/Goods/changeshow',
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
	if(id!='')
	{
		$.ajax({
			type:"POST",
			url:'/dmooo.php/Goods/changetop',
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

function changesale(id,status)
{
	if(id!='')
	{
		$.ajax({
			type:"POST",
			url:'/dmooo.php/Goods/changesale',
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

function del(id)
{
	if(id!='') {
		swal({
			title:"删除后的商品放到商品回收站，确定要删除该商品吗？",
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
				url:'/dmooo.php/Goods/del',
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
						<h3>当前位置： 商品管理 &raquo; {$cname}<a class="pull-right" href="__MODULE__/GoodsCat/index">返回商品分类列表 <i class="fa fa-angle-double-right"></i></a></h3>
					</div>
					<div class="ibox-content">
						<form action="__ACTION__/cat_id/{$cat_id}" method="get" role="form" class="form-inline pull-left">
                        	<input type="hidden" name="p" value="1"> 
                        	商品名称：<input type="text" placeholder="" name="search" class="form-control">
                        	<button class="btn btn-primary" type="submit">查询</button>
                        </form>
						<a class="btn btn-primary pull-right" href="__CONTROLLER__/add/cat_id/{$cat_id}">添加商品</a>
						<div class="">
							<form action="__CONTROLLER__/changesort/cat_id/{$cat_id}" method="post">
							<table class="table table-bordered table-striped table-hover">
								<thead>
									<tr>
										<th></th>
                                        <th>ID</th>
                                        <th>商品图片</th>
                                        <th width="23%">商品名称</th>
                                        <th>价格</th>
                                        <th>上架/下架</th>
                                        <th>是否推荐</th>
                                        <th>是否特价</th>
                                        <th>实际销量</th>
                                        <th>SKU配置</th>
                                        <th>商品相册</th>
                                        <th>排序</th>
                                        <th>查看评论</th>
                                        <th>操作</th>
									</tr>
								</thead>
								<tbody>
									<?php 
                                    $GoodsImg=new \Common\Model\GoodsImgModel();
                                    $GoodsComment=new \Common\Model\GoodsCommentModel();
                                    ?>
									<foreach name="goodslist" item="g">
                                	  <tr>
                                	      <td style="text-align: center"><input class="checkbox i-checks" type="checkbox" id="allid[]" value="{$g['goods_id']}"></td>
                                	      <td>{$g['goods_id']}</td>
                                	      <td>
                                	      <?php 
                                	      if($g['img']) {
                                	      	echo '<img src="'.$g['img'].'" height="50px">';
                                	      }
                                	      ?>
                                	      </td>
                                	      <td>{$g['goods_name']}</td>
                                	      <td>￥<?php echo $g['price']/100;?></td>
                                	      <td>
                                	        <if condition='$g[is_show] eq Y'>
                                	            <button type="button" class="btn btn-primary btn-sm" onclick="changeshow({$g.goods_id},'N');">上架</button>
                                			<else/>
                                				<button type="button" class="btn btn-danger btn-sm" onclick="changeshow({$g.goods_id},'Y');">下架</button>
                                			</if>
                                		  </td>
                                		  <td>
                                		    <if condition='$g.is_top eq Y'>
                                				<button type="button" class="btn btn-danger btn-sm" onclick="changetop({$g.goods_id},'N');">置顶</button>
                                			<else/>
                                				<button type="button" class="btn btn-primary btn-sm" onclick="changetop({$g.goods_id},'Y');">不置顶</button>
                                			</if>
                                		  </td>
                                		  <td>
                                		    <if condition='$g.is_sale eq Y'>
                                				<button type="button" class="btn btn-danger btn-sm" onclick="changesale({$g.goods_id},'N');">特价</button>
                                			<else/>
                                				<button type="button" class="btn btn-primary btn-sm" onclick="changesale({$g.goods_id},'Y');">否</button>
                                			</if>
                                		  </td>
                                		  <td>{$g['sales_volume']}</td>
                                		  <td><a href="__MODULE__/GoodsSku/set/goods_id/{$g['goods_id']}">SKU配置</a></td>
                                		  <?php 
                                		  $img_num=$GoodsImg->getGoodsImgNum($g['goods_id']);
                                		  ?>
                                		  <td><a href="__MODULE__/GoodsImg/index/goods_id/{$g.goods_id}/cat_id/{$cat_id}">查看相册({$img_num})</a></td>
                                		  <td class="has-warning"><input name="sort[{$g.goods_id}]" value="{$g.sort}" class="form-control" style="width:50px;text-align:center"/></td>
                                		  <?php 
                                		  //评论数量
                                		  $goods_id=$g['goods_id'];
                                		  $comment_num=$GoodsComment->where("goods_id='$goods_id'")->count();
                                		  ?>
                                		  <td><a href="__MODULE__/GoodsComment/index/goods_id/{$g.goods_id}">查看评论({$comment_num})</a></td>
                                		  <td>
                                		      <a href="__CONTROLLER__/edit/goods_id/{$g.goods_id}" title="修改">
                                                  <i class="fa fa-file-text-o text-danger" style="font-size:2.0rem"></i>&nbsp;
                                              </a>
                                              <a href="javascript:;" onclick="del({$g.goods_id});" title="删除">
                                				  <i class="fa fa-trash-o text-danger" style="font-size:2.0rem"></i>&nbsp;
                                			  </a>
                                		  </td>
                                	  </tr>
                                	  </foreach>
									<tr>
										<td colspan="14">
	        								<input type="submit" class="btn btn-primary" value="统一排序">
	        								<input type="button" class="btn btn-primary" id="unselect" value="取消选择">
	        								<input type="button" class="btn btn-primary" id="selectall" value="全选">
	        								<div class="form-inline pull-right">
	        									<input type="button" class="btn btn-primary" value="批量转移到=>">
	        									<select class="form-control" id="cat_id">
              										<option value="0">-请选择商品分类-</option>
                                                      <?php
                                                      foreach ($catlist as $v) {
                                                      	if($v['cat_id']!=$cat_id) {
                                                      		echo '<option value="'.$v['cat_id'].'" style="margin-left:55px;">'.$v['lefthtml'].''.$v['cat_name'].'</option>';
                                                        }
                                                      }
                                                      ?>
              									</select>
              									<input type="button" class="btn btn-primary" id="transfer" value="确定转移">
              								</div>
		  								</td>
	   								</tr>
								</tbody>
							</table>
							</form>
							<div class="pages">{$page}</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</body>
</html>