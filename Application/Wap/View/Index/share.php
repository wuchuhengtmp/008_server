<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="viewport" content="width=device-width, initial-scale=1,minmum-scale=1,maxmum-scale=1,user-scalable=no">
<title><?php echo APP_NAME;?></title>
<link href="__WAP_CSS__/global.css" rel="stylesheet" type="text/css" />
<link href="__WAP_CSS__/commen.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="__WAP_JS__/jquery-1.9.1.min.js"></script>
<script type="text/javascript" src="__WAP_JS__/Html5.js"></script>
</head>
<body id="wrapper">
<!--抬头开始-->
<header>
<div id="header" class="clearfix">
    <h1 class="float_l"><a title="分享" href="#"></a></h1>
    
    <div class="header2 float_r">
    	<a href="__CONTROLLER__/down/inviteCode/{$auth_code}" class="dj">下载APP省更多</a>
    </div>
    <div class="text_c fz20" onclick="copy('{$auth_code}')">复制邀请码<br/>{$auth_code}</div>
</div>
</header>
<!--抬头结束-->
<div class="clear"></div>
<!--主体-->
<section>
    <!--大图-->
    <div id="banner" style="background:url({$msg.pict_url}) no-repeat center top; background-size: contain;"></div>
    <!--大图结束-->
    <div class="clear"></div>
    <div id="container">
    	<div class="main1 text_c">
        	复制红框内信息打开<APP>即可领优惠券<br />{$tkl}
        </div>
        <div align="center"><a href="javascript:;" class="more" onclick="copy('{$tkl}')">一键复制</a></div>
        <p style="color: red;font-size:18px;text-align:center" id="copy_text"></p>	
        <div class="main2" align="center">
        <div class="text_l">
        	说明：<br />在点击一键复制后，请打开手机“ <img src="__WAP_IMG__/t.gif" />”购买<br />若一键复制失败，请长摁<img src="__WAP_IMG__/y.gif" />内文字全选复制拷贝
            </div>
        </div>
        <!-- <div class="main3"></div> -->
    </div>
</section>
<!--主体结束-->
<div class="clear"></div>
<!--底部-->
<footer>
	<div id="footer">
    	
    </div>
</footer>
<!--底部结束-->
<!--ui:?/style:cyy/code:?-->
</body>
<script>
function copy(message) {
        var input = document.createElement("input");
            input.value = message;
            document.body.appendChild(input);
            input.select();
            input.setSelectionRange(0, input.value.length), document.execCommand('Copy');
            document.body.removeChild(input);
            //$.toast("复制成功", "text");
            //alert('复制成功');
            document.getElementById('copy_text').innerHTML="恭喜您复制成功！";
}
</script>
</html>
