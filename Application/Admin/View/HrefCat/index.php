<script type="text/javascript">
function del(id)
{
	if(id!='')
	{
		if(confirm('分类下的链接会一起删除，确定要删除该分类吗？'))
		{
			$.ajax({
				type:"POST",
				url:'/dmooo.php/HrefCat/del',
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
      <h2><strong style="color:grey;">当前位置：内容管理 &raquo; 友情链接</strong></h2>
      <div class="page_title">
        <form action="__CONTROLLER__/add" method="post" >
          分类名称：<input type="text" class="textbox" name="title" placeholder=""/>
          排序：<input type="text" class="textbox" name="sort" placeholder=""/>
          <input type="submit" value="添加分类" class="group_btn"/>
        </form>
      </div>
      <form action="__CONTROLLER__/changesort/cat_id/{$cat_id}" method="post">
      <table class="table">
       <tr>
          <th>ID</th>
          <th>分类名称</th>
          <th>创建时间</th>
          <th>排序</th>
          <th>查看链接列表</th>
          <th>操作</th>
       </tr>
       <foreach name="list" item="a">
       <tr>
          <td>{$a['id']}</td>
          <td>{$a['title']}</td>
          <td>{$a['createtime']}</td>
          <td><input name="sort[{$a.id}]" value="{$a.sort}" class="list_order"/></td>
		  <td><a href="__MODULE__/Href/index/cat_id/{$a.id}">点击查看链接列表</a></td>
		  <td>
		      <a href="__CONTROLLER__/edit/id/{$a.id}" title="修改">
		         <img src="__ADMIN_IMG__/wzfl_05.png" />
		      </a>
		      <a href="javascript:;" onclick="del({$a.id});" title="删除">
		         <img src="__ADMIN_IMG__/wzfl_11.png" />
		      </a>
		  </td>
		</tr>
		</foreach>
		<tr>
	       <td colspan="6" align="right" style="text-align:right;"><button type="submit" class="link_btn">统一排序</button></td>
	    </tr>
      </table>
      </form>
     </section>
 </div>
</section>