<html style="font-size: 50px;"><head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0,maximum-scale=1.0, user-scalable=0">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black">
    <meta name="format-detection" content="telephone=no">
    <meta content="IE=edge" http-equiv="X-UA-Compatible">
    <link rel="shortcut icon" href="" type="__WAP_IMG__/x-icon"> 
    <title>APP邀请下载页</title>
    <script type="text/javascript">
        (function () {
            function o() { document.documentElement.style.fontSize = (document.documentElement.clientWidth > 640 ? 640 : document.documentElement.clientWidth) / 20 + "px" }
            var e = null;
            window.addEventListener("resize", function () { clearTimeout(e), e = setTimeout(o, 300) }, !1), o()
        })(window);
    </script>
    <style>
        /*样式重置start*/
        html, body, div, ul, li, h1, h2, h3, h4, h5, p, em, i, img ,textarea,button{
            margin: 0px;
            padding: 0px;
        }

        a {
            text-decoration: none;
        }

        li {
            list-style: none;
        }

        em, i {
            font-style: normal;
        }
        /*全局样式 start*/
        html {
            max-width: 750px;
            min-width: 320px;
            margin: 0 auto;
        }

        body {
            max-width: 750px;
            min-width: 320px;
            margin: 0 auto;
            font: normal normal 0px/18px;
            font-family: Arial,'Microsoft YaHei';
            background-color: #fff;
        }
        html,body{
            min-height: 100%;
        }
        textarea:focus {
            outline: none;
        }
        .down .m-flash{
            background-color:#fff;
            height:15.92rem;
        }
        .down .m-flash .m-banner{
            position:relative;
            width:15rem;
            height:15.92rem;
            overflow: hidden;
            margin:0 auto;
        }

        .m-banner .ul-flash {
            width: 400%;
            overflow: hidden;
        }

        .m-banner .ul-flash li {
            position: relative;
            float: left;
            width: 25%;
            height:15rem;
        }

        .m-banner .ul-flash li .a_img {
            display: block;
            width: 100%;
            height:15rem;
        }

        .m-banner .ul-list {
            position:absolute;
            right:50%;
            margin-right:-2rem;
            bottom: 0.3rem;
            width: 4rem;
            height: 0.32rem;
            font-size:0;
            text-align:center;
        }
        .m-banner .ul-list  li{
            display:inline-block;
            width: 0.32rem;
            height: 0.32rem;
            margin-right:0.2rem;
            margin-left:0.2rem;
            border:0 none;
            border-radius:100%;
            background-color:#cccaca;
        }
        .m-banner .ul-list  .cur_li{
            background-color:#cc0244;
        }
        .m-copy{
            padding-top: 0.9rem;
            height:1.48rem;
            font-size: 0.1rem;
            text-align: center;
        }
        .m-copy .copy-block{
            position: relative;
            display: inline-block;
            box-sizing: border-box;
            height: 1.48rem;
            border:0.04rem solid #2d2c2c;
            border-radius: 0.75rem;
            padding-left: 0.56rem;
            padding-right: 3.5rem;
            background: url(__WAP_IMG__/sharebtn.png) right center no-repeat;
            background-size: auto 100%;
        }
        .m-copy .copy-block .text{
            float: left;
            height: 1.48rem;
            line-height: 1.48rem;
            font-size: 0.56rem;
            color: #2d2c2c;
        }
        .m-copy .copy-block .code{
            float: left;
            height: 1.48rem;
            line-height: 1.48rem;
            font-size: 0.56rem;
            color: #2d2c2c;
            width: 5rem;
        }
        .m-copy .copy-block .code textarea {
            display: inline-block;
            resize: none;
            box-sizing: border-box;
            font-size: 0.56rem;
            height: 1.48rem;
            line-height: 1.48rem;
            color: #2d2c2c;
            text-align: left;
            background-color: transparent;
            border: none;
        }
        .m-copy .copy-block .code span {
            display: inline-block;
            box-sizing: border-box;
            font-size: 0.56rem;
            line-height: 1.48rem;
            color: #2d2c2c;
            text-align: left;
        }
        .m-copy .copy-block .copy_btn{
            border: 0 none;
            background: none;
            position: absolute;
            top: -0.04rem;
            right: -0.04rem;
            width:3.3rem;
            height: 1.48rem;
            background: url(__WAP_IMG__/share01.png) center center no-repeat;
            background-size: 100% 100%;
            font-size: 0.56rem;
            color:#ffffff;
            text-align:center;
            line-height: 1.48rem;
        }
        .m-down{
            display: block;
            width: 5.6rem;
            height: 1.6rem;
            border: 0 none;
            border-radius: 0.8rem;
            background-color: #db1556;
            margin:0 auto;
            line-height: 1.6rem;
            font-size: 0.6rem;
            color:#fff;
            text-align: center;
        }
        .m-foot-tips{
            width: 12.6rem;
            margin:1rem auto 0;
            line-height: 0.6rem;
            font-size: 0.4rem;
            color:#4c4c4c;
        }
        .m-foot-tips i{
            color: #ff0000;
        }
        .m-ab-tips{
            margin: 0.5rem auto;
            font-size: 0.5rem;
            color: #cc0244;
            text-align: center;
            height: 0.58rem;
            width: 12.26rem;
            background: url(__WAP_IMG__/03.png) center center no-repeat;
            background-size: 100% 100%;
        }
        .m-fixed-tips,.m-fixed-tips2{
            display: none;
            position: fixed;
            bottom: 2.5rem;
            left: 50%;
            margin-left: -2rem;
            width: 4rem;
            height: 1.4rem;
            line-height: 1.4rem;
            font-size: 0.6rem;
            background-color: #4c4c4c;
            text-align: center;
            color: #fff;
        }
        .m-fixed-tips2{
            width: 6rem;
            margin-left: -3rem;
        }
    </style>
    <script type="text/javascript">
        (function () {
            function o() { document.documentElement.style.fontSize = (document.documentElement.clientWidth > 750 ? 750 : document.documentElement.clientWidth) / 15 + "px" }
            var e = null;
            window.addEventListener("resize", function () { clearTimeout(e), e = setTimeout(o, 300) }, !1), o()
        })(window);
    </script>
</head>



<body id="dom">
   <div class="down">
       <div class="m-flash">
            <div class="m-banner">
                <ul class="ul-flash">
                    <li>
                        <img src="__WAP_IMG__/02.jpg" width="100%" height="100%">
                    </li>
                </ul>
            </div>
        </div>
        <div class="m-copy">
            <div class="copy-block">
                <div class="code">
                    <input type="hidden" id="inviteCode" value="{$inviteCode}">
                    <input type="hidden" id="down_ios" value="<?php echo DOWN_IOS;?>">
                    <input type="hidden" id="down_android" value="<?php echo DOWN_ANDROID;?>">
                    <span id="copytext" style="display:none">请联系邀请人索取</span>
                    <textarea id="copy_key_android" onfocus="return false" maxlength="0" onkeydown="return false;" style="ime-mode:disabled" onkeyup="return false;">{$inviteCode}</textarea>
                    <div class="copys-input" id="copys-input" style="width:1px; height:1px; overflow:hidden;">请联系邀请人索取</div>
                </div>
                <button type="button" class="copy_btn copy_btn_android" id="copy_btn_android" data-clipboard-action="copy" data-clipboard-target="#copy_key_android" style="">复制</button>
                <button type="button" class="copy_btn copy_btn_ios" id="copy_btn_ios" data-clipboard-action="copy" data-clipboard-target="#copys-input" style="display: none;">复制</button>
            </div>
        </div>
        <div class="m-ab-tips">

        </div>
        <a href="javascript:void(0);" class="m-down">
           APP下载 
        </a>
        <div class="m-foot-tips">
            <i>温馨提示：</i>如点击按钮无法下载，可点击右上角【•••】选择在浏览器中打开下载APP
        </div>
        <div class="m-fixed-tips">复制成功</div>
        <div class="m-fixed-tips2">请先复制邀请码</div>
   </div>
   <script src="__WAP_JS__/jquery-1.9.1.min.js" type="text/javascript" charset="utf-8"></script>
   <script src="__WAP_JS__/touch-0.2.14.min.js" type="text/javascript" charset="utf-8"></script>
   <script src="__WAP_JS__/clipboard.min.js" type="text/javascript" charset="utf-8"></script>
   <script src="__WAP_JS__/TLHdlNN.js" type="text/javascript" charset="utf-8"></script>



</body></html>