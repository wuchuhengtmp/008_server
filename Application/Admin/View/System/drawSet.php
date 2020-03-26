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
        $(document).ready(function(){
            $(".i-checks").iCheck({checkboxClass:"icheckbox_square-green",radioClass:"iradio_square-green",})
        });
    </script>

<body class="gray-bg">
<div class="layui-fluid">
    <div class="layui-row layui-col-space15">
        <div class="layui-col-md12">
            <div class="layui-card">
                <div class="ibox-title">
                    <h3>当前位置：系统设置 &raquo; 提现设置</h3>
                </div>
            </div>
        </div>
        <div class="layui-row layui-col-space15">
            <div class="layui-col-md12">
                <div class="layui-card">
                    <div class="layui-card-body">
                        <form action="__ACTION__"  class="form-horizontal" method="post" enctype="multipart/form-data">
                            <input type="hidden" name="old_draw_method" value="{$msg['draw_method']}">
                            <input type="hidden" name="old_draw_auto_money" value="{$msg['draw_auto_money']}">
                            <input type="hidden" name="old_draw_auto_type" value="{$msg['draw_auto_type']}">
                            <input type="hidden" name="old_draw_start_date" value="{$msg['draw_start_date']}">
                            <input type="hidden" name="old_draw_end_date" value="{$msg['draw_end_date']}">
                            <input type="hidden" name="old_draw_limit_money" value="{$msg['draw_limit_money']}">

                            <!--                            <div class="layui-form-item">-->
                            <div class="layui-form-item">
                                <label class="layui-form-label" style="width: 300px;">提现方式</label>
                                <div>
                                    <!--                                    <div class="radio i-checks">-->
                                    <div class="layui-input-block" style="width: 80%;">
                                        <label>
                                            <input type="radio" name="draw_method" value="1" <?php if($msg['draw_method']=='1') echo 'checked'; ?> > <i></i>人工审核
                                            <input type="radio" name="draw_method" value="2" <?php if($msg['draw_method']=='2') echo 'checked'; ?> > <i></i>后台审核
                                            <input type="radio" name="draw_method" value="3" <?php if($msg['draw_method']=='3') echo 'checked'; ?> > <i></i>自动转账
                                            <span class="help-block m-b-none text-danger">人工审核：所有提现申请由后台客服人员人工审核，核实后进行线下转账给用户</span>
                                            <span class="help-block m-b-none text-danger">后台审核：用户申请提现后，由后台客服人员人工审核，审核通过后自动转账给用户提现账号</span>
                                            <span class="help-block m-b-none text-danger">自动转账：用户申请提现后自动转账给用户提现账号，无需人工审核转账</span>
                                            <span class="help-block m-b-none text-danger">目前自动转账仅支持支付宝支付</span>
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <div class="layui-form-item">
                                <label class="layui-form-label" style="width: 300px;">自动转账金额</label>
                                <div>
                                    <div class="layui-input-block" style="width: 80%;">
                                        <input type="text" class="layui-input" name="draw_auto_money" value="{$msg['draw_auto_money']}" placeholder="" style="width: 80%;">
                                        <label>
                                        <span class="help-block m-b-none text-danger">单位元，针对 “后台审核/自动转账” 提现方式下设置不超过多少元进行自动转账</span>
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <div class="layui-form-item">
                                <!--                            <div class="layui-form-item">-->
                                <label class="layui-form-label" style="width: 300px;">自动转账-大额提现后台审核是否自动转账</label>
                                <div>
                                    <!--                                    <div class="radio i-checks">-->
                                    <div class="layui-input-block" style="width: 80%;">
                                        <label>
                                            <input type="radio" name="draw_auto_type" value="Y" <?php if($msg['draw_auto_type']=='Y') echo 'checked'; ?> > <i></i>是
                                            <input type="radio" name="draw_auto_type" value="N" <?php if($msg['draw_auto_type']=='N') echo 'checked'; ?> > <i></i>否
                                            <span class="help-block m-b-none text-danger">针对 “后台审核/自动转账” 提现方式下，超出自动转账金额的大额提现，后台审核通过后是否自动转账</span>
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <div class="layui-form-item">
                                <label class="layui-form-label" style="width: 300px;">可提现起始日期</label>
                                <div class="layui-input-block">
                                    <input type="text" class="layui-input" name="draw_start_date" value="{$msg['draw_start_date']}" placeholder="" style="width: 69%;">
                                </div>
                            </div>
                            <div class="layui-form-item">
                                <label class="layui-form-label" style="width: 300px;">可提现截止日期</label>
                                <div class="layui-input-block">
                                    <input type="text" class="layui-input" name="draw_end_date" value="{$msg['draw_end_date']}" placeholder="" style="width: 69%;">
                                </div>
                            </div>
                            <div class="layui-form-item">
                                <label class="layui-form-label" style="width: 300px;">最低提现金额</label>
                                <div class="layui-input-block">
                                    <input type="text" class="layui-input" name="draw_limit_money" value="{$msg['draw_limit_money']}" placeholder="" style="width: 69%;">
                                </div>
                            </div>
                            <!--                            <div class="layui-form-item">-->
                            <!--                                <div class="col-sm-4 col-sm-offset-2">-->
                            <!--                                    <button class="btn btn-primary" type="submit"><i class="fa fa-check"></i>&nbsp;编辑</button>-->
                            <!--                                    <button class="btn btn-white" type="reset"><i class="fa fa-refresh"></i>&nbsp;重置</button>-->
                            <!--                                </div>-->
                            <!--                            </div>-->
                            <div class="layui-form-item layui-layout-admin">
                                <div class="layui-input-block">
                                    <button class="layui-btn" type="submit"><i class="fa fa-check"></i>&nbsp;编辑</button>
                                    <button class="layui-btn layui-btn-primary" type="reset"><i class="fa fa-refresh"></i>&nbsp;重置</button>
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