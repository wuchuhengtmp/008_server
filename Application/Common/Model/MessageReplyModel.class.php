<?php
/**
 * 留言管理
 */
namespace Common\Model;
use Think\Model;

class MessageReplyModel extends Model
{
	//验证规则
	protected $_validate =array(
			array('message_id','require','请选择正确的留言进行回复！',self::EXISTS_VALIDATE),  //存在验证，必填
			array('message_id','is_positive_int','请选择正确的留言进行回复',self::EXISTS_VALIDATE,'function'),  //存在验证，必须是正整数
			array('content','require','留言内容不能为空！',self::EXISTS_VALIDATE),  //存在验证，必填
			array('reply_time','require','回复时间不能为空！',self::EXISTS_VALIDATE),  //存在验证，必填
			array('reply_time','is_datetime','回复时间格式不正确！',self::EXISTS_VALIDATE,'function'),  //存在验证，必须是正确的时间格式
	);
}