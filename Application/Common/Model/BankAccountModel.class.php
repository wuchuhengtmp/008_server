<?php
/**
 * 银行账号管理
 */
namespace Common\Model;
use Think\Model;

class BankAccountModel extends Model
{
	//验证规则
	protected $_validate =array(
			array('user_id','require','所属用户不能为空！',self::EXISTS_VALIDATE),  //存在验证，必填
			array('user_id','is_positive_int','所属用户不存在！',self::EXISTS_VALIDATE,'function'),  //存在验证，必须是正整数
			array('bank_id','require','所属银行不能为空！',self::EXISTS_VALIDATE),  //存在验证，必填
			array('bank_id','is_positive_int','所属银行不存在！',self::EXISTS_VALIDATE,'function'),  //存在验证，必须是正整数		
			array('account','require','卡号/账号不能为空！',self::EXISTS_VALIDATE),  //存在验证，必填
			array('account','1,30','卡号/账号不超过30个字符！',self::EXISTS_VALIDATE,'length'),  //存在验证，不超过30个字符
			array('truename','1,20','真实姓名不超过20个字符！',self::VALUE_VALIDATE,'length'),  //值不为空时候验证，不超过20个字符
	);
	
	/**
	 * 获取账号信息
	 * @param int $id:ID
	 * @return array|boolean
	 */
	public function getAccountMsg($id)
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
	 * 获取账号详情
	 * @param int $id:ID
	 * @return array|boolean
	 */
	public function getAccountDetail($id)
	{
	    $sql="select ba.*,b.bank_name,b.icon from __PREFIX__bank b,__PREFIX__bank_account ba where ba.id='$id' and ba.bank_id=b.bank_id";
	    $msg=M()->query($sql);
	    if($msg)
	    {
	        return $msg;
	    }else {
	        return false;
	    }
	}
}
?>