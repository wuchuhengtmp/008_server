<!-- 时间插件 -->
<script src="__JS__/my97/WdatePicker.js"></script>
<!-- 时间插件 -->

<section class="rt_wrap content">
 <div class="rt_content">
     <section>
      <h2><strong style="color:grey;">当前位置：内容管理 &raquo; 招聘管理&raquo; 编辑岗位</strong><a class="back" href="__CONTROLLER__/index">返回上一页 &raquo;</a></h2>

      <form action="__ACTION__/job_id/{$msg.job_id}" method="post" enctype="multipart/form-data">
      
      <ul class="ulColumn2">
       <li>
        <span class="item_name" style="width:120px;">岗位名称：</span>
        <input type="text" class="textbox textbox_295" name="job_name" value="{$msg.job_name}" placeholder=""/>
       </li>
       <li>
        <span class="item_name" style="width:120px;">薪资待遇：</span>
        <input type="text" class="textbox textbox_295" name="salary" value="{$msg.salary}" placeholder=""/>
       </li>
       <li>
        <span class="item_name" style="width:120px;">工作地点：</span>
        <input type="text" class="textbox textbox_295" name="address" value="{$msg.address}" placeholder=""/>
       </li>
       <li>
        <span class="item_name" style="width:120px;">学历要求：</span>
        <input type="text" class="textbox textbox_295" name="education" value="{$msg.education}" placeholder=""/>
       </li>
       <li>
        <span class="item_name" style="width:120px;">专业：</span>
        <input type="text" class="textbox textbox_295" name="major" value="{$msg.major}" placeholder=""/>
       </li>
       <li>
        <span class="item_name" style="width:120px;">性别：</span>
        <label class="single_selection"><input type="radio" name="sex" value="1" <?php if($msg['sex']=='1'){echo 'checked';}?> />男</label>
        <label class="single_selection"><input type="radio" name="sex" value="2" <?php if($msg['sex']=='2'){echo 'checked';}?> />女</label>
        <label class="single_selection"><input type="radio" name="sex" value="3" <?php if($msg['sex']=='3'){echo 'checked';}?> />男女不限</label>
       </li>
       <li>
        <span class="item_name" style="width:120px;">年龄：</span>
        <input type="text" class="textbox textbox_295" name="age" value="{$msg.age}" placeholder=""/>
       </li>
       <li>
        <span class="item_name" style="width:120px;">是否全职：</span>
        <label class="single_selection"><input type="radio" name="is_full" value="1" <?php if($msg['is_full']=='1'){echo 'checked';}?> />全职</label>
        <label class="single_selection"><input type="radio" name="is_full" value="2" <?php if($msg['is_full']=='2'){echo 'checked';}?> />兼职</label>
       </li>
       <li>
        <span class="item_name" style="width:120px;">工作经验：</span>
        <input type="text" class="textbox textbox_295" name="exp" value="{$msg.exp}" placeholder=""/>
       </li>
       <li>
        <span class="item_name" style="width:120px;">福利-薪酬：</span>
        <?php 
        $job_mark_salary=json_decode(job_mark_salary,true);
        $mark_salary_arr=explode(',', $msg['mark_salary']);
        foreach ($job_mark_salary as $k=>$v)
        {
        	if(in_array($k, $mark_salary_arr))
        	{
        		$check='checked';
        	}else {
        		$check='';
        	}
        	echo '<input type="checkbox" name="mark_salary[]" value="'.$k.'" '.$check.'> '.$v.'&nbsp;';
        }
        ?>
       </li>
       <li>
        <span class="item_name" style="width:120px;">福利-保障：</span>
        <?php 
        $job_mark_insurance=json_decode(job_mark_insurance,true);
        $mark_insurance_arr=explode(',', $msg['mark_insurance']);
        foreach ($job_mark_insurance as $k=>$v)
        {
        	if(in_array($k, $mark_insurance_arr))
        	{
        		$check='checked';
        	}else {
        		$check='';
        	}
        	echo '<input type="checkbox" name="mark_insurance[]" value="'.$k.'" '.$check.'> '.$v.'&nbsp;';
        }
        ?>
       </li>
       <li>
        <span class="item_name" style="width:120px;">福利-假期：</span>
        <?php 
        $job_mark_holiday=json_decode(job_mark_holiday,true);
        $mark_holiday_arr=explode(',', $msg['mark_holiday']);
        foreach ($job_mark_holiday as $k=>$v)
        {
        	if(in_array($k, $mark_holiday_arr))
        	{
        		$check='checked';
        	}else {
        		$check='';
        	}
        	echo '<input type="checkbox" name="mark_holiday[]" value="'.$k.'" '.$check.'> '.$v.'&nbsp;';
        }
        ?>
       </li>
       <li>
        <span class="item_name" style="width:120px;">福利-补贴活动：</span>
        <?php 
        $job_mark_subsidy=json_decode(job_mark_subsidy,true);
        $mark_subsidy_arr=explode(',', $msg['mark_subsidy']);
        foreach ($job_mark_subsidy as $k=>$v)
        {
        	if(in_array($k, $mark_subsidy_arr))
        	{
        		$check='checked';
        	}else {
        		$check='';
        	}
        	echo '<input type="checkbox" name="mark_subsidy[]" value="'.$k.'" '.$check.'> '.$v.'&nbsp;';
        }
        ?>
       </li>
       <li>
        <span class="item_name" style="width:120px;">岗位职责：</span>
        <textarea name="duty" placeholder="" class="textarea" style="width:500px;height:150px;">{$msg.duty}</textarea>
       </li>
       <li>
        <span class="item_name" style="width:120px;">任职要求：</span>
        <textarea name="requirement" placeholder="" class="textarea" style="width:500px;height:150px;">{$msg.requirement}</textarea>
       </li>
       <li>
        <span class="item_name" style="width:120px;">联系方式：</span>
        <textarea name="contact" placeholder="" class="textarea" style="width:500px;height:150px;">{$msg.contact}</textarea>
       </li>
       <li>
        <span class="item_name" style="width:120px;">招聘人数：</span>
        <input type="text" class="textbox textbox_295" name="num" value="{$msg.num}" placeholder=""/>
       </li>
       <li>
        <span class="item_name" style="width:120px;">招聘截止时间：</span>
        <input type="text" class="textbox textbox_295 Wdate" name="deadline" value="{$msg.deadline}" onclick="WdatePicker()" placeholder=""/>
       </li>
       <li>
        <span class="item_name" style="width:120px;"></span>
        <input type="submit" class="link_btn" value="编辑岗位"/>
        <input type="submit" class="link_btn" value="重置"/>
       </li>
      </ul>
      
      </form>
     </section>
     <!--tabStyle-->
 </div>
</section>
