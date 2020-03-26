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
    <link href="__ADMIN_CSS__/img.css" rel="stylesheet">
    <link rel="stylesheet" href="__LAYUIADMIN__/layui/css/layui.css" media="all">
    <link rel="stylesheet" href="__LAYUIADMIN__/style/admin.css" media="all">

    <script src="__ADMIN_JS__/jquery.min.js?v=2.1.4"></script>
    <script src="__ADMIN_JS__/bootstrap.min.js?v=3.3.6"></script>
    <script src="__ADMIN_JS__/plugins/iCheck/icheck.min.js"></script>

    <!-- Sweet Alert -->
    <link href="__ADMIN_CSS__/plugins/sweetalert/sweetalert.css" rel="stylesheet">
    <script src="__ADMIN_JS__/plugins/sweetalert/sweetalert.min.js"></script>
    <!-- Sweet Alert -->
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
                    <h3>当前位置：订单管理 &raquo; 退款成功订单<a class="pull-right" href="__CONTROLLER__/refundSuccess">返回退款成功订单列表 <i
                                    class="fa fa-angle-double-right"></i></a></h3>
                </div>
            </div>
        </div>
        <div class="layui-row layui-col-space15">
            <div class="layui-col-md12">
                <div class="layui-card">
                    <div class="layui-card-body">
                        <ul class="nav nav-tabs">
                            <li class="active">
                                <a data-toggle="tab" href="#tab-1" aria-expanded="true">订单基本信息</a>
                            </li>
                            <li class="">
                                <a data-toggle="tab" href="#tab-2" aria-expanded="false">订单明细</a>
                            </li>
                        </ul>
                        <form action="__ACTION__/id/{$msg.id}" class="form-horizontal" method="post"
                              enctype="multipart/form-data">
                            <div class="tab-content">
                                <!-- 订单基本信息  -->
                                <div id="tab-1" class="tab-pane active" style="padding-top: 10px">
                                    <div class="layui-form-item">
                                        <label class="layui-form-label" style="width: 100px;">订单名称</label>
                                        <div class="layui-input-block">
                                            <input type="text" class="layui-input" name="title" value="{$msg['title']}"
                                                   placeholder="">
                                            <span class="help-block m-b-none text-danger">必填</span>
                                        </div>
                                    </div>
                                    <div class="layui-form-item">
                                        <label class="layui-form-label" style="width: 100px;">总价</label>
                                        <div class="layui-input-block">
                                            <input type="text" class="layui-input" name="allprice"
                                                   value="{$msg['allprice']}">
                                            <span class="help-block m-b-none text-danger">必填</span>
                                        </div>
                                    </div>
                                    <div class="layui-form-item">
                                        <label class="layui-form-label" style="width: 100px;">收件人</label>
                                        <div class="layui-input-block">
                                            <input type="text" class="layui-input" name="consignee"
                                                   value="{$msg['consignee']}" placeholder="">
                                        </div>
                                    </div>
                                    <div class="layui-form-item">
                                        <label class="layui-form-label" style="width: 100px;">联系电话</label>
                                        <div class="layui-input-block">
                                            <input type="text" class="layui-input" name="contact_number"
                                                   value="{$msg['contact_number']}" placeholder="">
                                        </div>
                                    </div>
                                    <div class="layui-form-item">
                                        <label class="layui-form-label" style="width: 100px;">收货地址</label>
                                        <div class="layui-input-block">
                                            <input type="text" class="layui-input" name="address"
                                                   value="{$msg['address']}" placeholder="">
                                        </div>
                                    </div>
                                    <div class="layui-form-item">
                                        <label class="layui-form-label" style="width: 100px;">邮政编码</label>
                                        <div class="layui-input-block">
                                            <input type="text" class="layui-input" name="postcode"
                                                   value="{$msg['postcode']}" placeholder="">
                                        </div>
                                    </div>
                                    <div class="layui-form-item">
                                        <label class="layui-form-label" style="width: 100px;">单位名称</label>
                                        <div class="layui-input-block">
                                            <input type="text" class="layui-input" name="company"
                                                   value="{$msg['company']}" placeholder="">
                                        </div>
                                    </div>
                                    <div class="layui-form-item">
                                        <label class="layui-form-label" style="width: 100px;">快递公司</label>
                                        <div class="layui-input-block">
                                            <select class="layui-input m-b" name="logistics">
                                                <option value="">-请选择快递公司-</option>
                                                <?php
                                                $logistics_arr = json_decode(logistics, true);
                                                foreach ($logistics_arr as $k => $v) {
                                                    if ($k == $msg['logistics']) {
                                                        $select = 'selected';
                                                    } else {
                                                        $select = '';
                                                    }
                                                    echo '<option value="' . $k . '" ' . $select . '>' . $v . '</option>';
                                                }
                                                ?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="layui-form-item">
                                        <label class="layui-form-label" style="width: 100px;">快递单号</label>
                                        <div class="layui-input-block">
                                            <input type="text" class="layui-input" name="express_number"
                                                   value="{$msg['express_number']}" placeholder="">
                                            <span class="help-block m-b-none text-danger">请填写快递单号便于会员查看物流</span>
                                        </div>
                                    </div>
                                    <div class="layui-form-item">
                                        <label class="layui-form-label" style="width: 100px;">订单状态</label>
                                        <div class="layui-input-block">
                                            <input type="text" class="layui-input" name="" value="已确认收货" placeholder=""
                                                   disabled>
                                        </div>
                                    </div>
                                    <div class="layui-form-item">
                                        <label class="layui-form-label" style="width: 100px;">支付方式</label>
                                        <div class="layui-input-block">
                                            <select class="layui-input m-b" name="pay_method">
                                                <option value="">-请选择支付方式-</option>
                                                <option value="alipay" <?php if ($msg['pay_method'] == 'alipay') {
                                                    echo 'selected';
                                                } ?> >支付宝支付
                                                </option>
                                                <option value="wxpay" <?php if ($msg['pay_method'] == 'wxpay') {
                                                    echo 'selected';
                                                } ?> >微信支付
                                                </option>
                                                <option value="balance" <?php if ($msg['pay_method'] == 'balance') {
                                                    echo 'selected';
                                                } ?> >余额支付
                                                </option>
                                                <option value="offline" <?php if ($msg['pay_method'] == 'offline') {
                                                    echo 'selected';
                                                } ?> >线下支付
                                                </option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="layui-form-item">
                                        <label class="layui-form-label" style="width: 100px;">下单时间</label>
                                        <div class="layui-input-block">
                                            <input type="text" class="layui-input" name="" value="{$msg.create_time}"
                                                   disabled>
                                        </div>
                                    </div>
                                    <div class="layui-form-item">
                                        <label class="layui-form-label" style="width: 100px;">支付时间</label>
                                        <div class="layui-input-block">
                                            <input type="text" class="layui-input" name="" value="{$msg.pay_time}"
                                                   disabled>
                                        </div>
                                    </div>
                                    <div class="layui-form-item">
                                        <label class="layui-form-label" style="width: 100px;">发货时间</label>
                                        <div class="layui-input-block">
                                            <input type="text" class="layui-input" name="" value="{$msg.deliver_time}"
                                                   disabled>
                                        </div>
                                    </div>
                                    <div class="layui-form-item">
                                        <label class="layui-form-label" style="width: 100px;">申请退款时间</label>
                                        <div class="layui-input-block">
                                            <input type="text" class="layui-input" name="" value="{$msg.refund_time}"
                                                   disabled>
                                        </div>
                                    </div>
                                    <div class="layui-form-item">
                                        <label class="layui-form-label" style="width: 100px;">申请退款理由</label>
                                        <div class="layui-input-block">
                                            <textarea name="" placeholder="" class="layui-input" style="height:80px;">{$msg['drawback_reason']}</textarea>
                                        </div>
                                    </div>
                                    <div class="layui-form-item">
                                        <label class="layui-form-label" style="width: 100px;">申请退款凭证图片</label>
                                        <div class="layui-input-block">
                                            <!--imgContainer-->
                                            <div class="imgContainer">
                                                <ul class="clearfix">
                                                    <?php
                                                    if ($msg['drawback_img']) {
                                                        $img_arr = json_decode($msg['drawback_img'], true);
                                                        $img_num = count($img_arr);
                                                        for ($i = 0; $i < $img_num; $i++) {
                                                            echo '<li>
                                                    <span class="imgbox"><img src="' . $img_arr[$i] . '"/></span>
                                                </li>';
                                                        }
                                                    }
                                                    ?>
                                                </ul>
                                            </div>
                                            <!--imgContainer-->
                                        </div>
                                    </div>
                                    <div class="layui-form-item">
                                        <label class="layui-form-label" style="width: 100px;">退款成功时间</label>
                                        <div class="layui-input-block">
                                            <input type="text" class="layui-input" name="refund_success_time"
                                                   value="{$msg.refund_success_time}" disabled>
                                        </div>
                                    </div>
                                </div>
                                <!-- 订单基本信息  -->

                                <!-- 订单明细  -->
                                <div id="tab-2" class="tab-pane" style="padding-top: 10px">
                                    <?php
                                    foreach ($msg['detail'] as $l) {
                                        //单价
                                        $price = $l['price'];
                                        //总价
                                        $allprice = $l['allprice'];
                                        $goods_str = $l['goods_name'] . '，单价：￥' . $price . '，数量：' . $l['num'] . '，总价：￥' . $allprice . '，备注：' . $l['sku'];
                                        echo '<div class="layui-form-item">
                                <label class="layui-form-label" style="width: 100px;">商品名称：</label>
                                <div class="layui-input-block">
                                <input type="text" class="layui-input" name="keywords" value="' . $goods_str . '" disabled>  
                               </div>
                                </div>';
                                    }
                                    ?>
                                </div>
                                <!-- 订单明细  -->

                                <div class="layui-form-item layui-layout-admin">
                                    <div class="layui-input-block">
                                        <button class="layui-btn" type="submit"><i class="fa fa-check"></i>&nbsp;编辑订单
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