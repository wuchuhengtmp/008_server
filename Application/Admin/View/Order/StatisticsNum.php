<head>
    <!--日期插件-->
    <script type="text/javascript" src="__JS__/my97/WdatePicker.js"></script>
    <!--日期插件-->
    <script type="text/javascript" src="__ADMIN_JS__/ichart.1.2.1.min.js"></script>
    <script type="text/javascript">
        $(function () {
            var data = [{
                name: '已完成订单数量统计',
                value: [
                    <?php
                    foreach ($list as $l) {
                        echo $l['num'] . ',';
                    }
                    ?>
                ],
                color: '#1f7e92',
                line_width: 3
            }];
            var chart = new iChart.LineBasic2D({
                render: 'canvasDiv',
                data: data,
                title: '订单数量统计:<?php echo $p_title;?>',
                width: 800,
                height: 400,
                coordinate: {height: '90%', background_color: '#f6f9fa'},
                sub_option: {
                    hollow_inside: false,//设置一个点的亮色在外环的效果
                    point_size: 16
                },
                labels: [
                    <?php
                    foreach ($list as $l) {
                        echo "'" . $l['date'] . "',";
                    }
                    ?>
                ]
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
                <strong style="color: grey;">当前位置：订单管理 &raquo; 订单数量统计</strong>
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
                <input type="text" name="begin_time" value="" onClick="WdatePicker()" class="textbox Wdate"
                       style="width: 90px">
                结束时间：
                <input type="text" name="end_time" value="" onClick="WdatePicker()" class="textbox Wdate"
                       style="width: 90px; margin-right: 10px">
                <input type="submit" value="查询" class="group_btn"/>
            </form>
        </section>
        <div id="canvasDiv" style="margin-top: 80px"></div>
    </div>
</section>
</body>
