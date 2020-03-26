<?php
/**
 * 微信支付-服务器通知
*/
namespace App\Controller;
use Think\Controller;
class WxNotifyController extends Controller
{
	//服务器通知处理
	public function notify_app()
	{
		//引入微信服务器通知处理类
		require_once "./ThinkPHP/Library/Vendor/pay/wxpay/lib/WxPay.Api.php";
		require_once "./ThinkPHP/Library/Vendor/pay/wxpay/lib/WxPay.Notify.php";
		$raw_xml = file_get_contents("php://input");
		
		//写日志
		if(APP_DEBUG===true)
		{
			writeLog(json_encode($raw_xml,JSON_UNESCAPED_UNICODE));
		}
		
		$notify = new \WxPayNotify();
		$notify->Handle(false);
		//得到返回结果
		$res = $notify->GetValues();
		
		//写日志
		if(APP_DEBUG===true)
		{
			writeLog(json_encode($res,JSON_UNESCAPED_UNICODE));
		}
		
		if($res['return_code'] ==="SUCCESS" && $res['return_msg'] ==="OK")
		{
			libxml_disable_entity_loader(true);
			$ret = json_decode(json_encode(simplexml_load_string($raw_xml, 'SimpleXMLElement', LIBXML_NOCDATA)), true);
			\Think\Log::write('微信APP支付成功订单号'.$ret['out_trade_no'], \Think\Log::DEBUG);
			//在此处处理业务逻辑部分-防止重复处理
			//处理支付完成订单
			//订单号
			$out_trade_no=$ret['out_trade_no'];
			//订单类型
			$type=substr($out_trade_no, 0,2);//截取前2位
			//订单号
			$order_num=substr($out_trade_no, 3);//截取掉前3位
			switch ($type)
			{
				//升级为VIP会员
				case 'v1':
					$UserGroupRecharge=new \Common\Model\UserGroupRechargeModel();
					$res=$UserGroupRecharge->treatUpgrade($order_num, 'wxpay');
					break;
				//商城
				case 'sp':
					$Order=new \Common\Model\OrderModel();
					$res=$Order->treatOrder($order_num, 'wxpay');
					break;
				default:
					break;
			}
			if($res===false)
			{
				exit();
			}
			
		}
	}
}
?>