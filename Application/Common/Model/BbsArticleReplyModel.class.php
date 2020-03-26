<?php
/**
 * 论坛文章评论回复管理
 */
namespace Common\Model;
use Think\Model;

class BbsArticleReplyModel extends Model
{
	//验证规则
	protected $_validate =array(
			array('comment_id','require','请选择要回复的评论！',self::EXISTS_VALIDATE),  //存在验证，必填
			array('comment_id','is_positive_int','请选择要回复的评论',self::EXISTS_VALIDATE,'function'),  //存在验证，必须是正整数
			array('content','require','回复内容不能为空！',self::EXISTS_VALIDATE),  //存在验证，必填
			array('content','1,2000','回复内容不超过2000个字符！',self::EXISTS_VALIDATE,'length'),  //存在验证，不超过2000个字符
			array('reply_time','require','回复时间不能为空！',self::EXISTS_VALIDATE),  //存在验证，必填
			array('reply_time','is_datetime','回复时间格式不正确！',self::EXISTS_VALIDATE,'function'),  //存在验证，必须是正确的时间格式
			array('comment_uid','require','评论用户不能为空！',self::EXISTS_VALIDATE),  //存在验证，必填
			array('comment_uid','is_positive_int','评论用户不存在',self::EXISTS_VALIDATE,'function'),  //存在验证，必须是正整数
			array('reply_uid','require','回复用户不能为空！',self::EXISTS_VALIDATE),  //存在验证，必填
			array('reply_uid','is_positive_int','回复用户不存在',self::EXISTS_VALIDATE,'function'),  //存在验证，必须是正整数
			array('is_show','require','请选择是否显示！',self::EXISTS_VALIDATE),  //存在验证，必填
			array('is_show',array('Y','N'),'请选择是否显示！',self::EXISTS_VALIDATE,'in'),  //存在验证，只能是Y是 N否
	);
	
	/**
	 * 根据评论ID获取回复列表
	 * @param int $comment_id
	 * @return array
	 */
	public function getList($comment_id)
	{
		$res=$this->where("comment_id=$comment_id")->select();
		if($res!==false)
		{
			return $res;
		}else {
			return false;
		}
	}
	
	/**
	 * 获取回复信息
	 * @param int $id:回复ID
	 * @return array
	 */
	public function getReplyMsg($id)
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
	 * 删除回复
	 * @param int $id:回复ID
	 * @return boolean
	 */
	public function del($id)
	{
		$res=$this->where("id=$id")->delete();
		if($res!==false)
		{
			return true;
		}else {
			return false;
		}
	}
}