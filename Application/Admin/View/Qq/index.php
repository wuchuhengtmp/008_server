<link rel="stylesheet" type="text/css" href="__ADMIN_CSS__/qq.css" />
<script type="text/javascript">
function del(id)
{
	if(id!='')
	{
		if(confirm('确定要删除该客服QQ吗？'))
		{
			$.ajax({
				type:"POST",
				url:'/dmooo.php/Qq/del',
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

//客服样式切换
jQuery(function(){
	$('.qqlist').on('click','li',function(){
		$(this).addClass('current').siblings().removeClass('current');
		var index = $(".qqlist li").index(this)+1;
	    $('input[name="qq_css"]').attr('value',index);
	});
});
</script>
<section class="rt_wrap content">
 <div class="rt_content">
     <section>
      <h2><strong style="color:grey;">当前位置：内容管理 &raquo; QQ客服管理</strong></h2>
      <div class="page_title">
        <form action="__CONTROLLER__/add" method="post" >
          客服名称：<input type="text" class="textbox" name="title" placeholder=""/>
    QQ号码：<input type="text" class="textbox" name="num" placeholder=""/>
          排序：<input type="text" class="textbox" name="sort" placeholder=""/>
          <input type="submit" value="添加客服QQ" class="group_btn"/>
        </form>
      </div>
      <form action="__CONTROLLER__/changesort/cat_id/{$cat_id}" method="post">
      <table class="table">
       <tr>
          <th>ID</th>
          <th>名称</th>
          <th>QQ号码</th>
          <th>排序</th>
          <th>操作</th>
       </tr>
       <foreach name="qlist" item="a">
       <tr>
           <td>{$a['id']}</td>
           <td>{$a['title']}</td>
           <td>{$a['num']}</td>
           <td><input name="sort[{$a.id}]" value="{$a.sort}" class="list_order"/></td>
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
	     <td colspan="5" align="left" style="text-align:left">
	        <input type="submit" class="link_btn" value="统一排序">
		  </td>
	   </tr>
      </table>
      </form>
     </section>
     
     <div class="qqwrapper">
		<h2 class="h2tit">客服QQ样式选择：</h2>
		<form action="__CONTROLLER__/changeCss" method="post" >
		<input type="hidden" name="old_qq_css" value="{$qq_css}">
		<input type="hidden" name="old_contact_phone" value="{$contact_phone}">
		<input type="hidden" name="qq_css" value="{$qq_css}">
		 咨询电话：<input type="text" class="textbox" name="contact_phone" value="{$contact_phone}" placeholder="" style="margin-bottom: 10px"/>
		 <input type="submit" class="link_btn" value="确认选择">
		<ul class="qqlist">
		    <?php 
		    for($i=1;$i<=9;$i++)
		    {
		    	if($i==$qq_css)
		    	{
		    		$current='current';
		    	}else {
		    		$current='';
		    	}
		    	echo '<li class="'.$current.'"><img src="__ADMIN_IMG__/qq'.$i.'.png" style="max-height:100%"/></li>';
		    }
		    ?>
		</ul>
		
		</form>
	</div>
 </div>
</section>