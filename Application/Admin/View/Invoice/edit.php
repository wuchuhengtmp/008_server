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
						<h3>当前位置：商城系统 &raquo; 发票信息管理 &raquo; 编辑发票<a class="pull-right" href="__CONTROLLER__/index">返回上一页  <i class="fa fa-angle-double-right"></i></a></h3>
					</div>
				</div>
			</div>
			<div class="row">
            <div class="col-sm-12">
                <div class="ibox float-e-margins">
                    <div class="ibox-content">
                        <form action="__ACTION__/invoice_id/{$msg.invoice_id}"  class="form-horizontal" method="post" enctype="multipart/form-data">
                            <div class="form-group">
                                <label class="col-sm-2 control-label">发票类型</label>
                                <div>
                                    <div class="radio i-checks">
                                        <label>
                                        	<input type="radio" name="type" value="1" <?php if($msg['type']=='1'){echo 'checked';}?> > <i></i>企业
                                        	<input type="radio" name="type" value="2" <?php if($msg['type']=='2'){echo 'checked';}?> > <i></i>个人
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label">所属用户</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" name="user_account" value="{$msg.user_account}" placeholder="必填，请填写用户名/手机号码/邮箱">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label">发票抬头</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" name="purchaser" value="{$msg.purchaser}" placeholder="必填，请输入公司名称或个人姓名">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label">纳税人识别号</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" name="taxpayer_id" value="{$msg.taxpayer_id}" placeholder="请输入企业纳税人识别号">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label">开户行</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" name="bank" value="{$msg.bank}" placeholder="请填写开户行名称">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label">账号</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" name="account" value="{$msg.account}" placeholder="请填写账号">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label">联系电话</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" name="contact" value="{$msg.contact}" placeholder="请填写联系电话">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label">收件地址</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" name="address" value="{$msg.address}" placeholder="请填写收件地址">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label">收件人</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" name="linkman" value="{$msg.linkman}" placeholder="请填写收件人">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label">是否默认</label>
                                <div>
                                    <div class="radio i-checks">
                                        <label>
                                        	<input type="radio" name="is_default" value="Y" <?php if($msg['is_default']=='Y'){echo 'checked';}?> > <i></i>是
                                        	<input type="radio" name="is_default" value="N" <?php if($msg['is_default']=='N'){echo 'checked';}?> > <i></i>否
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-sm-4 col-sm-offset-2">
                                    <button class="btn btn-primary" type="submit"><i class="fa fa-check"></i>&nbsp;编辑发票</button>
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