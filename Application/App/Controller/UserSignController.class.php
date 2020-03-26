<?php
/**
 * 用户签到管理接口
 */
namespace App\Controller;
use App\Common\Controller\AuthController;

class UserSignController extends AuthController
{
	/**
	 * 签到
	 * @param string $token:用户身份令牌
	 * @return array
	 * @return @param code:返回码
	 * @return @param msg:返回码说明
	 */
	public function singin()
	{
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
				//判断今天是否已签到
				$UserSign=new \Common\Model\UserSignModel();
				$today=date('Y-m-d');
				$res_exist=$UserSign->where("user_id='$uid' and sign_date='$today'")->find();
				if($res_exist) {
					//今日已签到，请勿重复签到
					$res=array(
							'code'=>$this->ERROR_CODE_USER['PLEASE_DO_NOT_CHECK_IN_AGAIN_TODAY'],
							'msg'=>$this->ERROR_CODE_USER_ZH[$this->ERROR_CODE_USER['PLEASE_DO_NOT_CHECK_IN_AGAIN_TODAY']]
					);
				}else {
					//进行签到
					//判断用户昨日是否签到
					$res_yesterday=$UserSign->where("sign_date=DATE_SUB(CURDATE(), INTERVAL 1 DAY) and user_id='$uid'")->find();
					if($res_yesterday) {
						//昨日签到
						//连续签到天数
						$continuous_day=$res_yesterday['continuous_day']+1;
					}else {
						//昨日未签到
						//连续签到天数
						$continuous_day=1;
					}
					//根据签到奖励模式计算数值
					if(SIGN_AWARD_MODEL==1){
					    //固定奖励模式
					    $point=SIGN_AWARD_FIXED_NUM;
					}else {
					    //连续奖励模式
					    $continuous_day2=$continuous_day;
					    if($continuous_day2>7){
					        //最多连续奖励7天
					        $continuous_day2=7;
					    }
					    switch ($continuous_day2){
					        case 1:
					            $point=SIGN_AWARD_CONTINUOUS_NUM1;
					            break;
					        case 2:
					            $point=SIGN_AWARD_CONTINUOUS_NUM2;
					            break;
					        case 3:
					            $point=SIGN_AWARD_CONTINUOUS_NUM3;
					            break;
					        case 4:
					            $point=SIGN_AWARD_CONTINUOUS_NUM4;
					            break;
					        case 5:
					            $point=SIGN_AWARD_CONTINUOUS_NUM5;
					            break;
					        case 6:
					            $point=SIGN_AWARD_CONTINUOUS_NUM6;
					            break;
					        case 7:
					            $point=SIGN_AWARD_CONTINUOUS_NUM7;
					            break;
					        default:
					            $point=0;
					            break;
					    }
					}
					$data=array(
							'user_id'=>$uid,
							'sign_date'=>$today,
							'sign_time'=>date('Y-m-d H:i:s'),
							'point'=>$point,
							'continuous_day'=>$continuous_day
					);
					if(!$UserSign->create($data)) {
						//验证不通过
						$res=array(
								'code'=>$this->ERROR_CODE_COMMON['PARAMETER_FORMAT_ERROR'],
								'msg'=>$UserSign->getError()
						);
					}else {
						//验证通过
						//开启事务
						$UserSign->startTrans();
						$res_sign=$UserSign->add($data);
						if($res_sign!==false) {
						    //根据签到奖励类型，增加用户的积分/余额/成长值
						    $UserMsg=$User->getUserMsg($uid);
						    switch (SIGN_AWARD_TYPE){
						        //积分
						        case 1:
						            //增加用户积分、保存积分变动记录
						            $res_b=$User->where("uid='$uid'")->setInc('point',$point);
						            $UserPointRecord=new \Common\Model\UserPointRecordModel();
						            $all_point=$UserMsg['point']+$point;
						            $res_record=$UserPointRecord->addLog($uid, $point, $all_point, 'signin');
						            break;
						        //余额
						        case 2:
						            //增加用户余额、保存余额变动记录
						            $res_b=$User->where("uid='$uid'")->setInc('balance',$point);
						            $UserBalanceRecord=new \Common\Model\UserBalanceRecordModel();
						            $all_money=$UserMsg['balance']+$point;
						            $res_record=$UserBalanceRecord->addLog($uid,$point,$all_money,'signin');
						            break;
						        //成长值
						        case 3:
						            //增加用户成长值、保存成长值变动记录
						            $res_b=$User->where("uid='$uid'")->setInc('exp',$point);
						            $UserExpRecord=new \Common\Model\UserExpRecordModel();
						            $all_exp=$UserMsg['exp']+$point;
						            $res_record=$UserExpRecord->addLog($uid,$point,$all_exp,'signin');
						            break;
						        default:
						            break;
						    }
						    if($res_b!==false and $res_record!==false) {
								//提交事务
								$UserSign->commit();
								$res=array(
										'code'=>$this->ERROR_CODE_COMMON['SUCCESS'],
										'msg'=>'成功'
								);
							}else {
								//回滚
								$UserSign->rollback();
								//数据库错误
								$res=array(
										'code'=>$this->ERROR_CODE_COMMON['DB_ERROR'],
										'msg'=>$this->ERROR_CODE_COMMON_ZH[$this->ERROR_CODE_COMMON['DB_ERROR']]
								);
							}
						}else {
							//回滚
							$UserSign->rollback();
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
	 * 获取用户签到记录
	 * @param string $token:用户身份令牌
	 * @param int $page:页码，默认第1页
	 * @param int $per:每页条数，默认10条
	 * @return array
	 * @return @param code:返回码
	 * @return @param msg:返回码说明
	 * @return @param data:返回数据
	 * @return @param data->list:用户签到记录
	 */
	public function getSignRecord()
	{
		if(trim(I('post.token')))
		{
			//判断用户身份
			$token=trim(I('post.token'));
			$User=new \Common\Model\UserModel();
			$res_token=$User->checkToken($token);
			if($res_token['code']!=0)
			{
				//用户身份不合法
				$res=$res_token;
			}else {
				$uid=$res_token['uid'];
				if(trim(I('post.page')))
				{
					$page=trim(I('post.page'));
				}else {
					$page=1;
				}
				if(trim(I('post.per')))
				{
					$per=trim(I('post.per'));
				}else {
					$per=10;
				}
				//获取用户签到记录
				$UserSign=new \Common\Model\UserSignModel();
				$list=$UserSign->getSignRecordByPage($uid,$page,$per);
				if($list!==false)
				{
					$data=array(
							'list'=>$list
					);
					$res=array(
							'code'=>$this->ERROR_CODE_COMMON['SUCCESS'],
							'msg'=>'成功',
							'data'=>$data
					);
				}else {
					//数据库错误
					$res=array(
							'code'=>$this->ERROR_CODE_COMMON['DB_ERROR'],
							'msg'=>$this->ERROR_CODE_COMMON_ZH[$this->ERROR_CODE_COMMON['DB_ERROR']]
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
	 * 获取用户连续签到天数
	 * @param string $token:用户身份令牌
	 * @return array
	 * @return @param code:返回码
	 * @return @param msg:返回码说明
	 * @return @param data:返回数据
	 * @return @param data->point:可获取积分
	 * @return @param data->continuous_day:连续签到天数
	 */
	public function getContinuousDay()
	{
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
				//判断今天是否已签到
				$UserSign=new \Common\Model\UserSignModel();
				$today=date('Y-m-d');
				$res_exist=$UserSign->where("user_id='$uid' and sign_date='$today'")->find();
				if($res_exist) {
					//今日已签到，以今日签到情况为准
					$point=$res_exist['point'];
					$continuous_day=$res_exist['continuous_day'];
				}else {
					//今日未签到
					//判断用户昨日是否签到
					$res_yesterday=$UserSign->where("sign_date=DATE_SUB(CURDATE(), INTERVAL 1 DAY) and user_id='$uid'")->find();
					if($res_yesterday) {
						//昨日签到
						$point=$res_yesterday['continuous_day']+1;
						$continuous_day=$res_yesterday['continuous_day'];
					}else {
						//昨日未签到
						$point=1;
						$continuous_day=0;
					}
				}
				$data=array(
						'point'=>$point,
						'continuous_day'=>$continuous_day,
				);
				$res=array(
						'code'=>$this->ERROR_CODE_COMMON['SUCCESS'],
						'msg'=>'成功',
						'data'=>$data
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
	 * 判断用户今日是否签到
	 * @param string $token:用户身份令牌
	 * @return array
	 * @return @param code:返回码
	 * @return @param msg:返回码说明
	 * @return @param data:返回数据
	 * @return @param data->is_sign:今日是否签到，Y是 N否
	 */
	public function isSignToday()
	{
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
	            //判断今天是否已签到
	            $UserSign=new \Common\Model\UserSignModel();
	            $today=date('Y-m-d');
	            $res_exist=$UserSign->where("user_id='$uid' and sign_date='$today'")->find();
	            if($res_exist) {
	                //今日已签到，以今日签到情况为准
	                $is_sign='Y';
	            }else {
	                //今日未签到
	                $is_sign='N';
	            }
	            $data=array(
	                'is_sign'=>$is_sign,
	            );
	            $res=array(
	                'code'=>$this->ERROR_CODE_COMMON['SUCCESS'],
	                'msg'=>'成功',
	                'data'=>$data
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
}