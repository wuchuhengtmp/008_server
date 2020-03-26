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
    <div class="header"><!-- <a href=""></a> --></div>
    <div class="back-box">
        <a href="#rule" class="gz-link"></a>
        <div class="back-img"><img src="__WAP_IMG__/back01.png"></div>
        <div class="back-bottom text-center">
            <div class="back-date-tt">拉新活动剩余时间</div>
            <p><input type="hidden" name="countDown" value="{$msg['end_time']}"> <span></span></p>
        </div>
    </div>
    <div class="back-b">
        <div><img src="__WAP_IMG__/back02.png"></div>
        <div class="back-b-box text-center">
            <div class="back-b-tt">我的邀请</div>
            <div class="clearfix">
                <div class="col-xs-6">
                    <div class="number">邀请人数</div>
                    <div class="number-c"><span>{$people_num}</span>位</div>
                    <a href="__CONTROLLER__/teamList/rid/{$msg.id}/uid/{$uid}" class="number-a"><span>查看详情</span></a>
                </div>
                <div class="col-xs-6">
                    <div class="number">邀请奖励</div>
                    <div class="number-c"><span>{$reward_money}</span>{$reward_unit}</div>
                    <a href="__CONTROLLER__/teamList/rid/{$msg.id}/uid/{$uid}" class="number-a"><span>查看详情</span></a>
                </div>
            </div>
        </div>
    </div>
    <div class="title-back text-center">邀请好友 我将获得</div>
    <div class="clearfix">
        <?php 
        foreach ($rewardList as $l){
            $reward_css='';
            $reward_str='最高奖励';
            
            //开始人数区间
            $start_interval = $l['start_interval'];
            //闭合人数区间
            $end_interval = $l['end_interval'];
            $people_str='邀请'.$start_interval.'-'.$end_interval.'位';
            //奖励数量
            $reward_num = $l['reward_num'];
            //总奖励
            $reward_allnum = $reward_num*$end_interval;
            // 闭合区间设置为无限大
            if ($end_interval === '0') {
                //$end_interval = "∞";
                $people_str='邀请'.$start_interval.'位以上';
                
                $reward_css='number-span-d';
                $reward_str='最低奖励';
                //总奖励
                $reward_allnum = $reward_num*$start_interval;
            }
            
            
            echo '<div class="col-xs-4">
            <div class="number-back">
                <div><img src="__WAP_IMG__/number-back.png"></div>
                <div class="number-back-box text-center">
                    <div class="number-t">¥'.$reward_allnum.'</div>
                    <div class="number-bottom">
                        <div class="number-span '.$reward_css.'"><span>'.$reward_str.'</span></div>
                        <div class="number-p">'.$people_str.'</div>
                    </div>
                </div>
            </div>
        </div>';
        }
        ?>
    </div>
    <div class="bz"><img src="__WAP_IMG__/bz.png"></div>
    <div class="tt-gz text-center">活动规则</div>
    <div class="gz-p" id="rule">
    	{$msg.content}
    </div>
    <div class="bottom">
        <a href="dmooo://toShare"><img src="__WAP_IMG__/btn.png"></a>
    </div>
</body>
<script src="__WAP_JS__/jquery.min.js"></script>
<script src="__WAP_JS__/countDown.js"></script>
<script type="text/javascript">
    $("input[name='countDown']").each(function () {
        var time_end=this.value;
        var con=$(this).next("span");
        var _=this.dataset;
        countDown(con,{
            title:_.title,//优先级最高,填充在prefix位置
            prefix:_.prefix,//前缀部分
            suffix:_.suffix,//后缀部分
            time_end:time_end//要到达的时间
        })
        //提供3个事件分别为:启动,重启,停止
        .on("countDownStarted countDownRestarted  countDownEnded ",function (arguments) {
            console.info(arguments);
        });
    });

</script>
</html>