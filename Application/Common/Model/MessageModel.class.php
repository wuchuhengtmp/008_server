<?php
/**
 * 留言管理
 */
namespace Common\Model;
use Think\Model;

class MessageModel extends Model
{
	//验证规则
	protected $_validate =array(
			array('cat_id','require','留言分类名称不能为空！',self::EXISTS_VALIDATE),  //存在验证，必填
			array('cat_id','is_positive_int','请选择正确的留言分类',self::EXISTS_VALIDATE,'function'),  //存在验证，必须是正整数
			array('content','require','留言内容不能为空！',self::EXISTS_VALIDATE),  //存在验证，必填
			array('linkman','1,100','联系人不超过100字符！',self::VALUE_VALIDATE,'length'),  //值不为空的时候验证，不超过100个字符
			array('phone','1,100','联系方式不超过100字符！',self::VALUE_VALIDATE,'length'),  //值不为空的时候验证，不超过100个字符
			array('email','email','邮箱格式不正确！',self::VALUE_VALIDATE),  //值不为空的时候验证，必须是邮箱格式
			array('ip','require','IP地址不能为空！',self::EXISTS_VALIDATE),  //存在验证，必填
			array('ip','1,20','IP地址不超过20字符！',self::EXISTS_VALIDATE,'length'),  //值不为空的时候验证，不超过20个字符
			array('createtime','require','创建时间不能为空！',self::EXISTS_VALIDATE),  //存在验证，必填
			array('createtime','is_datetime','创建时间格式不正确！',self::EXISTS_VALIDATE,'function'),  //存在验证，必须是正确的时间格式
			array('is_show','require','请选择是否显示！',self::EXISTS_VALIDATE),  //存在验证，必填
			array('is_show',array('Y','N'),'请选择是否显示！',self::EXISTS_VALIDATE,'in'),  //存在验证，只能是Y是 N否
	);
	
	/**
	 * 获取留言列表
	 * @param int $cat_id:分类ID
	 * @return array|false
	 */
	public function getMessageList($cat_id)
	{
		$res=$this->where("cat_id=$cat_id")->order('createtime desc')->select();
		if($res!==false)
		{
			return $res;
		}else {
			return false;
		}
	}
	
	/**
	 * 获取留言信息
	 * @param int $id:留言ID
	 * @return array|false
	 */
	public function getMessageMsg($id)
	{
		$res=$this->where("id=$id")->find();
		if($res!==false)
		{
			return $res;
		}else {
			return false;
		}
	}
	
	/**
	 * 删除留言
	 * 同步删除站长回复内容
	 * @param int $id:留言ID
	 * @return number
	 */
	public function del($id)
	{
		$res=$this->where("id=$id")->delete();
		if($res)
		{
			//删除站长回复内容
			$MessageReply=new \Common\Model\MessageReplyModel();
			$res2=$MessageReply->where("message_id=$id")->delete();
			if($res2!==false)
			{
				return 1;
			}else {
				return 0;
			}
		}else {
			return 0;
		}
	}
}