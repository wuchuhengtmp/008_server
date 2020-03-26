<link rel="stylesheet" type="text/css" href="__CSS__/page.css" />
<script type="text/javascript">
$(document).ready(function(){
	//取消全选
	$('#unselect').click(function(){
		$("input:checkbox").removeAttr("checked");
	});
	//全选
	$('#selectall').click(function(){
		$("input:checkbox").prop("checked","checked");
	});
	
	//批量删除
	$('#batchdel').click(function(){
		var all_id='';
		$(":checkbox").each(function(){
			if($(this).prop("checked")) 
			{
				all_id+=$(this).val()+',';
			}
		});
		if(all_id!='')
		{
			if(confirm('确定删除这些帖子吗？'))
			{
				$.ajax({
					type:"POST",
					url:"/dmooo.php/BbsArticle/batchdel",
					dataType:"html",
					data:"all_id="+all_id,
					success:function(msg)
					{
						if(msg=='1')
						{
							alert('批量删除成功！');
						}else {
							alert('操作失败！');
						}
						location.reload();
					}
				});
			}
		}else {
			alert('请选择需要删除的帖子！');
			return false;
		}
	});
	
	//批量转移
	$('#transfer').click(function(){
		var all_id='';
		$(":checkbox").each(function(){
			if($(this).prop("checked")) 
			{
				all_id+=$(this).val()+',';
			}
		});
		if(all_id!='')
		{
			var board_id=$('#board_id').val();
			if(confirm('确定转移这些帖子吗？'))
			{
				$.ajax({
					type:"POST",
					url:"/dmooo.php/BbsArticle/transfer",
					dataType:"html",
					data:"all_id="+all_id+'&board_id='+board_id,
					success:function(msg)
					{
						if(msg=='1')
						{
							alert('批量转移成功！');
						}else {
							alert('操作失败！');
						}
						location.reload();
					}
				});
			}
		}else {
			alert('请选择需要转移的帖子！');
			return false;
		}
	});
	
});

function changeshow(id,status)
{
	if(id!='')
	{
		$.ajax({
			type:"POST",
			url:'/dmooo.php/BbsArticle/changeshow',
			dataType:"html",
			data:"id="+id+"&status="+status,
			success:function(msg)
			{
				if(msg=='1')
				{
					alert('修改状态成功！');
				}else {
					alert('修改状态失败！');
				}
			    location.reload();
			}
		});
	}
}

function changetop(id,status)
{
	if(id!='')
	{
		$.ajax({
			type:"POST",
			url:'/dmooo.php/BbsArticle/changetop',
			dataType:"html",
			data:"id="+id+"&status="+status,
			success:function(msg)
			{
				if(msg=='1')
				{
					alert('修改状态成功！');
				}else {
					alert('修改状态失败！');
				}
			    location.reload();
			}
		});
	}
}

function del(id)
{
	if(id!='')
	{
		if(confirm('确定要删除该篇帖子吗？'))
		{
			$.ajax({
				type:"POST",
				url:'/dmooo.php/BbsArticle/del',
				dataType:"html",
				data:"id="+id,
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
      <h2><strong style="color:grey;">当前位置：论坛系统 &raquo; 帖子管理 &raquo; {$board_name}</strong><a class="back" href="__MODULE__/BbsBoard/index">返回版块管理 &raquo;</a></h2>
      <div class="page_title">
        <form action="__ACTION__/board_id/{$board_id}" method="get" style="width:500px;float:left">
		  <input type="hidden" name="p" value="1">
          输入文章标题查询：<input type="text" class="textbox" name="search" placeholder=""/>
          <input type="submit" value="查询" class="group_btn"/>
        </form>
      </div>
      
      <table class="table">
       <tr>
        <th></th>
        <th>ID</th>
        <th>标题</th>
        <th>是否显示</th>
        <th>是否置顶</th>
        <th>浏览量</th>
        <th>发布时间</th>
        <th>操作</th>
       </tr>
       <foreach name="articlelist" item="l">
       <tr>
          <td ><input type="checkbox" id="allid[]" value="{$l['id']}"></td>
          <td>{$l['id']}</td>
          <td>{$l['title']}</td>
          <td>
            <if condition='$l[is_show] eq Y'>
               <a href="javascript:;" class="inner_btn" onclick="changeshow({$l.id},'N');">&nbsp;正常显示&nbsp;</a>
			<else/>
			   <a href="javascript:;" class="inner_btn_red" onclick="changeshow({$l.id},'Y');">&nbsp;&nbsp;不显示&nbsp;&nbsp;&nbsp;</a>
			</if>
		  </td>
		  <td>
		    <if condition='$l.is_top eq Y'>
		       <a href="javascript:;" class="inner_btn_red" onclick="changetop({$l.id},'N');">&nbsp;&nbsp;置顶&nbsp;&nbsp;&nbsp;</a>
			<else/>
			   <a href="javascript:;" class="inner_btn" onclick="changetop({$l.id},'Y');">&nbsp;不置顶&nbsp;</a>
			</if>
		</td>
		<td>{$l['clicknum']}</td>
		<td>{$l['pubtime']}</td>
		<td>
		   <a href="__CONTROLLER__/edit/id/{$l.id}" title="修改">
			 <img src="__ADMIN_IMG__/wzfl_05.png" />
		   </a>
		  <a href="javascript:;" onclick="del({$l.id});" title="删除">
			 <img src="__ADMIN_IMG__/wzfl_11.png" />
		  </a>
		</td>
	  </tr>
	  </foreach>
	  <tr>
	     <td colspan="8" align="left" style="text-align:left">
	        <input type="button" class="link_btn" id="unselect" value="取消选择">
			<input type="button" class="link_btn" id="selectall" value="全选">
			<input type="button" class="link_btn" id="batchdel" value="批量删除">
			<div style="float:right">
			<input type="button" class="link_btn" value="批量转移到=>">
			<select class="select2" id="board_id" style="vertical-align: middle;">
              <?php
              foreach ($boardlist as $l)
              {
              	 if($l['board_id']!=$board_id)
                 {
                 	echo '<option value="'.$l['board_id'].'" style="margin-left:55px;">'.$l['lefthtml'].''.$l['board_name'].'</option>';
                 }
              }
              ?>
             </select>
             <input type="button" class="link_btn" id="transfer" value="确定转移">
			 </div>
		  </td>
	   </tr>
      </table>
      
      <div class="pages">
			 {$page}
	  </div>
     </section>
     
 </div>
</section>