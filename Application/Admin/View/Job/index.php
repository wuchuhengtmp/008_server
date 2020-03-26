<link rel="stylesheet" type="text/css" href="__CSS__/page.css" />
<script type="text/javascript">
function del(id)
{
	if(id!='')
	{
		if(confirm('确定要删除该岗位吗？'))
		{
			$.ajax({
				type:"POST",
				url:'/dmooo.php/Job/del',
				dataType:"html",
				data:"job_id="+id,
				success:function(msg)
				{
					if(msg=='2')
					{
						alert('该工作岗位下存在投递的简历，不准直接删除！');
					}
					if(msg=='1')
					{
						alert('删除成功！');
					}
					if(msg=='0')
					{
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
      <h2><strong style="color:grey;">当前位置：内容管理 &raquo; 招聘管理 &raquo; 岗位管理</strong></h2>
      <div class="page_title">
        <form action="__ACTION__" method="get" style="width:500px;float:left">
		  <input type="hidden" name="p" value="1">
          岗位名称：<input type="text" class="textbox" name="job_name" placeholder=""/>
          <input type="submit" value="查询" class="group_btn"/>
        </form>
        <a class="fr top_rt_btn" href="__CONTROLLER__/add">发布岗位</a>
      </div>
      
      <table class="table">
       <tr>
        <th>ID</th>
        <th>岗位名称</th>
        <th>薪资待遇</th>
        <th>学历</th>
        <th>专业</th>
        <th>工作经验</th>
        <th>发布时间</th>
        <th>查看投递简历</th>
        <th>操作</th>
       </tr>
       <?php 
       $JobEmployment=new \Common\Model\JobEmploymentModel();
       ?>
       <foreach name="list" item="l">
       <tr>
          <td>{$l['job_id']}</td>
          <td>{$l['job_name']}</td>
          <td>{$l['salary']}</td>
          <td>{$l['education']}</td>
          <td>{$l['major']}</td>
          <td>{$l['exp']}</td>
          <td>{$l['pubtime']}</td>
          <?php 
          //简历投递数量
          $num=$JobEmployment->getApplyNum($l['job_id']);
          ?>
          <td><a href="__MODULE__/JobEmployment/index/job_id/{$l.job_id}">查看投递简历（{$num}）</a></td>
          <td>
             <a href="__CONTROLLER__/edit/job_id/{$l.job_id}" title="修改">
			    <img src="__ADMIN_IMG__/wzfl_05.png" />
		     </a>
		     <a href="javascript:;" onclick="del({$l.job_id});" title="删除">
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