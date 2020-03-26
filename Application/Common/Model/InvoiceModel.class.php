<?php
/**
 * 发票信息管理类
 */
namespace Common\Model;
use Think\Model;

class InvoiceModel extends Model
{
	//验证规则
	protected $_validate =array(
			array('user_id','require','用户ID不能为空！',self::EXISTS_VALIDATE),  //存在验证，必填
			array('user_id','is_positive_int','用户不存在',self::EXISTS_VALIDATE,'function'),  //存在验证，必须是正整数
			array('purchaser','require','发票抬头不能为空！',self::EXISTS_VALIDATE),  //存在验证，必填
			array('purchaser','1,30','发票抬头不超过30个字符！',self::EXISTS_VALIDATE,'length'),  //存在验证，不超过30个字符
			array('taxpayer_id','1,40','纳税人识别号不超过40个字符！',self::VALUE_VALIDATE,'length'),  //值不为空的时候验证，不超过40个字符
			array('bank','1,30','开户行不超过30个字符！',self::VALUE_VALIDATE,'length'),  //值不为空的时候验证，不超过30个字符
			array('account','1,30','账号不超过30个字符！',self::VALUE_VALIDATE,'length'),  //值不为空的时候验证，不超过30个字符
			array('contact','1,20','联系电话不超过20个字符！',self::VALUE_VALIDATE,'length'),  //值不为空的时候验证，不超过20个字符
			array('address','1,100','地址不超过100个字符！',self::VALUE_VALIDATE,'length'),  //值不为空的时候验证，不超过100个字符
			array('linkman','1,20','收件人不超过20个字符！',self::VALUE_VALIDATE,'length'),  //值不为空的时候验证，不超过20个字符
			array('is_default','require','请选择是否为默认发票！',self::EXISTS_VALIDATE),  //存在验证，必填
			array('is_default',array('Y','N'),'请选择是否为默认发票！',self::EXISTS_VALIDATE,'in'),  //存在验证，只能是Y是 N否
			array('type',array('1','2'),'发票类型不正确！',self::EXISTS_VALIDATE,'in'),  //存在验证，只能是1公司 2个人
	);
	
	/**
	 * 获取用户发票列表
	 * @param int $user_id:会员ID
	 * @return array|boolean
	 */
	public function getInvoiceList($user_id)
	{
		$list=$this->where("user_id='$user_id'")->order('is_default desc')->select();
		if($list!==false)
		{
			$num=count($list);
			for ($i=0;$i<$num;$i++)
			{
				if($list[$i]['type']=='1')
				{
					$type_zh='企业';
				}else {
					$type_zh='个人';
				}
				$list[$i]['type_zh']=$type_zh;
			}
			return $list;
		}else {
			return false;
		}
	}
	
	/**
	 * 获取发票信息
	 * @param int $invoice_id:发票ID
	 * @return array|boolean
	 */
	public function getInvoiceMsg($invoice_id)
	{
		$msg=$this->where("invoice_id='$invoice_id'")->find();
		if($msg!==false)
		{
			//发票类型
			if($msg['type']=='1')
			{
				$type_zh='企业';
			}else {
				$type_zh='个人';
			}
			$msg['type_zh']=$type_zh;
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