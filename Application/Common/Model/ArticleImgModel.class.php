<?php
/**
 * 文章图片
 */
namespace Common\Model;
use Think\Model;

class ArticleImgModel extends Model
{
	//验证规则
	protected $_validate =array(
			array('article_id','require','请选择图片所属文章！',self::EXISTS_VALIDATE),  //存在验证，必填
			array('article_id','is_positive_int','请选择图片所属文章！',self::EXISTS_VALIDATE,'function'),  //存在验证，必须是正整数
			array('title','1,100','图片标题不超过100个字符！',self::VALUE_VALIDATE,'length'),  //值不为空的时候验证，不超过100个字符
			array('img','require','请上传图片！',self::EXISTS_VALIDATE),  //存在验证，必填
			array('img','1,100','图片路径不正确！',self::EXISTS_VALIDATE,'length'),  //存在验证 ，路径不超过100个字符
			array('href','url','不是正确的网址格式！',self::VALUE_VALIDATE),  //值不为空的时候验证 ，URL地址格式验证
			array('sort','is_natural_num','排序必须为不小于零的整数！',self::VALUE_VALIDATE,'function'),  //值不为空的时候验证 ，必须是自然数
			array('createtime','require','创建时间不能为空！',self::EXISTS_VALIDATE),  //存在验证，必填
			array('createtime','is_datetime','创建时间格式不正确！',self::EXISTS_VALIDATE,'function'),  //存在验证，必须是正确的时间格式
	);
	
	/**
	 * 获取图片列表
	 * @param int $article_id:文章ID
	 * @return array
	 */
	public function getImgList($article_id)
	{
		$res=$this->where("article_id=$article_id")->order('sort desc')->select();
		if($res!==false)
		{
			return $res;
		}else {
			return false;
		}
	}
	
	/**
	 * 获取图片信息
	 * @param int $id:图片ID
	 * @return array
	 */
	public function getImgMsg($id)
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