<?php
/**
 * 订单管理类
 */
namespace Common\Model;
use Think\Model;

class OrderModel extends Model
{
	//验证规则
	protected $_validate =array(
			array('user_id','require','购买用户不能为空！',self::EXISTS_VALIDATE),  //存在验证，必填
			array('user_id','is_positive_int','购买用户不存在',self::EXISTS_VALIDATE,'function'),  //存在验证，必须是正整数
			array('order_num','require','订单号不能为空！',self::EXISTS_VALIDATE),  //存在验证，必填
			array('order_num','1,30','订单号不超过30个字符！',self::EXISTS_VALIDATE,'length'),  //存在验证，不超过30个字符
			array('title','1,200','订单名称不超过200个字符！',self::VALUE_VALIDATE,'length'),  //值不为空的时候验证 ，不超过200个字符
			array('allprice','require','订单总价不能为空！',self::EXISTS_VALIDATE),  //存在验证，必填
			array('allprice','is_natural_num','订单总价不是正确的货币格式！',self::EXISTS_VALIDATE,'function'),  //存在验证 ，必须是货币格式
			array('give_point','is_natural_num','赠送积分必须为不小于零的整数！',self::VALUE_VALIDATE,'function'),  //值不为空的时候验证 ，必须是自然数
			array('address','1,200','收货地址不超过200个字符！',self::VALUE_VALIDATE,'length'),  //值不为空的时候验证 ，不超过200个字符
			array('company','1,200','收件人单位名称不超过100个字符！',self::VALUE_VALIDATE,'length'),  //值不为空的时候验证 ，不超过100个字符
			array('consignee','1,30','收件人不超过30个字符！',self::VALUE_VALIDATE,'length'),  //值不为空的时候验证 ，不超过30个字符
			array('contact_number','1,30','联系电话不超过30个字符！',self::VALUE_VALIDATE,'length'),  //值不为空的时候验证 ，不超过30个字符
			array('postcode','1,10','邮政编码不超过10个字符！',self::VALUE_VALIDATE,'length'),  //值不为空的时候验证 ，不超过10个字符
			array('logistics',array(1,18),'快递公司不存在！',self::EXISTS_VALIDATE,'between'),  //存在验证，只能是1-18的状态值
			array('express_number','1,20','快递单号不超过20个字符！',self::VALUE_VALIDATE,'length'),  //值不为空的时候验证 ，不超过20个字符
			array('remark','1,200','备注不超过200个字符！',self::VALUE_VALIDATE,'length'),  //值不为空的时候验证 ，不超过200个字符
			array('status',array(1,8),'订单状态不正确！',self::EXISTS_VALIDATE,'between'),  //存在验证，只能是1-8的状态值
			array('create_time','require','下单时间不能为空！',self::EXISTS_VALIDATE),  //存在验证，必填
			array('create_time','is_datetime','下单时间格式不正确！',self::EXISTS_VALIDATE,'function'),  //存在验证，必须是正确的时间格式
			array('pay_time','is_datetime','订单支付时间格式不正确！',self::VALUE_VALIDATE,'function'),  //值不为空的时候验证，必须是正确的时间格式
			array('deliver_time','is_datetime','发货时间格式不正确！',self::VALUE_VALIDATE,'function'),  //值不为空的时候验证，必须是正确的时间格式
			array('finish_time','is_datetime','确认收货时间格式不正确！',self::VALUE_VALIDATE,'function'),  //值不为空的时候验证，必须是正确的时间格式
			array('comment_time','is_datetime','订单评论时间格式不正确！',self::VALUE_VALIDATE,'function'),  //值不为空的时候验证，必须是正确的时间格式
			array('refund_time','is_datetime','申请退款时间格式不正确！',self::VALUE_VALIDATE,'function'),  //值不为空的时候验证，必须是正确的时间格式
			array('refund_success_time','is_datetime','退款成功时间格式不正确！',self::VALUE_VALIDATE,'function'),  //值不为空的时候验证，必须是正确的时间格式
			array('refund_fail_time','is_datetime','拒绝退款时间格式不正确！',self::VALUE_VALIDATE,'function'),  //值不为空的时候验证，必须是正确的时间格式
			array('pay_method',array('alipay','wxpay','balance','point','offline'),'支付方式类型不正确！',self::VALUE_VALIDATE,'in'),  //值不为空的时候验证，支付方式只能是 alipay支付宝 wxpay微信支付 balance余额支付 point积分抵用 offline线下支付
			array('point','is_natural_num','抵用积分数必须是不小于零的整数',self::VALUE_VALIDATE,'function'),  //值不为空的时候验证，必须是自然数		
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
	 * 获取订单状态
	 * @param int $status:订单状态
	 * @return string
	 */
	public function getStatusZh($status)
	{
	    switch ($status) {
	        case '1':
	            $status_zh='未付款';
	            break;
	        case '2':
	            $status_zh='已付款、待发货';
	            break;
	        case '3':
	            $status_zh='已发货，待收货';
	            break;
	        case '4':
	            $status_zh='待评价';
	            break;
	        case '5':
	            $status_zh='已完成';
	            break;
	        case '6':
	            $status_zh='申请退款';
	            break;
	        case '7':
	            $status_zh='退款成功';
	            break;
	        case '8':
	            $status_zh='拒绝退款';
	            break;
	        default:
	            $status_zh='';
	    }
	    return $status_zh;
	}
	
	/**
	 * 获取订单信息
	 * @param int $id:订单ID
	 * @return array|false
	 */
	public function getOrderMsg($id)
	{
		$msg=$this->where("id=$id")->find();
		if($msg) {
			//订单总价
			$msg['allprice']=$msg['allprice']/100;
			//订单状态
			$msg['status_zh']=$this->getStatusZh($msg['status']);
			return $msg;
		}else {
			return false;
		}
	}
	
	/**
	 * 获取订单详情
	 * @param int $id:订单ID
	 * @return array|false
	 */
	public function getOrderDetail($id)
	{
		$msg=$this->getOrderMsg($id);
		if($msg!==false) {
			$OrderDetail=new \Common\Model\OrderDetailModel();
			$detail=$OrderDetail->getOrderDetail($id);
			$msg['detail']=$detail;
			return $msg;
		}else {
			return false;
		}
	}
	
	/**
	 * 根据订单号获取订单信息
	 * @param int $order_num:订单号
	 * @return array|false
	 */
	public function getOrderMsgByOrderNum($order_num)
	{
		$msg=$this->where("order_num='$order_num'")->find();
		if($msg) {
			//订单总价
			$msg['allprice']=$msg['allprice']/100;
			//订单状态
			$msg['status_zh']=$this->getStatusZh($msg['status']);
			return $msg;
		}else {
			return false;
		}
	}
	
	/**
	 * 根据订单号获取订单详情
	 * @param int $order_num:订单号
	 * @return array|false
	 */
	public function getOrderDetailByOrderNum($order_num)
	{
		$msg=$this->getOrderMsgByOrderNum($order_num);
		if($msg) {
			$OrderDetail=new \Common\Model\OrderDetailModel();
			$detail=$OrderDetail->getOrderDetail($msg['id']);
			$msg['detail']=$detail;
			return $msg;
		}else {
			return false;
		}
	}
	
	/**
	 * 生成消费码
	 * 当天日期加上第几个订单数字
	 * @return string:消费码
	 */
	public function generateConsumerCode()
	{
		//计算当天有多少订单
		$today=date('Ymd');
		$num=$this->where("TO_DAYS(create_time) = TO_DAYS(NOW())")->count();
		$code=$today.($num+1);
		return $code;
	}
	
	/**
	 * 获取用户订单列表
	 * @param int $user_id:用户ID
	 * @param int $p:页码，默认第1页
	 * @param int $per:每页条数，默认6条
	 * @return array|boolean
	 */
	public function getOrderListByUid($user_id,$status='',$p=1,$per=6)
	{
	    $where="user_id='$user_id'";
		if($status) {
			$where.=" and status='$status'";
		}
		$list=$this->where($where)->order('id desc')->page($p,$per)->select();
		if($list!==false) {
			//获取订单详情
			$num=count($list);
			$OrderDetail=new \Common\Model\OrderDetailModel();
			for($i=0;$i<$num;$i++) {
			    //订单总价
			    $list[$i]['allprice']=$list[$i]['allprice']/100;
				$detailList=$OrderDetail->getOrderDetail($list[$i]['id']);
				$list[$i]['detail']=$detailList;
			}
			return $list;
		}else {
			return false;
		}
	}
	
	/**
	 * 获取同一订单下订单列表
	 * @param string $order_num:订单号
	 * @return array|boolean
	 */
	public function getOrderListByOrderNum($order_num)
	{
		$list=$this->where("order_num='$order_num'")->select();
		if($list!==false) {
			return $list;
		}else {
			return false;
		}
	}
	
	/**
	 * 获取订单总价
	 * @param string $order_num:订单号
	 * @return number|boolean
	 */
	public function calculateOrderPrice($order_num)
	{
		$allprice=$this->where("order_num='$order_num'")->sum('allprice');
		if($allprice!==false) {
			return $allprice;
		}else {
			return false;
		}
	}
	
	/**
	 * 获取用户订单统计
	 * @param int $user_id:用户ID
	 * @return multitype:unknown
	 */
	public function statistics($user_id)
	{
		$sql="SELECT count(id) as num,status FROM __PREFIX__order WHERE user_id='$user_id' GROUP BY status";
		$list=M()->query($sql);
		$num1=$num2=$num3=$num4=$num5=$num6=$num7=$num8=0;
		for ($i=1;$i<=8;$i++) {
			$num_str='num'.$i;
			foreach ($list as $l)
			{
				if($l['status']==$i)
				{
					$$num_str=(int)$l['num'];
				}
			}
			//总数
			$allnum+=$$num_str;
		}
		//退换货数量
		$num_refund=$num6+$num7+$num8;
		$res=array(
				'allnum'=>$allnum,
				'num1'=>$num1,
				'num2'=>$num2,
				'num3'=>$num3,
				'num4'=>$num4,
				'num5'=>$num5,
				'num6'=>$num6,
				'num7'=>$num7,
				'num8'=>$num8,
				'num_refund'=>$num_refund
		);
		return $res;
	}
	
	/**
	 * 处理已付款订单
	 * 扣除抵扣积分
	 * @param string $order_num:订单号
	 * @param string $pay_method:支付方式，wxpay微信支付、alipay支付宝支付、balance余额支付、offline线下支付
	 * @return boolean
	 */
	public function treatOrder($order_num,$pay_method)
	{
		$msg=$this->getOrderDetailByOrderNum($order_num);
		if($msg) {
			if($msg['status']=='1') {
				//只有未付款订单可以处理
				$data=array(
						'status'=>'2',//已付款
						'pay_time'=>date('Y-m-d H:i:s'),
						'pay_method'=>$pay_method
				);
				if(!$this->create($data)) {
					//验证不通过
					return false;
				}else {
					//开启事务
					$this->startTrans();
					$res=$this->where("order_num='$order_num'")->save($data);
					if($res!==false) {
						//订单详情
						$detaillist=$msg['detail'];
						
						//订单详情
						$detaillist=$msg['detail'];
						
						//修改记录成功，改变用户会员组,增加会员时长
						$uid=$msg['user_id'];
						$User=new \Common\Model\UserModel();
						$UserGroup=new \Common\Model\UserGroupModel();
						$userMsg=$User->getUserMsg($uid);
						
						
						if($detaillist[0]['goods_id']=='15'){  //一年会员
						    $add_date='+1 year';
						    $is_forever='N';
						}elseif($detaillist[0]['goods_id']=='16'){  //终身会员
						    $add_date='+20 year';
						    $is_forever='Y';
						}
						if($userMsg['expiration_date'] and $userMsg['expiration_date']!='0000-00-00 00:00:00')
						{
						    if(strtotime($userMsg['expiration_date'])>time())
						    {
						        //到期时间大于当前时间，延长到期时间
						        $expiration_date=date('Y-m-d H:i:s',strtotime($add_date,strtotime($userMsg['expiration_date'])));
						    }else {
						        //已到期
						        $expiration_date=date('Y-m-d H:i:s',strtotime($add_date));
						    }
						}else {
						    //未设置到期时间
						    $expiration_date=date('Y-m-d H:i:s',strtotime($add_date));
						}
						//用户信息
						$data_u=array(
						    'group_id'=>4,//VIP会员组-汇客熊
						    'expiration_date'=>$expiration_date,//会员到期时间
						    'is_forever'=>$is_forever,
						    'exp'=>$userMsg['exp']+200
						);
						if(!$User->create($data_u))
						{
						    //验证不通过
						    //回滚
						    $this->rollback();
						    return false;
						}
						
						
						
						$Goods=new \Common\Model\GoodsModel();
						$GoodsSku=new \Common\Model\GoodsSkuModel();
						foreach ($detaillist as $dl) {
							//增加商品销量、减少库存
							$goods_id=$dl['goods_id'];
							$goods_num=$dl['num'];
							$GoodsMsg=$Goods->getGoodsMsg($goods_id);
							$res_goods_sales_volume=$Goods->where("goods_id='$goods_id'")->setInc('sales_volume',$goods_num);
							if($goods_num<=$GoodsMsg['inventory'])
							{
								$inventory_dec=$goods_num;
							}else {
								$inventory_dec=$GoodsMsg['inventory'];
							}
							$res_goods_inventory=$Goods->where("goods_id='$goods_id'")->setDec('inventory',$inventory_dec);
							//如果存在属性配置商品，则相应减少该配置的商品库存
							if($dl['sku'])
							{
								$sku=$dl['sku'];
								$skuMsg=$GoodsSku->getSkuMsg($sku,$goods_id);
								if($skuMsg)
								{
									if($goods_num<=$skuMsg['inventory'])
									{
										$inventory_dec=$goods_num;
									}else {
										$inventory_dec=$skuMsg['inventory'];
									}
									$res_goods_sku=$GoodsSku->where("goods_id='$goods_id' and sku='$sku'")->setDec('inventory',$inventory_dec);
								}else {
									$res_goods_sku=true;
								}
							}else {
								$res_goods_sku=true;
							}
							if($res_goods_sales_volume!==false and $res_goods_inventory!==false and $res_goods_sku!==false)
							{
								//继续
								continue;
							}else {
								//修改商品库存、销量失败
								//回滚
								$this->rollback();
								return false;
							}
						}
						if($pay_method=='balance')
						{
						    //减少用户余额
						    $User=new \Common\Model\UserModel();
						    $uid=$msg['user_id'];
						    $userMsg=$User->getUserMsg($uid);
						    $money=$msg['allprice'];
						    $res_balance=$User->where("uid='$uid'")->setDec('balance',$money);
						    //保存余额变动记录
						    $UserBalanceRecord=new \Common\Model\UserBalanceRecordModel();
						    $all_money=$userMsg['balance']-$money;
						    $res_balance_record=$UserBalanceRecord->addLog($uid, $money, $all_money, 'shop_buy');
						}else {
						    $res_balance=true;
						    $res_balance_record=true;
						}
						if($res_balance!==false and $res_balance_record!==false){
						    //如果使用了抵扣积分，扣除用户相应积分
						    if($msg['deduction_point']>0) {
						        //减少用户积分
						        $point=$msg['deduction_point'];
						        $uid=$msg['user_id'];
						        $User=new \Common\Model\UserModel();
						        $userMsg=$User->getUserMsg($uid);
						        $res_point=$User->where("uid='$uid'")->setDec('point',$point);
						        //保存积分变动记录
						        $UserPointRecord=new \Common\Model\UserPointRecordModel();
						        $all_point=$userMsg['point']-$point;
						        $res_point_record=$UserPointRecord->addLog($uid, $point,$all_point, 'buy_d');
						        if($res_point!==false and $res_point_record!==false) {
						            //成功，提交事务
						            $this->commit();
						            return true;
						        }else {
						            //修改用户积分失败
						            //回滚
						            $this->rollback();
						            return false;
						        }
						    }else {
						        //成功，提交事务
						        $this->commit();
						        return true;
						    }
						}else {
						    //成功，提交事务
						    $this->commit();
						    return true;
						}
					}else {
						//修改订单状态失败
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
	
	/**
	 * 处理确认收货订单
	 * 赠送积分
	 * @param string $order_num:订单号
	 * @return boolean
	 */
	public function confirmOrder($order_num)
	{
	    $msg=$this->getOrderDetailByOrderNum($order_num);
	    if($msg) {
	        if($msg['status']=='3') {
	            //只有未付款订单可以处理
	            $data=array(
	                'status'=>'4',//已完成
	                'finish_time'=>date('Y-m-d H:i:s')
	            );
	            if(!$this->create($data)) {
	                //验证不通过
	                return false;
	            }else {
	                //开启事务
	                $this->startTrans();
	                $res=$this->where("order_num='$order_num'")->save($data);
	                //如果赠送积分，给用户添加相应积分
	                if($msg['give_point']>0) {
	                    //增加用户积分
	                    $point=$msg['give_point'];
	                    $uid=$msg['user_id'];
	                    $User=new \Common\Model\UserModel();
	                    $userMsg=$User->getUserMsg($uid);
	                    $res_point=$User->where("uid='$uid'")->setInc('point',$point);
	                    //保存积分变动记录
	                    $UserPointRecord=new \Common\Model\UserPointRecordModel();
	                    $all_point=$userMsg['point']+$point;
	                    $res_point_record=$UserPointRecord->addLog($uid, $point,$all_point, 'buy');
	                }else {
	                    $res_point=$res_point_record=true;
	                }
	                if( $res!==false and $res_point!==false and $res_point_record!==false ) {
	                    //成功，提交事务
	                    $this->commit();
	                    return true;
	                }else {
	                    //修改订单状态失败
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
	
	/**
	 * 处理退款订单
	 * @param int $id:订单ID
	 * @param char $check_result:审核结果，Y通过、N不通过
	 * @param string $drawback_refuse_reason:拒绝退款理由
	 */
	public function refund($id,$check_result,$drawback_refuse_reason='')
	{
	    $msg=$this->getOrderMsg($id);
	    if($msg['status']=='6'){
	        //申请退款状态可以处理
	        if($check_result=='Y'){
	            //同意
	            $data=array(
	                'status'=>'7',//同意退款
	                'refund_success_time'=>date('Y-m-d H:i:s')
	            );
	            if(!$this->create($data)){
	                //验证不通过
	                return false;
	            }else {
	                //开启事务
	                $this->startTrans();
	                $res_save=$this->where("id=$id")->save($data);
	                if($res_save!==false){
	                    //将订单金额退还给用户
	                    $user_id=$msg['user_id'];
	                    $money=$msg['allprice'];
	                    $User=new \Common\Model\UserModel();
	                    $userMsg=$User->getUserMsg($user_id);
	                    $res_balance=$User->where("uid=$user_id")->setInc('balance',$money);
	                    //保存余额记录
	                    $UserBalanceRecord=new \Common\Model\UserBalanceRecordModel();
	                    $all_money=$userMsg['balance']+$money;
	                    $res_balance_record=$UserBalanceRecord->addLog($user_id, $money, $all_money, 'goods_back');
	                    if($res_balance!==false and $res_balance_record!==false){
	                        //如果使用了抵扣积分，扣除用户相应积分
	                        if($msg['deduction_point']>0) {
	                            //减少用户积分
	                            $point=$msg['deduction_point'];
	                            $res_point=$User->where("uid='$user_id'")->setInc('point',$point);
	                            //保存积分变动记录
	                            $UserPointRecord=new \Common\Model\UserPointRecordModel();
	                            $all_point=$userMsg['point']+$point;
	                            $res_point_record=$UserPointRecord->addLog($user_id, $point,$all_point, 'buy_refund');
	                            if($res_point!==false and $res_point_record!==false) {
	                                
	                            }else {
	                                //修改用户积分失败
	                                //回滚
	                                $this->rollback();
	                                return false;
	                            }
	                        }
	                        //提交事务
	                        $this->commit();
	                        return true;
	                    }else {
	                        //回滚
	                        $this->rollback();
	                        return false;
	                    }
	                }else {
	                    //回滚
	                    $this->rollback();
	                    return false;
	                }
	            }
	        }else {
	            //拒绝
	            $data=array(
	                'status'=>'8',//拒绝
	                'drawback_refuse_reason'=>$drawback_refuse_reason,
	                'refund_fail_time'=>date('Y-m-d H:i:s')
	            );
	            if(!$this->create($data)){
	                //验证不通过
	                return false;
	            }else {
	                $res_save=$this->where("id=$id")->save($data);
	                if($res_save!==false){
	                    return true;
	                }else {
	                    return false;
	                }
	            }
	        }
	    }else {
	        return false;
	    }
	}
}
?>