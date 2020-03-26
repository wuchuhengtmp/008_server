<?php
/**
 * 管理员组类
 */
namespace Admin\Model;
use Think\Model;

class AdminGroupModel extends Model
{
	/**
	 * 获取管理员组列表
	 * 不含超级管理员组
	 * @return array
	 */
	public function getGroupList()
	{
		$grouplist=$this->where('id!=1')->select();
		return $grouplist;
	}
	
	/**
	 * 获取管理员组列表
	 * 包含超级管理员组
	 * @return array
	 */
	public function getGroupList2()
	{
		$grouplist=$this->select();
		return $grouplist;
	}
	
	/**
	 * 获取管理员组信息
	 * @param int $id:管理员组ID
	 * @return array
	 */
	public function getGroupMsg($id)
	{
		$where=array(
				'id'=>$id
		);
		$res=$this->where($where)->find();
		if($res!==false)
		{
			return $res;
		}else {
			return false;
		}
	}
}