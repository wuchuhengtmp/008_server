<link rel="stylesheet" type="text/css" href="__CSS__/page.css" />

<section class="rt_wrap content mCustomScrollbar">
 <div class="rt_content">
     <section>
      <h2><strong style="color:grey;">当前位置：淘宝管理系统 &raquo; 淘宝隐藏优惠券管理</strong></h2>
      <div class="page_title">
        <form action="__ACTION__" method="get" style="float:left">
		  <input type="hidden" name="p" value="1">
		  输入查询条件：
		  商品名称：<input type="text" name="item_title" value="" class="textbox">
		 <input type="submit" value="查询" class="group_btn"/>
        </form>
        <a class="fr top_rt_btn" href="__CONTROLLER__/add">添加优惠券</a>
      </div>
      
      <table class="table">
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
            <th>操作</th>
       </tr>
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
	      if($l['user_id'])
	      {
	      	$userMsg=$User->getUserMsg($l['user_id']);
	      	echo $userMsg['phone'];
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
	          <a href="__CONTROLLER__/msg/id/{$l.id}" title="查看详情">
	              <img src="__ADMIN_IMG__/wzfl_05.png" />
	          </a>
	          <?php 
	          if($l['tk_status']=='3')
	          {
	          	if($l['is_refund']=='Y')
	          	{
	          		echo '已维权';
	          	}else {
	          		echo '<a href="javascript:;" onclick="refund('.$l['id'].')" title="维权处理">维权处理</a>';
	          	}
	          }
	          ?>
		  </td>
	   </tr>
	  </foreach>
      </table>
      
      <div class="pages">
			 {$page}
	  </div>
     </section>
     
 </div>
</section>