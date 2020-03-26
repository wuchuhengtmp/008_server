<?php
/**
 * 银行账号管理接口
 */
namespace App\Controller;
use App\Common\Controller\AuthController;

class BankAccountController extends AuthController
{
	/**
	 * 获取银行卡列表
	 * @param string $token:用户身份令牌
	 * @return array
	 * @return @param code:返回码
	 * @return @param msg:返回码说明
	 * @return @param data:返回数据
	 * @return @param data->list:银行卡列表
	 */
	public function getAccountList()
	{
		if( trim(I('post.token')) )
		{
		    //判断用户身份
		    $token=trim(I('post.token'));
		    $User=new \Common\Model\UserModel();
		    $res_token=$User->checkToken($token);
		    if($res_token['code']!=0) {
		        //用户身份不合法
		        $res=$res_token;
		    }else {
		        $uid=$res_token['uid'];
				//获取商户银行账号列表
				$sql="select ba.*,b.bank_name,b.icon from __PREFIX__bank b,__PREFIX__bank_account ba where ba.user_id='$uid' and ba.bank_id=b.bank_id order by ba.id desc";
				$list=M()->query($sql);
				if($list!==false) {
					$data=array(
							'list'=>$list
					);
					$res=array(
							'code'=>$this->ERROR_CODE_COMMON['SUCCESS'],
							'msg'=>'成功',
							'data'=>$data
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
			//参数不正确，参数缺失
			$res=array(
					'code'=>$this->ERROR_CODE_COMMON['PARAMETER_ERROR'],
					'msg'=>$this->ERROR_CODE_COMMON_ZH[$this->ERROR_CODE_COMMON['PARAMETER_ERROR']]
			);
		}
		echo json_encode ($res,JSON_UNESCAPED_UNICODE);
	}
	
	/**
	 * 获取银行卡信息
	 * @param string $token:用户身份令牌
	 * @param int $id:银行卡ID
	 * @return array
	 * @return @param code:返回码
	 * @return @param msg:返回码说明
	 * @return @param data:返回数据
	 * @return @param data->accountMsg:银行卡信息
	 */
	public function getAccountMsg()
	{
		if( trim(I('post.token')) and trim(I('post.id')) ) {
		    //判断用户身份
		    $token=trim(I('post.token'));
		    $User=new \Common\Model\UserModel();
		    $res_token=$User->checkToken($token);
		    if($res_token['code']!=0) {
		        //用户身份不合法
		        $res=$res_token;
		    }else {
		        $uid=$res_token['uid'];
				$id=trim(I('post.id'));
				$BankAccount=new \Common\Model\BankAccountModel();
				$accountMsg=$BankAccount->getAccountDetail($id);
				if($accountMsg!==false) {
					$data=array(
							'accountMsg'=>$accountMsg
					);
					$res=array(
							'code'=>$this->ERROR_CODE_COMMON['SUCCESS'],
							'msg'=>'成功',
							'data'=>$data
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
			//参数不正确，参数缺失
			$res=array(
					'code'=>$this->ERROR_CODE_COMMON['PARAMETER_ERROR'],
					'msg'=>$this->ERROR_CODE_COMMON_ZH[$this->ERROR_CODE_COMMON['PARAMETER_ERROR']]
			);
		}
		echo json_encode ($res,JSON_UNESCAPED_UNICODE);
	}
	
	/**
	 * 添加银行卡
	 * @param string $token:用户身份令牌
	 * @param int $bank_id:银行ID
	 * @param string $account:账号
	 * @param string $truename:真实姓名
	 * @return array
	 * @return @param code:返回码
	 * @return @param msg:返回码说明
	 */
	public function add()
	{
		if(trim(I('post.token')) and trim(I('post.bank_id')) and trim(I('post.account')) and trim(I('post.truename')) ){
		    //判断用户身份
		    $token=trim(I('post.token'));
		    $User=new \Common\Model\UserModel();
		    $res_token=$User->checkToken($token);
		    if($res_token['code']!=0) {
		        //用户身份不合法
		        $res=$res_token;
		    }else {
		        $uid=$res_token['uid'];
				$bank_id=trim(I('post.bank_id'));
				$account=trim(I('post.account'));
				$truename=trim(I('post.truename'));
				//判断该银行卡是否存在
				$BankAccount=new \Common\Model\BankAccountModel();
				$res_exist=$BankAccount->where("bank_id='$bank_id' and account='$account' and user_id='$uid'")->find();
				if($res_exist)
				{
					//该银行卡已经存在
					$res=array(
							'code'=>$this->ERROR_CODE_USER['BANK_ACCOUNT_ALREADY_EXISTS'],
							'msg'=>$this->ERROR_CODE_USER_ZH[$this->ERROR_CODE_MERCHANT['BANK_ACCOUNT_ALREADY_EXISTS']]
					);
				}else {
					$data=array(
							'user_id'=>$uid,
							'bank_id'=>$bank_id,
							'account'=>$account,
							'truename'=>$truename,
					);
					if(!$BankAccount->create($data)) {
						//验证不通过
					    $this->error($BankAccount->getError());
					}else {
						//验证通过
					    $res=$BankAccount->add($data);
						if($res!==false) {
							$res=array(
									'code'=>$this->ERROR_CODE_COMMON['SUCCESS'],
									'msg'=>'成功'
							);
						}else {
							//数据库错误
							$res=array(
									'code'=>$this->ERROR_CODE_COMMON['DB_ERROR'],
									'msg'=>$this->ERROR_CODE_COMMON_ZH[$this->ERROR_CODE_COMMON['DB_ERROR']]
							);
						}
					}
				}
			}
		}else {
			//参数不正确，参数缺失
			$res=array(
					'code'=>$this->ERROR_CODE_COMMON['PARAMETER_ERROR'],
					'msg'=>$this->ERROR_CODE_COMMON_ZH[$this->ERROR_CODE_COMMON['PARAMETER_ERROR']]
			);
		}
		echo json_encode ($res,JSON_UNESCAPED_UNICODE);
	}
	
	/**
	 * 编辑银行卡
	 * @param string $token:用户身份令牌
	 * @param int $id:银行卡ID
	 * @param int $bank_id:银行ID
	 * @param string $account:账号
	 * @param string $truename:真实姓名
	 * @return array
	 * @return @param code:返回码
	 * @return @param msg:返回码说明
	 */
	public function edit()
	{
		if(trim(I('post.token')) and trim(I('post.id')) and trim(I('post.bank_id')) and trim(I('post.account')) and trim(I('post.truename')))
		{
		    //判断用户身份
		    $token=trim(I('post.token'));
		    $User=new \Common\Model\UserModel();
		    $res_token=$User->checkToken($token);
		    if($res_token['code']!=0) {
		        //用户身份不合法
		        $res=$res_token;
		    }else {
		        $uid=$res_token['uid'];
				$id=trim(I('post.id'));
				$bank_id=trim(I('post.bank_id'));
				$account=trim(I('post.account'));
				$truename=trim(I('post.truename'));
				//判断该银行卡是否存在
				$BankAccount=new \Common\Model\BankAccountModel();
				$res_exist=$BankAccount->where("bank_id='$bank_id' and account='$account' and user_id='$uid' and id!=$id")->find();
				if($res_exist) {
					//该银行卡已经存在
					$res=array(
					    'code'=>$this->ERROR_CODE_USER['BANK_ACCOUNT_ALREADY_EXISTS'],
					    'msg'=>$this->ERROR_CODE_USER_ZH[$this->ERROR_CODE_MERCHANT['BANK_ACCOUNT_ALREADY_EXISTS']]
					);
				}else {
					$data=array(
							'bank_id'=>$bank_id,
							'account'=>$account,
							'truename'=>$truename,
					);
					if(!$BankAccount->create($data)) {
						//验证不通过
					    $this->error($BankAccount->getError());
					}else {
						//验证通过
					    $res=$BankAccount->where("id='$id'")->save($data);
						if($res!==false)
						{
							$res=array(
									'code'=>$this->ERROR_CODE_COMMON['SUCCESS'],
									'msg'=>'成功'
							);
						}else {
							//数据库错误
							$res=array(
									'code'=>$this->ERROR_CODE_COMMON['DB_ERROR'],
									'msg'=>$this->ERROR_CODE_COMMON_ZH[$this->ERROR_CODE_COMMON['DB_ERROR']]
							);
						}
					}
				}
			}
		}else {
			//参数不正确，参数缺失
			$res=array(
					'code'=>$this->ERROR_CODE_COMMON['PARAMETER_ERROR'],
					'msg'=>$this->ERROR_CODE_COMMON_ZH[$this->ERROR_CODE_COMMON['PARAMETER_ERROR']]
			);
		}
		echo json_encode ($res,JSON_UNESCAPED_UNICODE);
	}
	
	/**
	 * 删除银行卡
	 * @param string $token:用户身份令牌
	 * @param int $id:银行卡ID
	 * @return array
	 * @return @param code:返回码
	 * @return @param msg:返回码说明
	 */
	public function del()
	{
		if( trim(I('post.token')) and trim(I('post.id')) ) {
		    //判断用户身份
		    $token=trim(I('post.token'));
		    $User=new \Common\Model\UserModel();
		    $res_token=$User->checkToken($token);
		    if($res_token['code']!=0) {
		        //用户身份不合法
		        $res=$res_token;
		    }else {
		        $uid=$res_token['uid'];
				$id=trim(I('post.id'));
				$BankAccount=new \Common\Model\BankAccountModel();
				$res_del=$BankAccount->where("id='$id'")->delete();
				if($res_del!==false) {
					$res=array(
							'code'=>$this->ERROR_CODE_COMMON['SUCCESS'],
							'msg'=>'成功',
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
			//参数不正确，参数缺失
			$res=array(
					'code'=>$this->ERROR_CODE_COMMON['PARAMETER_ERROR'],
					'msg'=>$this->ERROR_CODE_COMMON_ZH[$this->ERROR_CODE_COMMON['PARAMETER_ERROR']]
			);
		}
		echo json_encode ($res,JSON_UNESCAPED_UNICODE);
	}
}
?>