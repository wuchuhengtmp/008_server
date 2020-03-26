<?php
/**
 * 商城系统-订单管理
 */
namespace Admin\Controller;
use Admin\Common\Controller\AuthController;
class OrderController extends AuthController
{
	//人工录入订单
	public function add()
	{
		//获取商品分类列表
		$GoodsCat=new \Common\Model\GoodsCatModel();
		$GoodsCatList=$GoodsCat->getCatList();
		$this->assign('GoodsCatList',$GoodsCatList);
		
		//获取用户组列表
		$UserGroup=new \Common\Model\UserGroupModel();
		$UserGroupList=$UserGroup->getGroupList();
		$this->assign('UserGroupList',$UserGroupList);
		
		if(I('post.'))
		{
			layout(false);
			if(I('post.goods_id') and I('post.user_id') and I('post.num') and I('post.province') and I('post.city') and I('post.county') and I('post.detail_address') and I('post.consignee') and I('post.contact_number'))
			{
				$goods_id=I('post.goods_id');
				//获取商品信息
				$Goods=new \Common\Model\GoodsModel();
				$GoodsMsg=$Goods->getGoodsMsg($goods_id);
				if($GoodsMsg)
				{
					$num=trim(I('post.num'));
					//总价
					$allprice=$GoodsMsg['price']*$num;
					//收货地址
					$address=I('post.province').I('post.city').I('post.county').trim(I('post.detail_address'));
					//订单状态
					$status=trim(I('post.status'));
					$Order=new \Common\Model\OrderModel();
					//订单号
					$order_num=$Order->generateOrderNum();
					$data=array(
							'user_id'=>I('post.user_id'),
							'order_num'=>$order_num,
							'title'=>$GoodsMsg['title'],
							'allprice'=>$allprice,
							'address'=>$address,
							'company'=>trim(I('post.company')),
							'consignee'=>trim(I('post.consignee')),
							'contact_number'=>trim(I('post.contact_number')),
							'postcode'=>trim(I('post.postcode')),
							'express_number'=>trim(I('post.express_number')),
							'status'=>$status,
							'pay_method'=>I('post.pay_method'),
							'create_time'=>date('Y-m-d H:i:s')
					);
					switch ($status)
					{
						//已支付
						case '2':
							$data['pay_time']=date('Y-m-d H:i:s');
							break;
						//已发货
						case '3':
							$data['pay_time']=date('Y-m-d H:i:s');
							$data['deliver_time']=date('Y-m-d H:i:s');
							break;
					}
					if(!$Order->create($data))
					{
						//验证不通过
						$this->error($Order->getError());
					}else {
						//验证通过
						//开启事务
						$Order->startTrans();
						$res=$Order->add($data);
						if($res!==false)
						{
							//记录日志
							$order_id=$res;
							//日志内容
							$log_content='人工录入订单，订单号：'.$order_num.'，订单总金额：￥'.$allprice/100;
							$OrderLog=new \Common\Model\OrderLogModel();
							$res_log=$OrderLog->addLog($order_id, $log_content, 'add');
							if($res_log!==false)
							{
								//保存订单详情
								$data_detail=array(
										'order_id'=>$order_id,
										'order_num'=>$order_num,
										'goods_id'=>$goods_id,
										'goods_name'=>$GoodsMsg['title'],
										'price'=>$GoodsMsg['price'],
										'num'=>$num,
										'allprice'=>$allprice,
								);
								$OrderDetail=new \Common\Model\OrderDetailModel();
								if(!$OrderDetail->create($data_detail))
								{
									//回滚
									$Order->rollback();
									//验证不通过
									$this->error($OrderDetail->getError());
								}else {
									//验证通过
									$res_add_detail=$OrderDetail->add($data_detail);
									if($res_add_detail!==false)
									{
										//提交
										$Order->commit();
										$this->success('人工录入订单成功！',U('unpaid'));
									}else {
										//回滚
										$Order->rollback();
										$this->error('人工录入订单失败！');
									}
								}
							}else {
								//回滚
								$Order->rollback();
								$this->error('人工录入订单失败！');
							}
						}else {
							//回滚
							$Order->rollback();
							$this->error('人工录入订单失败！');
						}
					}
				}else {
					$this->error('所属商品不存在！');
				}
			}else {
				$this->error('所属商品、购买数量、收货地址、收件人、联系电话不能为空！');
			}
		}else {
			$this->display();
		}
	}
	
	//未付款订单
	public function unpaid()
	{
		$where="status='1'";
		//订单号
		if(I('get.order_num'))
		{
			$order_num=trim(I('get.order_num'));
			$where.=" and order_num='$order_num'";
		}
		//订单名称
		if(I('get.title'))
		{
			$title=trim(I('get.title'));
			$where.=" and title like '%$title%'";
		}
		//收件人
		if(I('get.consignee'))
		{
			$consignee=trim(I('get.consignee'));
			$where.=" and consignee like '%$consignee%'";
		}
		//联系电话
		if(I('get.contact_number'))
		{
			$contact_number=trim(I('get.contact_number'));
			$where.=" and contact_number like '%$contact_number%'";
		}
		$Order=new \Common\Model\OrderModel();
		$count=$Order->where($where)->count();
		$per = 15;
		if($_GET['p'])
		{
			$p=$_GET['p'];
		}else {
			$p=1;
		}
		// 分页显示输出
		$Page=new \Common\Model\PageModel();
		$show= $Page->show($count,$per);
		$this->assign('page',$show);
		 
		$orderlist = $Order->where($where)->page($p.','.$per)->order('id desc')->select();
		$this->assign('orderlist',$orderlist);
		$this->display();
	}
    
    //处理未付款订单
    public function unpaidPro($id)
    {
    	//根据订单ID获取订单详细信息
    	$Order=new \Common\Model\OrderModel();
    	$msg=$Order->getOrderDetail($id);
    	$this->assign('msg',$msg);
    	
    	if(I('post.'))
    	{
    		layout(false);
    		//总价
    		$allprice=trim(I('post.allprice'));
    		$data=array(
    				'title'=>trim(I('post.title')),
    				'allprice'=>$allprice*100,
    				'address'=>trim(I('post.address')),
    				'company'=>trim(I('post.company')),
    				'postcode'=>trim(I('post.postcode')),
    				'consignee'=>trim(I('post.consignee')),
    				'contact_number'=>trim(I('post.contact_number')),
    		);
    		if(!$Order->create($data))
    		{
    			// 验证不通过
    			$this->error($Order->getError());
    		}else {
    			// 验证成功
    			//开启事务
    			$Order->startTrans();
    			//修改订单记录
    			$res=$Order->where("id=$id")->save($data);
    			if($res!==false)
    			{
    				//日志内容
    				if($allprice!=$msg['allprice'])
    				{
    					$log_content='修改订单总价，修改前订单总价：￥'.$msg['allprice'].'，修改后订单总价：￥'.$allprice.'。';
    				}
    				$status=I('post.status');
    				if($status=='2')
    				{
    					$log_content.='修改订单为已付款，订单号：'.$msg['order_num'];
    					//处理订单
    					$pay_method=I('post.pay_method');
    					$res_treat=$Order->treatOrder($msg['order_num'], $pay_method);
    				}else {
    					$res_treat=true;
    				}
    				if($log_content)
    				{
    					$OrderLog=new \Common\Model\OrderLogModel();
    					$res_log=$OrderLog->addLog($id, $log_content, 'unpaid');
    				}else {
    					$res_log=true;
    				}
    				if($res_treat!==false and $res_log!==false)
    				{
    					//提交
    					$Order->commit();
    					$this->success('修改订单成功！',U('unpaid'));
    				}else {
    					//回滚
    					$Order->rollback();
    					$this->error('操作失败！');
    				}
    			}else {
    				//回滚
    				$Order->rollback();
    				$this->error('操作失败！');
    			}
    		}
    	}else {
    		$this->display();
    	}
    }
    
    //删除订单
    public function del($id)
    {
    	$Order=new \Common\Model\OrderModel();
    	//判断订单是否付款，只有未付款可以删除
    	$msg=$Order->getOrderMsg($id);
    	if($msg['status']!='1')
    	{
    		//只有未付款可以删除
    		echo '2';
    	}else {
    		$res=$Order->where("id=$id")->delete();
    		if($res)
    		{
    			//记录日志
    			//日志内容
    			$log_content='删除订单，订单号：'.$msg['order_num'];
    			$OrderLog=new \Common\Model\OrderLogModel();
    			$res_log=$OrderLog->addLog($id, $log_content, 'del');
    			echo '1';
    		}else {
    			echo '0';
    		}
    	}
    }
    
    //已付款订单
    public function paid()
    {
    	$where="status='2'";
    	//订单号
    	if(I('get.order_num'))
    	{
    		$order_num=trim(I('get.order_num'));
    		$where.=" and order_num='$order_num'";
    	}
    	//订单名称
    	if(I('get.title'))
    	{
    		$title=trim(I('get.title'));
    		$where.=" and title like '%$title%'";
    	}
    	//收件人
    	if(I('get.consignee'))
    	{
    		$consignee=trim(I('get.consignee'));
    		$where.=" and consignee like '%$consignee%'";
    	}
    	//联系电话
    	if(I('get.contact_number'))
    	{
    		$contact_number=trim(I('get.contact_number'));
    		$where.=" and contact_number like '%$contact_number%'";
    	}
    	$Order=new \Common\Model\OrderModel();
    	$count=$Order->where($where)->count();
    	$per = 15;
    	if($_GET['p'])
    	{
    		$p=$_GET['p'];
    	}else {
    		$p=1;
    	}
    	// 分页显示输出
    	$Page=new \Common\Model\PageModel();
    	$show= $Page->show($count,$per);
    	$this->assign('page',$show);
    		
    	$orderlist = $Order->where($where)->page($p.','.$per)->order('id desc')->select();
    	$this->assign('orderlist',$orderlist);
    	$this->display();
    }
    
    //处理已付款订单
    public function paidPro($id)
    {
    	//根据订单ID获取订单详细信息
    	$Order=new \Common\Model\OrderModel();
    	$msg=$Order->getOrderDetail($id);
    	$this->assign('msg',$msg);
    	 
    	if(I('post.'))
    	{
    		layout(false);
    		$status=I('post.status');
    		//总价
    		$allprice=trim(I('post.allprice'));
    		$data=array(
    				'title'=>trim(I('post.title')),
    				'allprice'=>$allprice*100,
    				'address'=>trim(I('post.address')),
    				'company'=>trim(I('post.company')),
    				'postcode'=>trim(I('post.postcode')),
    				'consignee'=>trim(I('post.consignee')),
    				'contact_number'=>trim(I('post.contact_number')),
    				'logistics'=>trim(I('post.logistics')),
    				'express_number'=>trim(I('post.express_number')),
    				'status'=>$status,
    				'pay_method'=>I('post.pay_method')
    		);
    		if($status=='3')
    		{
    			$data['deliver_time']=date('Y-m-d H:i:s');
    		}
    		if(!$Order->create($data))
    		{
    			// 验证不通过
    			$this->error($Order->getError());
    		}else {
    			// 验证成功
    			//开启事务
    			$Order->startTrans();
    			//修改订单记录
    			$res=$Order->where("id=$id")->save($data);
    			if($res!==false)
    			{
    				//日志内容
    				if($allprice!=$msg['allprice'])
    				{
    					$log_content='修改订单总价，修改前订单总价：￥'.$msg['allprice'].'，修改后订单总价：￥'.$allprice.'。';
    				}
    				if($status=='3')
    				{
    					$log_content.='修改订单为已发货，订单号：'.$msg['order_num'];
    				}
    				if($log_content)
    				{
    					$OrderLog=new \Common\Model\OrderLogModel();
    					$res_log=$OrderLog->addLog($id, $log_content, 'paid');
    					if($res_log!==false)
    					{
    						//提交
    						$Order->commit();
    						$this->success('修改订单成功！',U('paid'));
    					}else {
    						//回滚
    						$Order->rollback();
    						$this->error('操作失败！');
    					}
    				}else {
    					//提交
    					$Order->commit();
    					$this->success('修改订单成功！',U('paid'));
    				}
    			}else {
    				//回滚
    				$Order->rollback();
    				$this->error('操作失败！');
    			}
    		}
    	}else {
    		$this->display();
    	}
    }
    
    //已发货订单
    public function send()
    {
    	$where="status='3'";
    	//订单号
    	if(I('get.order_num'))
    	{
    		$order_num=trim(I('get.order_num'));
    		$where.=" and order_num='$order_num'";
    	}
    	//订单名称
    	if(I('get.title'))
    	{
    		$title=trim(I('get.title'));
    		$where.=" and title like '%$title%'";
    	}
    	//收件人
    	if(I('get.consignee'))
    	{
    		$consignee=trim(I('get.consignee'));
    		$where.=" and consignee like '%$consignee%'";
    	}
    	//联系电话
    	if(I('get.contact_number'))
    	{
    		$contact_number=trim(I('get.contact_number'));
    		$where.=" and contact_number like '%$contact_number%'";
    	}
    	$Order=new \Common\Model\OrderModel();
    	$count=$Order->where($where)->count();
    	$per = 15;
    	if($_GET['p'])
    	{
    		$p=$_GET['p'];
    	}else {
    		$p=1;
    	}
    	// 分页显示输出
    	$Page=new \Common\Model\PageModel();
    	$show= $Page->show($count,$per);
    	$this->assign('page',$show);
    
    	$orderlist = $Order->where($where)->page($p.','.$per)->order('id desc')->select();
    	$this->assign('orderlist',$orderlist);
    	$this->display();
    }
    
    //处理已发货订单
    public function sendPro($id)
    {
    	//根据订单ID获取订单详细信息
    	$Order=new \Common\Model\OrderModel();
    	$msg=$Order->getOrderDetail($id);
    	$this->assign('msg',$msg);
    
    	if(I('post.'))
    	{
    		layout(false);
    		$status=I('post.status');
    		//总价
    		$allprice=trim(I('post.allprice'));
    		$data=array(
    				'title'=>trim(I('post.title')),
    				'allprice'=>$allprice*100,
    				'address'=>trim(I('post.address')),
    				'company'=>trim(I('post.company')),
    				'postcode'=>trim(I('post.postcode')),
    				'consignee'=>trim(I('post.consignee')),
    				'contact_number'=>trim(I('post.contact_number')),
    				'logistics'=>trim(I('post.logistics')),
    				'express_number'=>trim(I('post.express_number')),
    				'status'=>$status,
    				'pay_method'=>I('post.pay_method')
    		);
    		if($status=='4')
    		{
    			$data['finish_time']=date('Y-m-d H:i:s');
    		}
    		if(!$Order->create($data))
    		{
    			// 验证不通过
    			$this->error($Order->getError());
    		}else {
    			// 验证成功
    			//开启事务
    			$Order->startTrans();
    			//修改订单记录
    			$res=$Order->where("id=$id")->save($data);
    			if($res!==false)
    			{
    				//日志内容
    				if($allprice!=$msg['allprice'])
    				{
    					$log_content='修改订单总价，修改前订单总价：￥'.$msg['allprice'].'，修改后订单总价：￥'.$allprice.'。';
    				}
    				if($status=='4')
    				{
    					$log_content.='修改订单为已确认收货，订单号：'.$msg['order_num'];
    				}
    				if($log_content)
    				{
    					$OrderLog=new \Common\Model\OrderLogModel();
    					$res_log=$OrderLog->addLog($id, $log_content, 'send');
    					if($res_log!==false)
    					{
    						//提交
    						$Order->commit();
    						$this->success('修改订单成功！',U('send'));
    					}else {
    						//回滚
    						$Order->rollback();
    						$this->error('操作失败！');
    					}
    				}else {
    					//提交
    					$Order->commit();
    					$this->success('修改订单成功！',U('send'));
    				}
    			}else {
    				//回滚
    				$Order->rollback();
    				$this->error('操作失败！');
    			}
    		}
    	}else {
    		$this->display();
    	}
    }
    
    //已确认收货订单
    public function finish()
    {
    	$where="status='4'";
    	//订单号
    	if(I('get.order_num'))
    	{
    		$order_num=trim(I('get.order_num'));
    		$where.=" and order_num='$order_num'";
    	}
    	//订单名称
    	if(I('get.title'))
    	{
    		$title=trim(I('get.title'));
    		$where.=" and title like '%$title%'";
    	}
    	//收件人
    	if(I('get.consignee'))
    	{
    		$consignee=trim(I('get.consignee'));
    		$where.=" and consignee like '%$consignee%'";
    	}
    	//联系电话
    	if(I('get.contact_number'))
    	{
    		$contact_number=trim(I('get.contact_number'));
    		$where.=" and contact_number like '%$contact_number%'";
    	}
    	$Order=new \Common\Model\OrderModel();
    	$count=$Order->where($where)->count();
    	$per = 15;
    	if($_GET['p'])
    	{
    		$p=$_GET['p'];
    	}else {
    		$p=1;
    	}
    	// 分页显示输出
    	$Page=new \Common\Model\PageModel();
    	$show= $Page->show($count,$per);
    	$this->assign('page',$show);
    
    	$orderlist = $Order->where($where)->page($p.','.$per)->order('id desc')->select();
    	$this->assign('orderlist',$orderlist);
    	$this->display();
    }
    
    //处理已确认收货订单
    public function finishPro($id)
    {
    	//根据订单ID获取订单详细信息
    	$Order=new \Common\Model\OrderModel();
    	$msg=$Order->getOrderDetail($id);
    	$this->assign('msg',$msg);
    
    	$this->display();
    }
    
    //已评价/已结束订单
    public function comment()
    {
    	$where="status='5'";
    	//订单号
    	if(I('get.order_num'))
    	{
    		$order_num=trim(I('get.order_num'));
    		$where.=" and order_num='$order_num'";
    	}
    	//订单名称
    	if(I('get.title'))
    	{
    		$title=trim(I('get.title'));
    		$where.=" and title like '%$title%'";
    	}
    	//收件人
    	if(I('get.consignee'))
    	{
    		$consignee=trim(I('get.consignee'));
    		$where.=" and consignee like '%$consignee%'";
    	}
    	//联系电话
    	if(I('get.contact_number'))
    	{
    		$contact_number=trim(I('get.contact_number'));
    		$where.=" and contact_number like '%$contact_number%'";
    	}
    	$Order=new \Common\Model\OrderModel();
    	$count=$Order->where($where)->count();
    	$per = 15;
    	if($_GET['p'])
    	{
    		$p=$_GET['p'];
    	}else {
    		$p=1;
    	}
    	// 分页显示输出
    	$Page=new \Common\Model\PageModel();
    	$show= $Page->show($count,$per);
    	$this->assign('page',$show);
    
    	$orderlist = $Order->where($where)->page($p.','.$per)->order('id desc')->select();
    	$this->assign('orderlist',$orderlist);
    	$this->display();
    }
    
    //处理已评价/已结束订单
    public function commentPro($id)
    {
    	//根据订单ID获取订单详细信息
    	$Order=new \Common\Model\OrderModel();
    	$msg=$Order->getOrderDetail($id);
    	$this->assign('msg',$msg);
    
    	$this->display();
    }
    
    //申请退款订单
    public function refund()
    {
        $where="status='6'";
        //订单号
        if(I('get.order_num')) {
            $order_num=trim(I('get.order_num'));
            $where.=" and order_num='$order_num'";
        }
        //订单名称
        if(I('get.title')) {
            $title=trim(I('get.title'));
            $where.=" and title like '%$title%'";
        }
        //收件人
        if(I('get.consignee')) {
            $consignee=trim(I('get.consignee'));
            $where.=" and consignee like '%$consignee%'";
        }
        //联系电话
        if(I('get.contact_number')) {
            $contact_number=trim(I('get.contact_number'));
            $where.=" and contact_number like '%$contact_number%'";
        }
        $Order=new \Common\Model\OrderModel();
        $count=$Order->where($where)->count();
        $per = 15;
        if($_GET['p']) {
            $p=$_GET['p'];
        }else {
            $p=1;
        }
        // 分页显示输出
        $Page=new \Common\Model\PageModel();
        $show= $Page->show($count,$per);
        $this->assign('page',$show);
        
        $orderlist = $Order->where($where)->page($p.','.$per)->order('id desc')->select();
        $this->assign('orderlist',$orderlist);
        $this->display();
    }
    
    //处理申请退款订单
    public function refundPro($id)
    {
        //根据订单ID获取订单详细信息
        $Order=new \Common\Model\OrderModel();
        $msg=$Order->getOrderDetail($id);
        
        if($_POST){
            layout(false);
            if(trim(I('post.check_result'))){
                $check_result=trim(I('post.check_result'));
                $drawback_refuse_reason=trim(I('post.drawback_refuse_reason'));
                $res_t=$Order->refund($id, $check_result,$drawback_refuse_reason);
                if($res_t===true){
                    $this->success('处理成功！',U('refund'));
                }else {
                    $this->error('处理失败！');
                }
            }else {
                $this->error('请选择审核结果！');
            }
        }else {
            $this->assign('msg',$msg);
            
            $this->display();
        }
    }
    
    //退款成功订单
    public function refundSuccess()
    {
        $where="status='7'";
        //订单号
        if(I('get.order_num')) {
            $order_num=trim(I('get.order_num'));
            $where.=" and order_num='$order_num'";
        }
        //订单名称
        if(I('get.title')) {
            $title=trim(I('get.title'));
            $where.=" and title like '%$title%'";
        }
        //收件人
        if(I('get.consignee')) {
            $consignee=trim(I('get.consignee'));
            $where.=" and consignee like '%$consignee%'";
        }
        //联系电话
        if(I('get.contact_number')) {
            $contact_number=trim(I('get.contact_number'));
            $where.=" and contact_number like '%$contact_number%'";
        }
        $Order=new \Common\Model\OrderModel();
        $count=$Order->where($where)->count();
        $per = 15;
        if($_GET['p']) {
            $p=$_GET['p'];
        }else {
            $p=1;
        }
        // 分页显示输出
        $Page=new \Common\Model\PageModel();
        $show= $Page->show($count,$per);
        $this->assign('page',$show);
        
        $orderlist = $Order->where($where)->page($p.','.$per)->order('id desc')->select();
        $this->assign('orderlist',$orderlist);
        $this->display();
    }
    
    //退款成功订单详情
    public function refundSuccessPro($id)
    {
        //根据订单ID获取订单详细信息
        $Order=new \Common\Model\OrderModel();
        $msg=$Order->getOrderDetail($id);
        $this->assign('msg',$msg);
        
        $this->display();
    }
    
    //退款失败订单
    public function refundFail()
    {
        $where="status='8'";
        //订单号
        if(I('get.order_num')) {
            $order_num=trim(I('get.order_num'));
            $where.=" and order_num='$order_num'";
        }
        //订单名称
        if(I('get.title')) {
            $title=trim(I('get.title'));
            $where.=" and title like '%$title%'";
        }
        //收件人
        if(I('get.consignee')) {
            $consignee=trim(I('get.consignee'));
            $where.=" and consignee like '%$consignee%'";
        }
        //联系电话
        if(I('get.contact_number')) {
            $contact_number=trim(I('get.contact_number'));
            $where.=" and contact_number like '%$contact_number%'";
        }
        $Order=new \Common\Model\OrderModel();
        $count=$Order->where($where)->count();
        $per = 15;
        if($_GET['p']) {
            $p=$_GET['p'];
        }else {
            $p=1;
        }
        // 分页显示输出
        $Page=new \Common\Model\PageModel();
        $show= $Page->show($count,$per);
        $this->assign('page',$show);
        
        $orderlist = $Order->where($where)->page($p.','.$per)->order('id desc')->select();
        $this->assign('orderlist',$orderlist);
        $this->display();
    }
    
    //退款失败订单详情
    public function refundFailPro($id)
    {
        //根据订单ID获取订单详细信息
        $Order=new \Common\Model\OrderModel();
        $msg=$Order->getOrderDetail($id);
        $this->assign('msg',$msg);
        
        $this->display();
    }
    
    //订单数量统计
    public function StatisticsNum()
    {
    	if(I('get.'))
    	{
    		$createtime=I('get.createtime');
    		switch ($createtime)
    		{
    			case 3 :
    				$p_title = '本月';
    				$where = "date_format(pay_time,'%Y-%m')=date_format(now(),'%Y-%m')";
    				break;
    			case 4 :
    				$p_title = '本周';
    				$where = "YEARWEEK(date_format(pay_time,'%Y-%m-%d')) = YEARWEEK(now())";
    				break;
    			case 5 :
    				$p_title = '今天';
    				$where = 'TO_DAYS(pay_time) = TO_DAYS(NOW())';
    				break;
    			case 6 :
    				$p_title = '近一周';
    				$where = 'DATE_SUB(CURDATE(), INTERVAL 7 DAY) <= date(pay_time)';
    				break;
    			case 7 :
    				$p_title = '近一月';
    				$where = 'DATE_SUB(CURDATE(), INTERVAL 30 DAY) <= date(pay_time)';
    				break;
    			case 8 :
    				$p_title = '上一月';
    				$where = "date_format(pay_time,'%Y-%m')=date_format(DATE_SUB(curdate(), INTERVAL 1 MONTH),'%Y-%m')";
    				break;
    			default:
    				$p_title = '本月';
    				$where = "date_format(pay_time,'%Y-%m')=date_format(now(),'%Y-%m')";
    				break;
    		}
    		//根据时间查询
    		if(I('get.begin_time') and I('get.end_time'))
    		{
    			$begin_time=I('get.begin_time');
    			$end_time=I('get.end_time');
    			$where="pay_time BETWEEN '$begin_time' AND '$end_time'";
    		}
    	}else {
    		$p_title = '本月';
    		$where = "date_format(pay_time,'%Y-%m')=date_format(now(),'%Y-%m')";
    	}
    	$this->assign('p_title',$p_title);
    
    	$where.=" and status not in (1,7)";
    	$sql="SELECT count(id) as num,date(pay_time) as date FROM __PREFIX__order WHERE $where GROUP BY date(pay_time)";
    	$list=M()->query($sql);
    	$this->assign('list',$list);
    
    	$this->display();
    }
    
    //订单金额统计
    public function StatisticsMoney()
    {
    	if(I('get.'))
    	{
    		$createtime=I('get.createtime');
    		switch ($createtime)
    		{
    			case 3 :
    				$p_title = '本月';
    				$where = "date_format(pay_time,'%Y-%m')=date_format(now(),'%Y-%m')";
    				break;
    			case 4 :
    				$p_title = '本周';
    				$where = "YEARWEEK(date_format(pay_time,'%Y-%m-%d')) = YEARWEEK(now())";
    				break;
    			case 5 :
    				$p_title = '今天';
    				$where = 'TO_DAYS(pay_time) = TO_DAYS(NOW())';
    				break;
    			case 6 :
    				$p_title = '近一周';
    				$where = 'DATE_SUB(CURDATE(), INTERVAL 7 DAY) <= date(pay_time)';
    				break;
    			case 7 :
    				$p_title = '近一月';
    				$where = 'DATE_SUB(CURDATE(), INTERVAL 30 DAY) <= date(pay_time)';
    				break;
    			case 8 :
    				$p_title = '上一月';
    				$where = "date_format(pay_time,'%Y-%m')=date_format(DATE_SUB(curdate(), INTERVAL 1 MONTH),'%Y-%m')";
    				break;
    			default:
    				$p_title = '本月';
    				$where = "date_format(pay_time,'%Y-%m')=date_format(now(),'%Y-%m')";
    				break;
    		}
    		//根据时间查询
    		if(I('get.begin_time') and I('get.end_time'))
    		{
    			$begin_time=I('get.begin_time');
    			$end_time=I('get.end_time');
    			$where="pay_time BETWEEN '$begin_time' AND '$end_time'";
    		}
    	}else {
    		$p_title = '本月';
    		$where = "date_format(pay_time,'%Y-%m')=date_format(now(),'%Y-%m')";
    	}
    	$this->assign('p_title',$p_title);
    
    	$where.=" and status not in (1,7)";
    	$sql="SELECT sum(allprice) as money,date(pay_time) as date FROM __PREFIX__order WHERE $where GROUP BY date(pay_time)";
    	$list=M()->query($sql);
    	$this->assign('list',$list);
    
    	$this->display();
    }
    
    //订单统计
    public function statistics()
    {
    	//获取商品分类列表
    	$GoodsCat=new \Common\Model\GoodsCatModel();
    	$catlist=$GoodsCat->getCatList();
    	$this->assign('catlist',$catlist);
    	
    	if(I('get.'))
    	{
    		$createtime=I('get.createtime');
			switch ($createtime) 
			{
				case 1 :
					$p_title = '本年度';
					$where = "date_format(create_time,'%Y-')=date_format(now(),'%Y-')";
					break;
				case 2 :
					$p_title = '本季度';
					$where = 'QUARTER(create_time)=QUARTER(now())';
					break;
				case 3 :
					$p_title = '本月';
					$where = "date_format(create_time,'%Y-%m')=date_format(now(),'%Y-%m')";
					break;
				case 4 :
					$p_title = '本周';
					$where = "YEARWEEK(date_format(create_time,'%Y-%m-%d')) = YEARWEEK(now())";
					break;
				case 5 :
					$p_title = '今天';
					$where = 'TO_DAYS(create_time) = TO_DAYS(NOW())';
					break;
				case 6 :
					$p_title = '近一周';
					$where = 'DATE_SUB(CURDATE(), INTERVAL 7 DAY) <= date(create_time)';
					break;
				case 7 :
					$p_title = '近一月';
					$where = 'DATE_SUB(CURDATE(), INTERVAL 30 DAY) <= date(create_time)';
					break;
				case 8 :
					$p_title = '上一月';
					$where = "date_format(create_time,'%Y-%m')=date_format(DATE_SUB(curdate(), INTERVAL 1 MONTH),'%Y-%m')";
					break;
				case 9 :
					$p_title = '上一季度';
					$where = 'QUARTER(create_time)=QUARTER(DATE_SUB(now(),interval 1 QUARTER))';
					break;
				case 10 :
					$p_title = '上一年度';
					$where = 'YEAR(create_time)=YEAR(date_sub(now(),interval 1 YEAR))';
					break;
				default:
					$p_title='全部';
					$where='1';
    		}
    		//根据时间查询
    		if(I('get.begin_time') and I('get.end_time'))
    		{
    			$begin_time=I('get.begin_time');
    			$end_time=I('get.end_time');
    			$where="create_time BETWEEN '$begin_time' AND '$end_time'";
    		}
    	}else {
    		$p_title='全部';
    		$where='1';
    	}
    	
    	//饼状图
    	//标题
    	$pie3d_title=$p_title.'订单统计分布图';
    	$this->assign('pie3d_title',$pie3d_title);
    	//宽、高
    	$this->assign('pie3d_width',500);
    	$this->assign('pie3d_height',300);
    	//数据
    	$order=new \Common\Model\OrderModel();
    	$sql="SELECT count(status),sum(allprice),status FROM __PREFIX__order WHERE $where GROUP BY status";
    	$res_s=$order->query($sql);
    	$num=count($res_s);
    	if($num>0)
    	{
    		$d1=$d2=$d3=$d4=$d5=$d6=$d7=$d8=0;
    		$m1=$m2=$m3=$m4=$m5=$m6=$m7=$m8=0;
    		for($i=0;$i<=$num;$i++)
    		{
    			$status=$res_s[$i]['status'];
    			switch ($status) {
					case 1 :
						$d1 = $res_s [$i] ['count(status)'];
						$m1 = $res_s [$i] ['sum(allprice)'];
						break;
					case 2 :
						$d2 = $res_s [$i] ['count(status)'];
						$m2 = $res_s [$i] ['sum(allprice)'];
						break;
					case 3 :
						$d3 = $res_s [$i] ['count(status)'];
						$m3 = $res_s [$i] ['sum(allprice)'];
						break;
					case 4 :
						$d4 = $res_s [$i] ['count(status)'];
						$m4 = $res_s [$i] ['sum(allprice)'];
						break;
					case 5 :
						$d5 = $res_s [$i] ['count(status)'];
						$m5 = $res_s [$i] ['sum(allprice)'];
						break;
					case 6 :
						$d6 = $res_s [$i] ['count(status)'];
						$m6 = $res_s [$i] ['sum(allprice)'];
						break;
					case 7 :
						$d7 = $res_s [$i] ['count(status)'];
						$m7 = $res_s [$i] ['sum(allprice)'];
						break;
					case 8 :
						$d8 = $res_s [$i] ['count(status)'];
						$m8 = $res_s [$i] ['sum(allprice)'];
						break;
					default :
						break;
				}
    		}
    		$pie3d_data = $d1.','.$d2.','.$d3.','.$d4.','.$d5.','.$d6.','.$d7.','.$d8.',';
    		$money=array($m1,$m2,$m3,$m4,$m5,$m6,$m7,$m8);
    	}else {
    		$pie3d_data='';
    	}
    	$this->assign('money',$money);
    	$this->assign('pie3d_data',$pie3d_data);
    	//组别描述
    	$pie3d_legends='未支付(%d),已支付(%d),已发货(%d),已确认收货、未评价(%d),已确认收货、已评价(%d),申请退款(%d),退款成功(%d),拒绝退款(%d)';
    	$this->assign('pie3d_legends',$pie3d_legends);
    	
    	//条形图
    	//设置标题
    	$this->assign('bar_title','近一个月订单统计');
    	$this->assign('bar_xtitle','日期 ');
    	$this->assign('bar_ytitle','金额（元）');
    	//宽、高
    	$this->assign('bar_width',960);
    	$this->assign('bar_height',380);
    	//颜色
    	$this->assign('data1_color','red');
    	$this->assign('data2_color','lightblue');
    	//数据
    	$bar_where='DATE_SUB(CURDATE(), INTERVAL 30 DAY) <= date(createtime)';
    	//①先获取所有还有订单的日期
    	$sql_d="SELECT date(createtime) as date FROM __PREFIX__order WHERE $bar_where GROUP BY TO_DAYS(createtime)";
    	$res_d=$order->query($sql_d);
    	$d_num=count($res_d);
    	//②获取已支付的订单-根据日期分组
    	$sql1="SELECT count(id),sum(allprice) as price,date(createtime) as date FROM __PREFIX__order WHERE $bar_where and status not in(1,7) GROUP BY TO_DAYS(createtime)";
    	$res_s1=$order->query($sql1);
    	$bar_num1=count($res_s1);
    	for($i=0;$i<$bar_num1;$i++)
    	{
    		$tmp_a[$res_s1[$i]['date']]=$res_s1[$i];
    	}
    	//③获取未支付的订单-根据日期分组
    	$sql2="SELECT count(id),sum(allprice) as price,date(createtime) as date FROM __PREFIX__order WHERE $bar_where and status in(1,7) GROUP BY TO_DAYS(createtime)";
    	$res_s2=$order->query($sql2);
    	$bar_num2=count($res_s2);
    	for($i=0;$i<$bar_num2;$i++)
    	{
    		$tmp_b[$res_s2[$i]['date']]=$res_s2[$i];
    	}
    	//补齐缺少的日期
    	for($i=0;$i<$d_num;$i++)
    	{
    		//获取所有日期
    		$bar_datax.=$res_d[$i]['date'].',';
    		
    		if(!array_key_exists($res_d[$i]['date'],$tmp_a))
    		{
    			$tmp_a[$res_d[$i]['date']]=array(
    				'count(id)'=>0,
    				'price'=>0,
    				'date'=>$res_d[$i]['date'],
    			);
    		}
    		if(!array_key_exists($res_d[$i]['date'],$tmp_b))
    		{
    			$tmp_b[$res_d[$i]['date']]=array(
    					'count(id)'=>0,
    					'price'=>0,
    					'date'=>$res_d[$i]['date'],
    			);
    		}
    	}
    	//将数组重新排序得到新的已支付、未支付数组
    	$tmp_a=array_values($tmp_a);
    	$data1=array_values(array_sort($tmp_a, 'date', 'asc'));
    	$tmp_b=array_values($tmp_b);
    	$data2=array_values(array_sort($tmp_b, 'date', 'asc'));
    	for($i=0;$i<$d_num;$i++)
    	{
    		$bar_data1y.=$data1[$i]['price'].',';
    		$bar_data2y.=$data2[$i]['price'].',';
    	}
    	$bar_data1y=substr($bar_data1y,0,-1);
    	$bar_data2y=substr($bar_data2y,0,-1);
    	$bar_datax=substr($bar_datax,0,-1);
    	$this->assign('bar_data1y',$bar_data1y);
    	$this->assign('bar_data2y',$bar_data2y);
    	$this->assign('bar_datax',$bar_datax);
    	$this->display();
    }
}
?>