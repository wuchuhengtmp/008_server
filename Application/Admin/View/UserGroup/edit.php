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
                    <h3>当前位置：会员管理 &raquo; 会员组管理 &raquo; 编辑会员组<a class="pull-right" href="__CONTROLLER__/index">返回上一页 <i class="fa fa-angle-double-right"></i></a></h3>
                </div>
            </div>
        </div>
        <div class="layui-row layui-col-space15">
            <div class="layui-col-md12">
                <div class="layui-card">
                    <div class="layui-card-body">
                        <form action="__ACTION__/group_id/{$msg['id']}"  class="form-horizontal" method="post" enctype="multipart/form-data">

                            <div class="layui-form-item">
                                <label class="layui-form-label" style="width: 120px;">会员组名</label>
                                <div class="layui-input-block">
                                    <input class="layui-input" name="title" value="{$msg['title']}" placeholder="" style="width: 97%;">
                                    <span class="help-block m-b-none text-danger">{$error1}</span>
                                </div>
                            </div>
                            <div class="layui-form-item">
                                <label class="layui-form-label" style="width: 120px;">等级必要经验</label>
                                <div class="layui-input-block">
                                    <input class="layui-input" name="exp" value="{$msg['exp']}" placeholder="" style="width: 97%;">
                                    <span class="help-block m-b-none text-danger">从下一级会员组升级为本级会员组所需的最低经验，请填写正整数</span>
                                </div>
                            </div>
                            <!-- <div class="layui-form-item">
                                <label class="layui-form-label" style="width: 120px;">享受的折扣率</label>
                                <div class="layui-input-block">
                                    <input class="layui-input" name="discount" placeholder="" >
                                    <span class="help-block m-b-none text-danger">商品原价基础上打折，如：0.95代表95折</span>
                                </div>
                            </div> -->
                            <div class="layui-form-item">
                                <label class="layui-form-label" style="width: 120px;">是否冻结</label>
                                <div>
                                    <div class="layui-input-block">
                                        <label>
                                            <input type="radio" name="is_freeze" value="N" <?php if($msg['is_freeze']=='N') {echo 'checked';} ?> > <i></i>正常使用
                                            <input type="radio" name="is_freeze" value="Y" <?php if($msg['is_freeze']=='Y') {echo 'checked';} ?> > <i></i>冻结
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <div class="layui-form-item">
                                <label class="layui-form-label" style="width: 120px;">组别描述</label>
                                <div class="layui-input-block">
                                    <textarea name="introduce" placeholder="" class="layui-input" style="height:100px;width: 97%;">{$msg['introduce']}</textarea>
                                </div>
                            </div>
                            <div class="layui-form-item">
                                <label class="layui-form-label" style="width: 120px;">收益比例-用户</label>
                                <div class="layui-input-block">
                                    <input class="layui-input" name="fee_user" value="{$msg.fee_user}" placeholder="" style="width: 97%;">
                                    <span class="help-block m-b-none text-danger">请填写整数，60代表60%</span>
                                </div>
                            </div>
                            <div class="layui-form-item">
                                <label class="layui-form-label" style="width: 120px;">收益比例-扣税</label>
                                <div class="layui-input-block">
                                    <input class="layui-input" name="fee_service" value="{$msg.fee_service}" placeholder="" style="width: 97%;">
                                    <span class="help-block m-b-none text-danger">请填写整数，10代表10%</span>
                                </div>
                            </div>
                            <div class="layui-form-item">
                                <label class="layui-form-label" style="width: 120px;">收益比例-平台</label>
                                <div class="layui-input-block">
                                    <input class="layui-input" name="fee_plantform" value="{$msg.fee_plantform}" placeholder="" style="width: 97%;">
                                    <span class="help-block m-b-none text-danger">请填写整数，30代表30%</span>
                                </div>
                            </div>
                            <!--
                            <div class="layui-form-item">
                                <label class="layui-form-label" style="width: 120px;">自购佣金</label>
                                <div class="layui-input-block">
                                    <input class="layui-input" name="self_rate" value="{$msg.self_rate}" placeholder="" >
                                    <span class="help-block m-b-none text-danger">请填写整数，30代表30%</span>
                                </div>
                            </div>
                            -->
                            <div class="layui-form-item">
                                <label class="layui-form-label" style="width: 120px;">直推佣金</label>
                                <div class="layui-input-block">
                                    <input class="layui-input" name="referrer_rate" value="{$msg.referrer_rate}" placeholder="" style="width: 97%;">
                                    <span class="help-block m-b-none text-danger">请填写整数，30代表30%</span>
                                </div>
                            </div>

                            <div class="layui-form-item">
                                <label class="layui-form-label" style="width: 120px;">间推佣金</label>
                                <div class="layui-input-block">
                                    <input class="layui-input" name="referrer_rate2" value="{$msg.referrer_rate2}" placeholder="" style="width: 97%;">
                                    <span class="help-block m-b-none text-danger">请填写整数，30代表30%</span>
                                </div>
                            </div>

<!--                            <div class="layui-form-item">-->
<!--                                <div class="col-sm-4 col-sm-offset-2">-->
<!--                                    <button class="btn btn-primary" type="submit"><i class="fa fa-check"></i>&nbsp;编辑会员组</button>-->
<!--                                    <button class="btn btn-white" type="reset"><i class="fa fa-refresh"></i>&nbsp;重置</button>-->
<!--                                </div>-->
<!--                            </div>-->
                            <div class="layui-form-item layui-layout-admin">
                                <div class="layui-input-block" style="width: 97%">
                                    <button type="submit" class="layui-btn"><i class="fa fa-check"></i>&nbsp;编辑会员组</button>
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