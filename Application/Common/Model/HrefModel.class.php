<?php
/**
 * 友情链接类
 */
namespace Common\Model;
use Think\Model;

class HrefModel extends Model
{
	//验证规则
	protected $_validate =array(
			array('cat_id','require','分类名称不能为空！',self::EXISTS_VALIDATE),  //存在验证，必填
			array('cat_id','is_positive_int','请选择正确的分类',self::EXISTS_VALIDATE,'function'),  //存在验证，必须是正整数
			array('title','require','链接名称不能为空！',self::EXISTS_VALIDATE),  //存在验证，必填
			array('title','1,200','链接名称不超过200个字符！',self::EXISTS_VALIDATE,'length'),  //存在验证，不超过200个字符
			array('img','1,100','图片路径不正确！',self::VALUE_VALIDATE,'length'),  //值不为空的时候验证 ，不超过100个字符
			array('href','url','不是正确的网址格式！',self::VALUE_VALIDATE),  //值不为空的时候验证 ，URL地址格式验证
			array('sort','is_natural_num','排序必须为不小于零的整数！',self::VALUE_VALIDATE,'function'),  //值不为空的时候验证 ，必须是自然数
			array('createtime','require','创建时间不能为空！',self::EXISTS_VALIDATE),  //存在验证，必填
			array('createtime','is_datetime','创建时间格式不正确！',self::EXISTS_VALIDATE,'function'),  //存在验证，必须是正确的时间格式
	);
	
	/**
	 * 获取友情链接列表
	 * @param int $cat_id:分类ID
	 * @return array|false
	 */
	public function getHrefList($cat_id)
	{
		$res=$this->where("cat_id=$cat_id")->order('sort desc')->select();
		if($res!==false)
		{
			return $res;
		}else {
			return false;
		}
	}
	
	/**
	 * 获取链接信息
	 * @param int $id:链接ID
	 * @return array|false
	 */
	public function getHrefMsg($id)
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