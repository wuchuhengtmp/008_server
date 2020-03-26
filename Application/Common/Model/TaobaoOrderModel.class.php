<?php
/**
 * 淘宝订单兑现管理类
 */
namespace Common\Model;
use Think\Model;

class TaobaoOrderModel extends Model
{
	//验证规则
	protected $_validate =array(
			array('user_id','require','用户不能为空！',self::EXISTS_VALIDATE),  //存在验证，必填
			array('user_id','is_positive_int','用户不存在',self::EXISTS_VALIDATE,'function'),  //存在验证，必须是正整数
			array('order_num','require','淘宝订单号不能为空！',self::EXISTS_VALIDATE),  //存在验证，必填
			array('order_num','1,30','淘宝订单号不超过30个字符！',self::EXISTS_VALIDATE,'length'),  //存在验证，不超过30个字符
			array('apply_time','require','申请时间不能为空！',self::EXISTS_VALIDATE),  //存在验证，必填
			array('apply_time','is_datetime','申请时间格式不正确！',self::EXISTS_VALIDATE,'function'),  //存在验证，必须是正确的时间格式
			array('is_check',array('Y','N'),'请进行正确的审核操作！',self::EXISTS_VALIDATE,'in'),  //存在验证，只能是Y已审核 N未审核
			array('check_result',array('Y','N'),'请填写正确的审核结果！',self::VALUE_VALIDATE,'in'),  //值不为空的时候验证，只能是Y审核通过 N审核不通过
			array('check_time','is_datetime','审核时间格式不正确！',self::VALUE_VALIDATE,'function'),  //值不为空的时候验证，必须是正确的时间格式
			array('admin_id','is_positive_int','审核管理员不存在',self::VALUE_VALIDATE,'function'),  //值不为空的时候验证，必须是正整数
	);
	
	/**
	 * 获取申请信息
	 * @param int $id:申请ID
	 * @return array|boolean
	 */
	public function getApplyMsg($id)
	{
		$msg=$this->where("id='$id'")->find();
		if($msg)
		{
			return $msg;
		}else {
			return false;
		}
	}
	
	/**
	 * 获取申请详情
	 * @param int $id:申请ID
	 * @return array|boolean
	 */
	public function getApplyDetail($id)
	{
		$msg=$this->getApplyMsg($id);
		if($msg!==false)
		{
			//申请用户
			$user_id=$msg['user_id'];
			$User=new \Common\Model\UserModel();
			$UserMsg=$User->getUserMsg($user_id);
			$msg['phone']=$UserMsg['phone'];
			return $msg;
		}else {
			return false;
		}
	}
	
	/**
	 * 给会员返利
	 * @param string $order_num:充值订单号
	 * @param string $money:佣金
	 * @return boolean
	 */
	public function treat($order_num,$money)
	{
		$msg=$this->where("order_num='$order_num'")->find();
		if($msg)
		{
			//给购买会员返利
			$uid=$msg['user_id'];
			$User=new \Common\Model\UserModel();
			$UserMsg=$User->getUserMsg($uid);
			//根据用户所在的组获取相应收益比例
			$UserGroup=new \Common\Model\UserGroupModel();
			$groupMsg=$UserGroup->getGroupMsg($UserMsg['group_id']);
			if($groupMsg and $UserMsg)
			{
				//佣金-客户
				$money_user=$money*$groupMsg['fee_user']/100;
				//四舍五入
				$money_user=round($money_user, 2);
				//佣金-扣税
				$money_service=$money*$groupMsg['fee_service']/100;
				//四舍五入
				$money_service=round($money_service, 2);
				//佣金-平台
				$money_plantform=$money*$groupMsg['fee_plantform']/100;
				//四舍五入
				$money_plantform=round($money_plantform, 2);
				//开启事务
				$User->startTrans();
				$data_user=array(
						'balance'=>$UserMsg['balance']+$money_user,
						'balance_user'=>$UserMsg['balance_user']+$money_user,
						'balance_service'=>$UserMsg['balance_service']+$money_service,
						'balance_plantform'=>$UserMsg['balance_plantform']+$money_plantform,
				);
				if(!$User->create($data_user))
				{
					//验证不通过
					//回滚
					$User->rollback();
					return false;
				}else {
					//增加用户余额
					$res_balance=$User->where("uid='$uid'")->save($data_user);
					//保存余额变动记录
					$UserBalanceRecord=new \Common\Model\UserBalanceRecordModel();
					$all_money=$UserMsg['balance']+$money_user;
					$res_record=$UserBalanceRecord->addLog($uid, $money_user, $all_money, 'tbk');
					if($res_balance!==false and $res_record!==false)
					{
						//提交事务
						$User->commit();
						return true;
					}else {
						//回滚
						$User->rollback();
						return false;
					}
				}
			}else {
				//用户组不存在
				return false;
			}
		}else {
			return false;
		}
	}
}