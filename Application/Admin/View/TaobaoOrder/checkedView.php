<section class="rt_wrap content mCustomScrollbar">
 <div class="rt_content">
     <section>
      <h2><strong style="color:grey;">当前位置：值得买管理 &raquo; 查看淘宝订单已审核申请</strong><a class="back" href="__CONTROLLER__/checked">返回淘宝订单已审核申请 &raquo;</a></h2>

      <ul class="ulColumn2">
       <li>
        <span class="item_name" style="width:120px;">申请用户：</span>
        {$msg.phone}
       </li>
       <li>
        <span class="item_name" style="width:120px;">佣金：</span>
                    ￥{$msg.money}
       </li>
       <li>
        <span class="item_name" style="width:120px;">审核结果：</span>
        <?php 
        //审核结果
        if($msg['check_result']=='Y')
        {
        	$check_result='审核通过';
        }else {
        	$check_result='审核不通过';
        }
        echo $check_result;
        ?>
       </li>
       <li>
        <span class="item_name" style="width:120px;">审核时间：</span>
        {$msg.check_time}
       </li>
      </ul>
      
     </section>
     <!--tabStyle-->
 </div>
</section>