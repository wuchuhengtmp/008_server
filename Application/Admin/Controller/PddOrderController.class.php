<?php

/**
 * 拼多多订单管理
 */
namespace Admin\Controller;
use Admin\Common\Controller\AuthController;

class PddOrderController extends AuthController 
{
	//订单列表
	public function index()
	{
		$where='1';
		//商品名称
		if(trim(I('get.goods_name')))
		{
			$goods_name=trim(I('get.goods_name'));
			$where="goods_name like '%$goods_name%'";
		}
		//订单号
		if(trim(I('get.order_sn')))
		{
			$order_sn=trim(I('get.order_sn'));
			$where.=" and order_sn='$order_sn'";
		}
		//订单状态
		if(trim(I('get.order_status'))!=='')
		{
			$order_status=trim(I('get.order_status'));
			$where.=" and order_status='$order_status'";
		}
		//所属用户
		if(trim(I('get.username')))
		{
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
		$PddOrder=new \Common\Model\PddOrderModel();
		$count=$PddOrder->where($where)->count();
		$per = 15;
		if($_GET['p'])
		{
			$p=$_GET['p'];
		}else {
			$p=1;
		}
		$Page=new \Common\Model\PageModel();
		$show= $Page->show($count,$per);// 分页显示输出
		$this->assign('page',$show);
		 
		$list = $PddOrder->where($where)->page($p.','.$per)->order('id desc')->select();
		$this->assign('list',$list);
		 
		$this->display();
	}
	
	//订单详情
	public function msg($id)
	{
		$PddOrder=new \Common\Model\PddOrderModel();
		$msg=$PddOrder->getOrderMsg($id);
		$this->assign('msg',$msg);
		
		$this->display();
	}
	
	// 处理拼多多遗漏订单
	public function treatOrder()
	{
	    if($_POST){
	        layout(false);
	        if(trim(I('post.start_time'))){
	            // 订单查询开始时间
	            $start_time=trim(I('post.start_time'));
	            $start_update_time = strtotime($start_time);
	            
	            // 订单查询截止时间
	            $Time = new \Common\Model\TimeModel ();
	            // 当前时间往后30分钟
	            $end_time = $Time->getAfterDateTime ( $start_time, '2', '', '','', '', '+30');
	            $end_update_time = strtotime($end_time);
	            
	            $page_size = 100;
	            // 循环查询10万条订单最多，30分钟内最多10万条
	            $PddOrder = new \Common\Model\PddOrderModel();
	            $User = new \Common\Model\UserModel ();
	            // 拼多多订单接口
	            Vendor('pdd.pdd','','.class.php');
	            $pdd=new \pdd();
	            $num = 100000 / $page_size;
	            // 成功条数
	            $count = 0;
	            for($i = 0; $i < $num; $i ++) {
	                $page = $i + 1;
	                $res_pdd=$pdd->getOrderList($start_update_time,$end_update_time,$page_size,$page,'false');
	                if ($res_pdd ['data']['order_list']) {
	                    // 本次查询有结果
	                    // 处理所有的订单
	                    foreach ( $res_pdd ['data']['order_list'] as $l ) {
	                        // 判断订单是否存在，存在不处理
	                        $order_sn = $l ['order_sn'];
	                        $res_exist = $PddOrder->where ( "order_sn='$order_sn'" )->find ();
	                        if ($res_exist) {
	                            // 存在
	                            // 修改订单的一些重要参数
	                            $user_id = $l ['custom_parameters'];
	                            $data_o = array (
	                                'user_id' => $user_id,
	                                'order_sn' => $l ['order_sn'],
	                                'goods_id' => $l ['goods_id'],
	                                'goods_name' => $l ['goods_name'],
	                                'goods_thumbnail_url' => $l ['goods_thumbnail_url'],
	                                'goods_quantity' => $l ['goods_quantity'],
	                                'goods_price' => $l ['goods_price'],
	                                'order_amount' => $l ['order_amount'],
	                                'promotion_rate' => $l ['promotion_rate'],
	                                'promotion_amount' => $l ['promotion_amount'],//平台佣金（分）
	                                'pdd_commission' => $l ['promotion_amount'],//拼多多佣金（分）
	                                'batch_no' => $l ['batch_no'],
	                                'order_status' => $l ['order_status'],
	                                'order_status_desc' => $l ['order_status_desc'],
	                                'order_pay_time' => $l ['order_pay_time'],
	                                'order_group_success_time' => $l ['order_group_success_time'],
	                                'order_receive_time' => $l ['order_receive_time'],
	                                'order_verify_time' => $l ['order_verify_time'],
	                                'order_settle_time' => $l ['order_settle_time'],
	                                'order_modify_at' => $l ['order_modify_at'],
	                                'custom_parameters' => $l ['custom_parameters'],
	                                'pid' => $l ['pid'],
	                            );
	                            $res_order = $PddOrder->where ( "order_sn='$order_sn'" )->save ( $data_o );
	                            // 判断订单状态，如果尚未结算，给用户返利
	                            // 原来未结算，现在结算的订单进行返利
	                            if ($res_exist ['status'] == '1' and $l ['order_status'] == '5') {
	                                // 尚未结算，给用户返利
	                                if($user_id) {
	                                    // 用户存在，给对应用户返利
	                                    $res_treat = $PddOrder->treat ( $order_sn, $l ['promotion_amount'] );
	                                }else {
	                                    // 不存在对应用户，不去处理
	                                }
	                            } else {
	                                // 已结算，不处理
	                            }
	                            // 成功执行次数
	                            $count ++;
	                        } else {
	                            //不存在
	                            $user_id = $l['custom_parameters'];
	                            $data = array (
	                                'user_id' => $user_id,
	                                'order_sn' => $l ['order_sn'],
	                                'goods_id' => $l ['goods_id'],
	                                'goods_name' => $l ['goods_name'],
	                                'goods_thumbnail_url' => $l ['goods_thumbnail_url'],
	                                'goods_quantity' => $l ['goods_quantity'],
	                                'goods_price' => $l ['goods_price'],
	                                'order_amount' => $l ['order_amount'],
	                                'promotion_rate' => $l ['promotion_rate'],
	                                'promotion_amount' => $l ['promotion_amount'],//平台佣金（分）
	                                'pdd_commission' => $l ['promotion_amount'],//拼多多佣金（分）
	                                'batch_no' => $l ['batch_no'],
	                                'order_status' => $l ['order_status'],
	                                'order_status_desc' => $l ['order_status_desc'],
	                                'order_create_time' => $l ['order_create_time'],
	                                'order_pay_time' => $l ['order_pay_time'],
	                                'order_group_success_time' => $l ['order_group_success_time'],
	                                'order_receive_time' => $l ['order_receive_time'],
	                                'order_verify_time' => $l ['order_verify_time'],
	                                'order_settle_time' => $l ['order_settle_time'],
	                                'order_modify_at' => $l ['order_modify_at'],
	                                'match_channel' => $l ['match_channel'],
	                                'type' => $l ['type'],
	                                'group_id' => $l ['group_id'],
	                                'auth_duo_id' => $l ['auth_duo_id'],
	                                'zs_duo_id' => $l ['zs_duo_id'],
	                                'custom_parameters' => $l ['custom_parameters'],
	                                'cps_sign' => $l ['cps_sign'],
	                                'url_last_generate_time' => $l ['url_last_generate_time'],
	                                'point_time' => $l ['point_time'],
	                                'return_status' => $l ['return_status'],
	                                'pid' => $l ['pid'],
	                                'status' => '1'  // 是否结算给用户，1未结算，2已结算
	                            );
	                            // 保存订单
	                            $res_add = $PddOrder->add ( $data );
	                            // 给用户返利
	                            if ($l ['order_status'] == '5') {
	                                // 只有结算订单才给用户返利
	                                if ($user_id) {
	                                    // 用户存在，给对应用户返利
	                                    $res_treat = $PddOrder->treat ( $l['order_sn'], $l ['promotion_amount'] );
	                                }
	                            }
	                            
	                            if ($user_id) {
	                                //极光推送消息
	                                Vendor('jpush.jpush','','.class.php');
	                                $jpush=new \jpush();
	                                $alias=$user_id;//推送别名
	                                $title=APP_NAME.'通知您有新订单';
	                                $content='您有一笔新订单：'.$l ['goods_name'];
	                                $key='order';
	                                $value='pdd';
	                                $res_push=$jpush->push($alias,$title,$content,'','','',$key,$value);
	                                
	                                //给推荐人推送
	                                $userMsg=$User->getUserMsg($user_id);
	                                if($userMsg['group_id']=='1') {
	                                    //普通会员订单，才给上级推送
	                                    if($userMsg['referrer_id']) {
	                                        $referrer_id=$userMsg['referrer_id'];
	                                        $referrerMsg=$User->getUserMsg($referrer_id);
	                                        if($referrerMsg['group_id']!='1') {
	                                            $alias=$referrer_id;//推送别名
	                                            $title=APP_NAME.'通知您有新订单';
	                                            $content='您有一笔新订单：'.$l ['goods_name'];
	                                            $key='order';
	                                            $value='pdd1';
	                                            $res_push=$jpush->push($alias,$title,$content,'','','',$key,$value);
	                                        }
	                                        
	                                        if($referrerMsg['referrer_id']) {
	                                            $referrer_id2=$referrerMsg['referrer_id'];
	                                            $referrerMsg2=$User->getUserMsg($referrer_id2);
	                                            if($referrerMsg2['group_id']!='1') {
	                                                $alias=$referrer_id2;//推送别名
	                                                $title=APP_NAME.'通知您有新订单';
	                                                $content='您有一笔新订单：'.$l ['goods_name'];
	                                                $key='order';
	                                                $value='pdd2';
	                                                $res_push=$jpush->push($alias,$title,$content,'','','',$key,$value);
	                                            }
	                                        }
	                                    }
	                                }
	                                
	                                //对订单做预估收入处理
	                                $res_treat_tmp = $PddOrder->treat_tmp ( $l['order_sn'], $l ['promotion_amount'] );
	                            }
	                            // 成功次数
	                            $count ++;
	                        }
	                    }
	                    $list_num = count ( $res_pdd ['data']['order_list'] );
	                    if ($list_num == 100) {
	                        // 100条，可能还有更多订单，继续查询
	                        continue;
	                    } else {
	                        // 不超出100条，没有更多结果
	                        // 跳出循环
	                        break;
	                    }
	                } else {
	                    // 本次查询无结果
	                    // 跳出循环
	                    break;
	                }
	            }
	            $this->success('成功处理'.$count.'条');
	        }else {
	            $this->error('请填写订单时间！');
	        }
	    }else {
	        $this->display();
	    }
	}
}
?>