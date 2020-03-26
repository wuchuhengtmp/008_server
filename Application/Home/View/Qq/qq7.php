<link type="text/css" rel="stylesheet" href="__HOME_CSS__/qq.css" />
<script type="text/javascript" src="__HOME_JS__/qq.js"></script>
<div class="qq7">
	<ul class="qqlist">
	    <?php
	    //获取客服列表
	    $qq=new \Common\Model\QqModel();
	    $qqlist=$qq->getQqList();
	    foreach ($qqlist as $ql)
	    {
	    	echo '<li>
			         <a href="http://wpa.qq.com/msgrd?v=3&uin='.$ql['num'].'&site=qq&menu=yes" target="_blank" class="qqicon qqic18"></a>
		          </li>';
	    }
	    ?>
		<li>
			<a href="__MODULE__/Index/online" class="qqicon qqic19"></a>
		</li>
		<li id="top">
			<a href="" class="qqicon qqic20"></a>
		</li>
	</ul>
</div>

