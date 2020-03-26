<?php
/**
 * 支付宝APP支付
 * 2017-08-03
 * @author 葛阳
 */

require_once 'alipay_app/AopSdk.php';

class alipayApp
{
	/**
	 * 获取支付表单数据
	 * @param String $body:交易的具体描述信息，可空
	 * @param String $subject:订单名称，必填
	 * @param String $out_trade_no:商户订单号，商户网站订单系统中唯一订单号，必填
	 * @param float $total_amount:付款金额，必填，单位为元，精确到小数点后两位
	 * @return array:支付表单数据
	 */
	public function GetParameters($body='',$subject,$out_trade_no,$total_amount)
	{
		$aop = new AopClient ();
		//业务参数
		$bizContent=array(
				'body'=>$body,
				'subject'=>$subject,
				'out_trade_no'=>$out_trade_no,
				'total_amount'=>$total_amount,
				'product_code'=>'QUICK_MSECURITY_PAY',//销售产品码，商家和支付宝签约的产品码，为固定值QUICK_MSECURITY_PAY
		);
		$bizContent=json_encode($bizContent);
		//实例化具体API对应的request类，类名称和接口名称对应，当前调用接口名称：alipay.trade.app.pay
		$request =new AlipayTradeAppPayRequest();
		//异步通知地址
		$notify_url=WEB_URL.'/app.php/AliNotify/notify';
		$request->setNotifyUrl($notify_url);
		$request->setBizContent($bizContent);
		$response = $aop->sdkExecute($request);
		//htmlspecialchars是为了输出到页面时防止被浏览器将关键参数html转义，实际打印到日志以及http传输不会有这个问题
		//就是orderString 可以直接给客户端请求，无需再做处理。
		//return htmlspecialchars($response);
		return $response;
	}
	
	/**
	 * 单笔转账到支付宝账号
	 * @param string $out_biz_no:必填，商户转账唯一订单号
	 * @param string $payee_account:必填，收款方账户。与payee_type配合使用。付款方和收款方不能是同一个账户。
	 * @param float $amount:必填，转账金额，单位：元。 只支持2位小数，小数点前最大支持13位，金额必须大于等于0.1元。
	 * @param string $payer_show_name:付款方姓名（最长支持100个英文/50个汉字）。显示在收款方的账单详情页。如果该字段不传，则默认显示付款方的支付宝认证姓名或单位名称。
	 * @param string $payee_real_name:收款方真实姓名（最长支持100个英文/50个汉字）。 如果本参数不为空，则会校验该账户在支付宝登记的实名是否与收款方真实姓名一致。
	 * @param string $remark:转账备注（支持200个英文/100个汉字）。 当付款方为企业账户，且转账金额达到（大于等于）50000元，remark不能为空。收款方可见，会展示在收款用户的收支详情中。
	 */
	public function fundTransToaccountTransfer($out_biz_no,$payee_account,$amount,$payer_show_name='',$payee_real_name='',$remark='')
	{
		$aop = new AopClient ();
		//$aop->postCharset='GBK';
		//业务参数
		$bizContent=array(
				'out_biz_no'=>$out_biz_no,
				'payee_type'=>'ALIPAY_LOGONID',//收款方账户类型。可取值： 1、ALIPAY_USERID：支付宝账号对应的支付宝唯一用户号。以2088开头的16位纯数字组成。 2、ALIPAY_LOGONID：支付宝登录号，支持邮箱和手机号格式。
				'payee_account'=>$payee_account,
				'amount'=>$amount,
				'payer_show_name'=>$payer_show_name,
				'payee_real_name'=>$payee_real_name,
				'remark'=>$remark,//转账备注
		);
		$bizContent=json_encode($bizContent);
		$request = new AlipayFundTransToaccountTransferRequest ();
		$request->setBizContent($bizContent);
		$result = $aop->execute ( $request);
	
		$responseNode = str_replace(".", "_", $request->getApiMethodName()) . "_response";
		$resultCode = $result->$responseNode->code;
		if(!empty($resultCode)&&$resultCode == 10000)
		{
			//echo "成功";
			$res=array(
					'code'=>0,
					'msg'=>'成功'
			);
		} else {
			//echo "失败";
			$resultSubCode=$result->$responseNode->sub_code;
			$msg='错误码：'.$resultSubCode.'，错误原因：'.$result->$responseNode->sub_msg;
			$res=array(
					'code'=>$resultCode,
					'msg'=>$msg
			);
		}
		return $res;
	}
	
	/**
	 * 验签方法
	 * @param $params 验签支付宝返回的信息，使用支付宝公钥。
	 * @return boolean
	 */
	public function checkVerify($params)
	{
		$aop = new AopClient();
		$result = $aop->rsaCheckV1($params, null, "RSA2");
		return $result;
	}
}