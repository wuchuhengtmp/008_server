<?php
/**
 * 会员组升级充值订单管理
 */
namespace Common\Model;
use Think\Model;

class UserGroupRechargeModel extends Model
{
	//验证规则
	protected $_validate =array(
			array('order_num','1,30','订单号不超过30个字符！',self::EXISTS_VALIDATE,'length'),  //存在验证，不超过30个字符
			array('user_id','is_positive_int','请选择正确的充值会员',self::EXISTS_VALIDATE,'function'),  //存在验证，必须是正整数
			array('group_id','is_positive_int','请选择正确的升级会员组',self::EXISTS_VALIDATE,'function'),  //存在验证，必须是正整数
			array('fee','is_positive_int','升级会员组所需费用不正确',self::EXISTS_VALIDATE,'function'),  //存在验证，必须是正整数
			array('create_time','is_datetime','创建时间格式不正确！',self::EXISTS_VALIDATE,'function'),  //存在验证，必须是正确的时间格式
			array('is_pay',array('Y','N'),'请选择是否支付！',self::EXISTS_VALIDATE,'in'),  //存在验证，只能是Y是 N否
			array('pay_method',array('alipay','wxpay'),'支付方式类型不正确！',self::VALUE_VALIDATE,'in'),  //值不为空的时候验证，支付方式只能是 alipay支付宝 wxpay微信支付
			array('pay_time','is_datetime','支付时间格式不正确！',self::VALUE_VALIDATE,'function'),  //值不为空的时候验证，必须是正确的时间格式
	);
	
	/**
	 * 生成唯一订单号
	 * @return string:订单号
	 */
	public function generateOrderNum()
	{
		$num=uniqid().rand(100, 999);
		return $num;
	}
	
	/**
	 * 升级充值
	 * @param int $uid:用户ID
	 * @param int $group_id:升级到的会员组
	 * @param string $type:升级类型 1：1个月 2：1年 3：终生
	 * @return boolean
	 */
	public function recharge($uid,$group_id,$type)
	{
		switch ($type)
		{
			//1个月
			case '1':
				$fee=UPGRADE_FEE_MONTH;
				break;
			//1年
			case '2':
				$fee=UPGRADE_FEE_YEAR;
				break;
			//终生
			case '3':
				$fee=UPGRADE_FEE_FOREVER;
				break;
			default:
				$fee=0;
				break;
		}
		$data=array(
				'order_num'=>$this->generateOrderNum(),
				'user_id'=>$uid,
				'group_id'=>$group_id,
				'type'=>$type,
				'fee'=>$fee*100,
				'create_time'=>date('Y-m-d H:i:s'),
				'is_pay'=>'N',
		);
		if(!$this->create($data))
		{
			return false;
		}else {
			$res=$this->add($data);
			if($res!==false)
			{
				return $res;
			}else {
				return false;
			}
		}
	}
	
	/**
	 * 获取充值记录
	 * @param int $id:ID
	 * @return array|boolean
	 */
	public function getOrderMsg($id)
	{
		$msg=$this->where("id='$id'")->find();
		if($msg)
		{
			$msg['fee']=$msg['fee']/100;
			return $msg;
		}else {
			return false;
		}
	}
	
	/**
	 * 根据订单号获取充值记录
	 * @param string $order_num:订单号
	 * @return array|boolean
	 */
	public function getOrderMsgByOrderNum($order_num)
	{
		$msg=$this->where("order_num='$order_num'")->find();
		if($msg)
		{
			return $msg;
		}else {
			return false;
		}
	}
	
	/**
	 * 处理升级代理商会员组订单
	 * @param string $order_num:充值订单号
	 * @param string $pay_method:支付方式，alipay支付宝支付、wxpay微信支付
	 * @return boolean
	 */
	public function treatUpgrade($order_num,$pay_method)
	{
		$msg=$this->getOrderMsgByOrderNum($order_num);
		if($msg)
		{
			if($msg['is_pay']=='N')
			{
				//处理订单
				$data=array(
						'is_pay'=>'Y',
						'pay_method'=>$pay_method,
						'pay_time'=>date('Y-m-d H:i:s')
				);
				if(!$this->create($data))
				{
					//验证不通过
					return false;
				}else {
					//验证通过
					//开启事务
					$this->startTrans();
					$res=$this->where("order_num='$order_num'")->save($data);
					if($res!==false)
					{
						//修改记录成功，改变用户会员组
						$uid=$msg['user_id'];
						$User=new \Common\Model\UserModel();
						$userMsg=$User->getUserMsg($uid);
						switch ($msg['type'])
						{
							//1个月
							case '1':
								$add_date='+1 month';
								$fee_r1=20;
								$fee_r2=0;
								$fee_r3=0;
								$is_forever='N';
								break;
							//1年
							case '2':
								$add_date='+1 year';
								$fee_r1=200;
								$fee_r2=100;
								$fee_r3=0;
								$is_forever='N';
								break;
							//终生
							case '3':
								$add_date='+20 year';
								$fee_r1=300;
								$fee_r2=150;
								$fee_r3=0;
								$is_forever='Y';
								break;
							default:
								$add_date='';
								$fee_r1=0;
								$fee_r2=0;
								$fee_r3=0;
								$is_forever='N';
								break;
						}
						if($userMsg['expiration_date'] and $userMsg['expiration_date']!='0000-00-00 00:00:00')
						{
							if(strtotime($userMsg['expiration_date'])>time())
							{
								//到期时间大于当前时间，延长到期时间
								$expiration_date=date('Y-m-d H:i:s',strtotime($add_date,strtotime($userMsg['expiration_date'])));
							}else {
								//已到期
								$expiration_date=date('Y-m-d H:i:s',strtotime($add_date));
							}
						}else {
							//未设置到期时间
							$expiration_date=date('Y-m-d H:i:s',strtotime($add_date));
						}
						//用户信息
						$data_u=array(
								'group_id'=>4,//VIP会员组-汇客熊
								'expiration_date'=>$expiration_date,//会员到期时间
								'is_forever'=>$is_forever,
								'exp'=>$userMsg['exp']+500
						);
						if(!$User->create($data_u))
						{
							//验证不通过
							//回滚
							$this->rollback();
							return false;
						}else {
							//修改会员组
							$res_u=$User->where("uid='$uid'")->save($data_u);
							if($res_u!==false)
							{
								//给一级推荐人返利
								if($userMsg['referrer_id'])
								{
									$now=date('Y-m-d H:i:s');
									//存在推荐人,给推荐人返利
									$referrer_id=$userMsg['referrer_id'];
									$referrerMsg=$User->getUserMsg($referrer_id);
									$UserBalanceRecord=new \Common\Model\UserBalanceRecordModel();
									if($fee_r1>0 and $referrerMsg['group_id']=='2' and $referrerMsg['expiration_date']>=$now)
									{
										//增加推荐人余额
										$data_balance=array(
												'balance'=>$referrerMsg['balance']+$fee_r1,
										);
										$res_balance=$User->where("uid='$referrer_id'")->save($data_balance);
										//保存余额变动记录
										$all_money=$referrerMsg['balance']+$fee_r1;
										$res_balance_log=$UserBalanceRecord->addLog($referrer_id, $fee_r1, $all_money, 'recommend1');
									}else {
										$res_balance=true;
										$res_balance_log=true;
									}
									//给推荐人增加经验值6点
									if($msg['type']=='3')
									{
										//充值988，终生VIP-汇客熊
										$res_referrer_exp=$User->where("uid='$referrer_id'")->setInc('exp',6);
										//判断推荐人是否可以升级为VIP
										$new_exp=$referrerMsg['exp']+6;
										$new_group_id='';
										if($referrerMsg['group_id']==1 and $new_exp>=20)
										{
											//是成长熊，并且经验值达到了20，升级为奋斗熊
											$new_group_id=2;
										}
										if($referrerMsg['group_id']==2 and $new_exp>=200)
										{
											//是奋斗熊，并且经验值达到了200，升级为精英熊
											$new_group_id=3;
										}
										if($referrerMsg['group_id']==3 and $new_exp>=500)
										{
											//是精英熊，并且经验值达到了500，升级为汇客熊
											$new_group_id=4;
										}
										if($new_group_id)
										{
											//会员升级
											$data_referrer=array(
													'group_id'=>$new_group_id
											);
											$res_referrer_g=$User->where("uid='$referrer_id'")->save($data_referrer);
										}else {
											$res_referrer_g=true;
										}
									}else {
										$res_referrer_exp=true;
										$res_referrer_g=true;
									}
										
									if($res_balance!==false and $res_balance_log!==false and $res_referrer_exp!==false and $res_referrer_g!==false)
									{
										//给二级推荐人返利
										if($referrerMsg['referrer_id'])
										{
											//存在推荐人,给推荐人返利
											$referrer_id2=$referrerMsg['referrer_id'];
											$referrerMsg2=$User->getUserMsg($referrer_id2);
											if($fee_r2>0 and $referrerMsg2['group_id']=='2' and $referrerMsg2['expiration_date']>=$now)
											{
												//增加推荐人余额
												$data_balance2=array(
														'balance'=>$referrerMsg2['balance']+$fee_r2,
												);
												$res_balance2=$User->where("uid='$referrer_id2'")->save($data_balance2);
												//保存余额变动记录
												$all_money2=$referrerMsg2['balance']+$fee_r2;
												$res_balance_log2=$UserBalanceRecord->addLog($referrer_id2, $fee_r2, $all_money2, 'recommend2');							
											}else {
												$res_balance2=true;
												$res_balance_log2=true;
											}
											if($res_balance2!==false and $res_balance_log2!==false)
											{
												//给三级推荐人返利
												if($referrerMsg2['referrer_id'])
												{
													//存在推荐人,给推荐人返利
													$referrer_id3=$referrerMsg2['referrer_id'];
													$referrerMsg3=$User->getUserMsg($referrer_id3);
													if($fee_r3>0 and $referrerMsg3['group_id']=='2' and $referrerMsg3['expiration_date']>=$now)
													{
														//增加推荐人余额
														$data_balance3=array(
																'balance'=>$referrerMsg3['balance']+$fee_r3,
														);
														$res_balance3=$User->where("uid='$referrer_id3'")->save($data_balance3);
														//保存余额变动记录
														$all_money3=$referrerMsg3['balance']+$fee_r3;
														$res_balance_log3=$UserBalanceRecord->addLog($referrer_id3, $fee_r3, $all_money3, 'recommend3');	
													}else {
														$res_balance3=true;
														$res_balance_log3=true;
													}
													if($res_balance3!==false and $res_balance_log3!==false)
													{
														//提交事务
														$this->commit();
														return true;
													}else {
														//回滚
														$this->rollback();
														return false;
													}
												}else {
													//不存在三级推荐人
													//提交事务
													$this->commit();
													return true;
												}
											}else {
												//回滚
												$this->rollback();
												return false;
											}
										}else {
											//不存在二级推荐人
											//提交事务
											$this->commit();
											return true;
										}
									}else {
										//回滚
										$this->rollback();
										return false;
									}
								}else {
									//不存在推荐人
									//提交事务
									$this->commit();
									return true;
								}
							}else {
								//回滚
								$this->rollback();
								return false;
							}
						}
					}else {
						//回滚
						$this->rollback();
						return false;
					}
				}
			}else {
				return false;
			}
		}else {
			return false;
		}
	}
}
?>