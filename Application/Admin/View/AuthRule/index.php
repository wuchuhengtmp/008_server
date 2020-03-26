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
    <!-- Sweet Alert -->
    <link href="__ADMIN_CSS__/plugins/sweetalert/sweetalert.css" rel="stylesheet">
    <script src="__ADMIN_JS__/plugins/sweetalert/sweetalert.min.js"></script>
    <!-- Sweet Alert -->

    <script type="text/javascript">
        $(document).ready(function () {
            $('.topCat').click(function () {
                var status = $(this).html();
                if (status == '+') {
                    //改变符号
                    $(this).html('-');
                    //显示子分类
                    $(this).parents('tr').nextUntil(".tr_top").show();
                } else {
                    //改变符号
                    $(this).html('+');
                    //显示子分类
                    $(this).parents('tr').nextUntil(".tr_top").hide();
                }
            });

            $(".i-checks").iCheck({
                checkboxClass: "icheckbox_square-green",
                radioClass: "iradio_square-green",
            });
        });

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

        function delrule(id) {
            if (id != '') {
                swal({
                    title: "确定要删除该条权限规则吗？",
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
                        url: "delrule",
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
                                    title: "删除失败！",
                                    text: "",
                                    type: "success"
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
    <style>
        .topCat {
            color: red;
            font-size: 24px;
            font-weight: bold;
        }

        .tr_sub {
            display: none;
        }
    </style>
</head>

<body class="gray-bg">
<div class="layui-fluid">
    <div class="layui-row layui-col-space15">
        <div class="layui-col-md12">
            <div class="layui-card">
                <div class="ibox-title">
                    <h3>当前位置： 管理员管理 &raquo; 权限管理</h3>
                </div>
                <div class="ibox-content">
                    <form action="__CONTROLLER__/addrule" method="post" role="form" class="form-inline pull-left">
                        状态：<select class="form-control" name="status">
                            <option value="1">显示</option>
                            <option value="0">不显示</option>
                        </select>
                        父级：<select class="form-control" name="pid">
                            <option value="0">--默认顶级--</option>
                            <foreach name="admin_rule" item="v">
                                <option value="{$v.id}" style="margin-left:55px;">{$v.lefthtml}{$v.title}</option>
                            </foreach>
                        </select>
                        名称：<input type="text" placeholder="" name="title" class="form-control">
                        控制器/方法：<input type="text" placeholder="" name="name" class="form-control">
                        排序：<input type="text" placeholder="" name="sort" class="form-control">
                        &nbsp;&nbsp;&nbsp;
                        <button class="layui-btn pull-right" type="submit">添加权限</button>
                    </form>
                    <div class="layui-row layui-col-space15">
                        <h3><strong style="color:red;">1、不设置父级，则默认为顶级</strong></h3>
                        <h3><strong style="color:red;">2、《控制器/方法》：意思是按照 控制器/方法
                                名来对应设置文件访问权限，即ControllerName/ActionName</strong></h3>
                        <h3><strong style="color:red;">3、《控制器/方法》不能重复添加，否则报错，请确保每个权限的“控制器/方法”字段名称不同</strong></h3>
                        <h3><strong style="color:red;">4、排序控制显示顺序，数字越大越在前面显示</strong></h3>
                        <br/>
                        <form action="__CONTROLLER__/changesort" method="post">
                            <table class="layui-table">
                                <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>权限名称</th>
                                    <th>控制器/方法</th>
                                    <th>菜单状态</th>
                                    <th>添加时间</th>
                                    <th>排序</th>
                                    <th>操作</th>
                                </tr>
                                </thead>
                                <tbody>
                                <foreach name="admin_rule" item="v">
                                    <?php
                                    //顶级分类
                                    if ($v['pid'] == 0) {
                                        $topCat = '<span class="topCat">+</span> ';
                                        $tr_class = 'tr_top';
                                    } else {
                                        $topCat = '';
                                        $tr_class = 'tr_sub';
                                    }
                                    ?>
                                    <tr class="{$tr_class}">
                                        <td>{$v.id}</td>
                                        <td style='text-align:left;padding-left:<if condition="$v.leftpin neq 0">{$v.leftpin}px</if>'>
                                            {$topCat}{$v.lefthtml}{$v.title}
                                        </td>
                                        <td style="text-align:left;">{$v.name}</td>
                                        <td>
                                            <if condition='$v[status] eq 1'>
                                                <button type="button" class="layui-btn layui-btn-xs"
                                                        onclick="changestatus({$v.id},0);">显示
                                                </button>
                                                <else/>
                                                <button type="button" class="layui-btn layui-btn-primary layui-btn-xs"
                                                        onclick="changestatus({$v.id},1);">隐藏
                                                </button>
                                            </if>
                                        </td>
                                        <td>{$v.create_time}</td>
                                        <td class="has-warning"><input name="sort[{$v.id}]" value="{$v.sort}"
                                                                       class="form-control"
                                                                       style="width:50px;text-align:center"/></td>
                                        <td>
                                            <a href="__CONTROLLER__/editrule/rule_id/{$v.id}" title="修改">
                                                <i class="layui-icon layui-icon-edit" style="font-size:2.0rem"></i>&nbsp;
                                            </a>
                                            <a href="javascript:;" onclick="delrule({$v.id});" title="删除">
                                                <i class="layui-icon layui-icon-delete" style="font-size:2.0rem"></i>&nbsp;
                                            </a>
                                        </td>
                                    </tr>
                                </foreach>
                                <tr>
                                    <td colspan="7">
                                        <input type="submit" class="layui-btn pull-right" value="统一排序">
                                    </td>
                                </tr>
                                </tbody>
                            </table>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</body>
</html>