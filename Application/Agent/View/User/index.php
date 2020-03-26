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

<script src="__ADMIN_JS__/jquery.min.js?v=2.1.4"></script>
<!-- Sweet Alert -->
<link href="__ADMIN_CSS__/plugins/sweetalert/sweetalert.css" rel="stylesheet">
<script src="__ADMIN_JS__/plugins/sweetalert/sweetalert.min.js"></script>
<!-- Sweet Alert -->

<link rel="stylesheet" type="text/css" href="__CSS__/page.css" />

</head>

<body class="gray-bg">
	<div class="wrapper wrapper-content animated fadeInRight">
		<div class="row">
			<div class="col-sm-12">
				<div class="ibox float-e-margins">
					<div class="ibox-title">
						<h3>当前位置：会员管理 &raquo; 会员列表</h3>
					</div>
					<div class="ibox-content">
						<form action="__ACTION__" method="get" role="form" class="form-inline pull-left">
                        	<input type="hidden" name="p" value="1"> 
                        	<input type="hidden" name="group_id" value="{$group_id}">
                        	用户名/邮箱、手机号码：<input type="text" placeholder="" name="search" class="form-control" style="width:100px">
                        	备注姓名：<input type="text" placeholder="" name="remark" class="form-control" style="width:80px">
                        	城市：<input type="text" placeholder="" name="city" class="form-control" style="width:80px">
                        	会员组：<select class="form-control" name="group_id">
            						<option value="">请选择会员组</option>
            						<?php 
            						foreach ($glist as $l) {
            						    echo '<option value="'.$l['id'].'">'.$l['title'].'</option>';
                                    }
                                    ?>
                                    </select>
                                                                     推荐人手机：<input type="text" placeholder="" name="referrer_phone" class="form-control" style="width:80px">
                        	<button class="btn btn-primary" type="submit">查询</button>
                        </form>
						<div class="ibox">
							<table class="table table-bordered table-striped table-hover">
								<thead>
									<tr>
										<th>所属分组</th>
										<th>是否冻结</th>
										<th>手机号码</th>
										<th>姓名</th>
										<th>备注</th>
										<th>余额</th>
										<th>可提现</th>
										<th>本月预估</th>
										<th>本月结算</th>
										<th>推荐人数</th>
										<th>推广位</th>
										<th>上级/推荐</th>
										<th>注册时间</th>
										<th>手机归属地</th>
									</tr>
								</thead>
								<tbody>
									<?php 
									$group=new \Common\Model\UserGroupModel();
	                                $User=new \Common\Model\UserModel();
	                                $UserDetail=new \Common\Model\UserDetailModel();
	                                ?>
	                                <foreach name="list" item="l">
                            	      <tr>
                            	          <td>
                            	          <?php 
                            	          //会员组名称
                            	          $groupMsg=$group->getGroupMsg($l['group_id']);
                            	          echo $groupMsg['title'];
                            	          ?>
                            	          </td>
                            	          <td>
                            	           <if condition='$l[is_freeze] eq N'>
                            	               <button type="button" class="btn btn-primary btn-sm">正常</button>
                            			   <else/>
                            			       <button type="button" class="btn btn-danger btn-sm">冻结</button>
                            			   </if>
                            			  </td>
                            			  <td>
                            			  <?php 
                            			  //隐藏手机号码
                            			  $phone=hide_phone($l['phone'], 4, 4);
                            			  if($l['is_buy']=='Y')
                            			  {
                            			      echo '<font color="red">'.$phone.'</font>';
                            			  }else {
                            			      echo $phone;
                            			  }
                            			  ?>
                            			  </td>
                            			  <td>
                            			  <?php 
                            			  $detailMsg=$UserDetail->getUserDetailMsg($l['uid']);
                            			  echo $detailMsg['truename'];
                            			  ?>
                            			  </td>
                            			  <td>{$l['remark']}</td>
                            			  <td>￥{$l['balance']}</td>
                            			  <?php 
                            			  $res_balance=$User->getDrawBalance($l['uid']);
                            			  ?>
                            			  <td>￥{$res_balance.draw_balance}</td>
                            			  <td>￥{$res_balance.amount_current}</td>
                            			  <td>￥{$res_balance.amount_finish}</td>
                            			  <td>
                            			  <?php 
                            			  $uid=$l['uid'];
                            			  $referrer_num=$User->where("referrer_id='$uid'")->count();
                            			  echo '<a href="__ACTION__/referrer_phone/'.$l['phone'].'">'.$referrer_num.'</a>';
                            			  ?>
                            			  </td>
                            			  <td>
                            			  <?php 
                            			  if($l['tb_pid_master'])
                            			  {
                            			  	echo '1';
                            			  }else {
                            			  	echo '0';
                            			  }
                            			  ?>
                            			  </td>
                            			  <td>
                            			  <?php 
                            			  if($l['referrer_id'])
                            			  {
                            			  	$referrerMsg=$User->getUserDetail($l['referrer_id']);
                            			  	if($referrerMsg['remark'])
                            			  	{
                            			  		echo $referrerMsg['remark'];
                            			  	}else {
                            			  	    //隐藏手机号码
                            			  	    if(is_phone($referrerMsg['account'])){
                            			  	        echo hide_phone($referrerMsg['account'], 4, 4);
                            			  	    }else {
                            			  	        echo $referrerMsg['account'];
                            			  	    }
                            			  	}
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