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
<script src="__ADMIN_JS__/bootstrap.min.js?v=3.3.6"></script>
<script src="__ADMIN_JS__/plugins/iCheck/icheck.min.js"></script>

<!-- ueditor -->
<link rel="stylesheet" type="text/css" href="__PUBLIC__/ueditor/themes/default/css/ueditor.css" />
<script type="text/javascript" charset="utf-8" src="__PUBLIC__/ueditor/ueditor.config.js"></script>
<script type="text/javascript" charset="utf-8" src="__PUBLIC__/ueditor/ueditor.all.min.js"> </script>
<script type="text/javascript" charset="utf-8" src="__PUBLIC__/ueditor/lang/zh-cn/zh-cn.js"></script>
<!-- ueditor -->

<script>
//实例化编辑器
var ue = UE.getEditor('editor');

$(document).ready(function(){
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
						<h3>当前位置：黑卡管理 &raquo; 添加商品<a class="pull-right" href="__CONTROLLER__/index">返回上一页 <i class="fa fa-angle-double-right"></i></a></h3>
					</div>
				</div>
			</div>
			<div class="row">
            <div class="col-sm-12">
                <div class="ibox float-e-margins">
                    <div class="ibox-content">
                    <ul class="nav nav-tabs">
                        <li class="active">
                        	<a data-toggle="tab" href="#tab-1" aria-expanded="true">商品基本信息</a>
                        </li>
                    </ul>
                    <form action="__ACTION__"  class="form-horizontal" method="post" enctype="multipart/form-data">
                       
      				<div class="tab-content">
      					<!-- 商品基本信息  -->
      					<div id="tab-1" class="tab-pane active" style="padding-top: 10px">
                            <div class="form-group">
                                <label class="col-sm-2 control-label">商品名称</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" name="title" value="" placeholder="">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label">简单描述</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" name="sub_title">
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-sm-2 control-label">跳转链接</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" name="url">
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-sm-2 control-label">logo</label>
                                <div class="col-sm-10">
                                    <input type="file" name="logo" class="form-control">
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
                                <label class="col-sm-2 control-label">所属商品分类</label>
                                <div class="col-sm-10">
                                    <select class="form-control m-b" name="cate_id">
                                        <option value="">请选择</option>
                                    <?php
                                    foreach ($catlist as $v) {
                                        $select='';
                                        echo '<option value="'.$v['id'].'" style="margin-left:55px;" '.$select.'>'.$v['category_name'].'</option>';
                                    }
                                    ?>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label">上架/下架</label>
                                <div>
                                    <div class="radio i-checks">
                                        <label>
                                        	<input type="radio" name="status" value="Y" checked> <i></i>上架
                                        	<input type="radio" name="status" value="N"> <i></i>下架
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label">是否特权商品</label>
                                <div>
                                    <div class="radio i-checks">
                                        <label>
                                        	<input type="radio" name="tequan" value="Y"> <i></i>是
                                        	<input type="radio" name="tequan" value="N" checked> <i></i>否
                                        </label>
                                    </div>
                                </div>
                            </div>

                        </div>
                        <!-- 商品基本信息  -->
                        
                        <div class="form-group">
                                <div class="col-sm-4 col-sm-offset-2">
                                    <button class="btn btn-primary" type="submit"><i class="fa fa-check"></i>&nbsp;添加商品</button>
                                    <button class="btn btn-white" type="reset"><i class="fa fa-refresh"></i>&nbsp;重置</button>
                                </div>
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