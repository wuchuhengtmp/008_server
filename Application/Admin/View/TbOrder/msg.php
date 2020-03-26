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
    <link rel="stylesheet" href="__LAYUIADMIN__/layui/css/layui.css" media="all">
    <link rel="stylesheet" href="__LAYUIADMIN__/style/admin.css" media="all">
</head>

<body class="gray-bg">
<div class="layui-fluid">
    <div class="layui-row layui-col-space15">
        <div class="layui-col-md12">
            <div class="layui-card">
                <div class="ibox-title">
                    <h3>当前位置：淘宝管理系统 &raquo; 淘宝订单管理 &raquo; 查看订单详情<a class="pull-right" href="__CONTROLLER__/index">返回订单列表
                            <i class="fa fa-angle-double-right"></i></a></h3>
                </div>
            </div>
        </div>
        <div class="layui-row layui-col-space15">
            <div class="layui-col-md12">
                <div class="layui-card">
                    <div class="layui-card-body" style="overflow: hidden;">
                        <div class="layui-form-item">
                            <label class="layui-form-label" style="width: 190px;">所属用户</label>
                            <div class="layui-input-block">
                                <input type="text" class="layui-input" disabled value="{$msg.user_account}"
                                       style="width: 94%;">
                            </div>
                        </div>
                        <div class="layui-form-item">
                            <label class="layui-form-label" style="width: 190px;">淘宝父订单号</label>
                            <div class="layui-input-block">
                                <input type="text" class="layui-input" disabled value="{$msg.trade_parent_id}"
                                       style="width: 94%;">
                            </div>
                        </div>
                        <div class="layui-form-item">
                            <label class="layui-form-label" style="width: 190px;">淘宝订单号</label>
                            <div class="layui-input-block">
                                <input type="text" class="layui-input" disabled value="{$msg.trade_id}"
                                       style="width: 94%;">
                            </div>
                        </div>
                        <div class="layui-form-item">
                            <label class="layui-form-label" style="width: 190px;">商品ID</label>
                            <div class="layui-input-block">
                                <input type="text" class="layui-input" disabled value="{$msg.num_iid}"
                                       style="width: 94%;">
                            </div>
                        </div>
                        <div class="layui-form-item">
                            <label class="layui-form-label" style="width: 190px;">商品标题</label>
                            <div class="layui-input-block">
                                <input type="text" class="layui-input" disabled value="{$msg.item_title}"
                                       style="width: 94%;">
                            </div>
                        </div>
                        <div class="layui-form-item">
                            <label class="layui-form-label" style="width: 190px;">商品数量</label>
                            <div class="layui-input-block">
                                <input type="text" class="layui-input" disabled value="{$msg.item_num}"
                                       style="width: 94%;">
                            </div>
                        </div>
                        <div class="layui-form-item">
                            <label class="layui-form-label" style="width: 190px;">单价</label>
                            <div class="layui-input-block">
                                <input type="text" class="layui-input" disabled value="{$msg.price}"
                                       style="width: 94%;">
                            </div>
                        </div>
                        <div class="layui-form-item">
                            <label class="layui-form-label" style="width: 190px;">实际支付金额</label>
                            <div class="layui-input-block">
                                <input type="text" class="layui-input" disabled value="{$msg.pay_price}"
                                       style="width: 94%;">
                            </div>
                        </div>
                        <div class="layui-form-item">
                            <label class="layui-form-label" style="width: 190px;">卖家昵称</label>
                            <div class="layui-input-block">
                                <input type="text" class="layui-input" disabled value="{$msg.seller_nick}"
                                       style="width: 94%;">
                            </div>
                        </div>
                        <div class="layui-form-item">
                            <label class="layui-form-label" style="width: 190px;">卖家店铺名称</label>
                            <div class="layui-input-block">
                                <input type="text" class="layui-input" disabled value="{$msg.seller_shop_title}"
                                       style="width: 94%;">
                            </div>
                        </div>
                        <div class="layui-form-item">
                            <label class="layui-form-label" style="width: 190px;">推广者获得的收入金额</label>
                            <div class="layui-input-block">
                                <input type="text" class="layui-input" disabled value="{$msg.tb_commission}"
                                       style="width: 94%;">
                            </div>
                        </div>
                        <div class="layui-form-item">
                            <label class="layui-form-label" style="width: 190px;">平台佣金</label>
                            <div class="layui-input-block">
                                <input type="text" class="layui-input" disabled value="{$msg.commission}"
                                       style="width: 94%;">
                            </div>
                        </div>
                        <div class="layui-form-item">
                            <label class="layui-form-label" style="width: 190px;">推广者获得的分成比率</label>
                            <div class="layui-input-block">
                                <input type="text" class="layui-input" disabled value="{$msg.commission_rate}"
                                       style="width: 94%;">
                            </div>
                        </div>
                        <div class="layui-form-item">
                            <label class="layui-form-label" style="width: 190px;">淘客订单创建时间</label>
                            <div class="layui-input-block">
                                <input type="text" class="layui-input" disabled value="{$msg.create_time}"
                                       style="width: 94%;">
                            </div>
                        </div>
                        <div class="layui-form-item">
                            <label class="layui-form-label" style="width: 190px;">淘客订单结算时间</label>
                            <div class="layui-input-block">
                                <input type="text" class="layui-input" disabled value="{$msg.earning_time}"
                                       style="width: 94%;">
                            </div>
                        </div>
                        <div class="layui-form-item">
                            <label class="layui-form-label" style="width: 190px;">淘客订单状态</label>
                            <div class="layui-input-block">
                                <?php
                                $status_css = '';
                                switch ($msg['tk_status']) {
                                    //订单结算
                                    case '3':
                                        $status_str = '订单结算';
                                        $status_css = 'style="color:red"';
                                        break;
                                    //订单付款
                                    case '12':
                                        $status_str = '订单付款';
                                        break;
                                    //订单失效
                                    case '13':
                                        $status_str = '订单失效';
                                        break;
                                    //订单成功
                                    case '14':
                                        $status_str = '订单成功';
                                        break;
                                    default:
                                        $status_str = '';
                                        break;
                                }
                                ?>
                                <input type="text" class="layui-input" {$status_css} disabled value="{$status_str}"
                                       style="width: 94%;">
                            </div>
                        </div>
                        <div class="layui-form-item">
                            <label class="layui-form-label" style="width: 190px;">第三方服务来源</label>
                            <div class="layui-input-block">
                                <input type="text" class="layui-input" disabled value="{$msg.tk3rd_type}"
                                       style="width: 94%;">
                            </div>
                        </div>
                        <div class="layui-form-item">
                            <label class="layui-form-label" style="width: 190px;">第三方推广者ID</label>
                            <div class="layui-input-block">
                                <input type="text" class="layui-input" disabled value="{$msg.tk3rd_pub_id}"
                                       style="width: 94%;">
                            </div>
                        </div>
                        <div class="layui-form-item">
                            <label class="layui-form-label" style="width: 190px;">订单类型</label>
                            <div class="layui-input-block">
                                <input type="text" class="layui-input" disabled value="{$msg.order_type}"
                                       style="width: 94%;">
                            </div>
                        </div>
                        <div class="layui-form-item">
                            <label class="layui-form-label" style="width: 190px;">收入比率</label>
                            <div class="layui-input-block">
                                <input type="text" class="layui-input" disabled value="{$msg.income_rate}"
                                       style="width: 94%;">
                            </div>
                        </div>
                        <div class="layui-form-item">
                            <label class="layui-form-label" style="width: 190px;">效果预估</label>
                            <div class="layui-input-block">
                                <input type="text" class="layui-input" disabled value="{$msg.pub_share_pre_fee}"
                                       style="width: 94%;">
                            </div>
                        </div>
                        <div class="layui-form-item">
                            <label class="layui-form-label" style="width: 190px;">补贴比率</label>
                            <div class="layui-input-block">
                                <input type="text" class="layui-input" disabled value="{$msg.subsidy_rate}"
                                       style="width: 94%;">
                            </div>
                        </div>
                        <div class="layui-form-item">
                            <label class="layui-form-label" style="width: 190px;">补贴类型</label>
                            <div class="layui-input-block">
                                <?php
                                switch ($msg['subsidy_type']) {
                                    //天猫:1
                                    case '1':
                                        $subsidy_type_str = '天猫';
                                        break;
                                    //聚划算:2
                                    case '2':
                                        $subsidy_type_str = '聚划算';
                                        break;
                                    //航旅:3
                                    case '3':
                                        $subsidy_type_str = '航旅';
                                        break;
                                    //阿里云:4
                                    case '4':
                                        $subsidy_type_str = '阿里云';
                                        break;
                                    default:
                                        $subsidy_type_str = '无';
                                        break;
                                }
                                ?>
                                <input type="text" class="layui-input" disabled value="{$subsidy_type_str}"
                                       style="width: 94%;">
                            </div>
                        </div>
                        <div class="layui-form-item">
                            <label class="layui-form-label" style="width: 190px;">补贴金额</label>
                            <div class="layui-input-block">
                                <input type="text" class="layui-input" disabled value="{$msg.subsidy_fee}"
                                       style="width: 94%;">
                            </div>
                        </div>
                        <div class="layui-form-item">
                            <label class="layui-form-label" style="width: 190px;">成交平台</label>
                            <div class="layui-input-block">
                                <input type="text" class="layui-input" disabled value="{$msg['terminal_type']}"
                                       style="width: 94%;">
                            </div>
                        </div>
                        <div class="layui-form-item">
                            <label class="layui-form-label" style="width: 190px;">类目名称</label>
                            <div class="layui-input-block">
                                <input type="text" class="layui-input" disabled value="{$msg.auction_category}"
                                       style="width: 94%;">
                            </div>
                        </div>
                        <div class="layui-form-item">
                            <label class="layui-form-label" style="width: 190px;">来源媒体ID</label>
                            <div class="layui-input-block">
                                <input type="text" class="layui-input" disabled value="{$msg.site_id}"
                                       style="width: 94%;">
                            </div>
                        </div>
                        <div class="layui-form-item">
                            <label class="layui-form-label" style="width: 190px;">来源媒体名称</label>
                            <div class="layui-input-block">
                                <input type="text" class="layui-input" disabled value="{$msg.site_name}"
                                       style="width: 94%;">
                            </div>
                        </div>
                        <div class="layui-form-item">
                            <label class="layui-form-label" style="width: 190px;">广告位ID</label>
                            <div class="layui-input-block">
                                <input type="text" class="layui-input" disabled value="{$msg.adzone_id}"
                                       style="width: 94%;">
                            </div>
                        </div>
                        <div class="layui-form-item">
                            <label class="layui-form-label" style="width: 190px;">广告位名称</label>
                            <div class="layui-input-block">
                                <input type="text" class="layui-input" disabled value="{$msg.adzone_name}"
                                       style="width: 94%;">
                            </div>
                        </div>
                        <div class="layui-form-item">
                            <label class="layui-form-label" style="width: 190px;">渠道ID</label>
                            <div class="layui-input-block">
                                <input type="text" class="layui-input" disabled value="{$msg.relation_id}"
                                       style="width: 94%;">
                            </div>
                        </div>
                        <div class="layui-form-item">
                            <label class="layui-form-label" style="width: 190px;">付款金额</label>
                            <div class="layui-input-block">
                                <input type="text" class="layui-input" disabled value="{$msg.alipay_total_price}"
                                       style="width: 94%;">
                            </div>
                        </div>
                        <div class="layui-form-item">
                            <label class="layui-form-label" style="width: 190px;">佣金比率</label>
                            <div class="layui-input-block">
                                <input type="text" class="layui-input" disabled value="{$msg.total_commission_rate}"
                                       style="width: 94%;">
                            </div>
                        </div>
                        <div class="layui-form-item">
                            <label class="layui-form-label" style="width: 190px;">佣金金额</label>
                            <div class="layui-input-block">
                                <input type="text" class="layui-input" disabled value="{$msg.total_commission_fee}"
                                       style="width: 94%;">
                            </div>
                        </div>
                        <div class="layui-form-item">
                            <label class="layui-form-label" style="width: 190px;">是否结算给用户</label>
                            <div class="layui-input-block">
                                <?php
                                if ($msg['status'] == '2') {
                                    $status_str = '已结算';
                                } else {
                                    $status_str = '未结算';
                                }
                                ?>
                                <input type="text" class="layui-input" disabled value="{$status_str}"
                                       style="width: 94%;">
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