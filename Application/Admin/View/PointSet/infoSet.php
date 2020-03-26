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
    <script>
        $(document).ready(function () {
            $(".i-checks").iCheck({checkboxClass: "icheckbox_square-green", radioClass: "iradio_square-green",})
        });
    </script>

<body class="gray-bg">
<div class="layui-fluid">
    <div class="layui-row layui-col-space15">
        <div class="layui-col-md12">
            <div class="layui-card">
                <div class="ibox-title">
                    <h3>当前位置：任务中心 &raquo; 完善资料设置</h3>
                </div>
            </div>
        </div>
        <div class="layui-row layui-col-space15">
            <div class="layui-col-md12">
                <div class="layui-card">
                    <div class="layui-card-body">
                        <form action="__ACTION__" class="form-horizontal" method="post" enctype="multipart/form-data">
                            <input type="hidden" name="old_task_info_award_type" value="{$msg['task_info_award_type']}">
                            <input type="hidden" name="old_task_info_award_num" value="{$msg['task_info_award_num']}">

                            <div class="layui-form-item">
                                <label class="layui-form-label" style="width: 145px;">完善资料奖励类型</label>
                                <div>
                                    <div class="layui-input-block" style="width: 97%;">
                                        <label>
                                            <input type="radio" name="task_info_award_type"
                                                   value="1" <?php if ($msg['task_info_award_type'] == '1') echo 'checked'; ?> >
                                            <i></i>积分
                                            <input type="radio" name="task_info_award_type"
                                                   value="2" <?php if ($msg['task_info_award_type'] == '2') echo 'checked'; ?> >
                                            <i></i>余额
                                            <input type="radio" name="task_info_award_type"
                                                   value="3" <?php if ($msg['task_info_award_type'] == '3') echo 'checked'; ?> >
                                            <i></i>成长值
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <div class="layui-form-item">
                                <label class="layui-form-label" style="width: 145px;">完善资料奖励数值</label>
                                <div class="layui-input-block">
                                    <input type="text" class="layui-input" name="task_info_award_num"
                                           value="{$msg['task_info_award_num']}" placeholder="" style="width: 97%;">
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