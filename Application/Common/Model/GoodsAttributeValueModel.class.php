<?php
/**
 * 商城系统-商品分类属性值设置
 */
namespace Common\Model;
use Think\Model;

class GoodsAttributeValueModel extends Model
{
	//验证规则
	protected $_validate =array(
			array('goods_attribute_id','require','所属商品分类属性不能为空！',self::EXISTS_VALIDATE),  //存在验证，必填
			array('goods_attribute_id','is_positive_int','请选择正确的商品分类属性',self::EXISTS_VALIDATE,'function'),  //存在验证，必须是正整数
			array('name','require','商品分类属性值名称不能为空！',self::EXISTS_VALIDATE),  //存在验证，必填
			array('name','1,20','商品分类属性值名称不超过20个字符！',self::EXISTS_VALIDATE,'length'),  //存在验证，不超过20个字符
			array('img','1,255','属性值配图路径不正确！',self::VALUE_VALIDATE,'length'),  //值不为空的时候验证 ，不超过255个字符
			array('sort','is_natural_num','排序必须为不小于零的整数！',self::VALUE_VALIDATE,'function'),  //存在验证 ，必须是自然数
			array('is_show',array('Y','N'),'请选择是否显示！',self::EXISTS_VALIDATE,'in'),  //存在验证，只能是Y是 N否
	);
	
	/**
	 * 获取商品分类属性值个数
	 * @param int $goods_attribute_id:商品分类属性ID
	 * @return number|boolean
	 */
	public function getValueNum($goods_attribute_id)
	{
		$num=$this->where("goods_attribute_id='$goods_attribute_id'")->count();
		if($num!==false)
		{
			return $num;
		}else {
			return false;
		}
	}
	
	/**
	 * 获取商品分类属性值列表
	 * @param int $goods_attribute_id:商品分类属性ID
	 * @return array|boolean
	 */
	public function getList($goods_attribute_id)
	{
		$list=$this->where("goods_attribute_id='$goods_attribute_id'")->order('sort desc')->select();
		if($list!==false)
		{
			return $list;
		}else {
			return false;
		}
	}
	
	/**
	 * 获取商品分类属性值详情
	 * @param int $goods_attribute_value_id:商品分类属性值ID
	 * @return array|boolean
	 */
	public function getMsg($goods_attribute_value_id)
	{
		$msg=$this->where("goods_attribute_value_id='$goods_attribute_value_id'")->find();
		if($msg!==false)
		{
			return $msg;
		}else {
			return false;
		}
	}
}
?>