<?php
/**
 * 用户提现申请管理类
 */
namespace Common\Model;
use Think\Model;

class UserDrawApplyModel extends Model
{
	//验证规则
	protected $_validate =array(
			array('user_id','require','提现用户不能为空！',self::EXISTS_VALIDATE),  //存在验证，必填
			array('user_id','is_positive_int','提现用户不存在',self::EXISTS_VALIDATE,'function'),  //存在验证，必须是正整数
			array('money','require','提现金额不能为空！',self::EXISTS_VALIDATE),  //存在验证，必填
			array('money','currency','提现金额不是正确的货币格式！',self::EXISTS_VALIDATE),  //存在验证 ，必须是货币格式
			array('account_type','require','提现账户类型不能为空！',self::EXISTS_VALIDATE),  //存在验证，必填
			array('account_type',array('1','16'),'提现账户类型不正确！',self::EXISTS_VALIDATE,'between'),  //存在验证，只能是1-16
			array('account','require','提现账号不能为空！',self::EXISTS_VALIDATE),  //存在验证，必填
			array('account','1,50','提现账号不超过50个字符！',self::EXISTS_VALIDATE,'length'),  //存在验证，不超过50个字符
			array('truename','1,20','真实姓名不超过20个字符！',self::VALUE_VALIDATE,'length'),  //值不为空的时候验证，不超过20个字符
			array('apply_time','require','提现申请时间不能为空！',self::EXISTS_VALIDATE),  //存在验证，必填
			array('apply_time','is_datetime','提现申请时间格式不正确！',self::EXISTS_VALIDATE,'function'),  //存在验证，必须是正确的时间格式
			array('is_check',array('Y','N'),'请进行正确的审核操作！',self::EXISTS_VALIDATE,'in'),  //存在验证，只能是Y已审核 N未审核
			array('check_result',array('Y','N'),'请填写正确的审核结果！',self::VALUE_VALIDATE,'in'),  //值不为空的时候验证，只能是Y审核通过 N审核不通过
			array('check_time','is_datetime','审核时间格式不正确！',self::VALUE_VALIDATE,'function'),  //值不为空的时候验证，必须是正确的时间格式
			array('admin_id','is_positive_int','审核管理员不存在',self::VALUE_VALIDATE,'function'),  //值不为空的时候验证，必须是正整数
	);
	
	/**
	 * 获取申请信息
	 * @param int $user_draw_apply_id:申请ID
	 * @return array|boolean
	 */
	public function getApplyMsg($user_draw_apply_id)
	{
		$msg=$this->where("user_draw_apply_id='$user_draw_apply_id'")->find();
		if($msg!==false)
		{
			return $msg;
		}else {
			return false;
		}
	}
	
	/**
	 * 获取申请详情
	 * @param int $user_draw_apply_id:申请ID
	 * @return array|boolean
	 */
	public function getApplyDetail($user_draw_apply_id)
	{
		$msg=$this->getApplyMsg($user_draw_apply_id);
		if($msg!==false)
		{
			//申请用户
			$user_id=$msg['user_id'];
			$User=new \Common\Model\UserModel();
			$UserMsg=$User->getUserMsg($user_id);
			$msg['phone']=$UserMsg['phone'];
			//银行
			$account_type_d=json_decode(account_type_d,true);
			$msg['account_type_zh']=$account_type_d[$msg['account_type']];
			return $msg;
		}else {
			return false;
		}
	}
}