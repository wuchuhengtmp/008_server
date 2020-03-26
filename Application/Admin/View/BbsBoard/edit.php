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
    <script>
        $(document).ready(function(){
            $(".i-checks").iCheck({checkboxClass:"icheckbox_square-green",radioClass:"iradio_square-green",})
        });

        function deloldimg(id)
        {
            if(id!='') {
                swal({
                    title:"确定删除原版块图片吗？",
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
                        url:'/dmooo.php/BbsBoard/deloldimg',
                        dataType:"html",
                        data:"board_id="+id,
                        success:function(msg)
                        {
                            if(msg=='1') {
                                swal({
                                    title:"删除原图片成功！",
                                    text:"",
                                    type:"success"
                                },function(){location.reload();})
                            }else {
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
</head>

<body class="gray-bg">
<div class="layui-fluid">
    <div class="layui-row layui-col-space15">
        <div class="layui-col-md12">
            <div class="layui-card">
                <div class="ibox-title">
                    <h3>当前位置： 论坛系统 &raquo; 编辑版块<a class="pull-right" href="__CONTROLLER__/index">返回上一页 <i class="fa fa-angle-double-right"></i></a></h3>
                </div>
            </div>
        </div>
        <div class="layui-row layui-col-space15">
            <div class="layui-col-md12">
                <div class="layui-card">
                    <div class="layui-card-body">
                        <form action="__ACTION__/board_id/{$msg.board_id}"  class="form-horizontal" method="post" enctype="multipart/form-data">
                            <input type="hidden" name="oldimg" value="{$msg['img']}">
                            <div class="layui-form-item">
                                <label class="layui-form-label" style="width: 100px;">版块名称</label>
                                <div class="layui-input-block">
                                    <input type="text" class="layui-input" name="board_name" value="{$msg.board_name}" placeholder="">
                                </div>
                            </div>
                            <div class="layui-form-item">
                                <label class="layui-form-label" style="width: 100px;">版块图片</label>
                                <div class="layui-input-block">
                                    <?php
                                    if($msg['img']){
                                        echo '<img src="'.$msg['img'].'" height="100"/>
                                            <button class="btn btn-primary" type="button" onclick="deloldimg('.$msg['board_id'].')">删除原图片</button>';
                                    }else {
                                        echo '暂无';
                                    }
                                    ?>
                                </div>
                            </div>
                            <div class="layui-form-item">
                                <label class="layui-form-label" style="width: 100px;">上传新图片</label>
                                <div class="layui-input-block">
                                    <input type="file" name="img" class="layui-input">
                                </div>
                            </div>
                            <div class="layui-form-item">
                                <label class="layui-form-label" style="width: 100px;">排序</label>
                                <div class="layui-input-block">
                                    <input type="text" class="layui-input" name="sort" value="{$msg.sort}">
                                    <span class="help-block m-b-none text-danger">数字越大越排在前</span>
                                </div>
                            </div>
                            <div class="layui-form-item">
                                <label class="layui-form-label" style="width: 100px;">父级版块</label>
                                <div class="layui-input-block">
                                    <select class="layui-input m-b" name="pid">
                                        <option value="0">--默认顶级--</option>
                                        <?php
                                        foreach ($boardlist as $v) {
                                            if(in_array($v['board_id'], $subarr)) {
                                                $disabled='disabled="disabled"';
                                            }else {
                                                $disabled='';
                                            }
                                            if($v['board_id']==$msg['pid']) {
                                                $select='selected';
                                            }else {
                                                $select='';
                                            }
                                            echo '<option value="'.$v['board_id'].'" style="margin-left:55px;" '.$select.' '.$disabled.'>'.$v['lefthtml'].''.$v['board_name'].'</option>';
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div>
                            <div class="layui-form-item">
                                <label class="layui-form-label" style="width: 100px;">是否显示</label>
                                <div>
                                    <div class="layui-input-block">
                                        <label>
                                            <input type="radio" name="is_show" value="Y" <?php if($msg['is_show']=='Y'){echo 'checked';}?> > <i></i>是
                                            <input type="radio" name="is_show" value="N" <?php if($msg['is_show']=='N'){echo 'checked';}?> > <i></i>否
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <div class="layui-form-item">
                                <label class="layui-form-label" style="width: 100px;">关键词</label>
                                <div class="layui-input-block">
                                    <input type="text" class="layui-input" name="keyword" value="{$msg.keyword}">
                                </div>
                            </div>
                            <div class="layui-form-item">
                                <label class="layui-form-label" style="width: 100px;">简要说明</label>
                                <div class="layui-input-block">
                                    <textarea name="description" placeholder="" class="layui-input" style="height:100px;">{$msg.description}</textarea>
                                </div>
                            </div>
<!--                            <div class="form-group">-->
<!--                                <div class="col-sm-4 col-sm-offset-2">-->
<!--                                    <button class="btn btn-primary" type="submit"><i class="fa fa-check"></i>&nbsp;编辑版块</button>-->
<!--                                    <button class="btn btn-white" type="reset"><i class="fa fa-refresh"></i>&nbsp;重置</button>-->
<!--                                </div>-->
<!--                            </div>-->
                            <div class="layui-form-item layui-layout-admin">
                                <div class="layui-input-block">
                                    <button type="submit" class="layui-btn"><i class="fa fa-check"></i>&nbsp;编辑版块</button>
                                    <button type="reset" class="layui-btn layui-btn-primary"><i class="fa fa-refresh"></i>&nbsp;重置</button>
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