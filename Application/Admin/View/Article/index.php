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
                        title:"确定删除这些文章吗？",
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
                            url:"/dmooo.php/Article/batchdel",
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
                    swal({title:"",text:"请选择需要删除的文章！"})
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
                    var cat_id=$('#cat_id').val();
                    swal({
                        title:"确定转移这些文章吗？",
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
                            url:"/dmooo.php/Article/transfer",
                            dataType:"html",
                            data:"all_id="+all_id+'&cat_id='+cat_id,
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
                    swal({title:"",text:"请选择需要转移的文章！"})
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
                    url:'/dmooo.php/Article/changeshow',
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

        function changetop(id,status)
        {
            if(id!='')
            {
                $.ajax({
                    type:"POST",
                    url:'/dmooo.php/Article/changetop',
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

        function delarticle(id)
        {
            if(id!='') {
                swal({
                    title:"确定要删除该篇文章吗？",
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
                        url:'/dmooo.php/Article/del',
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
                    <h3>当前位置： 内容管理 &raquo; 文章管理 &raquo; {$cname}<a class="pull-right" href="__MODULE__/ArticleCat/index">返回文章分类列表 <i class="fa fa-angle-double-right"></i></a></h3>
                </div>
                <div class="ibox-content">
                    <form action="__ACTION__/cat_id/{$cat_id}" method="get" role="form" class="form-inline pull-left">
                        <input type="hidden" name="p" value="1">
                        文章标题：<input type="text" placeholder="" name="search" class="form-control">
<!--                        <button class="btn btn-primary" type="submit">查询</button>-->
                        <button class="layui-btn layuiadmin-btn-admin" lay-submit lay-filter="LAY-user-back-search">
                            <i class="layui-icon layui-icon-search layuiadmin-button-btn"></i>
                        </button>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                    </form>
                    <a class="layui-btn pull-right" href="__CONTROLLER__/add/cat_id/{$cat_id}">添加文章</a>
                    <div class="layui-row layui-col-space15">
                        <form action="__CONTROLLER__/changesort/cat_id/{$cat_id}" method="post">
                            <table class="layui-table">
                                <thead>
                                <tr>
                                    <th></th>
                                    <th>ID</th>
                                    <th>标题</th>
                                    <th>是否显示</th>
                                    <th>是否置顶</th>
                                    <th>点击量</th>
                                    <th>发布时间</th>
                                    <th>图片列表</th>
                                    <th>排序</th>
                                    <th>操作</th>
                                </tr>
                                </thead>
                                <tbody>
                                <foreach name="articlelist" item="a">
                                    <tr>
                                        <td style="text-align: center"><input class="checkbox i-checks" type="checkbox" id="allid[]" value="{$a['article_id']}"></td>
                                        <td>{$a['article_id']}</td>
                                        <td>{$a['title']}</td>
                                        <td>
                                            <if condition='$a[is_show] eq Y'>
                                                <button type="button" class="layui-btn layui-btn-xs" onclick="changeshow({$a.article_id},'N');">显示</button>
                                                <else/>
                                                <button type="button" class="layui-btn layui-btn-danger layui-btn-xs" onclick="changeshow({$a.article_id},'Y');">隐藏</button>
                                            </if>
                                        </td>
                                        <td>
                                            <if condition='$a.is_top eq Y'>
                                                <button type="button" class="layui-btn layui-btn-xs" onclick="changetop({$a.article_id},'N');">置顶</button>
                                                <else/>
                                                <button type="button" class="layui-btn layui-btn-danger layui-btn-xs" onclick="changetop({$a.article_id},'Y');">不置顶</button>
                                            </if>
                                        </td>
                                        <td>{$a['clicknum']}</td>
                                        <td>{$a['pubtime']}</td>
                                        <td><a href="__MODULE__/ArticleImg/index/article_id/{$a.article_id}/cat_id/{$cat_id}">查看图片列表</a></td>
                                        <td class="has-warning"><input name="sort[{$a.article_id}]" value="{$a.sort}" class="form-control" style="width:50px;text-align:center"/></td>
                                        <td>
                                            <a href="__CONTROLLER__/edit/article_id/{$a.article_id}" title="修改">
                                                <i class="layui-icon layui-icon-edit" style="font-size:2.0rem"></i>&nbsp;
                                            </a>
                                            <a href="javascript:;" onclick="delarticle({$a.article_id});" title="删除">
                                                <i class="layui-icon layui-icon-delete" style="font-size:2.0rem"></i>&nbsp;
                                            </a>
                                        </td>
                                    </tr>
                                </foreach>
                                <tr>
                                    <td colspan="10">
                                        <input type="submit" class="layui-btn pull-left" value="统一排序">
                                        <input type="button" class="layui-btn pull-left" id="unselect" value="取消选择">
                                        <input type="button" class="layui-btn pull-left" id="selectall" value="全选">
                                        <input type="button" class="layui-btn pull-left" id="batchdel" value="批量删除">
                                        <div class="form-inline pull-right">
                                            <input type="button" class="layui-btn" value="批量转移到=>">
                                            <select class="form-control" id="cat_id">
                                                <option value="">请选择版块</option>
                                                <?php
                                                foreach ($catlist as $v) {
                                                    if($v['cat_id']!=$cat_id) {
                                                        echo '<option value="'.$v['cat_id'].'" style="margin-left:55px;">'.$v['lefthtml'].''.$v['cat_name'].'</option>';
                                                    }
                                                }
                                                ?>
                                            </select>
                                            <input type="button" class="layui-btn pull-right" id="transfer" value="确定转移">
                                        </div>
                                    </td>
                                </tr>
                                </tbody>
                            </table>
                        </form>
                        <div class="pages">{$page}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</body>
</html>