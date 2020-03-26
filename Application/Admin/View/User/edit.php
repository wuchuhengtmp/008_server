<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link href="__ADMIN_CSS__/bootstrap.min14ed.css?v=3.3.6" rel="stylesheet">
<link href="__ADMIN_CSS__/font-awesome.min93e3.css?v=4.4.0" rel="stylesheet">
<link href="__ADMIN_CSS__/plugins/iCheck/custom.css" rel="stylesheet">
<link href="__ADMIN_CSS__/animate.min.css" rel="stylesheet">
<link href="__ADMIN_CSS__/style.min862f.css?v=4.1.0" rel="stylesheet">
<script src="__ADMIN_JS__/jquery.min.js?v=2.1.4"></script>
<script src="__ADMIN_JS__/plugins/iCheck/icheck.min.js"></script>
<script src="__ADMIN_JS__/plugins/layer/laydate/laydate.js"></script>
<script>
$(document).ready(function(){$(".i-checks").iCheck({checkboxClass:"icheckbox_square-green",radioClass:"iradio_square-green",})});
</script>

</head>

<body class="gray-bg">
	<div class="wrapper wrapper-content animated fadeInRight">
		<div class="row">
			<div class="col-sm-12">
				<div class="ibox float-e-margins">
					<div class="ibox-title">
						<h3>当前位置：会员管理 &raquo; 会员列表 &raquo; 编辑会员<a class="pull-right" href="__CONTROLLER__/index/group_id/{$msg.group_id}">返回上一页 <i class="fa fa-angle-double-right"></i></a></h3>
					</div>
				</div>
			</div>
			<div class="row">
            <div class="col-sm-12">
                <div class="ibox float-e-margins">
                    <div class="ibox-content">
                    <form action="__ACTION__/uid/{$msg['uid']}"  class="form-horizontal" method="post" enctype="multipart/form-data">
                    	<input type="hidden" name="oldemail" value="{$msg['email']}">
        				<input type="hidden" name="oldphone" value="{$msg['phone']}">
                            <div class="form-group">
                                <label class="col-sm-2 control-label">登录用户名</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" name="username" value="{$msg['username']}" placeholder="">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label">新密码</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" name="password" value="" placeholder="">
                                    <span class="help-block m-b-none text-danger">不填写则保持原有密码，长度不少于6位</span>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label">EMAIL</label>
                                <div class="col-sm-10">
                                    <input class="form-control" name="email" value="{$msg['email']}" placeholder="">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label">手机号码</label>
                                <div class="col-sm-10">
                                    <input class="form-control" name="phone" value="{$msg['phone']}" placeholder="">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label">备注姓名</label>
                                <div class="col-sm-10">
                                    <input class="form-control" name="remark" value="{$msg['remark']}" placeholder="">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label">授权码</label>
                                <div class="col-sm-10">
                                    <input class="form-control" name="auth_code" value="{$msg['auth_code']}" placeholder="">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label">积分</label>
                                <div class="col-sm-10">
                                    <input class="form-control" name="point" value="{$msg['point']}" placeholder="">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label">余额</label>
                                <div class="col-sm-10">
                                    <input class="form-control" name="balance" value="{$msg['balance']}" placeholder="">
                                    <span class="help-block m-b-none text-danger">涉及到客户账户余额，请谨慎修改</span>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label">推荐人手机号码</label>
                                <?php 
                                if($msg['referrer_id']) {
                                	$User=new \Common\Model\UserModel();
                                	$referrerMsg=$User->getUserMsg($msg['referrer_id']);
                                }
                                ?>
                                <div class="col-sm-10">
                                    <input class="form-control" name="referrer_phone" value="{$referrerMsg['phone']}" placeholder="">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label">淘宝账号</label>
                                <div class="col-sm-10">
                                    <input class="form-control" name="tb_uid" value="{$msg['tb_uid']}" placeholder="">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label">淘宝推广位</label>
                                <div class="col-sm-10">
                                    <input class="form-control" name="tb_pid_master" value="{$msg['tb_pid_master']}" placeholder="">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label">经验值</label>
                                <div class="col-sm-10">
                                    <input class="form-control" name="exp" value="{$msg['exp']}" placeholder="">
                                    <span class="help-block m-b-none text-danger">涉及到客户账户经验值，请谨慎修改</span>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label">所属分组</label>
                                <div class="col-sm-10">
                                    <select class="form-control m-b" name="group_id">
                                    	<option value="">--请选择所属分组--</option>
                                         <?php 
                                         foreach($glist as $g) {
                                             if($g['id']==$msg['group_id']) {
                                                 $select='selected';
                                             }else {
                                                 $select='';
                                             }
                                             echo '<option value="'.$g['id'].'" '.$select.'>--'.$g['title'].'--</option>';
                                         }
                                          ?>
                                    </select>
                                    <span class="help-block m-b-none text-danger" id="gAjax"></span>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label">是否冻结</label>
                                <div>
                                    <div class="radio i-checks">
                                        <label>
                                        	<input type="radio" name="is_freeze" value="N" <?php if($msg['is_freeze']=='N'){echo 'checked';} ?> > <i></i>正常使用
                                        	<input type="radio" name="is_freeze" value="Y" <?php if($msg['is_freeze']=='Y'){echo 'checked';} ?> > <i></i>冻结
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label">是否终生会员</label>
                                <div>
                                    <div class="radio i-checks">
                                        <label>
                                        	<input type="radio" name="is_forever" value="Y" <?php if($msg['is_forever']=='Y'){echo 'checked';} ?> > <i></i>是
                                        	<input type="radio" name="is_forever" value="N" <?php if($msg['is_forever']=='N'){echo 'checked';} ?> > <i></i>否
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label">是否代理商</label>
                                <div>
                                    <div class="radio i-checks">
                                        <label>
                                        	<input type="radio" name="is_agent" value="N" <?php if($msg['is_agent']=='N'){echo 'checked';} ?> > <i></i>否
                                        	<input type="radio" name="is_agent" value="Y" <?php if($msg['is_agent']=='Y'){echo 'checked';} ?> > <i></i>是
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label">是否为分享VIP</label>
                                <div>
                                    <div class="radio i-checks">
                                        <label>
                                        	<input type="radio" name="is_share_vip" value="Y" <?php if($msg['is_share_vip']=='Y'){echo 'checked';} ?> > <i></i>是
                                        	<input type="radio" name="is_share_vip" value="N" <?php if($msg['is_share_vip']=='N'){echo 'checked';} ?> > <i></i>否
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label">会员到期时间</label>
                                <div class="col-sm-10">
                                    <input class="form-control layer-date" name="expiration_date" value="{$msg['expiration_date']}" placeholder="" onclick="laydate({istime: true, format: 'YYYY-MM-DD hh:mm:ss'})">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label">注册时间</label>
                                <div class="col-sm-10">
                                    <input class="form-control" name="" value="{$msg['register_time']}" placeholder="" disabled>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label">注册IP</label>
                                <div class="col-sm-10">
                                    <input class="form-control" name="" value="{$msg['register_ip']}" placeholder="" disabled>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label">最后登录时间</label>
                                <div class="col-sm-10">
                                    <input class="form-control" name="" value="{$msg['last_login_time']}" placeholder="" disabled>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label">最后登录IP</label>
                                <div class="col-sm-10">
                                    <input class="form-control" name="" value="{$msg['last_login_ip']}" placeholder="" disabled>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label">登录次数</label>
                                <div class="col-sm-10">
                                    <input class="form-control" name="" value="{$msg['login_num']}" placeholder="" disabled>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label">第三方应用类型</label>
                                <div class="col-sm-10">
                                    <input class="form-control" name="" value="{$msg['third_type']}" placeholder="" disabled>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label">第三方应用ID</label>
                                <div class="col-sm-10">
                                    <input class="form-control" name="" value="{$msg['openid']}" placeholder="" disabled>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-sm-4 col-sm-offset-2">
                                    <button class="btn btn-primary" type="submit" id="sub"><i class="fa fa-check"></i>&nbsp;编辑会员</button>
                                    <button class="btn btn-white" type="reset"><i class="fa fa-refresh"></i>&nbsp;重置</button>
                                </div>
                            </div>
                    </form>
                    </div>
                </div>
            </div>
        </div>
		</div>
	</div>
</body>
</html>