<?php 
// 当前栏目标注
switch (CONTROLLER_NAME)
{
	default:
		break;
}
switch (CONTROLLER_NAME.'/'.ACTION_NAME)
{	
	//代理商系统-查看会员列表
	case 'Agent/index':
		$agentl_css1='active';
		break;

	//管理员管理-修改密码
	case 'Admin/changepwd':
		$al_css4='active';
		break;

	default:
		break;
}
?>
<aside class="lt_aside_nav content">
 <h2><a href="__MODULE__/System/index">后台首页</a></h2>
 <ul id="J_list">
  <li>
   <dl>
    <dt data-show="true">代理商系统<span class="fr">+</span></dt>
    <dd><a href="__MODULE__/Agent/index" class="<?php echo $agentl_css1;?>">查看用户列表</a></dd>
   </dl>
  </li>
  <li>
   <dl>
    <dt data-show="true">管理员管理<span class="fr">+</span></dt>
    <dd><a href="__MODULE__/Admin/changepwd" class="<?php echo $al_css4;?>">修改密码</a></dd>
   </dl>
  </li>
  <li>
   <p class="btm_infor">© 汇客熊 版权所有</p>
  </li>
 </ul>
</aside>

<script type="text/javascript">
			$(function(){
				$('#J_list').find('dd').hide();
				$('#J_list dd a').each(function(){
					var class1=$(this).attr('class');
					if(class1=='active')
					{
						$(this).parents('li').find('dd').show();
						$(this).parents('li').find('dt').attr('data-show','false');
						$(this).parents('li').find('dt').find('span').html('-');
					}
				});
				$('#J_list').on('click','dt',function(){
					var data_show= $(this).attr('data-show');
					if(data_show == 'true'){
						$(this).nextAll('dd').slideDown().parents('li').siblings().find('dd').hide(function(){
								$(this).parents('dl').find('dt').attr('data-show','true');
								$(this).parents('dl').find('dt').find('span').html('+');
						});
						$(this).attr('data-show','false');
						$(this).find('span').html('-');
					}
					else
					{
						$(this).nextAll('dd').slideUp();
						$(this).attr('data-show','true');
						$(this).find('span').html('+');
					}
					
				});
			});
</script>