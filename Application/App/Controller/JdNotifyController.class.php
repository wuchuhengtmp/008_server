<?php
/**
 * 京东异步通知
 */
namespace App\Controller;
use Think\Controller;

class JdNotifyController extends Controller
{
	//应用回调地址，获取授权码
	public function notify()
	{
		if($_REQUEST['code'])
		{
			$code=trim($_REQUEST['code']);
			Vendor('JingDong.JingDong','','.class.php');
			$JindDong=new \JindDong();
			$res=$JindDong->getAccessToken($code);
			dump($res);
		}
	}
}
?>