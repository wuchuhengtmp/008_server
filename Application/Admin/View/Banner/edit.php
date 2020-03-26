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
                    <h3>当前位置：内容管理 &raquo; Banner/广告图管理 &raquo; 编辑图片<a class="pull-right" href="__CONTROLLER__/index/cat_id/{$cat_id}">返回Banner/广告图列表 <i class="fa fa-angle-double-right"></i></a></h3>
                </div>
            </div>
        </div>
        <div class="layui-row layui-col-space15">
            <div class="layui-col-md12">
                <div class="layui-card">
                    <div class="layui-card-body">
                        <form action="__ACTION__/id/{$id}/cat_id/{$cat_id}"  class="form-horizontal" method="post" enctype="multipart/form-data">
                            <div class="layui-form-item">
                                <label class="layui-form-label" style="width: 100px;">名称</label>
                                <div class="layui-input-block">
                                    <input type="text" class="layui-input" name="title" value="{$msg['title']}" placeholder="">
                                </div>
                            </div>
                            <div class="layui-form-item">
                                <label class="layui-form-label" style="width: 100px;">原图片</label>
                                <div class="layui-input-block">
                                    <?php if($msg['img']){echo '<img src="'.$msg['img'].'" height="100"/>';}else {echo '暂无';} ?>
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
                                    <input type="text" class="layui-input" id="c1" name="color" value="{$msg['color']}">
                                </div>
                            </div>
                            <div class="layui-form-item">
                                <label class="layui-form-label" style="width: 100px;">排序</label>
                                <div class="layui-input-block">
                                    <input type="text" class="layui-input" name="sort" value="{$msg['sort']}">
                                    <span class="help-block m-b-none text-danger">数字越大越排在前</span>
                                </div>
                            </div>
                            <div class="layui-form-item">
                                <label class="layui-form-label" style="width: 100px;">超链接</label>
                                <div class="layui-input-block">
                                    <input type="text" class="layui-input" name="href" value="{$msg['href']}">
                                </div>
                            </div>
                            <div class="layui-form-item">
                                <label class="layui-form-label" style="width: 100px;">是否显示</label>
                                <div>
                                    <div class="layui-input-block">
                                        <label>
                                            <input type="radio" name="is_show" value="Y" <?php if($msg['is_show']=='Y') echo 'checked'; ?> > <i></i>是
                                            <input type="radio" name="is_show" value="N" <?php if($msg['is_show']=='N') echo 'checked'; ?> > <i></i>否
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <div class="layui-form-item">
                                <label class="layui-form-label" style="width: 100px;">类型</label>
                                <div>
                                    <div class="layui-input-block">
                                        <label>
                                            <input type="radio" name="type" value="1" <?php if($msg['type']==1) echo 'checked'; ?> /> <i></i>网页
                                            <input type="radio" name="type" value="2" <?php if($msg['type']==2) echo 'checked'; ?> /> <i></i>淘宝
                                            <input type="radio" name="type" value="3" <?php if($msg['type']==3) echo 'checked'; ?> /> <i></i>京东
                                            <input type="radio" name="type" value="4" <?php if($msg['type']==4) echo 'checked'; ?> /> <i></i>拼多多
                                            <input type="radio" name="type" value="5" <?php if($msg['type']==5) echo 'checked'; ?> /> <i></i>支付宝
                                            <input type="radio" name="type" value="6" <?php if($msg['type']==6) echo 'checked'; ?> /> <i></i>淘宝年货节
                                            <input type="radio" name="type" value="7" <?php if($msg['type']==7) echo 'checked'; ?> /> <i></i>春节红包
                                            <input type="radio" name="type" value="8" <?php if($msg['type']==8) echo 'checked'; ?> /> <i></i>新人红包
                                            <input type="radio" name="type" value="9" <?php if($msg['type']==9) echo 'checked'; ?> /> <i></i>淘宝商品
                                            <input type="radio" name="type" value="10" <?php if($msg['type']==10) echo 'checked'; ?> /> <i></i>拉新活动
                                            <input type="radio" name="type" value="11" <?php if($msg['type']==11) echo 'checked'; ?> /> <i></i>0元购
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <div class="layui-form-item">
                                <label class="layui-form-label" style="width: 100px;">类型值</label>
                                <div class="layui-input-block">
                                    <input type="text" class="layui-input" name="type_value" value="{$msg.type_value}">
                                </div>
                            </div>
<!--                            <div class="form-group">-->
<!--                                <div class="col-sm-4 col-sm-offset-2">-->
<!--                                    <button class="btn btn-primary" type="submit"><i class="fa fa-check"></i>&nbsp;编辑</button>-->
<!--                                    <button class="btn btn-white" type="reset"><i class="fa fa-refresh"></i>&nbsp;重置</button>-->
<!--                                </div>-->
<!--                            </div>-->
                            <div class="layui-form-item layui-layout-admin">
                                <div class="layui-input-block">
                                    <button type="submit" class="layui-btn"><i class="fa fa-check"></i>&nbsp;编辑</button>
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