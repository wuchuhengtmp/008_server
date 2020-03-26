<?php
/**
 * 订单详情管理类
 */
namespace Common\Model;
use Think\Model;

class OrderDetailModel extends Model
{
	//验证规则
	protected $_validate =array(
			array('order_id','require','订单ID不能为空！',self::EXISTS_VALIDATE),  //存在验证，必填
			array('order_id','is_positive_int','请选择正确的订单',self::EXISTS_VALIDATE,'function'),  //存在验证，必须是正整数
			array('order_num','require','订单号不能为空！',self::EXISTS_VALIDATE),  //存在验证，必填
			array('order_num','1,30','订单号不超过30个字符！',self::EXISTS_VALIDATE,'length'),  //存在验证，不超过30个字符		
			array('goods_id','require','购买的商品不能为空！',self::EXISTS_VALIDATE),  //存在验证，必填
			array('goods_id','is_positive_int','购买的商品不存在',self::EXISTS_VALIDATE,'function'),  //存在验证，必须是正整数
			array('goods_name','require','商品名称不能为空！',self::EXISTS_VALIDATE),  //存在验证，必填
			array('goods_name','1,100','商品名称不超过100个字符！',self::EXISTS_VALIDATE,'length'),  //存在验证，不超过100个字符		
			array('price','require','单价不能为空！',self::EXISTS_VALIDATE),  //存在验证，必填
			array('price','is_natural_num','单价不是正确的货币格式！',self::EXISTS_VALIDATE,'function'),  //存在验证 ，必须是货币格式
			//array('price','currency','单价不是正确的货币格式！',self::EXISTS_VALIDATE),  //存在验证 ，必须是货币格式
			array('num','require','商品数量不能为空！',self::EXISTS_VALIDATE),  //存在验证，必填
			array('num','is_positive_int','商品数量只能为正整数',self::EXISTS_VALIDATE,'function'),  //存在验证，必须是正整数
			array('allprice','require','总价不能为空！',self::EXISTS_VALIDATE),  //存在验证，必填
			//array('allprice','currency','总价不是正确的货币格式！',self::EXISTS_VALIDATE),  //存在验证 ，必须是货币格式
			array('allprice','is_natural_num','总价不是正确的货币格式！',self::EXISTS_VALIDATE,'function'),  //存在验证 ，必须是货币格式
			array('give_point','is_natural_num','赠送积分必须为不小于零的整数！',self::VALUE_VALIDATE,'function'),  //值不为空的时候验证 ，必须是自然数
			array('all_give_point','is_natural_num','全部赠送积分必须为不小于零的整数！',self::VALUE_VALIDATE,'function'),  //值不为空的时候验证 ，必须是自然数	
	);
	
	/**
	 * 获取订单详情列表
	 * @param int $order_id:订单ID
	 * @return array
	 */
	public function getOrderDetail($order_id)
	{
		$sql="SELECT o.*,g.img,g.clicknum,g.old_price,g.sales_volume,g.virtual_volume FROM __PREFIX__order_detail o,__PREFIX__goods g WHERE o.order_id='$order_id' and o.goods_id=g.goods_id";
		$list=M()->query($sql);
		if($list!==false)
		{
			$num=count($list);
			$GoodsAttribute=new \Common\Model\GoodsAttributeModel();
			$GoodsSku=new \Common\Model\GoodsSkuModel();
			for($i=0;$i<$num;$i++)
			{
				//单价
				$list[$i]['price']=$list[$i]['price']/100;
				//总价
				$list[$i]['allprice']=$list[$i]['allprice']/100;
				$sku_arr=array();
				//属性字符串
				$sku_str='';
				if($list[$i]['sku']) {
					$sku_arr=json_decode($list[$i]['sku'],true);
					$sku_num=count($sku_arr);
					for($j=0;$j<$sku_num;$j++)
					{
						$GoodsAttributeMsg=$GoodsAttribute->getMsg($sku_arr[$j]['attribute_id']);
						$sku_arr[$j]['attribute_name']=$GoodsAttributeMsg['goods_attribute_name'];
						$sku_str.=$GoodsAttributeMsg['goods_attribute_name'].'：'.$sku_arr[$j]['value'].'&nbsp;&nbsp;';
					}
					//商品图片
					$GoodsSkuMsg=$GoodsSku->getSkuMsg($list[$i]['sku'],$list[$i]['goods_id']);
					if($GoodsSkuMsg['img']) {
						$list[$i]['img']=$GoodsSkuMsg['img'];
					}
				}
				$list[$i]['sku_arr']=$sku_arr;
				if($sku_str) {
					$sku_str=substr($sku_str, 0,-1);
				}
				$list[$i]['sku_str']=$sku_str;
			}
			return $list;
		}else {
			return false;
		}
	}
}
?>