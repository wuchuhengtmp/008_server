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
</head>

<body class="gray-bg">
<div class="layui-fluid">
    <div class="layui-row layui-col-space15">
        <div class="layui-col-md12">
            <div class="layui-card">
                <div class="ibox-title">
                    <h3>当前位置：系统设置 &raquo; 应用账号配置</h3>
                </div>
            </div>
        </div>
        <div class="layui-row layui-col-space15">
            <div class="layui-col-md12">
                <div class="layui-card">
                    <div class="layui-card-body">
                        <div class="layui-tab">
                            <h3><strong style="color:red;">友情提示：该页面账号配置参数由专业技术人员配置，请勿随意修改！请妥善保管，请勿泄露！</strong></h3>
                            <ul class="nav nav-tabs">
                                <li class="active">
                                    <a data-toggle="tab" href="#tab-1" aria-expanded="true">淘宝客账号</a>
                                </li>
                                <li class="">
                                    <a data-toggle="tab" href="#tab-2" aria-expanded="false">拼多多账号</a>
                                </li>
                                <li class="">
                                    <a data-toggle="tab" href="#tab-3" aria-expanded="false">极光推送账号</a>
                                </li>
                                <li class="">
                                    <a data-toggle="tab" href="#tab-4" aria-expanded="false">支付宝账号</a>
                                </li>
                                <li class="">
                                    <a data-toggle="tab" href="#tab-5" aria-expanded="false">京东账号</a>
                                </li>
                                <li class="">
                                    <a data-toggle="tab" href="#tab-6" aria-expanded="false">联盟推广位</a>
                                </li>
                                <li class="">
                                    <a data-toggle="tab" href="#tab-7" aria-expanded="false">短信配置</a>
                                </li>
                                <li class="">
                                    <a data-toggle="tab" href="#tab-8" aria-expanded="false">唯品会账号</a>
                                </li>
                            </ul>
                            <form action="__ACTION__"  class="form-horizontal" method="post" enctype="multipart/form-data">
                                <input type="hidden" name="old_tbk_appid" value="{$msg['tbk_appid']}">
                                <input type="hidden" name="old_tbk_appkey" value="{$msg['tbk_appkey']}">
                                <input type="hidden" name="old_tbk_appsecret" value="{$msg['tbk_appsecret']}">
                                <input type="hidden" name="old_tbk_pid" value="{$msg['tbk_pid']}">
                                <input type="hidden" name="old_tbk_adzone_id" value="{$msg['tbk_adzone_id']}">
                                <input type="hidden" name="old_tbk_relation_pid" value="{$msg['tbk_relation_pid']}">
                                <input type="hidden" name="old_wy_appkey" value="{$msg['wy_appkey']}">
                                <input type="hidden" name="old_adzone_id" value="{$msg['adzone_id']}">
                                <input type="hidden" name="old_auth_code" value="{$msg['auth_code']}">

                                <input type="hidden" name="old_pdd_client_id" value="{$msg['pdd_client_id']}">
                                <input type="hidden" name="old_pdd_client_secret" value="{$msg['pdd_client_secret']}">
                                <input type="hidden" name="old_pdd_pid" value="{$msg['pdd_pid']}">

                                <input type="hidden" name="old_jpush_key" value="{$msg['jpush_key']}">
                                <input type="hidden" name="old_jpush_secret" value="{$msg['jpush_secret']}">

                                <input type="hidden" name="old_alipay_appid" value="{$msg['alipay_appid']}">
                                <input type="hidden" name="old_alipay_private_key" value="{$msg['alipay_private_key']}">
                                <input type="hidden" name="old_alipay_public_key" value="{$msg['alipay_public_key']}">

                                <input type="hidden" name="old_vip_appkey" value="{$msg['vip_appkey']}">
                                <input type="hidden" name="old_vip_appsecret" value="{$msg['vip_appsecret']}">
                                <div class="tab-content">
                                    <!-- 淘宝客账号   -->
                                    <div id="tab-1" class="tab-pane active" style="padding-top: 10px">
                                        <div class="layui-form-item">
                                            <label class="layui-form-label" style="width: 190px;">淘宝客AppID</label>
                                            <div class="layui-input-block">
                                                <input type="text" class="layui-input" name="tbk_appid" value="{$msg['tbk_appid']}" placeholder="" style="width: 94%;">
                                                <span class="help-block m-b-none text-danger">请填写淘宝客AppID</span>
                                            </div>
                                        </div>
                                        <div class="layui-form-item">
                                            <label class="layui-form-label" style="width: 190px;">淘宝客App key</label>
                                            <div class="layui-input-block">
                                                <input type="text" class="layui-input" name="tbk_appkey" value="{$msg['tbk_appkey']}" placeholder="" style="width: 94%;">
                                            </div>
                                        </div>
                                        <div class="layui-form-item">
                                            <label class="layui-form-label" style="width: 190px;">淘宝客App secret</label>
                                            <div class="layui-input-block">
                                                <input type="text" class="layui-input" name="tbk_appsecret" value="{$msg['tbk_appsecret']}" placeholder="" style="width: 94%;">
                                            </div>
                                        </div>
                                        <div class="layui-form-item">
                                            <label class="layui-form-label" style="width: 190px;">淘宝客PID</label>
                                            <div class="layui-input-block">
                                                <input type="text" class="layui-input" name="tbk_pid" value="{$msg['tbk_pid']}" placeholder="" style="width: 94%;">
                                            </div>
                                        </div>
                                        <div class="layui-form-item">
                                            <label class="layui-form-label" style="width: 190px;">淘宝客广告位ID</label>
                                            <div class="layui-input-block">
                                                <input type="text" class="layui-input" name="tbk_adzone_id" value="{$msg['tbk_adzone_id']}" placeholder="" style="width: 94%;">
                                            </div>
                                        </div>
                                        <div class="layui-form-item">
                                            <label class="layui-form-label" style="width: 190px;">淘宝客渠道专用PID</label>
                                            <div class="layui-input-block">
                                                <input type="text" class="layui-input" name="tbk_relation_pid" value="{$msg['tbk_relation_pid']}" placeholder="" style="width: 94%;">
                                            </div>
                                        </div>
                                        <div class="layui-form-item">
                                            <label class="layui-form-label" style="width: 190px;">维易淘宝客key</label>
                                            <div class="layui-input-block">
                                                <input type="text" class="layui-input" name="wy_appkey" value="{$msg['wy_appkey']}" placeholder="" style="width: 94%;">
                                            </div>
                                        </div>
                                        <div class="layui-form-item">
                                            <label class="layui-form-label" style="width: 190px;">广告位ID</label>
                                            <div class="layui-input-block">
                                                <input type="text" class="layui-input" name="adzone_id" value="{$msg['adzone_id']}" placeholder="" style="width: 94%;">
                                            </div>
                                        </div>
                                        <div class="layui-form-item">
                                            <label class="layui-form-label" style="width: 190px;">联盟授权码</label>
                                            <div class="layui-input-block">
                                                <input type="text" class="layui-input" name="auth_code" value="{$msg['auth_code']}" placeholder="" style="width: 94%;">
                                            </div>
                                        </div>
                                    </div>
                                    <!-- 淘宝客账号   -->

                                    <!-- 拼多多账号  -->
                                    <div id="tab-2" class="tab-pane" style="padding-top: 10px">
                                        <div class="layui-form-item">
                                            <label class="layui-form-label" style="width: 190px;">拼多多client_id</label>
                                            <div class="layui-input-block">
                                                <input type="text" class="layui-input" name="pdd_client_id" value="{$msg['pdd_client_id']}" style="width: 94%;">
                                            </div>
                                        </div>
                                        <div class="layui-form-item">
                                            <label class="layui-form-label" style="width: 190px;">拼多多client_secret</label>
                                            <div class="layui-input-block">
                                                <input type="text" class="layui-input" name="pdd_client_secret" value="{$msg['pdd_client_secret']}" style="width: 94%;">
                                            </div>
                                        </div>
                                        <div class="layui-form-item">
                                            <label class="layui-form-label" style="width: 190px;">拼多多推广位pid</label>
                                            <div class="layui-input-block">
                                                <input type="text" class="layui-input" name="pdd_pid" value="{$msg['pdd_pid']}" style="width: 94%;">
                                            </div>
                                        </div>
                                    </div>
                                    <!-- 拼多多账号  -->

                                    <!-- 极光推送账号  -->
                                    <div id="tab-3" class="tab-pane" style="padding-top: 10px">
                                        <div class="layui-form-item">
                                            <label class="layui-form-label" style="width: 190px;">极光推送key</label>
                                            <div class="layui-input-block">
                                                <input type="text" class="layui-input" name="jpush_key" value="{$msg['jpush_key']}" style="width: 94%;">
                                            </div>
                                        </div>
                                        <div class="layui-form-item">
                                            <label class="layui-form-label" style="width: 190px;">极光推送secret</label>
                                            <div class="layui-input-block">
                                                <input type="text" class="layui-input" name="jpush_secret" value="{$msg['jpush_secret']}" style="width: 94%;">
                                            </div>
                                        </div>
                                    </div>
                                    <!-- 极光推送账号  -->

                                    <!-- 支付宝账号  -->
                                    <div id="tab-4" class="tab-pane" style="padding-top: 10px">
                                        <div class="layui-form-item">
                                            <label class="layui-form-label" style="width: 190px;">支付宝appid</label>
                                            <div class="layui-input-block">
                                                <input type="text" class="layui-input" name="alipay_appid" value="{$msg['alipay_appid']}" style="width: 94%;">
                                            </div>
                                        </div>
                                        <div class="layui-form-item">
                                            <label class="layui-form-label" style="width: 190px;">支付宝私钥</label>
                                            <div class="layui-input-block">
                                                <input type="text" class="layui-input" name="alipay_private_key" value="{$msg['alipay_private_key']}" style="width: 94%;">
                                            </div>
                                        </div>
                                        <div class="layui-form-item">
                                            <label class="layui-form-label" style="width: 190px;">支付宝公钥</label>
                                            <div class="layui-input-block">
                                                <input type="text" class="layui-input" name="alipay_public_key" value="{$msg['alipay_public_key']}" style="width: 94%;">
                                            </div>
                                        </div>
                                    </div>


                                    <!-- 京东账号  -->
                                    <div id="tab-5" class="tab-pane" style="padding-top: 10px">
                                        <div class="layui-form-item">
                                            <label class="layui-form-label" style="width: 190px;">京东用户id</label>
                                            <div class="layui-input-block">
                                                <input type="text" class="layui-input" name="jd_unionid" value="{$msg['jd_unionid']}" style="width: 94%;">
                                            </div>
                                        </div>
                                        <div class="layui-form-item">
                                            <label class="layui-form-label" style="width: 190px;">授权key</label>
                                            <div class="layui-input-block">
                                                <input type="text" class="layui-input" name="jd_auth_key" value="{$msg['jd_auth_key']}" style="width: 94%;">
                                            </div>
                                        </div>

                                        <div class="layui-form-item">
                                            <label class="layui-form-label" style="width: 190px;">安卓appkey</label>
                                            <div class="layui-input-block">
                                                <input type="text" class="layui-input" name="android_appkey" value="{$msg['android_appkey']}" style="width: 94%;">
                                            </div>
                                        </div>

                                        <div class="layui-form-item">
                                            <label class="layui-form-label" style="width: 190px;">安卓appsecret</label>
                                            <div class="layui-input-block">
                                                <input type="text" class="layui-input" name="android_appsecret" value="{$msg['android_appsecret']}" style="width: 94%;">
                                            </div>
                                        </div>

                                        <div class="layui-form-item">
                                            <label class="layui-form-label" style="width: 190px;">IOS appkey</label>
                                            <div class="layui-input-block">
                                                <input type="text" class="layui-input" name="ios_appkey" value="{$msg['ios_appkey']}" style="width: 94%;">
                                            </div>
                                        </div>
                                        <div class="layui-form-item">
                                            <label class="layui-form-label" style="width: 190px;">IOS appsecret</label>
                                            <div class="layui-input-block">
                                                <input type="text" class="layui-input" name="ios_appsecret" value="{$msg['ios_appsecret']}" style="width: 94%;">
                                            </div>
                                        </div>

                                    </div>

                                    <!-- 联盟推广位置  -->
                                    <div id="tab-6" class="tab-pane" style="padding-top: 10px">
                                        <div class="layui-form-item">
                                            <label class="layui-form-label" style="width: 190px;">pid 1</label>
                                            <div class="layui-input-block">
                                                <input type="text" class="layui-input" name="tk_pid[1]" value="{$msg['tk_pid'][1]}" style="width: 94%;">
                                            </div>
                                        </div>
                                        <div class="layui-form-item">
                                            <label class="layui-form-label" style="width: 190px;">pid 2</label>
                                            <div class="layui-input-block">
                                                <input type="text" class="layui-input" name="tk_pid[2]" value="{$msg['tk_pid'][2]}" style="width: 94%;">
                                            </div>
                                        </div>
                                        <div class="layui-form-item">
                                            <label class="layui-form-label" style="width: 190px;">pid 3</label>
                                            <div class="layui-input-block">
                                                <input type="text" class="layui-input" name="tk_pid[3]" value="{$msg['tk_pid'][3]}" style="width: 94%;">
                                            </div>
                                        </div>
                                        <div class="layui-form-item">
                                            <label class="layui-form-label" style="width: 190px;">pid 4</label>
                                            <div class="layui-input-block">
                                                <input type="text" class="layui-input" name="tk_pid[4]" value="{$msg['tk_pid'][4]}" style="width: 94%;">
                                            </div>
                                        </div>
                                        <div class="layui-form-item">
                                            <label class="layui-form-label" style="width: 190px;">pid 5</label>
                                            <div class="layui-input-block">
                                                <input type="text" class="layui-input" name="tk_pid[5]" value="{$msg['tk_pid'][5]}" style="width: 94%;">
                                            </div>
                                        </div>
                                        <div class="layui-form-item">
                                            <label class="layui-form-label" style="width: 190px;">pid 6</label>
                                            <div class="layui-input-block">
                                                <input type="text" class="layui-input" name="tk_pid[6]" value="{$msg['tk_pid'][6]}" style="width: 94%;">
                                            </div>
                                        </div>
                                        <div class="layui-form-item">
                                            <label class="layui-form-label" style="width: 190px;">pid 7</label>
                                            <div class="layui-input-block">
                                                <input type="text" class="layui-input" name="tk_pid[7]" value="{$msg['tk_pid'][7]}" style="width: 94%;">
                                            </div>
                                        </div>
                                        <div class="layui-form-item">
                                            <label class="layui-form-label" style="width: 190px;">pid 8</label>
                                            <div class="layui-input-block">
                                                <input type="text" class="layui-input" name="tk_pid[8]" value="{$msg['tk_pid'][8]}" style="width: 94%;">
                                            </div>
                                        </div>
                                        <div class="layui-form-item">
                                            <label class="layui-form-label" style="width: 190px;">pid 9</label>
                                            <div class="layui-input-block">
                                                <input type="text" class="layui-input" name="tk_pid[9]" value="{$msg['tk_pid'][9]}" style="width: 94%;">
                                            </div>
                                        </div>
                                        <div class="layui-form-item">
                                            <label class="layui-form-label" style="width: 190px;">pid 10</label>
                                            <div class="layui-input-block">
                                                <input type="text" class="layui-input" name="tk_pid[10]" value="{$msg['tk_pid'][10]}" style="width: 94%;">
                                            </div>
                                        </div>
                                    </div>

                                    <!-- 支付宝账号  -->
                                    <div id="tab-7" class="tab-pane" style="padding-top: 10px">
                                        <div class="layui-form-item">
                                            <label class="layui-form-label" style="width: 190px;">美圣融云账号id</label>
                                            <div class="layui-input-block">
                                                <input type="text" class="layui-input" name="sms_sid" value="{$msg['sms_sid']}" style="width: 94%;">
                                            </div>
                                        </div>
                                        <div class="layui-form-item">
                                            <label class="layui-form-label" style="width: 190px;">美圣融云apikey</label>
                                            <div class="layui-input-block">
                                                <input type="text" class="layui-input" name="sms_apikey" value="{$msg['sms_apikey']}" style="width: 94%;">
                                            </div>
                                        </div>
                                        <div class="layui-form-item">
                                            <label class="layui-form-label" style="width: 190px;">短信模版</label>
                                            <div class="layui-input-block">
                                                <input type="text" class="layui-input" name="sms_tpl" value="{$msg['sms_tpl']}" style="width: 94%;">
                                            </div>
                                        </div>
                                    </div>

                                    <!-- 唯品会账号  -->
                                    <div id="tab-8" class="tab-pane" style="padding-top: 10px">
                                        <div class="layui-form-item">
                                            <label class="layui-form-label" style="width: 190px;">唯品会账号appkey</label>
                                            <div class="layui-input-block">
                                                <input type="text" class="layui-input" name="vip_appkey" value="{$msg['vip_appkey']}" style="width: 94%;">
                                            </div>
                                        </div>
                                        <div class="layui-form-item">
                                            <label class="layui-form-label" style="width: 190px;">唯品会账号appsecret</label>
                                            <div class="layui-input-block">
                                                <input type="text" class="layui-input" name="vip_appsecret" value="{$msg['vip_appsecret']}" style="width: 94%;">
                                            </div>
                                        </div>
                                    </div>


                                    <!--                        <div class="form-group">-->
                                    <!--                                <div class="col-sm-4 col-sm-offset-2">-->
                                    <!--                                    <button class="btn btn-primary" type="submit"><i class="fa fa-check"></i>&nbsp;编辑</button>-->
                                    <!--                                    <button class="btn btn-white" type="reset"><i class="fa fa-refresh"></i>&nbsp;重置</button>-->
                                    <!--                                </div>-->
                                    <!--                         </div>-->
                                    <div class="layui-form-item layui-layout-admin">
                                        <div class="layui-input-block">
                                            <button class="layui-btn" type="submit"><i class="fa fa-check"></i>&nbsp;编辑</button>
                                            <button class="layui-btn layui-btn-primary" type="reset"><i class="fa fa-refresh"></i>&nbsp;重置</button>
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
</div>
</body>
</html>