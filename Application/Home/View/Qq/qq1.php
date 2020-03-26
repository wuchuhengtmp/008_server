<link type="text/css" rel="stylesheet" href="__HOME_CSS__/qq.css" />
<script type="text/javascript" src="__HOME_JS__/qq.js"></script>
<div class="qq5" id="qq5">
	<span class="side">
		在线咨询
		<em><<</em>
	</span>
	<div class="sidecon">
		<dl class="sdl">
			<dt>QQ在线咨询</dt>
			<?php
			//获取客服列表
			$qq=new \Common\Model\QqModel();
			$qqlist=$qq->getQqList();
			foreach ($qqlist as $ql)
			{
				echo '<dd>
		                 <i class="qqicon qqic13"></i>
				         <a href="http://wpa.qq.com/msgrd?v=3&uin='.$ql['num'].'&site=qq&menu=yes" target="_blank">'.$ql['title'].'</a>
			          </dd>';
			}
			?>
		</dl>
		<div class="dlft">
			<p>咨询电话</p>
			<p><?php echo CONTACT_PHONE;?></p>
		</div>
	</div>
</div>


