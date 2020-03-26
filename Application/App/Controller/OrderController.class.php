<?php
/**
 * 订单管理接口
 */
namespace App\Controller;
use App\Common\Controller\AuthController;

class OrderController extends AuthController
{
	/**
	 * 立即购买
	 * @param string $token:用户身份令牌
	 * @param int $goods_id:商品ID
	 * @param string $goods_sku:商品SKU配置，json数组
	 * @param int $num:购买数量
	 * @param int $deduction_point:抵扣积分
	 * @param int $address_id:收货地址ID
	 * @param string $remark:备注
	 * @return array
	 * @return @param code:返回码
	 * @return @param msg:返回码说明
	 * @return @param data:返回数据
	 * @return @param data->order_id:订单ID
	 * @return @param data->order_num:订单号
	 */
	public function order()
	{
		if(trim(I('post.token')) and trim(I('post.goods_id')) and trim(I('post.num')) and trim(I('post.address_id')))
		{
			//判断用户身份
			$token=trim(I('post.token'));
			$User=new \Common\Model\UserModel();
			$res_token=$User->checkToken($token);
			if($res_token['code']!=0) {
				//用户身份不合法
				$res=$res_token;
			}else {
				$uid=$res_token['uid'];
				//判断商品是否存在
				$goods_id=trim(I('post.goods_id'));
				$Goods=new \Common\Model\GoodsModel();
				$goodsMsg=$Goods->getGoodsMsg($goods_id);
				if($goodsMsg['goods_id']) {
					$goods_num=trim(I('post.num'));
					//单价
					$price=$goodsMsg['price'];
					//赠送积分
					$give_point=$goodsMsg['give_point'];
					//可抵扣积分
					$deduction_point=$goodsMsg['deduction_point'];
					//判断购买数量是否超出商品库存
					if($goodsMsg['is_sku']=='Y') {
						//开启规格配置
						if(trim($_POST['goods_sku'])) {
							$goods_sku=trim($_POST['goods_sku']);
							$GoodsSku=new \Common\Model\GoodsSkuModel();
							$skuMsg=$GoodsSku->getSkuMsg($goods_sku);
							if(!empty($skuMsg)){
							    if($goods_num>$skuMsg['inventory']) {
							        //库存不足
							        $res=array(
							            'code'=>$this->ERROR_CODE_GOODS['INVENTORY_SHORTAGE'],
							            'msg'=>$this->ERROR_CODE_GOODS_ZH[$this->ERROR_CODE_GOODS['INVENTORY_SHORTAGE']]
							        );
							        echo json_encode ($res,JSON_UNESCAPED_UNICODE);
							        exit();
							    }else {
							        //单价
							        $price=$skuMsg['price'];
							        //赠送积分
							        $give_point=$skuMsg['give_point'];
							        //可抵扣积分
							        $deduction_point=$skuMsg['deduction_point'];
							    }
							}
						}
					}else {
						//未开启规格配置
						if($goods_num>$goodsMsg['inventory']) {
							//库存不足
							$res=array(
									'code'=>$this->ERROR_CODE_GOODS['INVENTORY_SHORTAGE'],
									'msg'=>$this->ERROR_CODE_GOODS_ZH[$this->ERROR_CODE_GOODS['INVENTORY_SHORTAGE']]
							);
							echo json_encode ($res,JSON_UNESCAPED_UNICODE);
							exit();
						}
					}
					//可抵扣总积分
					$all_deduction_point=$deduction_point*$goods_num;
					$deduction_point2=trim(I('post.deduction_point'));
					if($deduction_point2>$all_deduction_point){
					    //超出最多可抵扣积分
					    $res=array(
					        'code'=>$this->ERROR_CODE_GOODS['LACK_OF_POINT'],
					        'msg'=>'最多可抵扣'.$all_deduction_point.'分'
					    );
					    echo json_encode ($res,JSON_UNESCAPED_UNICODE);
					    exit();
					}
					$userMsg=$User->getUserMsg($uid);
					if($userMsg['point']>=$deduction_point2) {
					    //用户积分大于抵扣积分
					    $deduction_point_order=$deduction_point2;
					}else {
					    //用户积分小于抵扣积分
					    $res=array(
					        'code'=>$this->ERROR_CODE_GOODS['LACK_OF_POINT'],
					        'msg'=>$this->ERROR_CODE_GOODS_ZH[$this->ERROR_CODE_GOODS['LACK_OF_POINT']]
					    );
					    echo json_encode ($res,JSON_UNESCAPED_UNICODE);
					    exit();
					}
					
					//收货地址
					$address_id=trim(I('post.address_id'));
					//获取地址详情
					$ConsigneeAddress=new \Common\Model\ConsigneeAddressModel();
					$addressMsg=$ConsigneeAddress->where("id='$address_id'")->find();
					if($addressMsg) {
						//地址
						$address=$addressMsg['province'].$addressMsg['city'].$addressMsg['county'].$addressMsg['detail_address'];
						//总价
						$allprice=$price*$goods_num;
						//订单号
						$Order=new \Common\Model\OrderModel();
						$order_num=$Order->generateOrderNum();
						$data=array(
							'user_id'=>$uid,
							'order_num'=>$order_num,
							'title'=>$goodsMsg['goods_name'],
							'allprice'=>$allprice*100,
							'give_point'=>$give_point*$goods_num,
						    'deduction_point'=>$deduction_point_order,//抵扣积分
							'province'=>$addressMsg['province'],
							'city'=>$addressMsg['city'],
							'county'=>$addressMsg['county'],
							'address'=>$address,
							'company'=>$addressMsg['company'],
							'consignee'=>$addressMsg['consignee'],
							'contact_number'=>$addressMsg['contact_number'],
							'postcode'=>$addressMsg['postcode'],
							'remark'=>trim(I('post.remark')),
							'status'=>'1',//订单状态-未付款
							'create_time'=>date('Y-m-d H:i:s'),
						);
						if(!$Order->create($data)) {
							//验证不通过
							$res=array(
									'code'=>$this->ERROR_CODE_COMMON['PARAMETER_FORMAT_ERROR'],
									'msg'=>$Order->getError()
							);
						}else {
							//验证通过
							//开启事务
							$Order->startTrans();
							$res_add=$Order->add($data);
							if($res_add!==false) {
								$order_id=$res_add;
								//保存订单详情
								$data_detail=array(
										'order_id'=>$order_id,
										'order_num'=>$order_num,
										'goods_id'=>$goods_id,
										'goods_name'=>$goodsMsg['goods_name'],
										'sku'=>$goods_sku,
										'price'=>$price*100,
										'num'=>$goods_num,
										'allprice'=>$allprice*100,
										'give_point'=>$give_point,
										'all_give_point'=>$give_point*$goods_num,
								);
								$OrderDetail=new \Common\Model\OrderDetailModel();
								if(!$OrderDetail->create($data_detail)) {
									//回滚
									$Order->rollback();
									//验证不通过
									$res=array(
											'code'=>$this->ERROR_CODE_COMMON['PARAMETER_FORMAT_ERROR'],
											'msg'=>$OrderDetail->getError()
									);
								}else {
									//验证通过
									$res_add_detail=$OrderDetail->add($data_detail);
									if($res_add_detail!==false) {
										//提交事务
										$Order->commit();
										$data=array(
												'order_id'=>$order_id,
												'order_num'=>$order_num,
												'allprice'=>$allprice,
												'title'=>$goodsMsg['goods_name']
										);
										$res=array(
												'code'=>$this->ERROR_CODE_COMMON['SUCCESS'],
												'msg'=>'成功',
												'data'=>$data
										);
									}else {
										//回滚，订单提交失败，订单详情保存失败！
										$Order->rollback();
										//数据库错误
										$res=array(
												'code'=>$this->ERROR_CODE_COMMON['DB_ERROR'],
												'msg'=>$this->ERROR_CODE_COMMON_ZH[$this->ERROR_CODE_COMMON['DB_ERROR']]
										);
									}
								}
							}else {
								//回滚，订单提交失败！
								$Order->rollback();
								//数据库错误
								$res=array(
										'code'=>$this->ERROR_CODE_COMMON['DB_ERROR'],
										'msg'=>$this->ERROR_CODE_COMMON_ZH[$this->ERROR_CODE_COMMON['DB_ERROR']]
								);
							}
						}
					}else {
						//收货地址不存在
						$res=array(
								'code'=>$this->ERROR_CODE_GOODS['ADDRESS_NOT_EXIST'],
								'msg'=>$this->ERROR_CODE_GOODS_ZH[$this->ERROR_CODE_GOODS['ADDRESS_NOT_EXIST']]
						);
					}
				}else {
					//该商品不存在
					$res=array(
							'code'=>$this->ERROR_CODE_GOODS['GOODS_NOT_EXIST'],
							'msg'=>$this->ERROR_CODE_GOODS_ZH[$this->ERROR_CODE_GOODS['GOODS_NOT_EXIST']]
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
	 * 确认订单-购物车
	 * @param string $token:用户身份令牌
	 * @param array $goodslist:商品列表，json数组
	 * @param int $address_id:收货地址ID
	 * @param int $deduction_point:抵扣积分
	 * @param string $remark:备注
	 * @return array
	 * @return @param code:返回码
	 * @return @param msg:返回码说明
	 */
	public function orderByShopcart()
	{
	    /* $list1=array(
	     'shopcart_id'=>1,
	     'goods_num'=>2,
	     );
	     $list2=array(
	     'shopcart_id'=>2,
	     'goods_num'=>3,
	     );
	     $list=array(
	     0=>$list1,
	     1=>$list2
	     );
	     $goodslist=json_encode($list);
	     echo $goodslist;die(); */
	    if( trim(I('post.token')) and $_POST['goodslist'] and trim(I('post.address_id')) )
	    {
	        //判断用户身份
	        $token=trim(I('post.token'));
	        $User=new \Common\Model\UserModel();
	        $res_token=$User->checkToken($token);
	        if($res_token['code']!=0) {
	            //用户身份不合法
	            $res=$res_token;
	        }else {
	            $uid=$res_token['uid'];
	            $goodslist_str=str_replace('\\', '', $_POST['goodslist']);
	            $goodslist=json_decode($goodslist_str,true);
	            //判断购物车里商品是否存在
	            $Goods=new \Common\Model\GoodsModel();
	            $Shopcart=new \Common\Model\ShopcartModel();
	            $GoodsSku=new \Common\Model\GoodsSkuModel();
	            foreach ($goodslist as $l) {
	                $shopcart_id=$l['shopcart_id'];
	                $shopcartMsg=$Shopcart->where("id='$shopcart_id'")->find();
	                
	                $goods_id=$shopcartMsg['goods_id'];
	                $goods_num=$l['goods_num'];
	                $goodsMsg=$Goods->getGoodsMsg($goods_id);
	                if($goodsMsg) {
	                    //单价
	                    $price=$goodsMsg['price'];
	                    //赠送积分
	                    $give_point=$goodsMsg['give_point'];
	                    //可抵扣积分
	                    $deduction_point=$goodsMsg['deduction_point'];
	                    
	                    $sku_str='';
	                    //判断购买数量是否超出商品库存
	                    if($goodsMsg['is_sku']=='Y' and $shopcartMsg['goods_sku']) {
	                        //开启规格配置
	                        $sku=$shopcartMsg['goods_sku'];
	                        $skuMsg=$GoodsSku->getSkuMsg($sku,$goods_id);
	                        if($skuMsg) {
	                            if($goods_num>$skuMsg['inventory']) {
	                                //库存不足
	                                $res=array(
	                                    'code'=>$this->ERROR_CODE_GOODS['INVENTORY_SHORTAGE'],
	                                    'msg'=>$this->ERROR_CODE_GOODS_ZH[$this->ERROR_CODE_GOODS['INVENTORY_SHORTAGE']]
	                                );
	                                 echo json_encode ($res,JSON_UNESCAPED_UNICODE);
	                                 exit();
	                            }else {
	                                //单价
	                                $price=$skuMsg['price'];
	                                //SKU文字描述
	                                $sku_str=$skuMsg['sku_str'];
	                                //赠送积分
	                                $give_point=$skuMsg['give_point'];
	                                //可抵扣积分
	                                $deduction_point=$skuMsg['deduction_point'];
	                            }
	                        }else {
	                            //规格不存在，按照正常商品处理
	                            if($goods_num>$goodsMsg['inventory']) {
	                                //库存不足
	                                $res=array(
	                                    'code'=>$this->ERROR_CODE_GOODS['INVENTORY_SHORTAGE'],
	                                    'msg'=>$this->ERROR_CODE_GOODS_ZH[$this->ERROR_CODE_GOODS['INVENTORY_SHORTAGE']]
	                                );
	                                 echo json_encode ($res,JSON_UNESCAPED_UNICODE);
	                                 exit();
	                            }
	                        }
	                    }else {
	                        //未开启规格配置
	                        if($goods_num>$goodsMsg['inventory']) {
	                            //库存不足
	                            $res=array(
	                                'code'=>$this->ERROR_CODE_GOODS['INVENTORY_SHORTAGE'],
	                                'msg'=>$this->ERROR_CODE_GOODS_ZH[$this->ERROR_CODE_GOODS['INVENTORY_SHORTAGE']]
	                             );
	                             echo json_encode ($res,JSON_UNESCAPED_UNICODE);
	                             exit();
	                        }
	                    }
	                    //总价
	                    $allprice+=$price*$goods_num;
	                    //总赠送积分
	                    $all_give_point+=$give_point*$goods_num;
	                    //总可抵扣积分
	                    $all_deduction_point+=$deduction_point*$goods_num;
	                    //订单名称
	                    $title.=$goodsMsg['goods_name'].'-';
	                    $data_detail_tmp[]=array(
	                        'goods_id'=>$goods_id,
	                        'goods_name'=>$goodsMsg['goods_name'],
	                        'sku'=>$sku_str,
	                        'price'=>$price,
	                        'num'=>$goods_num,
	                        'allprice'=>$price*$goods_num,
	                        'give_point'=>$give_point,
	                        'all_give_point'=>$give_point*$goods_num,
	                    );
	                }else {
	                    //该商品不存在
	                    $res=array(
	                        'code'=>$this->ERROR_CODE_GOODS['GOODS_NOT_EXIST'],
	                        'msg'=>$this->ERROR_CODE_GOODS_ZH[$this->ERROR_CODE_GOODS['GOODS_NOT_EXIST']]
	                    );
	                    echo json_encode ($res,JSON_UNESCAPED_UNICODE);
	                    exit();
	                }
	            }
	            //判断可抵扣积分是否超出
	            $deduction_point2=trim(I('post.deduction_point'));
	            if($deduction_point2>$all_deduction_point){
	                //超出最多可抵扣积分
	                $res=array(
	                    'code'=>$this->ERROR_CODE_GOODS['LACK_OF_POINT'],
	                    'msg'=>'最多可抵扣'.$all_deduction_point.'分'
	                );
	                echo json_encode ($res,JSON_UNESCAPED_UNICODE);
	                exit();
	            }
	            $userMsg=$User->getUserMsg($uid);
	            if($userMsg['point']>=$deduction_point2) {
	                //用户积分大于抵扣积分
	                $deduction_point_order=$deduction_point2;
	            }else {
	                //用户积分小于抵扣积分
	                $res=array(
	                    'code'=>$this->ERROR_CODE_GOODS['LACK_OF_POINT'],
	                    'msg'=>$this->ERROR_CODE_GOODS_ZH[$this->ERROR_CODE_GOODS['LACK_OF_POINT']]
	                );
	                echo json_encode ($res,JSON_UNESCAPED_UNICODE);
	                exit();
	            }
	            
	            //订单名称
	            $title=substr($title, 0,-1);
	            //判断收货地址是否存在
	            $address_id=trim(I('post.address_id'));
	            $ConsigneeAddress=new \Common\Model\ConsigneeAddressModel();
	            $addressMsg=$ConsigneeAddress->where("id='$address_id' and user_id='$uid'")->find();
	            if($addressMsg)
	            {
	                //收货地址
	                $address=$addressMsg['province'].$addressMsg['city'].$addressMsg['county'].$addressMsg['detail_address'];
	            
	                //生成订单
	                $Order=new \Common\Model\OrderModel();
	                //订单号
	                $order_num=$Order->generateOrderNum();
	                $data=array(
	                    'user_id'=>$uid,
	                    'order_num'=>$order_num,
	                    'title'=>$title,
	                    'allprice'=>$allprice*100,
	                    'give_point'=>$all_give_point,
	                    'deduction_point'=>$deduction_point_order,//抵扣积分
	                    'province'=>$addressMsg['province'],
	                    'city'=>$addressMsg['city'],
	                    'county'=>$addressMsg['county'],
	                    'address'=>$address,
	                    'company'=>$addressMsg['company'],
	                    'consignee'=>$addressMsg['consignee'],
	                    'contact_number'=>$addressMsg['contact_number'],
	                    'postcode'=>$addressMsg['postcode'],
	                    'remark'=>trim(I('post.remark')),
	                    'status'=>'1',//订单状态-未付款
	                    'create_time'=>date('Y-m-d H:i:s'),
	                );
	                if(!$Order->create($data)) {
	                    //验证不通过
	                    $res=array(
	                        'code'=>$this->ERROR_CODE_COMMON['PARAMETER_FORMAT_ERROR'],
	                        'msg'=>$Order->getError()
	                    );
	                }else {
	                    //验证通过
	                    //开启事务
	                    $Order->startTrans();
	                    $res_add=$Order->add($data);
	                    if($res_add!==false) {
	                        $order_id=$res_add;
	                        //保存订单详情
	                        $OrderDetail=new \Common\Model\OrderDetailModel();
	                        foreach ($data_detail_tmp as $tl) {
	                            $data_detail[]=array(
	                                'order_id'=>$order_id,
	                                'order_num'=>$order_num,
	                                'goods_id'=>$tl['goods_id'],
	                                'goods_name'=>$tl['goods_name'],
	                                'price'=>$tl['price']*100,
	                                'num'=>$tl['num'],
	                                'allprice'=>$tl['allprice']*100,
	                                'give_point'=>$tl['give_point'],
	                                'all_give_point'=>$tl['all_give_point'],
	                                'sku'=>$tl['sku'],
	                            );
	                        }
	                        $res_add_detail=$OrderDetail->addAll($data_detail);
	                        if($res_add_detail!==false) {
	                            //同时删除购物车
	                            foreach ($goodslist as $l)
	                            {
	                                $shopcart_id=$l['shopcart_id'];
	                                $Shopcart->where("id='$shopcart_id'")->delete();
	                            }
	                            //提交事务
	                            $Order->commit();
	                            $data=array(
	                                'order_id'=>$order_id,
	                                'order_num'=>$order_num,
	                                'allprice'=>$allprice,
	                                'title'=>$title
	                            );
	                            $res=array(
	                                'code'=>$this->ERROR_CODE_COMMON['SUCCESS'],
	                                'msg'=>'成功',
	                                'data'=>$data
	                            );
	                        }else {
	                            //回滚
	                            $Order->rollback();
	                            //数据库错误
	                            $res=array(
	                                'code'=>$this->ERROR_CODE_COMMON['DB_ERROR'],
	                                'msg'=>$this->ERROR_CODE_COMMON_ZH[$this->ERROR_CODE_COMMON['DB_ERROR']]
	                            );
	                        }
	                    }else {
	                        //回滚
	                        $Order->rollback();
	                        //数据库错误
	                        $res=array(
	                            'code'=>$this->ERROR_CODE_COMMON['DB_ERROR'],
	                            'msg'=>$this->ERROR_CODE_COMMON_ZH[$this->ERROR_CODE_COMMON['DB_ERROR']]
	                        );
	                    }
	                }
	            }else {
	                //收货地址不存在
	                $res=array(
	                    'code'=>$this->ERROR_CODE_ORDER['ADDRESS_NOT_EXIST'],
	                    'msg'=>$this->ERROR_CODE_ORDER_ZH[$this->ERROR_CODE_ORDER['ADDRESS_NOT_EXIST']]
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
	 * 获取支付表单
	 * @param string $token:用户身份令牌
	 * @param int $order_id:订单ID
	 * @param string $pay_method:支付方式 alipay支付宝支付 wxpay微信支付 balance微信支付
	 * @return array
	 * @return @param code:返回码
	 * @return @param msg:返回码说明
	 * @return @param data:返回码数据
	 * @return @param data->pay_parameters:支付表单数据
	 */
	public function getPayForm()
	{
		if(trim(I('post.token')) and trim(I('post.order_id'))  and trim(I('post.pay_method')))
		{
			//判断用户身份
			$token=trim(I('post.token'));
			$User=new \Common\Model\UserModel();
			$res_token=$User->checkToken($token);
			if($res_token['code']!=0) {
				//用户身份不合法
				$res=$res_token;
			}else {
				$uid=$res_token['uid'];
				$order_id=trim(I('post.order_id'));
				//获取订单信息
				$Order=new \Common\Model\OrderModel();
				$msg=$Order->getOrderMsg($order_id);
				if($msg) {
					//判断订单是否已付款
					if($msg['status']=='1') {
					    //实际付款金额
					    $pay_price=$msg['allprice']-$msg['deduction_point']*POINT_VALUE;
					    //保留2位小数-四舍五不入
					    $pay_price=substr(sprintf("%.3f",$pay_price),0,-1);
					    //获取支付订单
						//订单生成成功，根据支付方式获取相应支付二维码
						$pay_method=trim(I('post.pay_method'));
						switch ($pay_method){
						    case 'wxpay':
						        //获取微信支付表单数据
						        Vendor('pay.wxpay','','.class.php');
						        $wxpay=new \wxpay();
						        $body=$msg['title'];
						        //订单号
						        $out_trade_no='sp_'.$msg['order_num'];//订单号
						        //订单费用，精确到分
						        $total_fee=$pay_price*100;
						        $notify_url=WEB_URL.'/app.php/WxNotify/notify_app';
						        $AppParameters=$wxpay->GetAppParameters($body, $out_trade_no, $total_fee, $notify_url);
						        $pay_parameters=$AppParameters;
						        break;
						    case 'alipay':
						        //获取支付宝请求参数
						        Vendor('pay.alipayApp','','.class.php');
						        $alipayApp=new \alipayApp();
						        //订单描述
						        $body=$msg['title'];
						        //订单名称，必填
						        $subject=$msg['title'];
						        //商户订单号，商户网站订单系统中唯一订单号，必填
						        $out_trade_no='sp_'.$msg['order_num'];//订单号
						        //付款金额，必填
						        $total_amount=$pay_price;
						        $alipay_parameters=$alipayApp->GetParameters($body,$subject, $out_trade_no, $total_amount);
						        $pay_parameters=$alipay_parameters;
						        break;
						    case 'balance':
						        $pay_parameters='';
						        //判断用户余额是否足够
						        $userMsg=$User->getUserMsg($uid);
						        if($userMsg['balance']>=$pay_price) {
						            //处理订单
						            $res_treat=$Order->treatOrder($msg['order_num'], 'balance');
						            if($res_treat!==false) {
						                
						            }else {
						                //数据库错误
						                $res=array(
						                    'code'=>$this->ERROR_CODE_COMMON['DB_ERROR'],
						                    'msg'=>$this->ERROR_CODE_COMMON_ZH[$this->ERROR_CODE_COMMON['DB_ERROR']]
						                );
						                echo json_encode ($res,JSON_UNESCAPED_UNICODE);
						                exit();
						            }
						        }else {
						            //余额不足
						            $res=array(
						                'code'=>$this->ERROR_CODE_USER['BALANCE_INSUFFICIENT'],
						                'msg'=>$this->ERROR_CODE_USER_ZH[$this->ERROR_CODE_USER['BALANCE_INSUFFICIENT']]
						            );
						            echo json_encode ($res,JSON_UNESCAPED_UNICODE);
						            exit();
						        }
						}
						$data=array(
								'pay_parameters'=>$pay_parameters,
						);
						$res=array(
								'code'=>$this->ERROR_CODE_COMMON['SUCCESS'],
								'msg'=>'成功',
								'data'=>$data
						);
					}else {
						//只有未付款订单才可以支付
						$res=array(
								'code'=>$this->ERROR_CODE_GOODS['ONLY_UNPAID_ORDER_CAN_PAY'],
								'msg'=>$this->ERROR_CODE_GOODS_ZH[$this->ERROR_CODE_GOODS['ONLY_UNPAID_ORDER_CAN_PAY']]
						);
					}
				}else {
					//订单不存在
					$res=array(
							'code'=>$this->ERROR_CODE_GOODS['ORDER_NOT_EXIST'],
							'msg'=>$this->ERROR_CODE_GOODS_ZH[$this->ERROR_CODE_GOODS['ORDER_NOT_EXIST']]
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
	 * 获取用户订单列表
	 * @param string $token:用户身份令牌
	 * @param int $status:订单状态 1待付款 2已付款、待发货 3已发货、待确认收货 4已确认收货、已完成
	 * @param int $p:页码，默认第1页
	 * @param int $per:每页条数，默认6条
	 * @return array
	 * @return @param code:返回码
	 * @return @param msg:返回码说明
	 * @return @param data:返回数据
	 * @return @param data->list:订单列表
	 */
	public function getOrderList()
	{
		if(trim(I('post.token')))
		{
			//判断用户身份
			$token=trim(I('post.token'));
			$User=new \Common\Model\UserModel();
			$res_token=$User->checkToken($token);
			if($res_token['code']!=0) {
				//用户身份不合法
				$res=$res_token;
			}else {
				$uid=$res_token['uid'];
				//获取订单列表
				$Order=new \Common\Model\OrderModel();
				$status=trim(I('post.status'));
				if(trim(I('post.p'))) {
				    $p=trim(I('post.p'));
				}else {
				    $p=1;
				}
				if(trim(I('post.per'))) {
				    $per=trim(I('post.per'));
				}else {
				    $per=6;
				}
				$list=$Order->getOrderListByUid($uid,$status,$p,$per);
				if($list!==false) {
					$data=array(
							'list'=>$list,
					);
					$res=array(
							'code'=>$this->ERROR_CODE_COMMON['SUCCESS'],
							'msg'=>'成功',
							'data'=>$data,
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
	 * 获取订单信息
	 * @param int $order_id:订单ID
	 * @return array
	 * @return @param code:返回码
	 * @return @param msg:返回码说明
	 * @return @param data:返回数据
	 */
	public function getOrderMsg()
	{
		if(trim(I('post.order_id'))) {
			$order_id=trim(I('post.order_id'));
			$Order=new \Common\Model\OrderModel();
			$orderMsg=$Order->getOrderDetail($order_id);
			if($orderMsg) {
				$data=array(
						'orderMsg'=>$orderMsg,
				);
				$res=array(
						'code'=>$this->ERROR_CODE_COMMON['SUCCESS'],
						'msg'=>'成功',
						'data'=>$data,
				);
			}else {
				//订单不存在
				$res=array(
						'code'=>$this->ERROR_CODE_GOODS['ORDER_NOT_EXIST'],
						'msg'=>$this->ERROR_CODE_GOODS_ZH[$this->ERROR_CODE_GOODS['ORDER_NOT_EXIST']]
				);
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
	 * 取消订单
	 * @param string $token:用户身份令牌
	 * @param int $order_id:订单ID
	 * @return array
	 * @return @param code:返回码
	 * @return @param msg:返回码说明
	 */
	public function cancel()
	{
		if(trim(I('post.token')) and trim(I('post.order_id'))) {
			//判断用户身份
			$token=trim(I('post.token'));
			$User=new \Common\Model\UserModel();
			$res_token=$User->checkToken($token);
			if($res_token['code']!=0) {
				//用户身份不合法
				$res=$res_token;
			}else {
				$uid=$res_token['uid'];
				//判断订单是否存在
				$order_id=trim(I('post.order_id'));
				$Order=new \Common\Model\OrderModel();
				$orderMsg=$Order->getOrderMsg($order_id);
				if($orderMsg) {
					//判断订单状态-只有待处理订单才可以取消
					if($orderMsg['status']=='1') {
						//判断订单是否属于用户
						if($orderMsg['user_id']==$uid) {
							//取消订单
							//开启事务
							$Order->startTrans();
							$res_cancel=$Order->where("id='$order_id'")->delete();
							if($res_cancel!==false) {
								//删除订单详情列表
								$OrderDetail=new \Common\Model\OrderDetailModel();
								$res_del=$OrderDetail->where("order_id='$order_id'")->delete();
								if($res_del!==false)
								{
									//提交事务
									$Order->commit();
									$res=array(
											'code'=>$this->ERROR_CODE_COMMON['SUCCESS'],
											'msg'=>'成功',
									);
								}else {
									//回滚
									$Order->rollback();
									//数据库错误
									$res=array(
											'code'=>$this->ERROR_CODE_COMMON['DB_ERROR'],
											'msg'=>$this->ERROR_CODE_COMMON_ZH[$this->ERROR_CODE_COMMON['DB_ERROR']]
									);
								}
							}else {
								//回滚
								$Order->rollback();
								//数据库错误
								$res=array(
										'code'=>$this->ERROR_CODE_COMMON['DB_ERROR'],
										'msg'=>$this->ERROR_CODE_COMMON_ZH[$this->ERROR_CODE_COMMON['DB_ERROR']]
								);
							}
						}else {
							//该订单不属于您
							$res=array(
									'code'=>$this->ERROR_CODE_GOODS['ORDER_NOT_BELONG_USER'],
									'msg'=>$this->ERROR_CODE_GOODS_ZH[$this->ERROR_CODE_GOODS['ORDER_NOT_BELONG_USER']]
							);
						}
					}else {
						//只有待付款订单才可以取消
						$res=array(
								'code'=>$this->ERROR_CODE_GOODS['ONLY_UNPAID_ORDER_CAN_BE_CANCELLED'],
								'msg'=>$this->ERROR_CODE_GOODS_ZH[$this->ERROR_CODE_GOODS['ONLY_UNPAID_ORDER_CAN_BE_CANCELLED']]
						);
					}
				}else {
					//订单不存在
					$res=array(
							'code'=>$this->ERROR_CODE_GOODS['ORDER_NOT_EXIST'],
							'msg'=>$this->ERROR_CODE_GOODS_ZH[$this->ERROR_CODE_GOODS['ORDER_NOT_EXIST']]
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
	 * 确认收货
	 * @param string $token:用户身份令牌
	 * @param int $order_id:订单ID
	 * @return array
	 * @return @param code:返回码
	 * @return @param msg:返回码说明
	 */
	public function confirmOrder()
	{
		if(trim(I('post.token')) and trim(I('post.order_id'))) {
			//判断用户身份
			$token=trim(I('post.token'));
			$User=new \Common\Model\UserModel();
			$res_token=$User->checkToken($token);
			if($res_token['code']!=0) {
				//用户身份不合法
				$res=$res_token;
			}else {
				$uid=$res_token['uid'];
				//判断订单是否存在
				$order_id=trim(I('post.order_id'));
				$Order=new \Common\Model\OrderModel();
				$orderMsg=$Order->getOrderMsg($order_id);
				if($orderMsg) {
					//判断订单状态-只有已发货订单可以确认收货
				    if($orderMsg['status']=='3' or $orderMsg['status']=='8') {
						//判断订单是否属于用户
				        if($orderMsg['user_id']==$uid) {
							//处理确认收货订单
				            $res_order=$Order->confirmOrder($orderMsg['order_num']);
							if($res_order!==false) {
								$res=array(
										'code'=>$this->ERROR_CODE_COMMON['SUCCESS'],
										'msg'=>'成功',
								);
							}else {
								//数据库错误
								$res=array(
										'code'=>$this->ERROR_CODE_COMMON['DB_ERROR'],
										'msg'=>$this->ERROR_CODE_COMMON_ZH[$this->ERROR_CODE_COMMON['DB_ERROR']]
								);
							}
						}else {
							//该订单不属于您
							$res=array(
									'code'=>$this->ERROR_CODE_GOODS['ORDER_NOT_BELONG_USER'],
									'msg'=>$this->ERROR_CODE_GOODS_ZH[$this->ERROR_CODE_GOODS['ORDER_NOT_BELONG_USER']]
							);
						}
					}else {
						//只有已发货订单可以确认收货
						$res=array(
								'code'=>$this->ERROR_CODE_GOODS['ONLY_DELIVERY_ORDER_CAN_BE_CONFIRMED'],
								'msg'=>$this->ERROR_CODE_GOODS_ZH[$this->ERROR_CODE_GOODS['ONLY_DELIVERY_ORDER_CAN_BE_CONFIRMED']]
						);
					}
				}else {
					//订单不存在
					$res=array(
							'code'=>$this->ERROR_CODE_GOODS['ORDER_NOT_EXIST'],
							'msg'=>$this->ERROR_CODE_GOODS_ZH[$this->ERROR_CODE_GOODS['ORDER_NOT_EXIST']]
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
	 * 获取物流详情
	 * @param string $token:用户身份令牌
	 * @param string $express_number:快递单号
	 * @return array
	 * @return @param code:返回码
	 * @return @param msg:返回码说明
	 * @return @param data:返回数据
	 * @return @param data->logisticsMsg:物流详情列表
	 */
	public function getLogisticsMsg()
	{
	    if(trim(I('post.token')) and trim(I('post.express_number'))) {
	        //判断用户身份
	        $token=trim(I('post.token'));
	        $User=new \Common\Model\UserModel();
	        $res_token=$User->checkToken($token);
	        if($res_token['code']!=0) {
	            //用户身份不合法
	            $res=$res_token;
	        }else {
	            $uid=$res_token['uid'];
	            //获取物流详情
	            $express_number=trim(I('post.express_number'));
	            $Express = new \Common\Model\ExpressModel();
	            $logisticsMsg=$Express->getorder($express_number);
	            if($logisticsMsg!==false) {
	                $data=array(
	                    'logisticsMsg'=>$logisticsMsg['data'],
	                );
	                $res=array(
	                    'code'=>$this->ERROR_CODE_COMMON['SUCCESS'],
	                    'msg'=>'成功',
	                    'data'=>$data,
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
	 * 评价订单
	 * @param string $token:用户身份令牌
	 * @param int $order_id:订单ID
	 * @param array $goods_id:商品ID数组
	 * @param array $grade:评价等级数组
	 * @param array $score:评分数组
	 * @param array $content:评论内容数组
	 * @param array $sku:商品属性数组
	 * @param file $img_:评论图片数组
	 * @return array
	 * @return @param code:返回码
	 * @return @param msg:返回码说明
	 */
	public function comment()
	{
	    if(trim(I('post.token')) and trim(I('post.order_id')) and $_POST['goods_id'] and $_POST['grade'] and $_POST['score']) {
	        //判断用户身份
	        $token=trim(I('post.token'));
	        $User=new \Common\Model\UserModel();
	        $res_token=$User->checkToken($token);
	        if($res_token['code']!=0) {
	            //用户身份不合法
	            $res=$res_token;
	        }else {
	            $uid=$res_token['uid'];
	            $order_id=trim(I('post.order_id'));
	            //获取订单信息
	            $Order=new \Common\Model\OrderModel();
	            $orderMsg=$Order->getOrderDetail($order_id);
	            if($orderMsg) {
	                //订单详情商品数量
	                $num=count($orderMsg['detail']);
	                //评论商品
	                //商品ID数组
	                $goods_id_arr=$_POST['goods_id'];
	                //评价等级数组
	                $grade_arr=$_POST['grade'];
	                //评分数组
	                $score_arr=$_POST['score'];
	                //评论内容数组
	                $content_arr=$_POST['content'];
	                //商品属性数组
	                $sku_arr=$_POST['sku'];
	                $config = array(
	                    'mimes'         =>  array(), //允许上传的文件MiMe类型
	                    'maxSize'       =>  1024*1024*8, //上传的文件大小限制 (0-不做限制)
	                    'exts'          =>  array('jpg', 'gif', 'png', 'jpeg'), //允许上传的文件后缀
	                    'rootPath'      =>  './Public/Upload/GoodsComment/', //保存根路径
	                    'savePath'      =>  '', //保存路径
	                    'saveExt'       =>  '', //文件保存后缀，空则使用原后缀
	                );
	                $upload = new \Think\Upload($config);
	                //临时图片
	                $tmp_filepath=array();
	                $img=array();
	                for ($i=1;$i<=$num;$i++) {
	                    //上传图片
	                    $file_name='img_'.$i;
	                    if(!empty($_FILES[$file_name]['name'][0])) {
	                        // 上传单个文件
	                        $info = $upload->upload(array($_FILES[$file_name]));
	                        if(!$info) {
	                            // 上传错误提示错误信息
	                            //删除已上传图片
	                            if($tmp_filepath) {
	                                foreach ($tmp_filepath as $k=>$v) {
	                                    @unlink($v);
	                                }
	                            }
	                            $res=array(
	                                'code'=>$this->ERROR_CODE_COMMON['FILE_UPLOAD_ERROR'],
	                                'msg'=>$upload->getError()
	                            );
	                            echo json_encode ($res,JSON_UNESCAPED_UNICODE);
	                            exit();
	                        }else{
	                            // 上传成功
	                            // 文件完成路径
	                            foreach ($info as $l) {
	                                $filepath=$config['rootPath'].$l['savepath'].$l['savename'];
	                                $img[]=substr($filepath,1);
	                                //临时图片
	                                $tmp_filepath[]=$filepath;
	                            }
	                            $img_str=json_encode($img);
	                            //上传图片标记
	                            $have_img='Y';
	                        }
	                    }else {
	                        $have_img='N';
	                        $img_str='';
	                    }
	                    //商品ID
	                    $goods_id=$goods_id_arr[$i];
	                    //评价等级
	                    $grade=$grade_arr[$i];
	                    //评分
	                    $score=$score_arr[$i];
	                    //评论内容
	                    $content=$content_arr[$i];
	                    //商品属性
	                    $sku=$sku_arr[$i];
	                    $data[]=array(
	                        'user_id'=>$uid,
	                        'order_id'=>$order_id,
	                        'goods_id'=>$goods_id,
	                        'grade'=>$grade,
	                        'score'=>$score,
	                        'content'=>$content,
	                        'img'=>$img_str,
	                        'comment_time'=>date('Y-m-d H:i:s'),
	                        'have_img'=>$have_img,
	                        'sku'=>$sku
	                    );
	                }
	                //保存全部评论
	                //开启事务
	                $GoodsComment=new \Common\Model\GoodsCommentModel();
	                $GoodsComment->startTrans();
	                $res=$GoodsComment->addAll($data);
	                if($res!==false) {
	                    //修改订单状态
	                    $data_o=array(
	                        'status'=>'5',//已评价、已完成
	                        'comment_time'=>date('Y-m-d H:i:s')
	                    );
	                    $Order=new \Common\Model\OrderModel();
	                    $res_o=$Order->where("id=$order_id")->save($data_o);
	                    if($res_o!==false) {
	                        //提交事务
	                        $GoodsComment->commit();
	                        $res=array(
	                            'code'=>$this->ERROR_CODE_COMMON['SUCCESS'],
	                            'msg'=>'成功',
	                        );
	                    }else {
	                        //删除已上传图片
	                        if($tmp_filepath) {
	                            foreach ($tmp_filepath as $k=>$v) {
	                                @unlink($v);
	                            }
	                        }
	                        //回滚
	                        $GoodsComment->rollback();
	                        $res=array(
	                            'code'=>$this->ERROR_CODE_COMMON['DB_ERROR'],
	                            'msg'=>$this->ERROR_CODE_COMMON_ZH[$this->ERROR_CODE_COMMON['DB_ERROR']]
	                        );
	                    }
	                }else {
	                    //删除已上传图片
	                    if($tmp_filepath) {
	                        foreach ($tmp_filepath as $k=>$v) {
	                            @unlink($v);
	                        }
	                    }
	                    //回滚
	                    $GoodsComment->rollback();
	                    $res=array(
	                        'code'=>$this->ERROR_CODE_COMMON['DB_ERROR'],
	                        'msg'=>$this->ERROR_CODE_COMMON_ZH[$this->ERROR_CODE_COMMON['DB_ERROR']]
	                    );
	                }
	            }else {
	                //订单不存在
	                $res=array(
	                    'code'=>$this->ERROR_CODE_GOODS['ORDER_NOT_EXIST'],
	                    'msg'=>$this->ERROR_CODE_GOODS_ZH[$this->ERROR_CODE_GOODS['ORDER_NOT_EXIST']]
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
	 * 申请退款
	 * 备注：已付款未确认收货的订单可以申请，申请退款被拒绝的可以再次提交申请
	 * @param string $token:用户身份令牌
	 * @param int $order_id:订单ID
	 * @param string $drawback_reason:申请退款理由
	 * @param file $drawback_img:申请退款凭证图片，多张
	 * @return array
	 * @return @param code:返回码
	 * @return @param msg:返回码说明
	 */
	public function applyDrawback()
	{
	    if(trim(I('post.token')) and trim(I('post.order_id')) and trim(I('post.drawback_reason')))
	    {
	        //判断用户身份
	        $token=trim(I('post.token'));
	        $User=new \Common\Model\UserModel();
	        $res_token=$User->checkToken($token);
	        if($res_token['code']!=0) {
	            //用户身份不合法
	            $res=$res_token;
	        }else {
	            $uid=$res_token['uid'];
	            //判断订单是否存在
	            $order_id=trim(I('post.order_id'));
	            $Order=new \Common\Model\OrderModel();
	            $orderMsg=$Order->getOrderMsg($order_id);
	            if($orderMsg) {
	                //判断订单是否属于用户
	                if($orderMsg['user_id']==$uid) {
	                    //判断订单状态-只有未确认收货订单可以申请退款
	                    if($orderMsg['status']=='2' or $orderMsg['status']=='3' or $orderMsg['status']=='8')
	                    {
	                        //申请退款理由
	                        $drawback_reason=trim(I('post.drawback_reason'));
	                        //上传申请退款凭证图片
	                        if(!empty($_FILES['drawback_img']['name'][0]))
	                        {
	                            $config = array(
	                                'mimes'         =>  array(), //允许上传的文件MiMe类型
	                                'maxSize'       =>  1024*1024*8, //上传的文件大小限制 (0-不做限制)
	                                'exts'          =>  array('jpg', 'gif', 'png', 'jpeg'), //允许上传的文件后缀
	                                'rootPath'      =>  './Public/Upload/Order/drawback/', //保存根路径
	                                'savePath'      =>  '', //保存路径
	                                'saveExt'       =>  '', //文件保存后缀，空则使用原后缀
	                            );
	                            $upload = new \Think\Upload($config);
	                            // 上传单个文件
	                            $info = $upload->upload(array($_FILES['drawback_img']));
	                            if(!$info) {
	                                // 上传错误提示错误信息
	                                $res=array(
	                                    'code'=>$this->ERROR_CODE_COMMON['FILE_UPLOAD_ERROR'],
	                                    'msg'=>$upload->getError()
	                                );
	                                echo json_encode ($res,JSON_UNESCAPED_UNICODE);
	                                exit();
	                            }else{
	                                // 上传成功
	                                // 文件完成路径
	                                foreach ($info as $l)
	                                {
	                                    $filepath_mob=$config['rootPath'].$l['savepath'].$l['savename'];
	                                    $img[]=substr($filepath_mob,1);
	                                }
	                                $img_str=json_encode($img);
	                            }
	                        }else {
	                            $img_str='';
	                        }
	                        $data=array(
	                            'drawback_reason'=>$drawback_reason,
	                            'drawback_img'=>$img_str,
	                            'status'=>'6',//申请退款
	                            'refund_time'=>date('Y-m-d H:i:s')
	                        );
	                        if(!$Order->create($data)) {
	                            //验证不通过
	                            //删除已上传图片
	                            if($img) {
	                                foreach ($img as $k=>$v) {
	                                    $tmp='.'.$v;
	                                    @unlink($tmp);
	                                }
	                            }
	                            //数据库自动验证，参数格式错误
	                            $res=array(
	                                'code'=>$this->ERROR_CODE_COMMON['PARAMETER_FORMAT_ERROR'],
	                                'msg'=>$Order->getError()
	                            );
	                        }else {
	                            //验证通过
	                            $res_save=$Order->where("id=$order_id")->save($data);
	                            if($res_save!==false) {
	                                //成功
	                                $res=array(
	                                    'code'=>$this->ERROR_CODE_COMMON['SUCCESS'],
	                                    'msg'=>'申请退款成功，请耐心等待管理员审核！',
	                                );
	                            }else {
	                                //删除已上传图片
	                                if($img) {
	                                    foreach ($img as $k=>$v) {
	                                        $tmp='.'.$v;
	                                        @unlink($tmp);
	                                    }
	                                }
	                                //数据库错误
	                                $res=array(
	                                    'code'=>$this->ERROR_CODE_COMMON['DB_ERROR'],
	                                    'msg'=>$this->ERROR_CODE_COMMON_ZH[$this->ERROR_CODE_COMMON['DB_ERROR']]
	                                );
	                            }
	                        }
	                    }else {
	                        //只有未确认收货订单可以申请退款
	                        $res=array(
	                            'code'=>$this->ERROR_CODE_GOODS['ONLY_UNCONFIRMED_RECEIPT_ORDER_CAN_APPLY_FOR_REFUND'],
	                            'msg'=>$this->ERROR_CODE_GOODS_ZH[$this->ERROR_CODE_GOODS['ONLY_UNCONFIRMED_RECEIPT_ORDER_CAN_APPLY_FOR_REFUND']]
	                        );
	                    }
	                }else {
	                    //该订单不属于您
	                    $res=array(
	                        'code'=>$this->ERROR_CODE_GOODS['ORDER_NOT_BELONG_USER'],
	                        'msg'=>$this->ERROR_CODE_GOODS_ZH[$this->ERROR_CODE_GOODS['ORDER_NOT_BELONG_USER']]
	                    );
	                }
	            }else {
	                //订单不存在
	                $res=array(
	                    'code'=>$this->ERROR_CODE_GOODS['ORDER_NOT_EXIST'],
	                    'msg'=>$this->ERROR_CODE_GOODS_ZH[$this->ERROR_CODE_GOODS['ORDER_NOT_EXIST']]
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
	 * 填写退款快递单号
	 * 已同意退款的订单填写
	 * @param string $token:用户身份令牌
	 * @param string $express_number:快递单号
	 * @param int $order_id:订单ID
	 * @return array
	 * @return @param code:返回码
	 * @return @param msg:返回码说明
	 */
	public function fillInRefundExpressNum()
	{
	    if(trim(I('post.token')) and trim(I('post.express_number')) and trim(I('post.order_id')))
	    {
	        //判断用户身份
	        $token=trim(I('post.token'));
	        $User=new \Common\Model\UserModel();
	        $res_token=$User->checkToken($token);
	        if($res_token['code']!=0) {
	            //用户身份不合法
	            $res=$res_token;
	        }else {
	            $uid=$res_token['uid'];
	            $order_id=trim(I('post.order_id'));
	            $Order=new \Common\Model\OrderModel();
	            $orderMsg=$Order->getOrderDetail($order_id);
	            if($orderMsg) {
	                $data=array(
	                    'refund_express_number'=>trim(I('post.express_number'))
	                );
	                if(!$Order->create($data)) {
	                    //验证不通过
	                    $res=array(
	                        'code'=>$this->ERROR_CODE_COMMON['PARAMETER_FORMAT_ERROR'],
	                        'msg'=>$Order->getError()
	                    );
	                }else {
	                    $res_save=$Order->where("id='$order_id'")->save($data);
	                    if($res_save!==false) {
	                        $res=array(
	                            'code'=>$this->ERROR_CODE_COMMON['SUCCESS'],
	                            'msg'=>'成功',
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
	                //订单不存在
	                $res=array(
	                    'code'=>$this->ERROR_CODE_GOODS['ORDER_NOT_EXIST'],
	                    'msg'=>$this->ERROR_CODE_GOODS_ZH[$this->ERROR_CODE_GOODS['ORDER_NOT_EXIST']]
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
	 * 延迟确认收货
	 * 备注：已付款未确认收货的订单可以延迟确认收货，只可以延迟一次
	 * @param string $token:用户身份令牌
	 * @param int $order_id:订单ID
	 * @return array
	 * @return @param code:返回码
	 * @return @param msg:返回码说明
	 */
	public function delayReceiving()
	{
	    if(trim(I('post.token')) and trim(I('post.order_id')))
	    {
	        //判断用户身份
	        $token=trim(I('post.token'));
	        $User=new \Common\Model\UserModel();
	        $res_token=$User->checkToken($token);
	        if($res_token['code']!=0) {
	            //用户身份不合法
	            $res=$res_token;
	        }else {
	            $uid=$res_token['uid'];
	            //获取订单信息
	            $order_id=trim(I('post.order_id'));
	            $Order=new \Common\Model\OrderModel();
	            $orderMsg=$Order->getOrderDetail($order_id);
	            if($orderMsg) {
	                //判断订单是否属于用户
	                if($orderMsg['user_id']==$uid) {
	                    //判断订单状态-只有未确认收货订单可以延迟收货
	                    if($orderMsg['status']=='3') {
	                        //判断是否已延迟过
	                        if($orderMsg['is_delay']=='N') {
	                            $Time=new \Common\Model\TimeModel();
	                            $deliver_time=$Time->getAfterDateTime($orderMsg['deliver_time'],'2','','','7');
	                            $data=array(
	                                'deliver_time'=>$deliver_time,//延迟发货时间7天
	                                'is_delay'=>'Y',//已延迟收货
	                            );
	                            if(!$Order->create($data)) {
	                                //验证不通过
	                                //数据库自动验证，参数格式错误
	                                $res=array(
	                                    'code'=>$this->ERROR_CODE_COMMON['PARAMETER_FORMAT_ERROR'],
	                                    'msg'=>$Order->getError()
	                                );
	                            }else {
	                                //验证通过
	                                $res_save=$Order->where("id='$order_id'")->save($data);
	                                if($res_save!==false) {
	                                    //成功
	                                    $res=array(
	                                        'code'=>$this->ERROR_CODE_COMMON['SUCCESS'],
	                                        'msg'=>'成功',
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
	                            //只能延迟收货一次
	                            $res=array(
	                                'code'=>$this->ERROR_CODE_GOODS['ONLY_DELAY_RECEIPT_ONCE'],
	                                'msg'=>$this->ERROR_CODE_GOODS_ZH[$this->ERROR_CODE_GOODS['ONLY_DELAY_RECEIPT_ONCE']]
	                            );
	                        }
	                    }else {
	                        //只有未确认收货订单可以延迟收货
	                        $res=array(
	                            'code'=>$this->ERROR_CODE_GOODS['ONLY_UNCONFIRMED_RECEIPT_ORDER_CAN_DELAY_RECEIVING'],
	                            'msg'=>$this->ERROR_CODE_GOODS_ZH[$this->ERROR_CODE_GOODS['ONLY_UNCONFIRMED_RECEIPT_ORDER_CAN_DELAY_RECEIVING']]
	                        );
	                    }
	                }else {
	                    //该订单不属于您
	                    $res=array(
	                        'code'=>$this->ERROR_CODE_GOODS['ORDER_NOT_BELONG_USER'],
	                        'msg'=>$this->ERROR_CODE_GOODS_ZH[$this->ERROR_CODE_GOODS['ORDER_NOT_BELONG_USER']]
	                    );
	                }
	            }else {
	                //订单不存在
	                $res=array(
	                    'code'=>$this->ERROR_CODE_GOODS['ORDER_NOT_EXIST'],
	                    'msg'=>$this->ERROR_CODE_GOODS_ZH[$this->ERROR_CODE_GOODS['ORDER_NOT_EXIST']]
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