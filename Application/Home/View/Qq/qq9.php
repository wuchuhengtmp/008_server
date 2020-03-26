<link type="text/css" rel="stylesheet" href="__HOME_CSS__/qq.css" />
<script type="text/javascript" src="__HOME_JS__/qq.js"></script>
<div id="qq9">
	<ul class="info">
		<li>
			<a href="__MODULE__/Index/online">
				<i class="zicon ic1"></i>
				<span>在线留言</span>
			</a>
		</li>
		<li>
			<a href="__MODULE__/Index/contact">
				<i class="zicon ic2"></i>
				<span>热线电话</span>
				<span class="wk"> </span>
			</a>
		</li>
		<li>
			<a href="javascript:;">
				<i class="zicon ic3"></i>
				<span>官方微信</span>
			</a>
			<img src="__HOME_IMG__/weixin.jpg" class="wk" />
		</li>
		<?php
	    //获取客服列表
	    $qq=new \Common\Model\QqModel();
	    $qqlist=$qq->getQqList();
	    foreach ($qqlist as $ql)
	    {
	    	echo '<li>
			<a href="http://wpa.qq.com/msgrd?v=3&uin='.$ql['num'].'&site=qq&menu=yes" target="_blank">
				<i class="zicon ic4"></i>
				<span>'.$ql['title'].'</span>
			</a>
		</li>';
	    }
	    ?>
		<li>
			<a href="" id="top">
				<i class="zicon ic5"></i>
				<span>返回顶部</span>
			</a>
		</li>
	</ul>
</div>
