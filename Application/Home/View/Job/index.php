<link rel="stylesheet" type="text/css" href="__HOME_CSS__/dmoo.css" />
<script src="__HOME_JS__/form.js" type="text/javascript" charset="utf-8"></script>


<div class="dBanner">
	<img src="{$bannerMsg.img}" alt="{$bannerMsg.title}" />
</div>
<!--dBanner-->
<div class="dcontainer4 clearfix">
	<div class="drecruitment">
		<div class="drehd">
			<div class="drehdInner">
				<h2>加入我们</h2>
				<p class="en">JOIN US</p>
			</div>
		</div>
		<div class="drebd">
			<ul class="dreclist clearfix">
				<?php 
				foreach ($list as $l)
				{
					echo '<li>
					<h4>'.$l['job_name'].'</h4>
					<p>
						<i class="zicon zic5"></i>
						'.$l['salary'].'
					</p>
					<p>
						<i class="zicon zic6"></i>
						'.$l['education'].'
					</p>
					<p>
						<i class="zicon zic7"></i>
						'.$l['exp'].'
					</p>
					<p>
						<i class="zicon zic8"></i>
						'.$l['address'].'
					</p>
					<p>
						<i class="zicon zic9"></i>
						'.$l['pubtime'].'
					</p>
					<p class="tc">
						<a href="__CONTROLLER__/jobview/job_id/'.$l['job_id'].'" class="btnMore">查看</a>
					</p>
				</li>';
				}
				?>
			</ul>
			<div class="pages">
				{$page}
			</div>
		</div>
		<!--drebd-->
	</div>
	<!--recruitment-->
</div>
<!--dcontainer-->