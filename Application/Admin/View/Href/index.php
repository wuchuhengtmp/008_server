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
			if(confirm('确定删除这些链接吗？'))
			{
				$.ajax({
					type:"POST",
					url:"/dmooo.php/Href/batchdel",
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
			alert('请选择需要删除的链接！');
			return false;
		}
	});
	
});

function del(id)
{
	if(id!='')
	{
		if(confirm('确定要删除该链接吗？'))
		{
			$.ajax({
				type:"POST",
				url:"/dmooo.php/Href/del",
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
      <h2><strong style="color:grey;">当前位置：内容管理 &raquo; 友情链接 &raquo; {$cat_title}</strong><a class="back" href="__MODULE__/HrefCat/index">返回友情链接 &raquo;</a></h2>
      <div class="page_title">
       <a class="fr top_rt_btn" href="__CONTROLLER__/add/cat_id/{$cat_id}">添加链接</a>
      </div>
      <form action="__CONTROLLER__/changesort/cat_id/{$cat_id}" method="post">
      <table class="table">
       <tr>
          <th></th>
          <th>ID</th>
          <th>链接名称</th>
          <th>图片</th>
          <th>超链接</th>
          <th>发布时间</th>
          <th>排序</th>
          <th>操作</th>
       </tr>
       <foreach name="hlist" item="a">
       <tr>
           <td><input type="checkbox" id="allid[]" value="{$a['id']}"></td>
           <td>{$a['id']}</td>
           <td>{$a['title']}</td>
           <td>
           <?php
           if($a['img']!='')
           {
           	  echo '<img src="'.$a['img'].'" height="60px" width="100px">';
           }
           ?>
           </td>
           <td>{$a['href']}</td>
           <td>{$a['createtime']}</td>
           <td><input name="sort[{$a.id}]" value="{$a.sort}" class="list_order"/></td>
		   <td>
		       <a href="__CONTROLLER__/edit/id/{$a.id}/cat_id/{$cat_id}" title="修改">
					<img src="__ADMIN_IMG__/wzfl_05.png" />
			   </a>
			   <a href="javascript:;" onclick="del({$a.id});" title="删除">
					 <img src="__ADMIN_IMG__/wzfl_11.png" />
			   </a>
		    </td>
		 </tr>
		 </foreach>
	  <tr>
	     <td colspan="10" align="left" style="text-align:left">
	        <input type="submit" class="link_btn" value="统一排序">
			<input type="button" class="link_btn" id="unselect" value="取消选择">
			<input type="button" class="link_btn" id="selectall" value="全选">
			<input type="button" class="link_btn" id="batchdel" value="批量删除">
		  </td>
	   </tr>
      </table>
      </form>
      <div class="pages">
			 {$page}
	  </div>
     </section>
     
 </div>
</section>