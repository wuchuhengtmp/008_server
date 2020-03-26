<?php
/**
 * 淘宝活动管理接口
 */
namespace App\Controller;
use App\Common\Controller\AuthController;

class TbActivityController extends AuthController
{
	/**
	 * 获取淘宝年货节信息
	 * @param string $token:用户身份令牌
	 * @return array
	 * @return @param code:返回码
	 * @return @param msg:返回码说明
	 * @return @param data:返回数据
	 */
	public function getNewYearShoppingFestivalMsg()
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
				$pid='mm_21742772_104250451_20294800352';
				//获取微信群主推广位
				$userMsg=$User->getUserMsg($uid);
				if($userMsg['tb_pid_master'])
				{
					$pid=$userMsg['tb_pid_master'];
				}
				//年货节链接地址-2019天猫年货合家-主会场（带超级红包）
				$url='https://s.click.taobao.com/t?e=m%3D2%26s%3DgXFSl9omEmccQipKwQzePCperVdZeJviK7Vc7tFgwiFRAdhuF14FMS6kS8yTp6kRAYPhC%2B%2FgaE8zuTGl041QAqD5kstin37fU2%2FAEepMzfHGq4iT%2BDv5w0JUXBtwD3jmeK6AyaN9cNFkkwrO4VzIZmsFOLhERZOUamkFHdIFxeOp%2B9oXPRnHb8s%2Fhc73tO6KVYo%2BqyT%2FBa1NrKwvDJNPXsxESlRLQlPP&pid='.$pid;
				//生成淘口令
				$data_ext=array(
						'user_id'=>$uid
				);
				$ext=json_encode($data_ext);
				$text='2019天猫年货合家-主会场（带超级红包）';
				$logo=WEB_URL.'/Public/static/wap/images/tb1.jpg';
				//淘宝客淘口令
				Vendor('tbk.tbk','','.class.php');
				$tbk=new \tbk();
				$res_tbk=$tbk->createTpwd($user_id='',$text,$url,$logo,$ext);
				if($res_tbk['code']==0)
				{
					$tkl=$res_tbk['data'];
					$data=array(
							'url'=>$url,
							'tkl'=>$tkl
					);
					$res=array(
							'code'=>$this->ERROR_CODE_COMMON['SUCCESS'],
							'msg'=>'成功',
							'data'=>$data
					);
				}else {
					$res=$res_tbk;
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
?>