<?php

/**
 * 淘宝订单管理
 */
namespace Admin\Controller;

use Admin\Common\Controller\AuthController;

class TbOrderController extends AuthController 
{
	//订单列表
	public function index()
	{
		$where='1';
		//商品名称
		if(trim(I('get.item_title'))) {
			$item_title=trim(I('get.item_title'));
			$where="item_title like '%$item_title%'";
		}
		//订单号
		if(trim(I('get.trade_id'))) {
			$trade_id=trim(I('get.trade_id'));
			$where.=" and (trade_id='$trade_id' or trade_parent_id='$trade_id')";
		}
		//订单状态
		if(trim(I('get.tk_status'))) {
			$tk_status=trim(I('get.tk_status'));
			$where.=" and tk_status='$tk_status'";
		}
		//是否维权
		if(trim(I('get.is_refund'))) {
			$is_refund=trim(I('get.is_refund'));
			$where.=" and is_refund='$is_refund'";
		}
		//所属用户
		if(trim(I('get.username'))) {
			$username=trim(I('get.username'));
			$User=new \Common\Model\UserModel();
			$res_u=$User->where("username='$username' or phone='$username' or email='$username'")->find();
			if($res_u['uid'])
			{
				$user_id=$res_u['uid'];
				$where.=" and user_id='$user_id'";
			}else {
				layout(false);
				$this->error('您查询的用户不存在，请核实');
			}
		}
		$TbOrder=new \Common\Model\TbOrderModel();
		$count=$TbOrder->where($where)->count();
		$per = 15;
		if($_GET['p']) {
			$p=$_GET['p'];
		}else {
			$p=1;
		}
		$Page=new \Common\Model\PageModel();
		$show= $Page->show($count,$per);// 分页显示输出
		$this->assign('page',$show);
		 
		$list = $TbOrder->where($where)->page($p.','.$per)->order('id desc')->select();
		$this->assign('list',$list);
		 
		$this->display();
	}
	
	//订单详情
	public function msg($id)
	{
		$TbOrder=new \Common\Model\TbOrderModel();
		$msg=$TbOrder->getOrderMsg($id);
		$this->assign('msg',$msg);
		
		$this->display();
	}
	
	// 处理遗漏淘宝订单
	public function treatOrder() 
	{
		if ($_POST) {
			layout ( false );
			if (trim (I('post.order_scene')) and trim (I('post.time')) ) {
				//查询时间类型，1：按照订单淘客创建时间查询，2:按照订单淘客付款时间查询，3:按照订单淘客结算时间查询
				$query_type=trim(I('post.query_type'));
				//淘客订单状态，12-付款，13-关闭，14-确认收货，3-结算成功;不传，表示所有状态
				$tk_status=trim(I('post.tk_status'));
				//场景订单场景类型，1:常规订单，2:渠道订单，3:会员运营订单
				$order_scene=trim(I('post.order_scene'));
				// 订单查询开始时间，格式：2016-05-23 12:18:22
				$start_time=trim(I('post.time'));
				if(is_datetime($start_time)===false) {
				    $this->error('请填写正确的订单时间！');
				    exit();
				}
				//结束时间往后推20分钟
				$Time=new \Common\Model\TimeModel();
				$end_time=$Time->getAfterDateTime($start_time,'2','','','','','+20');
				//推广者角色类型,2:二方，3:三方，不传，表示所有角色
				$member_type=trim(I('post.member_type'));
				$page_size=100;
				//位点，除第一页之外，都需要传递；前端原样返回。
				$position_index='';
				//跳转类型，当向前或者向后翻页必须提供,-1: 向前翻页,1：向后翻页
				$jump_type=1;
				
				// 循环查询10万条订单最多，10分钟内最多10万条
				$TbOrder = new \Common\Model\TbOrderModel ();
				
				$num = 100000 / $page_size;
				// 成功条数
				$count = 0;
				for($i = 0; $i < $num; $i ++) {
					$page_no = $i + 1;
					// 淘宝订单接口
					$res_list=$TbOrder->pullOrder($query_type, $tk_status, $order_scene, $start_time, $end_time, $member_type, $page_no, $page_size, $position_index, $jump_type);
					if($res_list['code']==0){
					    $count+=$res_list['data']['count'];
					    $position_index=$res_list['data']['position_index'];
					    $list_num=$res_list['data']['list_num'];
					    if ($list_num == 100)  {
					        // 100条，可能还有更多订单，继续查询
					        continue;
					    } else {
					        // 不超出100条，没有更多结果
					        // 跳出循环
					        break;
					    }
					}else {
					    $this->error('处理失败！');
					    // 跳出循环
					    break;
					}
				}
				$success_msg='成功执行：' . $count;
				$this->success($success_msg);
			} else {
				$this->error ( '请选择订单类型、订单时间！' );
			}
		} else {
			$this->display ();
		}
	}
	
	// 处理无归属淘宝订单
	public function allotOrder()
	{
		if(I('post.')) {
			layout(false);
			if(trim(I('post.trade_id')) and trim(I('post.phone'))) {
				$trade_id=trim(I('post.trade_id'));
				//查询淘宝订单
				$TbOrder=new \Common\Model\TbOrderModel();
				$orderMsg=$TbOrder->getOrderMsgByTradeId($trade_id);
				if($orderMsg) {
				    if($orderMsg['tk_status']=='3') {
				        //该订单已结算
				        //所属用户
				        $phone=trim(I('post.phone'));
				        $User=new \Common\Model\UserModel();
				        $res_u=$User->where("phone='$phone'")->find();
				        if($res_u['uid']) {
				            $user_id=$res_u['uid'];
				            $data_o = array (
				                'user_id' => $user_id,
				            );
				            //开启事务
				            $TbOrder->startTrans();
				            $res_order = $TbOrder->where ( "trade_id='$trade_id'" )->save ( $data_o );
				            // 原来没有所属人，给对应用户返利
				            if($orderMsg['user_id']) {
				                $res_treat=true;
				            }else {
				                $res_treat = $TbOrder->treat ( $trade_id, $orderMsg ['total_commission_fee'] );
				            }
				            if($res_order!==false and $res_treat!==false) {
				                //提交事务
				                $TbOrder->commit();
				                $this->success('重新分配成功！');
				            }else {
				                //回滚
				                $TbOrder->rollback();
				                $this->error('处理失败！','',10);
				            }
				        }else {
				            $this->error('所属用户不存在！','',10);
				        }
				    }else {
				        //该订单尚未结算，只修改所属用户
				        //所属用户
				        $phone=trim(I('post.phone'));
				        $User=new \Common\Model\UserModel();
				        $res_u=$User->where("phone='$phone'")->find();
				        if($res_u['uid']) {
				            $user_id=$res_u['uid'];
				            $data_o = array (
				                'user_id' => $user_id,
				            );
				            $res_order = $TbOrder->where ( "trade_id='$trade_id'" )->save ( $data_o );
				            if($res_order!==false) {
				                $this->success('重新分配成功！');
				            }else {
				                $this->error('处理失败！','',10);
				            }
				        }else {
				            $this->error('所属用户不存在！','',10);
				        }
				    }
				}else {
					$this->error('该淘宝订单不存在，请核实！','',10);
				}
			}else {
				$this->error('淘宝订单号、用户手机号码不能为空！');
			}
		}else {
			$this->display();
		}
	}
	
	//批量处理无归属淘宝订单
	public function allotOrderAll()
	{
	    if($_POST){
	        layout(false);
	        $where="(user_id='' or user_id is null)";
	        if(trim(I('post.start_date')) and trim(I('post.end_date'))){
	            $start_date=trim(I('post.start_date'));
	            $end_date=trim(I('post.end_date'));
	            $where.=" and create_time between '$start_date' and '$end_date'";
	        }
	        //获取所有没有所属用户的订单
	        $TbOrder=new \Common\Model\TbOrderModel();
	        $orderList=$TbOrder->where($where)->select();
	        $User=new \Common\Model\UserModel();
	        $count=0;
	        foreach ($orderList as $l){
	            $trade_id=$l['trade_id'];
	            // 淘宝用户uid
	            $tb_uid = substr ( $trade_id, - 6 );
	            //判断所属用户
	            $user_id='';
	            //渠道
	            if($l['relation_id'] or $l['adzone_name']=='渠道专用'){
	                $relation_id=$l['relation_id'];
	                $res_r=$User->where("tb_rid='$relation_id' or tb_uid='$tb_uid'")->field('uid')->find();
	                if($res_r['uid']){
	                    $user_id=$res_r['uid'];
	                }
	            }
	            if($user_id==''){
	                //不存在渠道，继续判断用户
	                // 淘宝推广位pid
	                $tb_pid = 'mm_'.TBK_APPID.'_' . $l ['site_id'] . '_' . $l ['adzone_id'];
	                //先判断微信群主推广位pid
	                $res_user = $User->where ( "tb_pid_master='$tb_pid'" )->find ();
	                //并且判断VIP会员没有到期
	                if ($res_user ['uid']) {
	                    // 用户存在，给对应用户返利
	                    $user_id = $res_user ['uid'];
	                } else {
	                    //再判断淘宝用户个人pid
	                    $res_user2 = $User->where ( "tb_pid='$tb_pid' and tb_uid='$tb_uid'" )->field('uid')->find ();
	                    if ($res_user2 ['uid']) {
	                        // 用户存在，给对应用户返利
	                        $user_id = $res_user2 ['uid'];
	                    }
	                }
	            }
	            //如果用所属用户，进行分配处理
	            if($user_id){
	                $data_o['user_id']=$user_id;
	                $res_order = $TbOrder->where ( "trade_id='$trade_id'" )->save ( $data_o );
	                if ($l ['status'] == '1' and $l ['tk_status'] == '3') {
	                    // 尚未结算，给用户返利
	                    $res_treat = $TbOrder->treat ( $trade_id, $l ['total_commission_fee'] );
	                }
	                $count++;
	            }
	        }
	        $this->success('成功处理'.$count.'条');
	    }else {
	        $this->display();
	    }
	    
	}
	
	//处理维权订单
	public function refund($id)
	{
	    $TbOrder=new \Common\Model\TbOrderModel();
	    $msg=$TbOrder->where("id='$id'")->find();
	    if($msg) {
	        if($msg['tk_status']=='3') {
	            if($msg['user_id']) {
	                if($msg['is_refund']=='N') {
	                    if($_POST){
	                        layout(false);
	                        if(trim(I('post.refund_money'))){
	                            //应该扣除的金额比例
	                            $refund_money=trim(I('post.refund_money'));
	                            //退款金额不能大于付款金额
	                            if($refund_money>$msg['pay_price']){
	                                $this->error('退款金额不能大于付款金额');
	                                exit();
	                            }
	                            $rate=$refund_money/$msg['pay_price'];
	                            //淘宝订单号
	                            $order_id=$msg['trade_id'];
	                            //查询该订单所有返利
	                            $UserBalanceRecord=new \Common\Model\UserBalanceRecordModel();
	                            $list=$UserBalanceRecord->where("type='1' and order_id='$order_id' and action!='tbk_refund'")->order('id asc')->select();
	                            $User=new \Common\Model\UserModel();
	                            //已处理过的同一订单同一用户不进行重复处理
	                            $treat_user=array();
	                            //开启事务
	                            $User->startTrans();
	                            foreach ($list as $l) {
	                                //扣除用户相应余额
	                                $money=$l['money']/100*$rate;
	                                //四舍五不入
	                                $money=keepTwoDecimalPlaces($money);
	                                $user_id=$l['user_id'];
	                                if(!empty($treat_user) and in_array($user_id, $treat_user)){
	                                    //已处理过，不重复处理
	                                    //继续
	                                    continue;
	                                }else {
	                                    //未处理，进行处理
	                                    $userMsg=$User->getUserMsg($user_id);
	                                    //减少用户余额
	                                    $res_balance=$User->where("uid='$user_id'")->setDec('balance',$money);
	                                    //保存余额变动记录
	                                    $all_money=$userMsg['balance']-$money;
	                                    $res_record=$UserBalanceRecord->addLog($user_id, $money, $all_money, 'tbk_refund','2',$order_id,'1');
	                                    if($res_balance!==false and $res_record!==false) {
	                                        //添加到处理过的记录中
	                                        $treat_user[]=$user_id;
	                                        //继续
	                                        continue;
	                                    }else {
	                                        //回滚
	                                        $User->rollback();
	                                        //修改用户余额失败
	                                        $this->error('维权处理失败，修改用户'.$userMsg['phone'].'余额失败！');
	                                        exit();
	                                    }
	                                }
	                            }
	                            //修改淘宝订单维权标记
	                            $data_o=array(
	                                'is_refund'=>'Y'//已维权退款
	                            );
	                            $res_o=$TbOrder->where("id='$id'")->save($data_o);
	                            if($res_o!==false) {
	                                //成功
	                                //提交事务
	                                $User->commit();
	                                $this->success('维权处理成功！',U('index'));
	                            }else {
	                                //回滚
	                                $User->rollback();
	                                //修改淘宝订单维权标记失败
	                                $this->error('维权失败：修改淘宝订单维权标记失败');
	                            }
	                        }else {
	                            $this->error('请填写退款金额！');
	                        }
	                    }else {
	                        $this->assign('msg',$msg);
	                        $this->display();
	                    }
	                }else {
	                    //该订单已维权处理过，请勿重复操作
	                    layout(false);
	                    $this->error('该订单已维权处理过，请勿重复操作！');
	                }
	            }else {
	                //该订单不存在归属用户，无需处理
	                layout(false);
	                $this->error('该订单不存在归属用户，无需处理！');
	            }
	        }else {
	            //只有已结算订单可以处理
	            layout(false);
	            $this->error('只有已结算订单可以处理！');
	        }
	    }else {
	        //订单不存在
	        layout(false);
	        $this->error('订单不存在！');
	    }
	}
	
	//处理淘宝订单卡住
	public function task()
	{
	    $config_id=1;
	    //判断当前是否正在处理
	    $TaskConfig=new \Common\Model\TaskConfigModel();
	    //修改处理标记
	    $data_c=array(
	        'status'=>'N'
	    );
	    $res_c=$TaskConfig->where("id=$config_id")->save($data_c);
	    
	    $TbOrderTmp=new \Common\Model\TbOrderTmpModel();
	    $res_tmp=$TbOrderTmp->treat();
	    
	    if($res_c!==false and $res_tmp!==false){
	        /* layout(false);
	         $this->success('执行成功！'); */
	        echo '执行成功';
	    }
	}
}
?>