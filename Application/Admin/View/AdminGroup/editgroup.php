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
                    <h3>当前位置：管理员管理 &raquo; 组别管理 &raquo; 编辑管理员组<a class="pull-right" href="__CONTROLLER__/index">返回上一页 <i
                                    class="fa fa-angle-double-right"></i></a></h3>
                </div>
            </div>
        </div>
        <div class="layui-row layui-col-space15">
            <div class="layui-col-md12">
                <div class="layui-card">
                    <div class="layui-card-body">
                        <form action="__ACTION__/group_id/{$msg['id']}" class="form-horizontal" method="post"
                              enctype="multipart/form-data">
                            <div class="layui-form-item">
                                <label class="layui-form-label" style="width: 100px;">管理员组名</label>
                                <div class="layui-input-block">
                                    <input type="text" class="layui-input" name="title" value="{$msg['title']}"
                                           placeholder="">
                                    <span class="help-block m-b-none text-danger">{$error1}</span>
                                </div>
                            </div>
                            <div class="layui-form-item">
                                <label class="layui-form-label" style="width: 100px;">是否开启</label>
                                <div>
                                    <div class="layui-input-block">
                                        <label>
                                            <input type="radio" name="status" value="1" <?php if ($msg['status'] == 1) {
                                                echo 'checked';
                                            } ?> > <i></i>是
                                            <input type="radio" name="status" value="0" <?php if ($msg['status'] == 0) {
                                                echo 'checked';
                                            } ?> > <i></i>否
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <div class="layui-form-item">
                                <label class="layui-form-label" style="width: 100px;">组别描述</label>
                                <div class="layui-input-block">
                                    <textarea name="introduce" placeholder="" class="layui-textarea"
                                              style="height:100px;">{$msg['introduce']}</textarea>
                                </div>
                            </div>
                            <div class="layui-form-item">
                                <label class="layui-form-label" style="width: 100px;">权限列表</label>
                                <!--                                <div class="layui-input-block">-->
                                <?php
                                foreach ($rlist as $r) {
                                    if ($r['pid'] == 0) {
                                        $bcss = 'border-bottom: 1px solid #999;';
                                    } else {
                                        $bcss = '';
                                    }
                                    $rid = $r['id'];
                                    $pleft = $r['lvl'] * 20;
                                    $plcss = 'padding-left:' . $pleft . 'px';
                                    $ridp = ',' . $r['id'] . ',';
                                    $rulesp = ',' . $msg['rules'] . ',';
                                    if (strpos($rulesp, $ridp) === false) {
                                        $check = '';
                                    } else {
                                        $check = 'checked';
                                    }
                                    echo '<div class="layui-input-block" style="' . $bcss . ' ' . $plcss . '">
                                                <input type="checkbox" name="rules[]" value="' . $rid . '" ' . $check . '> ' . $r['title'] . '
                                              </div>';
                                }
                                ?>
                                <!--                                </div>-->
                            </div>
                            <div class="layui-form-item layui-layout-admin">
                                <div class="layui-input-block">
                                    <button class="layui-btn" type="submit"><i class="fa fa-check"></i>&nbsp;确认
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