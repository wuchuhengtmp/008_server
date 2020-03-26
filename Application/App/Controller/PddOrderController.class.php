<?php
/**
 * 拼多多订单管理接口
 */
namespace App\Controller;
use App\Common\Controller\AuthController;

class PddOrderController extends AuthController 
{
	/**
	 * 获取拼多多订单列表
	 * @param string $token:用户身份令牌
	 * @param string $order_sn:订单号
	 * @param string $type:订单类型，1本身（默认），2直接，3间接
	 * @param string $order_status:订单状态描述（ -1 未支付; 0-已支付；1-已成团；2-确认收货；3-审核成功；4-审核失败（不可提现）；5-已经结算；8-非多多进宝商品（无佣金订单）;10-已处罚）
	 * @param int $p:页码，默认第1页
	 * @param int $per:每页条数，默认6条
	 * @return array
	 * @return @param code:返回码
	 * @return @param msg:返回码说明
	 * @return @param data:返回数据
	 * @return @param data->list:拼多多订单列表
	 */
	public function getOrderList()
	{
		if(trim(I('post.token')))
		{
			//判断用户身份
			$token=trim(I('post.token'));
			$User=new \Common\Model\UserModel();
			$res_token=$User->checkToken($token);
			if($res_token['code']!=0)
			{
				//用户身份不合法
				$res=$res_token;
			}else {
				$uid=$res_token['uid'];
				$where='1';
				//订单号
				if(trim(I('post.order_sn'))!=='')
				{
					$order_sn=trim(I('post.order_sn'));
					$where.=" and order_sn='$order_sn'";
				}
				if(trim(I('post.order_status'))!=='')
				{
					$order_status=trim(I('post.order_status'));
					$where.=" and order_status='$order_status'";
				}
				if(trim(I('post.type')))
				{
					$type=trim(I('post.type'));
				}else {
					$type=1;
				}
				switch ($type)
				{
					//本身
					case '1':
						$where.=" and user_id='$uid'";
						break;
						//直接
					case '2':
						//获取团队列表-一级，过滤掉VIP
						$all_uid='';
						$referrerList=$User->where("referrer_id='$uid' and group_id in (1,2)")->field('uid')->select();
						if($referrerList)
						{
							foreach ($referrerList as $l)
							{
								$all_uid.=$l['uid'].',';
							}
						}
						if($all_uid)
						{
							//一级团队列表
							$all_uid=substr($all_uid, 0,-1);
							$where.=" and user_id in ($all_uid)";
						}else {
							$where='';
							$data=array(
									'list'=>array()
							);
							$res=array(
									'code'=>$this->ERROR_CODE_COMMON['SUCCESS'],
									'msg'=>'成功',
									'data'=>$data
							);
							echo json_encode ($res,JSON_UNESCAPED_UNICODE);
							exit();
						}
						break;
						//间接
					case '3':
						//获取团队列表-一级、二级
						$all_uid='';
						$referrerList=$User->where("referrer_id='$uid'")->field('uid')->select();
						if($referrerList)
						{
							foreach ($referrerList as $l)
							{
								$all_uid.=$l['uid'].',';
							}
						}
						if($all_uid)
						{
							//一级团队列表
							$all_uid=substr($all_uid, 0,-1);
							//二级团队列表，过滤掉VIP
							$all_uid2='';
							$referrerList2=$User->where("referrer_id in ($all_uid) and group_id in (1,2)")->field('uid')->select();
							if($referrerList2)
							{
								foreach ($referrerList2 as $l)
								{
									$all_uid2.=$l['uid'].',';
								}
								$all_uid2=substr($all_uid2, 0,-1);
								$where.=" and user_id in ($all_uid2)";
							}else {
								$where='';
								$data=array(
										'list'=>array()
								);
								$res=array(
										'code'=>$this->ERROR_CODE_COMMON['SUCCESS'],
										'msg'=>'成功',
										'data'=>$data
								);
								echo json_encode ($res,JSON_UNESCAPED_UNICODE);
								exit();
							}
						}else {
							$where='';
							$data=array(
									'list'=>array()
							);
							$res=array(
									'code'=>$this->ERROR_CODE_COMMON['SUCCESS'],
									'msg'=>'成功',
									'data'=>$data
							);
							echo json_encode ($res,JSON_UNESCAPED_UNICODE);
							exit();
						}
						break;
				
				}
				
				if(trim(I('post.p')))
				{
					$p=trim(I('post.p'));
				}else {
					$p=1;
				}
				if(trim(I('post.per')))
				{
					$per=trim(I('post.per'));
				}else {
					$per=6;
				}
				$PddOrder=new \Common\Model\PddOrderModel();
				$list=$PddOrder->where($where)->page($p,$per)->order("id desc")->select();
				if($list!==false)
				{
					$num=count($list);
					for($i=0;$i<$num;$i++)
					{
						//如果不是已结算订单，佣金要做用户所在的组获取相应收益比例扣除
						if($list[$i]['order_status']!='5')
						{
							//非结算订单
							$UserMsg=$User->getUserMsg($list[$i]['user_id']);
							//根据用户所在的组获取相应收益比例
							$UserGroup=new \Common\Model\UserGroupModel();
							$groupMsg=$UserGroup->getGroupMsg($UserMsg['group_id']);
							$list[$i]['promotion_amount']=$list[$i]['promotion_amount']*$groupMsg['fee_user']/100;
						}
					}
					$data=array(
							'list'=>$list
					);
					$res=array(
							'code'=>$this->ERROR_CODE_COMMON['SUCCESS'],
							'msg'=>'成功',
							'data'=>$data
					);
				}else {
					//数据库错误
					$res=array(
							'code'=>$this->ERROR_CODE_COMMON['DB_ERROR'],
							'msg'=>$this->ERROR_CODE_COMMON_ZH[$this->ERROR_CODE_COMMON['DB_ERROR']]
					);
				}
			}
		}else {
			//参数不正确，参数缺失
			$res=array(
					'code'=>$this->ERROR_CODE_COMMON['PARAMETER_ERROR'],
					'msg'=>$this->ERROR_CODE_COMMON_ZH[$this->ERROR_CODE_COMMON['PARAMETER_ERROR']]
			);
		}
		echo json_encode ($res,JSON_UNESCAPED_UNICODE);
	}
}
?>