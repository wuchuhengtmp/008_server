<?php
/**
 * 拼多多分类管理类
 */
namespace Common\Model;
use Think\Model;

class PddCatModel extends Model
{
	//验证规则
	protected $_validate =array(
			array('name','require','商品分类名称不能为空！',self::EXISTS_VALIDATE),  //存在验证，必填
			array('name','1,20','商品分类名称不超过20个字符！',self::EXISTS_VALIDATE,'length'),  //存在验证，不超过20个字符
			array('icon','1,255','商品分类图标路径不正确！',self::VALUE_VALIDATE,'length'),  //值不为空的时候验证，不超过255个字符
			array('sort','is_natural_num','排序必须为不小于零的整数！',self::VALUE_VALIDATE,'function'),  //值不为空的时候验证 ，必须是自然数
			array('is_show','require','请选择是否显示！',self::EXISTS_VALIDATE),  //存在验证，必填
			array('is_show',array('Y','N'),'请选择是否显示！',self::EXISTS_VALIDATE,'in'),  //存在验证，只能是Y是 N否
			array('icon','1,255','图片路径不正确！',self::VALUE_VALIDATE,'length'),  //值不为空的时候验证 ，不超过255个字符
			array('pid','require','父级供应商类型不能为空！',self::EXISTS_VALIDATE),  //存在验证，必填
			array('pid','is_natural_num','请选择正确的父级供应商类型！',self::EXISTS_VALIDATE,'function'),  //存在验证，必须是自然数
			array('pdd_id','require','拼多多官方分类ID不能为空！',self::EXISTS_VALIDATE),  //存在验证，必填
			array('pdd_id','is_natural_num','拼多多官方分类ID不存在！',self::EXISTS_VALIDATE,'function'),  //存在验证，必须是自然数
	);
	
	/**
	 * 获取商品分类列表
	 * @param string $is_show:是否显示 Y显示，N不显示，默认显示全部
	 * @return array
	 */
	public function getGoodsCatList($is_show='')
	{
		$where='1';
		if($is_show)
		{
			$where.=" and is_show='$is_show'";
		}
		$cat=$this->where($where)->order('sort desc,pdd_cat_id asc')->select();
		if($cat!==false)
		{
			$list =$this->rule($cat);
			return $list;
		}else {
			return false;
		}
	}
	
	/**
	 * 获取顶级商品分类列表
	 * @param string $is_show:是否显示 Y显示，N不显示，默认显示全部
	 * @return array
	 */
	public function getParentList($is_show='')
	{
		$where="pid=0";
		if($is_show)
		{
			$where.=" and is_show='$is_show'";
		}
		$list=$this->where($where)->order('sort desc,pdd_cat_id asc')->select();
		if($list!==false)
		{
			return $list;
		}else {
			return false;
		}
	}
	
	/**
	 * 获取子分类
	 * @param int $pid:父级商品分类ID
	 * @param string $is_show:是否显示 Y显示，N不显示，默认显示全部
	 * @return array
	 */
	public function getSubListByParent($pid,$order='asc',$is_show='')
	{
		$where="pid='$pid'";
		if($is_show)
		{
			$where.=" and is_show='$is_show'";
		}
		$list=$this->where($where)->order("sort desc,pdd_cat_id $order")->select();
		if($list!==false)
		{
			return $list;
		}else {
			return false;
		}
	}
	
	/**
	 * 获取子分类
	 * @param int $taobao_cat_id:父级商品分类ID
	 * @param string $is_show:是否显示 Y显示，N不显示，默认显示全部
	 * @return array
	 */
	public function getSubList($taobao_cat_id,$order='asc',$is_show='')
	{
		$where='1';
		if($is_show)
		{
			$where.=" and is_show='$is_show'";
		}
		$cat=$this->where($where)->order("sort desc,pdd_cat_id $order")->select();
		$sublist =$this->rule($cat,'-',$taobao_cat_id);
		return $sublist;
	}
	
	/**
	 * 获取商品分类信息
	 * @param int $pdd_cat_id:商品分类ID
	 * @return array|boolean
	 */
	public function getMsg($pdd_cat_id)
	{
		$msg=$this->where("pdd_cat_id='$pdd_cat_id'")->find();
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
			if($v['pid']==$pid)
			{
				$v['lvl']=$lvl + 1;
				$v['leftpin']=$leftpin + 0;//左边距
				$v['lefthtml']=str_repeat($lefthtml,$lvl);
				$arr[]=$v;
				$arr= array_merge($arr,self::rule($cate,$lefthtml,$v['pdd_cat_id'],$lvl+1 , $leftpin+20));
			}
		}
		return $arr;
	}
}
?>