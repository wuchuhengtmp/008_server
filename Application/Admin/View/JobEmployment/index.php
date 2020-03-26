<link rel="stylesheet" type="text/css" href="__CSS__/page.css" />
<script type="text/javascript">
function del(id)
{
	if(id!='')
	{
		if(confirm('确定要删除该简历吗？'))
		{
			$.ajax({
				type:"POST",
				url:'/dmooo.php/JobEmployment/del',
				dataType:"html",
				data:"job_employment_id="+id,
				success:function(msg)
				{
					if(msg=='1')
					{
						alert('删除成功！');
					}else {
						alert('操作失败！');
					}
				    location.reload();
				}
			});
		}
	}
}
</script>
<section class="rt_wrap content">
 <div class="rt_content">
     <section>
      <h2><strong style="color:grey;">当前位置：内容管理 &raquo; 招聘管理&raquo; 投递简历管理</strong><a class="back" href="__MODULE__/Job/index">返回上一页 &raquo;</a></h2>
      
      <table class="table">
       <tr>
        <th>ID</th>
        <th>岗位名称</th>
        <th>期望薪资</th>
        <th>姓名</th>
        <th>电话</th>
        <th>性别</th>
        <th>名族</th>
        <th>毕业学校</th>
        <th>学历</th>
        <th>专业</th>
        <th>投递时间</th>
        <th>简历下载</th>
        <th>操作</th>
       </tr>
       <?php 
       $Job=new \Common\Model\JobModel();
       ?>
       <foreach name="list" item="l">
       <tr>
          <td>{$l['job_employment_id']}</td>
          <td>
          <?php 
          //岗位名称
          $JobMsg=$Job->getJobMsg($l['job_id']);
          echo $JobMsg['job_name'];
          ?>
          </td>
          <td>{$l['salary']}</td>
          <td>{$l['truename']}</td>
          <td>{$l['phone']}</td>
          <td>
          <?php 
          switch ($l['sex'])
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
          </td>
          <td>{$l['nationality']}</td>
          <td>{$l['school']}</td>
          <td>{$l['education']}</td>
          <td>{$l['major']}</td>
          <td>{$l['apply_time']}</td>
          <td>
          <?php 
          if($l['file'])
          {
          	echo '<a target="_blank" href="'.$l['file'].'">简历下载</a>';
          }else {
          	echo '没有上传简历';
          }
          ?>
          </td>
          <td>
             <a href="__CONTROLLER__/edit/job_employment_id/{$l.job_employment_id}" title="查看简历详情">
			    <img src="__ADMIN_IMG__/wzfl_05.png" />
		     </a>
		     <a href="javascript:;" onclick="del({$l.job_employment_id});" title="删除">
			    <img src="__ADMIN_IMG__/wzfl_11.png" />
		     </a>
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