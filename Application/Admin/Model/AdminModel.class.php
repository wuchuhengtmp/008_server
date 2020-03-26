<?php
/**
 * 管理员类
 */
namespace Admin\Model;
use Think\Model;

class AdminModel extends Model
{
	/**
	 * 获取管理员列表
	 * @return array
	 */
	public function getAdminList()
	{
		$adminlist=$this->select();
		return $adminlist;
	}
	
	/**
	 * 根据管理员组ID获取管理员列表
	 * @param int $group_id:管理员组ID
	 * @return array
	 */
	public function getAdminListByGroup($group_id)
	{
		$adminlist=$this->where("group_id='$group_id'")->select();
		return $adminlist;
	}
	
	/**
	 * 获取管理员信息
	 * @param int $uid:管理员ID
	 * @return array
	 */
	public function getAdminMsg($uid)
	{
		if(!empty($uid))
		{
			$where=array(
					'uid'=>$uid
			);
			$res=$this->where($where)->find();
			if($res['last_login_ip'])
			{
				//根据IP地址获取实际地址
				if($res['last_login_ip']=='127.0.0.1')
				{
					$res['last_login_ip_name']='本地服务器';
				}else {
					$ip=$res['last_login_ip'];
					//百度地图API
					$content = file_get_contents("http://api.map.baidu.com/location/ip?ak=1nvtZWSUFeVlOp84fCc4MqMF&ip={$ip}&coor=bd09ll");
					$json = json_decode($content);
					$res['last_login_ip_name']=$json->{'content'}->{'address'};
				}
			}
			//获取管理员组名
			$group_id=$res['group_id'];
			$AdminGroup=new \Admin\Model\AdminGroupModel();
			$res_g=$AdminGroup->where("id=$group_id")->field('title')->find();
			if($res_g!==false)
			{
				$res['group_title']=$res_g['title'];
			}
			return $res;
		}else {
			return 0;
		}
	}
	
	/**
	 * 验证密码是否正确
	 * @param int $admin_id:管理员ID
	 * @param string $pwd:未加密的密码
	 * @return 正确返回1，错误返回0
	 */
	public function checkPwd($admin_id,$pwd)
	{
		if(!empty($admin_id) and !empty($pwd))
		{
			$res=$this->where("uid=$admin_id")->find();
			if($res)
			{
				$password=$res['password'];
				//MD5加密
				$pwd=$this->encrypt($pwd);
				if($password!=$pwd)
				{
					return 0;
				}else {
					return 1;
				}
			}else {
				return 0;
			}
		}
	}
	
	/**
	 * 密码加密
	 * @param string $pwd:明文密码
	 * @return string:加密后的密文密码
	 */
	public function encrypt($pwd)
	{
		$password=md5($pwd.'9'.substr($pwd,0,3));
		return $password;
	}
}