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
    <script src="__ADMIN_JS__/jquery.min.js?v=2.1.4"></script>
    <script src="__ADMIN_JS__/plugins/iCheck/icheck.min.js"></script>
    <script src="__ADMIN_JS__/plugins/layer/laydate/laydate.js"></script>
    <script>
        $(document).ready(function(){$(".i-checks").iCheck({checkboxClass:"icheckbox_square-green",radioClass:"iradio_square-green",})});
    </script>
</head>

<body class="gray-bg">
<div class="wrapper wrapper-content animated fadeInRight">
    <div class="row">
        <div class="col-sm-12">
            <div class="ibox float-e-margins">
                <div class="ibox-title">
                    <h3>当前位置：唯品会管理系统 &raquo; 唯品会订单管理 &raquo; 处理遗漏唯品会订单</h3>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-12">
                <div class="ibox float-e-margins">
                    <div class="ibox-content">
                        <h3><strong style="color:red;">1、订单时间格式：年-月-日 时:分:秒，如2018-10-28 14:20:00</strong></h3>
                        <h3><strong style="color:red;">2、查询的订单是查询30分钟内的所有订单，比如下单时间为2018-10-28 14:30:00，则“订单开始时间”填写2018-10-28 14:00:00 至 2018-10-28 14:30:00 之间的时间</strong></h3>
                        <h3><strong style="color:red;">3、订单时间填写的是订单最新的更新时间，请根据订单状态填写最新的时间，不一定是下单时间或者付款时间，也可以是确认收货时间</strong></h3>
                        <form action="__ACTION__"  class="form-horizontal" method="post" enctype="multipart/form-data">
                            <div class="form-group">
                                <label class="col-sm-2 control-label">订单开始时间</label>
                                <div class="col-sm-10">
<!--                                    todo 记得修改回来-->
                                    <input class="form-control layer-date" name="time" placeholder="" onclick="laydate({istime: true, format: 'YYYY-MM-DD hh:mm:ss'})">
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-sm-4 col-sm-offset-2">
                                    <button class="btn btn-primary" type="submit"><i class="fa fa-check"></i>&nbsp;批量处理</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</body>
</html>