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
    <script src="__ADMIN_JS__/bootstrap.min.js?v=3.3.6"></script>
    <script src="__ADMIN_JS__/plugins/iCheck/icheck.min.js"></script>

    <!-- ueditor -->
    <link rel="stylesheet" type="text/css" href="__PUBLIC__/ueditor/themes/default/css/ueditor.css" />
    <script type="text/javascript" charset="utf-8" src="__PUBLIC__/ueditor/ueditor.config.js"></script>
    <script type="text/javascript" charset="utf-8" src="__PUBLIC__/ueditor/ueditor.all.min.js"> </script>
    <script type="text/javascript" charset="utf-8" src="__PUBLIC__/ueditor/lang/zh-cn/zh-cn.js"></script>
    <!-- ueditor -->

    <script>
        //实例化编辑器
        var ue = UE.getEditor('editor');

        $(document).ready(function(){$(".i-checks").iCheck({checkboxClass:"icheckbox_square-green",radioClass:"iradio_square-green",})});
    </script>
</head>

<body class="gray-bg">
<div class="layui-fluid">
    <div class="layui-row layui-col-space15">
        <div class="layui-col-md12">
            <div class="layui-card">
                <div class="ibox-title">
                    <h3>当前位置：内容管理 &raquo; 添加文章分类<a class="pull-right" href="__CONTROLLER__/index">返回上一页 <i class="fa fa-angle-double-right"></i></a></h3>
                </div>
            </div>
        </div>
        <div class="layui-row layui-col-space15">
            <div class="layui-col-md12">
                <div class="layui-card">
                    <div class="layui-card-body">
                        <div class="layui-tab">
                            <ul class="nav nav-tabs">
                                <li class="active">
                                    <a data-toggle="tab" href="#tab-1" aria-expanded="true">文章分类基本信息</a>
                                </li>
                                <li class="">
                                    <a data-toggle="tab" href="#tab-2" aria-expanded="false">文章分类详情</a>
                                </li>
                            </ul>
                            <form action="__ACTION__"  class="form-horizontal" method="post" enctype="multipart/form-data">

                                <div class="tab-content">
                                    <!-- 文章分类基本信息  -->
                                    <div id="tab-1" class="tab-pane active" style="padding-top: 10px">
                                        <div class="layui-form-item">
                                            <label class="layui-form-label" style="width: 100px;">分类名称</label>
                                            <div class="layui-input-block">
                                                <input type="text" class="layui-input" name="cat_name" value="" placeholder="">
                                            </div>
                                        </div>
                                        <div class="layui-form-item">
                                            <label class="layui-form-label" style="width: 100px;">分类图片</label>
                                            <div class="layui-input-block">
                                                <input type="file" name="img" class="layui-input">
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
                                            <label class="layui-form-label" style="width: 100px;">父级分类</label>
                                            <div class="layui-input-block">
                                                <select class="layui-input m-b" name="parent_id">
                                                    <option value="0">--默认顶级--</option>
                                                    <foreach name="catlist" item="v">
                                                        <option value="{$v.cat_id}" style="margin-left:55px;">{$v.lefthtml}{$v.cat_name}</option>
                                                    </foreach>
                                                </select>
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
                                            <label class="layui-form-label" style="width: 100px;">关键词</label>
                                            <div class="layui-input-block">
                                                <input type="text" class="layui-input" name="keywords" value="" placeholder="">
                                            </div>
                                        </div>
                                        <div class="layui-form-item">
                                            <label class="layui-form-label" style="width: 100px;">简要说明</label>
                                            <div class="layui-input-block">
                                                <textarea name="description" placeholder="" class="layui-input" style="height:100px;"></textarea>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- 文章分类基本信息  -->

                                    <!-- 文章分类详情  -->
                                    <div id="tab-2" class="tab-pane" style="padding-top: 10px">
                                        <div class="layui-form-item">
                                            <label class="layui-form-label" style="width: 100px;">内容</label>
                                            <div class="layui-input-block">
                                                <script name="content" id="editor" type="text/plain" style="height:300px;"></script>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- 文章分类详情  -->

<!--                                    <div class="form-group">-->
<!--                                        <div class="col-sm-4 col-sm-offset-2">-->
<!--                                            <button class="btn btn-primary" type="submit"><i class="fa fa-check"></i>&nbsp;添加文章分类</button>-->
<!--                                            <button class="btn btn-white" type="reset"><i class="fa fa-refresh"></i>&nbsp;重置</button>-->
<!--                                        </div>-->
<!--                                    </div>-->
                                    <div class="layui-form-item layui-layout-admin">
                                        <div class="layui-input-block">
                                            <button class="layui-btn" type="submit"><i class="fa fa-check"></i>&nbsp;添加文章分类</button>
                                            <button class="layui-btn layui-btn-primary" type="reset"><i class="fa fa-refresh"></i>&nbsp;重置</button>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</body>
</html>