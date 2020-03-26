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
    <script>
        $(document).ready(function(){$(".i-checks").iCheck({checkboxClass:"icheckbox_square-green",radioClass:"iradio_square-green",})});
    </script>
</head>

<body class="gray-bg">
<div class="layui-fluid">
    <div class="layui-row layui-col-space15">
        <div class="layui-col-md12">
            <div class="layui-card">
                <div class="ibox-title">
                    <h3>当前位置：内容管理  &raquo; 留言管理 &raquo; 编辑留言<a class="pull-right" href="__CONTROLLER__/index/cat_id/{$cat_id}">返回留言列表 <i class="fa fa-angle-double-right"></i></a></h3>
                </div>
            </div>
        </div>
        <div class="layui-row layui-col-space15">
            <div class="layui-col-md12">
                <div class="layui-card">
                    <div class="layui-card-body">
                        <form action="__ACTION__/id/{$id}/cat_id/{$cat_id}"  class="form-horizontal" method="post" enctype="multipart/form-data">
                           <div class="layui-form-item">
                                 <label class="layui-form-label" style="width: 120px;">联系人</label>
                                <div class="layui-input-block">
                                    <input type="text" class="layui-input" name="linkman" value="{$msg.linkman}" placeholder="" style="width: 99%">
                                </div>
                            </div>
                           <div class="layui-form-item">
                                 <label class="layui-form-label" style="width: 120px;">联系电话</label>
                                <div class="layui-input-block">
                                    <input type="text" class="layui-input" name="phone" value="{$msg.phone}" placeholder="" style="width: 99%">
                                </div>
                            </div>
                           <div class="layui-form-item">
                                 <label class="layui-form-label" style="width: 120px;">Email</label>
                                <div class="layui-input-block">
                                    <input type="text" class="layui-input" name="email" value="{$msg.email}" placeholder="" style="width: 99%">
                                </div>
                            </div>
                           <div class="layui-form-item">
                                 <label class="layui-form-label" style="width: 120px;">IP地址</label>
                                <div class="layui-input-block">
                                    <input type="text" class="layui-input" name="ip" value="{$msg.ip}" placeholder="" style="width: 99%">
                                </div>
                            </div>
                           <div class="layui-form-item">
                                 <label class="layui-form-label" style="width: 120px;">是否显示</label>
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
                                 <label class="layui-form-label" style="width: 120px;">所属留言分类</label>
                                <div class="layui-input-block">
                                    <select class="layui-input m-b" name="cat_id" style="width: 99%">
                                        <?php
                                        foreach($list as $catlist){
                                            if($catlist['id']==$msg['cat_id']){
                                                $select='selected';
                                            }else {
                                                $select='';
                                            }
                                            echo '<option value="'.$catlist['id'].'" '.$select.'>'.$catlist['title'].'';
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div>
                           <div class="layui-form-item">
                                 <label class="layui-form-label" style="width: 120px;">留言时间</label>
                                <div class="layui-input-block">
                                    <input type="text" class="layui-input" name="createtime" value="{$msg.createtime}" placeholder="" style="width: 99%">
                                </div>
                            </div>
                           <div class="layui-form-item">
                                 <label class="layui-form-label" style="width: 120px;">留言内容</label>
                                <div class="layui-input-block">
                                    <textarea name="content" placeholder="" class="layui-input" style="height:100px;width: 99%">{$msg['content']}</textarea>
                                </div>
                            </div>
<!--                           <div class="layui-form-item">-->
<!--                                <div class="col-sm-4 col-sm-offset-2">-->
<!--                                    <button class="btn btn-primary" type="submit"><i class="fa fa-check"></i>&nbsp;编辑留言</button>-->
<!--                                    <button class="btn btn-white" type="reset"><i class="fa fa-refresh"></i>&nbsp;重置</button>-->
<!--                                </div>-->
<!--                            </div>-->
                            <div class="layui-form-item layui-layout-admin">
                                <div class="layui-input-block">
                                    <button type="submit" class="layui-btn"><i class="fa fa-check"></i>&nbsp;编辑留言</button>
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