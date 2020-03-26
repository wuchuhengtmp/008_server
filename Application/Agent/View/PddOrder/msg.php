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
						<h3>当前位置：拼多多管理系统 &raquo; 拼多多订单管理 &raquo; 查看订单详情<a class="pull-right" href="__CONTROLLER__/index">返回订单列表 <i class="fa fa-angle-double-right"></i></a></h3>
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
                                <label class="col-sm-2 control-label">订单编号</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" disabled value="{$msg.order_sn}" >
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label">商品id</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" disabled value="{$msg.goods_id}" >
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label">商品名称</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" disabled value="{$msg.goods_name}" >
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label">商品缩略图</label>
                                <div class="col-sm-10">
                                    <img src="{$msg['goods_thumbnail_url']}" height="50px">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label">商品数量</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" disabled value="{$msg.goods_quantity}" >
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label">商品价格</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" disabled value="￥<?php echo $msg['goods_price']/100;?>" >
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label">订单价格</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" disabled value="￥<?php echo $msg['order_amount']/100;?>" >
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label">佣金比例</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" disabled value="<?php echo $msg['promotion_rate']/10;?>%" >
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label">平台佣金</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" disabled value="￥<?php echo $msg['promotion_amount']/100;?>" >
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label">拼多多佣金</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" disabled value="￥<?php echo $msg['pdd_commission']/100;?>" >
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label">结算批次号</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" disabled value="{$msg.batch_no}" >
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label">订单状态</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" disabled value="{$msg.order_status_desc}" >
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label">订单创建时间</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" disabled value="<?php echo date('Y-m-d H:i:s',$msg['order_create_time'])?>" >
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label">订单支付时间</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" disabled value="<?php echo date('Y-m-d H:i:s',$msg['order_pay_time'])?>" >
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label">订单成团时间</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" disabled value="<?php echo date('Y-m-d H:i:s',$msg['order_group_success_time'])?>" >
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label">订单确认收货时间</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" disabled value="<?php echo date('Y-m-d H:i:s',$msg['order_receive_time'])?>" >
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label">订单审核时间</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" disabled value="<?php echo date('Y-m-d H:i:s',$msg['order_verify_time'])?>" >
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label">订单结算时间</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" disabled value="<?php echo date('Y-m-d H:i:s',$msg['order_settle_time'])?>" >
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label">订单最后更新时间</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" disabled value="<?php echo date('Y-m-d H:i:s',$msg['order_modify_at'])?>" >
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label">订单来源</label>
                                <div class="col-sm-10">
                                <?php 
                                switch ($msg['match_channel']) {
                                    case '0':
                                        $match_channel_str='关联';
                                        break;
                                    case '5':
                                        $match_channel_str='直接下单页RPC请求';
                                        break;
                                    default:
                                        $match_channel_str='无';
                                        break;
                                }
                                ?>
                                    <input type="text" class="form-control" disabled value="{$match_channel_str}" >
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label">订单类型</label>
                                <div class="col-sm-10">
                                <?php 
                                switch ($msg['type']) {
                                    case '0':
                                        $type_str='领券页面';
                                        break;
                                    case '1':
                                        $type_str=' 红包页';
                                        break;
                                    case '2':
                                        $type_str=' 领券页';
                                        break;
                                    case '3':
                                        $type_str=' 题页';
                                        break;
                                    default:
                                        $type_str='无';
                                        break;
                                }
                                ?>
                                    <input type="text" class="form-control" disabled value="{$type_str}" >
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label">成团编号</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" disabled value="{$msg.group_id}" >
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label">多多客工具ID</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" disabled value="{$msg.auth_duo_id}" >
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label">招商多多客id</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" disabled value="{$msg.zs_duo_id}" >
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label">自定义参数</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" disabled value="{$msg.custom_parameters}" >
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label">CPS_Sign</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" disabled value="{$msg.cps_sign}" >
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label">链接最后一次生产时间</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" disabled value="<?php echo date('Y-m-d H:i:s',$msg['url_last_generate_time'])?>" >
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label">打点时间</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" disabled value="<?php echo date('Y-m-d H:i:s',$msg['point_time'])?>" >
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label">售后状态</label>
                                <div class="col-sm-10">
                                <?php 
                                switch ($msg['return_status']) {
                                    case 0:
                                        $return_status_str='无';
                                        break;
                                    case 1:
                                        $return_status_str='售后中';
                                        break;
                                    case 2:
                                        $return_status_str='售后完成';
                                        break;
                                    default:
                                        $return_status_str='';
                                        break;
                                }
                                ?>
                                    <input type="text" class="form-control" disabled value="{$return_status_str}" >
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label">推广位ID</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" disabled value="{$msg.pid}" >
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