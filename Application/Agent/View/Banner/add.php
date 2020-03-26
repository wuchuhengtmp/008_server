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
<!--颜色拾取插件-->
<link rel="stylesheet" type="text/css" href="__ADMIN__/color/css/jquery.bigcolorpicker.css" />
<script type="text/javascript" src="__ADMIN__/color/js/jquery.bigcolorpicker.min.js"></script>
<!--颜色拾取插件-->
<script>
$(document).ready(function(){
	//颜色拾取
	$("#c1").bigColorpicker("c1",'#00AC2B');
	
	$(".i-checks").iCheck({checkboxClass:"icheckbox_square-green",radioClass:"iradio_square-green",})
});
</script>
</head>

<body class="gray-bg">
	<div class="wrapper wrapper-content animated fadeInRight">
		<div class="row">
			<div class="col-sm-12">
				<div class="ibox float-e-margins">
					<div class="ibox-title">
						<h3>当前位置：{$cat_name}管理 &raquo; 添加图片<a class="pull-right" href="__CONTROLLER__/index/cat_id/{$cat_id}">返回广告图列表 <i class="fa fa-angle-double-right"></i></a></h3>
					</div>
				</div>
			</div>
			<div class="row">
            <div class="col-sm-12">
                <div class="ibox float-e-margins">
                    <div class="ibox-content">
                        <form action="__ACTION__/cat_id/{$cat_id}"  class="form-horizontal" method="post" enctype="multipart/form-data">
                            <div class="form-group">
                                <label class="col-sm-2 control-label">名称</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" name="title" placeholder="">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label">上传图片</label>
                                <div class="col-sm-10">
                                    <input type="file" name="img" class="form-control">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label">图片颜色</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" id="c1" name="color"> 
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label">排序</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" name="sort"> 
                                    <span class="help-block m-b-none text-danger">数字越大越排在前</span>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label">超链接</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" name="href"> 
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label">是否显示</label>
                                <div>
                                    <div class="radio i-checks">
                                        <label>
                                        	<input type="radio" name="is_show" value="Y" checked> <i></i>是
                                        	<input type="radio" name="is_show" value="N"> <i></i>否
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label">类型</label>
                                <div>
                                    <div class="radio i-checks">
                                        <label>
                                        	<input type="radio" name="type" value="1" checked/> <i></i>网页
                                        	<input type="radio" name="type" value="2"/> <i></i>淘宝
                                        	<input type="radio" name="type" value="3"/> <i></i>京东
                                        	<input type="radio" name="type" value="4"/> <i></i>拼多多
                                        	<input type="radio" name="type" value="5"/> <i></i>支付宝
                                        	<input type="radio" name="type" value="6"/> <i></i>淘宝年货节
                                        	<input type="radio" name="type" value="7"/> <i></i>春节红包
                                        	<input type="radio" name="type" value="8"/> <i></i>新人红包
                                        	<input type="radio" name="type" value="9"/> <i></i>淘宝商品
                                        	<input type="radio" name="type" value="10"/> <i></i>拉新活动
                                        	<input type="radio" name="type" value="11"/> <i></i>0元购
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label">类型值</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" name="type_value"> 
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-sm-4 col-sm-offset-2">
                                    <button class="btn btn-primary" type="submit"><i class="fa fa-check"></i>&nbsp;添加</button>
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