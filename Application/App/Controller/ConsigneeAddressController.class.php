<?php
/**
 * 收货地址管理接口
 */
namespace App\Controller;
use App\Common\Controller\AuthController;

class ConsigneeAddressController extends AuthController
{
	/**
	 * 获取收货地址列表
	 * @param string $token:用户身份令牌
	 * @return array
	 * @return @param code:返回码
	 * @return @param msg:返回码说明
	 * @return @param data:返回数据
	 * @return @param data->list:收货地址列表
	 */
	public function getAddressList()
	{
		if(trim(I('post.token')))
		{
			//判断用户身份
			$token=trim(I('post.token'));
			$User=new \Common\Model\UserModel();
			$res_token=$User->checkToken($token);
			if($res_token['code']!=0)
			{
				//用户身份不合法
				$res=$res_token;
			}else {
				$uid=$res_token['uid'];
				$ConsigneeAddress=new \Common\Model\ConsigneeAddressModel();
				$list=$ConsigneeAddress->getList($uid);
				if($list!==false)
				{
					//获取地址列表成功
					$data=array(
							'list'=>$list
					);
					$res=array(
							'code'=>$this->ERROR_CODE_COMMON['SUCCESS'],
							'msg'=>'成功！',
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
	 * 获取收货地址信息
	 * @param string $address_id:地址ID
	 * @return array
	 * @return @param code:返回码
	 * @return @param msg:返回码说明
	 * @return @param data:返回数据
	 * @return @param data->addressMsg:收货地址信息
	 */
	public function getAddressMsg()
	{
		if(trim(I('post.address_id')))
		{
			$address_id=trim(I('post.address_id'));
			$ConsigneeAddress=new \Common\Model\ConsigneeAddressModel();
			$addressMsg=$ConsigneeAddress->getMsg($address_id);
			if($addressMsg!==false)
			{
				//获取地址信息成功
				$data=array(
						'addressMsg'=>$addressMsg
				);
				$res=array(
						'code'=>$this->ERROR_CODE_COMMON['SUCCESS'],
						'msg'=>'成功！',
						'data'=>$data
				);
			}else {
				//数据库错误
				$res=array(
						'code'=>$this->ERROR_CODE_COMMON['DB_ERROR'],
						'msg'=>$this->ERROR_CODE_COMMON_ZH[$this->ERROR_CODE_COMMON['DB_ERROR']]
				);
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
	 * 添加收货地址
	 * @param string $token:用户身份令牌
	 * @param string $consignee:收件人
	 * @param string $contact_number:联系电话
	 * @param string $province:省份
	 * @param string $city:城市
	 * @param string $county:县、区域
	 * @param string $detail_address:详细地址
	 * @param string $company:单位名称
	 * @param string $postcode:邮编
	 * @param string $is_default:是否为默认地址 Y是 N否
	 * @return array
	 * @return @param code:返回码
	 * @return @param msg:返回码说明
	 */
	public function addAddress()
	{
		if(trim(I('post.consignee')) and trim(I('post.contact_number')) and trim(I('post.province')) and trim(I('post.city')) and trim(I('post.county')) and trim(I('post.detail_address')) and trim(I('post.is_default')) and trim(I('post.token')))
		{
			//判断用户身份
			$token=trim(I('post.token'));
			$User=new \Common\Model\UserModel();
			$res_token=$User->checkToken($token);
			if($res_token['code']!=0)
			{
				//用户身份不合法
				$res=$res_token;
			}else {
				$uid=$res_token['uid'];
				//添加地址
				$data=array(
						'user_id'=>$uid,
						'province'=>trim(I('post.province')),
						'city'=>trim(I('post.city')),
						'county'=>trim(I('post.county')),
						'detail_address'=>trim(I('post.detail_address')),
						'company'=>trim(I('post.company')),
						'consignee'=>trim(I('post.consignee')),
						'contact_number'=>trim(I('post.contact_number')),
						'postcode'=>trim(I('post.postcode')),
						'is_default'=>trim(I('post.is_default'))
				);
				$ConsigneeAddress=new \Common\Model\ConsigneeAddressModel();
				if(!$ConsigneeAddress->create($data))
				{
					//验证不通过
					$res=array(
							'code'=>$this->ERROR_CODE_COMMON['PARAMETER_FORMAT_ERROR'],
							'msg'=>$ConsigneeAddress->getError()
					);
				}else {
					//验证通过
					//开启事务
					$ConsigneeAddress->startTrans();
					$res_add=$ConsigneeAddress->add($data);
					if($res_add!==false)
					{
						//将其他地址修改为非默认地址
						if(I('post.is_default')=='Y')
						{
							$id=$res_add;
							$data2=array(
									'is_default'=>'N'
							);
							$res2=$ConsigneeAddress->where("id!=$id and user_id='$uid'")->save($data2);
							if($res2!==false)
							{
								//提交事务
								$ConsigneeAddress->commit();
								$res=array(
										'code'=>$this->ERROR_CODE_COMMON['SUCCESS'],
										'msg'=>'添加收货地址成功！'
								);
							}else {
								//数据库错误
								//回滚
								$ConsigneeAddress->rollback();
								$res=array(
										'code'=>$this->ERROR_CODE_COMMON['DB_ERROR'],
										'msg'=>$this->ERROR_CODE_COMMON_ZH[$this->ERROR_CODE_COMMON['DB_ERROR']]
								);
							}
						}else {
							//提交事务
							$ConsigneeAddress->commit();
							$res=array(
									'code'=>$this->ERROR_CODE_COMMON['SUCCESS'],
									'msg'=>'添加收货地址成功！'
							);
						}
					}else {
						//数据库错误
						//回滚
						$ConsigneeAddress->rollback();
						$res=array(
								'code'=>$this->ERROR_CODE_COMMON['DB_ERROR'],
								'msg'=>$this->ERROR_CODE_COMMON_ZH[$this->ERROR_CODE_COMMON['DB_ERROR']]
						);
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
	 * 编辑收货地址
	 * @param string $token:用户身份令牌
	 * @param string $address_id:收货地址ID
	 * @param string $consignee:收件人
	 * @param string $contact_number:联系电话
	 * @param string $province:省份
	 * @param string $city:城市
	 * @param string $county:县、区域
	 * @param string $detail_address:详细地址
	 * @param string $company:单位名称
	 * @param string $postcode:邮编
	 * @param string $is_default:是否为默认地址 Y是 N否
	 * @return array
	 * @return @param code:返回码
	 * @return @param msg:返回码说明
	 */
	public function editAddress()
	{
		if(trim(I('post.address_id')) and trim(I('post.consignee')) and trim(I('post.contact_number')) and trim(I('post.province')) and trim(I('post.city')) and trim(I('post.county')) and trim(I('post.detail_address')) and trim(I('post.is_default')) and trim(I('post.token')))
		{
			//判断用户身份
			$token=trim(I('post.token'));
			$User=new \Common\Model\UserModel();
			$res_token=$User->checkToken($token);
			if($res_token['code']!=0)
			{
				//用户身份不合法
				$res=$res_token;
			}else {
				$uid=$res_token['uid'];
				
				$id=I('post.address_id');
				//编辑地址
				$data=array(
						'province'=>trim(I('post.province')),
						'city'=>trim(I('post.city')),
						'county'=>trim(I('post.county')),
						'detail_address'=>trim(I('post.detail_address')),
						'company'=>trim(I('post.company')),
						'consignee'=>trim(I('post.consignee')),
						'contact_number'=>trim(I('post.contact_number')),
						'postcode'=>trim(I('post.postcode')),
						'is_default'=>trim(I('post.is_default'))
				);
				$ConsigneeAddress=new \Common\Model\ConsigneeAddressModel();
				if(!$ConsigneeAddress->create($data))
				{
					//验证不通过
					$res=array(
							'code'=>$this->ERROR_CODE_COMMON['PARAMETER_FORMAT_ERROR'],
							'msg'=>$ConsigneeAddress->getError()
					);
				}else {
					//验证通过
					//开启事务
					$ConsigneeAddress->startTrans();
					$res_add=$ConsigneeAddress->where("id='$id'")->save($data);
					if($res_add!==false)
					{
						//将其他地址修改为非默认地址
						if(I('post.is_default')=='Y')
						{
							$data2=array(
									'is_default'=>'N'
							);
							$res2=$ConsigneeAddress->where("id!=$id and user_id='$uid'")->save($data2);
							if($res2!==false)
							{
								//提交事务
								$ConsigneeAddress->commit();
								$res=array(
										'code'=>$this->ERROR_CODE_COMMON['SUCCESS'],
										'msg'=>'编辑收货地址成功！'
								);
							}else {
								//数据库错误
								//回滚
								$ConsigneeAddress->rollback();
								$res=array(
										'code'=>$this->ERROR_CODE_COMMON['DB_ERROR'],
										'msg'=>$this->ERROR_CODE_COMMON_ZH[$this->ERROR_CODE_COMMON['DB_ERROR']]
								);
							}
						}else {
							//提交事务
							$ConsigneeAddress->commit();
							$res=array(
									'code'=>$this->ERROR_CODE_COMMON['SUCCESS'],
									'msg'=>'编辑收货地址成功！'
							);
						}
					}else {
						//数据库错误
						//回滚
						$ConsigneeAddress->rollback();
						$res=array(
								'code'=>$this->ERROR_CODE_COMMON['DB_ERROR'],
								'msg'=>$this->ERROR_CODE_COMMON_ZH[$this->ERROR_CODE_COMMON['DB_ERROR']]
						);
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
	 * 删除收货地址
	 * @param string $token:用户身份令牌
	 * @param int $address_id:收货地址ID
	 * @return array
	 * @return @param code:返回码
	 * @return @param msg:返回码说明
	 */
	public function delAddress()
	{
		if(trim(I('post.token')) and trim(I('post.address_id')))
		{
			//判断用户身份
			$token=trim(I('post.token'));
			$User=new \Common\Model\UserModel();
			$res_token=$User->checkToken($token);
			if($res_token['code']!=0)
			{
				//用户身份不合法
				$res=$res_token;
			}else {
				$uid=$res_token['uid'];
				$address_id=I('post.address_id');
				$ConsigneeAddress=new \Common\Model\ConsigneeAddressModel();
				$res=$ConsigneeAddress->where("id='$address_id'")->delete();
				if($res!==false)
				{
					$res=array(
							'code'=>$this->ERROR_CODE_COMMON['SUCCESS'],
							'msg'=>'删除收货地址成功！'
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
	 * 修改默认收货地址
	 * @param string $token:用户身份令牌
	 * @param int $address_id:收货地址ID
	 * @return array
	 * @return @param code:返回码
	 * @return @param msg:返回码说明
	 */
	public function changeDefault()
	{
		if(trim(I('post.token')) and trim(I('post.address_id')))
		{
			//判断用户身份
			$token=trim(I('post.token'));
			$User=new \Common\Model\UserModel();
			$res_token=$User->checkToken($token);
			if($res_token['code']!=0)
			{
				//用户身份不合法
				$res=$res_token;
			}else {
				$uid=$res_token['uid'];
				$address_id=trim(I('post.address_id'));
				$ConsigneeAddress=new \Common\Model\ConsigneeAddressModel();
				//开启事务
				$ConsigneeAddress->startTrans();
				//将用户所有收货地址修改为非默认-除该地址
				$data=array(
						'is_default'=>'N'
				);
				$res_c=$ConsigneeAddress->where("user_id='$uid' and id!='$address_id'")->save($data);
				if($res_c!==false)
				{
					//设置该地址为默认收货地址
					$data2=array(
							'is_default'=>'Y'
					);
					$res2=$ConsigneeAddress->where("user_id='$uid' and id='$address_id'")->save($data2);
					if($res2!==false)
					{
						//提交
						$ConsigneeAddress->commit();
						$res=array(
								'code'=>$this->ERROR_CODE_COMMON['SUCCESS'],
								'msg'=>'成功！'
						);
					}else {
						//回滚
						$ConsigneeAddress->rollback();
						//数据库错误
						$res=array(
								'code'=>$this->ERROR_CODE_COMMON['DB_ERROR'],
								'msg'=>$this->ERROR_CODE_COMMON_ZH[$this->ERROR_CODE_COMMON['DB_ERROR']]
						);
					}
				}else {
					//回滚
					$ConsigneeAddress->rollback();
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