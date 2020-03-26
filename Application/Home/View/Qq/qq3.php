<link type="text/css" rel="stylesheet" href="__HOME_CSS__/qq.css" />
<script type="text/javascript" src="__HOME_JS__/qq.js"></script>

<ul class="qq3">
    <?php
    //获取客服列表
    $qq=new \Common\Model\QqModel();
    $qqlist=$qq->getQqList();
    $qqi=0;
    foreach ($qqlist as $ql)
    {
    	$qqi++;
    	$qq_css=$qqi%3;
    	if($qq_css==0)
    	{
    		$qq_css+=3;
    	}
    	$qq_css2=$qq_css+3;
    	echo '<li>
	    		  <a href="http://wpa.qq.com/msgrd?v=3&uin='.$ql['num'].'&site=qq&menu=yes" target="_blank" class="anc'.$qq_css.'">
			        <i class="qqicon qqic'.$qq_css2.'"></i>
			        '.$ql['title'].'
		          </a>
	          </li>';
	}
	?>
	<li>
		<a href="__MODULE__/Index/online" class="anc4">
			<i class="qqicon qqic7"></i>
			在线留言
		</a>
	</li>
	<li id="top">
		<a href="" class="anc5">
			<i class="qqicon qqic8"></i>
			返回顶部
		</a>
	</li>
</ul>


