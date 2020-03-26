<link rel="stylesheet" type="text/css" href="__CSS__/page.css" />
<section class="rt_wrap content mCustomScrollbar">
 <div class="rt_content">
     <section>
      <h2><strong style="color:grey;">当前位置：值得买管理 &raquo; 淘宝订单已审核申请</strong></h2>
      <div class="page_title">
        <form action="__ACTION__" method="get" style="float:left">
		  <input type="hidden" name="p" value="1">
                         用户手机号码：<input type="text" class="textbox" name="phone" placeholder=""/>
                         淘宝订单：<input type="text" class="textbox" name="order_num" placeholder=""/>
          <input type="submit" value="查询" class="group_btn"/>
        </form>
      </div>
      
      <table class="table">
       <tr>
        <th>ID</th>
        <th>提现用户</th>
        <th>佣金</th>
        <th>申请时间</th>
        <th>审核结果</th>
        <th>审核时间</th>
        <th>操作</th>
       </tr>
       <?php
          $User=new \Common\Model\UserModel();
          foreach($list as $l)
          {
          	//提现用户
          	$user_id=$l['user_id'];
          	$UserMsg=$User->getUserMsg($user_id);
          	$user_phone=$UserMsg['phone'];
          	//银行
          	$account_type_d=json_decode(account_type_d,true);
          	$account_type=$account_type_d[$l['account_type']];
          	//审核结果
          	if($l['check_result']=='Y')
          	{
          		$check_result='审核通过';
          	}else {
          		$check_result='审核不通过';
          	}
          	echo '<tr>
       		<td>'.$l['id'].'</td>
       		<td>'.$user_phone.'</td>
       		<td>￥'.$l['money'].'</td>
          	<td>'.$l['apply_time'].'</td>
          	<td>'.$check_result.'</td>
          	<td>'.$l['check_time'].'</td>
          	<td>
        		<a href="__CONTROLLER__/checkedView/id/'.$l['id'].'" title="查看">
			       <img src="__ADMIN_IMG__/wzfl_05.png" />
		        </a>
        	</td>
       		</tr>';
          }
	   ?>
      </table>
      <div class="pages">
         {$page}
      </div>
     </section>
 </div>
</section>