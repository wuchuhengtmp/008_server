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

    <!--颜色拾取插件-->
    <link rel="stylesheet" type="text/css" href="__ADMIN__/color/css/jquery.bigcolorpicker.css" />
    <script type="text/javascript" src="__ADMIN__/color/js/jquery.bigcolorpicker.min.js"></script>
    <!--颜色拾取插件-->

    <!-- ueditor -->
    <link rel="stylesheet" type="text/css" href="__PUBLIC__/ueditor/themes/default/css/ueditor.css" />
    <script type="text/javascript" charset="utf-8" src="__PUBLIC__/ueditor/ueditor.config.js"></script>
    <script type="text/javascript" charset="utf-8" src="__PUBLIC__/ueditor/ueditor.all.min.js"> </script>
    <script type="text/javascript" charset="utf-8" src="__PUBLIC__/ueditor/lang/zh-cn/zh-cn.js"></script>
    <!-- ueditor -->

    <script>
        //实例化编辑器
        var ue = UE.getEditor('editor');

        $(document).ready(function(){
            $('#title').blur(function(){
                if($('#title').val()!='')
                {
                    var title=$('#title').val();
                    $.ajax({
                        type:"POST",
                        url:"/dmooo.php/System/scws",
                        dataType:"html",
                        data:"title="+title,
                        success:function(msg)
                        {
                            $('#keywords').val(msg);
                        }
                    });
                }
            });
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
                    <h3>当前位置：内容管理 &raquo; 文章管理 &raquo; 添加文章<a class="pull-right" href="__CONTROLLER__/index/cat_id/{$cat_id}">返回上一页 <i class="fa fa-angle-double-right"></i></a></h3>
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
                                    <a data-toggle="tab" href="#tab-1" aria-expanded="true">文章基本信息</a>
                                </li>
                                <li class="">
                                    <a data-toggle="tab" href="#tab-2" aria-expanded="false">文章详情</a>
                                </li>
                            </ul>
                            <form action="__ACTION__"  class="form-horizontal" method="post" enctype="multipart/form-data">

                                <div class="tab-content">
                                    <!-- 文章基本信息  -->
                                    <div id="tab-1" class="tab-pane active" style="padding-top: 10px">
                                        <div class="layui-form-item">
                                            <label class="layui-form-label" style="width: 120px;">标题</label>
                                            <div class="layui-input-block">
                                                <input type="text" class="layui-input" name="title" id="title" value="" placeholder="" style="width:99%">
                                            </div>
                                        </div>
                                        <div class="layui-form-item">
                                            <label class="layui-form-label" style="width: 120px;">标题颜色</label>
                                            <div class="layui-input-block">
                                                <input type="text" class="layui-input" id="c1" name="title_font_color" style="width:99%">
                                            </div>
                                        </div>
                                        <div class="layui-form-item">
                                            <label class="layui-form-label" style="width: 120px;">作者</label>
                                            <div class="layui-input-block">
                                                <input type="text" class="layui-input" name="author" value="" placeholder="" style="width:99%">
                                            </div>
                                        </div>
                                        <div class="layui-form-item">
                                            <label class="layui-form-label" style="width: 120px;">标题图片</label>
                                            <div class="layui-input-block">
                                                <input type="file" name="img" class="layui-input" style="width:99%">
                                            </div>
                                        </div>
                                        <div class="layui-form-item">
                                            <label class="layui-form-label" style="width: 120px;">大图片</label>
                                            <div class="layui-input-block">
                                                <input type="file" name="bigimg" class="layui-input" style="width:99%">
                                            </div>
                                        </div>
                                        <div class="layui-form-item">
                                            <label class="layui-form-label" style="width: 120px;">上传文件</label>
                                            <div class="layui-input-block">
                                                <input type="file" name="file" class="layui-input" style="width:99%">
                                            </div>
                                        </div>
                                        <div class="layui-form-item">
                                            <label class="layui-form-label" style="width: 120px;">排序</label>
                                            <div class="layui-input-block">
                                                <input type="text" class="layui-input" name="sort" style="width:99%">
                                                <span class="help-block m-b-none text-danger">数字越大越排在前</span>
                                            </div>
                                        </div>
                                        <div class="layui-form-item">
                                            <label class="layui-form-label" style="width: 120px;">点击量</label>
                                            <div class="layui-input-block">
                                                <input type="text" class="layui-input" name="clicknum" value="" placeholder="" style="width:99%">
                                            </div>
                                        </div>
                                        <div class="layui-form-item">
                                            <label class="layui-form-label" style="width: 120px;">链接</label>
                                            <div class="layui-input-block">
                                                <input type="text" class="layui-input" name="href" value="" placeholder="" style="width:99%">
                                            </div>
                                        </div>
                                        <div class="layui-form-item">
                                            <label class="layui-form-label" style="width: 120px;">所属文章分类</label>
                                            <div class="layui-input-block">
                                                <select class="layui-input m-b" name="cat_id" style="width:99%">
                                                    <?php
                                                    foreach ($catlist as $v) {
                                                        if($v['cat_id']==$cat_id) {
                                                            $select='selected';
                                                        }else {
                                                            $select='';
                                                        }
                                                        echo '<option value="'.$v['cat_id'].'" style="margin-left:55px;" '.$select.'>'.$v['lefthtml'].''.$v['cat_name'].'</option>';
                                                    }
                                                    ?>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="layui-form-item">
                                            <label class="layui-form-label" style="width: 120px;">是否显示</label>
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
                                            <label class="layui-form-label" style="width: 120px;">是否置顶</label>
                                            <div>
                                                <div class="layui-input-block">
                                                    <label>
                                                        <input type="radio" name="is_top" value="Y"> <i></i>是
                                                        <input type="radio" name="is_top" value="N" checked> <i></i>否
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- 文章基本信息  -->

                                    <!-- 文章详情  -->
                                    <div id="tab-2" class="tab-pane" style="padding-top: 10px">
                                        <div class="layui-form-item">
                                            <label class="layui-form-label" style="width: 120px;">SEO关键词</label>
                                            <div class="layui-input-block">
                                                <input type="text" class="layui-input" name="keywords" id="keywords" value="" style="width:99%">
                                            </div>
                                        </div>
                                        <div class="layui-form-item">
                                            <label class="layui-form-label" style="width: 120px;">SEO简要说明</label>
                                            <div class="layui-input-block">
                                                <textarea name="description" placeholder="" class="layui-input" style="height:100px;width:99%"></textarea>
                                            </div>
                                        </div>
                                        <div class="layui-form-item">
                                            <label class="layui-form-label" style="width: 120px;">内容</label>
                                            <div class="layui-input-block">
                                                <script name="content" id="editor" type="text/plain" style="height:300px;"></script>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- 文章详情  -->

<!--                                    <div class="form-group">-->
<!--                                        <div class="col-sm-4 col-sm-offset-2">-->
<!--                                            <button class="btn btn-primary" type="submit"><i class="fa fa-check"></i>&nbsp;添加文章</button>-->
<!--                                            <button class="btn btn-white" type="reset"><i class="fa fa-refresh"></i>&nbsp;重置</button>-->
<!--                                        </div>-->
<!--                                    </div>-->
                                    <div class="layui-form-item layui-layout-admin">
                                        <div class="layui-input-block">
                                            <button class="layui-btn" type="submit"><i class="fa fa-check"></i>&nbsp;添加文章</button>
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