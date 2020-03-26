<?php
/**
 * 商品分类管理
 */
namespace Common\Model;
use Think\Model;

class GoodsCatModel extends Model
{
	//验证规则
	protected $_validate =array(
			array('cat_name','require','商品分类名称不能为空！',self::EXISTS_VALIDATE),  //存在验证，必填
			array('cat_name','1,100','商品分类名称不超过100个字符！',self::EXISTS_VALIDATE,'length'),  //存在验证，不超过100个字符
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
			array('is_top',array('Y','N'),'请选择是否为推荐/置顶！',self::EXISTS_VALIDATE,'in'),  //存在验证，只能是Y是 N否
	);
	
	/**
	 * 获取商品分类列表
	 * @param string $is_show:是否显示 Y显示 N不显示
	 * @return array
	 */
	public function getCatList($is_show='')
	{
		if($is_show)
		{
			$where="is_show='$is_show'";
		}else {
			$where='1';
		}
		$cat=$this->where($where)->order('sort desc')->select();
		$catlist =$this->rule($cat);
		return $catlist;
	}
	
	/**
	 * 获取全部商品分类列表
	 * @param string $is_show:是否显示 Y显示 N不显示
	 * @return array
	 */
	public function getAllCatList($is_show='')
	{
	    if($is_show) {
	        $where="is_show='$is_show'";
	    }else {
	        $where='1';
	    }
	    $list=$this->where($where)->field('cat_id,cat_name,keywords,description,img,parent_id')->order('sort desc,cat_id asc')->select();
	    $catlist =$this->generateTree($list);
	    return $catlist;
	}
	
	/**
	 * 获取顶级商品分类列表
	 * @param string $is_show:是否显示 Y显示 N不显示
	 * @return array
	 */
	public function getTopCatList($is_show='')
	{
		if($is_show)
		{
			$where="parent_id='0' and is_show='$is_show'";
		}else {
			$where="parent_id='0'";
		}
		$list=$this->where($where)->order('sort desc')->select();
		if($list!==false)
		{
			return $list;
		}else {
			return false;
		}
	}
	
	/**
	 * 获取子分类
	 * @param int $cat_id:商品分类ID
	 * @return array
	 */
	public function getSubCatList($cat_id)
	{
		$cat=$this->order('sort desc')->select();
		$catlist =$this->rule($cat,'-',$cat_id);
		return $catlist;
	}
	
	/**
	 * 获取子分类
	 * @param int $cat_id:商品分类ID
	 * @param string $is_show:是否显示 Y显示 N不显示
	 * @return array
	 */
	public function getSubCatList2($cat_id,$is_show='')
	{
		$where="parent_id='$cat_id'";
		if($is_show)
		{
			$where.=" and is_show='$is_show'";
		}
		$catlist=$this->where($where)->order('sort desc')->select();
		if($catlist!==false)
		{
			return $catlist;
		}else {
			return false;
		}
	}
	
	/**
	 * 获取商品分类信息
	 * @param int $cat_id:商品分类ID
	 * @return array|false
	 */
	public function getCatMsg($cat_id)
	{
		$msg=$this->where("cat_id='$cat_id'")->find();
		if($msg!==false)
		{
			return $msg;
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
	
	/**
	 * 生成分类树
	 * @param array $list:分类列表
	 * @return array 树结构
	 */
	static public function generateTree($list)
	{
	    $num=count($list);
	    for($i=1;$i<=$num;$i++){
	        $items[$i]=$list[$i-1];
	    }
	    foreach($items as $item)
	        $items[$item['parent_id']]['sublist'][$item['cat_id']] = &$items[$item['cat_id']];
	        return isset($items[0]['sublist']) ? $items[0]['sublist'] : array();
	}
}
?>