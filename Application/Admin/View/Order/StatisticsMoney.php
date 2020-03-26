<head>
    <!--日期插件-->
    <script type="text/javascript" src="__JS__/my97/WdatePicker.js"></script>
    <!--日期插件-->
    <script type="text/javascript" src="__ADMIN_JS__/ichart.1.2.1.min.js"></script>
    <script type="text/javascript">
        $(function () {
            var data = [{
                name: '',
                value: [
                    <?php
                    foreach ($list as $l) {
                        $money = $l['money'] / 100;
                        echo $money . ',';
                    }
                    ?>
                ],
                color: '#1385a5'
            }];
            var chart = new iChart.ColumnMulti2D({
                render: 'canvasDiv',
                data: data,
                labels: [
                    <?php
                    foreach ($list as $l) {
                        echo '"' . $l['date'] . '",';
                    }
                    ?>
                ],
                title: '订单金额统计:<?php echo $p_title;?>',
                subtitle: '',
                footnote: '',
                width: 1200,
                height: 400,
                background_color: '#ffffff',
                legend: {
                    enable: true,
                    background_color: null,
                    border: {
                        enable: false
                    }
                },
                coordinate: {
                    background_color: '#f1f1f1',
                    scale: [{
                        position: 'left',
                        start_scale: 0,
                        end_scale: 1000,
                        scale_space: 5000
                    }],
                    width: 900,
                    height: 260
                }
            });
            chart.draw();
        });
    </script>
</head>
<body>
<section class="rt_wrap content mCustomScrollbar">
    <div class="rt_content">
        <section>
            <h2>
                <strong style="color: grey;">当前位置：订单管理 &raquo; 订单金额统计 &raquo; <?php echo $p_title; ?></strong>
            </h2>

            <form action="__ACTION__" method="get" style="float: left">
                根据时间查询统计：
                <select class="select" name="createtime">
                    <option value="3" selected>本月</option>
                    <option value="4">本周</option>
                    <option value="6">近一周</option>
                    <option value="7">近一月</option>
                    <option value="8">上一月</option>
                </select>
                开始时间：
                <input type="text" name="begin_time" value=""
                       onClick="WdatePicker()" class="textbox Wdate" style="width: 90px">
                结束时间：
                <input type="text" name="end_time" value="" onClick="WdatePicker()"
                       class="textbox Wdate" style="width: 90px; margin-right: 10px">
                <input type="submit" value="查询" class="group_btn"/>
            </form>
        </section>
        <div id="canvasDiv" style="margin-top: 80px"></div>
    </div>
</section>
</body>
