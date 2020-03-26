<script src="__JS__/area.js" type="text/javascript" charset="utf-8"></script>
<script>
    $(document).ready(function () {
        $('#cat_id').change(function () {
            var cat_id = $('#cat_id').val();
            if (cat_id) {
                //根据商品分类ID获取商品列表
                $.ajax({
                    type: "POST",
                    url: "/dmooo.php/Goods/getGoodsList",
                    dataType: "json",
                    data: "cat_id=" + cat_id,
                    success: function (list) {
                        if (list != '0') {
                            var result = '<option value="">-请选择商品-</option>';
                            $.each(list, function (index, item) {
                                result += '<option value="' + item.goods_id + '">' + item.title + '-单价：￥' + item.price / 100 + '</option>';
                            });
                            $('#goods_id').html(result);
                        } else {
                            $('#goods_id').html('');
                        }
                    }
                });
            }
        });

        $('#group_id').change(function () {
            var group_id = $('#group_id').val();
            if (group_id) {
                //根据用户组ID获取用户列表
                $.ajax({
                    type: "POST",
                    url: "/dmooo.php/User/getUserList",
                    dataType: "json",
                    data: "group_id=" + group_id,
                    success: function (list) {
                        if (list != '0') {
                            var result = '<option value="">-请选择购买用户-</option>';
                            $.each(list, function (index, item) {
                                result += '<option value="' + item.id + '">' + item.username + '-' + item.phone + '-' + item.email + '</option>';
                            });
                            $('#user_id').html(result);
                        } else {
                            $('#user_id').html('');
                        }
                    }
                });
            }
        });
    });
</script>
<section class="rt_wrap content mCustomScrollbar">
    <div class="rt_content">
        <section>
            <h2><strong style="color:grey;">当前位置：订单管理 &raquo; 人工录入订单</strong></h2>
            <form action="__ACTION__/id/{$msg.id}" method="post">
                <ul class="ulColumn2">
                    <li>
                        <span class="item_name" style="width:130px;">所属商品分类：</span>
                        <select class="select" name="cat_id" id="cat_id">
                            <option value="">-请选择商品分类-</option>
                            <?php
                            foreach ($GoodsCatList as $v) {
                                echo '<option value="' . $v['cat_id'] . '" style="margin-left:55px;">' . $v['lefthtml'] . '' . $v['cat_name'] . '</option>';
                            }
                            ?>
                        </select>
                    </li>
                    <li>
                        <span class="item_name" style="width:130px;">选择商品：</span>
                        <select class="select" name="goods_id" id="goods_id">
                        </select>
                    </li>
                    <li>
                        <span class="item_name" style="width:130px;">所属用户组：</span>
                        <select class="select" name="group_id" id="group_id">
                            <option value="">-请选择用户组-</option>
                            <?php
                            foreach ($UserGroupList as $v) {
                                echo '<option value="' . $v['id'] . '">' . $v['title'] . '</option>';
                            }
                            ?>
                        </select>
                    </li>
                    <li>
                        <span class="item_name" style="width:130px;">购买用户：</span>
                        <select class="select" name="user_id" id="user_id">
                        </select>
                    </li>
                    <li>
                        <span class="item_name" style="width:130px;">购买数量：</span>
                        <input type="text" class="textbox textbox_295" name="num" value="" placeholder=""/>
                        <span class="errorTips">必填</span>
                    </li>
                    <li class="clearfix">
                        <span class="item_name" style="width:130px;">地址：</span>
                        <select name="province" id="province" class="select"></select>
                        <select name="city" id="city" runat="server" class="select"></select>
                        <select name="county" id="county" runat="server" class="select"></select>
                        <script type="text/javascript">var optSelect = ["江苏省", "南京市", ""];
                            setup();</script>
                    </li>
                    <li>
                        <span class="item_name" style="width:130px;">详细地址：</span>
                        <input type="text" class="textbox textbox_295" name="detail_address" value="" placeholder=""/>
                        <span class="errorTips">必填</span>
                    </li>
                    <li>
                        <span class="item_name" style="width:130px;">邮政编码：</span>
                        <input type="text" class="textbox textbox_295" name="postcode" value="" placeholder=""/>
                    </li>
                    <li>
                        <span class="item_name" style="width:130px;">收件人：</span>
                        <input type="text" class="textbox textbox_295" name="consignee" value="" placeholder=""/>
                    </li>
                    <li>
                        <span class="item_name" style="width:130px;">联系电话：</span>
                        <input type="text" class="textbox textbox_295" name="contact_number" value="" placeholder=""/>
                    </li>
                    <li>
                        <span class="item_name" style="width:130px;">单位名称：</span>
                        <input type="text" class="textbox textbox_295" name="company" value="" placeholder=""/>
                    </li>
                    <li>
                        <span class="item_name" style="width:130px;">快递单号：</span>
                        <input type="text" class="textbox textbox_295" name="express_number" value="" placeholder=""/>
                    </li>
                    <li>
                        <span class="item_name" style="width:130px;">订单状态：</span>
                        <select name="status" class="select">
                            <option value="1">未支付</option>
                            <option value="2">已支付</option>
                            <option value="3">已发货</option>
                        </select>
                    </li>
                    <li>
                        <span class="item_name" style="width:130px;">支付方式：</span>
                        <select name="pay_method" class="select">
                            <option value="">-请选择支付方式-</option>
                            <option value="alipay">支付宝支付</option>
                            <option value="wxpay">微信支付</option>
                            <option value="offline">线下支付</option>
                        </select>
                    </li>
                    <li>
                        <span class="item_name" style="width:130px;"></span>
                        <input type="submit" class="link_btn" value="确认录入"/>
                    </li>
                </ul>
            </form>
        </section>
        <!--tabStyle-->
    </div>
</section>