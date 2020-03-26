<?php
/**
 * 友情链接分类管理
 */
namespace Common\Model;
use Think\Model;

class HrefCatModel extends Model
{
	//验证规则
	protected $_validate =array(
			array('title','require','分类名称不能为空！',self::EXISTS_VALIDATE),  //存在验证，必填
			array('title','1,50','分类名称不超过50个字符！',self::EXISTS_VALIDATE,'length'),  //存在验证，不超过50个字符
			array('sort','is_natural_num','排序必须为不小于零的整数！',self::VALUE_VALIDATE,'function'),  //值不为空的时候验证 ，必须是自然数
			array('createtime','require','创建时间不能为空！',self::EXISTS_VALIDATE),  //存在验证，必填
			array('createtime','is_datetime','创建时间格式不正确！',self::EXISTS_VALIDATE,'function'),  //存在验证，必须是正确的时间格式
	);
	
	/**
	 * 获取友情链接分类列表
	 * @return array|boolean
	 */
	public function getHrefCatList()
	{
		$res=$this->order('sort desc')->select();
		if($res!==false)
		{
			return $res;
		}else {
			return false;
		}
	}
	
	/**
	 * 获取分类信息
	 * @param int $id:分类ID
	 * @return array|false
	 */
	public function getCatMsg($id)
	{
		$res=$this->where("id=$id")->find();
		if($res!==false)
		{
			return $res;
		}else {
			return false;
		}
	}
}