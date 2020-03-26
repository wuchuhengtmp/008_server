<head>
    <title>订单统计</title>
    <link rel="stylesheet" type="text/css" href="__PUBLIC__/css/page.css"/>
    <link rel="stylesheet" type="text/css" href="__PUBLIC__/admin/css/rule.css"/>
    <script type="text/javascript" src="__PUBLIC__/js/my97/WdatePicker.js"></script>
</head>

<body>
<div id="wapper">
    <include file="Public:goodsleft"/>
    <div class="right">
        <div class="right_top">
            <div class="right_l">订单管理 &raquo; 订单统计</div>
            <div class="right_r"></div>
        </div>
        <div class="right_t">
            <div class="addrule">
                <form class="select" action="__ACTION__" method="get">
                    根据时间查询订单统计：<select name="createtime">
                        <option value="">——全部——</option>
                        <option value="1">本年度订单</option>
                        <option value="2">本季度订单</option>
                        <option value="3">本月订单</option>
                        <option value="4">本周订单</option>
                        <option value="5">今天订单</option>
                        <option value="6">近一周订单</option>
                        <option value="7">近一月订单</option>
                        <option value="8">上一月订单</option>
                        <option value="9">上一季度订单</option>
                        <option value="10">上一年度订单</option>
                    </select>
                    开始时间：<input type="text" name="begin_time" value="" onClick="WdatePicker()" class="Wdate input1"
                                style="width:90px">
                    结束时间：<input type="text" name="end_time" value="" onClick="WdatePicker()" class="Wdate input1"
                                style="width:90px;margin-right:10px">
                    <input type="submit" value="查询订单" class="ruleadd">
                </form>
            </div>
            <div style="clear:both"></div>
            <?php
            if (!empty($pie3d_data)) {
                echo '<div class="order">
               <div class="l">
                <img src="__MODULE__/JpGraph/pie3d?title=' . $pie3d_title . '&width=' . $pie3d_width . '&height=' . $pie3d_height . '&data=' . $pie3d_data . '&legends=' . $pie3d_legends . '">
               </div>
               <div class="r">
                 <p>金额统计：</p>
                 <p>未支付金额：￥' . $money[0] . '</p>
                 <p>已支付金额：￥' . $money[1] . '</p>
                 <p>已发货金额：￥' . $money[2] . '</p>
                 <p>已确认收货、未评价金额：￥' . $money[3] . '</p>
                 <p>已确认收货、已评价金额：￥' . $money[4] . '</p>
                 <p>申请退款金额：￥' . $money[5] . '</p>
                 <p>退款成功金额：￥' . $money[6] . '</p>
                 <p>拒绝退款金额：￥' . $money[7] . '</p>
               </div>
             </div>';
            } else {
                echo '<div style="text-align: center;">暂无订单！</div>';
            }
            ?>
            <div style="clear:both"></div>
            <?php
            if (!empty($bar_data1y) and !empty($bar_data2y) and !empty($bar_datax)) {
                echo '<img src="__MODULE__/JpGraph/bar2?title=' . $bar_title . '&x_title=' . $bar_xtitle . '&y_title=' . $bar_ytitle . '&width=' . $bar_width . '&height=' . $bar_height . '&data1_color=' . $data1_color . '&data2_color=' . $data2_color . '&data1y=' . $bar_data1y . '&data2y=' . $bar_data2y . '&datax=' . $bar_datax . '">
    	<p style="font-size:14px;margin-left:20px">备注说明：</br>
        1、红色代表已付款的订单，不包括未支付和退款成功的订单；浅蓝色代表未付款的订单，只统计未支付和退款成功的订单。<br>
    	2、横轴代表交易日期，纵轴代表当天所有订单的总金额。</br>
    	3、只统计最近一个月的订单，其他统计请查看上方的饼状图。</p>';
            } else {
                echo '<p style="font-size:14px;margin-left:20px">近一个月暂无订单！</p>';
            }
            ?>

            <div style="clear:both"></div>
        </div>
    </div>
</div>
</body>
