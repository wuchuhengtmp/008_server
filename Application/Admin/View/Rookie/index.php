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


    <link rel="stylesheet" type="text/css" href="__CSS__/page.css" />

    <script type="text/javascript">
        $(document).ready(function(){
            $(".i-checks").iCheck({
                checkboxClass:"icheckbox_square-green",
                radioClass:"iradio_square-green",
            });
        });

        function deladmin(id)
        {
            if(id!='') {
                swal({
                    title:"确定要删除？",
                    text:"",
                    type:"warning",
                    showCancelButton:true,
                    cancelButtonText:"取消",
                    confirmButtonColor:"#DD6B55",
                    confirmButtonText:"删除",
                    closeOnConfirm:false
                },function(){
                    $.ajax({
                        type:"POST",
                        url: "/dmooo.php/Rookie/del",
                        dataType:"html",
                        data:"id="+id,
                        success:function(msg)
                        {
                            if(msg=='1')
                            {
                                swal({
                                    title:"删除成功！",
                                    text:"",
                                    type:"success"
                                },function(){location.reload();})
                            }else {
                                swal({
                                    title:"操作失败！",
                                    text:"",
                                    type:"error"
                                },function(){location.reload();})
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
                    <h3>当前位置： 营销中心  &raquo; 拉新活动</h3>
                </div>
                <div class="ibox-content">
                    <form action="__ACTION__" method="get" role="form" class="form-inline pull-left">
                        <input type="hidden" name="p" value="1">
                        搜索拉新活动：<input type="text" placeholder="" name="search" value="{$search}" class="form-control">
<!--                        <button class="btn btn-primary" type="submit">查询</button>-->
                        <button class="layui-btn layuiadmin-btn-admin" lay-submit lay-filter="LAY-user-back-search">查询</button>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                    </form>
                    <a class="layui-btn pull-right" href="__CONTROLLER__/add">添加活动</a>
                    <div class="layui-row layui-col-space15">
                        <table class="layui-table">
                            <thead>
                            <tr>
                                <th>ID</th>
                                <th>活动名称</th>
                                <th>创建时间</th>
                                <th>活动时间</th>
                                <th>兑现时间</th>
                                <th>奖励设置</th>
                                <th>兑现记录</th>
                                <th>操作</th>
                            </tr>
                            </thead>
                            <tbody>
                            <foreach name="data" item="value">
                                <tr>
                                    <td>{$value.id}</td>
                                    <td>{$value.name}</td>
                                    <td>{$value.add_time}</td>
                                    <td>{$value.start_time} 至 {$value.end_time}</td>
                                    <td>{$value.exs_time} 至 {$value.exe_time}</td>
                                    <td><a href="__CONTROLLER__/reward?id={$value.id}" style="color: blue">奖励设置</a></td>
                                    <td><a href="/dmooo.php/RookieRecord/index?id={$value.id}" style="color: blue">兑现记录</a></td>
                                    <td>
                                        <a href="__CONTROLLER__/edit?id={$value.id}" title="修改">
                                            <i class="layui-icon layui-icon-edit" style="font-size:2.0rem"></i>&nbsp;
                                        </a>
                                        <a href="javascript:;" onclick="deladmin({$value.id});" title="删除">
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