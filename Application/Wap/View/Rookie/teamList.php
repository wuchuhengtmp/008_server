<!doctype html>
<html lang="zh-CN">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width,minimum-scale=1,maximum-scale=1,initial-scale=1,user-scalable=no" />
    <title>{$web_title}-<?php echo WEB_TITLE;?></title>
    <meta name="keywords" content="{$web_keywords}" />
    <meta name="description" content="{$web_description}" />

    <!-- Bootstrap -->
    <link href="__WAP_CSS__/bootstrap.css" rel="stylesheet">
    <link href="__WAP_CSS__/slick.css" rel="stylesheet">
    <link href="__WAP_CSS__/style.css" rel="stylesheet">
</head>
<body>
    <div class="index-back"></div>
    <div class="header"><a href="__CONTROLLER__/index/uid/{$uid}"></a></div>
    <div class="back-box">
        <div class="back-img"><img src="__WAP_IMG__/back01.png"></div>
        <div class="back-bottom text-center">
            <div class="back-date-tt">拉新活动兑换奖励时间</div>
            <p class="p-sm"><span class="payment-time"><span class="active-time pull-right"><em class="time_d">{$start_m}</em>月<em class="time_h">{$start_d}</em>—<em class="time_d">{$end_m}</em>月<em class="time_s">{$end_d}</em></span></span></p>
        </div>
    </div>
    <div class="tt-img"><img src="__WAP_IMG__/tt03.png"></div>
    <div class="back-rs">
        <ul class="rs-list text-center">
            <?php 
            $i=0;
            foreach ($userList as $l){
                $i++;
                //手机号码
                $phone=substr_replace($l['phone'],'****',3,4);
                //用户头像
                $avatar='__WAP_IMG__/logo.png';
                if($l['avatar']){
                    $avatar=$l['avatar'];
                }
                //注册时间
                $register_date=date('Y.m.d',strtotime($l['register_time']));
                echo '<li>
                <div class="name">NO.'.$i.'</div>
                <div class="rs-img"><img src="'.$avatar.'"></div>
                <div class="rs-phone">'.$phone.'</div>
                <div class="rs-date">'.$register_date.'</div>
            </li>';
            }
            ?>
        </ul>
    </div>
    <div class="text-center btn-p"><a href="javascript:alert('活动进行中，稍后领取奖励！');"><img src="__WAP_IMG__/next.png"></a></div>
    <div><img src="__WAP_IMG__/bottom.png"></div>
</body>
<script src="__WAP_JS__/jquery.min.js"></script>
</html>