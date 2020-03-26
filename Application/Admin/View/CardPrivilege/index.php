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
					url:"/dmooo.php/CardPrivilege/transfer",
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
			url:'/dmooo.php/CardPrivilege/changeshow',
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
			url:'/dmooo.php/CardPrivilege/changetop',
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
			url:'/dmooo.php/CardPrivilege/changesale',
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
			title:"确定要删除该商品吗？",
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
				url:'/dmooo.php/CardPrivilege/del',
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
						<h3>当前位置： 黑卡管理 &raquo; {$cname}<a class="pull-right" href="__MODULE__/CardPrivilege/index">返回黑卡列表 <i class="fa fa-angle-double-right"></i></a></h3>
					</div>
					<div class="ibox-content">
						<form action="__ACTION__" method="get" role="form" class="form-inline pull-left">
                            分类
                            <select name="cate_id" class="form-control">
                                <option value="">请选择</option>
                                <foreach name="catlist" item="c">
                                    <option value="{$c['id']}" <?php if($cate_id==$c['id']){echo "selected";}?>>{$c['category_name']}</option>
                                </foreach>
                            </select>
                        	<input type="hidden" name="p" value="1"> 
                        	商品名称：<input type="text" placeholder="" name="search" class="form-control">

                        	<button class="btn btn-primary" type="submit">查询</button>
                        </form>
						<a class="btn btn-primary pull-right" href="__CONTROLLER__/add">添加商品</a>
						<div class="">
							<form action="__CONTROLLER__/changesort" method="post">
							<table class="table table-bordered table-striped table-hover">
								<thead>
									<tr>
										<th></th>
                                        <th>ID</th>
                                        <th>logo</th>
                                        <th width="23%">商品名称</th>
                                        <th>副标题</th>
                                        <th>分类</th>
                                        <th>是否特权</th>
                                        <th>是否显示</th>
                                        <th>排序</th>
                                        <th>操作</th>
									</tr>
								</thead>
								<tbody>
									<foreach name="goodslist" item="g">
                                	  <tr>
                                	      <td style="text-align: center"><input class="checkbox i-checks" type="checkbox" id="allid[]" value="{$g['id']}"></td>
                                	      <td>{$g['id']}</td>
                                	      <td>
                                	      <?php 
                                	      if($g['logo']) {
                                	      	echo '<img src="'.$g['logo'].'" height="50px">';
                                	      }
                                	      ?>
                                	      </td>
                                	      <td>{$g['title']}</td>
                                	      <td>{$g['sub_title']}</td>
                                	      <td>
                                                {$g['cate_name']}
                                		  </td>
                                		  <td>
                                		    <if condition="$g.tequan eq 'Y'">
                                				<button type="button" class="btn btn-danger btn-sm" onclick="changetop({$g.id},'N');">特权</button>
                                			<else/>
                                				<button type="button" class="btn btn-primary btn-sm" onclick="changetop({$g.id},'Y');">不特权</button>
                                			</if>
                                		  </td>
                                		  <td>
                                		    <if condition='$g.status eq Y'>
                                				<button type="button" class="btn btn-danger btn-sm" onclick="changesale({$g.id},'N');">是</button>
                                			<else/>
                                				<button type="button" class="btn btn-primary btn-sm" onclick="changesale({$g.id},'Y');">否</button>
                                			</if>
                                		  </td>
                                          <td class="has-warning"><input name="sort[{$g['id']}]" value="{$g['sort']}" class="form-control" style="width:50px;text-align:center"/></td>
                                		  <td>
                                		      <a href="__CONTROLLER__/edit/id/{$g.id}" title="修改">
                                                  <i class="fa fa-file-text-o text-danger" style="font-size:2.0rem"></i>&nbsp;
                                              </a>
                                              <a href="javascript:;" onclick="del({$g.id});" title="删除">
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

                                                      		echo '<option value="'.$v['id'].'" style="margin-left:55px;">'.$v['category_name'].'</option>';

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