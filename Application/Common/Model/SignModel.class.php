<?php
namespace Common\Model;

class SignModel
{
	//API接口名称
	public $type='';
	//接口秘钥
	protected $secret='999dmooohuikexiong999';
	//UNIX时间戳，单位秒
	public $timestamp='';
	//响应格式，即返回数据的格式，JSON，注意是大写
	public $data_type = "JSON";
	//API协议版本号，默认为V1
	public $version = "V1";
	
	/**
	 * 验签方法
	 * @param $params 请求参数
	 * @return boolean
	 */
	public function checkVerify($params)
	{
		$get_sign=$params['sign'];
		//去除数组中的sign参数
		unset($params['sign']);
		unset($params['c']);
		unset($params['a']);
		//按首字母升序排列
		ksort($params);
		//把排序后的结果按照参数名+参数值的方式拼接
		$stringToBeSigned = $this->secret;
		foreach ($params as $k => $v)
		{
			if("@" != substr($v, 0, 1))
			{
				$stringToBeSigned .= "$k$v";
			}
		}
		$stringToBeSigned .= $this->secret;
		//拼装好的字符串首尾拼接secret进行md5加密后转大写
		$sign_str=strtoupper(md5($stringToBeSigned));
		if($get_sign==$sign_str)
		{
			return true;
		}else {
			return false;
		}
	}
}
?>