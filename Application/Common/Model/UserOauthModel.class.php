<?php
/**
 * 第三方社交平台账号共享
 */
namespace Common\Model;
use Think\Model;

class UserOauthModel extends Model
{
	/**
	 * 判断用户是否已注册
	 * @param string $openid:openid
	 * @param string $type:第三方平台类型
	 * @return int 用户ID
	 */
	public function is_register($openid,$type)
	{
		$msg=$this->where("openid='$openid' and type='$type'")->field('user_id')->find();
		if($msg['user_id'])
		{
			return $msg['user_id'];
		}else {
			return false;
		}
	}
	
	/**
	 * 用户注册
	 * @param int $user_id:用户ID
	 * @param string $openid:openid
	 * @param string $type:第三方平台类型
	 * @return boolean
	 */
	public function register($user_id,$openid,$type)
	{
		$data=array(
				'user_id'=>$user_id,
				'openid'=>$openid,
				'type'=>$type
		);
		$res=$this->add($data);
		if($res!==false)
		{
			return true;
		}else {
			return false;
		}
	}
}
?>