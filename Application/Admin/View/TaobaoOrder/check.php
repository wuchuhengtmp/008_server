<section class="rt_wrap content mCustomScrollbar">
 <div class="rt_content">
     <section>
      <h2><strong style="color:grey;">当前位置：值得买管理 &raquo; 审核淘宝订单兑现申请</strong><a class="back" href="__CONTROLLER__/checkPending">返回待审核申请 &raquo;</a></h2>

      <form action="__ACTION__/id/{$msg['id']}" method="post" enctype="multipart/form-data">
      <ul class="ulColumn2">
       <li>
        <span class="item_name" style="width:120px;">申请用户：</span>
        {$msg.phone}
       </li>
       <li>
        <span class="item_name" style="width:120px;">淘宝订单号：</span>
        {$msg.order_num}
       </li>
       <li>
        <span class="item_name" style="width:120px;">佣金：</span>
        <input type="text" class="textbox textbox_295" name="money" value="" placeholder="" style="width:90px"/>
        <span class="errorTips">必填，佣金按照比例分配给会员，请填写总佣金</span>
       </li>
       <li>
        <span class="item_name" style="width:120px;">审核结果：</span>
        <label class="single_selection"><input type="radio" name="check_result" value="Y"/>通过</label>
        <label class="single_selection"><input type="radio" name="check_result" value="N"/>不通过</label>
       </li>
       <li>
        <span class="item_name" style="width:120px;"></span>
        <input type="submit" class="link_btn" value="确认审核"/>
       </li>
      </ul>
      
      </form>
     </section>
     <!--tabStyle-->
 </div>
</section>