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
    <script src="__ADMIN_JS__/plugins/iCheck/icheck.min.js"></script>
    <script src="__ADMIN_JS__/bootstrap.min.js?v=3.3.6"></script>
    <script src="__ADMIN_JS__/content.min.js?v=1.0.0"></script>
    <script src="__ADMIN_JS__/plugins/sweetalert/sweetalert.min.js"></script>
    <script type="text/javascript">
        $(document).ready(function(){
            $(".i-checks").iCheck({
                checkboxClass:"icheckbox_square-green",
                radioClass:"iradio_square-green",
            });

            //取消全选
            $('#unselect').click(function(){
                $("input:checkbox").removeAttr("checked");
                $(".i-checks").iCheck({
                    checkboxClass:"icheckbox_square-green",
                    radioClass:"iradio_square-green",
                });
            });
            //全选
            $('#selectall').click(function(){
                $("input:checkbox").prop("checked","checked");
                $(".i-checks").iCheck({
                    checkboxClass:"icheckbox_square-green",
                    radioClass:"iradio_square-green",
                });
            });

            //批量删除
            $('#batchdel').click(function(){
                var all_id='';
                $(":checkbox").each(function(){
                    if($(this).prop("checked"))
                    {
                        all_id+=$(this).val()+',';
                    }
                });
                if(all_id!='') {
                    swal({
                        title:"确定删除这些留言吗？",
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
                            url:"/dmooo.php/Message/batchdel",
                            dataType:"html",
                            data:"all_id="+all_id,
                            success:function(msg)
                            {
                                if(msg=='1')
                                {
                                    swal({
                                        title:"批量删除成功！",
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
                }else {
                    swal({title:"",text:"请选择需要删除的留言！"})
                    return false;
                }
            });

        });

        function changestatus(id,status)
        {
            if(id!='')
            {
                $.ajax({
                    type:"POST",
                    url:"/dmooo.php/Message/changestatus",
                    dataType:"html",
                    data:"id="+id+"&status="+status,
                    success:function(msg)
                    {
                        if(msg=='1')
                        {
                            swal({
                                title:"修改状态成功！",
                                text:"",
                                type:"success"
                            },function(){location.reload();})
                        }else {
                            swal({
                                title:"修改状态失败！",
                                text:"",
                                type:"error"
                            },function(){location.reload();})
                        }
                    }
                });
            }
        }

        function del(id)
        {
            if(id!='') {
                swal({
                    title:"确定要删除该留言吗？",
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
                        url:"/dmooo.php/Message/del",
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
                    <h3>当前位置： 内容管理 &raquo; 留言管理 &raquo; {$cat_title}<a class="pull-right" href="__MODULE__/MessageCat/index">返回留言分类列表 <i class="fa fa-angle-double-right"></i></a></h3>
                </div>
                <div class="ibox-content">
                    <div class="layui-row layui-col-space15">
                        <table class="layui-table">
                            <thead>
                            <tr>
                                <th></th>
                                <th>ID</th>
                                <th>留言人</th>
                                <th>联系电话</th>
                                <th>Email</th>
                                <th>IP地址</th>
                                <th>留言时间</th>
                                <th>是否显示</th>
                                <th>操作</th>
                            </tr>
                            </thead>
                            <tbody>
                            <foreach name="mlist" item="l">
                                <tr>
                                    <td style="text-align: center"><input class="checkbox i-checks" type="checkbox" id="allid[]" value="{$l['id']}"></td>
                                    <td>{$l['id']}</td>
                                    <td>{$l['linkman']}</td>
                                    <td>{$l['phone']}</td>
                                    <td>{$l['email']}</td>
                                    <td>{$l['ip']}</td>
                                    <td>{$l['createtime']}</td>
                                    <td>
                                        <if condition='$l.is_show eq Y'>
                                            <button type="button" class="layui-btn layui-btn-xs" onclick="changestatus({$l.id},'N');">显示</button>
                                            <else/>
                                            <button type="button" class="layui-btn layui-btn-danger layui-btn-xs" onclick="changestatus({$l.id},'Y');">隐藏</button>
                                        </if>
                                    </td>
                                    <td>
                                        <a href="__CONTROLLER__/edit/id/{$l.id}/cat_id/{$cat_id}" title="修改/编辑留言">
                                            <i class="layui-icon layui-icon-edit" style="font-size:2.0rem"></i>&nbsp;
                                        </a>
                                        <a href="javascript:;" onclick="del({$l.id});" title="删除">
                                            <i class="layui-icon layui-icon-delete" style="font-size:2.0rem"></i>&nbsp;
                                        </a>
                                        <a href="__CONTROLLER__/reply/id/{$l.id}/cat_id/{$cat_id}" title="站长回复留言">
                                            <i class="fa fa-bullhorn text-danger" style="font-size:2.0rem"></i>&nbsp;
                                        </a>
                                    </td>
                                </tr>
                            </foreach>
                            <tr>
                                <td colspan="9">
                                    <input type="button" class="layui-btn pull-left" id="unselect" value="取消选择">
                                    <input type="button" class="layui-btn pull-left" id="selectall" value="全选">
                                    <input type="button" class="layui-btn pull-left" id="batchdel" value="批量删除">
                                </td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</body>
</html>