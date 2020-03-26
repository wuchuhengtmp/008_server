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
        $(document).ready(function(){
            $(".i-checks").iCheck({
                checkboxClass:"icheckbox_square-green",
                radioClass:"iradio_square-green",
            });
        });

        function changestatus(id,status)
        {
            if(id!='') {
                $.ajax({
                    type:"POST",
                    url:"/dmooo.php/ArticleCat/changestatus",
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

        function delcat(id)
        {
            if(id!='') {
                swal({
                    title:"确定要删除该文章分类吗？分类下的所有文章会一起删除！！！",
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
                        url:"/dmooo.php/ArticleCat/del",
                        dataType:"html",
                        data:"cat_id="+id,
                        success:function(msg)
                        {
                            if(msg=='2') {
                                swal({
                                    title:"该分类下还有二级分类，需要先删除二级分类才能删除该分类！",
                                    text:"",
                                    type:"error"
                                },function(){location.reload();})
                            }
                            if(msg=='1') {
                                swal({
                                    title:"删除成功！",
                                    text:"",
                                    type:"success"
                                },function(){location.reload();})
                            }
                            if(msg=='0') {
                                swal({
                                    title:"删除失败！",
                                    text:"",
                                    type:"success"
                                },function(){location.reload();})
                            }
                        }
                    });
                })
            }
        }
    </script>
    <style>
        .topCat{
            color:red;font-size:24px;font-weight: bold;
        }
        .tr_sub{
            display:none;
        }
    </style>
</head>

<body class="gray-bg">
<div class="layui-fluid">
    <div class="layui-row layui-col-space15">
        <div class="layui-col-md12">
            <div class="layui-card">
                <div class="ibox-title">
                    <h3>当前位置： 内容管理 &raquo; 文章分类管理</h3>
                </div>
                <div class="ibox-content">
                    <a class="layui-btn pull-right" href="__CONTROLLER__/add">添加文章分类</a>
                    <div class="layui-row layui-col-space15">
                        <form action="__CONTROLLER__/changesort" method="post">
                            <table class="layui-table">
                                <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>文章分类名称</th>
                                    <th>菜单状态</th>
                                    <th>创建时间</th>
                                    <th>文章列表</th>
                                    <th>排序</th>
                                    <th>操作</th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php
                                $Article=new \Common\Model\ArticleModel();
                                foreach($catlist as $cl)
                                {
                                    $cat_id=$cl['cat_id'];
                                    //文章篇数
                                    $article_num=$Article->where("cat_id=$cat_id")->count();
                                    //偏移值
                                    if($cl['leftpin']!='0') {
                                        $leftpin=$cl['leftpin'];
                                    }else {
                                        $leftpin=10;
                                    }
                                    //菜单状态
                                    if($cl['is_show']=='Y') {
                                        $show_str='显示';
                                        $change_show='N';
                                        $show_css='layui-btn layui-btn-xs';
                                    }else {
                                        $show_str='隐藏';
                                        $change_show='Y';
                                        $show_css='layui-btn layui-btn-danger layui-btn-xs';
                                    }
                                    echo '<tr>
       		                                   <td>'.$cl['cat_id'].'</td>
       		                                   <td style="text-align:left;padding-left:'.$leftpin.'px">'.$cl['lefthtml'].''.$cl['cat_name'].'</td>
       		                                   <td><button type="button" class="'.$show_css.'" onclick="changestatus('.$cl['cat_id'].',\''.$change_show.'\');">'.$show_str.'</button></td>
       		                                   <td>'.$cl['create_time'].'</td>
       		                                   <td><a href="__MODULE__/Article/index/cat_id/'.$cl['cat_id'].'">查看文章列表（'.$article_num.'篇）</a></td>
       		                                   <td class="has-warning"><input name="sort['.$cl['cat_id'].']" value="'.$cl['sort'].'" class="form-control" style="width:50px;text-align:center"/></td>
       		                                   <td>
		                                          <a href="__CONTROLLER__/edit/cat_id/'.$cl['cat_id'].'" title="修改">
			                                         <i class="layui-icon layui-icon-edit" style="font-size:2.0rem"></i>&nbsp;
      		                                      </a>
		                                          <a href="javascript:;" onclick="delcat('.$cl['cat_id'].');" title="删除">
			                                         <i class="layui-icon layui-icon-delete" style="font-size:2.0rem"></i>&nbsp;
		                                          </a>
                                               </td>
       		                                 </tr>';
                                }
                                ?>
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