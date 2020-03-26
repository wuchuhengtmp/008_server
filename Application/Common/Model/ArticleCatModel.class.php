<?php
/**
 * 文章分类
 */
namespace Common\Model;
use Think\Model;

class ArticleCatModel extends Model
{
	//验证规则
	protected $_validate =array(
			array('cat_name','require','文章分类名称不能为空！',self::EXISTS_VALIDATE),  //存在验证，必填
			array('cat_name','1,100','文章分类名称不超过100个字符！',self::EXISTS_VALIDATE,'length'),  //存在验证，不超过100个字符
			array('keywords','1,255','关键词不超过255个字符！',self::VALUE_VALIDATE,'length'),  //值不为空的时候验证 ，不超过255个字符
			array('description','1,1000','简要说明不超过1000个字符！',self::VALUE_VALIDATE,'length'),  //值不为空的时候验证 ，不超过1000个字符
			array('sort','is_natural_num','排序必须为不小于零的整数！',self::VALUE_VALIDATE,'function'),  //值不为空的时候验证 ，必须是自然数
			array('is_show','require','请选择是否显示！',self::EXISTS_VALIDATE),  //存在验证，必填
			array('is_show',array('Y','N'),'请选择是否显示！',self::EXISTS_VALIDATE,'in'),  //存在验证，只能是Y是 N否
			array('img','1,255','图片路径不正确！',self::VALUE_VALIDATE,'length'),  //值不为空的时候验证 ，不超过255个字符
			array('parent_id','require','父级分类不能为空！',self::EXISTS_VALIDATE),  //存在验证，必填
			array('parent_id','is_natural_num','请选择正确的父级分类！',self::EXISTS_VALIDATE,'function'),  //存在验证，必须是自然数
			array('create_time','require','创建时间不能为空！',self::EXISTS_VALIDATE),  //存在验证，必填
			array('create_time','is_datetime','创建时间格式不正确！',self::EXISTS_VALIDATE,'function'),  //存在验证，必须是正确的时间格式
	);
	
	/**
	 * 获取文章分类列表
	 * @return array
	 */
	public function getCatList()
	{
		$cat=$this->order('sort desc')->select();
		$catlist =$this->rule($cat);
		return $catlist;
	}
	
	/**
	 * 获取子分类
	 * @param int $cat_id:文章分类ID
	 * @return array
	 */
	public function getSubCatList($cat_id,$order='asc')
	{
		$cat=$this->order("sort desc,cat_id $order")->select();
		$catlist =$this->rule($cat,'-',$cat_id);
		return $catlist;
	}
	
	/**
	 * 获取文章分类信息
	 * @param int $cat_id:文章分类ID
	 * @return 成功返回数组，失败返回false
	 */
	public function getCatMsg($cat_id)
	{
		$res=$this->where("cat_id=$cat_id")->find();
		if($res!==false)
		{
			return $res;
		}else {
			return false;
		}
	}
	
	static public function rule($cate , $lefthtml = '— ' , $pid=0 , $lvl=0, $leftpin=0 )
	{
		$arr=array();
		foreach ($cate as $v)
		{
			if($v['parent_id']==$pid)
			{
				$v['lvl']=$lvl + 1;
				$v['leftpin']=$leftpin + 0;//左边距
				$v['lefthtml']=str_repeat($lefthtml,$lvl);
				$arr[]=$v;
				$arr= array_merge($arr,self::rule($cate,$lefthtml,$v['cat_id'],$lvl+1 , $leftpin+20));
			}
		}
		return $arr;
	}
}