<?php
/**
 * 拉新活动
 */
namespace Common\Model;
use Think\Model;

class RookieUserModel extends Model
{
    // 验证规则
    protected $_validate = array(
        array('user_id','require','用户ID不能为空'), // 存在验证，必填
        array('rid','require','活动ID不能为空'), // 存在验证，必填
        array('exchange','is_datetime','兑换时间格式不正确',2), // 值不为空验证，等级必须为正整数
        array('num','is_natural_num','拉新人数必须为正整数',2), // 值不为空验证，拉新人数必须为正整数
        array('is_ex',array('Y','N'),'请选择是否兑换',1,'in'), // 存在时验证，等级必须为正整数
    );
}