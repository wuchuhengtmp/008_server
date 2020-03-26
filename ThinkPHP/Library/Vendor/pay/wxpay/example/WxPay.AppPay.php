<?php
//require_once "../lib/WxPay.Api.php";
/**
 * APP支付实现类
 */
class AppPay
{
	public $data = null;
	
	/**
	 * 
	 * 获取app支付的参数
	 * @param array $UnifiedOrderResult 统一支付接口返回的数据
	 * @throws WxPayException
	 * @return json数据，可直接填入js函数作为参数
	 */
	public function GetAppParameters($UnifiedOrderResult)
	{
		if(!array_key_exists("appid", $UnifiedOrderResult)
		|| !array_key_exists("prepay_id", $UnifiedOrderResult)
		|| $UnifiedOrderResult['prepay_id'] == "")
		{
			throw new WxPayException("参数错误");
		}
		$api = new WxPayAppPay();
		//应用ID
		$api->SetAppid($UnifiedOrderResult["appid"]);
		//商户号
		$api->SetMchid($UnifiedOrderResult["mch_id"]);
		//预支付交易会话ID
		$api->SetPrepayid($UnifiedOrderResult["prepay_id"]);
		//扩展字段，暂填写固定值Sign=WXPay
		$api->SetPackage("Sign=WXPay");
		//随机字符串
		$api->SetNonceStr(WxPayApi::getNonceStr());
		//时间戳
		$timeStamp = time();
		$api->SetTimeStamp($timeStamp);
		//签名
		$api->SetPaySign($api->MakeSign());
		$parameters = json_encode($api->GetValues());
		return $parameters;
	}
}