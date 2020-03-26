<?php
/**
 * 商品图片管理
 */
namespace Common\Model;
use Think\Model;

class GoodsImgModel extends Model
{
	//验证规则
	protected $_validate =array(
			array('goods_id','require','请选择图片所属商品！',self::EXISTS_VALIDATE),  //存在验证，必填
			array('goods_id','is_positive_int','请选择图片所属商品！',self::EXISTS_VALIDATE,'function'),  //存在验证，必须是正整数
			array('title','1,100','图片标题不超过100个字符！',self::VALUE_VALIDATE,'length'),  //值不为空的时候验证，不超过100个字符
			array('img','require','请上传图片！',self::EXISTS_VALIDATE),  //存在验证，必填
			array('img','1,100','图片路径不正确！',self::EXISTS_VALIDATE,'length'),  //存在验证 ，路径不超过100个字符
			array('sort','is_natural_num','排序必须为不小于零的整数！',self::VALUE_VALIDATE,'function'),  //值不为空的时候验证 ，必须是自然数
			array('createtime','require','创建时间不能为空！',self::EXISTS_VALIDATE),  //存在验证，必填
			array('createtime','is_datetime','创建时间格式不正确！',self::EXISTS_VALIDATE,'function'),  //存在验证，必须是正确的时间格式
	);
	
	/**
	 * 获取图片列表
	 * @param int $goods_id:商品ID
	 * @return array|false
	 */
	public function getImgList($goods_id)
	{
		$list=$this->where("goods_id='$goods_id'")->order('sort desc')->select();
		if($list!==false)
		{
			return $list;
		}else {
			return false;
		}
	}
	
	/**
	 * 获取图片信息
	 * @param int $goods_img_id:图片ID
	 * @return array|false
	 */
	public function getImgMsg($goods_img_id)
	{
		$msg=$this->where("goods_img_id='$goods_img_id'")->find();
		if($msg!==false)
		{
			return $msg;
		}else {
			return false;
		}
	}
	
	/**
	 * 获取商品图片数量
	 * @param int $goods_id:商品ID
	 * @return number|boolean
	 */
	public function getGoodsImgNum($goods_id)
	{
		$num=$this->where("goods_id='$goods_id'")->count();
		if($num!==false)
		{
			return $num;
		}else {
			return false;
		}
	}
}
?>