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
    <script>
        $(document).ready(function () {
            $(".i-checks").iCheck({checkboxClass: "icheckbox_square-green", radioClass: "iradio_square-green",})
        });
    </script>
</head>

<body class="gray-bg">
<div class="layui-fluid">
    <div class="layui-row layui-col-space15">
        <div class="layui-col-md12">
            <div class="layui-card">
                <div class="ibox-title">
                    <h3>当前位置：京东管理系统 &raquo; 京东商品分类管理 &raquo; 添加京东商品分类<a class="pull-right" href="__CONTROLLER__/index">返回上一页
                            <i class="fa fa-angle-double-right"></i></a></h3>
                </div>
            </div>
        </div>
        <div class="layui-row layui-col-space15">
            <div class="layui-col-md12">
                <div class="layui-card">
                    <div class="layui-card-body">
                        <form action="__ACTION__" class="form-horizontal" method="post" enctype="multipart/form-data">
                            <div class="layui-form-item">
                                <label class="layui-form-label" style="width: 145px;">商品分类名称</label>
                                <div class="layui-input-block">
                                    <input type="text" class="layui-input" name="name" placeholder=""
                                           style="width: 97%;">
                                </div>
                            </div>
                            <div class="layui-form-item">
                                <label class="layui-form-label" style="width: 145px;">京东官方分类ID</label>
                                <div class="layui-input-block">
                                    <input type="text" class="layui-input" name="jingdong_id" placeholder=""
                                           style="width: 97%;">
                                </div>
                            </div>
                            <div class="layui-form-item">
                                <label class="layui-form-label" style="width: 145px;">商品分类图标</label>
                                <div class="layui-input-block">
                                    <input type="file" name="img" class="layui-input" style="width: 97%;">
                                </div>
                            </div>
                            <div class="layui-form-item">
                                <label class="layui-form-label" style="width: 145px;">排序</label>
                                <div class="layui-input-block">
                                    <input type="text" class="layui-input" name="sort" style="width: 97%;">
                                    <span class="help-block m-b-none text-danger"
                                          style="margin-left: 2%;">数字越大越排在前</span>
                                </div>
                            </div>
                            <div class="layui-form-item">
                                <label class="layui-form-label" style="width: 145px;">父级分类</label>
                                <div class="layui-input-block">
                                    <select class="layui-input m-b" name="pid" style="width: 97%;">
                                        <option value="0">--默认顶级--</option>
                                        <?php
                                        foreach ($catlist as $l) {
                                            echo '<option value="' . $l['jingdong_cat_id'] . '" style="margin-left:55px;">' . $l['lefthtml'] . '  ' . $l['name'] . ' ';
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div>
                            <div class="layui-form-item">
                                <label class="layui-form-label" style="width: 145px;">是否显示</label>
                                <div>
                                    <div class="layui-input-block" style="width: 97%;">
                                        <label>
                                            <input type="radio" name="is_show" value="Y" checked> <i></i>是
                                            <input type="radio" name="is_show" value="N"> <i></i>否
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <div class="layui-form-item layui-layout-admin">
                                <div class="layui-input-block">
                                    <button class="layui-btn" type="submit"><i class="fa fa-check"></i>&nbsp;添加京东商品分类
                                    </button>
                                    <button class="layui-btn layui-btn-primary" type="reset"><i
                                                class="fa fa-refresh"></i>&nbsp;重置
                                    </button>
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