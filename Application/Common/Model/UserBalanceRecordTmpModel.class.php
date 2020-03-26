<?php
/**
 * 用户预估收益记录管理
 */
namespace Common\Model;
use Think\Model;

class UserBalanceRecordTmpModel extends Model
{
	//验证规则
	protected $_validate =array(
			
	);
	
	/**
	 * 生成用户余额变动记录
	 * @param int $user_id:用户ID
	 * @param float $money:变动金额
	 * @param string $action:操作类型
	 * @param string $order_id:订单号
	 * @param string $type:订单类型 1淘宝 2京东 3拼多多
	 * @param DateTime $create_time:订单下单时间
	 * @return boolean
	 */
	public function addLog($user_id,$money,$action,$order_id,$type='1',$create_time)
	{
		$data=array(
				'user_id'=>$user_id,
				'money'=>$money*100,
				'create_time'=>$create_time,
				'action'=>$action,
				'order_id'=>$order_id,
				'type'=>$type
		);
		if(!$this->create($data))
		{
			return false;
		}else {
			$res=$this->add($data);
			if($res!==false)
			{
				return true;
			}else {
				return false;
			}
		}
	}
}
?>