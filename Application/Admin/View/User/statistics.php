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

</head>

<body class="gray-bg">
	<div class="wrapper wrapper-content animated fadeInRight">
		<div class="row">
			<div class="col-sm-12">
				<div class="ibox float-e-margins">
					<div class="ibox-title">
						<h3>当前位置： 会员管理 &raquo; 统计</h3>
					</div>
					<div class="ibox-content">
						<div class="ibox">
							  <h3>会员总余额：<strong style="color: red">￥{$all_balance}</strong></h3>
                              <h3>推荐注册收益总额：<strong style="color: red">￥{$recommend_amount}</strong></h3>
                              <h3>淘宝返利总收益：<strong style="color: red">￥{$tb_amount}</strong>，本月淘宝返利总收益：<strong style="color: red">￥{$tb_amount_month}</strong></h3>
                              <h3>京东返利总收益：<strong style="color: red">￥{$jd_amount}</strong></h3>
                              <h3>拼多多返利总收益：<strong style="color: red">￥{$pdd_amount}</strong></h3>
                              <h3>唯品会返利总收益：<strong style="color: red">￥{$vip_amount}</strong></h3>
                              <h3>返利总收益：<strong style="color: red">￥{$amount}</strong>（淘宝、拼多多、京东、唯品会返利收益总和）</h3>
                              <h3>本月返利总额：<strong style="color: red">￥{$amount_month}</strong>（本月淘宝、拼多多、京东、唯品会返利收益总和）</h3>
                              <h3>用户可提现总额：<strong style="color: red">￥{$amount2}</strong>（会员总余额-本月淘宝、拼多多、京东、唯品会返利收益总和）</h3>
                              <h3>用户已提现总额：<strong style="color: red">￥{$draw_amount}</strong></h3>
                              <h3>淘宝购物总人数：<strong style="color: red">{$tb_order_num}人</strong></h3>
                              <h3>淘宝结算总人数：<strong style="color: red">{$tb_order_num2}人</strong></h3>
                              <h3>淘宝官方返利总佣金：<strong style="color: red">￥{$all_tb_amount}</strong>，淘宝官方本月返利总佣金：<strong style="color: red">￥{$all_tb_amount_month}</strong>（未扣除淘宝技术服务费和税）</h3>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</body>
</html>