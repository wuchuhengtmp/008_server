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
    <!-- Sweet Alert -->
    <link href="__ADMIN_CSS__/plugins/sweetalert/sweetalert.css" rel="stylesheet">
    <!-- Sweet Alert -->
    <script src="__ADMIN_JS__/jquery.min.js?v=2.1.4"></script>
    <script src="__ADMIN_JS__/bootstrap.min.js?v=3.3.6"></script>
    <script src="__ADMIN_JS__/content.min.js?v=1.0.0"></script>
    <script src="__ADMIN_JS__/plugins/sweetalert/sweetalert.min.js"></script>

    <link rel="stylesheet" type="text/css" href="__CSS__/page.css"/>
    <script type="text/javascript">
        function changestatus(id, status) {
            if (id != '') {
                $.ajax({
                    type: "POST",
                    url: "changestatus",
                    dataType: "html",
                    data: "id=" + id + "&status=" + status,
                    success: function (msg) {
                        if (msg == '1') {
                            swal({
                                title: "修改状态成功！",
                                text: "",
                                type: "success"
                            }, function () {
                                location.reload();
                            })
                        } else {
                            swal({
                                title: "修改状态失败！",
                                text: "",
                                type: "error"
                            }, function () {
                                location.reload();
                            })
                        }
                    }
                });
            }
        }

        function deladmin(id) {
            if (id != '') {
                swal({
                    title: "确定要删除该管理员吗？",
                    text: "",
                    type: "warning",
                    showCancelButton: true,
                    cancelButtonText: "取消",
                    confirmButtonColor: "#DD6B55",
                    confirmButtonText: "删除",
                    closeOnConfirm: false
                }, function () {
                    $.ajax({
                        type: "POST",
                        url: "/dmooo.php/Admin/del",
                        dataType: "html",
                        data: "id=" + id,
                        success: function (msg) {
                            if (msg == '1') {
                                swal({
                                    title: "删除成功！",
                                    text: "",
                                    type: "success"
                                }, function () {
                                    location.reload();
                                })
                            } else {
                                swal({
                                    title: "操作失败！",
                                    text: "",
                                    type: "error"
                                }, function () {
                                    location.reload();
                                })
                            }
                        }
                    });
                })
            }
        }
    </script>
</head>

<body class="gray-bg">
<div class="layui-fluid">
    <div class="layui-row layui-col-space15">
        <div class="layui-col-md12">
            <div class="layui-card">
                <div class="ibox-title">
                    <h3>当前位置： 管理员管理 &raquo; 管理员列表</h3>
                </div>
                <div class="ibox-content">
                    <form action="__ACTION__" method="get" role="form" class="form-inline pull-left">
                        <input type="hidden" name="p" value="1">
                        管理员用户名、邮箱、手机号码：<input type="text" placeholder="" name="search" class="form-control">
                        <!--                        <button class="btn btn-primary" type="submit">查询</button>-->
                        <button class="layui-btn layuiadmin-btn-admin" lay-submit lay-filter="LAY-user-back-search">
                            <i class="layui-icon layui-icon-search layuiadmin-button-btn"></i>
                        </button>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                    </form>
                    <form action="__ACTION__" method="get" role="form" class="form-inline pull-left">
                        <input type="hidden" name="p" value="1">
                        组名：<input type="text" placeholder="" name="group_name" class="form-control">
                        <!--                        <button class="btn btn-primary" type="submit">查询</button>-->
                        <button class="layui-btn layuiadmin-btn-admin" lay-submit lay-filter="LAY-user-back-search">
                            <i class="layui-icon layui-icon-search layuiadmin-button-btn"></i>
                        </button>
                    </form>
                    <a class="layui-btn pull-right" href="__CONTROLLER__/add">添加管理员</a>
                    <!--                                                            <a class="btn btn-primary pull-right" href="__CONTROLLER__/add">添加管理员</a>-->
                    <div class="layui-row layui-col-space15">
                        <table class="layui-table">
                            <thead>
                            <tr>
                                <th>ID</th>
                                <th>用户名</th>
                                <th>地区</th>
                                <th>所属分组</th>
                                <th>状态</th>
                                <th>邮箱</th>
                                <th>手机号码</th>
                                <th>登录次数</th>
                                <th>最后登录时间</th>
                                <th>最后登录IP</th>
                                <th>操作</th>
                            </tr>
                            </thead>
                            <tbody>
                            <foreach name="alist" item="a">
                                <tr>
                                    <td>{$a['uid']}</td>
                                    <td>{$a['adminname']}</td>
                                    <td>{$a['province']}-{$a['city']}</td>
                                    <td>
                                        <?php
                                        $group_id = $a['group_id'];
                                        $group = D('admin_group');
                                        $res = $group->where("id=$group_id")->field('title')->find();
                                        echo $res['title'];
                                        ?>
                                    </td>
                                    <td>
                                        <if condition='$a[status] eq 1'>
                                            <button type="button" class="layui-btn layui-btn-xs"
                                                    onclick="changestatus({$a.uid},0);">正常
                                            </button>
                                            <else/>
                                            <button type="button" class="layui-btn layui-btn-primary layui-btn-xs"
                                                    onclick="changestatus({$a.uid},1);">禁用
                                            </button>
                                        </if>
                                    </td>
                                    <td>{$a['email']}</td>
                                    <td>{$a['phone']}</td>
                                    <td>{$a['login_num']}</td>
                                    <td>{$a['last_login_time']}</td>
                                    <td>{$a['last_login_ip']}</td>
                                    <td>
                                        <a href="__CONTROLLER__/edit/uid/{$a.uid}" title="修改">
                                            <i class="layui-icon layui-icon-edit" style="font-size:2.0rem"></i>&nbsp;
                                        </a>
                                        <a href="javascript:;" onclick="deladmin({$a.uid});" title="删除">
                                            <i class="layui-icon layui-icon-delete" style="font-size:2.0rem"></i>&nbsp;
                                        </a>
                                    </td>
                                </tr>
                            </foreach>
                            </tbody>
                        </table>
                        <div class="pages">{$page}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</body>
</html>