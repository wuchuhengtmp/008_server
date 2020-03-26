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

<!-- Sweet Alert -->
<link href="__ADMIN_CSS__/plugins/sweetalert/sweetalert.css" rel="stylesheet">
<!-- Sweet Alert -->
<script src="__ADMIN_JS__/jquery.min.js?v=2.1.4"></script>
<script src="__ADMIN_JS__/bootstrap.min.js?v=3.3.6"></script>
<script src="__ADMIN_JS__/content.min.js?v=1.0.0"></script>
<script src="__ADMIN_JS__/plugins/sweetalert/sweetalert.min.js"></script>

<link rel="stylesheet" type="text/css" href="__CSS__/page.css" />

</head>

<body class="gray-bg">
	<div class="wrapper wrapper-content animated fadeInRight">
		<div class="row">
			<div class="col-sm-12">
				<div class="ibox float-e-margins">
					<div class="ibox-title">
						<h3>当前位置： 淘宝管理系统 &raquo; 淘宝订单管理</h3>
					</div>
					<div class="ibox-content">
                        <form action="__ACTION__" method="get" role="form" class="form-inline pull-left">
                        	 订单号：<input type="text" placeholder="" name="trade_id" class="form-control">
                        	 商品名称：<input type="text" placeholder="" name="item_title" class="form-control">
                        	 所属用户：<input type="text" placeholder="" name="username" class="form-control">
                        	 订单状态：<select class="form-control" name="tk_status">
                        	 <option value="">请选择订单状态</option>
                        	 <option value="3">订单结算</option>
                        	 <option value="12">订单付款</option>
                        	 <option value="13">订单失效</option>
                        	 <option value="14">订单成功</option>
                        	 </select>
                        	 是否维权：<select class="form-control" name="is_refund">
                        	 <option value="">请选择</option>
                        	 <option value="Y">是</option>
                        	 <option value="N">否</option>
                        	 </select>
                            <button class="btn btn-primary" type="submit">查询</button>
                        </form>
						<div class="">
							<table class="table table-bordered table-striped table-hover">
								<thead>
									<tr>
										<th>订单号</th>
										<th width="20%">订单名称</th>
										<th>卖家店铺名称</th>
										<th>所属用户</th>
										<th>总价</th>
										<th>平台佣金</th>
										<th>淘宝佣金</th>
										<th>佣金比例</th>
										<th>下单时间</th>
										<th>订单状态</th>
										<th>渠道ID</th>
										<th>操作</th>
									</tr>
								</thead>
								<tbody>
									<?php 
									$User=new \Common\Model\UserModel();
									?>
									<foreach name="list" item="l">
									<tr>
										<td>{$l['trade_id']}</td>
										<td>{$l['item_title']}</td>
										<td>{$l['seller_shop_title']}</td>
										<td>
										<?php 
										if($l['user_id']) {
										    $userMsg=$User->getUserMsg($l['user_id']);
										    //隐藏手机号码
										    echo hide_phone($userMsg['phone'], 4, 4);
										    //echo $userMsg['phone'];
										}
										?>
										</td>
										<td>￥{$l['alipay_total_price']}</td>
										<td>￥{$l['commission']}</td>
										<td>￥{$l['total_commission_fee']}</td>
										<td><?php echo $l['total_commission_rate']*100;?>%</td>
										<td>{$l['create_time']}</td>
										<td>
										<?php 
										switch ($l['tk_status'])
										{
										    //订单结算
										    case '3':
										        $status_str='<font color="red">订单结算</font>';
										        break;
										    //订单付款
										    case '12':
										        $status_str='订单付款';
										        break;
										    //订单失效
										    case '13':
										        $status_str='订单失效';
										        break;
										    //订单成功
										    case '14':
										        $status_str='订单成功';
										        break;
										    default:
										        $status_str='';
										        break;
										}
										echo $status_str;
										?>
										</td>
										<td>
										<?php 
										if($l['relation_id']){
										    echo '<font color="red">'.$l['relation_id'].'</font>';
										}
										?>
										</td>
										<td>
											<a href="__CONTROLLER__/msg/id/{$l.id}" title="查看详情">
												<i class="fa fa-file-text-o text-danger" style="font-size:2.0rem"></i>&nbsp;
											</a>
										  </td>
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