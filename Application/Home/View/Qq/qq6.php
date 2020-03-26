<link type="text/css" rel="stylesheet" href="__HOME_CSS__/qq.css" />
<script type="text/javascript" src="__HOME_JS__/qq.js"></script>
<div class="qq6">
	<ul class="qqlist">
	    <?php
	    //获取客服列表
	    $qq=new \Common\Model\QqModel();
	    $qqlist=$qq->getQqList();
	    foreach ($qqlist as $ql)
	    {
	    	echo '<li>
		          	 <a href="http://wpa.qq.com/msgrd?v=3&uin='.$ql['num'].'&site=qq&menu=yes" target="_blank">
				      <i class="qqicon qqic14"></i>
				      <span>'.$ql['title'].'</span>
			         </a>
		         </li>';
	    }
	    ?>
		
		<li>
			<a href="__MODULE__/Index/online">
				<i class="qqicon qqic15"></i>
				<span>在线留言</span>
			</a>
		</li>
		<li>
			<a href="__MODULE__/Index/contact">
				<i class="qqicon qqic16"></i>
				<span>联系电话</span>
			</a>
		</li>
		<li>
			<a href="" id="top">
				<i class="qqicon qqic17"></i>
			</a>
		</li>
	</ul>
</div>