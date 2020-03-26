<?php
/**
 * 用户余额变动记录管理
 */
namespace Common\Model;
use Think\Model;

class UserBalanceRecordModel extends Model
{
	//验证规则
	protected $_validate =array(
			array('order_num','require','订单号不能为空！',self::EXISTS_VALIDATE),  //存在验证，必填
			array('order_num','1,20','订单号不超过20个字符！',self::EXISTS_VALIDATE,'length'),  //存在验证，不超过20个字符		
			array('user_id','require','会员不能为空！',self::EXISTS_VALIDATE),  //存在验证，必填
			array('user_id','is_positive_int','会员不存在',self::EXISTS_VALIDATE,'function'),  //存在验证，必须是正整数
			array('money','require','金额不能为空！',self::EXISTS_VALIDATE),  //存在验证，必填
			array('money','is_natural_num','金额不是正确的货币格式！',self::EXISTS_VALIDATE,'function'),  //存在验证 ，必须是自然数	
			array('all_money','is_natural_num','余额存量不是正确的货币格式！',self::VALUE_VALIDATE,'function'),  //值不为空的时候验证，必须是自然数
			array('status',array(1,2),'订单状态不正确！',self::EXISTS_VALIDATE,'between'),  //存在验证，只能是1-2的状态值
			array('create_time','require','下单时间不能为空！',self::EXISTS_VALIDATE),  //存在验证，必填
			array('create_time','is_datetime','下单时间格式不正确！',self::EXISTS_VALIDATE,'function'),  //存在验证，必须是正确的时间格式
			array('pay_time','is_datetime','支付时间格式不正确！',self::VALUE_VALIDATE,'function'),  //值不为空的时候验证，必须是正确的时间格式
			//array('action',array('card','recharge','complete_info','draw','tbk','tbk_r','tbk_r2','tbk_rt','tbk_refund','tbk_free','pdd','pdd_r','pdd_r2','pdd_rt','jd','jd_r','jd_r2','jd_rt','recommend1','recommend2','recommend3','bonus','bonus2','signin'),'操作类型不正确！',self::VALUE_VALIDATE,'in'),  //存在验证，只能是card充值卡兑换 add充值 del消费
			array('pay_method',array('alipay','wxpay'),'支付方式类型不正确！',self::VALUE_VALIDATE,'in'),  //值不为空的时候验证，支付方式只能是 alipay支付宝 wxpay微信支付
	);
	
	/**
	 * 生成唯一订单号
	 * @return string:订单号
	 */
	public function generateOrderNum()
	{
		$num=uniqid().rand(100, 999);
		return $num;
	}
	
	/**
	 * 生成用户余额变动记录
	 * @param int $user_id:用户ID
	 * @param float $money:变动金额
	 * @param float $all_money:余额存量
	 * @param string $action:操作类型
	 * @param string $status:支付状态，1未付款、2已付款
	 * @param string $order_id:淘宝/京东/拼多多订单号
	 * @param string $type:订单类型 1淘宝 2京东 3拼多多 4唯品会
	 * @return boolean
	 */
	public function addLog($user_id,$money,$all_money,$action,$status='2',$order_id='',$type='')
	{
		$data=array(
				'order_num'=>$this->generateOrderNum(),
				'user_id'=>$user_id,
				'money'=>$money*100,
				'all_money'=>$all_money*100,
				'status'=>$status,
				'create_time'=>date('Y-m-d H:i:s'),
				'action'=>$action,
				'order_id'=>$order_id,
				'type'=>$type
		);
		if($status=='2') {
			$data['pay_time']=date('Y-m-d H:i:s');
		}
		if(!$this->create($data)) {
			return false;
		}else {
			$res=$this->add($data);
			if($res!==false) {
				return true;
			}else {
				return false;
			}
		}
	}
	
	/**
	 * 获取操作类型描述
	 * @param string $action:操作类型
	 * @return array
	 */
	public function getActionZh($action)
	{
	    switch ($action) {
	        case 'card':
	            $action_zh='充值卡兑换增加';
	            $action_symbol='+';
	            break;
	        case 'recharge':
	            $action_zh='在线充值';
	            $action_symbol='+';
	            break;
	        case 'complete_info':
	            $action_zh='完善资料获取';
	            $action_symbol='+';
	            break;
	        case 'tbk':
	            $action_zh='淘宝返利';
	            $action_symbol='+';
	            break;
	        case 'tbk_r':
	            $action_zh='淘宝一级返利';
	            $action_symbol='+';
	            break;
	        case 'tbk_r2':
	            $action_zh='淘宝二级返利';
	            $action_symbol='+';
	            break;
	        case 'tbk_rt':
	            $action_zh='淘宝团队返利';
	            $action_symbol='+';
	            break;
	        case 'tbk_refund':
	            $action_zh='淘宝订单维权退款扣除';
	            $action_symbol='-';
	            break;
	        case 'tbk_free':
	            $action_zh='淘宝0元购商品补贴';
	            $action_symbol='+';
	            break;
	        case 'pdd':
	            $action_zh='拼多多返利';
	            $action_symbol='+';
	            break;
	        case 'pdd_r':
	            $action_zh='拼多多一级返利';
	            $action_symbol='+';
	            break;
	        case 'pdd_r2':
	            $action_zh='拼多多二级返利';
	            $action_symbol='+';
	            break;
	        case 'pdd_rt':
	            $action_zh='拼多多团队返利';
	            $action_symbol='+';
	            break;
	        case 'jd':
	            $action_zh='京东返利';
	            $action_symbol='+';
	            break;
	        case 'jd_r':
	            $action_zh='京东一级返利';
	            $action_symbol='+';
	            break;
	        case 'jd_r2':
	            $action_zh='京东二级返利';
	            $action_symbol='+';
	            break;
	        case 'jd_rt':
	            $action_zh='京东团队返利';
	            $action_symbol='+';
	            break;
            case 'vip':
                $action_zh='唯品会返利';
                $action_symbol='+';
                break;
            case 'vip_r':
                $action_zh='唯品会一级返利';
                $action_symbol='+';
                break;
            case 'vip_r2':
                $action_zh='唯品会二级返利';
                $action_symbol='+';
                break;
            case 'vip_rt':
                $action_zh='唯品会团队返利';
                $action_symbol='+';
                break;
	        case 'draw':
	            $action_zh='提现';
	            $action_symbol='-';
	            break;
	        case 'recommend1':
	            $action_zh='一级推荐返利';
	            $action_symbol='+';
	            break;
	        case 'recommend2':
	            $action_zh='二级推荐返利';
	            $action_symbol='+';
	            break;
	        case 'recommend3':
	            $action_zh='三级推荐返利';
	            $action_symbol='+';
	            break;
	        case 'bonus':
	            $action_zh='春节红包';
	            $action_symbol='+';
	            break;
	        case 'bonus2':
	            $action_zh='新人红包';
	            $action_symbol='+';
	            break;
	        case 'signin':
	            $action_zh='签到奖励';
	            $action_symbol='+';
	            break;
	        case 'goods_back':
	            $action_zh='自营商城退款';
	            $action_symbol='+';
	            break;
	        default:
	            $action_zh='';
	            $action_symbol='';
	            break;
	    }
	    return array(
	        'action_zh'=>$action_zh,
	        'action_symbol'=>$action_symbol
	    );
	}
	
	/**
	 * 获取会员余额变动记录
	 * @param int $user_id:会员ID
	 * @param string $status:状态 1未付款 2已付款
	 * @return array|boolean
	 */
	public function getRecordList($user_id,$status='')
	{
		$where="user_id='$user_id'";
		if($status) {
			$where.=" and status='$status'";
		}
		$list=$this->where($where)->order("id desc")->select();
		if($list!==false) {
			$num=count($list);
			for ($i=0;$i<$num;$i++)
			{
				$list[$i]['money']=$list[$i]['money']/100;
				$list[$i]['all_money']=$list[$i]['all_money']/100;
				//保留2位小数，四舍五不入
				$list[$i]['all_money']=substr(sprintf("%.3f",$list[$i]['all_money']),0,-1);
				//获取操作类型描述
				$res_action=$this->getActionZh($list[$i]['action']);
				$list[$i]['action_zh']=$res_action['action_zh'];
				$list[$i]['action_symbol']=$res_action['action_symbol'];
			}
			return $list;
		}else {
			return false;
		}
	}
	
	/**
	 * 获取分页会员余额变动记录列表
	 * @param int $user_id:会员ID
	 * @param string $status:状态 1未付款 2已付款
	 * @param string $order:排序规则 desc降序 asc升序
	 * @param int $p:当前页码，默认1
	 * @param int $per:每页显示条数，默认15
	 * @return array|boolean
	 * @return array $list:会员余额变动记录列表
	 * @return array $page:分页条
	 */
	public function getRecordListByPage($user_id,$status='',$order='desc',$p=1,$per=15)
	{
		//列表
		$where="user_id='$user_id'";
		if($status) {
			$where.=" and status='$status'";
		}
		$list = $this->where($where)->page($p.','.$per)->order("id $order")->select();
		if($list!==false) {
			$num=count($list);
			for ($i=0;$i<$num;$i++) {
				//变动金额
				$list[$i]['money']=$list[$i]['money']/100;
				//保留2位小数，四舍五不入
				$list[$i]['money']=substr(sprintf("%.3f",$list[$i]['money']),0,-1);
				//当前余额
				$list[$i]['all_money']=$list[$i]['all_money']/100;
				//保留2位小数，四舍五不入
				$list[$i]['all_money']=substr(sprintf("%.3f",$list[$i]['all_money']),0,-1);
				//获取操作类型描述
				$res_action=$this->getActionZh($list[$i]['action']);
				$list[$i]['action_zh']=$res_action['action_zh'];
				$list[$i]['action_symbol']=$res_action['action_symbol'];
			}
			//总数
			$count=$this->where($where)->count();
			//分页
			$Page=new \Common\Model\PageModel();
			$show=$Page->show($count, $per);
			$content=array(
					'list'=>$list,
					'page'=>$show
			);
			return $content;
		}else {
			return false;
		}
	}
	
	/**
	 * 获取余额订单信息
	 * @param int $id:订单ID
	 * @return array|boolean
	 */
	public function getOrderMsg($id)
	{
		$msg=$this->where("id='$id'")->find();
		if($msg)
		{
			$msg['money']=$msg['money']/100;
			$msg['all_money']=$msg['all_money']/100;
			return $msg;
		}else {
			return false;
		}
	}
	
	/**
	 * 根据订单号获取余额订单信息
	 * @param string $order_num:订单号
	 * @return array|boolean
	 */
	public function getOrderMsgByOrderNum($order_num)
	{
		$msg=$this->where("order_num='$order_num'")->find();
		if($msg)
		{
			$msg['money']=$msg['money']/100;
			$msg['all_money']=$msg['all_money']/100;
			return $msg;
		}else {
			return false;
		}
	}
	
	/**
	 * 处理已支付订单
	 * @param string $order_num:订单号
	 * @param string $pay_method:支付方式，wxpay微信支付、alipay支付宝支付
	 * @return boolean
	 */
	public function treatOrder($order_num,$pay_method)
	{
		$msg=$this->getOrderMsgByOrderNum($order_num);
		if($msg) {
			if($msg['status']=='1') {
				$uid=$msg['user_id'];
				$money=$msg['money'];
				$User=new \Common\Model\UserModel();
				$userMsg=$User->getUserMsg($uid);
				$all_money=$userMsg['balance']+$money;
				//只有未付款订单可以处理
				$data=array(
						'all_money'=>$all_money*100,//当前余额需要重新计算
						'status'=>'2',
						'pay_time'=>date('Y-m-d H:i:s'),
						'pay_method'=>$pay_method
				);
				if(!$this->create($data))
				{
					//验证不通过
					return false;
				}else {
					//开启事务
					$this->startTrans();
					$res=$this->where("order_num='$order_num'")->save($data);
					//增加用户余额
					$res_balance=$User->where("uid='$uid'")->setInc('balance',$money);
					if($res!==false and $res_balance!==false)
					{
						//提交事务
						$this->commit();
						return true;
					}else {
						//回滚
						$this->rollback();
						return false;
					}
				}
			}else {
				return false;
			}
		}else {
			return false;
		}
	}
}
?>