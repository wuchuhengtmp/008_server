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
<script src="__ADMIN_JS__/plugins/layer/laydate/laydate.js"></script>
<script>
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
						<h3>当前位置：会员管理 &raquo; 会员列表 &raquo; 编辑会员详情<a class="pull-right" href="__MODULE__/User/index/group_id/{$group_id}">返回上一页 <i class="fa fa-angle-double-right"></i></a></h3>
					</div>
				</div>
			</div>
			<div class="row">
            <div class="col-sm-12">
                <div class="ibox float-e-margins">
                    <div class="ibox-content">
                        <form action="__ACTION__/uid/{$msg.user_id}"  class="form-horizontal" method="post" enctype="multipart/form-data">
                            <div class="form-group">
                                <label class="col-sm-2 control-label">昵称</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" name="nickname" value="{$msg['nickname']}" placeholder="">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label">头像</label>
                                <div class="col-sm-10">
                                    <?php 
                                    if(!empty($msg['avatar'])) {
                                        echo '<img src="'.$msg['avatar'].'" height="100px">';
                                    }else {
                                        echo '暂无';
                                    }
                                    ?>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label">上传新头像</label>
                                <div class="col-sm-10">
                                    <input type="file" name="img" class="form-control">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label">真实姓名</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" name="truename" value="{$msg['truename']}"> 
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label">性别</label>
                                <div>
                                    <div class="radio i-checks">
                                        <label>
                                        	<input type="radio" name="sex" value="1" <?php if($msg['sex']=='1'){echo 'checked';} ?> /> <i></i>男
                                        	<input type="radio" name="sex" value="2" <?php if($msg['sex']=='2'){echo 'checked';} ?> /> <i></i>女
                                        	<input type="radio" name="sex" value="3" <?php if($msg['sex']=='3'){echo 'checked';} ?> /> <i></i>保密
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label">身高</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" name="height" value="{$msg['height']}"> 
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label">体重</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" name="weight" value="{$msg['weight']}"> 
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label">血型</label>
                                <div class="col-sm-10">
                                    <select class="form-control m-b" name="blood">
           								<option value="">请选择血型</option>
           								<option value="1" <?php if($msg['blood']=='1'){echo 'selected';}?> >A型</option>
           								<option value="2" <?php if($msg['blood']=='2'){echo 'selected';}?> >B型</option>
           								<option value="3" <?php if($msg['blood']=='3'){echo 'selected';}?> >AB型</option>
           								<option value="4" <?php if($msg['blood']=='4'){echo 'selected';}?> >0型</option>
           								<option value="5" <?php if($msg['blood']=='5'){echo 'selected';}?> >其它</option>
        	 						</select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label">出生日期</label>
                                <div class="col-sm-10">
                                    <input class="form-control layer-date" name="birthday" placeholder="" onclick="laydate({istime: true, format: 'YYYY-MM-DD'})">
                                    <span class="help-block m-b-none text-danger">格式：1990-09-19</span>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label">QQ</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" name="qq" value="{$msg['qq']}"> 
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label">微信</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" name="weixin" value="{$msg['weixin']}"> 
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label">详细地址</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" name="detail_address" value="{$msg['detail_address']}"> 
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label">个性签名</label>
                                <div class="col-sm-10">
                                    <textarea name="signature" placeholder="" class="form-control" style="height:100px;">{$msg.signature}</textarea> 
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-sm-4 col-sm-offset-2">
                                    <button class="btn btn-primary" type="submit"><i class="fa fa-check"></i>&nbsp;确认修改</button>
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