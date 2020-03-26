<?php
/**
 * 收货地址管理类
 */
namespace Common\Model;
use Think\Model;

class ConsigneeAddressModel extends Model
{
	//验证规则
	protected $_validate =array(
			array('user_id','require','用户ID不能为空！',self::EXISTS_VALIDATE),  //存在验证，必填
			array('user_id','is_positive_int','用户不存在',self::EXISTS_VALIDATE,'function'),  //存在验证，必须是正整数
			array('province','1,30','省份名称不超过30个字符！',self::VALUE_VALIDATE,'length'),  //值不为空的时候验证，不超过30个字符
			array('city','1,30','城市名称不超过30个字符！',self::VALUE_VALIDATE,'length'),  //值不为空的时候验证，不超过30个字符
			array('county','1,30','县/区域名称不超过30个字符！',self::VALUE_VALIDATE,'length'),  //值不为空的时候验证，不超过30个字符
			array('detail_address','require','详细地址不能为空！',self::EXISTS_VALIDATE),  //存在验证，必填
			array('detail_address','1,100','详细地址不超过100个字符！',self::EXISTS_VALIDATE,'length'),  //存在验证，不超过100个字符
			array('company','1,100','单位名称不超过100个字符！',self::VALUE_VALIDATE,'length'),  //值不为空的时候验证，不超过100个字符
			array('consignee','require','收件人不能为空！',self::EXISTS_VALIDATE),  //存在验证，必填
			array('consignee','1,30','收件人不超过30个字符！',self::EXISTS_VALIDATE,'length'),  //存在验证，不超过30个字符
			array('contact_number','require','联系电话不能为空！',self::EXISTS_VALIDATE),  //存在验证，必填
			array('contact_number','1,30','联系电话不超过30个字符！',self::EXISTS_VALIDATE,'length'),  //存在验证，不超过30个字符
			array('postcode','1,10','邮政编码不超过10个字符！',self::VALUE_VALIDATE,'length'),  //值不为空的时候验证，不超过10个字符
			array('is_default','require','请选择是否为默认地址！',self::EXISTS_VALIDATE),  //存在验证，必填
			array('is_default',array('Y','N'),'请选择是否为默认地址！',self::EXISTS_VALIDATE,'in'),  //存在验证，只能是Y是 N否	
	);
	
	/**
	 * 获取收货地址列表
	 * @param int $userid:用户ID
	 * @return array|boolean
	 */
	public function getList($user_id)
	{
		$list=$this->where("user_id='$user_id'")->order('is_default desc')->select();
		if($list!==false)
		{
			return $list;
		}else {
			return false;
		}
	}
	
	/**
	 * 获取收货地址详情
	 * @param int $id:收货地址ID
	 * @return array|boolean
	 */
	public function getMsg($id)
	{
		$msg=$this->where("id='$id'")->find();
		if($msg!==false)
		{
			//所属用户
			$User=new \Common\Model\UserModel();
			$UserMsg=$User->getUserDetail($msg['user_id']);
			if($UserMsg!==false)
			{
				$msg['user_account']=$UserMsg['account'];
			}
			return $msg;
		}else {
			return false;
		}
	}
}
?>