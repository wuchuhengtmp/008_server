<link type="text/css" rel="stylesheet" href="__HOME_CSS__/qq.css" />
<script type="text/javascript" src="__HOME_JS__/qq.js"></script>

<ul class="qq1">
    <?php
    //获取客服列表
    $qq=new \Common\Model\QqModel();
    $qqlist=$qq->getQqList();
    foreach ($qqlist as $ql)
    {
    	echo '<li>
		          <a href="http://wpa.qq.com/msgrd?v=3&uin='.$ql['num'].'&site=qq&menu=yes" target="_blank">
			        <i class="qqicon qqic1"></i>
			        '.$ql['title'].'
		          </a>
	         </li>';
	}
	?>
	<li id="top">
		<a href="">
			<i class="qqicon qqic2"></i>
			返回顶部
		</a>
	</li>
</ul>


