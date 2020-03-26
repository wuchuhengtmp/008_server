<?php
/**
 * 手机短信类
 * 注意：
 * 不同的客户请替换账号sid，密码apikey,短信模板tplid
 * 验证码有效时间为10分钟，可设置valid_time进行修改
 */
namespace Common\Model;
use Think\Model;
header("Access-Control-Allow-Origin:*");

class SmsModel extends Model
{
	public $ERROR_CODE_COMMON =array();     // 公共返回码
	public $ERROR_CODE_COMMON_ZH =array();  // 公共返回码中文描述
	public $ERROR_CODE_SMS =array();       // 手机短信返回码
	public $ERROR_CODE_SMS_ZH =array();    // 手机短信返回码中文描述
	
	protected $sid = SMS_SID; //账号
	protected $apikey = SMS_APIKEY;  //密码
	//protected $svr_rest = "http://api.rcscloud.cn:8030/rcsapi/rest";  //rest请求地址  或使用IP：121.14.114.153
	protected $svr_rest = "http://121.41.114.153:8030/rcsapi/rest";  //rest请求地址  或使用IP：121.14.114.153
	protected $svr_url = '';  //服务器接口路径-发送短信地址
	//protected $tplid_default = "65cecc2760b3493a8ff29fa913a66b19";  //测试发送的模板id
	//protected $tplid_vip = "65cecc2760b3493a8ff29fa913a66b19";  //成为VIP后发送的模板id
	
	protected $tplid_default = SMS_TPL;  //测试发送的模板id
	protected $tplid_vip = SMS_TPL;  //成为VIP后发送的模板id
	
	protected $valid_time = 600; //验证码有效时间10分钟
		
	//初始化
	public function _initialize()
	{
		$this->ERROR_CODE_COMMON = json_decode(error_code_common,true);
		$this->ERROR_CODE_COMMON_ZH = json_decode(error_code_common_zh,true);
		$this->ERROR_CODE_SMS = json_decode(error_code_sms,true);
		$this->ERROR_CODE_SMS_ZH = json_decode(error_code_sms_zh,true);
		
		//设置服务器接口路径
		$this->svr_url=$this->svr_rest."/sms/sendtplsms.json";
	}
	
	/**
	 * 发送短信
	 * @param string $mobile:手机号码
	 * @param string $content:短信内容
	 * @return array
	 * @return @param code:返回码
	 * @return @param msg:返回码说明
	 */
	public function sendMessage($mobile,$content='',$tplid_type='')
	{
		//短信模板
		switch ($tplid_type)
		{
			case 'default':
				$tplid=$this->tplid_default;
				break;
			case 'vip':
				$tplid=$this->tplid_vip;
				break;
			default:
				$tplid=$this->tplid_default;
				break;
		}
		if(is_phone($mobile)==false)
		{
			//不是正确的手机号码格式
			$res=array(
					'code'=>$this->ERROR_CODE_COMMON['PHONE_FORMAT_ERROR'],
					'msg'=>$this->ERROR_CODE_COMMON_ZH[$this->ERROR_CODE_COMMON['PHONE_FORMAT_ERROR']]
			);
		}else {
			// 签名
			$sign = $this->generateSign($mobile, $content , $tplid);
			
			//判断是否已有该手机号码发送记录
			$msg=$this->where("mobile='$mobile'")->find();
			if($msg)
			{
				//已有记录
				$last_send_time=$msg['send_time'];
				if( (time()-$last_send_time) > 60)
				{
					//发送短信
					$post_data=array(
							'sid'=>$this->sid,
							'sign'=>$sign,
							'tplid'=>$tplid,
							'mobile'=>$mobile,
							'content'=>$content
					);
					$json=$this->request_post($this->svr_url,$post_data);
					$result=json_decode($json,true);
					if($result['code']=='0')
					{
						//修改
						$data=array(
								'code'=>substr($content, -4),
								'send_time'=>time()
						);
						$res_save=$this->where("mobile='$mobile'")->save($data);
						if($res_save!==false)
						{
							$res=array(
									'code'=>$this->ERROR_CODE_COMMON['SUCCESS'],
									'msg'=>'发送短信成功'
							);
						}else {
							// 数据库错误
							$res=array(
									'code'=>$this->ERROR_CODE_COMMON['DB_ERROR'],
									'msg'=>$this->ERROR_CODE_COMMON_ZH[$this->ERROR_CODE_COMMON['DB_ERROR']]
							);
						}
					}else {
						//发送失败-短信接口返回的错误
						$res=array(
								'code'=>$this->ERROR_CODE_SMS['API_ERROR'],
								'msg'=>'发送失败：错误码：'.$result['code'].';错误描述：'.$result['msg'],
						);
					}
				}else {
					// 1分钟内只允许发送一条短信
					$res=array(
							'code'=>$this->ERROR_CODE_SMS['SEND_LIMIT'],
							'msg'=>$this->ERROR_CODE_SMS_ZH[$this->ERROR_CODE_SMS['SEND_LIMIT']]
					);
				}
			}else {
				//没有记录，第一次发送短信，进行添加操作
				$post_data=array(
						'sid'=>$this->sid,
						'sign'=>$sign,
						'tplid'=>$tplid,
						'mobile'=>$mobile,
						'content'=>$content
				);
				$json=$this->request_post($this->svr_url,$post_data);
				$result=json_decode($json,true);
				if($result['code']=='0')
				{
					//保存到数据库
					$data=array(
							'mobile'=>$mobile,
							'code'=>substr($content, -4),
							'send_time'=>time()
					);
					$res_add=$this->add($data);
					if($res_add!==false)
					{
						$res=array(
								'code'=>$this->ERROR_CODE_COMMON['SUCCESS'],
								'msg'=>'发送短信成功'
						);
					}else {
						// 数据库错误
						$res=array(
								'code'=>$this->ERROR_CODE_COMMON['DB_ERROR'],
								'msg'=>$this->ERROR_CODE_COMMON_ZH[$this->ERROR_CODE_COMMON['DB_ERROR']]
						);
					}
				}else {
					//发送失败-短信接口返回的错误
					$res=array(
							'code'=>$this->ERROR_CODE_SMS['API_ERROR'],
							'msg'=>'发送失败：错误码：'.$result['code'].';错误描述：'.$result['msg'],
					);
				}
			}
		}
		return $res;
	}
	
	/**
	 * 检查验证码是否正确
	 * @param string $mobile:手机号码
	 * @param string $code:验证码
	 * @return array
	 * @return @param code:返回码
	 * @return @param msg:返回码说明
	 */
	public function checkCode($mobile,$code)
	{
		$msg=$this->where("mobile='$mobile'")->find();
		if($msg)
		{
			//有效时间10分钟
			$last_send_time=$msg['send_time'];
			if( (time()-$last_send_time) > $this->valid_time )
			{
				// 验证码已过有效时间
				$res=array(
						'code'=>$this->ERROR_CODE_SMS['BEYOND_VALID_TIME'],
						'msg'=>$this->ERROR_CODE_SMS_ZH[$this->ERROR_CODE_SMS['BEYOND_VALID_TIME']],
				);
			}else {
				if($code!=$msg['code'])
				{
					// 验证码错误
					$res=array(
							'code'=>$this->ERROR_CODE_SMS['CODE_ERROR'],
							'msg'=>$this->ERROR_CODE_SMS_ZH[$this->ERROR_CODE_SMS['CODE_ERROR']],
					);
				}else {
					// 验证码正确
					$res=array(
							'code'=>$this->ERROR_CODE_COMMON['SUCCESS'],
							'msg'=>'验证码正确'
					);
				}
			}
		}else {
			// 手机号码不存在
			$res=array(
					'code'=>$this->ERROR_CODE_SMS['MOBILE_NOT_EXIST'],
					'msg'=>$this->ERROR_CODE_SMS_ZH[$this->ERROR_CODE_SMS['MOBILE_NOT_EXIST']],
			);
		}
		return $res;
	}
	
	/**
	 * 查询账号所有模板
	 * @return array
	 */
	public function searchTpl()
	{
		// 签名认证 Md5(sid+apikey)
		$sign = md5($this->sid.$this->apikey);
		// 服务器接口路径
		$svr_url  = $this->svr_rest."/tpl/gets.json?sid=".$this->sid."&sign=".$sign;
		// 获取信息
		$json_arr = json_decode(file_get_contents($svr_url));
		return $json_arr;
	}
	
	/**
	 * 生成签名
	 * @param string $tplid:模板ID
	 * @param string $mobile:手机号码
	 * @param string $content:发送内容
	 * @return string:签名
	 */
	protected function generateSign($mobile,$content,$tplid)
	{
		//签名认证 Md5(sid+apikey+tplid+mobile+content)
		$sign = md5($this->sid.$this->apikey.$tplid.$mobile.$content);
		return $sign;             
	}
	
	/**
	 * URL请求
	 * @param string $url:请求地址
	 * @param array $post_data:请求数据数组
	 * @return boolean|array
	 */
	public function request_post($url = '', $post_data = array()) 
	{
		if (empty($url) || empty($post_data)) 
		{
			return false;
		}
		
		$o = "";
		foreach ( $post_data as $k => $v ) 
		{
			$o.= "$k=" . urlencode( $v ). "&" ;
		}
		$post_data = substr($o,0,-1);
		$postUrl = $url;
		$curlPost = $post_data;
		$ch = curl_init();//初始化curl
		curl_setopt($ch, CURLOPT_URL,$postUrl);//抓取指定网页
		curl_setopt($ch, CURLOPT_HEADER, 0);//设置header
		curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/x-www-form-urlencoded','Content-Encoding: utf-8'));
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);//要求结果为字符串且输出到屏幕上
		curl_setopt($ch, CURLOPT_POST, 1);//post提交方式
		curl_setopt($ch, CURLOPT_POSTFIELDS, $curlPost);
		$data = curl_exec($ch);//运行curl
		curl_close($ch);
		
		return $data;
	}
}