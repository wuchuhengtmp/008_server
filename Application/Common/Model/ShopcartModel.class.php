<?php
/**
 * 购物车管理类
 */
namespace Common\Model;
use Think\Model;

class ShopcartModel extends Model
{
	//验证规则
	protected $_validate =array(
			array('user_id','require','购买用户不能为空！',self::EXISTS_VALIDATE),  //存在验证，必填
			array('user_id','is_positive_int','购买用户不存在',self::EXISTS_VALIDATE,'function'),  //存在验证，必须是正整数
			array('goods_id','require','购买商品不能为空！',self::EXISTS_VALIDATE),  //存在验证，必填
			array('goods_id','is_positive_int','购买商品不存在',self::EXISTS_VALIDATE,'function'),  //存在验证，必须是正整数
			array('goods_num','require','商品数量不能为空！',self::EXISTS_VALIDATE),  //存在验证，必填
			array('goods_num','is_positive_int','商品数量必须为正整数',self::EXISTS_VALIDATE,'function'),  //存在验证，必须是正整数
	);
	
	/**
	 * 获取用户购物车列表
	 * @param int $uid:用户ID
	 * @return array|boolean
	 */
	public function getUserShopcart($uid)
	{
		$sql="select c.*,g.* from __PREFIX__shopcart c,__PREFIX__goods g where c.user_id='$uid' and c.goods_id=g.goods_id";
		$list=M()->query($sql);
		if($list!==false)
		{
			$num=count($list);
			$GoodsSku=new \Common\Model\GoodsSkuModel();
			$GoodsAttribute=new \Common\Model\GoodsAttributeModel();
			for($i=0;$i<$num;$i++)
			{
			    $list[$i]['price']=$list[$i]['price']/100;
				if($list[$i]['goods_sku']) {
					$goods_sku=$list[$i]['goods_sku'];
					$skuMsg=$GoodsSku->getSkuMsg($goods_sku);
					//价格
					$list[$i]['price']=$skuMsg['price'];
					//图片
					if($skuMsg['img']) {
						$list[$i]['img']=$skuMsg['img'];
					}
					$sku_arr=json_decode($list[$i]['goods_sku'],true);
					$num2=count($sku_arr);
					$sku_str='';
					for ($j=0;$j<$num2;$j++)
					{
						//属性ID
						$GoodsAttributeMsg=$GoodsAttribute->getMsg($sku_arr[$j]['attribute_id']);
						$sku_arr[$j]['attribute_name']=$GoodsAttributeMsg['goods_attribute_name'];
						$sku_str.=$GoodsAttributeMsg['goods_attribute_name'].'：'.$sku_arr[$j]['value'].'&nbsp;&nbsp;';
					}
					$list[$i]['sku_arr']=$sku_arr;
					$list[$i]['sku_str']=substr($sku_str, 0,-1);
				}else {
					$list[$i]['sku_arr']=array();
					$list[$i]['sku_str']='';
				}
			}
			return $list;
		}else {
			return false;
		}
	}
	
	/**
	 * 获取购物车数量
	 * @param int $uid:用户ID
	 * @return number|boolean
	 */
	public function shopcartNum($uid)
	{
		$num=$this->where("user_id='$uid'")->count();
		if($num!==false)
		{
			return $num;
		}else {
			return false;
		}
	}
}
?>