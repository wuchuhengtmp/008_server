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
						<h3>当前位置： 拼多多管理系统 &raquo; 拼多多订单管理</h3>
					</div>
					<div class="ibox-content">
                        <form action="__ACTION__" method="get" role="form" class="form-inline pull-left">
                        	 订单号：<input type="text" placeholder="" name="order_sn" class="form-control">
                        	 商品名称：<input type="text" placeholder="" name="goods_name" class="form-control">
                        	 所属用户：<input type="text" placeholder="" name="username" class="form-control">
                        	 订单状态：<select class="form-control" name="order_status">
                        	 <option value="">请选择订单状态</option>
                        	 <option value="-1">未支付</option>
                        	 <option value="0">已支付</option>
                        	 <option value="1">已成团</option>
                        	 <option value="2">确认收货</option>
                        	 <option value="3">审核成功</option>
                        	 <option value="4">审核失败</option>
                        	 <option value="5">已经结算</option>
                        	 <option value="8">非多多进宝商品</option>
                        	 <option value="10">已处罚</option>
                        	 </select>
                            <button class="btn btn-primary" type="submit">查询</button>
                        </form>
						<div class="">
							<table class="table table-bordered table-striped table-hover">
								<thead>
									<tr>
										<th>订单号</th>
										<th width="20%">订单名称</th>
										<th>商品缩略图</th>
										<th>所属用户</th>
										<th>总价</th>
										<th>平台佣金</th>
										<th>拼多多佣金</th>
										<th>佣金比例</th>
										<th>下单时间</th>
										<th>订单状态</th>
										<th>操作</th>
									</tr>
								</thead>
								<tbody>
									<?php 
									$User=new \Common\Model\UserModel();
									?>
									<foreach name="list" item="l">
									<tr>
										<td>{$l['order_sn']}</td>
										<td>{$l['goods_name']}</td>
										<td><img src="{$l['goods_thumbnail_url']}" height="50px"></td>
										<td>
										<?php 
										if($l['user_id']) {
										    $userMsg=$User->getUserMsg($l['user_id']);
										    echo $userMsg['phone'];
										}
										?>
										</td>
										<td>￥<?php echo $l['order_amount']/100;?></td>
										<td>￥<?php echo $l['promotion_amount']/100;?></td>
										<td>￥<?php echo $l['pdd_commission']/100;?></td>
										<td><?php echo $l['promotion_rate']/10;?>%</td>
										<td><?php echo date('Y-m-d H:i:s',$l['order_create_time'])?></td>
										<td>{$l['order_status_desc']}</td>
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