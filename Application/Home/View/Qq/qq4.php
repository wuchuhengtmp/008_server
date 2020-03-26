<link type="text/css" rel="stylesheet" href="__HOME_CSS__/qq.css" />
<script type="text/javascript" src="__HOME_JS__/qq.js"></script>

<div class="qq2" id="qq2">
	<span class="side">
		在线咨询
		<a class="del2" href="">×</a>
	</span>
	<div class="sidecon">
		<div class="schd">
			<h6>在线咨询</h6>
			<p>Online consulting</p>
			<a class="del" href=""></a>
		</div>
		<ul class="scbd">
		    <?php
		    //获取客服列表
		    $qq=new \Common\Model\QqModel();
		    $qqlist=$qq->getQqList();
		    foreach ($qqlist as $ql)
		    {
		    	echo '<li>
				          <a href="http://wpa.qq.com/msgrd?v=3&uin='.$ql['num'].'&site=qq&menu=yes" target="_blank">
					        <i class="qqicon qqic3"></i>
					        '.$ql['title'].'
				          </a>
			         </li>';
		    }
		    ?>
		</ul>
	</div>
</div>

