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
                    <h3>当前位置：唯品会管理系统 &raquo; 唯品会订单管理 &raquo; 查看订单详情<a class="pull-right" href="__CONTROLLER__/index">返回订单列表 <i class="fa fa-angle-double-right"></i></a></h3>
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
                                <input type="text" class="form-control" disabled value="{$msg.ordersn}" >
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 control-label">商品id</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" disabled value="{$msg.goodsid}" >
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 control-label">商品名称</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" disabled value="{$msg.goodsname}" >
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 control-label">商品缩略图</label>
                            <div class="col-sm-10">
                                <img src="{$msg['goodsthumb']}" height="50px">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 control-label">商品数量</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" disabled value="{$msg.goodscount}" >
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 control-label">商品价格</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" disabled value="￥{$msg.commissiontotalcost}" >
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 control-label">佣金比例</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" disabled value="{$msg.commissionrate}%" >
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 control-label">平台佣金</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" disabled value="￥{$msg.commission}" >
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 control-label">唯品会佣金</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" disabled value="￥{$msg.vipcommission}" >
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 control-label">佣金编码</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" disabled value="{$msg.commcode}" >
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 control-label">佣金方案名称</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" disabled value="{$msg.commname}" >
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 control-label">订单状态</label>
                            <div class="col-sm-10">
                                <?php
                                switch ($msg['vipstatus']) {
                                    case 0:
                                        $vipStatus='不合格';
                                        break;
                                    case 1:
                                        $vipStatus='待定';
                                        break;
                                    case 2:
                                        $vipStatus='已完结';
                                        break;
                                    default:
                                        $vipStatus='无';
                                        break;
                                }
                                ?>
                                <input type="text" class="form-control" disabled value="{$vipStatus}" >
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 control-label">订单结算状态</label>
                            <div class="col-sm-10">
                                <?php
                                switch ($msg['settled']) {
                                    case 0:
                                        $settled='未结算';
                                        break;
                                    case 1:
                                        $settled='已结算';
                                        break;
                                    default:
                                        $settled='无';
                                        break;
                                }
                                ?>
                                <input type="text" class="form-control" disabled value="{$settled}" >
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 control-label">订单子状态：流转状态</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" disabled value="{$msg.ordersubstatusname}" >
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 control-label">新老客</label>
                            <div class="col-sm-10">
                                <?php
                                switch ($msg['newcustomer']) {
                                    case 0:
                                        $newCustomer='待定';
                                        break;
                                    case 1:
                                        $newCustomer='新客';
                                        break;
                                    case 2:
                                        $newCustomer='老客';
                                        break;
                                    default:
                                        $newCustomer='无';
                                        break;
                                }
                                ?>
                                <input type="text" class="form-control" disabled value="{$newCustomer}" >
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 control-label">是否自推自买</label>
                            <div class="col-sm-10">
                                <?php
                                switch ($msg['selfbuy']) {
                                    case 0:
                                        $selfBuy='否';
                                        break;
                                    case 1:
                                        $selfBuy='是';
                                        break;
                                    default:
                                        $selfBuy='无';
                                        break;
                                }
                                ?>
                                <input type="text" class="form-control" disabled value="{$selfBuy}" >
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 control-label">渠道标识</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" disabled value="{$msg.channeltag}" >
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 control-label">订单来源</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" disabled value="{$msg.ordersource}" >
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 control-label">是否预付订单</label>
                            <div class="col-sm-10">
                                <?php
                                switch ($msg['isprepay']) {
                                    case '0':
                                        $isPrepay='否';
                                        break;
                                    case '1':
                                        $isPrepay='是';
                                        break;
                                    default:
                                        $isPrepay='无';
                                        break;
                                }
                                ?>
                                <input type="text" class="form-control" disabled value="{$isPrepay}" >
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 control-label">推广PID</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" disabled value="{$msg.pid}" >
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 control-label">下单时间</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" disabled value="<?php echo date('Y-m-d H:i:s',$msg['ordertime']/1000)?>" >
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 control-label">签收时间</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" disabled value="<?php echo date('Y-m-d H:i:s',$msg['signtime']/1000)?>" >
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 control-label">结算时间</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" disabled value="<?php echo date('Y-m-d H:i:s',$msg['settledtime']/1000)?>" >
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 control-label">订单上次更新时间</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" disabled value="<?php echo date('Y-m-d H:i:s',$msg['lastupdatetime']/1000)?>" >
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 control-label">入账时间</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" disabled value="<?php echo date('Y-m-d H:i:s',$msg['commissionentertime']/1000)?>" >
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 control-label">售后订单佣金变动</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" disabled value="{$msg.aftersalechangecommission}" >
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 control-label">售后订单总商品数量变动</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" disabled value="{$msg.aftersalechangegoodscount}" >
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