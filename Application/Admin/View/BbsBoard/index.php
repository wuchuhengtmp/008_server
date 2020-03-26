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
            $('.topCat').click(function(){
                var status=$(this).html();
                if(status=='+')
                {
                    //改变符号
                    $(this).html('-');
                    //显示子分类
                    $(this).parents('tr').nextUntil(".tr_top").show();
                }else {
                    //改变符号
                    $(this).html('+');
                    //显示子分类
                    $(this).parents('tr').nextUntil(".tr_top").hide();
                }
            });

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
                    url:"/dmooo.php/BbsBoard/changestatus",
                    dataType:"html",
                    data:"board_id="+id+"&status="+status,
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
                    title:"确定要删除该版块吗？",
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
                        url:"/dmooo.php/BbsBoard/del",
                        dataType:"html",
                        data:"board_id="+id,
                        success:function(msg)
                        {
                            if(msg=='2') {
                                swal({
                                    title:"该版块下还有二级版块，不准直接删除！",
                                    text:"",
                                    type:"error"
                                },function(){location.reload();})
                            }
                            if(msg=='3') {
                                swal({
                                    title:"该版块下还有帖子，不准直接删除！",
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
                    <h3>当前位置： 论坛系统 &raquo; 版块管理</h3>
                </div>
                <div class="ibox-content">
                    <a class="layui-btn pull-right" href="__CONTROLLER__/add">添加版块</a>
                    <div class="layui-row layui-col-space15">
                        <form action="__CONTROLLER__/changesort" method="post">
                            <table class="layui-table">
                                <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>版块名称</th>
                                    <th>版块图标</th>
                                    <th>菜单状态</th>
                                    <th>创建时间</th>
                                    <th>帖子列表</th>
                                    <th>排序</th>
                                    <th>操作</th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php
                                $BbsArticle=new \Common\Model\BbsArticleModel();
                                foreach($list as $l) {
                                    //顶级分类
                                    if($l['pid']==0) {
                                        $topCat='<span class="topCat">+</span> ';
                                        $tr_class='tr_top';
                                    }else {
                                        $topCat='';
                                        $tr_class='tr_sub';
                                    }
                                    $board_id=$l['board_id'];
                                    //帖子篇数
                                    $article_num=$BbsArticle->where("board_id='$board_id' and is_check='Y'")->count();
                                    //偏移值
                                    if($l['leftpin']!='0') {
                                        $leftpin=$l['leftpin'];
                                    }else {
                                        $leftpin=10;
                                    }
                                    //菜单状态
                                    if($l['is_show']=='Y')
                                    {
                                        $show_str='显示';
                                        $change_show='N';
                                        $show_css='layui-btn layui-btn-xs';
                                    }else {
                                        $show_str='隐藏';
                                        $change_show='Y';
                                        $show_css='layui-btn layui-btn-primary layui-btn-xs';
                                    }
                                    //图标
                                    $img='';
                                    if($l['img'])
                                    {
                                        $img='<img src="'.$l['img'].'" height="50px">';
                                    }
                                    echo '<tr class="'.$tr_class.'">
                                                <td>'.$board_id.'</td>
                                                <td style="text-align:left;padding-left:'.$leftpin.'px">'.$topCat.''.$l['lefthtml'].''.$l['board_name'].'</td>
       		                                    <td>'.$img.'</td>
                                                <td><button type="button" class="'.$show_css.'" onclick="changestatus('.$l['board_id'].',\''.$change_show.'\');">'.$show_str.'</button>
                                                <td>'.$l['createtime'].'</td>                                                
                                                <td><a href="__MODULE__/BbsArticle/checkPass/board_id/'.$l['board_id'].'">查看帖子列表（'.$article_num.'篇）</a></td>
                                                <td class="has-warning"><input name="sort['.$l['board_id'].']" value="'.$l['sort'].'" class="form-control" style="width:50px;text-align:center"/></td>
       		                                    <td>
		                                            <a href="__CONTROLLER__/edit/board_id/'.$l['board_id'].'" title="修改">
			                                             <i class="layui-icon layui-icon-edit" style="font-size:2.0rem"></i>&nbsp;
      		                                        </a>
		                                            <a href="javascript:;" onclick="del('.$l['board_id'].');" title="删除">
			                                             <i class="layui-icon layui-icon-delete" style="font-size:2.0rem"></i>&nbsp;
		                                            </a>
		                                         </td>
       		                                   </tr>';
                                }
                                ?>
                                <tr>
                                    <td colspan="8">
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