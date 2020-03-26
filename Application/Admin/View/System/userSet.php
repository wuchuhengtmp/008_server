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

<body class="gray-bg">
<div class="layui-fluid">
    <div class="layui-row layui-col-space15">
        <div class="layui-col-md12">
            <div class="layui-card">
                <div class="ibox-title">
                    <h3>当前位置：任务中心 &raquo; 分享好友</h3>
                </div>
            </div>
        </div>
        <div class="layui-row layui-col-space15">
            <div class="layui-col-md12">
                <div class="layui-card">
                    <div class="layui-card-body">
                        <form action="__ACTION__" class="form-horizontal" method="post" enctype="multipart/form-data">
                            <input type="hidden" name="old_user_upgrade_register"
                                   value="{$msg['user_upgrade_register']}">
                            <input type="hidden" name="old_user_upgrade_buy" value="{$msg['user_upgrade_buy']}">

                            <div class="layui-form-item">
                                <label class="layui-form-label" style="width: 185px;">推荐注册增加经验值</label>
                                <div class="layui-input-block">
                                    <input type="text" class="layui-input" name="user_upgrade_register"
                                           value="{$msg['user_upgrade_register']}" placeholder="" style="width: 95%;">
                                </div>
                            </div>
                            <div class="layui-form-item">
                                <label class="layui-form-label" style="width: 185px;">推荐用户购物增加经验值</label>
                                <div class="layui-input-block">
                                    <input type="text" class="layui-input" name="user_upgrade_buy"
                                           value="{$msg['user_upgrade_buy']}" placeholder="" style="width: 95%;">
                                </div>
                            </div>
                            <div class="layui-form-item layui-layout-admin">
                                <div class="layui-input-block">
                                    <button class="layui-btn" type="submit"><i class="fa fa-check"></i>&nbsp;编辑
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