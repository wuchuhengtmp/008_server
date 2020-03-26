<?php
/**
 * 支付宝异步通知
 */
namespace App\Controller;
use Think\Controller;

class AliNotifyController extends Controller
{
	//异步通知
	public function notify()
	{
		//引入支付宝APP类
		Vendor('pay.alipayApp','','.class.php');
		$alipayApp=new \alipayApp();
		//验签
		$verify_result = $alipayApp->checkVerify($_POST);
		if($verify_result)
		{
			//验证成功
			//商户订单号
			$out_trade_no = $_POST['out_trade_no'];
			//支付宝交易号
			$trade_no = $_POST['trade_no'];
			//交易状态
			$trade_status = $_POST['trade_status'];
		
			/**
			 * 交易状态说明：trade_status
			 * WAIT_BUYER_PAY 	交易创建，等待买家付款
			 * TRADE_CLOSED 	未付款交易超时关闭，或支付完成后全额退款
			 * TRADE_SUCCESS 	交易支付成功
			 * TRADE_FINISHED 	交易结束，不可退款
			 */
			if($_POST['trade_status'] == 'TRADE_FINISHED')
			{
				//判断该笔订单是否在商户网站中已经做过处理
				//如果没有做过处理，根据订单号（out_trade_no）在商户网站的订单系统中查到该笔订单的详细，并执行商户的业务程序
				//如果有做过处理，不执行商户的业务程序
		
				//注意：
				//退款日期超过可退款期限后（如三个月可退款），支付宝系统发送该交易状态通知
			}else if ($_POST['trade_status'] == 'TRADE_SUCCESS') {
				//注意：
				//付款完成后，支付宝系统发送该交易状态通知
				//处理支付完成订单
				//订单类型
				$type=substr($out_trade_no, 0,2);//截取前2位
				//订单号
				$order_num=substr($out_trade_no, 3);//截取掉前3位
				switch ($type)
				{
					//升级为VIP会员
					case 'v1':
						$UserGroupRecharge=new \Common\Model\UserGroupRechargeModel();
						$res=$UserGroupRecharge->treatUpgrade($order_num, 'alipay');
						break;
					//商城
					case 'sp':
						$Order=new \Common\Model\OrderModel();
						$res=$Order->treatOrder($order_num, 'alipay');
						break;
					default:
						break;
				}
				if($res===false)
				{
					exit();
				}
			}
			//***请不要修改或删除，支付宝异步通知页面，最终必须只能输出success，其它任何字符都会报错***
			echo "success";
		}else {
			//验证失败
			echo "fail";
			//调试用，写文本函数记录程序运行情况是否正常
			//writeLog("这里写入想要调试的代码变量值，或其他运行的结果记录");
			//记录日志
			if(APPLOG_DEBUG===true)
			{
			    writeLog(json_encode ($_POST,JSON_UNESCAPED_UNICODE));
			}
		}
	}
}
?>