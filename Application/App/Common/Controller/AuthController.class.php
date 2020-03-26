<?php
namespace App\Common\Controller;
use Think\Controller;

//权限认证
class AuthController extends Controller 
{
	public $ERROR_CODE_COMMON =array();                      // 公共返回码
	public $ERROR_CODE_COMMON_ZH =array();                   // 公共返回码中文描述
	public $ERROR_CODE_SMS =array();                         // 手机短信返回码
	public $ERROR_CODE_SMS_ZH =array();                      // 手机短信返回码中文描述
	public $ERROR_CODE_EMAIL =array();                       // 邮件返回码
	public $ERROR_CODE_EMAIL_ZH =array();                    // 邮件返回码中文描述
	public $ERROR_CODE_USER =array();                        // 用户管理返回码
	public $ERROR_CODE_USER_ZH =array();                     // 用户管理返回码中文描述
	public $ERROR_CODE_GOODS =array();                       // 商品管理返回码
	public $ERROR_CODE_GOODS_ZH =array();                    // 商品管理返回码中文描述
	
	protected function _initialize()
	{
		// 返回码配置
		$this->ERROR_CODE_COMMON = json_decode(error_code_common,true);
		$this->ERROR_CODE_COMMON_ZH = json_decode(error_code_common_zh,true);
		$this->ERROR_CODE_SMS = json_decode(error_code_sms,true);
		$this->ERROR_CODE_SMS_ZH = json_decode(error_code_sms_zh,true);
		$this->ERROR_CODE_EMAIL = json_decode(error_code_email,true);
		$this->ERROR_CODE_EMAIL_ZH = json_decode(error_code_email_zh,true);
		$this->ERROR_CODE_USER = json_decode(error_code_user,true);
		$this->ERROR_CODE_USER_ZH = json_decode(error_code_user_zh,true);
		$this->ERROR_CODE_GOODS = json_decode(error_code_goods,true);
		$this->ERROR_CODE_GOODS_ZH = json_decode(error_code_goods_zh,true);
	}
}