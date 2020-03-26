<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="__ADMIN_CSS__/bootstrap.min14ed.css?v=3.3.6" rel="stylesheet">
    <link href="__ADMIN_CSS__/font-awesome.min93e3.css?v=4.4.0" rel="stylesheet">
    <link href="__ADMIN_CSS__/plugins/iCheck/custom.css" rel="stylesheet">
    <link href="__ADMIN_CSS__/animate.min.css" rel="stylesheet">
    <link rel="stylesheet" href="__LAYUIADMIN__/layui/css/layui.css" media="all">
    <link rel="stylesheet" href="__LAYUIADMIN__/style/admin.css" media="all">
    <link href="__ADMIN_CSS__/style.min862f.css?v=4.1.0" rel="stylesheet">
    <script src="__ADMIN_JS__/jquery.min.js?v=2.1.4"></script>
    <script src="__ADMIN_JS__/plugins/iCheck/icheck.min.js"></script>
    <script>
        $(document).ready(function(){$(".i-checks").iCheck({checkboxClass:"icheckbox_square-green",radioClass:"iradio_square-green",})});
    </script>
</head>

<body class="gray-bg">
<div class="layui-fluid">
    <div class="layui-row layui-col-space15">
        <div class="layui-col-md12">
            <div class="layui-card">
                <div class="ibox-title">
                    <h3>当前位置：营销中心 &raquo; 爆款商品推送</h3>
                </div>
            </div>
        </div>
        <div class="layui-row layui-col-space15">
            <div class="layui-col-md12">
                <div class="layui-card">
                    <div class="layui-card-body">
                        <h3><strong style="color:red;">1、请确保所填写的商品ID正确</strong></h3>
                        <form action="__ACTION__"  class="form-horizontal" method="post" enctype="multipart/form-data">
                            <div class="layui-form-item">
                                <label class="layui-form-label" style="width: 100px;">商品类型</label>
                                <div>
                                    <div class="layui-input-block" style="width: 97%;">
                                        <label>
                                            <input type="radio" name="type" value="1" checked> <i></i>淘宝
                                            <input type="radio" name="type" value="2" > <i></i>京东
                                            <input type="radio" name="type" value="3" > <i></i>拼多多
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <div class="layui-form-item">
                                <label class="layui-form-label" style="width: 100px;">商品ID</label>
                                <div class="layui-input-block">
                                    <input class="layui-input" name="goods_id" placeholder="" style="width: 97%;">
                                </div>
                            </div>
                            <div class="layui-form-item">
                                <label class="layui-form-label" style="width: 100px;">自定义标题</label>
                                <div class="layui-input-block">
                                    <input class="layui-input" name="title" placeholder="" style="width: 97%;">
                                    <span class="help-block m-b-none text-danger">如果不填写自定义标题，则用"爆款商品推荐！"</span>
                                </div>
                            </div>
                            <div class="layui-form-item">
                                <label class="layui-form-label" style="width: 100px;">自定义内容</label>
                                <div class="layui-input-block">
                                    <input class="layui-input" name="content" placeholder="" style="width: 97%;">
                                    <span class="help-block m-b-none text-danger">如果不填写自定义内容，则用商品原标题</span>
                                </div>
                            </div>
<!--                            <div class="form-group">-->
<!--                                <div class="col-sm-4 col-sm-offset-2">-->
<!--                                    <button class="btn btn-primary" type="submit"><i class="fa fa-check"></i>&nbsp;确认推送</button>-->
<!--                                </div>-->
<!--                            </div>-->
                            <div class="layui-form-item layui-layout-admin">
                                <div class="layui-input-block">
                                    <button class="layui-btn" type="submit"><i class="fa fa-check"></i>&nbsp;确认推送</button>
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