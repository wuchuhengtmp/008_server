<?php
/**
 * 会员授权/邀请码管理
 */
namespace Common\Model;
use Think\Model;

class UserAuthCodeModel extends Model
{
	//验证规则
	protected $_validate =array(
			array('auth_code','require','授权码不能为空！',self::EXISTS_VALIDATE),  //存在验证，必填
			array('auth_code','1,30','授权码不超过30个字符！',self::EXISTS_VALIDATE,'length'),  //存在验证，不超过30个字符
			array('is_used',array('Y','N'),'请选择是否使用！',self::EXISTS_VALIDATE,'in'),  //存在验证，只能是Y是 N否
			array('user_id','is_positive_int','所属用户不存在！',self::VALUE_VALIDATE,'function'),  //值不为空的时候验证，必须是正整数		
	);
	
	/**
	 * 获取授权码信息
	 * @param int $id:ID
	 * @return array|boolean
	 */
	public function getMsg($id)
	{
		$msg=$this->where("id='$id'")->find();
		if($msg)
		{
			return $msg;
		}else {
			return false;
		}
	}
}
?>