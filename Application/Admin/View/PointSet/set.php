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
    <script src="__ADMIN_JS__/bootstrap.min.js?v=3.3.6"></script>
    <script src="__ADMIN_JS__/plugins/iCheck/icheck.min.js"></script>
    <script>
        $(document).ready(function () {
            $(".i-checks").iCheck({checkboxClass: "icheckbox_square-green", radioClass: "iradio_square-green",})
        });
    </script>
</head>

<body class="gray-bg">
<div class="layui-fluid">
    <div class="layui-row layui-col-space15">
        <div class="layui-col-md12">
            <div class="layui-card">
                <div class="ibox-title">
                    <h3>当前位置：任务中心 &raquo; 积分系统</h3>
                </div>
            </div>
        </div>
        <div class="layui-row layui-col-space15">
            <div class="layui-col-md12">
                <div class="layui-card">
                    <div class="layui-card-body">
                        <div class="layui-tab">
                            <ul class="nav nav-tabs">
<!--                                <li class="layui-this">-->
                                    <!--                            <ul class="layui-tab-title">-->
                                <li class="active">
                                    <a data-toggle="tab" href="#tab-1" aria-expanded="true">签到配置</a>
                                </li>
                                <li>
                                    <a data-toggle="tab" href="#tab-2" aria-expanded="false">注册配置</a>
                                </li>
                                <li>
                                    <a data-toggle="tab" href="#tab-3" aria-expanded="false">费用配置</a>
                                </li>
                            </ul>
                            <form action="__ACTION__" class="form-horizontal" method="post"
                                  enctype="multipart/form-data">
                                <input type="hidden" name="old_point_value" value="{$msg['point_value']}">
                                <input type="hidden" name="old_point_register" value="{$msg['point_register']}">
                                <input type="hidden" name="old_point_recommend_register"
                                       value="{$msg['point_recommend_register']}">
                                <input type="hidden" name="old_sign_award_type" value="{$msg['sign_award_type']}">
                                <input type="hidden" name="old_sign_award_model" value="{$msg['sign_award_model']}">
                                <input type="hidden" name="old_sign_award_fixed_num"
                                       value="{$msg['sign_award_fixed_num']}">
                                <input type="hidden" name="old_sign_award_continuous_num1"
                                       value="{$msg['sign_award_continuous_num1']}">
                                <input type="hidden" name="old_sign_award_continuous_num2"
                                       value="{$msg['sign_award_continuous_num2']}">
                                <input type="hidden" name="old_sign_award_continuous_num3"
                                       value="{$msg['sign_award_continuous_num3']}">
                                <input type="hidden" name="old_sign_award_continuous_num4"
                                       value="{$msg['sign_award_continuous_num4']}">
                                <input type="hidden" name="old_sign_award_continuous_num5"
                                       value="{$msg['sign_award_continuous_num5']}">
                                <input type="hidden" name="old_sign_award_continuous_num6"
                                       value="{$msg['sign_award_continuous_num6']}">
                                <input type="hidden" name="old_sign_award_continuous_num7"
                                       value="{$msg['sign_award_continuous_num7']}">

                                <div class="tab-content">
                                    <!-- 签到配置  -->
                                    <div id="tab-1" class="tab-pane active">
                                        <div class="layui-form-item">
                                            <label class="layui-form-label" style="width: 190px;">签到奖励类型</label>
                                            <div style="padding-top: 7px">
                                                <div class="layui-input-block" style="width: 94%;">
                                                    <label>
                                                        <input type="radio" name="sign_award_type"
                                                               value="1" <?php if ($msg['sign_award_type'] == '1') echo 'checked'; ?> >
                                                        <i></i>积分
                                                        <input type="radio" name="sign_award_type"
                                                               value="2" <?php if ($msg['sign_award_type'] == '2') echo 'checked'; ?> >
                                                        <i></i>余额
                                                        <input type="radio" name="sign_award_type"
                                                               value="3" <?php if ($msg['sign_award_type'] == '3') echo 'checked'; ?> >
                                                        <i></i>成长值
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="layui-form-item">
                                            <label class="layui-form-label" style="width: 190px;">签到奖励模式</label>
                                            <div style="padding-top: 7px">
                                                <div class="layui-input-block" style="width: 94%;">
                                                    <label>
                                                        <input type="radio" name="sign_award_model"
                                                               value="1" <?php if ($msg['sign_award_model'] == '1') echo 'checked'; ?> >
                                                        <i></i>固定
                                                        <input type="radio" name="sign_award_model"
                                                               value="2" <?php if ($msg['sign_award_model'] == '2') echo 'checked'; ?> >
                                                        <i></i>连续
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="layui-form-item">
                                            <label class="layui-form-label" style="width: 190px;">固定签到奖励数值</label>
                                            <div class="layui-input-block">
                                                <input type="text" class="layui-input" name="sign_award_fixed_num"
                                                       value="{$msg['sign_award_fixed_num']}" placeholder="" style="width: 88%;">
                                            </div>
                                        </div>
                                        <div class="layui-form-item">
                                            <label class="layui-form-label" style="width: 190px;">连续签到奖励数值-第1天</label>
                                            <div class="layui-input-block">
                                                <input type="text" class="layui-input"
                                                       name="sign_award_continuous_num1"
                                                       value="{$msg['sign_award_continuous_num1']}" placeholder="" style="width: 88%;">
                                            </div>
                                        </div>
                                        <div class="layui-form-item">
                                            <label class="layui-form-label" style="width: 190px;">连续签到奖励数值-第2天</label>
                                            <div class="layui-input-block">
                                                <input type="text" class="layui-input"
                                                       name="sign_award_continuous_num2"
                                                       value="{$msg['sign_award_continuous_num2']}" placeholder="" style="width: 88%;">
                                            </div>
                                        </div>
                                        <div class="layui-form-item">
                                            <label class="layui-form-label" style="width: 190px;">连续签到奖励数值-第3天</label>
                                            <div class="layui-input-block">
                                                <input type="text" class="layui-input"
                                                       name="sign_award_continuous_num3"
                                                       value="{$msg['sign_award_continuous_num3']}" placeholder="" style="width: 88%;">
                                            </div>
                                        </div>
                                        <div class="layui-form-item">
                                            <label class="layui-form-label" style="width: 190px;">连续签到奖励数值-第4天</label>
                                            <div class="layui-input-block">
                                                <input type="text" class="layui-input"
                                                       name="sign_award_continuous_num4"
                                                       value="{$msg['sign_award_continuous_num4']}" placeholder="" style="width: 88%;">
                                            </div>
                                        </div>
                                        <div class="layui-form-item">
                                            <label class="layui-form-label" style="width: 190px;">连续签到奖励数值-第5天</label>
                                            <div class="layui-input-block">
                                                <input type="text" class="layui-input"
                                                       name="sign_award_continuous_num5"
                                                       value="{$msg['sign_award_continuous_num5']}" placeholder="" style="width: 88%;">
                                            </div>
                                        </div>
                                        <div class="layui-form-item">
                                            <label class="layui-form-label" style="width: 190px;">连续签到奖励数值-第6天</label>
                                            <div class="layui-input-block">
                                                <input type="text" class="layui-input"
                                                       name="sign_award_continuous_num6"
                                                       value="{$msg['sign_award_continuous_num6']}" placeholder="" style="width: 88%;">
                                            </div>
                                        </div>
                                        <div class="layui-form-item">
                                            <label class="layui-form-label" style="width: 190px;">连续签到奖励数值-第7天</label>
                                            <div class="layui-input-block">
                                                <input type="text" class="layui-input"
                                                       name="sign_award_continuous_num7"
                                                       value="{$msg['sign_award_continuous_num7']}" placeholder="" style="width: 88%;">
                                            </div>
                                        </div>
                                    </div>
                                    <!-- 签到配置  -->

                                    <!-- 注册配置  -->
                                    <div id="tab-2" class="tab-pane" style="padding-top: 10px">
                                        <div class="layui-form-item">
                                            <label class="layui-form-label" style="width: 190px;">注册赠送积分</label>
                                            <div class="layui-input-block">
                                                <input type="text" class="layui-input" name="point_register"
                                                       value="{$msg['point_register']}" placeholder="" style="width: 88%;">
                                            </div>
                                        </div>
                                        <div class="layui-form-item">
                                            <label class="layui-form-label" style="width: 190px;">推荐注册赠送积分</label>
                                            <div class="layui-input-block">
                                                <input type="text" class="layui-input" name="point_recommend_register"
                                                       value="{$msg['point_recommend_register']}" placeholder="" style="width: 88%;">
                                            </div>
                                        </div>
                                    </div>
                                    <!-- 注册配置  -->

                                    <!-- 费用配置  -->
                                    <div id="tab-3" class="tab-pane" style="padding-top: 10px">
                                        <div class="layui-form-item">
                                            <label class="layui-form-label" style="width: 190px;">每个积分价值金额</label>
                                            <div class="layui-input-block">
                                                <input type="text" class="layui-input" name="point_value" value="{$msg['point_value']}" placeholder="" style="width: 94%;">
                                            </div>
                                        </div>
                                    </div>
                                    <!-- 费用配置  -->
                                    <div class="layui-form-item layui-layout-admin">
                                        <div class="layui-input-block">
                                            <button class="layui-btn" type="submit"><i class="fa fa-check"></i>&nbsp;编辑
                                            </button>
                                            <button class="layui-btn layui-btn-primary" type="reset"><i
                                                        class="fa fa-refresh"></i>&nbsp;重置
                                            </button>
                                        </div>
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