<?php
/**
 * 论坛文章评论管理
 */
namespace Common\Model;
use Think\Model;

class BbsArticleCommentModel extends Model
{
	//验证规则
	protected $_validate =array(
			array('article_id','require','评论的文章不能为空！',self::EXISTS_VALIDATE),  //存在验证，必填
			array('article_id','is_positive_int','请选择要评论的文章',self::EXISTS_VALIDATE,'function'),  //存在验证，必须是正整数
			array('content','require','评论内容不能为空！',self::EXISTS_VALIDATE),  //存在验证，必填
			array('content','1,2000','评论内容不超过2000个字符！',self::EXISTS_VALIDATE,'length'),  //存在验证，不超过2000个字符
			array('comment_time','require','评论时间不能为空！',self::EXISTS_VALIDATE),  //存在验证，必填
			array('comment_time','is_datetime','评论时间格式不正确！',self::EXISTS_VALIDATE,'function'),  //存在验证，必须是正确的时间格式
			array('comment_uid','require','评论用户不能为空！',self::EXISTS_VALIDATE),  //存在验证，必填
			array('comment_uid','is_positive_int','评论用户不存在',self::EXISTS_VALIDATE,'function'),  //存在验证，必须是正整数
			array('is_show','require','请选择是否显示！',self::EXISTS_VALIDATE),  //存在验证，必填
			array('is_show',array('Y','N'),'请选择是否显示！',self::EXISTS_VALIDATE,'in'),  //存在验证，只能是Y是 N否
	);
	
	/**
	 * 根据文章ID获取评论列表
	 * @param int $article_id
	 * @return array
	 */
	public function getCommentList($article_id)
	{
		$res=$this->where("article_id=$article_id")->select();
		if($res!==false)
		{
			return $res;
		}else {
			return false;
		}
	}
	
	/**
	 * 获取评论信息
	 * @param int $id:评论ID
	 * @return array
	 */
	public function getCommentMsg($id)
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
	 * 删除评论
	 * 同步删除评论下的回复
	 * @param int $id:评论ID
	 * @return boolean
	 */
	public function del($id)
	{
		$res=$this->where("id=$id")->delete();
		if($res!==false)
		{
			// 删除评论下的回复
			$BbsArticleReply=new \Common\Model\BbsArticleReplyModel();
			$res_r=$BbsArticleReply->where("comment_id=$id")->delete();
			if($res_r!==false)
			{
				return true;
			}else {
				return false;
			}
		}else {
			return false;
		}
	}
}