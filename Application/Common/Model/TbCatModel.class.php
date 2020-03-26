<?php
/**
 * 淘宝商品分类管理类
 * 淘宝官方分类
 */
namespace Common\Model;
use Think\Model;

class TbCatModel extends Model
{
	//验证规则
	protected $_validate =array(
			array('category_id','require','淘宝商品分类ID不能为空！',self::EXISTS_VALIDATE),  //存在验证，必填
			array('category_id','1,50','淘宝商品分类ID不超过50个字符！',self::EXISTS_VALIDATE,'length'),  //存在验证，不超过50个字符
			array('category_name','require','淘宝商品分类名称不能为空！',self::EXISTS_VALIDATE),  //存在验证，必填
			array('category_name','1,300','淘宝商品分类名称不超过300个字符！',self::EXISTS_VALIDATE,'length'),  //存在验证，不超过300个字符
	);
	
	/**
	 * 获取淘宝官方商品分类信息
	 * @param string $category_id:淘宝官方商品分类ID
	 * @return array|boolean
	 */
	public function getCatMsg($category_id)
	{
		$msg=$this->where("category_id='$category_id'")->find();
		if($msg) {
			return $msg;
		}else {
			return false;
		}
	}
	
	/**
	 * 获取淘宝官方商品分类信息
	 * @param string $id:ID
	 * @return array|boolean
	 */
	public function getCatMsg2($id)
	{
	    $msg=$this->where("id='$id'")->find();
	    if($msg) {
	        return $msg;
	    }else {
	        return false;
	    }
	}
}
?>