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

<script>
$(document).ready(function(){
	$(".i-checks").iCheck({checkboxClass:"icheckbox_square-green",radioClass:"iradio_square-green",})
});

function deloldimg(id)
{
	if(id!=''){
		swal({
			title:"确定删除原商品分类属性值配图吗？",
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
				url:'/dmooo.php/GoodsAttributeValue/deloldimg',
				dataType:"html",
				data:"goods_attribute_value_id="+id,
				success:function(msg)
				{
				    if(msg=='1') {
						swal({
							title:"删除原商品分类属性值配图成功！",
							text:"",
							type:"success"
						},function(){location.reload();})
					}else {
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
						<h3>当前位置：商城系统 &raquo; 商品分类属性管理 &raquo; 编辑商品分类属性值<a class="pull-right" href="__CONTROLLER__/index/goods_attribute_id/{$msg.goods_attribute_id}">返回上一页 <i class="fa fa-angle-double-right"></i></a></h3>
					</div>
				</div>
			</div>
			<div class="row">
            <div class="col-sm-12">
                <div class="ibox float-e-margins">
                    <div class="ibox-content">
                        <form action="__ACTION__/goods_attribute_value_id/{$msg.goods_attribute_value_id}"  class="form-horizontal" method="post" enctype="multipart/form-data">
                        <input type="hidden" name="oldimg" value="{$msg['img']}">
                            <div class="form-group">
                                <label class="col-sm-2 control-label">属性值名称</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" name="name" value="{$msg['name']}" placeholder="">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label">原属性值配图</label>
                                <div class="col-sm-10">
                                    <?php 
                                    if($msg['img']){
                                        echo '<img src="'.$msg['img'].'" height="100"/>
                                        <button class="btn btn-primary" type="button" onclick="deloldimg('.$msg['goods_attribute_value_id'].')">删除原配图</button>';
                                    }else {
                                        echo '暂无';
                                    } ?>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label">属性值配图</label>
                                <div class="col-sm-10">
                                    <input type="file" name="img" class="form-control">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label">排序</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" name="sort" value="{$msg['sort']}"> 
                                    <span class="help-block m-b-none text-danger">数字越大越排在前</span>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label">是否显示</label>
                                <div>
                                    <div class="radio i-checks">
                                        <label>
                                        	<input type="radio" name="is_show" value="Y" <?php if($msg['is_show']=='Y') echo 'checked'; ?> > <i></i>是
                                        	<input type="radio" name="is_show" value="N" <?php if($msg['is_show']=='N') echo 'checked'; ?> > <i></i>否
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-sm-4 col-sm-offset-2">
                                    <button class="btn btn-primary" type="submit"><i class="fa fa-check"></i>&nbsp;编辑商品分类属性值</button>
                                    <button class="btn btn-white" type="reset"><i class="fa fa-refresh"></i>&nbsp;重置</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
		</div>
	</div>
</body>
</html>