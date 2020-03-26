<?php
/**
 * 淘宝订单兑现管理接口
 */
namespace App\Controller;
use App\Common\Controller\AuthController;

class TaobaoOrderController extends AuthController 
{
	/**
	 * 申请淘宝订单佣金
	 * @param string $token:用户身份令牌
	 * @param string $order_num:淘宝订单号
	 * @return array
	 * @return @param code:返回码
	 * @return @param msg:返回码说明
	 */
	public function apply()
	{
		if(trim(I('post.token')) and trim(I('post.order_num')))
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
				$order_num=trim(I('post.order_num'));
				//判断该订单是否已兑现
				$TaobaoOrder=new \Common\Model\TaobaoOrderModel();
				//已经审核通过了的或者用户自己的订单，不准重复申请
				$res_exist=$TaobaoOrder->where("order_num='$order_num' and (check_result='Y' or user_id='$uid')")->find();
				if($res_exist)
				{
					//该订单已被兑换/已申请兑换，请勿重复申请
					$res=array(
							'code'=>$this->ERROR_CODE_GOODS['ORDER_HAS_BEEN_CHANGED_DO_NOT_APPLY_AGAIN'],
							'msg'=>$this->ERROR_CODE_GOODS_ZH[$this->ERROR_CODE_GOODS['ORDER_HAS_BEEN_CHANGED_DO_NOT_APPLY_AGAIN']]
					);
				}else {
					$data=array(
							'user_id'=>$uid,
							'order_num'=>$order_num,
							'apply_time'=>date('Y-m-d H:i:s'),
							'is_check'=>'N'
					);
					if(!$TaobaoOrder->create($data))
					{
						//验证不通过
						$res=array(
								'code'=>$this->ERROR_CODE_COMMON['PARAMETER_FORMAT_ERROR'],
								'msg'=>$TaobaoOrder->getError()
						);
					}else {
						$res_add=$TaobaoOrder->add($data);
						if($res_add!==false)
						{
							$res=array(
									'code'=>$this->ERROR_CODE_COMMON['SUCCESS'],
									'msg'=>'申请佣金成功，请耐心等待管理员审核！'
							);
						}else {
							//数据库错误
							$res=array(
									'code'=>$this->ERROR_CODE_COMMON['DB_ERROR'],
									'msg'=>$this->ERROR_CODE_COMMON_ZH[$this->ERROR_CODE_COMMON['DB_ERROR']]
							);
						}
					}
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
	
	/**
	 * 获取淘宝订单列表
	 * @param string $token:用户身份令牌
	 * @param string $status:状态 1待审核 2审核不通过 3已返利
	 * @param int $p:页码，默认第1页
	 * @param int $per:每页条数，默认6条
	 * @return array
	 * @return @param code:返回码
	 * @return @param msg:返回码说明
	 * @return @param data:返回数据
	 * @return @param data->list:淘宝订单列表
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
				$where="user_id='$uid'";
				$status=trim(I('post.status'));
				if($status)
				{
					switch ($status)
					{
						//待审核
						case '1':
							$where.=" and is_check='N'";
							break;
						//审核不通过
						case '2':
							$where.=" and is_check='Y' and check_result='N'";
							break;
						//已返利
						case '3':
							$where.=" and is_check='Y' and check_result='Y'";
							break;
					}
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
				$TaobaoOrder=new \Common\Model\TaobaoOrderModel();
				$list=$TaobaoOrder->where($where)->page($p,$per)->order("id desc")->select();
				if($list!==false)
				{
					$num=count($list);
					for($i=0;$i<$num;$i++)
					{
						if($list[$i]['is_check']=='N')
						{
							$status_str='1';
						}else if($list[$i]['check_result']=='N'){
							$status_str='2';
						}else {
							$status_str='3';
						}
						$list[$i]['status']=$status_str;
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
	
	/**
	 * 获取淘宝订单列表（新）
	 * @param string $token:用户身份令牌
	 * @param string $trade_id:淘宝订单号
	 * @param string $type:订单类型，1本身（默认），2直接，3间接
	 * @param string $tk_status:淘客订单状态，3：订单结算，12：订单付款， 13：订单失效，14：订单成功，默认空值，获取全部
	 * @param int $p:页码，默认第1页
	 * @param int $per:每页条数，默认6条
	 * @return array
	 * @return @param code:返回码
	 * @return @param msg:返回码说明
	 * @return @param data:返回数据
	 * @return @param data->list:淘宝订单列表
	 */
	public function getOrderList_new()
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
				if(trim(I('post.trade_id')))
				{
					$trade_id=trim(I('post.trade_id'));
					$where.=" and (trade_id='$trade_id' or trade_parent_id='$trade_id')";
				}
				if(trim(I('post.tk_status')))
				{
					$tk_status=trim(I('post.tk_status'));
					$where.=" and tk_status='$tk_status'";
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
				$TbOrder=new \Common\Model\TbOrderModel();
				$list=$TbOrder->where($where)->field('num_iid,trade_id,item_title,item_num,seller_shop_title,pub_share_pre_fee,commission,commission_rate,create_time,earning_time,tk_status,order_type,alipay_total_price,total_commission_fee,total_commission_rate')->page($p,$per)->order("id desc")->select();
				if($list!==false)
				{
					$num=count($list);
					//查询商品图片
					Vendor('tbk.tbk','','.class.php');
					$tbk=new \tbk();
					
					$UserMsg=$User->getUserMsg($uid);
					//根据用户所在的组获取相应收益比例
					$UserGroup=new \Common\Model\UserGroupModel();
					$groupMsg=$UserGroup->getGroupMsg($UserMsg['group_id']);
					for($i=0;$i<$num;$i++)
					{
						//佣金、佣金比率
						if($type!='1')
						{
							//直推、间推订单佣金X50%
							if(empty($list[$i]['commission']) or $list[$i]['commission']=='0.00' or $list[$i]['commission']==$list[$i]['total_commission_fee'])
							{
								$list[$i]['commission']=$list[$i]['pub_share_pre_fee'];
								$list[$i]['commission']*=0.5;
							}
						}else {
							//自己订单
							if(empty($list[$i]['commission']) or $list[$i]['commission']=='0.00' or $list[$i]['commission']==$list[$i]['total_commission_fee'])
							{
								$list[$i]['commission']=$list[$i]['pub_share_pre_fee']*$groupMsg['fee_user']/100;
							}
						}
						//四舍五入
						$list[$i]['commission']=round($list[$i]['commission'], 2);
						//佣金比率
						$list[$i]['commission_rate']=($list[$i]['pub_share_pre_fee']/$list[$i]['alipay_total_price'])*100;
						$list[$i]['commission_rate']=substr(sprintf("%.3f",$list[$i]['commission_rate']),0,-1);
							
						$num_iid=$list[$i]['num_iid'];
						$res_goods=$tbk->getItemInfo($num_iid,'2','');
						if($res_goods['code']==0)
						{
							$list[$i]['goods_img']=$res_goods['data']['pict_url'];
						}else {
							/* $res=$res_goods;
							echo json_encode ($res,JSON_UNESCAPED_UNICODE);
							exit(); */
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