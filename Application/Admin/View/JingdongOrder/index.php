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

    <!-- Sweet Alert -->
    <link href="__ADMIN_CSS__/plugins/sweetalert/sweetalert.css" rel="stylesheet">
    <!-- Sweet Alert -->
    <script src="__ADMIN_JS__/jquery.min.js?v=2.1.4"></script>
    <script src="__ADMIN_JS__/bootstrap.min.js?v=3.3.6"></script>
    <script src="__ADMIN_JS__/content.min.js?v=1.0.0"></script>
    <script src="__ADMIN_JS__/plugins/sweetalert/sweetalert.min.js"></script>

    <link rel="stylesheet" type="text/css" href="__CSS__/page.css"/>

</head>

<body class="gray-bg">
<div class="layui-fluid">
    <div class="layui-row layui-col-space15">
        <div class="layui-col-md12">
            <div class="layui-card">
                <div class="ibox-title">
                    <h3>当前位置： 京东管理系统 &raquo; 京东订单管理</h3>
                </div>
                <div class="ibox-content">
                    <form action="__ACTION__" method="get" role="form" class="form-inline pull-left">
                        订单号：<input type="text" placeholder="" name="orderId" class="form-control">
                        商品名称：<input type="text" placeholder="" name="skuName" class="form-control">
                        所属用户：<input type="text" placeholder="" name="username" class="form-control">
                        订单状态：<select class="form-control" name="validCode">
                            <option value="">请选择订单状态</option>
                            <option value="-1">未知</option>
                            <option value="2">无效-拆单</option>
                            <option value="3">无效-取消</option>
                            <option value="4">无效-京东帮帮主订单</option>
                            <option value="5">无效-账号异常</option>
                            <option value="6">无效-赠品类目不返佣</option>
                            <option value="7">无效-校园订单</option>
                            <option value="8">无效-企业订单</option>
                            <option value="9">无效-团购订单</option>
                            <option value="10">无效-开增值税专用发票订单</option>
                            <option value="11">无效-乡村推广员下单</option>
                            <option value="12">无效-自己推广自己下单</option>
                            <option value="13">无效-违规订单</option>
                            <option value="14">无效-来源与备案网址不符</option>
                            <option value="15">待付款</option>
                            <option value="16">已付款</option>
                            <option value="17">已完成</option>
                            <option value="18">已结算</option>
                        </select>
                        <button class="layui-btn" type="submit">查询</button>
                    </form>
                    <div class="layui-row layui-col-space15">
                        <table class="layui-table">
                            <thead>
                            <tr>
                                <th>订单号</th>
                                <th width="20%">订单名称</th>
                                <th>所属用户</th>
                                <th>总价</th>
                                <th>平台佣金</th>
                                <th>京东佣金</th>
                                <th>佣金比例</th>
                                <th>下单时间</th>
                                <th>订单状态</th>
                                <th>操作</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php
                            $JingdongOrder = new \Common\Model\JingdongOrderModel();
                            $JingdongOrderDetail = new \Common\Model\JingdongOrderDetailModel();
                            $User = new \Common\Model\UserModel();
                            ?>
                            <foreach name="list" item="l">
                                <tr>
                                    <?php
                                    //订单信息
                                    $order_id = $l['order_id'];
                                    $orderMsg = $JingdongOrder->where("id=$order_id")->find();
                                    ?>
                                    <td>{$orderMsg['orderid']}</td>
                                    <td>{$l['skuname']}</td>
                                    <td>
                                        <?php
                                        if ($l['user_id']) {
                                            $userMsg = $User->getUserMsg($l['user_id']);
                                            echo $userMsg['phone'];
                                        }
                                        ?>
                                    </td>
                                    <td>￥{$l['estimatecosprice']}</td>
                                    <td>￥{$l['commission']}</td>
                                    <td>￥{$l['estimatefee']}</td>
                                    <td><?php echo $l['commissionrate']; ?>%</td>
                                    <td>
                                        <?php
                                        echo date('Y-m-d H:i:s', $l['ordertime'] / 1000);
                                        ?>
                                    </td>
                                    <td>
                                        <?php
                                        $status_zh = $JingdongOrderDetail->getStatusZh($l['validcode']);
                                        if ($l['status'] == 2) {
                                            $status_zh = '<font color="red">已结算</font>';
                                        }
                                        echo $status_zh;
                                        ?>
                                    </td>
                                    <td>
                                        <a href="__CONTROLLER__/msg/id/{$l.id}" title="查看详情">
                                            <i class="layui-icon layui-icon-read" style="font-size:2.0rem"></i>&nbsp;
                                        </a>
                                    </td>
                                </tr>
                            </foreach>
                            </tbody>
                        </table>
                        <div class="pages">{$page}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</body>
</html>