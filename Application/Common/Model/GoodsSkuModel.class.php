<?php
/**
 * 商城系统-商品SKU管理
 */
namespace Common\Model;
use Think\Model;

class GoodsSkuModel extends Model
{
	//验证规则
	protected $_validate =array(
		array('goods_id','require','所属商品不能为空！',self::EXISTS_VALIDATE),  //存在验证，必填
		array('goods_id','is_positive_int','请选择正确的商品',self::EXISTS_VALIDATE,'function'),  //存在验证，必须是正整数
		array('sku','require','请配置正确的商品SKU属性！',self::EXISTS_VALIDATE),  //存在验证，必填
		array('price','require','价格不能为空！',self::EXISTS_VALIDATE),  //存在验证，必填
		array('price','is_natural_num','价格不是正确的货币格式！',self::EXISTS_VALIDATE,'function'),  //存在验证 ，必须是自然数
		array('inventory','require','库存不能为空！',self::EXISTS_VALIDATE),  //存在验证，必填
		array('inventory','is_natural_num','库存必须为不小于零的整数！',self::EXISTS_VALIDATE,'function'),  //存在验证 ，必须是自然数
		array('give_point','is_natural_num','赠送积分必须为不小于零的整数！',self::VALUE_VALIDATE,'function'),  //值不为空的时候验证 ，必须是自然数
	    array('deduction_point','is_natural_num','可抵扣积分必须为不小于零的整数！',self::VALUE_VALIDATE,'function'),  //值不为空的时候验证 ，必须是自然数
	    array('img','1,255','图片路径不正确！',self::VALUE_VALIDATE,'length'),  //值不为空的时候验证 ，不超过255个字符
	);
	
	/**
	 * 获取已配置商品SKU属性列表
	 * @param int $goods_id:商品ID
	 * @return array|boolean
	 */
	public function getSkuList($goods_id)
	{
		$list=$this->where("goods_id='$goods_id'")->select();
		if($list!==false)
		{
			$num=count($list);
			for ($i=0;$i<$num;$i++)
			{
				$list[$i]['price']=$list[$i]['price']/100;
			}
			return $list;
		}else {
			return false;
		}
	}
	
	/**
	 * 获取商品SKU配置信息
	 * @param string $sku:商品SKU配置，json数组
	 * @param int $goods_id:商品ID
	 * @return array|boolean
	 */
	public function getSkuMsg($sku,$goods_id='')
	{
		$where="sku='$sku'";
		if($goods_id)
		{
			$where.=" and goods_id='$goods_id'";
		}
		$msg=$this->where($where)->find();
		if($msg)
		{
			//价格
			$msg['price']=$msg['price']/100;
			$msg['sku_arr']=array();
			if($msg['sku'])
			{
				$sku_arr=json_decode($msg['sku'],true);
				$num=count($sku_arr);
				$GoodsAttribute=new \Common\Model\GoodsAttributeModel();
				for ($i=0;$i<$num;$i++)
				{
					//属性ID
					$attribute_id=$sku_arr[$i]['attribute_id'];
					$GoodsAttributeMsg=$GoodsAttribute->getMsg($attribute_id);
					$sku_arr[$i]['attribute_name']=$GoodsAttributeMsg['goods_attribute_name'];
					$sku_str.=$GoodsAttributeMsg['goods_attribute_name'].'：'.$sku_arr[$i]['value'].'&nbsp;&nbsp;';
				}
				$msg['sku_arr']=$sku_arr;
				$msg['sku_str']=substr($sku_str, 0,-1);
			}
			return $msg;
		}else {
			return false;
		}
	}
}
?>