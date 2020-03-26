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
						<h3>当前位置：淘宝管理系统 &raquo; 淘宝订单管理 &raquo; 查看订单详情<a class="pull-right" href="__CONTROLLER__/index">返回订单列表 <i class="fa fa-angle-double-right"></i></a></h3>
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
                                <label class="col-sm-2 control-label">淘宝父订单号</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" disabled value="{$msg.trade_parent_id}" >
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label">淘宝订单号</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" disabled value="{$msg.trade_id}" >
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label">商品ID</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" disabled value="{$msg.num_iid}" >
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label">商品标题</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" disabled value="{$msg.item_title}" >
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label">商品数量</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" disabled value="{$msg.item_num}" >
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
                                    <input type="text" class="form-control" disabled value="{$msg.pay_price}" >
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label">卖家昵称</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" disabled value="{$msg.seller_nick}" >
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label">卖家店铺名称</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" disabled value="{$msg.seller_shop_title}" >
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label">推广者获得的收入金额</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" disabled value="{$msg.tb_commission}" >
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
                                    <input type="text" class="form-control" disabled value="{$msg.commission_rate}" >
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label">淘客订单创建时间</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" disabled value="{$msg.create_time}" >
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label">淘客订单结算时间</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" disabled value="{$msg.earning_time}" >
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label">淘客订单状态</label>
                                <div class="col-sm-10">
                                <?php 
                                $status_css='';
                                switch ($msg['tk_status']) {
                                    //订单结算
                                    case '3':
                                        $status_str='订单结算';
                                        $status_css='style="color:red"';
                                        break;
                                    //订单付款
                                    case '12':
                                        $status_str='订单付款';
                                        break;
                                    //订单失效
                                    case '13':
                                        $status_str='订单失效';
                                        break;
                                    //订单成功
                                    case '14':
                                        $status_str='订单成功';
                                        break;
                                    default:
                                        $status_str='';
                                        break;
                                }
                                ?>
                                    <input type="text" class="form-control" {$status_css} disabled value="{$status_str}" >
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label">第三方服务来源</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" disabled value="{$msg.tk3rd_type}" >
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label">第三方推广者ID</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" disabled value="{$msg.tk3rd_pub_id}" >
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label">订单类型</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" disabled value="{$msg.order_type}" >
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label">收入比率</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" disabled value="{$msg.income_rate}" >
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label">效果预估</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" disabled value="{$msg.pub_share_pre_fee}" >
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label">补贴比率</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" disabled value="{$msg.subsidy_rate}" >
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label">补贴类型</label>
                                <div class="col-sm-10">
                                <?php 
                                switch ($msg['subsidy_type']){
                                    //天猫:1
                                    case '1':
                                        $subsidy_type_str='天猫';
                                        break;
                                    //聚划算:2
                                    case '2':
                                        $subsidy_type_str='聚划算';
                                        break;
                                    //航旅:3
                                    case '3':
                                        $subsidy_type_str='航旅';
                                        break;
                                    //阿里云:4
                                    case '4':
                                        $subsidy_type_str='阿里云';
                                        break;
                                    default:
                                        $subsidy_type_str='无';
                                        break;
                                }
                                ?>
                                    <input type="text" class="form-control" disabled value="{$subsidy_type_str}" >
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label">补贴金额</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" disabled value="{$msg.subsidy_fee}" >
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label">成交平台</label>
                                <div class="col-sm-10">
                                <?php 
                                switch ($msg['terminal_type'])
                                {
                                    //PC
                                    case '1':
                                        $terminal_type_str='PC';
                                        break;
                                    //无线
                                    case '2':
                                        $terminal_type_str='无线';
                                        break;
                                    default:
                                        $terminal_type_str='';
                                        break;
                                }
                                ?>
                                    <input type="text" class="form-control" disabled value="{$terminal_type_str}" >
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label">类目名称</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" disabled value="{$msg.auction_category}" >
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label">来源媒体ID</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" disabled value="{$msg.site_id}" >
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label">来源媒体名称</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" disabled value="{$msg.site_name}" >
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label">广告位ID</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" disabled value="{$msg.adzone_id}" >
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label">广告位名称</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" disabled value="{$msg.adzone_name}" >
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label">渠道ID</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" disabled value="{$msg.relation_id}" >
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label">付款金额</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" disabled value="{$msg.alipay_total_price}" >
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label">佣金比率</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" disabled value="{$msg.total_commission_rate}" >
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label">佣金金额</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" disabled value="{$msg.total_commission_fee}" >
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