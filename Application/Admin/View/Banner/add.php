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
    <!--颜色拾取插件-->
    <link rel="stylesheet" type="text/css" href="__ADMIN__/color/css/jquery.bigcolorpicker.css" />
    <script type="text/javascript" src="__ADMIN__/color/js/jquery.bigcolorpicker.min.js"></script>
    <!--颜色拾取插件-->
    <script>
        $(document).ready(function(){
            //颜色拾取
            $("#c1").bigColorpicker("c1",'#00AC2B');

            $(".i-checks").iCheck({checkboxClass:"icheckbox_square-green",radioClass:"iradio_square-green",})
        });
    </script>
</head>

<body class="gray-bg">
<div class="layui-fluid">
    <div class="layui-row layui-col-space15">
        <div class="layui-col-md12">
            <div class="layui-card">
                <div class="ibox-title">
                    <h3>当前位置：内容管理 &raquo; Banner/广告图管理 &raquo; {$cat_title} &raquo; 添加图片<a class="pull-right" href="__CONTROLLER__/index/cat_id/{$cat_id}">返回Banner/广告图列表 <i class="fa fa-angle-double-right"></i></a></h3>
                </div>
            </div>
        </div>
        <div class="layui-row layui-col-space15">
            <div class="layui-col-md12">
                <div class="layui-card">
                    <div class="layui-card-body">
                        <form action="__ACTION__/cat_id/{$cat_id}"  class="form-horizontal" method="post" enctype="multipart/form-data">
                            <div class="layui-form-item">
                                <label class="layui-form-label" style="width: 100px;">名称</label>
                                <div class="layui-input-block">
                                    <input type="text" class="layui-input" name="title" placeholder="">
                                </div>
                            </div>
                            <div class="layui-form-item">
                                <label class="layui-form-label" style="width: 100px;">上传图片</label>
                                <div class="layui-input-block">
                                    <input type="file" name="img" class="layui-input">
                                </div>
                            </div>
                            <div class="layui-form-item">
                                <label class="layui-form-label" style="width: 100px;">图片颜色</label>
                                <div class="layui-input-block">
                                    <input type="text" class="layui-input" id="c1" name="color">
                                </div>
                            </div>
                            <div class="layui-form-item">
                                <label class="layui-form-label" style="width: 100px;">排序</label>
                                <div class="layui-input-block">
                                    <input type="text" class="layui-input" name="sort">
                                    <span class="help-block m-b-none text-danger">数字越大越排在前</span>
                                </div>
                            </div>
                            <div class="layui-form-item">
                                <label class="layui-form-label" style="width: 100px;">超链接</label>
                                <div class="layui-input-block">
                                    <input type="text" class="layui-input" name="href">
                                </div>
                            </div>
                            <div class="layui-form-item">
                                <label class="layui-form-label" style="width: 100px;">是否显示</label>
                                <div>
                                    <div class="layui-input-block">
                                        <label>
                                            <input type="radio" name="is_show" value="Y" checked> <i></i>是
                                            <input type="radio" name="is_show" value="N"> <i></i>否
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <div class="layui-form-item">
                                <label class="layui-form-label" style="width: 100px;">类型</label>
                                <div>
                                    <div class="layui-input-block">
                                        <label>
                                            <input type="radio" name="type" value="1" checked/> <i></i>网页
                                            <input type="radio" name="type" value="2"/> <i></i>淘宝
                                            <input type="radio" name="type" value="3"/> <i></i>京东
                                            <input type="radio" name="type" value="4"/> <i></i>拼多多
                                            <input type="radio" name="type" value="5"/> <i></i>支付宝
                                            <input type="radio" name="type" value="6"/> <i></i>淘宝年货节
                                            <input type="radio" name="type" value="7"/> <i></i>春节红包
                                            <input type="radio" name="type" value="8"/> <i></i>新人红包
                                            <input type="radio" name="type" value="9"/> <i></i>淘宝商品
                                            <input type="radio" name="type" value="10"/> <i></i>拉新活动
                                            <input type="radio" name="type" value="11"/> <i></i>0元购
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <div class="layui-form-item">
                                <label class="layui-form-label" style="width: 100px;">类型值</label>
                                <div class="layui-input-block">
                                    <input type="text" class="layui-input" name="type_value">
                                </div>
                            </div>
<!--                            <div class="form-group">-->
<!--                                <div class="col-sm-4 col-sm-offset-2">-->
<!--                                    <button class="btn btn-primary" type="submit"><i class="fa fa-check"></i>&nbsp;添加</button>-->
<!--                                    <button class="btn btn-white" type="reset"><i class="fa fa-refresh"></i>&nbsp;重置</button>-->
<!--                                </div>-->
<!--                            </div>-->
                            <div class="layui-form-item layui-layout-admin">
                                <div class="layui-input-block">
                                    <button type="submit" class="layui-btn"><i class="fa fa-check"></i>&nbsp;添加</button>
                                    <button type="reset" class="layui-btn layui-btn-primary"><i class="fa fa-refresh"></i>&nbsp;重置</button>
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