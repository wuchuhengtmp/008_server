<?php
/**
 * 用户管理
 */
namespace Common\Model;
use Think\Model;

class UserModel extends Model
{
	public $ERROR_CODE_COMMON =array();     // 公共返回码
	public $ERROR_CODE_COMMON_ZH =array();  // 公共返回码中文描述
	public $ERROR_CODE_USER =array();       // 用户管理返回码
	public $ERROR_CODE_USER_ZH =array();    // 用户管理返回码中文描述
	
	//初始化
	protected function _initialize()
	{
		$this->ERROR_CODE_COMMON = json_decode(error_code_common,true);
		$this->ERROR_CODE_COMMON_ZH = json_decode(error_code_common_zh,true);
		$this->ERROR_CODE_USER = json_decode(error_code_user,true);
		$this->ERROR_CODE_USER_ZH = json_decode(error_code_user_zh,true);
	}
	
	//验证规则
	protected $_validate =array(
			array('group_id','require','会员组不能为空！',self::EXISTS_VALIDATE),  //存在验证，必填
			array('group_id','is_positive_int','请选择正确的会员组',self::EXISTS_VALIDATE,'function'),  //存在验证，必须是正整数
			array('username','1,20','用户名不超过20个字符！',self::VALUE_VALIDATE,'length'),  //值不为空的时候验证，不超过20个字符
			//array('password','require','密码不能为空！',self::EXISTS_VALIDATE),  //存在验证，必填
			array('password','32','密码格式不正确！',self::EXISTS_VALIDATE,'length'),  //存在验证，密码只能是加密后的32位字符串
			array('phone','1,20','手机号码不超过20个字符！',self::VALUE_VALIDATE,'length'),  //值不为空的时候验证，不超过20个字符
			array('email','email','邮箱格式不正确！',self::VALUE_VALIDATE),  //值不为空的时候验证，必须是邮箱格式
			array('auth_code_id','is_positive_int','请填写正确的用户授权码组',self::EXISTS_VALIDATE,'function'),  //存在验证，必须是正整数
			array('balance','currency','不是正确的货币格式！',self::VALUE_VALIDATE),  //值不为空的时候验证 ，必须是货币格式
			array('point','currency','积分必须为不小于零的整数！',self::VALUE_VALIDATE,'function'),  //值不为空的时候验证 ，必须是自然数
			//array('exp','is_natural_num','经验必须为不小于零的整数！',self::VALUE_VALIDATE,'function'),  //值不为空的时候验证 ，必须是自然数
			array('register_time','require','注册时间不能为空！',self::EXISTS_VALIDATE),  //存在验证，必填
			array('register_time','is_datetime','注册时间格式不正确！',self::EXISTS_VALIDATE,'function'),  //存在验证，必须是正确的时间格式
			array('register_ip','require','注册IP不能为空！',self::EXISTS_VALIDATE),  //存在验证，必填
			array('register_ip','1,20','注册IP不超过20个字符！',self::EXISTS_VALIDATE,'length'),  //存在验证，不超过20个字符
			array('login_num','is_natural_num','登录次数必须为不小于零的整数！',self::VALUE_VALIDATE,'function'),  //值不为空的时候验证 ，必须是自然数
			array('last_login_time','is_datetime','最后登录时间格式不正确！',self::VALUE_VALIDATE,'function'),  //值不为空的时候验证 ，必须是正确的时间格式
			array('last_login_ip','1,20','最后登录IP不超过20个字符！',self::VALUE_VALIDATE,'length'),  //值不为空的时候验证 ，不超过20个字符
			array('openid','1,50','第三方应用OPENID不超过50个字符！',self::VALUE_VALIDATE,'length'),  //值不为空的时候验证 ，不超过50个字符
			array('third_type','1,10','第三方应用类型不超过10个字符！',self::VALUE_VALIDATE,'length'),  //值不为空的时候验证 ，不超过10个字符
			array('is_freeze',array('Y','N'),'请选择是否冻结！',self::EXISTS_VALIDATE,'in'),  //存在验证，只能是Y是 N否
			array('referrer_id','is_positive_int','请选择正确的推荐人',self::VALUE_VALIDATE,'function'),  //值不为空的时候验证，必须是正整数
			array('tb_uid','1,10','淘宝用户ID不超过10个字符！',self::VALUE_VALIDATE,'length'),  //值不为空的时候验证，不超过10个字符
			array('tb_pid','1,50','淘宝推广位不超过50个字符！',self::VALUE_VALIDATE,'length'),  //值不为空的时候验证，不超过50个字符
	);
	
	/**
	 * 密码加密
	 * @param string $pwd:明文密码
	 * @return string:加密后的密文密码
	 */
	public function encrypt($pwd)
	{
		$password=md5($pwd.'9'.substr($pwd,0,3));
		return $password;
	}
	
	/**
	 * 生成APP访问令牌
	 * @param int $uid:用户ID
	 * @return string
	 */
	public function getAccessToken($uid)
	{
		$str=$uid.uniqid().rand(100, 999);
		$token=md5($str);
		return $token;
	}
	
	/**
	 * 检查会员身份
	 * @param string $token:会员身份令牌
	 * @return array
	 * @return @param code:返回码
	 * @return @param msg:返回码说明
	 * @return @param uid:用户ID
	 */
	public function checkToken($token)
	{
		$msg=$this->where("token='$token'")->find();
		if($msg)
		{
			$uid=$msg['uid'];
			//判断用户是否有效
			$res_freeze=$this->where("uid=$uid")->field('is_freeze')->find();
			if($res_freeze) {
			    if($res_freeze['is_freeze']=='N') {
			        //用户有效，返回用户ID
			        $res=array(
			            'code'=>$this->ERROR_CODE_COMMON['SUCCESS'],
			            'msg'=>'用户身份有效',
			            'uid'=>$uid
			        );
			    }else {
			        // 被冻结
			        $res=array(
			            'code'=>$this->ERROR_CODE_USER['USER_FROZEN'],
			            'msg'=>$this->ERROR_CODE_USER_ZH[$this->ERROR_CODE_USER['USER_FROZEN']]
			        );
			    }
			}else {
			    // 用户不存在
			    $res=array(
			        'code'=>$this->ERROR_CODE_USER['USER_NOT_EXIST'],
			        'msg'=>$this->ERROR_CODE_USER_ZH[$this->ERROR_CODE_USER['USER_NOT_EXIST']]
			    );
			}
		}else {
			// 用户不存在
			$res=array(
					'code'=>$this->ERROR_CODE_USER['USER_NOT_EXIST'],
					'msg'=>$this->ERROR_CODE_USER_ZH[$this->ERROR_CODE_USER['USER_NOT_EXIST']]
			);
		}
		return $res;
	}
	
	/**
	 * 获取会员信息
	 * @param int $uid:会员ID
	 * @return array|false
	 */
	public function getUserMsg($uid)
	{
		$msg=$this->where("uid='$uid'")->find();
		if($msg)
		{
			if(empty($msg['auth_code'])){
				$msg['auth_code']="";
			}
			return $msg;
		}else {
			return false;
		}
	}
	
	/**
	 * 获取会员详情
	 * @param int $uid:会员ID
	 * @return array|false
	 */
	public function getUserDetail($uid)
	{
		$msg=$this->getUserMsg($uid);
		if($msg!==false)
		{
			//会员账号
			if($msg['phone']) {
				$account=$msg['phone'];
			}else if($msg['username']) {
				$account=$msg['username'];
			}else if($msg['email']) {
				$account=$msg['email'];
			}
			$msg['account']=$account;
			//会员组名称
			$msg['group_name']='';
			if($msg['group_id']){
			    $UserGroup=new \Common\Model\UserGroupModel();
			    $groupMsg=$UserGroup->getGroupMsg($msg['group_id']);
			    $msg['group_name']=$groupMsg['title'];
			}
			//会员详情
			$UserDetail=new \Common\Model\UserDetailModel();
			$detail=$UserDetail->getUserDetailMsg($uid);
			if($detail!==false) {
				$msg['detail']=$detail;
				return $msg;
			}else {
				return false;
			}
		}else {
			return false;
		}
	}
	
	/**
	 * 检测用户是否被冻结
	 * @param string $account:账号（用户名|手机|邮箱）
	 * @return array
	 * @return @param code:返回码
	 * @return @param msg:返回码说明
	 */
	public function is_freeze($account)
	{
	    if(is_phone($account)===true)
	    {
	        $where="phone='$account'";
	    }else if(is_email($account)===true)
	    {
	        $where="email='$account'";
	    }else {
	        $where="username='$account'";
	    }
	    $res_freeze=$this->where($where)->find();
	    if($res_freeze) {
	        if($res_freeze['is_freeze']=='N')
	        {
	            // 未冻结，可以使用
	            $data=array(
	                'userMsg'=>$res_freeze
	            );
	            $res=array(
	                'code'=>$this->ERROR_CODE_COMMON['SUCCESS'],
	                'msg'=>'用户未被冻结，可以使用！',
	                'data'=>$data
	            );
	        }else {
	            // 被冻结
	            $res=array(
	                'code'=>$this->ERROR_CODE_USER['USER_FROZEN'],
	                'msg'=>$this->ERROR_CODE_USER_ZH[$this->ERROR_CODE_USER['USER_FROZEN']]
	            );
	        }
	    }else {
	        // 用户不存在
	        $res=array(
	            'code'=>$this->ERROR_CODE_USER['USER_NOT_EXIST'],
	            'msg'=>$this->ERROR_CODE_USER_ZH[$this->ERROR_CODE_USER['USER_NOT_EXIST']]
	        );
	    }
	    return $res;
	}
	
	/**
	 * 登录
	 * @param string $account:账号（用户名|手机|邮箱）
	 * @param string $pwd:明文密码
	 * @return array
	 * @return @param code:返回码
	 * @return @param msg:返回码说明
	 */
	public function login($account,$pwd)
	{
		//检测用户是否有效
		$res_valid=$this->is_freeze($account);
		if($res_valid['code']!=0) {
			//用户无效，不准登录
			$res=$res_valid;
		}else {
			//用户有效
			$msg=$res_valid['data']['userMsg'];
			if($msg) {
				//检验原密码是否正确
				$pwd=$this->encrypt($pwd);
				if($pwd!=$msg['password']) {
					// 账号或密码错误
					$res=array(
							'code'=>$this->ERROR_CODE_USER['USER_LOGIN_ERROR'],
							'msg'=>$this->ERROR_CODE_USER_ZH[$this->ERROR_CODE_USER['USER_LOGIN_ERROR']]
					);
				}else {
					// 修改最后登录信息
					$uid=$msg['uid'];
					$token=$this->getAccessToken($uid);
					$data=array(
							'login_num'=>$msg['login_num']+1,
							'last_login_time'=>date('Y-m-d H:i:s'),
							'last_login_ip'=>getIP(),
							'token'=>$token,
							'token_createtime'=>time()
					);
					$res_c=$this->where("uid='$uid'")->save($data);
					if($res_c!==false) {
						$res=array(
								'code'=>$this->ERROR_CODE_COMMON['SUCCESS'],
								'msg'=>'登录成功',
								'uid'=>$msg['uid'],
								'group_id'=>$msg['group_id'],
								'token'=>$token
						);
					}else {
						//数据库错误
						$res=array(
								'code'=>$this->ERROR_CODE_COMMON['DB_ERROR'],
								'msg'=>$this->ERROR_CODE_COMMON_ZH[$this->ERROR_CODE_COMMON['DB_ERROR']]
						);
					}
				}
			}else {
				// 用户不存在
				$res=array(
						'code'=>$this->ERROR_CODE_USER['USER_NOT_EXIST'],
						'msg'=>$this->ERROR_CODE_USER_ZH[$this->ERROR_CODE_USER['USER_NOT_EXIST']]
				);
			}
		}
		return $res;
	}

    /**
     * mob短信登录
     * @param string $phone:用户手机号
     * @return array
     * @return @param code:返回码
     * @return @param msg:返回码说明
     */
    public function mobSmsLogin($phone)
    {
        //检测用户是否有效
        $res_valid=$this->is_freeze($phone);
        if($res_valid['code']!=0) {
            //用户无效，不准登录
            $res=$res_valid;
        }else {
            //用户有效
            $msg=$res_valid['data']['userMsg'];
            if($msg) {
                // 修改最后登录信息
                $uid=$msg['uid'];
                $token=$this->getAccessToken($uid);
                $data=array(
                    'login_num'=>$msg['login_num']+1,
                    'last_login_time'=>date('Y-m-d H:i:s'),
                    'last_login_ip'=>getIP(),
                    'token'=>$token,
                    'token_createtime'=>time()
                );
                $res_c=$this->where("uid='$uid'")->save($data);
                if($res_c!==false) {
                    $res=array(
                        'code'=>$this->ERROR_CODE_COMMON['SUCCESS'],
                        'msg'=>'登录成功',
                        'uid'=>$msg['uid'],
                        'group_id'=>$msg['group_id'],
                        'token'=>$token
                    );
                }else {
                    //数据库错误
                    $res=array(
                        'code'=>$this->ERROR_CODE_COMMON['DB_ERROR'],
                        'msg'=>$this->ERROR_CODE_COMMON_ZH[$this->ERROR_CODE_COMMON['DB_ERROR']]
                    );
                }
            }else {
                // 用户不存在
                $res=array(
                    'code'=>$this->ERROR_CODE_USER['USER_NOT_EXIST'],
                    'msg'=>$this->ERROR_CODE_USER_ZH[$this->ERROR_CODE_USER['USER_NOT_EXIST']]
                );
            }
        }
        return $res;
    }
	
	/**
	 * 退出登录
	 * @param int $uid:会员ID
	 * @return boolean
	 */
	public function loginout($uid)
	{
		//退出时将身份令牌清空
		$data=array(
				'token'=>null,
				'token_createtime'=>null
		);
		$res=$this->where("uid='$uid'")->save($data);
		if($res!==false)
		{
			return true;
		}else {
			return false;
		}
	}
	
	/**
	 * 修改密码
	 * @param int $uid:会员ID
	 * @param string $oldpwd:原密码
	 * @param string $pwd1:新密码
	 * @param string $pwd2:重复密码
	 * @return array
	 * @return @param string code:返回码
	 * @return @param string msg:返回码说明
	 */
	public function changePwd($uid,$oldpwd,$pwd1,$pwd2)
	{
		//判断两次密码是否正确
		$res_pwd=$this->checkPwdFormat($pwd1, $pwd2);
		if($res_pwd['code']==0)
		{
			//验证原密码是否正确
			$res_old=$this->checkPwd($uid,$oldpwd);
			if($res_old)
			{
				//修改密码
				$newpwd=$this->encrypt($pwd2);
				$data=array(
						'password'=>$newpwd
				);
				$res2=$this->where("uid=$uid")->save($data);
				if($res2!==false)
				{
					$res=array(
							'code'=>$this->ERROR_CODE_COMMON['SUCCESS'],
							'msg'=>'成功！'
					);
				}else {
					// 数据库错误
					$res=array(
							'code'=>$this->ERROR_CODE_COMMON['DB_ERROR'],
							'msg'=>$this->ERROR_CODE_COMMON_ZH[$this->ERROR_CODE_COMMON['DB_ERROR']]
					);
				}
			}else {
				// 原密码错误
				$res=array(
						'code'=>$this->ERROR_CODE_USER['USER_OLD_PASSWORD_ERROR'],
						'msg'=>$this->ERROR_CODE_USER_ZH[$this->ERROR_CODE_USER['USER_OLD_PASSWORD_ERROR']]
				);
			}
		}else {
			//两次密码不合法
			$res=$res_pwd;
		}
		return $res;
	}
	
	/**
	 * 重置密码
	 * @param int $uid:会员ID
	 * @param string $pwd1:新密码
	 * @param string $pwd2:重复密码
	 * @return array
	 * @return @param string code:返回码
	 * @return @param string msg:返回码说明
	 */
	public function setpwd($uid,$pwd1,$pwd2)
	{
		if($pwd1==$pwd2)
		{
			if(strlen($pwd2)>5)
			{
				//修改密码
				$newpwd=$this->encrypt($pwd2);
				$data=array(
						'password'=>$newpwd
				);
				$res2=$this->where("uid=$uid")->save($data);
				if($res2!==false)
				{
					$res=array(
							'code'=>0,
							'msg'=>'成功！'
					);
				}else {
					$res=array(
							'code'=>2,
							'msg'=>'数据库连接失败！'
					);
				}
			}else {
				$res=array(
						'code'=>103,
						'msg'=>'新密码长度不少于5位！'
				);
			}
		}else {
			$res=array(
					'code'=>101,
					'msg'=>'两次密码不相同！'
			);
		}
		return $res;
	}
	
	/**
	 * 验证密码是否正确
	 * @param int $uid:会员ID
	 * @param string $pwd:未加密的密码
	 * @return boolean
	 */
	public function checkPwd($uid,$pwd)
	{
		$msg=$this->getUserMsg($uid);
		if($msg)
		{
			$password=$msg['password'];
			//加密
			$pwd=$this->encrypt($pwd);
			if($password!=$pwd)
			{
				return false;
			}else {
				return true;
			}
		}else {
			return false;
		}
	}
	
	/**
	 * 检验两次密码格式
	 * @param string $pwd1:密码
	 * @param string $pwd2:重复密码
	 * @return array
	 * @return @param string code:返回码
	 * @return @param string msg:返回码说明
	 */
	public function checkPwdFormat($pwd1,$pwd2)
	{
		//判断密码是否相同
		if($pwd1==$pwd2)
		{
			//判断密码不少于6位
			if( strlen($pwd1) < 6 )
			{
				//密码不少于6位
				$res=array(
						'code'=>$this->ERROR_CODE_COMMON['PASSWORD_FORMAT_ERROR'],
						'msg'=>$this->ERROR_CODE_COMMON_ZH[$this->ERROR_CODE_COMMON['PASSWORD_FORMAT_ERROR']]
				);
			}else {
				$res=array(
						'code'=>$this->ERROR_CODE_COMMON['SUCCESS'],
						'msg'=>'密码格式正确'
				);
			}
		}else {
			// 两次密码不相同
			$res=array(
					'code'=>$this->ERROR_CODE_COMMON['TWICE_PASSWORD_UNEQUAL'],
					'msg'=>$this->ERROR_CODE_COMMON_ZH[$this->ERROR_CODE_COMMON['TWICE_PASSWORD_UNEQUAL']]
			);
		}
		return $res;
	}
	
	/**
	 * 检查用户名称是否已被使用
	 * @param string $username:用户名称
	 * @return array
	 * @return @param string code:返回码
	 * @return @param string msg:返回码说明
	 */
	public function checkUserName($username)
	{
		$msg=$this->where("username='$username'")->find();
		if($msg)
		{
			//该用户名称已被使用！
			$res=array(
					'code'=>$this->ERROR_CODE_USER['USERNAME_ALREADY_EXISTS'],
					'msg'=>$this->ERROR_CODE_USER_ZH[$this->ERROR_CODE_USER['USERNAME_ALREADY_EXISTS']]
			);
		}else {
			$res=array(
					'code'=>$this->ERROR_CODE_COMMON['SUCCESS'],
					'msg'=>'该用户名称可以使用！'
			);
		}
		return $res;
	}
	
	/**
	 * 检查用户名格式、唯一性
	 * @param string $username:用户名
	 * @return array
	 * @return @param string code:返回码
	 * @return @param string msg:返回码说明
	 */
	public function checkUsername2($username)
	{
		$pattern = '/^[\w]{6,20}$/'; // \w 匹配任何字类字符，包括下划线。与[A-Za-z0-9_]等效。
		if (preg_match ( $pattern, $username ))
		{
			//判断用户名是否存在
			$res_exist=$this->where("username='$username'")->find();
			if($res_exist)
			{
				//用户名已存在
				$res=array(
						'code'=>$this->ERROR_CODE_USER['USERNAME_ALREADY_EXISTS'],
						'msg'=>$this->ERROR_CODE_USER_ZH[$this->ERROR_CODE_USER['USERNAME_ALREADY_EXISTS']]
				);
			}else {
				//可以使用
				$res=array(
						'code'=>$this->ERROR_CODE_COMMON['SUCCESS'],
						'msg'=>'用户名可以使用'
				);
			}
		}else {
			//用户名格式错误
			$res=array(
					'code'=>$this->ERROR_CODE_USER['USERNAME_FORMAT_ERROR'],
					'msg'=>'用户名只能是6-20位的数字和字母组成！'
			);
		}
		return $res;
	}
	
	/**
	 * 检查手机号码是否已被使用
	 * @param string $phone:手机号码
	 * @return array
	 * @return @param string code:返回码
	 * @return @param string msg:返回码说明
	 */
	public function checkPhone($phone)
	{
		$msg=$this->where("phone='$phone'")->find();
		if($msg)
		{
			//该手机号码已被使用！
			$res=array(
					'code'=>$this->ERROR_CODE_USER['PHONE_ALREADY_EXISTS'],
					'msg'=>$this->ERROR_CODE_USER_ZH[$this->ERROR_CODE_USER['PHONE_ALREADY_EXISTS']]
			);
		}else {
			$res=array(
					'code'=>$this->ERROR_CODE_COMMON['SUCCESS'],
					'msg'=>'该手机号码可以使用！'
			);
		}
		return $res;
	}
	
	/**
	 * 获取用户团队路径
	 * @param int $uid:用户ID
	 * @return string
	 */
	public function getPath($uid)
	{
		$path=$this->getPathArray($uid,array());
		krsort($path);
		$path_str=implode(',',$path);
		return $path_str;
	}
	
	public function getPathArray($uid,$path=array())
	{
		$User=new \Common\Model\UserModel();
		$UserMsg=$User->getUserMsg($uid);
		//获取团队关系
		$path[]=$uid;
		if($UserMsg['referrer_id'])
		{
			$path=self::getPathArray($UserMsg['referrer_id'],$path);
		}
		return $path;
	}
	
	/**
	 * 获取用户可提现余额
	 * @param int $uid:用户ID
	 * @return number
	 */
	public function getDrawBalance($uid)
	{
		$userMsg=$this->where("uid='$uid'")->find();
		//余额
		$balance=$userMsg['balance'];
		//本月淘宝客、拼多多、京东收入
		$UserBalanceRecord=new \Common\Model\UserBalanceRecordModel();
		$amount_month=$UserBalanceRecord->where("user_id='$uid' and status='2' and date_format(pay_time,'%Y-%m')=date_format(now(),'%Y-%m') and action in ('tbk','tbk_r','tbk_r2','tbk_rt','jd','jd_r','jd_r2','jd_rt','pdd','pdd_r','pdd_r2','pdd_rt')")->sum('money');
		$amount_month=$amount_month/100;
		//可提现金额
		$draw_balance=$balance-$amount_month;
		//保留2位小数，四舍五不入
		$draw_balance=substr(sprintf("%.3f",$draw_balance),0,-1);
	
		//本月结算佣金
		$amount_finish=$amount_month;
		//保留2位小数，四舍五不入
		$amount_finish=substr(sprintf("%.3f",$amount_finish),0,-1);
	
		//根据用户会员组用户比例做相应扣除
		$sql="select g.fee_user from __PREFIX__user u,__PREFIX__user_group g where u.uid='$uid' and u.group_id=g.id";
		$res_g=M()->query($sql);
		$fee_user=$res_g[0]['fee_user']/100;
		//本月预估佣金
		//本月预估-淘宝客
		$TbOrder=new \Common\Model\TbOrderModel();
		$tbk_amount_current=$TbOrder->where("user_id='$uid' and tk_status!='13' and date_format(create_time,'%Y-%m')=date_format(now(),'%Y-%m')")->sum('pub_share_pre_fee');
		$tbk_amount_current*=$fee_user;
		//本月预估-拼多多
		$PddOrder=new \Common\Model\PddOrderModel();
		$pdd_amount_current=$PddOrder->where("user_id='$uid' and order_status in (0,1,2,3,5) and date_format(order_create_time,'%Y-%m')=date_format(now(),'%Y-%m')")->sum('promotion_amount');
		$pdd_amount_current=$pdd_amount_current*$fee_user/100;
		//本月预估-京东
		$JingdongOrderDetail=new \Common\Model\JingdongOrderDetailModel();
		$jd_amount_current=$JingdongOrderDetail->where("user_id='$uid' and validCode in (16,17,18) and date_format(orderTime,'%Y-%m')=date_format(now(),'%Y-%m')")->sum('estimateCosPrice');
		$jd_amount_current=$jd_amount_current*$fee_user;
		//保留2位小数，四舍五不入
		$amount_current=$tbk_amount_current+$pdd_amount_current+$jd_amount_current;
		//保留2位小数，四舍五不入
		$amount_current=substr(sprintf("%.3f",$amount_current),0,-1);
	
		return array(
				'draw_balance'=>$draw_balance,
				'amount_finish'=>$amount_finish,
				'amount_current'=>$amount_current,
		);
	}
	
	/**
	 * 处理淘宝、拼多多、京东订单推荐人佣金
	 * @param string $order_id 订单号
	 * @param float $money 订单总佣金
	 * @param float $money_user 用户获取佣金
	 * @param int $type 订单类型 1淘宝 2京东 3拼多多
	 * @param array $userMsg 购买用户信息
	 * @return boolean
	 */
	public function treatCommission($order_id,$money,$type,array $userMsg,$money_user)
	{
	    //极光推送消息
	    Vendor('jpush.jpush','','.class.php');
	    $jpush=new \jpush();
	    $alias=$userMsg['uid'];//推送别名
	    $title='收入通知';
	    $content='您有一笔'.$money_user.'元收入，请查收！';
	    $key='banlance';
	    switch ($type){
	        case 1:
	            $value_u='taobao';
	            break;
	        case 2:
	            $value_u='jingdong';
	            break;
	        case 3:
	            $value_u='pdd';
	            break;
	        default:
	            $value_u='';
	            break;
	    }
	    $res_push=$jpush->push($alias,$title,$content,'','','',$key,$value_u);
	    
	    $UserBalanceRecord=new \Common\Model\UserBalanceRecordModel();
	    //给直接推荐、间接推荐人返利
	    //并且购买用户是普通会员
	    if($userMsg['referrer_id'] and ($userMsg['group_id']=='1' or $userMsg['group_id']=='2'))
	    {
	        //用户的直接、间接推荐人可得到返利
	        //存在直接推荐人
	        $referrer_id=$userMsg['referrer_id'];
	        $referrerMsg=$this->getUserMsg($referrer_id);
	        //不同会员组，直接推荐佣金不同
	        if($referrerMsg['group_id']>2){
	            //团队中精英熊、汇客熊会员数
	            $is_vip=1;
	        }else {
	            //团队中精英熊、汇客熊会员数
	            $is_vip=0;
	        }
	        $UserGroup=new \Common\Model\UserGroupModel();
	        $rgMsg=$UserGroup->getGroupMsg($referrerMsg['group_id']);
	        $rate1=$rgMsg['referrer_rate'];//直接推荐返利比例-百分比
	        if(empty($rate1)){
                $rate1 = REFERRER_RATE;
            }
	        $referrer_money=$money*$rate1/100;
	        //四舍五入，保留2位
	        $referrer_money=round($referrer_money,2);
	        if($referrer_money>0) {
	            //增加直接推荐人用户余额
	            $res_balance_r=$this->where("uid='$referrer_id'")->setInc('balance',$referrer_money);
	            //保存余额变动记录
	            $all_money_r=$referrerMsg['balance']+$referrer_money;
	            switch ($type){
	                case 1:
	                    $action_r='tbk_r';
	                    break;
	                case 2:
	                    $action_r='jd_r';
	                    break;
	                case 3:
	                    $action_r='pdd_r';
	                    break;
	                default:
	                    $action_r='';
	                    break;
	            }
	            $res_record_r=$UserBalanceRecord->addLog($referrer_id, $referrer_money, $all_money_r, $action_r,'2',$order_id,$type);
	        }else {
	            $res_balance_r=true;
	            $res_record_r=true;
	        }
	        
	        if($res_balance_r!==false and $res_record_r!==false ) {
	            if($referrer_money>0) {
	                $alias=$referrer_id;//推送别名
	                $title='收入通知';
	                $content='您有一笔'.$referrer_money.'元收入，请查收！';
	                $key='banlance';
	                switch ($type){
	                    case 1:
	                        $value='taobao1';
	                        break;
	                    case 2:
	                        $value='jingdong1';
	                        break;
	                    case 3:
	                        $value='pdd1';
	                        break;
	                    default:
	                        $value='';
	                        break;
	                }
	                $res_push=$jpush->push($alias,$title,$content,'','','',$key,$value);
	            }
	            
	            //给间接推荐人返利
	            if($referrerMsg['referrer_id']) {
	                //存在间接推荐人
	                $referrer_id2=$referrerMsg['referrer_id'];
	                $referrerMsg2=$this->getUserMsg($referrer_id2);
	                //不同会员组，间接推荐佣金不同
	                if($referrerMsg2['group_id']>2){
	                    //团队中精英熊、汇客熊会员数
	                    $is_vip+=1;
	                }
	                $rgMsg2=$UserGroup->getGroupMsg($referrerMsg2['group_id']);
	                $rate2=$rgMsg2['referrer_rate2'];//间接推荐返利比例-百分比
	                
			        if(empty($rate2)){
		                $rate2 = REFERRER_RATE2;
		            }	                
	                $referrer_money2=$money*$rate2/100;
	                //四舍五入，保留2位
	                $referrer_money2=round($referrer_money2,2);
	                if($referrer_money2>0) {
	                    //增加间接推荐人用户余额
	                    $res_balance_r2=$this->where("uid='$referrer_id2'")->setInc('balance',$referrer_money2);
	                    //保存余额变动记录
	                    $all_money_r2=$referrerMsg2['balance']+$referrer_money2;
	                    switch ($type){
	                        case 1:
	                            $action_r2='tbk_r2';
	                            break;
	                        case 2:
	                            $action_r2='jd_r2';
	                            break;
	                        case 3:
	                            $action_r2='pdd_r2';
	                            break;
	                        default:
	                            $action_r2='';
	                            break;
	                    }
	                    $res_record_r2=$UserBalanceRecord->addLog($referrer_id2, $referrer_money2, $all_money_r2, $action_r2,'2',$order_id,$type);
	                }else {
	                    $res_balance_r2=true;
	                    $res_record_r2=true;
	                }
	                
	                if($res_balance_r2!==false and $res_record_r2!==false) {
	                    if($referrer_money2>0) {
	                        $alias=$referrer_id2;//推送别名
	                        $title='收入通知';
	                        $content='您有一笔'.$referrer_money2.'元收入，请查收！';
	                        $key='banlance';
	                        switch ($type){
	                            case 1:
	                                $value2='taobao2';
	                                break;
	                            case 2:
	                                $value2='jingdong2';
	                                break;
	                            case 3:
	                                $value2='pdd2';
	                                break;
	                            default:
	                                $value2='';
	                                break;
	                        }
	                        $res_push=$jpush->push($alias,$title,$content,'','','',$key,$value2);
	                    }
	                    
	                    //往上找无限极，给与团队奖励
	                    if($is_vip<2) {
	                        //团队路径
	                        $path=$userMsg['path'];
	                        $teamList=$this->where("uid in ($path)")->field('uid,group_id,balance')->order('uid desc')->select();
	                        //删除团队中的自己、一级、二级推荐人，从第四个开始计算
	                        $team_num=count($teamList);
	                        for($i=3;$i<$team_num;$i++) {
	                            if($is_vip<2) {
	                                $referrer_tgid=$teamList[$i]['group_id'];
	                                if($referrer_tgid=='3' or $referrer_tgid=='4') {
	                                    //精英熊、汇客熊可以拿
	                                    $referrer_tid=$teamList[$i]['uid'];
	                                    //团队佣金
	                                    if($is_vip==0) {
	                                        if($referrer_tgid=='3') {
	                                            //团队佣金=总佣金10%-精英熊
	                                            $referrer_money_team=$money*0.1;
	                                        }else {
	                                            //团队佣金=总佣金12%-汇客熊
	                                            $referrer_money_team=$money*0.12;
	                                        }
	                                    }else {
	                                        //团队佣金=总佣金5%
	                                        $referrer_money_team=$money*0.05;
	                                    }
	                                    //四舍五入，保留2位
	                                    $referrer_money_team=round($referrer_money_team,2);
	                                    if($referrer_money_team>0) {
	                                        //增加团队推荐人用户余额
	                                        $res_balance_rt=$this->where("uid='$referrer_tid'")->setInc('balance',$referrer_money_team);
	                                        //保存余额变动记录
	                                        $all_money_rt=$teamList[$i]['balance']+$referrer_money_team;
	                                        switch ($type){
	                                            case 1:
	                                                $action_rt='tbk_rt';
	                                                break;
	                                            case 2:
	                                                $action_rt='jd_rt';
	                                                break;
	                                            case 3:
	                                                $action_rt='pdd_rt';
	                                                break;
	                                            default:
	                                                $action_rt='';
	                                                break;
	                                        }
	                                        $res_record_rt=$UserBalanceRecord->addLog($referrer_tid, $referrer_money_team, $all_money_rt, $action_rt,'2',$order_id,$type);
	                                        if($res_balance_rt!==false and $res_record_rt!==false) {
	                                            //团队精英熊、汇客熊人数+1
	                                            $is_vip+=1;
	                                            
	                                            $alias=$referrer_tid;//推送别名
	                                            $title='收入通知';
	                                            $content='您有一笔'.$referrer_money_team.'元收入，请查收！';
	                                            $key='banlance';
	                                            switch ($type){
	                                                case 1:
	                                                    $value_t='taobaot';
	                                                    break;
	                                                case 2:
	                                                    $value_t='jingdongt';
	                                                    break;
	                                                case 3:
	                                                    $value_t='pddt';
	                                                    break;
	                                                default:
	                                                    $value_t='';
	                                                    break;
	                                            }
	                                            $res_push=$jpush->push($alias,$title,$content,'','','',$key,$value_t);
	                                            
	                                            continue;
	                                        }else {
	                                            //回滚
	                                            $this->rollback();
	                                            return false;
	                                        }
	                                    }
	                                }else {
	                                    continue;
	                                }
	                            }else {
	                                //团队奖励已完成，跳出循环
	                                break;
	                            }
	                        }
	                        //提交事务
	                        $this->commit();
	                        return true;
	                    }else {
	                        //提交事务
	                        $this->commit();
	                        return true;
	                    }
	                }else {
	                    //回滚
	                    $this->rollback();
	                    return false;
	                }
	            }else {
	                //不存在间接推荐人
	                //提交事务
	                $this->commit();
	                return true;
	            }
	        }else {
	            //回滚
	            $this->rollback();
	            return false;
	        }
	    }else {
	        //不存在直接推荐人
	        return true;
	    }
	}
	
	/**
	 * 处理淘宝、拼多多、京东订单推荐人佣金-预估
	 * @param string $order_id 订单号
	 * @param float $money 订单总佣金
	 * @param int $type 订单类型 1淘宝 2京东 3拼多多
	 * @param int $uid 购买用户ID
	 * @param date $create_time 订单时间
	 * @param string $goods_name 商品名称
	 * @return boolean
	 */
	public function treatCommissionTmp($order_id,$money,$type,$uid,$create_time,$goods_name='')
	{
	    //给购买会员返利
	    $userMsg=$this->getUserMsg($uid);
	    //根据用户所在的组获取相应收益比例
	    $UserGroup=new \Common\Model\UserGroupModel();
	    $groupMsg=$UserGroup->getGroupMsg($userMsg['group_id']);
	    if($groupMsg and $userMsg) {
	        //佣金-客户
	        $money_user=$money*$groupMsg['fee_user']/100;
	        //四舍五入
	        $money_user=round($money_user, 2);
	        
	        //开启事务
	        $UserBalanceRecordTmp=new \Common\Model\UserBalanceRecordTmpModel();
	        $UserBalanceRecordTmp->startTrans();
	        //保存余额变动记录-预估
	        switch ($type){
	            case 1:
	                $action='tbk';
	                $jg_value='taobao';
	                $jg_o_name='淘宝';
	                break;
	            case 2:
	                $action='jd';
	                $jg_value='jingdong';
	                $jg_o_name='京东';
	                break;
	            case 3:
	                $action='pdd';
	                $jg_value='pdd';
	                $jg_o_name='拼多多';
	                break;
	            default:
	                $action='';
	                break;
	        }
	        $res_record=$UserBalanceRecordTmp->addLog($uid,$money_user,$action,$order_id,$type,$create_time);
	        if($res_record!==false) {
	            Vendor('jpush.jpush','','.class.php');
	            $jpush=new \jpush();
	            //极光推送消息
	            $alias=$uid;//推送别名
	            $title=APP_NAME.'通知您有新订单';
	            $content='您有一笔新订单：'.$goods_name;
	            $key='order';
	            $res_push=$jpush->push($alias,$title,$content,'','','',$key,$jg_value);
	            
	            //给直接推荐、间接推荐人返利
	            //并且购买用户是普通会员
	            if($userMsg['referrer_id'] and ($userMsg['group_id']=='1' or $userMsg['group_id']=='2'))
	            {
	                //存在直接推荐人
	                $referrer_id=$userMsg['referrer_id'];
	                $referrerMsg=$this->getUserMsg($referrer_id);
	                //不同会员组，直接推荐佣金不同
	                if($referrerMsg['group_id']>2){
	                    //团队中精英熊、汇客熊会员数
	                    $is_vip=1;
	                }else {
	                    //团队中精英熊、汇客熊会员数
	                    $is_vip=0;
	                }
	                $UserGroup=new \Common\Model\UserGroupModel();
	                $rgMsg=$UserGroup->getGroupMsg($referrerMsg['group_id']);
	                $rate1=$rgMsg['referrer_rate'];//直接推荐返利比例-百分比
	                $referrer_money=$money*$rate1/100;
	                //四舍五入，保留2位
	                $referrer_money=round($referrer_money,2);
	                if($referrer_money>0){
	                    //保存余额变动记录
	                    switch ($type){
	                        case 1:
	                            $action_r='tbk_r';
	                            $jg_value1='taobao1';
	                            break;
	                        case 2:
	                            $action_r='jd_r';
	                            $jg_value1='jingdong1';
	                            break;
	                        case 3:
	                            $action_r='pdd_r';
	                            $jg_value1='pdd1';
	                            break;
	                        default:
	                            $action_r='';
	                            break;
	                    }
	                    $res_record_r=$UserBalanceRecordTmp->addLog($referrer_id,$referrer_money,$action_r,$order_id,$type,$create_time);
	                    
	                    //给直接推荐人推送
	                    $alias=$referrer_id;//推送别名
	                    $title=APP_NAME.'通知您有新订单';
	                    $content='您有一笔'.$jg_o_name.'一级订单：'.$goods_name;
	                    $res_push=$jpush->push($alias,$title,$content,'','','',$key,$jg_value1);
	                }else {
	                    $res_record_r=true;
	                }
	                if($res_record_r!==false) {
	                    //给间接推荐人返利
	                    if($referrerMsg['referrer_id']) {
	                        //存在间接推荐人
	                        $referrer_id2=$referrerMsg['referrer_id'];
	                        $referrerMsg2=$this->getUserMsg($referrer_id2);
	                        //不同会员组，间接推荐佣金不同
	                        if($referrerMsg2['group_id']>2){
	                            //团队中精英熊、汇客熊会员数
	                            $is_vip+=1;
	                        }
	                        $rgMsg2=$UserGroup->getGroupMsg($referrerMsg2['group_id']);
	                        $rate2=$rgMsg2['referrer_rate2'];//间接推荐返利比例-百分比
	                        $referrer_money2=$money*$rate2/100;
	                        //四舍五入，保留2位
	                        $referrer_money2=round($referrer_money2,2);
	                        if($referrer_money2>0) {
	                            //四舍五入，保留2位
	                            $referrer_money2=round($referrer_money2,2);
	                            //保存余额变动记录
	                            switch ($type){
	                                case 1:
	                                    $action_r2='tbk_r2';
	                                    $jg_value2='taobao2';
	                                    break;
	                                case 2:
	                                    $action_r2='jd_r2';
	                                    $jg_value2='jingdong2';
	                                    break;
	                                case 3:
	                                    $action_r2='pdd_r2';
	                                    $jg_value2='pdd2';
	                                    break;
	                                default:
	                                    $action_r2='';
	                                    break;
	                            }
	                            $res_record_r2=$UserBalanceRecordTmp->addLog($referrer_id2,$referrer_money2,$action_r2,$order_id,$type,$create_time);
	                            
	                            //给间接推荐人推送
	                            $alias=$referrer_id2;//推送别名
	                            $title=APP_NAME.'通知您有新订单';
	                            $content='您有一笔'.$jg_o_name.'二级订单：'.$goods_name;
	                            $res_push=$jpush->push($alias,$title,$content,'','','',$key,$jg_value2);
	                        }else {
	                            $res_record_r2=true;
	                        }
	                        if($res_record_r2!==false) {
	                            //往上找无限极，给与团队奖励
	                            if($is_vip<2) {
	                                //团队路径
	                                $path=$userMsg['path'];
	                                $teamList=$this->where("uid in ($path)")->field('uid,group_id,balance')->order('uid desc')->select();
	                                //删除团队中的自己、一级、二级推荐人，从第四个开始计算
	                                $team_num=count($teamList);
	                                for($i=3;$i<$team_num;$i++) {
	                                    if($is_vip<2) {
	                                        $referrer_tgid=$teamList[$i]['group_id'];
	                                        if($referrer_tgid=='3' or $referrer_tgid=='4') {
	                                            //精英熊、汇客熊可以拿
	                                            $referrer_tid=$teamList[$i]['uid'];
	                                            //团队佣金
	                                            if($is_vip==0) {
	                                                if($referrer_tgid=='3') {
	                                                    //团队佣金=总佣金10%-精英熊
	                                                    $referrer_money_team=$money*0.1;
	                                                }else {
	                                                    //团队佣金=总佣金12%-汇客熊
	                                                    $referrer_money_team=$money*0.12;
	                                                }
	                                            }else {
	                                                //团队佣金=总佣金5%
	                                                $referrer_money_team=$money*0.05;
	                                            }
	                                            //四舍五入，保留2位
	                                            $referrer_money_team=round($referrer_money_team,2);
	                                            if($referrer_money_team>0) {
	                                                //保存余额变动记录
	                                                switch ($type){
	                                                    case 1:
	                                                        $action_rt='tbk_rt';
	                                                        break;
	                                                    case 2:
	                                                        $action_rt='jd_rt';
	                                                        break;
	                                                    case 3:
	                                                        $action_rt='pdd_rt';
	                                                        break;
	                                                    default:
	                                                        $action_rt='';
	                                                        break;
	                                                }
	                                                $res_record_rt=$UserBalanceRecordTmp->addLog($referrer_tid,$referrer_money_team,$action_rt,$order_id,$type,$create_time);
	                                                if($res_record_rt!==false) {
	                                                    //团队精英熊、汇客熊人数+1
	                                                    $is_vip+=1;
	                                                    continue;
	                                                }else {
	                                                    //回滚
	                                                    $UserBalanceRecordTmp->rollback();
	                                                    return false;
	                                                }
	                                            }
	                                        }else {
	                                            continue;
	                                        }
	                                    }else {
	                                        //团队奖励已完成，跳出循环
	                                        break;
	                                    }
	                                }
	                                //提交事务
	                                $UserBalanceRecordTmp->commit();
	                                return true;
	                            }else {
	                                //提交事务
	                                $UserBalanceRecordTmp->commit();
	                                return true;
	                            }
	                        }else {
	                            //回滚
	                            $UserBalanceRecordTmp->rollback();
	                            return false;
	                        }
	                    }else {
	                        //不存在间接推荐人
	                        //提交事务
	                        $UserBalanceRecordTmp->commit();
	                        return true;
	                    }
	                }else {
	                    //回滚
	                    $UserBalanceRecordTmp->rollback();
	                    return false;
	                }
	            }else {
	                //不存在直接推荐人
	                //提交事务
	                $UserBalanceRecordTmp->commit();
	                return true;
	            }
	        }else {
	            //回滚
	            $UserBalanceRecordTmp->rollback();
	            return false;
	        }
	    }else {
	        return false;
	    }
	}
}
?>