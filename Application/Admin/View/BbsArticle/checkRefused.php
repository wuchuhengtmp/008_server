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
    <!-- Sweet Alert -->
    <link href="__ADMIN_CSS__/plugins/sweetalert/sweetalert.css" rel="stylesheet">
    <script src="__ADMIN_JS__/plugins/sweetalert/sweetalert.min.js"></script>
    <!-- Sweet Alert -->

    <script type="text/javascript">
        function del(id)
        {
            if(id!='') {
                swal({
                    title:"确定要删除该篇帖子吗？",
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
                        url:'/dmooo.php/BbsArticle/del',
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
                    <h3>当前位置： 论坛系统 &raquo; 帖子管理 &raquo; 审核不通过帖子列表<a class="pull-right" href="__MODULE__/BbsBoard/index">返回版块管理 <i class="fa fa-angle-double-right"></i></a></h3>
                </div>
                <div class="ibox-content">
                    <form action="__ACTION__" method="get" role="form" class="form-inline pull-left">
                        <input type="hidden" name="p" value="1">
                        帖子标题：<input type="text" placeholder="" name="search" class="form-control">
<!--                        <button class="btn btn-primary" type="submit">查询</button>-->
                        <button class="layui-btn layuiadmin-btn-admin" lay-submit lay-filter="LAY-user-back-search">
                            <i class="layui-icon layui-icon-search layuiadmin-button-btn"></i>
                        </button>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                    </form>
                    <div class="layui-row layui-col-space15">
                        <table class="layui-table">
                            <thead>
                            <tr>
                                <th>ID</th>
                                <th>所属版块</th>
                                <th>标题</th>
                                <th>发布用户</th>
                                <th>联系人</th>
                                <th>联系电话</th>
                                <th>地址</th>
                                <th>浏览量</th>
                                <th>发布时间</th>
                                <th>操作</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php
                            $BbsBoard=new \Common\Model\BbsBoardModel();
                            $User=new \Common\Model\UserModel();
                            ?>
                            <foreach name="list" item="l">
                                <tr>
                                    <td>{$l['id']}</td>
                                    <td>
                                        <?php
                                        $boardMsg=$BbsBoard->getBoardMsg($l['board_id']);
                                        echo $boardMsg['board_name'];
                                        ?>
                                    </td>
                                    <td>{$l['title']}</td>
                                    <td>
                                        <?php
                                        $userMsg=$User->getUserMsg($l['uid']);
                                        echo $userMsg['phone'];
                                        ?>
                                    </td>
                                    <td>{$l['linkman']}</td>
                                    <td>{$l['contact']}</td>
                                    <td>{$l['address']}</td>
                                    <td>{$l['clicknum']}</td>
                                    <td>{$l['pubtime']}</td>
                                    <td>
                                        <a href="__CONTROLLER__/edit/id/{$l.id}" title="审核/查看详情">
                                            <i class="layui-icon layui-icon-edit" style="font-size:2.0rem"></i>&nbsp;
                                        </a>
                                        <a href="javascript:;" onclick="del({$l.id});" title="删除">
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