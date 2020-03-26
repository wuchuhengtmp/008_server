<?php
/**
 * 商城系统-商品分类属性管理
 */
namespace Common\Model;
use Think\Model;

class GoodsAttributeModel extends Model
{
	//验证规则
	protected $_validate =array(
			array('goods_attribute_name','require','商品分类属性名称不能为空！',self::EXISTS_VALIDATE),  //存在验证，必填
			array('goods_attribute_name','1,20','商品分类属性名称不超过20个字符！',self::EXISTS_VALIDATE,'length'),  //存在验证，不超过20个字符
			array('sort','is_natural_num','排序必须为不小于零的整数！',self::VALUE_VALIDATE,'function'),  //存在验证 ，必须是自然数
			array('is_show',array('Y','N'),'请选择是否显示！',self::EXISTS_VALIDATE,'in'),  //存在验证，只能是Y是 N否
	);
	
	/**
	 * 获取商品分类下的属性个数
	 * @param int $goods_cat_id:所属商品分类ID
	 * @return number|boolean
	 */
	public function getGoodsCatAttributeNum($goods_cat_id)
	{
		$num=$this->where("goods_cat_id='$goods_cat_id'")->count();
		if($num!==false)
		{
			return $num;
		}else {
			return false;
		}
	}
	
	/**
	 * 获取商品分类属性列表
	 * @param int $goods_cat_id:所属商品分类ID
	 * @return array|boolean
	 */
	public function getList($goods_cat_id)
	{
		$list=$this->where("goods_cat_id='$goods_cat_id'")->order('sort desc')->select();
		if($list!==false)
		{
			return $list;
		}else {
			return false;
		}
	}
	
	/**
	 * 获取商品分类属性详情
	 * @param int $goods_attribute_id:商品分类属性ID
	 * @return array|boolean
	 */
	public function getMsg($goods_attribute_id)
	{
		$msg=$this->where("goods_attribute_id='$goods_attribute_id'")->find();
		if($msg!==false)
		{
			return $msg;
		}else {
			return false;
		}
	}
	
	/**
	 * 获取商品分类属性及相应属性值列表
	 * @param int $goods_cat_id:所属商品分类ID
	 * @return array|boolean
	 */
	public function getListDetail($goods_cat_id)
	{
		$list=$this->where("goods_cat_id='$goods_cat_id'")->order('sort desc')->select();
		if($list!==false)
		{
			//进一步获取属性值列表
			$GoodsAttributeValue=new \Common\Model\GoodsAttributeValueModel();
			$num=count($list);
			for ($i=0;$i<$num;$i++)
			{
				$goods_attribute_id=$list[$i]['goods_attribute_id'];
				$valuelist=$GoodsAttributeValue->getList($goods_attribute_id);
				$list[$i]['valuelist']=$valuelist;
			}
			return $list;
		}else {
			return false;
		}
	}
	
	/**
	 * 获取商品分类及其父分类属性及相应属性值列表
	 * @param int $goods_cat_id:所属商品分类ID
	 * @return array|boolean
	 */
	public function getListDetail2($goods_cat_id)
	{
		$GoodsCat=new \Common\Model\GoodsCatModel();
		$GoodsCatMsg=$GoodsCat->getCatMsg($goods_cat_id);
		$all_catid=$goods_cat_id;
		if($GoodsCatMsg['parent_id'])
		{
			$all_catid.=','.$GoodsCatMsg['parent_id'];
		}
		$list=$this->where("goods_cat_id in ($all_catid)")->order('sort desc')->select();
		if($list!==false)
		{
			//进一步获取属性值列表
			$GoodsAttributeValue=new \Common\Model\GoodsAttributeValueModel();
			$num=count($list);
			for ($i=0;$i<$num;$i++)
			{
				$goods_attribute_id=$list[$i]['goods_attribute_id'];
				$valuelist=$GoodsAttributeValue->getList($goods_attribute_id);
				$list[$i]['valuelist']=$valuelist;
			}
			return $list;
		}else {
			return false;
		}
	}
}
?>