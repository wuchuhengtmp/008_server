<?php
/**
 * 会员组管理
 */
namespace Common\Model;
use Think\Model;

class UserGroupModel extends Model
{
	//验证规则
	protected $_validate =array(
			array('title','require','会员组名不能为空！',self::EXISTS_VALIDATE),  //存在验证，必填
			array('title','1,20','会员组名不超过20个字符！',self::EXISTS_VALIDATE,'length'),  //存在验证，不超过20个字符
			array('exp','is_natural_num','等级必要经验必须为不小于零的整数！',self::VALUE_VALIDATE,'function'),  //值不为空的时候验证 ，必须是自然数
			array('discount','currency','折扣格式不正确！',self::VALUE_VALIDATE),  //值不为空的时候验证 ，必须是货币格式		
			array('introduce','1,300','会员组简介不超过300个字符！',self::VALUE_VALIDATE,'length'),  //值不为空的时候验证 ，不超过300个字符
			array('is_freeze',array('Y','N'),'请选择是否冻结！',self::EXISTS_VALIDATE,'in'),  //存在验证，只能是Y是 N否
			array('createtime','require','创建时间不能为空！',self::EXISTS_VALIDATE),  //存在验证，必填
			array('createtime','is_datetime','创建时间格式不正确！',self::EXISTS_VALIDATE,'function'),  //存在验证，必须是正确的时间格式
			array('fee_user','is_natural_num','收益比例-用户必须为不小于零的整数！',self::VALUE_VALIDATE,'function'),  //值不为空的时候验证 ，必须是自然数
			array('fee_service','is_natural_num','收益比例-扣税必须为不小于零的整数！',self::VALUE_VALIDATE,'function'),  //值不为空的时候验证 ，必须是自然数
			array('fee_plantform','is_natural_num','收益比例-平台必须为不小于零的整数！',self::VALUE_VALIDATE,'function'),  //值不为空的时候验证 ，必须是自然数		
	);
	
	/**
	 * 获取会员组列表
	 * @param string $is_freeze:是否冻结 Y是 N否
	 * @return array
	 */
	public function getGroupList($is_freeze='')
	{
		$where='1';
		if($is_freeze)
		{
			$where.=" and is_freeze='$is_freeze'";
		}
		$list=$this->where($where)->select();
		if($list!==false)
		{
			return $list;
		}else {
			return false;
		}
	}
	
	/**
	 * 获取会员组信息
	 * @param int $id:会员组ID
	 * @return array|false
	 */
	public function getGroupMsg($group_id)
	{
		$msg=$this->where("id='$group_id'")->find();
		if($msg!==false)
		{
			return $msg;
		}else {
			return false;
		}
	}
}
?>