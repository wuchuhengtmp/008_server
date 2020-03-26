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
    <script src="__ADMIN_JS__/jquery.min.js?v=2.1.4"></script>
    <script src="__ADMIN_JS__/plugins/iCheck/icheck.min.js"></script>
    <!-- Sweet Alert -->
    <link href="__ADMIN_CSS__/plugins/sweetalert/sweetalert.css" rel="stylesheet">
    <script src="__ADMIN_JS__/plugins/sweetalert/sweetalert.min.js"></script>
    <!-- Sweet Alert -->

    <link rel="stylesheet" type="text/css" href="__CSS__/page.css" />

    <script type="text/javascript">
        $(document).ready(function(){
            $(".i-checks").iCheck({
                checkboxClass:"icheckbox_square-green",
                radioClass:"iradio_square-green",
            });
        });
    </script>
</head>

<body class="gray-bg">
<div class="layui-fluid">
    <div class="layui-row layui-col-space15">
        <div class="layui-col-md12">
            <div class="layui-card">
                <div class="ibox-title">
                    <h3>当前位置： 营销中心 &raquo; 拉新活动 &raquo; 奖励设置<a class="pull-right" href="__CONTROLLER__/index">返回上一页 <i class="fa fa-angle-double-right"></i></a></h3>
                </div>

                <div class="ibox-content">
                    <h3 style="color: red;">*如果想设置N人以上，闭合区间不填写即可</h3>
                    <div class="layui-row layui-col-space15">
                        <form action="__ACTION__/id/{$id}" method="post">
                            <table class="layui-table">
                                <thead>
                                <tr>
                                    <th>等级</th>
                                    <th>开始人数区间</th>
                                    <th>闭合人数区间</th>
                                    <th>奖励数量</th>
                                    <th>奖励形式</th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php
                                for ($i=1; $i < $lv_num+1; $i++) {
                                    // 循环已经设置的数据
                                    $j = $i-1;
                                    $start_interval = $data[$j]['start_interval'];
                                    $end_interval = $data[$j]['end_interval'];
                                    $reward_num = $data[$j]['reward_num'];
                                    $reward_type = $data[$j]['reward_type'];
                                    // 闭合区间设置为无限大
                                    if ($end_interval === '0') {
                                        $end_interval = "∞";
                                    }

                                    // 输出html标签
                                    echo <<<EOF
                                    <tr>
                                        <td>
                                            $i
                                            <input type="hidden" class="input" name="lv$i" value="$i">
                                        </td>
                                        <td><input type="text" class="form-control" name="start_interval$i" value="$start_interval" style="width: 30%;" placeholder="请设置"></td>
                                        <td><input type="text" class="form-control" name="end_interval$i" value="$end_interval" style="width: 30%;" placeholder="请设置"></td>
                                        <td><input type="text" class="form-control" name="reward_num$i" value="$reward_num" style="width: 30%;" placeholder="请设置"></td>
EOF;
                                    // 设置奖励方式下拉框
                                    if ($reward_type == 1) {
                                        $selected1 = "selected";
                                    } else if ($reward_type == 2){
                                        $selected2 = "selected";
                                    }
                                    echo <<<EOF
                                    <td>
                                        <select class="form-control" name="reward_type$i">
                                            <option value="1" $selected1>现金</option>
                                            <option value="2" $selected2>积分</option>
                                        </select>
                                    </td>
                                </tr>
EOF;
                                    // selected置空
                                    if ($reward_type == 1) {
                                        $selected1 = null;
                                    } else if ($reward_type == 2){
                                        $selected2 = null;
                                    }
                                }
                                ?>
                                <tr>
                                    <td colspan="5">
<!--                                        <input type="submit" class="btn btn-primary pull-right" value="提交">-->
<!--                                        <input type="reset" class="btn btn-primary pull-right" value="重置" style="margin-right: 10px">-->
                                        <button class="layui-btn pull-right" type="submit">提交</button>
                                        <button class="layui-btn pull-right" type="reset" style="margin-right: 10px">重置</button>
                                    </td>
                                </tr>
                                </tbody>
                            </table>
                        </form>
                        <div class="pages">{$page}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</body>
</html>