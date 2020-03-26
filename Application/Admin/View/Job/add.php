<!-- 时间插件 -->
<script src="__JS__/my97/WdatePicker.js"></script>
<!-- 时间插件 -->

<section class="rt_wrap content">
 <div class="rt_content">
     <section>
      <h2><strong style="color:grey;">当前位置：内容管理 &raquo; 招聘管理&raquo; 添加岗位</strong><a class="back" href="__CONTROLLER__/index">返回上一页 &raquo;</a></h2>

      <form action="__ACTION__" method="post" enctype="multipart/form-data">
      
      <ul class="ulColumn2">
       <li>
        <span class="item_name" style="width:120px;">岗位名称：</span>
        <input type="text" class="textbox textbox_295" name="job_name" placeholder=""/>
       </li>
       <li>
        <span class="item_name" style="width:120px;">薪资待遇：</span>
        <input type="text" class="textbox textbox_295" name="salary" placeholder=""/>
       </li>
       <li>
        <span class="item_name" style="width:120px;">工作地点：</span>
        <input type="text" class="textbox textbox_295" name="address" placeholder=""/>
       </li>
       <li>
        <span class="item_name" style="width:120px;">学历要求：</span>
        <input type="text" class="textbox textbox_295" name="education" placeholder=""/>
       </li>
       <li>
        <span class="item_name" style="width:120px;">专业：</span>
        <input type="text" class="textbox textbox_295" name="major" placeholder=""/>
       </li>
       <li>
        <span class="item_name" style="width:120px;">性别：</span>
        <label class="single_selection"><input type="radio" name="sex" value="1"/>男</label>
        <label class="single_selection"><input type="radio" name="sex" value="2"/>女</label>
        <label class="single_selection"><input type="radio" name="sex" value="3"/>男女不限</label>
       </li>
       <li>
        <span class="item_name" style="width:120px;">年龄：</span>
        <input type="text" class="textbox textbox_295" name="age" placeholder=""/>
       </li>
       <li>
        <span class="item_name" style="width:120px;">是否全职：</span>
        <label class="single_selection"><input type="radio" name="is_full" value="1" checked/>全职</label>
        <label class="single_selection"><input type="radio" name="is_full" value="2"/>兼职</label>
       </li>
       <li>
        <span class="item_name" style="width:120px;">工作经验：</span>
        <input type="text" class="textbox textbox_295" name="exp" placeholder=""/>
       </li>
       <li>
        <span class="item_name" style="width:120px;">福利-薪酬：</span>
        <?php 
        $job_mark_salary=json_decode(job_mark_salary,true);
        foreach ($job_mark_salary as $k=>$v)
        {
        	echo '<input type="checkbox" name="mark_salary[]" value="'.$k.'"> '.$v.'&nbsp;';
        }
        ?>
       </li>
       <li>
        <span class="item_name" style="width:120px;">福利-保障：</span>
        <?php 
        $job_mark_insurance=json_decode(job_mark_insurance,true);
        foreach ($job_mark_insurance as $k=>$v)
        {
        	echo '<input type="checkbox" name="mark_insurance[]" value="'.$k.'"> '.$v.'&nbsp;';
        }
        ?>
       </li>
       <li>
        <span class="item_name" style="width:120px;">福利-假期：</span>
        <?php 
        $job_mark_holiday=json_decode(job_mark_holiday,true);
        foreach ($job_mark_holiday as $k=>$v)
        {
        	echo '<input type="checkbox" name="mark_holiday[]" value="'.$k.'"> '.$v.'&nbsp;';
        }
        ?>
       </li>
       <li>
        <span class="item_name" style="width:120px;">福利-补贴活动：</span>
        <?php 
        $job_mark_subsidy=json_decode(job_mark_subsidy,true);
        foreach ($job_mark_subsidy as $k=>$v)
        {
        	echo '<input type="checkbox" name="mark_subsidy[]" value="'.$k.'"> '.$v.'&nbsp;';
        }
        ?>
       </li>
       <li>
        <span class="item_name" style="width:120px;">岗位职责：</span>
        <textarea name="duty" placeholder="" class="textarea" style="width:500px;height:150px;"></textarea>
       </li>
       <li>
        <span class="item_name" style="width:120px;">任职要求：</span>
        <textarea name="requirement" placeholder="" class="textarea" style="width:500px;height:150px;"></textarea>
       </li>
       <li>
        <span class="item_name" style="width:120px;">联系方式：</span>
        <textarea name="contact" placeholder="" class="textarea" style="width:500px;height:150px;"></textarea>
       </li>
       <li>
        <span class="item_name" style="width:120px;">招聘人数：</span>
        <input type="text" class="textbox textbox_295" name="num" placeholder=""/>
       </li>
       <li>
        <span class="item_name" style="width:120px;">招聘截止时间：</span>
        <input type="text" class="textbox textbox_295 Wdate" name="deadline" onclick="WdatePicker()" placeholder=""/>
       </li>
       <li>
        <span class="item_name" style="width:120px;"></span>
        <input type="submit" class="link_btn" value="添加岗位"/>
       </li>
      </ul>
      
      </form>
     </section>
     <!--tabStyle-->
 </div>
</section>
