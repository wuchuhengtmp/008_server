<link rel="stylesheet" type="text/css" href="__HOME_CSS__/dmoo.css" />

<div class="dBanner">
	<img src="{$bannerMsg.img}" alt="{$bannerMsg.title}" />
</div>
<!--dBanner-->
<div class="dcontainer3 clearfix">
	<div class="drecruitment">
		<div class="drehd">
			<div class="drehdInner">
				<h2>加入我们</h2>
				<p class="en">JOIN US</p>
			</div>
		</div>
		<div class="drebd">
			<div class="dpos clearfix">
				<span class="fr">{$msg.salary}</span>
				<span class="fl">
					<em class="font18">{$msg.job_name}</em>
					{$msg.address}
				</span>
			</div>
			<!--dpos-->
			<div class="posdes">
				<?php 
				//是否全职
				if($msg['is_full']=='1')
				{
					$full='全职';
				}else {
					$full='兼职';
				}
				//性别
				switch ($msg['sex'])
				{
					case '1':
						$sex='男性';
						break;
					case '2':
						$sex='女性';
						break;
					case '3':
						$sex='男女不限';
						break;
				}
				?>
				<span class="mgr34"><i class="zicon zic10"></i>{$full}</span>
				<span class="mgr34"><i class="zicon zic1"></i>{$msg.exp}</span>
				<span class="mgr34"><i class="zicon zic2"></i>{$msg.education}</span>
				<span class="mgr34"><i class="zicon zic11"></i>{$msg.major}</span>
				<span class="mgr34"><i class="zicon zic12"></i>{$sex} {$msg.age}</span>
				<span class="mgr34"><i class="zicon zic3"></i>{$msg.num}</span>
				<span><i class="zicon zic13"></i>{$msg.deadline}截止</span><br>
				<span><i class="zicon zic4"></i>{$msg.pubtime}发布</span>
			</div>
			<!--posdes-->
			<div class="dpostag">
				<?php 
				//招聘标签-薪酬
				if($msg['mark_salary'])
				{
					$job_mark_salary=json_decode(job_mark_salary,true);
					$mark_salary_arr=explode(',', $msg['mark_salary']);
					foreach ($job_mark_salary as $k=>$v)
					{
						if(in_array($k, $mark_salary_arr))
						{
							echo '<span class="mgr15">'.$v.'</span>';
						}
					}
				}
				//招聘标签-保障
				if($msg['mark_insurance'])
				{
					$job_mark_insurance=json_decode(job_mark_insurance,true);
					$mark_insurance_arr=explode(',', $msg['mark_insurance']);
					foreach ($job_mark_insurance as $k=>$v)
					{
						if(in_array($k, $mark_insurance_arr))
						{
							echo '<span class="mgr15">'.$v.'</span>';
						}
					}
				}
				//招聘标签-假期
				if($msg['mark_holiday'])
				{
					$job_mark_holiday=json_decode(job_mark_holiday,true);
					$mark_holiday_arr=explode(',', $msg['mark_holiday']);
					foreach ($job_mark_holiday as $k=>$v)
					{
						if(in_array($k, $mark_holiday_arr))
						{
							echo '<span class="mgr15">'.$v.'</span>';
						}
					}
				}
				//招聘标签-补贴活动
				if($msg['mark_subsidy'])
				{
					$job_mark_subsidy=json_decode(job_mark_subsidy,true);
					$mark_subsidy_arr=explode(',', $msg['mark_subsidy']);
					foreach ($job_mark_subsidy as $k=>$v)
					{
						if(in_array($k, $mark_subsidy_arr))
						{
							echo '<span class="mgr15">'.$v.'</span>';
						}
					}
				}
				?>
			</div>
			<!--dpostag-->
			<div class="dreCon">
				<h4>岗位职责：</h4>
				<div class="dreCons">
					<?php 
					if($msg['duty'])
					{
						echo '<p>'.nl2br($msg['duty']).'</p>';
					}
					?>
				</div>
			</div>
			<!--dreCon-->
			<div class="dreCon">
				<h4>任职要求：</h4>
				<div class="dreCons">
					<?php 
					if($msg['requirement'])
					{
						echo '<p>'.nl2br($msg['requirement']).'</p>';
					}
					?>
				</div>
			</div>
			<!--dreCon-->
			<div class="dreCon nbd">
				<h4>联系方式：</h4>
				<div class="dreCons">
					<?php 
					if($msg['contact'])
					{
						echo '<p>'.nl2br($msg['contact']).'</p>';
					}
					?>
				</div>
			</div>
			<p class="tc">
				<a target="_blank" href="__CONTROLLER__/employment/job_id/{$msg.job_id}" class="btnExper">投递简历</a>
			</p>
		</div>
		<!--drebd-->
	</div>
	<!--recruitment-->
</div>
<!--dcontainer-->
