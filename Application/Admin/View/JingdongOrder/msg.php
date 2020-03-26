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
                    <h3>当前位置：京东管理系统 &raquo; 京东订单管理 &raquo; 查看订单详情<a class="pull-right" href="__CONTROLLER__/index">返回订单列表
                            <i class="fa fa-angle-double-right"></i></a></h3>
                </div>
            </div>
        </div>
        <div class="layui-row layui-col-space15">
            <div class="layui-col-md12">
                <div class="layui-card">
                    <div class="layui-card-body" style="overflow: hidden;">
                        <!--                    <div class="ibox-content" style="overflow: hidden;">-->
                        <div class="layui-form-item">
                            <label class="layui-form-label" style="width: 190px;">所属用户</label>
                            <div class="layui-input-block">
                                <input type="text" class="layui-input" disabled value="{$msg.user_account}"
                                       style="width: 94%;">
                            </div>
                        </div>
                        <div class="layui-form-item">
                            <label class="layui-form-label" style="width: 190px;">京东父订单号</label>
                            <div class="layui-input-block">
                                <input type="text" class="layui-input" disabled value="{$msg.info.parentid}"
                                       style="width: 94%;">
                            </div>
                        </div>
                        <div class="layui-form-item">
                            <label class="layui-form-label" style="width: 190px;">京东订单号</label>
                            <div class="layui-input-block">
                                <input type="text" class="layui-input" disabled value="{$msg.info.orderid}"
                                       style="width: 94%;">
                            </div>
                        </div>
                        <div class="layui-form-item">
                            <label class="layui-form-label" style="width: 190px;">商品ID</label>
                            <div class="layui-input-block">
                                <input type="text" class="layui-input" disabled value="{$msg.skuid}"
                                       style="width: 94%;">
                            </div>
                        </div>
                        <div class="layui-form-item">
                            <label class="layui-form-label" style="width: 190px;">商品标题</label>
                            <div class="layui-input-block">
                                <input type="text" class="layui-input" disabled value="{$msg.skuname}"
                                       style="width: 94%;">
                            </div>
                        </div>
                        <div class="layui-form-item">
                            <label class="layui-form-label" style="width: 190px;">商品数量</label>
                            <div class="layui-input-block">
                                <input type="text" class="layui-input" disabled value="{$msg.skunum}"
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
                                <input type="text" class="layui-input" disabled value="{$msg.payprice}"
                                       style="width: 94%;">
                            </div>
                        </div>
                        <div class="layui-form-item">
                            <label class="layui-form-label" style="width: 190px;">推广者获得的收入金额</label>
                            <div class="layui-input-block">
                                <input type="text" class="layui-input" disabled value="{$msg.actualfee}"
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
                                <input type="text" class="layui-input" disabled value="{$msg.commissionrate}%"
                                       style="width: 94%;">
                            </div>
                        </div>
                        <div class="layui-form-item">
                            <label class="layui-form-label" style="width: 190px;">订单创建时间</label>
                            <div class="layui-input-block">
                                <input type="text" class="layui-input" disabled
                                       value="<?php echo date('Y-m-d H:i:s', $msg['ordertime'] / 1000); ?>"
                                       style="width: 94%;">
                            </div>
                        </div>
                        <div class="layui-form-item">
                            <label class="layui-form-label" style="width: 190px;">订单结算时间</label>
                            <div class="layui-input-block">
                                <input type="text" class="layui-input" disabled
                                       value="<?php echo date('Y-m-d H:i:s', $msg['info']['finishtime'] / 1000); ?>"
                                       style="width: 94%;">
                            </div>
                        </div>
                        <div class="layui-form-item">
                            <label class="layui-form-label" style="width: 190px;">订单状态</label>
                            <div class="layui-input-block">
                                <input type="text" class="layui-input" disabled value="{$msg.statusZh}"
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