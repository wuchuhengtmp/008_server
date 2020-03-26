<link type="text/css" rel="stylesheet" href="__HOME_CSS__/qq.css" />
<script type="text/javascript" src="__HOME_JS__/qq.js"></script>
<div id="qq8">
	<ul class="siderNav">
		<li>
			<a href="">
				<img src="__HOME_IMG__/l02.png" class="show" />
				<img src="__HOME_IMG__/a.png" class="hide2" width="57" height="49" />
				<img src="__HOME_IMG__/weixin.jpg" class="wk" />
			</a>
		</li>
		<?php
	    //获取客服列表
	    $qq=new \Common\Model\QqModel();
	    $qqlist=$qq->getQqList();
	    foreach ($qqlist as $ql)
	    {
	    	echo '<li>
			<a href="http://wpa.qq.com/msgrd?v=3&uin='.$ql['num'].'&site=qq&menu=yes" target="_blank">
				<img src="__HOME_IMG__/l04.png" class="show" />
				<div class="hide">
					<img src="__HOME_IMG__/ll04.png" class="fl" />
					<p class="p1">'.$ql['title'].'</p>
				</div>
			</a>
		</li>';
	    }
	    ?>
		<li>
			<a href="javascript:;">
				<img src="__HOME_IMG__/l05.png" class="show" />
				<div class="hide">
					<img src="__HOME_IMG__/ll05.png" class="fl" />
					<p class="p2"><?php echo CONTACT_PHONE;?></p>
				</div>
			</a>
		</li>
		<li id="top">
			<a href="javascript:;">
				<img src="__HOME_IMG__/l06.png" class="show" />
				<div class="hide">
					<img src="__HOME_IMG__/ll06.png" />
				</div>
			</a>
		</li>
	</ul>
</div>