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

    <!-- Sweet Alert -->
    <link href="__ADMIN_CSS__/plugins/sweetalert/sweetalert.css" rel="stylesheet">
    <!-- Sweet Alert -->
    <script src="__ADMIN_JS__/jquery.min.js?v=2.1.4"></script>
    <script src="__ADMIN_JS__/bootstrap.min.js?v=3.3.6"></script>
    <script src="__ADMIN_JS__/content.min.js?v=1.0.0"></script>
    <script src="__ADMIN_JS__/plugins/sweetalert/sweetalert.min.js"></script>

    <link rel="stylesheet" type="text/css" href="__CSS__/page.css"/>

</head>

<body class="gray-bg">
<div class="layui-fluid">
    <div class="layui-row layui-col-space15">
        <div class="layui-col-md12">
            <div class="layui-card">
                <div class="ibox-title">
                    <h3>当前位置： 代理商系统 &raquo; 查看会员列表</h3>
                </div>
                <div class="ibox-content">
                    <form action="__ACTION__" method="get" role="form" class="form-inline pull-left">
                        <input type="hidden" name="p" value="1">
                        <input type="hidden" name="group_id" value="{$group_id}">
                        用户名/邮箱、手机号码：<input type="text" placeholder="" name="search" class="form-control">
                        会员组：<select class="form-control" name="group_id">
                            <option value="">请选择会员组</option>
                            <?php
                            foreach ($glist as $l) {
                                echo '<option value="' . $l['id'] . '">' . $l['title'] . '</option>';
                            }
                            ?>
                        </select>
                        <button class="layui-btn layuiadmin-btn-admin" type="submit">查询</button>
                        <!--                        <button class="btn btn-primary" type="submit">查询</button>-->
                    </form>
                    <div class="layui-row layui-col-space15">
                        <table class="layui-table">
                            <thead>
                            <tr>
                                <th>ID</th>
                                <th>所属分组</th>
                                <th>手机号码</th>
                                <th>余额</th>
                                <th>可提现余额</th>
                                <th>本月预估</th>
                                <th>本月结算</th>
                                <th>推荐人数</th>
                                <th>上级/推荐</th>
                                <th>注册时间</th>
                                <th>手机归属地</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php
                            $group = new \Common\Model\UserGroupModel();
                            $User = new \Common\Model\UserModel();
                            ?>
                            <foreach name="list" item="l">
                                <tr>
                                    <td>{$l['uid']}</td>
                                    <td>
                                        <?php
                                        //会员组名称
                                        $groupMsg = $group->getGroupMsg($l['group_id']);
                                        echo $groupMsg['title'];
                                        ?>
                                    </td>
                                    <td>
                                        <?php
                                        //隐藏手机号码
                                        echo substr_replace($l['phone'], '**', 7, 2);
                                        ?>
                                    </td>
                                    <td>￥{$l['balance']}</td>
                                    <?php
                                    $res_balance = $User->getDrawBalance($l['uid']);
                                    ?>
                                    <td>￥{$res_balance.draw_balance}</td>
                                    <td>￥{$res_balance.amount_current}</td>
                                    <td>￥{$res_balance.amount_finish}</td>
                                    <td>
                                        <?php
                                        $uid = $l['uid'];
                                        $referrer_num = $User->where("referrer_id='$uid'")->count();
                                        echo $referrer_num;
                                        ?>
                                    </td>
                                    <td>
                                        <?php
                                        if ($l['referrer_id']) {
                                            $referrerMsg = $User->getUserDetail($l['referrer_id']);
                                            //隐藏手机号码
                                            echo substr_replace($referrerMsg['account'], '**', 7, 2);
                                        }
                                        ?>
                                    </td>
                                    <td>{$l['register_time']}</td>
                                    <td>{$l['phone_province']}-{$l['phone_city']}</td>
                                </tr>
                            </foreach>
                            </tbody>
                        </table>
                        <div class="pages">{$page}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</body>
</html>