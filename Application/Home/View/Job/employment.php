<link rel="stylesheet" type="text/css" href="__HOME_CSS__/dmoo.css" />
<script src="__HOME_JS__/form.js" type="text/javascript" charset="utf-8"></script>

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
		<form action="__ACTION__/job_id/{$msg.job_id}" method="post" enctype="multipart/form-data">
		<div class="drebd">
			<h3 class="h3">填写简历</h3>
			<ul class="dform">
				<li class="clearfix">
					<span class="spGroup fl">
						<label for="" class="lb fl">
							<span class="dtip">*</span>
							姓名：
						</label>
						<input type="text" class="inptxt inptxt2" name="truename"/>
					</span>
					<span class="spGroup fl">
						<label for="" class="lb fl" style="width: 60px;">性别：</label>
						<span class="inpgroup">
							<label for="" class="mgr34">
								<input type="radio" name="sex" value="1" class="ch" checked="checked" />
								男
							</label>
							<label for="" class="mgr34">
								<input type="radio" name="sex" value="2" class="ch">女
							</label>
							<label for="">
								<input type="radio" name="sex" value="3" class="ch">保密
							</label>
						</span>
					</span>
				</li>
				<li>
					<label for="" class="lb fl">
						<span class="dtip">*</span>
						电话：
					</label>
					<input type="text" class="inptxt" name="phone"/>
				</li>
				<li>
					<label for="" class="lb fl">
						<span class="dtip">*</span>
						民族：
					</label>
					<input type="text" class="inptxt" name="nationality"/>
				</li>
				<li>
					<label for="" class="lb fl">
						<span class="dtip">*</span>
						年龄：
					</label>
					<input type="text" class="inptxt" name="age"/>
				</li>
				<li>
					<label for="" class="lb fl">
						<span class="dtip">*</span>
						学历：
					</label>
					<select class="se" name="education">
						<option value="">请选择学历</option>
						<option value="本科">本科</option>
						<option value="专科">专科</option>
						<option value="硕士">硕士</option>
						<option value="博士">博士</option>
						<option value="博士后">博士后</option>
						<option value="高中">高中</option>
						<option value="其他">其他</option>
					</select>
				</li>
				<li>
					<label for="" class="lb fl">
						<span class="dtip">*</span>
						毕业学校：
					</label>
					<input type="text" class="inptxt" name="school"/>
				</li>
				<li>
					<label for="" class="lb fl">
						专业：
					</label>
					<input type="text" class="inptxt" name="major"/>
				</li>
				<li>
					<label for="" class="lb fl">
						住址：
					</label>
					<input type="text" class="inptxt" name="address"/>
				</li>
				<li>

					<label for="" class="lb fl">
						<span class="dtip">*</span>
						目标薪资：
					</label>
					<input type="text" class="inptxt" name="salary"/>
				</li>
				<li>
					<label for="" class="lb fl">
						求职意向：
					</label>
					<input type="text" class="inptxt" name="job_intension"/>
				</li>
				<li>
					<label for="" class="lb fl">
						特长：
					</label>
					<input type="text" class="inptxt inptxt3"  name="speciality"/>
				</li>
				<li>
					<label for="" class="lb fl">
						个人技能：
					</label>
					<input type="text" class="inptxt inptxt3"  name="skill"/>
				</li>
				<li>
					<label for="" class="lb fl">
						学习及工作经历：
					</label>
					<textarea name="exp" rows="" cols="" class="textarea"></textarea>
				</li>
				<li>

					<label for="" class="lb fl">
						自我评价：
					</label>
					<textarea name="self_assessment" rows="" cols="" class="textarea"></textarea>
				</li>
				<li>
					<label for="" class="lb fl">添加附件：</label>
					<input type="text" class="inptxt inptxt2 fl" />
					<span class="btnUpload fl" id="J_upload">
						<input type="button" name="" id="" value="上传附件简历" class="btnup" />
						<input type="file" name="file" id="" value="" accept="application/msword, application/vnd.openxmlformats-officedocument.wordprocessingml.document,application/pdf,application/rtf,image/jpeg,image/png" />
					</span>
					<span class="tips">支持doc、docx、pdf、jpg、png格式</span>
				</li>
			</ul>
			<p class="tc">
				<input type="submit" class="btnExper" style="border:0px" value="投递简历">
			</p>
		</div>
		</form>
		<!--drebd-->
	</div>
	<!--recruitment-->
</div>
<!--dcontainer-->
