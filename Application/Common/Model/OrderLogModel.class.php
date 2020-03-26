<?php
/**
 * 订单日志
 */
namespace Common\Model;
use Think\Model;

class OrderLogModel extends Model
{
	//验证规则
	protected $_validate =array(
			array('order_id','require','所属订单不能为空！',self::EXISTS_VALIDATE),  //存在验证，必填
			array('order_id','is_positive_int','请选择正确的订单',self::EXISTS_VALIDATE,'function'),  //存在验证，必须是正整数
			array('content','require','日志内容不能为空！',self::EXISTS_VALIDATE),  //存在验证，必填
			array('content','1,500','日志内容不超过500个字符！',self::EXISTS_VALIDATE,'length'),  //存在验证，不超过500个字符
			array('admin_id','require','操作管理员不能为空！',self::EXISTS_VALIDATE),  //存在验证，必填
			array('admin_id','is_positive_int','请选择正确的操作管理员',self::EXISTS_VALIDATE,'function'),  //存在验证，必须是正整数
			array('action','require','操作类型不能为空！',self::EXISTS_VALIDATE),  //存在验证，必填
			array('action','1,10','操作类型不正确！',self::EXISTS_VALIDATE,'length'),  //存在验证，不超过10个字符
			array('action_time','require','日志操作时间不能为空！',self::EXISTS_VALIDATE),  //存在验证，必填
			array('action_time','is_datetime','日志操作时间格式不正确！',self::EXISTS_VALIDATE,'function'),  //存在验证，必须是正确的时间格式
	);
	
	/**
	 * 保存日志
	 * @param int $order_onsite_id:上门维修订单ID
	 * @param string $log_content：日志内容
	 * @param string $action:操作类型 add新增、treat分配、retreat重新分配、finish完成、cancel取消、del删除
	 * @return boolean
	 */
	public function addLog($order_id,$log_content,$action)
	{
		$data=array(
				'order_id'=>$order_id,
				'content'=>$log_content,
				'admin_id'=>$_SESSION['admin_id'],
				'action'=>$action,
				'action_time'=>date('Y-m-d H:i:s'),
		);
		$res=$this->add($data);
		if($res!==false)
		{
			return true;
		}else {
			return false;
		}
	}
}
?>