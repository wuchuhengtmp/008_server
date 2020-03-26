<?php
class PddClient
{
	//正式环境
	public $serverUrl = "http://gw-api.pinduoduo.com/api/router";
	//API接口名称
	public $type='';
	//POP分配给应用的client_id
	public $client_id=PDD_CLIENT_ID;
	//POP分配给应用的client_secret
	public $client_secret=PDD_CLIENT_SECRET;
	//通过code获取的access_token(无需授权的接口，该字段不参与sign签名运算)
	public $access_token;
	//UNIX时间戳，单位秒，需要与拼多多服务器时间差值在10分钟内
	public $timestamp='';
	//响应格式，即返回数据的格式，JSON或者XML（二选一），默认JSON，注意是大写
	public $data_type = "JSON";
	//API协议版本号，默认为V1，可不填
	public $version = "V1";
	//API输入参数签名结果，签名算法参考开放平台接入指南第三部分底部。
	public $sign='';
	
	public $connectTimeout = 0;
	public $readTimeout = 0;

	/**
	 * 签名大体过程如下：
	 * 1-所有参数进行按照首字母先后顺序排列
	 * 2-把排序后的结果按照参数名+参数值的方式拼接
	 * 3-拼装好的字符串首尾拼接client_secret进行md5加密后转大写，secret的值是拼多多开放平台后台分配的client_secret
	 * @param unknown $params
	 * @return string
	 */
	protected function generateSign($params)
	{
		//按首字母升序排列
		ksort($params);
		//把排序后的结果按照参数名+参数值的方式拼接
		$stringToBeSigned = $this->client_secret;
		foreach ($params as $k => $v)
		{
			if("@" != substr($v, 0, 1))
			{
				$stringToBeSigned .= "$k$v";
			}
		}
		unset($k, $v);
		$stringToBeSigned .= $this->client_secret;
		//拼装好的字符串首尾拼接client_secret进行md5加密后转大写
		return strtoupper(md5($stringToBeSigned));
	}

	public function curl($url, $postFields = null)
	{
		$ch = curl_init();
		
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_FAILONERROR, false);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		if ($this->readTimeout) {
			curl_setopt($ch, CURLOPT_TIMEOUT, $this->readTimeout);
		}
		if ($this->connectTimeout) {
			curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $this->connectTimeout);
		}
		//https 请求
		if(strlen($url) > 5 && strtolower(substr($url,0,5)) == "https" ) {
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
			curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
		}

		if (is_array($postFields) && 0 < count($postFields))
		{
			$postBodyString = "";
			$postMultipart = false;
			foreach ($postFields as $k => $v)
			{
				if("@" != substr($v, 0, 1))//判断是不是文件上传
				{
					$postBodyString .= "$k=" . urlencode($v) . "&"; 
				}
				else//文件上传用multipart/form-data，否则用www-form-urlencoded
				{
					$postMultipart = true;
				}
			}
			unset($k, $v);
			//以post方式传递数据
			curl_setopt($ch, CURLOPT_POST, true);
			if ($postMultipart)
			{
				curl_setopt($ch, CURLOPT_POSTFIELDS, $postFields);
			}
			else
			{
				curl_setopt($ch, CURLOPT_POSTFIELDS, substr($postBodyString,0,-1));
			}
		}
		$reponse = curl_exec($ch);
		
		if (curl_errno($ch))
		{
			throw new Exception(curl_error($ch),0);
		}
		else
		{
			$httpStatusCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
			if (200 !== $httpStatusCode)
			{
				throw new Exception($reponse,$httpStatusCode);
			}
		}
		curl_close($ch);
		return $reponse;
	}

	public function execute($request, $access_token = null)
	{
		//组装系统参数
		$sysParams["type"] = $this->type;
		$sysParams["client_id"] = $this->client_id;
		if (null != $access_token)
		{
			$sysParams["access_token"] = $access_token;
		}
		$sysParams["timestamp"] = time();
		$sysParams["data_type"] = $this->data_type;
		$sysParams["version"] = $this->version;
		
		//获取业务参数
		//合并数组
		$sysParams=array_merge($sysParams,$request);
		
		//签名
		$sysParams["sign"] = $this->generateSign($sysParams);
		
		/* //拼装API请求，系统参数放入GET请求串
		$requestUrl = $this->serverUrl . "?";
		foreach ($sysParams as $sysParamKey => $sysParamValue)
		{
			$requestUrl .= "$sysParamKey=" . urlencode($sysParamValue) . "&";
			//$requestUrl .= "$sysParamKey=" . $sysParamValue . "&";
		}
		$requestUrl=substr($requestUrl, 0,-1); */
		
		//发起HTTP请求
		//$resp = $this->curl($requestUrl);
		$resp = $this->curl($this->serverUrl,$sysParams);

		return $resp;
	}
}
?>