<?php
/**
 * 用户账号管理接口
 */
namespace App\Controller;
use App\Common\Controller\AuthController;

class UserAccountController extends AuthController
{
	/**
	 * 注册
	 * @param string $phone:手机号码
	 * @param string $pwd1:密码
	 * @param string $pwd2:重复密码
	 * @param string $code:手机短信验证码
	 * @param string $auth_code:邀请码
	 * @param string $referrer_phone:推荐人手机号/用户名
	 * @return array
	 * @return @param code:返回码
	 * @return @param msg:返回码说明
	 */
	public function register()
	{
		if(trim(I('post.phone')) and trim(I('post.pwd1')) and trim(I('post.pwd2')) and trim(I('post.code')))
		{
			//判断手机验证码是否正确
			$code=trim(I('post.code'));
			$phone=trim(I('post.phone'));
//			$Sms=new \Common\Model\SmsModel();
//			$res_code=$Sms->checkCode($phone, $code);
//            $res_code['code']!=0
			// todo 准备修改成下面mob短信方式
            Vendor('mob.mob','','.class.php');
            $mob=new \mob();
            $res_code=$mob->checkSmsCode($phone,$code);
			if($res_code['code']!=200) {
				//短信验证码错误
				$res=$res_code;
			}else {
				//短信验证码正确
				//判断密码格式
				$pwd1=trim(I('post.pwd1'));
				$pwd2=trim(I('post.pwd2'));
				$User=new \Common\Model\UserModel();
				$res_pwd=$User->checkPwdFormat($pwd1, $pwd2);
				if($res_pwd['code']!=0) {
					//密码不符合规范
					$res=$res_pwd;
				}else {
					//密码符合规范
					//判断手机号码是否已注册
					$res_phone=$User->checkPhone($phone);
					if($res_phone['code']!=0) {
						//手机号已被注册
						$res=$res_phone;
					}else {
						//查询手机归属地
						$result_phone=queryPhoneOwner($phone);
						$phone_province=$result_phone['data']['province'];
						$phone_city=$result_phone['data']['city'];
						
						//判断推荐人是否存在
						if(trim(I('post.referrer_phone'))) {
							$referrer_phone=trim(I('post.referrer_phone'));
							$res_referrer=$User->where("phone='$referrer_phone' or username='$referrer_phone'")->find();
							if($res_referrer) {
								$referrer_id=$res_referrer['uid'];
							}else {
								//推荐人不存在
								$res=array(
										'code'=>$this->ERROR_CODE_USER['REFERRER_NOT_EXISTS'],
										'msg'=>$this->ERROR_CODE_USER_ZH[$this->ERROR_CODE_USER['REFERRER_NOT_EXISTS']]
								);
								echo json_encode ($res,JSON_UNESCAPED_UNICODE);
								exit();
							}
						}else {
							$referrer_id=null;
						}
						if(trim(I('post.referrer_id'))) {
							$referrer_id=trim(I('post.referrer_id'));
						}
						//邀请码推荐人
						if(trim(I('post.auth_code'))) {
							$auth_code=trim(I('post.auth_code'));
							$res_referrer=$User->where("auth_code='$auth_code'")->find();
							if($res_referrer) {
								$referrer_id=$res_referrer['uid'];
							}else {
								//推荐人不存在
								$res=array(
										'code'=>$this->ERROR_CODE_USER['REFERRER_NOT_EXISTS'],
										'msg'=>$this->ERROR_CODE_USER_ZH[$this->ERROR_CODE_USER['REFERRER_NOT_EXISTS']]
								);
								echo json_encode ($res,JSON_UNESCAPED_UNICODE);
								exit();
							}
						}
						
						//可以注册
						$data=array(
								'group_id'=>1,//普通会员组
								'password'=>$User->encrypt($pwd1),
								'phone'=>$phone,
								'point'=>POINT_REGISTER,
								'register_time'=>date('Y-m-d H:i:s'),
								'register_ip'=>getIP(),
								'referrer_id'=>$referrer_id,
								'phone_province'=>$phone_province,
								'phone_city'=>$phone_city,
						);
						if(!$User->create($data)) {
							//验证不通过
							$res=array(
									'code'=>$this->ERROR_CODE_COMMON['PARAMETER_FORMAT_ERROR'],
									'msg'=>$User->getError()
							);
						}else {
							//开启事务
							$User->startTrans();
							$res_regiser=$User->add($data);
							if($res_regiser!==false) {
								$uid=$res_regiser;
								//修改用户团队路径
								$path=$User->getPath($uid,array());
								//绑定邀请码
								$UserAuthCode=new \Common\Model\UserAuthCodeModel();
								//查询第一个未使用的邀请码
								$codeMsg=$UserAuthCode->where("is_used='N'")->order('id asc')->find();
								$data=array(
										'path'=>$path,
										'auth_code'=>$codeMsg['auth_code']
								);
								$res_path=$User->where("uid='$uid'")->save($data);
								//修改邀请码状态
								$data_code=array(
										'is_used'=>'Y',
										'user_id'=>$uid
								);
								$code_id=$codeMsg['id'];
								$res_code=$UserAuthCode->where("id='$code_id'")->save($data_code);
								//保存用户详情
								$UserDetail=new \Common\Model\UserDetailModel();
								$data_detail=array(
										'user_id'=>$res_regiser
								);
								if(!$UserDetail->create($data_detail)) {
									//验证不通过
									//回滚
									$User->rollback();
									$res=array(
											'code'=>$this->ERROR_CODE_COMMON['PARAMETER_FORMAT_ERROR'],
											'msg'=>$UserDetail->getError()
									);
								}else {
									$res_detail=$UserDetail->add($data_detail);
									//判断注册是否赠送积分
									$UserPointRecord=new \Common\Model\UserPointRecordModel();
									if(POINT_REGISTER>0) {
										//保存积分变动记录
										$res_point_record=$UserPointRecord->addLog($uid, POINT_REGISTER,POINT_REGISTER, 'register');
									}else {
										$res_point_record=true;
									}
									if($res_detail!==false and $res_path!==false and $res_point_record!==false and $res_code!==false)
									{
										//判断是否赠送推荐注册积分
										if($referrer_id) {
											if(POINT_RECOMMEND_REGISTER>0) {
												//给推荐人赠送积分
												$res_referrer_point=$User->where("uid='$referrer_id'")->setInc('point',POINT_RECOMMEND_REGISTER);
												//推荐人积分存量
												$allpoint=$res_referrer['point']+POINT_RECOMMEND_REGISTER;
												//保存积分变动记录
												$res_referrer_point_record=$UserPointRecord->addLog($referrer_id,POINT_RECOMMEND_REGISTER,$allpoint,'recommend_register');
											}else {
												$res_referrer_point=true;
												$res_referrer_point_record=true;
											}
											if($res_referrer_point!==false and $res_referrer_point_record!==false) {
												//判断推荐人是否可以升级为VIP
												$referrerMsg=$User->getUserMsg($referrer_id);
												$new_exp=$referrerMsg['exp']+USER_UPGRADE_REGISTER;//新经验值
												$data_referrer=array(
												    'exp'=>$new_exp,
												);
												//判断推荐人应该升级到那个会员组
												//大于当前会员组，并且小于新经验值的最大值
												$group_id=$referrerMsg['group_id'];
												$UserGroup=new \Common\Model\UserGroupModel();
												$res_group=$UserGroup->where("id>$group_id and exp<=$new_exp")->order('exp desc')->field('id')->find();
												if($res_group['id']){
												    $data_referrer['group_id']=$res_group['id'];
												}
												$res_referrer_g=$User->where("uid='$referrer_id'")->save($data_referrer);
												
												//保存经验值变动记录
												$UserExpRecord=new \Common\Model\UserExpRecordModel();
												$res_exp_record=$UserExpRecord->addLog($uid,USER_UPGRADE_REGISTER,$new_exp,'recommend_register');
												
												if($res_referrer_g!==false and $res_exp_record!==false) {
													//提交
													$User->commit();
													$res=array(
															'code'=>$this->ERROR_CODE_COMMON['SUCCESS'],
															'msg'=>'成功',
													);
												}else {
													//注册失败
													//回滚
													$User->rollback();
													//数据库错误
													$res=array(
															'code'=>$this->ERROR_CODE_COMMON['DB_ERROR'],
															'msg'=>$this->ERROR_CODE_COMMON_ZH[$this->ERROR_CODE_COMMON['DB_ERROR']]
													);
												}
											}else {
												//注册失败
												//回滚
												$User->rollback();
												//数据库错误
												$res=array(
														'code'=>$this->ERROR_CODE_COMMON['DB_ERROR'],
														'msg'=>$this->ERROR_CODE_COMMON_ZH[$this->ERROR_CODE_COMMON['DB_ERROR']]
												);
											}
										}else {
											//提交事务
											$User->commit();
											$res=array(
													'code'=>$this->ERROR_CODE_COMMON['SUCCESS'],
													'msg'=>'成功',
											);
										}
									}else {
										//回滚
										$User->rollback();
										//数据库错误
										$res=array(
												'code'=>$this->ERROR_CODE_COMMON['DB_ERROR'],
												'msg'=>$this->ERROR_CODE_COMMON_ZH[$this->ERROR_CODE_COMMON['DB_ERROR']]
										);
									}
								}
							}else {
								//回滚
								$User->rollback();
								//数据库错误
								$res=array(
										'code'=>$this->ERROR_CODE_COMMON['DB_ERROR'],
										'msg'=>$this->ERROR_CODE_COMMON_ZH[$this->ERROR_CODE_COMMON['DB_ERROR']]
								);
							}
						}
					}
				}
			}
		}else {
			//参数不正确，参数缺失
			$res=array(
					'code'=>$this->ERROR_CODE_COMMON['PARAMETER_ERROR'],
					'msg'=>$this->ERROR_CODE_COMMON_ZH[$this->ERROR_CODE_COMMON['PARAMETER_ERROR']]
			);
		}
		echo json_encode ($res,JSON_UNESCAPED_UNICODE);
	}
	
	/**
	 * 登录
	 * @param string $account:账号
	 * @param string $pwd:密码
	 * @return array
	 * @return @param code:返回码
	 * @return @param msg:返回码说明
	 * @return @param uid:用户ID
	 * @return @param group_id:用户组ID
	 * @return @param token:身份令牌
	 */
	public function login()
	{
		if(trim(I('post.account')) and trim(I('post.pwd'))) {
			$account = I('post.account');
			$pwd = I('post.pwd');
			$User = new \Common\Model\UserModel();
			$res = $User->login($account, $pwd);
		}else {
			//参数不正确，参数缺失
			$res=array(
					'code'=>$this->ERROR_CODE_COMMON['PARAMETER_ERROR'],
					'msg'=>$this->ERROR_CODE_COMMON_ZH[$this->ERROR_CODE_COMMON['PARAMETER_ERROR']]
			);
		}
		echo json_encode ($res,JSON_UNESCAPED_UNICODE);
	}

    /**
     * mob短信登录
     * @param string $phone:手机号码
     * @param string $code:手机短信验证码
     * @return array
     * @return @param code:返回码
     * @return @param msg:返回码说明
     * @return @param uid:用户ID
     * @return @param group_id:用户组ID
     * @return @param token:身份令牌
     */
    public function mobSmsLogin()
    {
        if(trim(I('post.phone')) and trim(I('post.code'))) {
            //判断手机验证码是否正确
            $code=trim(I('post.code'));
            $phone=trim(I('post.phone'));
            Vendor('mob.mob','','.class.php');
            $mob=new \mob();
            $res_code=$mob->checkSmsCode($phone,$code);
            if($res_code['code']!=200) {
                //短信验证码错误
                $res=$res_code;
            }else {
                $User = new \Common\Model\UserModel();
                $res = $User->mobSmsLogin($phone);
            }
        }else {
            //参数不正确，参数缺失
            $res=array(
                'code'=>$this->ERROR_CODE_COMMON['PARAMETER_ERROR'],
                'msg'=>$this->ERROR_CODE_COMMON_ZH[$this->ERROR_CODE_COMMON['PARAMETER_ERROR']]
            );
        }
        echo json_encode ($res,JSON_UNESCAPED_UNICODE);
    }
	
	/**
	 * 使用手机找回密码
	 * @param string $phone:手机号码
	 * @param string $code:手机验证码
	 * @param string $pwd:新密码
	 * @return array
	 * @return @param code:返回码
	 * @return @param msg:返回码说明
	 */
	public function findPwdByPhone()
	{
		if(trim(I('post.phone')) and trim(I('post.code')) and trim(I('post.pwd')))
		{
			$phone=trim(I('post.phone'));
			$code=trim(I('post.code'));
			//判断手机验证码是否正确
//			$Sms=new \Common\Model\SmsModel();
//			$res_code=$Sms->checkCode($phone, $code);
            Vendor('mob.mob','','.class.php');
            $mob=new \mob();
            $res_code=$mob->checkSmsCode($phone,$code);
			if($res_code['code']!=200) {
				//手机验证码不正确
				$res=$res_code;
			}else {
				$pwd=I('post.pwd');
				if(strlen($pwd)<6) {
					//新密码不少于6位
					$res=array(
							'code'=>$this->ERROR_CODE_COMMON['PASSWORD_FORMAT_ERROR'],
							'新密码不少于6位'
					);
				}else {
					//判断手机号码是否已注册
					$User=new \Common\Model\UserModel();
					$res_exist=$User->where("phone='$phone'")->find();
					if($res_exist) {
						//重新设置密码
						$data=array(
								'password'=>$User->encrypt($pwd)
						);
						$res_p=$User->where("phone='$phone'")->save($data);
						if($res_p!==false) {
							//找回密码成功
							$res=array(
									'code'=>$this->ERROR_CODE_COMMON['SUCCESS'],
									'msg'=>'找回密码成功，请使用新密码登录'
							);
						}else {
							//数据库错误
							$res=array(
									'code'=>$this->ERROR_CODE_COMMON['DB_ERROR'],
									'msg'=>$this->ERROR_CODE_COMMON_ZH[$this->ERROR_CODE_COMMON['DB_ERROR']]
							);
						}
					}else {
						// 该手机号码尚未注册
						$res=array(
								'code'=>$this->ERROR_CODE_USER['PHONE_NON_REGISTERED'],
								'msg'=>$this->ERROR_CODE_USER_ZH[$this->ERROR_CODE_USER['PHONE_NON_REGISTERED']]
						);
					}
				}
			}
		}else {
			//参数不正确，参数缺失
			$res=array(
					'code'=>$this->ERROR_CODE_COMMON['PARAMETER_ERROR'],
					'msg'=>$this->ERROR_CODE_COMMON_ZH[$this->ERROR_CODE_COMMON['PARAMETER_ERROR']]
			);
		}
		echo json_encode ($res,JSON_UNESCAPED_UNICODE);
	}
	
	/**
	 * 判断手机号码是否注册
	 * @param string $phone:手机号码
	 * @return array
	 * @return @param code:返回码
	 * @return @param msg:返回码说明
	 * @return @param data:返回数据
	 */
	public function checkPhone()
	{
		if(trim(I('post.phone'))) {
			$phone=trim(I('post.phone'));
			//判断手机号码是否注册过
			$User=new \Common\Model\UserModel();
			$res_phone=$User->where("phone='$phone'")->field('uid')->find();
			if($res_phone['uid']) {
				$is_register='Y';
			}else {
				$is_register='N';
			}
			$data=array(
					'is_register'=>$is_register
			);
			$res=array(
					'code'=>$this->ERROR_CODE_COMMON['SUCCESS'],
					'msg'=>'成功',
					'data'=>$data
			);
		}else {
			//参数不正确，参数缺失
			$res=array(
					'code'=>$this->ERROR_CODE_COMMON['PARAMETER_ERROR'],
					'msg'=>$this->ERROR_CODE_COMMON_ZH[$this->ERROR_CODE_COMMON['PARAMETER_ERROR']]
			);
		}
		echo json_encode ($res,JSON_UNESCAPED_UNICODE);
	}
	
	/**
	 * 判断第三方社交平台账号是否注册
	 * @param string $openid:第三方社交平台ID
	 * @param string $type:第三方社交平台类型，微信：wx、QQ：qq、新浪微博：sina
	 * @return array
	 * @return @param code:返回码
	 * @return @param msg:返回码说明
	 * @return @param data:返回数据
	 */
	public function checkRegisterOauth()
	{
		if(trim(I('post.openid')) and trim(I('post.type'))) {
			$openid=trim(I('post.openid'));
			$type=trim(I('post.type'));
			//判断用户是否已注册
			$UserOauth=new \Common\Model\UserOauthModel();
			$res_exist=$UserOauth->is_register($openid, $type);
			if($res_exist) {
				//已注册
				//修改用户登录信息
				$uid=$res_exist;
				$User=new \Common\Model\UserModel();
				$UserMsg=$User->getUserMsg($uid);
				$token=$User->getAccessToken($uid);
				$data=array(
						'login_num'=>$UserMsg['login_num']+1,
						'last_login_time'=>date('Y-m-d H:i:s'),
						'last_login_ip'=>getIP(),
						'token'=>$token,
						'token_createtime'=>time()
				);
				if(!$User->create($data)) {
					//验证不通过
					$res=array(
							'code'=>$this->ERROR_CODE_COMMON['PARAMETER_FORMAT_ERROR'],
							'msg'=>$User->getError()
					);
				}else {
					$res_save=$User->where("uid='$uid'")->save($data);
					if($res_save!==false) {
						$data=array(
								'uid'=>$uid,
								'token'=>$token,
								'phone'=>$UserMsg['phone']
						);
						$res=array(
								'code'=>$this->ERROR_CODE_COMMON['SUCCESS'],
								'msg'=>'成功，已授权',
								'data'=>$data
						);
					}else {
						$res=array(
								'code'=>$this->ERROR_CODE_COMMON['DB_ERROR'],
								'msg'=>$this->ERROR_CODE_COMMON_ZH[$this->ERROR_CODE_COMMON['DB_ERROR']]
						);
					}
				}
			}else {
				//未注册
				$res=array(
						'code'=>1,
						'msg'=>'尚未绑定手机，请跳转绑定手机页面'
				);
			}
		}else {
			//参数不正确，参数缺失
			$res=array(
					'code'=>$this->ERROR_CODE_COMMON['PARAMETER_ERROR'],
					'msg'=>$this->ERROR_CODE_COMMON_ZH[$this->ERROR_CODE_COMMON['PARAMETER_ERROR']]
			);
		}
		echo json_encode ($res,JSON_UNESCAPED_UNICODE);
	}
	
	/**
	 * 第三方社交平台账号注册
	 * @param string $phone:手机号码
	 * @param string $pwd1:密码，已去掉
	 * @param string $code:手机短信验证码
	 * @param string $auth_code:邀请码
	 * @param string $openid:第三方社交平台ID
	 * @param string $type:第三方社交平台类型，微信：wx、QQ：qq、新浪微博：sina
	 * @param string $nickname:昵称
	 * @param file $avatar:头像
	 * @param string $sex:性别，1男 2女 3保密
	 * @return array
	 * @return @param code:返回码
	 * @return @param msg:返回码说明
	 * @return @param data:返回数据
	 */
	public function loginOauth()
	{
		if(trim(I('post.openid')) and trim(I('post.type')) and trim(I('post.nickname')) and trim(I('post.avatar')) and trim(I('post.phone')) and trim(I('post.code')))
		{
			//判断手机验证码是否正确
			$code=trim(I('post.code'));
			$phone=trim(I('post.phone'));
			$Sms=new \Common\Model\SmsModel();
			$res_code=$Sms->checkCode($phone, $code);
			if($res_code['code']!=0) {
				//短信验证码错误
				$res=$res_code;
			}else {
				//短信验证码正确
				/* //判断密码格式
				$pwd1=trim(I('post.pwd1'));
				if( strlen($pwd1) < 6 )
				{
					//密码不少于6位
					$res=array(
							'code'=>$this->ERROR_CODE_COMMON['PASSWORD_FORMAT_ERROR'],
							'msg'=>$this->ERROR_CODE_COMMON_ZH[$this->ERROR_CODE_COMMON['PASSWORD_FORMAT_ERROR']]
					);
					echo json_encode ($res,JSON_UNESCAPED_UNICODE);
					exit();
				} */
	
				$openid=trim(I('post.openid'));
				$type=trim(I('post.type'));
				//判断用户是否已注册
				$UserOauth=new \Common\Model\UserOauthModel();
				$res_exist=$UserOauth->is_register($openid, $type);
	
				$User=new \Common\Model\UserModel();
				//判断手机号码是否已注册
				$UserMsg=$User->where("phone='$phone'")->find();
				if($UserMsg) {
					//手机号已被注册
					$uid=$UserMsg['uid'];
					$token=$User->getAccessToken($uid);
					$data=array(
							//'password'=>$User->encrypt($pwd1),
							'login_num'=>$UserMsg['login_num']+1,
							'last_login_time'=>date('Y-m-d H:i:s'),
							'last_login_ip'=>getIP(),
							'token'=>$token,
							'token_createtime'=>time()
					);
					if(!$User->create($data)) {
						//验证不通过
						$res=array(
								'code'=>$this->ERROR_CODE_COMMON['PARAMETER_FORMAT_ERROR'],
								'msg'=>$User->getError()
						);
					}else {
						//开启事务
						$User->startTrans();
						$res_save=$User->where("uid='$uid'")->save($data);
						//昵称
						$nickname=trim(I('post.nickname'));
						//判断是否为emojo表情
						if ( haveEmojiChar($nickname) ) {
						    //做base64编码
						    $nickname=base64_encode($nickname);
						}
						//头像
						$avatar=trim(I('post.avatar'));
						//性别
						$sex=trim(I('post.sex'));
						//保存用户详情
						$data_detail=array(
								'nickname'=>$nickname,
								'avatar'=>$avatar,
								'sex'=>$sex
						);
						$UserDetail=new \Common\Model\UserDetailModel();
						$res_detail=$UserDetail->where("user_id='$uid'")->save($data_detail);
						if($res_save!==false and $res_detail!==false) {
							if($res_exist) {
								//第三方已注册，修改所属用户
								$data_oauth=array(
										'user_id'=>$uid
								);
								$res_oauth=$UserOauth->where("openid='$openid' and type='$type'")->save($data_oauth);
								if($res_oauth!==false) {
									//提交
									$User->commit();
									$data=array(
											'uid'=>$uid,
											'token'=>$token
									);
									$res=array(
											'code'=>$this->ERROR_CODE_COMMON['SUCCESS'],
											'msg'=>'成功，已授权',
											'data'=>$data
									);
								}else {
									//回滚
									$User->rollback();
									$res=array(
											'code'=>$this->ERROR_CODE_COMMON['DB_ERROR'],
											'msg'=>$this->ERROR_CODE_COMMON_ZH[$this->ERROR_CODE_COMMON['DB_ERROR']]
									);
								}
							}else {
								//第三方未注册，新增
								$res_oauth=$UserOauth->register($uid, $openid, $type);
								if($res_oauth!==false) {
									//提交
									$User->commit();
									$data=array(
											'uid'=>$uid,
											'token'=>$token
									);
									$res=array(
											'code'=>$this->ERROR_CODE_COMMON['SUCCESS'],
											'msg'=>'成功，已授权',
											'data'=>$data
									);
								}else {
									//回滚
									$User->rollback();
									$res=array(
											'code'=>$this->ERROR_CODE_COMMON['DB_ERROR'],
											'msg'=>$this->ERROR_CODE_COMMON_ZH[$this->ERROR_CODE_COMMON['DB_ERROR']]
									);
								}
							}
						}else {
							//回滚
							$User->rollback();
							$res=array(
									'code'=>$this->ERROR_CODE_COMMON['DB_ERROR'],
									'msg'=>$this->ERROR_CODE_COMMON_ZH[$this->ERROR_CODE_COMMON['DB_ERROR']]
							);
						}
					}
				}else {
					//手机号尚未注册
					//邀请码推荐人
					if(trim(I('post.auth_code'))=='' and trim(I('post.referrer_phone'))=='' ) {
						//新注册用户推荐人邀请码必填
						$res=array(
								'code'=>$this->ERROR_CODE_USER['REFERRER_NOT_EXISTS'],
								'msg'=>'新注册用户推荐人邀请码必填'
						);
						echo json_encode ($res,JSON_UNESCAPED_UNICODE);
						exit();
					}
					if(trim(I('post.auth_code'))) {
						$auth_code=trim(I('post.auth_code'));
						$res_referrer=$User->where("auth_code='$auth_code'")->find();
						if($res_referrer) {
							$referrer_id=$res_referrer['uid'];
						}else {
							//推荐人不存在
							$res=array(
									'code'=>$this->ERROR_CODE_USER['REFERRER_NOT_EXISTS'],
									'msg'=>$this->ERROR_CODE_USER_ZH[$this->ERROR_CODE_USER['REFERRER_NOT_EXISTS']]
							);
							echo json_encode ($res,JSON_UNESCAPED_UNICODE);
							exit();
						}
					}
						
					if(trim(I('post.referrer_phone'))) {
						$referrer_phone=trim(I('post.referrer_phone'));
						$res_referrer=$User->where("phone='$referrer_phone'")->find();
						if($res_referrer) {
							$referrer_id=$res_referrer['uid'];
						}else {
							//推荐人不存在
							$res=array(
									'code'=>$this->ERROR_CODE_USER['REFERRER_NOT_EXISTS'],
									'msg'=>$this->ERROR_CODE_USER_ZH[$this->ERROR_CODE_USER['REFERRER_NOT_EXISTS']]
							);
							echo json_encode ($res,JSON_UNESCAPED_UNICODE);
							exit();
						}
					}
					
					//查询手机归属地
					$result_phone=queryPhoneOwner($phone);
					$phone_province=$result_phone['data']['province'];
					$phone_city=$result_phone['data']['city'];
					$token=$User->getAccessToken(0);
					$data=array(
							'group_id'=>1,
							//'password'=>$User->encrypt($pwd1),
							'phone'=>$phone,
							'point'=>POINT_REGISTER,//注册获得积分
							'register_time'=>date('Y-m-d H:i:s'),
							'register_ip'=>getIP(),
							'login_num'=>1,
							'last_login_time'=>date('Y-m-d H:i:s'),
							'last_login_ip'=>getIP(),
							'token'=>$token,
							'token_createtime'=>time(),
							'referrer_id'=>$referrer_id,
							'phone_province'=>$phone_province,
							'phone_city'=>$phone_city,
					);
					if(!$User->create($data)) {
						//验证不通过
						$res=array(
								'code'=>$this->ERROR_CODE_COMMON['PARAMETER_FORMAT_ERROR'],
								'msg'=>$User->getError()
						);
					}else {
						//验证通过
						//开启事务
						$User->startTrans();
						$res_add=$User->add($data);
						if($res_add!==false) {
							$user_id=$res_add;
							//修改用户团队路径
							$path=$User->getPath($user_id,array());
							//绑定邀请码
							$UserAuthCode=new \Common\Model\UserAuthCodeModel();
							//查询第一个未使用的邀请码
							$codeMsg=$UserAuthCode->where("is_used='N'")->order('id asc')->find();
							$data=array(
									'path'=>$path,
									'auth_code'=>$codeMsg['auth_code']
							);
							$res_path=$User->where("uid='$user_id'")->save($data);
							//修改邀请码状态
							$data_code=array(
									'is_used'=>'Y',
									'user_id'=>$user_id
							);
							$code_id=$codeMsg['id'];
							$res_code=$UserAuthCode->where("id='$code_id'")->save($data_code);
							
							//判断注册是否赠送积分
							$UserPointRecord=new \Common\Model\UserPointRecordModel();
							if(POINT_REGISTER>0) {
								//保存积分变动记录
								$res_point_record=$UserPointRecord->addLog($user_id, POINT_REGISTER,POINT_REGISTER, 'register');
							}else {
								$res_point_record=true;
							}
							
							//昵称
							$nickname=trim(I('post.nickname'));
							//判断是否为emojo表情
							if ( haveEmojiChar($nickname) ) {
							    //做base64编码
							    $nickname=base64_encode($nickname);
							}
							//头像
							$avatar=trim(I('post.avatar'));
							//性别
							$sex=trim(I('post.sex'));
							//保存用户详情
							$data_detail=array(
									'user_id'=>$user_id,
									'nickname'=>$nickname,
									'avatar'=>$avatar,
									'sex'=>$sex
							);
							$UserDetail=new \Common\Model\UserDetailModel();
							if(!$UserDetail->create($data_detail)) {
								//验证不通过
								//回滚
								$User->rollback();
								$res=array(
										'code'=>$this->ERROR_CODE_COMMON['PARAMETER_FORMAT_ERROR'],
										'msg'=>$UserDetail->getError()
								);
							}else {
								//验证通过
								$res_detail=$UserDetail->add($data_detail);
								if($res_detail!==false and $res_path!==false and $res_code!==false and $res_point_record!==false)
								{
									//判断是否赠送推荐注册积分
									if($referrer_id) {
										if(POINT_RECOMMEND_REGISTER>0) {
											//给推荐人赠送积分
											$res_referrer_point=$User->where("uid='$referrer_id'")->setInc('point',POINT_RECOMMEND_REGISTER);
											//推荐人积分存量
											$allpoint=$res_referrer['point']+POINT_RECOMMEND_REGISTER;
											//保存积分变动记录
											$res_referrer_point_record=$UserPointRecord->addLog($referrer_id,POINT_RECOMMEND_REGISTER,$allpoint,'recommend_register');
										}else {
											$res_referrer_point=true;
											$res_referrer_point_record=true;
										}
										if($res_referrer_point!==false and $res_referrer_point_record!==false) {
										    //判断推荐人是否可以升级为VIP
										    $referrerMsg=$User->getUserMsg($referrer_id);
										    $new_exp=$referrerMsg['exp']+USER_UPGRADE_REGISTER;//新经验值
										    $data_referrer=array(
										        'exp'=>$new_exp,
										    );
										    //判断推荐人应该升级到那个会员组
										    //大于当前会员组，并且小于新经验值的最大值
										    $group_id=$referrerMsg['group_id'];
										    $UserGroup=new \Common\Model\UserGroupModel();
										    $res_group=$UserGroup->where("id>$group_id and exp<=$new_exp")->order('exp desc')->field('id')->find();
										    if($res_group['id']){
										        $data_referrer['group_id']=$res_group['id'];
										    }
										    $res_referrer_g=$User->where("uid='$referrer_id'")->save($data_referrer);
											if($res_referrer_g!==false) {
												
											}else {
												//注册失败
												//回滚
												$User->rollback();
												//数据库错误
												$res=array(
														'code'=>$this->ERROR_CODE_COMMON['DB_ERROR'],
														'msg'=>$this->ERROR_CODE_COMMON_ZH[$this->ERROR_CODE_COMMON['DB_ERROR']]
												);
												echo json_encode ($res,JSON_UNESCAPED_UNICODE);
												exit();
											}
										}else {
											//注册失败
											//回滚
											$User->rollback();
											//数据库错误
											$res=array(
													'code'=>$this->ERROR_CODE_COMMON['DB_ERROR'],
													'msg'=>$this->ERROR_CODE_COMMON_ZH[$this->ERROR_CODE_COMMON['DB_ERROR']]
											);
											echo json_encode ($res,JSON_UNESCAPED_UNICODE);
											exit();
										}
									}else {
										//不存在推荐人
									}
									
									if($res_exist) {
										//第三方已注册，修改所属用户
										$data_oauth=array(
												'user_id'=>$user_id
										);
										$res_oauth=$UserOauth->where("openid='$openid' and type='$type'")->save($data_oauth);
										if($res_oauth!==false) {
											//提交
											$User->commit();
											$data=array(
													'uid'=>$user_id,
													'token'=>$token
											);
											$res=array(
													'code'=>$this->ERROR_CODE_COMMON['SUCCESS'],
													'msg'=>'成功，已授权',
													'data'=>$data
											);
										}else {
											//回滚
											$User->rollback();
											$res=array(
											    'code'=>$this->ERROR_CODE_COMMON['DB_ERROR'],
												'msg'=>$this->ERROR_CODE_COMMON_ZH[$this->ERROR_CODE_COMMON['DB_ERROR']]
											);
											echo json_encode ($res,JSON_UNESCAPED_UNICODE);
											exit();
										}
									}else {
										//第三方未注册，新增
										$res_oauth=$UserOauth->register($user_id, $openid, $type);
										if($res_oauth!==false) {
											//提交
											$User->commit();
											$data=array(
													'uid'=>$user_id,
													'token'=>$token
											);
											$res=array(
												'code'=>$this->ERROR_CODE_COMMON['SUCCESS'],
												'msg'=>'成功，已授权',
												'data'=>$data
											);
										}else {
											//回滚
											$User->rollback();
											$res=array(
												'code'=>$this->ERROR_CODE_COMMON['DB_ERROR'],
												'msg'=>$this->ERROR_CODE_COMMON_ZH[$this->ERROR_CODE_COMMON['DB_ERROR']]
											);
											echo json_encode ($res,JSON_UNESCAPED_UNICODE);
											exit();
										}
									}
								}else {
									//回滚
									$User->rollback();
									//数据库错误
									$res=array(
											'code'=>$this->ERROR_CODE_COMMON['DB_ERROR'],
											'msg'=>$this->ERROR_CODE_COMMON_ZH[$this->ERROR_CODE_COMMON['DB_ERROR']]
									);
								}
							}
						}else {
							//回滚
							$User->rollback();
							//数据库错误
							$res=array(
									'code'=>$this->ERROR_CODE_COMMON['DB_ERROR'],
									'msg'=>$this->ERROR_CODE_COMMON_ZH[$this->ERROR_CODE_COMMON['DB_ERROR']]
							);
						}
					}
				}
			}
		}else {
			//参数不正确，参数缺失
			$res=array(
					'code'=>$this->ERROR_CODE_COMMON['PARAMETER_ERROR'],
					'msg'=>$this->ERROR_CODE_COMMON_ZH[$this->ERROR_CODE_COMMON['PARAMETER_ERROR']]
			);
		}
		echo json_encode ($res,JSON_UNESCAPED_UNICODE);
	}

    /**
     * 判断mob秒验手机号是否注册
     * @param string $mobToken:客户端的token
     * @param string $opToken:客户端返回的运营商token
     * @param string $operator:客户端返回的运营商，CMCC:中国移动通信, CUCC:中国联通通讯, CTCC:中国电信
     * @return array
     * @return @param code:返回码
     * @return @param msg:返回码说明
     * @return @param data:返回数据
     */
    public function checkRegisterMobPhone()
    {
        if(trim(I('post.mobToken')) and trim(I('post.opToken')) and trim(I('post.operator')))
        {
            //判断手机验证码是否正确
            $token=trim(I('post.mobToken'));
            $opToken=trim(I('post.opToken'));
            $operator=trim(I('post.operator'));
            Vendor('mob.mob','','.class.php');
            $mob=new \mob();
//            $res_code=$mob->checkSecVerify($token,$opToken,$operator);
            $res_code=array(
                'code'=>200,
                'msg'=>'测试',
                'data'=>array('isValid'=>1,'phone'=>18960172536)
            );
            if($res_code['code']!=200) {
                //秒验验证错误
                $res=$res_code;
            }else {
                if ($res_code['data']['isValid'] == 1){
                    $phone=$res_code['data']['phone'];
                    //判断用户是否已注册
                    $User = new \Common\Model\UserModel();
                    $res_exist = $User->is_freeze($phone);
                    if($res_exist['code']==0) {
                        $User = new \Common\Model\UserModel();
                        // 修改最后登录信息
                        $uid=$res_exist['data']['userMsg']['uid'];
                        $token=$User->getAccessToken($uid);
                        $data=array(
                            'login_num'=>$res_exist['data']['userMsg']['login_num']+1,
                            'last_login_time'=>date('Y-m-d H:i:s'),
                            'last_login_ip'=>getIP(),
                            'token'=>$token,
                            'token_createtime'=>time()
                        );
                        $res_c=$User->where("uid='$uid'")->save($data);
                        if($res_c!==false) {
                            $res=array(
                                'code'=>$User->ERROR_CODE_COMMON['SUCCESS'],
                                'msg'=>'登录成功',
                                'uid'=>$res_exist['data']['userMsg']['uid'],
                                'group_id'=>$res_exist['data']['userMsg']['group_id'],
                                'token'=>$token
                            );
                        }else {
                            //数据库错误
                            $res=array(
                                'code'=>$this->ERROR_CODE_COMMON['DB_ERROR'],
                                'msg'=>$this->ERROR_CODE_COMMON_ZH[$this->ERROR_CODE_COMMON['DB_ERROR']]
                            );
                        }
                    }else {
                        //未注册或者被冻结
                        $res_exist['data']['phone']=$phone;
                        $res=$res_exist;
                    }
                }else{
                    //mob秒验没通过
                    $res=$res_code;
                }
            }
        }else {
            //参数不正确，参数缺失
            $res=array(
                'code'=>$this->ERROR_CODE_COMMON['PARAMETER_ERROR'],
                'msg'=>$this->ERROR_CODE_COMMON_ZH[$this->ERROR_CODE_COMMON['PARAMETER_ERROR']]
            );
        }
        echo json_encode ($res,JSON_UNESCAPED_UNICODE);
    }

    /**
     * mob秒验手机号注册
     * @param string $phone:手机号码
     * @param string $pwd1:密码
     * @param string $pwd2:重复密码
     * @param string $auth_code:邀请码
     * @return array
     * @return @param code:返回码
     * @return @param msg:返回码说明
     * @return @param data:返回数据
     */
    public function loginMobPhone()
    {
        if(trim(I('post.phone')) and trim(I('post.pwd1')) and trim(I('post.pwd2')))
        {
            //判断密码格式
            $phone=trim(I('post.phone'));
            $pwd1=trim(I('post.pwd1'));
            $pwd2=trim(I('post.pwd2'));
            $User=new \Common\Model\UserModel();
            $res_pwd=$User->checkPwdFormat($pwd1, $pwd2);
            if($res_pwd['code']!=0) {
                //密码不符合规范
                $res=$res_pwd;
            }else {
                //密码符合规范
                //判断手机号码是否已注册
                $res_phone=$User->checkPhone($phone);
                if($res_phone['code']!=0) {
                    //手机号已被注册
                    $res=$res_phone;
                }else {
                    //查询手机归属地
                    $result_phone=queryPhoneOwner($phone);
                    $phone_province=$result_phone['data']['province'];
                    $phone_city=$result_phone['data']['city'];

                    //邀请码推荐人
                    if(trim(I('post.auth_code'))) {
                        $auth_code=trim(I('post.auth_code'));
                        $res_referrer=$User->where("auth_code='$auth_code'")->find();
                        if($res_referrer) {
                            $referrer_id=$res_referrer['uid'];
                        }else {
                            //推荐人不存在
                            $res=array(
                                'code'=>$this->ERROR_CODE_USER['REFERRER_NOT_EXISTS'],
                                'msg'=>$this->ERROR_CODE_USER_ZH[$this->ERROR_CODE_USER['REFERRER_NOT_EXISTS']]
                            );
                            echo json_encode ($res,JSON_UNESCAPED_UNICODE);
                            exit();
                        }
                    }

                    //可以注册
                    $data=array(
                        'group_id'=>1,//普通会员组
                        'password'=>$User->encrypt($pwd1),
                        'phone'=>$phone,
                        'point'=>POINT_REGISTER,
                        'register_time'=>date('Y-m-d H:i:s'),
                        'register_ip'=>getIP(),
                        'referrer_id'=>$referrer_id,
                        'phone_province'=>$phone_province,
                        'phone_city'=>$phone_city,
                    );
                    if(!$User->create($data)) {
                        //验证不通过
                        $res=array(
                            'code'=>$this->ERROR_CODE_COMMON['PARAMETER_FORMAT_ERROR'],
                            'msg'=>$User->getError()
                        );
                    }else {
                        //开启事务
                        $User->startTrans();
                        $res_regiser=$User->add($data);
                        if($res_regiser!==false) {
                            $uid=$res_regiser;
                            //修改用户团队路径
                            $path=$User->getPath($uid,array());
                            //绑定邀请码
                            $UserAuthCode=new \Common\Model\UserAuthCodeModel();
                            //查询第一个未使用的邀请码
                            $codeMsg=$UserAuthCode->where("is_used='N'")->order('id asc')->find();
                            $data=array(
                                'path'=>$path,
                                'auth_code'=>$codeMsg['auth_code']
                            );
                            $res_path=$User->where("uid='$uid'")->save($data);
                            //修改邀请码状态
                            $data_code=array(
                                'is_used'=>'Y',
                                'user_id'=>$uid
                            );
                            $code_id=$codeMsg['id'];
                            $res_code=$UserAuthCode->where("id='$code_id'")->save($data_code);
                            //保存用户详情
                            $UserDetail=new \Common\Model\UserDetailModel();
                            $data_detail=array(
                                'user_id'=>$res_regiser
                            );
                            if(!$UserDetail->create($data_detail)) {
                                //验证不通过
                                //回滚
                                $User->rollback();
                                $res=array(
                                    'code'=>$this->ERROR_CODE_COMMON['PARAMETER_FORMAT_ERROR'],
                                    'msg'=>$UserDetail->getError()
                                );
                            }else {
                                $res_detail=$UserDetail->add($data_detail);
                                //判断注册是否赠送积分
                                $UserPointRecord=new \Common\Model\UserPointRecordModel();
                                if(POINT_REGISTER>0) {
                                    //保存积分变动记录
                                    $res_point_record=$UserPointRecord->addLog($uid, POINT_REGISTER,POINT_REGISTER, 'register');
                                }else {
                                    $res_point_record=true;
                                }
                                if($res_detail!==false and $res_path!==false and $res_point_record!==false and $res_code!==false)
                                {
                                    //判断是否赠送推荐注册积分
                                    if($referrer_id) {
                                        if(POINT_RECOMMEND_REGISTER>0) {
                                            //给推荐人赠送积分
                                            $res_referrer_point=$User->where("uid='$referrer_id'")->setInc('point',POINT_RECOMMEND_REGISTER);
                                            //推荐人积分存量
                                            $allpoint=$res_referrer['point']+POINT_RECOMMEND_REGISTER;
                                            //保存积分变动记录
                                            $res_referrer_point_record=$UserPointRecord->addLog($referrer_id,POINT_RECOMMEND_REGISTER,$allpoint,'recommend_register');
                                        }else {
                                            $res_referrer_point=true;
                                            $res_referrer_point_record=true;
                                        }
                                        if($res_referrer_point!==false and $res_referrer_point_record!==false) {
                                            //判断推荐人是否可以升级为VIP
                                            $referrerMsg=$User->getUserMsg($referrer_id);
                                            $new_exp=$referrerMsg['exp']+USER_UPGRADE_REGISTER;//新经验值
                                            $data_referrer=array(
                                                'exp'=>$new_exp,
                                            );
                                            //判断推荐人应该升级到那个会员组
                                            //大于当前会员组，并且小于新经验值的最大值
                                            $group_id=$referrerMsg['group_id'];
                                            $UserGroup=new \Common\Model\UserGroupModel();
                                            $res_group=$UserGroup->where("id>$group_id and exp<=$new_exp")->order('exp desc')->field('id')->find();
                                            if($res_group['id']){
                                                $data_referrer['group_id']=$res_group['id'];
                                            }
                                            $res_referrer_g=$User->where("uid='$referrer_id'")->save($data_referrer);

                                            //保存经验值变动记录
                                            $UserExpRecord=new \Common\Model\UserExpRecordModel();
                                            $res_exp_record=$UserExpRecord->addLog($uid,USER_UPGRADE_REGISTER,$new_exp,'recommend_register');

                                            if($res_referrer_g!==false and $res_exp_record!==false) {
                                                //提交
                                                $User->commit();
                                                $token=$User->getAccessToken($uid);
                                                $msg=$User->getUserDetail($uid);
                                                $data=array(
                                                    'login_num'=>$msg['login_num']+1,
                                                    'last_login_time'=>date('Y-m-d H:i:s'),
                                                    'last_login_ip'=>getIP(),
                                                    'token'=>$token,
                                                    'token_createtime'=>time()
                                                );
                                                $res_c=$User->where("uid='$uid'")->save($data);
                                                if($res_c!==false) {
                                                    $res=array(
                                                        'code'=>$User->ERROR_CODE_COMMON['SUCCESS'],
                                                        'msg'=>'注册成功',
                                                        'uid'=>$uid,
                                                        'group_id'=>$msg['group_id'],
                                                        'token'=>$token
                                                    );
                                                }else {
                                                    //注册失败
                                                    //回滚
                                                    $User->rollback();
                                                    //数据库错误
                                                    $res=array(
                                                        'code'=>$this->ERROR_CODE_COMMON['DB_ERROR'],
                                                        'msg'=>$this->ERROR_CODE_COMMON_ZH[$this->ERROR_CODE_COMMON['DB_ERROR']]
                                                    );
                                                }
                                            }else {
                                                //注册失败
                                                //回滚
                                                $User->rollback();
                                                //数据库错误
                                                $res=array(
                                                    'code'=>$this->ERROR_CODE_COMMON['DB_ERROR'],
                                                    'msg'=>$this->ERROR_CODE_COMMON_ZH[$this->ERROR_CODE_COMMON['DB_ERROR']]
                                                );
                                            }
                                        }else {
                                            //注册失败
                                            //回滚
                                            $User->rollback();
                                            //数据库错误
                                            $res=array(
                                                'code'=>$this->ERROR_CODE_COMMON['DB_ERROR'],
                                                'msg'=>$this->ERROR_CODE_COMMON_ZH[$this->ERROR_CODE_COMMON['DB_ERROR']]
                                            );
                                        }
                                    }else {
                                        //提交事务
                                        $User->commit();
                                        $token=$User->getAccessToken($uid);
                                        $msg=$User->getUserDetail($uid);
                                        $data=array(
                                            'login_num'=>$msg['login_num']+1,
                                            'last_login_time'=>date('Y-m-d H:i:s'),
                                            'last_login_ip'=>getIP(),
                                            'token'=>$token,
                                            'token_createtime'=>time()
                                        );
                                        $res_c=$User->where("uid='$uid'")->save($data);
                                        if($res_c!==false) {
                                            $res=array(
                                                'code'=>$User->ERROR_CODE_COMMON['SUCCESS'],
                                                'msg'=>'注册成功',
                                                'uid'=>$uid,
                                                'group_id'=>$msg['group_id'],
                                                'token'=>$token
                                            );
                                        }else {
                                            //注册失败
                                            //回滚
                                            $User->rollback();
                                            //数据库错误
                                            $res=array(
                                                'code'=>$this->ERROR_CODE_COMMON['DB_ERROR'],
                                                'msg'=>$this->ERROR_CODE_COMMON_ZH[$this->ERROR_CODE_COMMON['DB_ERROR']]
                                            );
                                        }
                                    }
                                }else {
                                    //回滚
                                    $User->rollback();
                                    //数据库错误
                                    $res=array(
                                        'code'=>$this->ERROR_CODE_COMMON['DB_ERROR'],
                                        'msg'=>$this->ERROR_CODE_COMMON_ZH[$this->ERROR_CODE_COMMON['DB_ERROR']]
                                    );
                                }
                            }
                        }else {
                            //回滚
                            $User->rollback();
                            //数据库错误
                            $res=array(
                                'code'=>$this->ERROR_CODE_COMMON['DB_ERROR'],
                                'msg'=>$this->ERROR_CODE_COMMON_ZH[$this->ERROR_CODE_COMMON['DB_ERROR']]
                            );
                        }
                    }
                }
            }
        }else {
            //参数不正确，参数缺失
            $res=array(
                'code'=>$this->ERROR_CODE_COMMON['PARAMETER_ERROR'],
                'msg'=>$this->ERROR_CODE_COMMON_ZH[$this->ERROR_CODE_COMMON['PARAMETER_ERROR']]
            );
        }
        echo json_encode ($res,JSON_UNESCAPED_UNICODE);
    }
}
?>