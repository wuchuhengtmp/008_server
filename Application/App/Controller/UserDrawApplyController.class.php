<?php
/**
 * 用户提现申请管理
 */
namespace App\Controller;
use App\Common\Controller\AuthController;

class UserDrawApplyController extends AuthController
{
	/**
	 * 发送支付宝账号修改/绑定验证码
	 * @param string $token:用户身份令牌
	 * @return array
	 * @return @param code:返回码
	 * @return @param msg:返回码说明
	 */
	public function sendAlipayCode()
	{
		//验签
		$Sign=new \Common\Model\SignModel();
		$res_sign=$Sign->checkVerify($_POST);
		if($res_sign!==true) {
			//验签错误
			$res=array(
					'code'=>$this->ERROR_CODE_COMMON['SIGN_ERROR'],
					'msg'=>$this->ERROR_CODE_COMMON_ZH[$this->ERROR_CODE_COMMON['SIGN_ERROR']]
			);
			echo json_encode ($res,JSON_UNESCAPED_UNICODE);
			exit();
		}
		
		if(trim(I('post.token'))) {
			//判断用户身份
			$token=trim(I('post.token'));
			$User=new \Common\Model\UserModel();
			$res_token=$User->checkToken($token);
			if($res_token['code']!=0) {
				//用户身份不合法
				$res=$res_token;
			}else {
				$uid=$res_token['uid'];
				$userMsg=$User->getUserMsg($uid);
				$phone = $userMsg['phone']; // 手机号码
				if(is_phone($phone)) {
					//发送手机短信
					$sms=new \Common\Model\SmsModel();
					$content="@1@=".rand(1000,9999);
					$res=$sms->sendMessage($phone, $content, 'default');
				} else {
					//手机号码格式不正确
					$res=array(
							'code'=>$this->ERROR_CODE_COMMON['PARAMETER_ERROR'],
							'msg'=>'手机号码格式不正确'
					);
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
	 * 变更绑定支付宝账号
	 * @param string $token:用户身份令牌
	 * @param string $alipay_account:支付宝账号
	 * @param string $truename:真实姓名
	 * @param string $code:手机短信验证码
	 * @return array
	 * @return @param code:返回码
	 * @return @param msg:返回码说明
	 */
	public function changeAlipay()
	{
		//验签
		$Sign=new \Common\Model\SignModel();
		$res_sign=$Sign->checkVerify($_POST);
		if($res_sign!==true) {
			//验签错误
			$res=array(
					'code'=>$this->ERROR_CODE_COMMON['SIGN_ERROR'],
					'msg'=>$this->ERROR_CODE_COMMON_ZH[$this->ERROR_CODE_COMMON['SIGN_ERROR']]
			);
			echo json_encode ($res,JSON_UNESCAPED_UNICODE);
			exit();
		}
		
		if(trim(I('post.token')) and trim(I('post.alipay_account')) and trim(I('post.code'))  and trim(I('post.truename')))
		{
			//判断用户身份
			$token=trim(I('post.token'));
			$User=new \Common\Model\UserModel();
			$res_token=$User->checkToken($token);
			if($res_token['code']!=0) {
				//用户身份不合法
				$res=$res_token;
			}else {
				//获取用户账号信息
				$uid=$res_token['uid'];
				//判断手机验证码是否正确
				$code=trim(I('post.code'));
				$userMsg=$User->getUserMsg($uid);
				if($userMsg) {
					$phone=$userMsg['phone'];
					$Sms=new \Common\Model\SmsModel();
					$res_code=$Sms->checkCode($phone, $code);
					if($res_code['code']!=0) {
						//短信验证码错误
						$res=$res_code;
					}else {
						//短信验证码正确
						//进行支付宝账号变更
						$data=array(
								'alipay_account'=>trim(I('post.alipay_account'))
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
							$res_s=$User->where("uid='$uid'")->save($data);
							//修改真实姓名
							$data_t=array(
									'truename'=>trim(I('post.truename'))
							);
							$UserDetail=new \Common\Model\UserDetailModel();
							$res_t=$UserDetail->where("user_id='$uid'")->save($data_t);
							if($res_s!==false and $res_t!==false) {
								//提交事务
								$User->commit();
								$res=array(
										'code'=>$this->ERROR_CODE_COMMON['SUCCESS'],
										'msg'=>'成功'
								);
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
				}else {
					//用户不存在
					$res=array(
							'code'=>$this->ERROR_CODE_USER['USER_NOT_EXIST'],
							'msg'=>$this->ERROR_CODE_USER_ZH[$this->ERROR_CODE_USER['USER_NOT_EXIST']]
					);
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
	 * 获取账户类型列表
	 * @return array
	 * @return @param code:返回码
	 * @return @param msg:返回码说明
	 * @return @param data:返回数据
	 * @return @param data->list:账户类型列表
	 */
	public function getAccountType()
	{
		$list=json_decode(account_type_d,true);
		$data=array(
				'list'=>$list
		);
		$res=array(
				'code'=>$this->ERROR_CODE_COMMON['SUCCESS'],
				'msg'=>'成功',
				'data'=>$data
		);
		echo json_encode ($res,JSON_UNESCAPED_UNICODE);
	}
	
	/**
	 * 用户申请提现
	 * @param string $token:用户身份令牌
	 * @param string $account_type:提现账户类型
	 * @param string $account:提现账号
	 * @param string $truename:收款人真实姓名
	 * @param float $money:提现金额
	 * @return array
	 * @return @param code:返回码
	 * @return @param msg:返回码说明
	 */
	public function draw()
	{
		//验签
		$Sign=new \Common\Model\SignModel();
		$res_sign=$Sign->checkVerify($_POST);
		if($res_sign!==true) {
			//验签错误
			$res=array(
					'code'=>$this->ERROR_CODE_COMMON['SIGN_ERROR'],
					'msg'=>$this->ERROR_CODE_COMMON_ZH[$this->ERROR_CODE_COMMON['SIGN_ERROR']]
			);
			echo json_encode ($res,JSON_UNESCAPED_UNICODE);
			exit();
		}
		
		//每个月规定的时间段才可以提现
		$today=date('d');
		if($today<DRAW_START_DATE or $today>DRAW_END_DATE) {
			//验签错误
			$res=array(
				'code'=>1,
			    'msg'=>'每个月的'.DRAW_START_DATE.'号-'.DRAW_END_DATE.'号可以提现'
			);
			echo json_encode ($res,JSON_UNESCAPED_UNICODE);
			exit();
		}
		
		if(trim(I('post.token')) and trim(I('post.account_type')) and trim(I('post.account')) and trim(I('post.money')) and trim(I('post.truename')))
		{
			//判断用户身份
			$token=trim(I('post.token'));
			$User=new \Common\Model\UserModel();
			$res_token=$User->checkToken($token);
			if($res_token['code']!=0) {
				//用户身份不合法
				$res=$res_token;
			}else {
				$uid=$res_token['uid'];
				$money=trim(I('post.money'));
				//判断提现金额必须大于几元的整数
				if($money<DRAW_LIMIT_MONEY or is_positive_int($money)===false) {
					//提现金额必须大于几元
					$res=array(
						'code'=>$this->ERROR_CODE_USER['WITHDRAWAL_AMOUNT_MUST_BE_A_MULTIPLE_OF_10'],
					    'msg'=>'提现金额必须大'.DRAW_LIMIT_MONEY.'于元的整数'
					);
				}else {
					//判断用户余额是否足够
					$UserMsg=$User->getUserMsg($uid);
					$balance=$UserMsg['balance'];
					//减去本月获取到的淘宝客返利
					//本月淘宝客、拼多多、京东收入
					$UserBalanceRecord=new \Common\Model\UserBalanceRecordModel();
					$amount_mouth=$UserBalanceRecord->where("user_id='$uid' and status='2' and date_format(pay_time,'%Y-%m')=date_format(now(),'%Y-%m') and action in ('tbk','tbk_r','tbk_r2','tbk_rt','pdd','pdd_r','pdd_r2','pdd_rt','jd','jd_r','jd_r2','jd_rt')")->sum('money');
					$amount_mouth=$amount_mouth/100;
					if(($balance-$amount_mouth)>=$money) {
						$UserDrawApply=new \Common\Model\UserDrawApplyModel();
						//判断今天是否已提现
						$where = "TO_DAYS(apply_time) = TO_DAYS(NOW()) and user_id='$uid'";
						$res_exist=$UserDrawApply->where($where)->find();
						if($res_exist) {
							//每天只准提现一次
							$res=array(
									'code'=>$this->ERROR_CODE_USER['WITHDRAWAL_ONLY_ONCE_A_DAY'],
									'msg'=>$this->ERROR_CODE_USER_ZH[$this->ERROR_CODE_USER['WITHDRAWAL_ONLY_ONCE_A_DAY']]
							);
							echo json_encode ($res,JSON_UNESCAPED_UNICODE);
							exit();
						}
						//判断提现方式为3自动提现，并且提现金额不超过限制的方可自动转账，其他都需要后台审核
						if(DRAW_METHOD=='3' and $money<=DRAW_AUTO_MONEY) {
						    //不超过300元的提现直接自动转账
						    $data=array(
						        'user_id'=>$uid,
						        'money'=>$money,
						        'account_type'=>trim(I('post.account_type')),
						        'account'=>trim(I('post.account')),
						        'truename'=>trim(I('post.truename')),
						        'apply_time'=>date('Y-m-d H:i:s'),
						        'is_check'=>'Y',//已审核
						        'check_result'=>'Y',//审核通过
						        'check_time'=>date('Y-m-d H:i:s')
						    );
						    if(!$UserDrawApply->create($data)) {
						        //验证不通过
						        $res=array(
						            'code'=>$this->ERROR_CODE_COMMON['PARAMETER_FORMAT_ERROR'],
						            'msg'=>$UserDrawApply->getError()
						        );
						    }else {
						        //验证通过
						        //开启事务
						        $UserDrawApply->startTrans();
						        $res_add=$UserDrawApply->add($data);
						        //修改用户余额
						        $res_balance=$User->where("uid='$uid'")->setDec('balance',$money);
						        //记录用户余额变动记录
						        $UserBalanceRecord=new \Common\Model\UserBalanceRecordModel();
						        $all_money=$UserMsg['balance']-$money;
						        //保留2位小数，四舍五入
						        $all_money=round($all_money, 2);
						        $res_record=$UserBalanceRecord->addLog($uid, $money, $all_money, 'draw','2');
						        if($res_add!==false and $res_balance!==false and $res_record!==false)
						        {
						            //提交事务
						            $UserDrawApply->commit();
						            $res=array(
						                'code'=>$this->ERROR_CODE_COMMON['SUCCESS'],
						                'msg'=>'提现成功！'
						            );
						            
						            //单笔转账到支付宝账号
						            //获取支付宝请求参数
						            Vendor('pay.alipayApp','','.class.php');
						            $alipayApp=new \alipayApp();
						            $out_biz_no=time().'_'.$res_add;
						            $payee_account=trim(I('post.account'));
						            $amount=$money;
						            $payer_show_name=APP_NAME;//付款方姓名
						            $payee_real_name=trim(I('post.truename'));//收款方真实姓名
						            $res_ali=$alipayApp->fundTransToaccountTransfer($out_biz_no, $payee_account, $amount,$payer_show_name,$payee_real_name);
						            if($res_ali['code']===0) {
						                
						            }else {
						                //转账失败
						                /* $res=array(
						                 'code'=>$this->ERROR_CODE_COMMON['DB_ERROR'],
						                 'msg'=>$res_ali['msg']
						                 ); */
						                $error_msg='支付宝转账失败：'.$res_ali['msg'].',账号：'.$payee_account.'，姓名：'.$payee_real_name;
						                writeLog($error_msg);
						            }
						        }else {
						            //回滚
						            $UserDrawApply->rollback();
						            //数据库错误
						            $res=array(
						                'code'=>$this->ERROR_CODE_COMMON['DB_ERROR'],
						                'msg'=>$this->ERROR_CODE_COMMON_ZH[$this->ERROR_CODE_COMMON['DB_ERROR']]
						            );
						        }
						    }
						}else {
						    //超过限制的提现需要审核
						    $data=array(
						        'user_id'=>$uid,
						        'money'=>$money,
						        'account_type'=>trim(I('post.account_type')),
						        'account'=>trim(I('post.account')),
						        'truename'=>trim(I('post.truename')),
						        'apply_time'=>date('Y-m-d H:i:s'),
						        'is_check'=>'N',//未审核，等待审核
						    );
						    if(!$UserDrawApply->create($data)) {
						        //验证不通过
						        $res=array(
						            'code'=>$this->ERROR_CODE_COMMON['PARAMETER_FORMAT_ERROR'],
						            'msg'=>$UserDrawApply->getError()
						        );
						    }else {
						        //验证通过
						        $res=$UserDrawApply->add($data);
						        if($res!==false) {
						            $res=array(
						                'code'=>$this->ERROR_CODE_COMMON['SUCCESS'],
						                'msg'=>'提现申请成功，请等待管理员审核！'
						            );
						        }else {
						            //数据库错误
						            $res=array(
						                'code'=>$this->ERROR_CODE_COMMON['DB_ERROR'],
						                'msg'=>$this->ERROR_CODE_COMMON_ZH[$this->ERROR_CODE_COMMON['DB_ERROR']]
						            );
						        }
						    }
						}
					}else {
						//余额不足
						$res=array(
								'code'=>$this->ERROR_CODE_USER['BALANCE_INSUFFICIENT'],
								'msg'=>$this->ERROR_CODE_USER_ZH[$this->ERROR_CODE_USER['BALANCE_INSUFFICIENT']]
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
}