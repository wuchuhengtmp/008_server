<?php
/**
 * 微信支付
 * 2017-03-29
 * @author 葛阳
 */
require_once "wxpay/lib/WxPay.Api.php";
require_once "wxpay/example/WxPay.JsApiPay.php";
require_once "wxpay/example/WxPay.AppPay.php";
require_once 'wxpay/example/log.php';

class wxpay
{
	public function __construct()
	{
		//初始化日志
		$logHandler= new CLogFileHandler("../logs/".date('Y-m-d').'.log');
		$log = Log::Init($logHandler, 15);
	}
	
	/**
	 * 获取支付表单数据-公众号支付
	 * @param String(128) $body:必填，商品描述
	 * @param String(6000) $attach:非必填，附加数据
	 * @param String(32) $out_trade_no:必填，商户系统内部订单号，要求32个字符内、且在同一个商户号下唯一
	 * @param int $total_fee:必填，订单总金额，单位为分
	 * @param String(255) $notify_url:必填，异步接收微信支付结果通知的回调地址，通知url必须为外网可访问的url，不能携带参数
	 * @param String(32) $goods_tag:非必填，商品标记，使用代金券或立减优惠功能时需要的参数
	 * @return array:支付表单数据
	 */
	public function GetJsApiParameters($body,$out_trade_no,$total_fee,$notify_url,$attach='',$goods_tag='')
	{
		//①、获取用户openid
		$tools = new JsApiPay();
		$openId = $tools->GetOpenid();
		
		//②、统一下单
		$input = new WxPayUnifiedOrder();
		//商品描述
		$input->SetBody($body);
		//附加数据
		$input->SetAttach($attach);
		//商户系统内部订单号
		$input->SetOut_trade_no($out_trade_no);
		//订单总金额
		$input->SetTotal_fee($total_fee);
		//订单生成时间
		$input->SetTime_start(date("YmdHis"));
		//订单失效时间
		$input->SetTime_expire(date("YmdHis", time() + 600));
		//商品标记
		$input->SetGoods_tag($goods_tag);
		//异步接收微信支付结果通知的回调地址
		$input->SetNotify_url($notify_url);
		//交易类型，JSAPI--公众号支付
		$input->SetTrade_type("JSAPI");
		$input->SetOpenid($openId);
		$order = WxPayApi::unifiedOrder($input);
		$jsApiParameters = $tools->GetJsApiParameters($order);
		return $jsApiParameters;
	}
	
	/**
	 * 获取支付表单数据-APP支付
	 * @param String(128) $body:必填，商品描述
	 * @param String(32) $out_trade_no:必填，商户系统内部订单号，要求32个字符内、且在同一个商户号下唯一
	 * @param int $total_fee:必填，订单总金额，单位为分
	 * @param String(255) $notify_url:必填，异步接收微信支付结果通知的回调地址，通知url必须为外网可访问的url，不能携带参数
	 * @param string $attach
	 * @param String(32) $goods_tag:非必填，商品标记，使用代金券或立减优惠功能时需要的参数
	 * @return array:支付表单数据
	 */
	public function GetAppParameters($body,$out_trade_no,$total_fee,$notify_url,$attach='',$goods_tag='')
	{
		//①请求prepay_id
		//统一下单
		$input = new WxPayUnifiedOrder();
		//商品描述
		$input->SetBody($body);
		//附加数据
		$input->SetAttach($attach);
		//商户系统内部订单号
		$input->SetOut_trade_no($out_trade_no);
		//订单总金额
		$input->SetTotal_fee($total_fee);
		//订单生成时间
		$input->SetTime_start(date("YmdHis"));
		//订单失效时间
		$input->SetTime_expire(date("YmdHis", time() + 600));
		//商品标记
		$input->SetGoods_tag($goods_tag);
		//异步接收微信支付结果通知的回调地址
		$input->SetNotify_url($notify_url);
		//交易类型，APP--移动应用APP支付
		$input->SetTrade_type("APP");
		$order = WxPayApi::unifiedOrder($input);
		
		//返回给客户端信息
		$tools=new AppPay();
		$AppParameters = $tools->GetAppParameters($order);
		return $AppParameters;
	}
	
	/**
	 * 扫码支付模式
	 * 该模式的预付单有效期为2小时，过期后无法支付。
	 * 二流程：
	 * 1、调用统一下单，取得code_url，生成二维码
	 * 2、用户扫描二维码，进行支付
	 * 3、支付完成之后，微信服务器会通知支付成功
	 * 4、在支付成功通知中需要查单确认是否真正支付成功（见：notify.php）
	 * 取得code_url，生成二维码
	 * @param String(128) $body:必填，商品描述
	 * @param String(6000) $attach:非必填，附加数据
	 * @param String(32) $out_trade_no:必填，商户系统内部订单号，要求32个字符内、且在同一个商户号下唯一
	 * @param int $total_fee:必填，订单总金额，单位为分
	 * @param String(255) $notify_url:必填，异步接收微信支付结果通知的回调地址，通知url必须为外网可访问的url，不能携带参数
	 * @param String(32) $goods_tag:非必填，商品标记，使用代金券或立减优惠功能时需要的参数
	 * @return code_url
	 */
	public function GetNative2Parameters($body,$out_trade_no,$total_fee,$notify_url,$attach='',$goods_tag='')
	{
		$input = new WxPayUnifiedOrder();
		$input->SetBody($body);
		$input->SetAttach($attach);
		$input->SetOut_trade_no(WxPayConfig::MCHID.date("YmdHis"));
		$input->SetTotal_fee($total_fee);
		$input->SetTime_start(date("YmdHis"));
		$input->SetTime_expire(date("YmdHis", time() + 600));
		$input->SetGoods_tag($goods_tag);
		$input->SetNotify_url($notify_url);
		$input->SetTrade_type("NATIVE");
		$input->SetProduct_id($out_trade_no);
		$notify = new NativePay();
		$result = $notify->GetPayUrl($input);
		$url2 = $result["code_url"];
		return $url2;
	}
}