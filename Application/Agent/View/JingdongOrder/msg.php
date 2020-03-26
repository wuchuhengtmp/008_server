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
</head>

<body class="gray-bg">
	<div class="wrapper wrapper-content animated fadeInRight">
		<div class="row">
			<div class="col-sm-12">
				<div class="ibox float-e-margins">
					<div class="ibox-title">
						<h3>当前位置：京东管理系统 &raquo; 京东订单管理 &raquo; 查看订单详情<a class="pull-right" href="__CONTROLLER__/index">返回订单列表 <i class="fa fa-angle-double-right"></i></a></h3>
					</div>
				</div>
			</div>
			<div class="row">
            <div class="col-sm-12">
                <div class="ibox float-e-margins">
                    <div class="ibox-content" style="overflow: hidden;">
                            <div class="form-group">
                                <label class="col-sm-2 control-label">所属用户</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" disabled value="{$msg.user_account}" >
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label">京东父订单号</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" disabled value="{$msg.info.parentid}" >
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label">京东订单号</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" disabled value="{$msg.info.orderid}" >
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label">商品ID</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" disabled value="{$msg.skuid}" >
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label">商品标题</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" disabled value="{$msg.skuname}" >
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label">商品数量</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" disabled value="{$msg.skunum}" >
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label">单价</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" disabled value="{$msg.price}" >
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label">实际支付金额</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" disabled value="{$msg.payprice}" >
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label">推广者获得的收入金额</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" disabled value="{$msg.actualfee}" >
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label">平台佣金</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" disabled value="{$msg.commission}" >
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label">推广者获得的分成比率</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" disabled value="{$msg.commissionrate}%" >
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label">订单创建时间</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" disabled value="<?php echo date('Y-m-d H:i:s',$msg['ordertime']/1000); ?>" >
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label">订单结算时间</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" disabled value="<?php echo date('Y-m-d H:i:s',$msg['info']['finishtime']/1000); ?>" >
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label">订单状态</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" disabled value="{$msg.statusZh}" >
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label">是否结算给用户</label>
                                <div class="col-sm-10">
                                <?php 
                                if($msg['status']=='2') {
                                    $status_str='已结算';
                                }else {
                                    $status_str='未结算';
                                }
                                ?>
                                    <input type="text" class="form-control" disabled value="{$status_str}" >
                                </div>
                            </div>
                    </div>
                </div>
            </div>
        </div>
		</div>
	</div>
</body>
</html>