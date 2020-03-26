<?php
/**
 * 客服QQ管理
 */
namespace Common\Model;
use Think\Model;

class QqModel extends Model
{
	//验证规则
	protected $_validate =array(
			array('title','require','客服名称不能为空！',self::EXISTS_VALIDATE),  //存在验证，必填
			array('title','1,50','客服名称不超过50个字符！',self::EXISTS_VALIDATE,'length'),  //存在验证，不超过50个字符
			array('num','require','客服号码不能为空！',self::EXISTS_VALIDATE),  //存在验证，必填
			array('num','1,20','客服号码不超过20个字符！',self::EXISTS_VALIDATE,'length'),  //存在验证，不超过20个字符
			array('sort','is_natural_num','排序必须为不小于零的整数！',self::VALUE_VALIDATE,'function'),  //值不为空的时候验证 ，必须是自然数
	);
	
	/**
	 * 获取QQ客服列表
	 * @return array|false
	 */
	public function getQqList()
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
	 * 获取QQ信息
	 * @param int $id:客服ID
	 * @return array|false
	 */
	public function getMsg($id)
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