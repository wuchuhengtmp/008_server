<?php
/**
 * 京东订单管理接口
 */
namespace App\Controller;
use App\Common\Controller\AuthController;

class JingdongOrderController extends AuthController 
{
	/**
	 * 获取京东订单列表
	 * @param string $token:用户身份令牌
	 * @param string $order_sn:订单号
	 * @param string $type:订单类型，1本身（默认），2直接，3间接
	 * @param string $order_status:订单状态描述（-1：未知,2.无效-拆单,3.无效-取消,4.无效-京东帮帮主订单,5.无效-账号异常,6.无效-赠品类目不返佣,7.无效-校园订单,8.无效-企业订单,9.无效-团购订单,10.无效-开增值税专用发票订单,11.无效-乡村推广员下单,12.无效-自己推广自己下单,13.无效-违规订单,14.无效-来源与备案网址不符,15.待付款,16.已付款,17.已完成,18.已结算）
	 * @param int $p:页码，默认第1页
	 * @param int $per:每页条数，默认6条
	 * @return array
	 * @return @param code:返回码
	 * @return @param msg:返回码说明
	 * @return @param data:返回数据
	 * @return @param data->list:京东订单列表
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
					$JingdongOrder=new \Common\Model\JingdongOrderModel();
					$res_o=$JingdongOrder->where("orderId='$order_sn' or parentId='$order_sn'")->select();
					if($res_o!==false)
					{
						$order_allid='';
						foreach ($res_o as $l)
						{
							$order_allid.=$l['id'].',';
						}
						if($order_allid)
						{
							$order_allid=substr($order_allid, 0,-1);
							$where.=" and order_id in ($order_allid)";
						}else {
							$data=array(
									'list'=>array()
							);
							$res=array(
									'code'=>1,
									'msg'=>'查询的订单号不存在',
									'data'=>$data
							);
							echo json_encode ($res,JSON_UNESCAPED_UNICODE);
							exit();
						}
					}else {
						$data=array(
								'list'=>array()
						);
						$res=array(
								'code'=>1,
								'msg'=>'查询的订单号不存在',
								'data'=>$data
						);
						echo json_encode ($res,JSON_UNESCAPED_UNICODE);
						exit();
					}
				}
				if(trim(I('post.order_status'))!=='')
				{
					$order_status=trim(I('post.order_status'));
					$where.=" and validCode='$order_status'";
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
				$JingdongOrderDetail=new \Common\Model\JingdongOrderDetailModel();
				$list2=$JingdongOrderDetail->where($where)->field('id,user_id,order_id,actualCosPrice,actualFee,commissionRate,estimateCosPrice,estimateFee,payPrice,skuId,skuName,validCode,commission,orderTime,price,skuNum')->page($p,$per)->order("id desc")->select();
				if($list2!==false)
				{
					$list=array();
					$UserGroup=new \Common\Model\UserGroupModel();
					$JingdongOrder=new \Common\Model\JingdongOrderModel();
					foreach ($list2 as $l)
					{
					    //查看订单号
					    $order_id=$l['order_id'];
					    $pMsg=$JingdongOrder->where("id='$order_id'")->find();
						//付款金额-预估计佣金额
						/* if($l['payprice'] and $l['payprice']!='0.00')
						{
							$payPrice=$l['payprice'];
						}else {
							$payPrice=$l['price']*$l['skunum'];
						} */
						$payPrice=$l['estimatecosprice'];
						//佣金
						if($l['commission']>0)
						{
							$commission=$l['commission'];
						}else {
							//如果不是已结算订单，佣金要做用户所在的组获取相应收益比例扣除
							if($l['validcode']!='18')
							{
								//非结算订单
								$UserMsg=$User->getUserMsg($l['user_id']);
								//根据用户所在的组获取相应收益比例
								$groupMsg=$UserGroup->getGroupMsg($UserMsg['group_id']);
								$commission=$l['estimatefee']*$groupMsg['fee_user']/100;
							}
						}
						//商品图片
						$goods_id=$l['skuid'];
						$url="http://api.josapi.net/iteminfo?skuids=$goods_id";
						$res_json=https_request($url);
						$res_g=json_decode($res_json,true);
						$goods_img=$res_g['data'][0]['imgUrl'];
						
						$list[]=array(
								'skuid'=>$goods_id,//商品ID
								'goods_name'=>$l['skuname'],//商品名称
								'goods_img'=>$goods_img,//商品图片
								'order_time'=>$l['ordertime'],//下单时间
								'pay_price'=>$payPrice,//付款金额
								'commission'=>$commission,//佣金
								'commissionRate'=>$l['commissionrate'],//佣金比例
								'order_status'=>$l['validcode'],//订单状态
								'order_sn'=>$pMsg['orderid']//订单号
						);
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