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
                        title:"确定删除这些帖子吗？",
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
                            url:"/dmooo.php/BbsArticle/batchdel",
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
                    swal({title:"",text:"请选择需要删除的帖子！"})
                    return false;
                }
            });

            //批量转移
            $('#transfer').click(function(){
                var all_id='';
                $(":checkbox").each(function(){
                    if($(this).prop("checked"))
                    {
                        all_id+=$(this).val()+',';
                    }
                });
                if(all_id!='') {
                    var board_id=$('#board_id').val();
                    swal({
                        title:"确定转移这些帖子吗？",
                        text:"",
                        type:"warning",
                        showCancelButton:true,
                        cancelButtonText:"取消",
                        confirmButtonColor:"#DD6B55",
                        confirmButtonText:"转移",
                        closeOnConfirm:false
                    },function(){
                        $.ajax({
                            type:"POST",
                            url:"/dmooo.php/BbsArticle/transfer",
                            dataType:"html",
                            data:"all_id="+all_id+'&board_id='+board_id,
                            success:function(msg)
                            {
                                if(msg=='1')
                                {
                                    swal({
                                        title:"批量转移成功！",
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
                    swal({title:"",text:"请选择需要转移的帖子！"})
                    return false;
                }
            });

        });

        function changeshow(id,status)
        {
            if(id!='')
            {
                $.ajax({
                    type:"POST",
                    url:'/dmooo.php/BbsArticle/changeshow',
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
                    <h3>当前位置： 论坛系统 &raquo; 帖子管理 &raquo; 已审核帖子列表<a class="pull-right" href="__MODULE__/BbsBoard/index">返回版块管理 <i class="fa fa-angle-double-right"></i></a></h3>
                </div>
                <div class="ibox-content">
                    <a class="layui-btn pull-right" href="__CONTROLLER__/add/board_id/{$board_id}">发帖</a>
                    <div class="layui-row layui-col-space15">
                        <table class="layui-table">
                            <thead>
                            <tr>
                                <th></th>
                                <th>ID</th>
                                <th>所属版块</th>
                                <th>标题</th>
                                <th>发布用户</th>
                                <th>联系人</th>
                                <th>联系电话</th>
                                <th>地址</th>
                                <th>浏览量</th>
                                <th>是否显示</th>
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
                                    <td style="text-align: center"><input class="checkbox i-checks" type="checkbox" id="allid[]" value="{$l['id']}"></td>
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
                                    <td>
                                        <if condition='$l[is_show] eq Y'>
                                            <button type="button" class="layui-btn layui-btn-xs" onclick="changeshow({$l.id},'N');">显示</button>
                                            <else/>
                                            <button type="button" class="layui-btn layui-btn-danger layui-btn-xs" onclick="changeshow({$l.id},'Y');">隐藏</button>
                                        </if>
                                    </td>
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
                            <tr>
                                <td colspan="12">
                                    <input type="button" class="layui-btn pull-left" id="unselect" value="取消选择">
                                    <input type="button" class="layui-btn pull-left" id="selectall" value="全选">
                                    <input type="button" class="layui-btn pull-left" id="batchdel" value="批量删除">
                                    <div class="form-inline pull-right">
                                        <input type="button" class="layui-btn" value="批量转移到=>">
                                        <select class="form-control" id="board_id">
                                            <option value="">请选择版块</option>
                                            <?php
                                            foreach ($catlist as $v) {
                                                echo '<option value="'.$v['board_id'].'" style="margin-left:55px;">'.$v['lefthtml'].''.$v['board_name'].'</option>';
                                            }
                                            ?>
                                        </select>
                                        <input type="button" class="layui-btn pull-right" id="transfer" value="确定转移">
                                    </div>
                                </td>
                            </tr>
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