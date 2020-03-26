<?php
/**
 * 银行管理
 */
namespace Common\Model;
use Think\Model;

class BankModel extends Model
{
	//验证规则
	protected $_validate =array(
			array('bank_name','require','银行名称不能为空！',self::EXISTS_VALIDATE),  //存在验证，必填
			array('bank_name','1,30','银行名称不超过30个字符！',self::EXISTS_VALIDATE,'length'),  //存在验证，不超过30个字符
			array('icon','1,255','银行logo路径不正确！',self::VALUE_VALIDATE,'length'),  //值不为空的时候验证 ，不超过255个字符
			array('bg_img','1,255','背景图路径不正确！',self::VALUE_VALIDATE,'length'),  //值不为空的时候验证 ，不超过255个字符
			array('sort','is_natural_num','排序必须为不小于零的整数！',self::VALUE_VALIDATE,'function'),  //值不为空的时候验证 ，必须是自然数
			array('is_show',array('Y','N'),'请选择是否显示！',self::EXISTS_VALIDATE,'in'),  //存在验证，只能是Y是 N否
	);
	
	/**
	 * 获取银行列表
	 * @param string $is_show:是否显示 Y显示 N不显示
	 * @return array|boolean
	 */
	public function getBankList($is_show='')
	{
		$where='1';
		if($is_show)
		{
			$where.=" and is_show='$is_show'";
		}
		$list=$this->where($where)->order('sort desc')->select();
		if($list!==false)
		{
			return $list;
		}else {
			return false;
		}
	}
	
	/**
	 * 获取银行信息
	 * @param int $bank_id:银行信息
	 * @return array|boolean
	 */
	public function getBankMsg($bank_id)
	{
		$msg=$this->where("bank_id='$bank_id'")->find();
		if($msg)
		{
			return $msg;
		}else {
			return false;
		}
	}
}
?>