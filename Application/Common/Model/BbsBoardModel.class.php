<?php
namespace Common\Model;
use Think\Model;

class BbsBoardModel extends Model
{
	//验证规则
	protected $_validate =array(
			array('board_name','require','版块名称不能为空！',self::EXISTS_VALIDATE),  //存在验证，必填
			array('board_name','1,50','版块名称不超过50个字符！',self::EXISTS_VALIDATE,'length'),  //存在验证，不超过50个字符
			array('keyword','1,100','关键词不超过100个字符！',self::VALUE_VALIDATE,'length'),  //值不为空的时候验证 ，不超过100个字符
			array('description','1,1000','简要说明不超过1000个字符！',self::VALUE_VALIDATE,'length'),  //值不为空的时候验证 ，不超过1000个字符
			array('sort','is_natural_num','排序必须为自然数！',self::VALUE_VALIDATE,'function'),  //值不为空的时候验证 ，必须是自然数
			array('is_show','require','请选择是否显示！',self::EXISTS_VALIDATE),  //存在验证，必填
			array('is_show',array('Y','N'),'请选择是否显示！',self::EXISTS_VALIDATE,'in'),  //存在验证，只能是Y是 N否
			array('img','1,255','图片路径不正确！',self::VALUE_VALIDATE,'length'),  //值不为空的时候验证 ，不超过255个字符
			array('pid','require','父级分类不能为空！',self::EXISTS_VALIDATE),  //存在验证，必填
			array('pid','is_natural_num','请选择正确的父级分类！',self::EXISTS_VALIDATE,'function'),  //存在验证，必须是自然数
			array('createtime','require','创建时间不能为空！',self::EXISTS_VALIDATE),  //存在验证，必填
			array('createtime','is_datetime','创建时间格式不正确！',self::EXISTS_VALIDATE,'function'),  //存在验证，必须是正确的时间格式
	);
	
	/**
	 * 获取顶级版块列表
	 * @param string $is_show:是否显示，Y显示、N不显示
	 * @return unknown|boolean
	 */
	public function getTopList($is_show='')
	{
		$where="pid=0";
		if($is_show)
		{
			$where.=" and is_show='$is_show'";
		}
		$list=$this->where($where)->field('board_id,board_name,keyword,description,img')->order('sort desc,board_id asc')->select();
		if($list!==false)
		{
			return $list;
		}else {
			return false;
		}
	}
	
	/**
	 * 根据版块ID获取子版块列表
	 * @param int $pid:父级版块ID
	 * @param string $is_show:是否显示，Y显示、N不显示
	 * @return array 子版块列表
	 */
	public function getSubList2($pid,$is_show='')
	{
		$where="pid='$pid'";
		if($is_show)
		{
			$where.=" and is_show='$is_show'";
		}
		$list=$this->where($where)->field('board_id,board_name,keyword,description,img')->order('sort desc,board_id asc')->select();
		if($list!==false)
		{
			return $list;
		}else {
			return false;
		}
	}
	
	/**
	 * 获取论坛版块列表
	 * @return array 版块列表
	 */
	public function getList()
	{
		$board=$this->order('sort desc')->select();
		$list =$this->rule($board);
		return $list;
	}
	
	/**
	 * 根据版块ID获取子版块列表
	 * @param int $id:版块ID
	 * @return array 子版块列表
	 */
	public function getSubList($board_id)
	{
		$board=$this->order('sort desc')->select();
		$sublist =$this->rule($board,'-',$board_id);
		return $sublist;
	}
	
	/**
	 * 获取版块信息
	 * @param int $id:版块ID
	 * @return array
	 */
	public function getBoardMsg($id)
	{
		$msg=$this->where("board_id=$id")->find();
		if($msg)
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
				$arr= array_merge($arr,self::rule($cate,$lefthtml,$v['board_id'],$lvl+1 , $leftpin+20));
			}
		}
		return $arr;
	}
}
?>