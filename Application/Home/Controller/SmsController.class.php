<?php
/**
 * 发送手机短信
 */
namespace Home\Controller;
use Think\Controller;

class SmsController extends Controller
{
	function _empty()
	{
		header('HTTP/1.1 404 Not Found');
		header('Status:404 Not Found');
		$this->display ( 'Public:404' );
	}
	
	//发送通用短信
	public function sendDefault()
	{
		if (trim(I('post.phone')))
		{
			$phone = trim(I('post.phone')); // 手机号码
			if(is_phone($phone))
			{
				//发送手机短信
				$sms=new \Common\Model\SmsModel();
				$content="@1@=".rand(1000,9999);
				$res=$sms->sendMessage($phone, $content, 'default');
				if($res['code']=='0')
				{
					$str = '√短信发送成功';
					echo $str;
					exit ();
				}else {
					$str = '×短信发送失败：'.$res['msg'];
					echo $str;
					exit ();
				}
			} else {
				$str = 'X手机号码格式不正确';
				echo $str;
				exit ();
			}
		}else {
			$str = 'X手机号码不能为空';
			echo $str;
			exit ();
		}
	}
	
	// 发送注册手机验证码
	public function sendRegister()
	{
		if (trim(I('post.phone')))
		{
			$phone = trim(I('post.phone')); // 手机号码
			if(is_phone($phone))
			{
				// 判断手机是否存在
				$User=new \Common\Model\UserModel();
				$res = $User->where ( "phone='$phone'" )->field ( 'uid' )->find ();
				if ($res ['uid']!='')
				{
					$str = 'X该手机已被注册';
					echo $str;
					exit ();
				} else {
					//发送手机短信
					$sms=new \Common\Model\SmsModel();
					$content="@1@=".rand(1000,9999);
					$res=$sms->sendMessage($phone, $content, 'default');
					if($res['code']=='0')
					{
						$str = '√短信发送成功';
						echo $str;
						exit ();
					}else {
						$str = '×短信发送失败：'.$res['msg'];
						echo $str;
						exit ();
					}
				}
			} else {
				$str = 'X手机号码格式不正确';
				echo $str;
				exit ();
			}
		}else {
			$str = 'X手机号码不能为空';
			echo $str;
			exit ();
		}
	}
	
	// 找回密码-发送手机验证码
	public function sendFindpwd()
	{
		if (trim(I('post.phone')))
		{
			$phone = trim(I('post.phone')); // 手机号码
			if(is_phone($phone))
			{
				// 判断手机是否存在
				$User=new \Common\Model\UserModel();
				$res = $User->where ( "phone='$phone'" )->field ( 'id' )->find ();
				if ($res ['id']=='')
				{
					$str = 'X该手机尚未注册';
					echo $str;
					exit ();
				} else {
					//发送手机短信
					$sms=new \Common\Model\SmsModel();
					$content="@1@=".rand(1000,9999);
					$res=$sms->sendMessage($phone, $content, 'default');
					if($res['code']=='0')
					{
						$str = '√短信发送成功';
						echo $str;
						exit ();
					}else {
						$str = '×短信发送失败：'.$res['msg'];
						echo $str;
						exit ();
					}
				}
			} else {
				$str = 'X手机号码格式不正确';
				echo $str;
				exit ();
			}
		}else {
			$str = 'X手机号码不能为空';
			echo $str;
			exit ();
		}
	}
}
?>