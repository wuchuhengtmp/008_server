<?php
/**
 * 会员详细信息管理类
 */
namespace Common\Model;
use Think\Model;

class UserDetailModel extends Model
{
	//验证规则
	protected $_validate =array(
			array('user_id','require','所属会员不能为空！',self::EXISTS_VALIDATE),  //存在验证，必填
			array('user_id','is_positive_int','请选择正确的会员',self::EXISTS_VALIDATE,'function'),  //存在验证，必须是正整数
			array('nickname','1,100','昵称不超过100个字符！',self::VALUE_VALIDATE,'length'),  //值不为空的时候验证，不超过100个字符
			//array('avatar','1,100','头像路径不正确！',self::VALUE_VALIDATE,'length'),  //值不为空的时候验证，不超过100个字符
			array('truename','1,30','真实姓名不超过30个字符！',self::VALUE_VALIDATE,'length'),  //值不为空的时候验证，不超过30个字符
			array('sex',array('1','2','3'),'请选择正确的性别！',self::VALUE_VALIDATE,'in'),  //值不为空的时候验证，只能是1男 2女 3保密
			array('height','1,20','身高不超过20个字符！',self::VALUE_VALIDATE,'length'),  //值不为空的时候验证，不超过20个字符
			array('weight','1,20','体重不超过20个字符！',self::VALUE_VALIDATE,'length'),  //值不为空的时候验证，不超过20个字符
			array('blood',array('1','2','3','4','5'),'请选择正确的血型！',self::VALUE_VALIDATE,'in'),  //值不为空的时候验证，只能是1A型 2B型 3AB型 4O型 5其它		
			array('birthday','is_date','出生日期格式不正确！',self::VALUE_VALIDATE,'function'),  //值不为空的时候验证，必须是正确的时间格式
			array('qq','1,12','QQ号不超过12个字符！',self::VALUE_VALIDATE,'length'),  //值不为空的时候验证，不超过12个字符
			array('weixin','1,30','微信号不超过30个字符！',self::VALUE_VALIDATE,'length'),  //值不为空的时候验证，不超过30个字符
			array('province','1,30','省份不超过30个字符！',self::VALUE_VALIDATE,'length'),  //值不为空的时候验证，不超过30个字符
			array('city','1,30','城市不超过30个字符！',self::VALUE_VALIDATE,'length'),  //值不为空的时候验证，不超过30个字符
			array('county','1,30','县名不超过30个字符！',self::VALUE_VALIDATE,'length'),  //值不为空的时候验证，不超过30个字符
			array('detail_address','1,100','详细地址不超过100个字符！',self::VALUE_VALIDATE,'length'),  //值不为空的时候验证，不超过100个字符
			array('signature','1,200','个性签名不超过200个字符！',self::VALUE_VALIDATE,'length'),  //值不为空的时候验证，不超过200个字符
	);
	
	/**
	 * 获取会员信息
	 * @param int $user_id:用户ID
	 * @return array|false
	 */
	public function getUserDetailMsg($user_id)
	{
		$msg=$this->where("user_id='$user_id'")->find();
		if($msg!==false)
		{
			return $msg;
		}else {
			return false;
		}
	}
}