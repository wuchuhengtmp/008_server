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

<link rel="stylesheet" type="text/css" href="__CSS__/page.css" />

</head>

<body class="gray-bg">
	<div class="wrapper wrapper-content animated fadeInRight">
		<div class="row">
			<div class="col-sm-12">
				<div class="ibox float-e-margins">
					<div class="ibox-title">
						<h3>当前位置： 会员管理 &raquo; 查看会员积分变动记录</h3>
					</div>
					<div class="ibox-content">
						<form action="__ACTION__" method="get" role="form" class="form-inline pull-left">
                        	<input type="hidden" name="p" value="1"> 
                        	用户手机号码：<input type="text" placeholder="" name="phone" class="form-control">
                        	<button class="btn btn-primary" type="submit">查询</button>
                        </form>
						<div class="ibox">
							<table class="table table-bordered table-striped table-hover">
								<thead>
									<tr>
										<th>会员账号</th>
                                        <th>积分</th>
                                        <th>积分存量</th>
                                        <th>变动时间</th>
                                        <th>操作类型</th>
									</tr>
								</thead>
								<tbody>
									<?php
									$User=new \Common\Model\UserModel();
									$group=new \Common\Model\UserGroupModel();
									$UserPointRecord=new \Common\Model\UserPointRecordModel();
                            	      ?>
                            	      <foreach name="list" item="l">
                            	      <tr>
                            	          <?php 
                            	          //会员账号
                            	          $UserMsg=$User->getUserDetail($l['user_id']);
                            	          $user_account=$UserMsg['account'];
                            	          //操作类型
                            	          $res_action=$UserPointRecord->getActionZh($l['action']);
                            	          ?>
                            	          <td>{$user_account}</td>
                            	          <td>{$res_action.action_symbol}{$l.point}</td>
                            	          <td>{$l.all_point}</td>
                            			  <td>{$l['create_time']}</td>
                            			  <td>{$res_action.action_zh}</td>
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