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
    <link rel="stylesheet" type="text/css" href="__CSS__/page.css" />
</head>

<body class="gray-bg">
<div class="layui-fluid">
    <div class="layui-row layui-col-space15">
        <div class="layui-col-md12">
            <div class="layui-card">
                <div class="ibox-title">
                    <h3>当前位置： 营销中心 &raquo; 拉新活动 &raquo; {$rname} 提现记录<a class="pull-right" href="__MODULE__/Rookie/index">返回上一页 <i class="fa fa-angle-double-right"></i></a></h3>
                </div>
                <div class="ibox-content">
                    <form action="__ACTION__/id/{$id}" method="get" role="form" class="form-inline pull-left">
                        <input type="hidden" name="p" value="1">
                        搜索用户：<input type="text" placeholder="" name="search" value="{$search}" class="form-control">
                        是否兑换：<select class="form-control" name="is_ex">
                            <?php
                            if ($is_ex == 'Y') {
                                $is_ex1 = 'selected';
                            }
                            if ($is_ex == 'N') {
                                $is_ex2 = 'selected';
                            }
                            ?>
                            <option value="">请选择</option>
                            <option value="N" {$is_ex2}>未兑换</option>
                            <option value="Y" {$is_ex1}>已兑换</option>
                        </select>
<!--                        <button class="btn btn-primary" type="submit">查询</button>-->
                        <button class="layui-btn layuiadmin-btn-admin" lay-submit lay-filter="LAY-user-back-search">查询</button>&nbsp;
                    </form>
                    <div class="layui-row layui-col-space15">
                        <table class="layui-table">
                            <thead>
                            <tr>
                                <th>用户名</th>
                                <th>拉新人数</th>
                                <th>奖励</th>
                                <th>是否兑换</th>
                                <th>兑换时间</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php
                            $Rookie = new \Common\Model\RookieModel();
                            $RookieDetails = new \Common\Model\RookieDetailsModel();
                            // 根据奖励方式判断奖励信息
                            $ex_type = $Rookie->where("id = $id")->getField('ex_type');
                            ?>
                            <foreach name="data" item="value">
                                <tr>
                                    <td>{$value.username}</td>
                                    <td>{$value.num}</td>
                                    <?php
                                    // 查询奖励
                                    $num = $value['num'];
                                    if ($ex_type == 1) {
                                        $reward = $RookieDetails->getPeopleReward($id, $num);
                                    } else if ($ex_type == 2) {
                                        $reward = $RookieDetails->getReward($id, $num);
                                    }
                                    $reward = $reward[0].$reward[2];
                                    // 处理是否兑换
                                    $is_ex = $value['is_ex'];
                                    if ($is_ex == 'Y') {
                                        $ex_info = '<a href="javascript:;" class="inner_btn_red">&nbsp;已兑换&nbsp;</a>';
                                        $exchange = $value['exchange'];
                                    }
                                    if ($is_ex == 'N') {
                                        $ex_info = '<a href="javascript:;" class="inner_btn">&nbsp;未兑换&nbsp;</a>';
                                        $exchange = '未兑换';
                                    }
                                    ?>
                                    <td>{$reward}</td>
                                    <td>{$ex_info}</td>
                                    <td>{$exchange}</td>
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