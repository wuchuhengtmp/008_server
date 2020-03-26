<link type="text/css" rel="stylesheet" href="__HOME_CSS__/qq.css" />
<script type="text/javascript" src="__HOME_JS__/qq.js"></script>

<div class="qq4" id="qq4">
	<a href="" class="del"></a>
	<ul class="qqi4list">
	    <?php
	    //获取客服列表
	    $qq=new \Common\Model\QqModel();
	    $qqlist=$qq->getQqList();
	    foreach ($qqlist as $ql)
	    {
	    	echo '<li>
	    		     <i class="qqicon qqic9"></i>
			         <a href="http://wpa.qq.com/msgrd?v=3&uin='.$ql['num'].'&site=qq&menu=yes" target="_blank">'.$ql['title'].'</a>
		         </li>';
		}
		?>
		<li>
			<i class="qqicon qqic10"></i>
			<a href="__MODULE__/Index/contact">在线咨询</a>
		</li>
		<li>
			<i class="qqicon qqic11"></i>
			<a href="__MODULE__/Index/online">在线留言</a>
		</li>
		<li class="wek">
			<img src="__HOME_IMG__/weixin.jpg" />
		</li>
	</ul>
	<p class="phone">
		<i class="qqicon qqic12"></i>
		<?php echo CONTACT_PHONE;?>
	</p>
</div>

