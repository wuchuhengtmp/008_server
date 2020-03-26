<?php
/**
 * 后台权限管理
 */
namespace Admin\Model;
use Think\Model;

class AuthRuleModel extends Model
{
	/**
	 * 获取权限列表
	 * @return array
	 */
	public function getRuleList()
	{
		$rule=$this->order('sort desc,id asc')->select();
		$rule_arr =$this->rule($rule);
		return $rule_arr;
	}
	
	/**
	 * 获取认证规则信息
	 * @param int $id:权限ID
	 * @return 成功返回规则记录，失败返回false
	 */
	public function getRuleMsg($id)
	{
		$where=array(
			'id'=>$id
		);
		$res=$this->where($where)->find();
		if($res)
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
			if($v['pid']==$pid)
			{
				$v['lvl']=$lvl + 1;
				$v['leftpin']=$leftpin + 0;//左边距
				$v['lefthtml']=str_repeat($lefthtml,$lvl);
				$arr[]=$v;
				$arr= array_merge($arr,self::rule($cate,$lefthtml,$v['id'],$lvl+1 , $leftpin+20));
			}
		}
		return $arr;
	}
}