<section class="rt_wrap content">
 <div class="rt_content">
     <section>
      <h2><strong style="color:grey;">当前位置：内容管理 &raquo; 招聘管理&raquo; 查看简历详情</strong><a class="back" href="__CONTROLLER__/index/job_id/{$msg.job_id}">返回上一页 &raquo;</a></h2>
      <ul class="ulColumn2">
       <li>
        <span class="item_name" style="width:120px;">岗位名称：</span>
        {$msg.job_name}
       </li>
       <li>
        <span class="item_name" style="width:120px;">期望薪资：</span>
        {$msg.salary}
       </li>
       <li>
        <span class="item_name" style="width:120px;">姓名：</span>
        {$msg.truename}
       </li>
       <li>
        <span class="item_name" style="width:120px;">电话：</span>
        {$msg.phone}
       </li>
       <li>
        <span class="item_name" style="width:120px;">住址：</span>
        {$msg.address}
       </li>
       <li>
        <span class="item_name" style="width:120px;">性别：</span>
        <?php 
        switch ($msg['sex'])
        {
        	case '1':
        		$sex='男';
        		break;
        	case '2':
        		$sex='女';
        		break;
        	case '3':
        		$sex='保密';
        		break;
        }
        echo $sex;
        ?>
       </li>
       <li>
        <span class="item_name" style="width:120px;">年龄：</span>
        {$msg.age}
       </li>
       <li>
        <span class="item_name" style="width:120px;">名族：</span>
        {$msg.nationality}
       </li>
       <li>
        <span class="item_name" style="width:120px;">学校：</span>
        {$msg.school}
       </li>
       <li>
        <span class="item_name" style="width:120px;">学历：</span>
        {$msg.education}
       </li>
       <li>
        <span class="item_name" style="width:120px;">专业：</span>
        {$msg.major}
       </li>
       <li>
        <span class="item_name" style="width:120px;">特长：</span>
        <textarea name="" placeholder="" class="textarea" style="width:500px;height:100px;">{$msg.speciality}</textarea>
       </li>
       <li>
        <span class="item_name" style="width:120px;">求职意向：</span>
        <textarea name="" placeholder="" class="textarea" style="width:500px;height:100px;">{$msg.job_intension}</textarea>
       </li>
       <li>
        <span class="item_name" style="width:120px;">个人技能：</span>
        <textarea name="" placeholder="" class="textarea" style="width:500px;height:100px;">{$msg.skill}</textarea>
       </li>
       <li>
        <span class="item_name" style="width:120px;">学习及工作经历：</span>
        <textarea name="" placeholder="" class="textarea" style="width:500px;height:100px;">{$msg.exp}</textarea>
       </li>
       <li>
        <span class="item_name" style="width:120px;">自我评价：</span>
        <textarea name="" placeholder="" class="textarea" style="width:500px;height:100px;">{$msg.self_assessment}</textarea>
       </li>
       <li>
        <span class="item_name" style="width:120px;">投递时间：</span>
        {$msg.apply_time}
       </li>
       <li>
        <span class="item_name" style="width:120px;">简历下载：</span>
        <?php 
          if($msg['file'])
          {
          	echo '<a target="_blank" href="'.$msg['file'].'">简历下载</a>';
          }else {
          	echo '没有上传简历';
          }
          ?>
       </li>
      </ul>
     </section>
     <!--tabStyle-->
 </div>
</section>