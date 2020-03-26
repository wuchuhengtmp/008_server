<?php
/**
 * 京东订单管理
 */
namespace Admin\Controller;
use Admin\Common\Controller\AuthController;

class JingdongOrderController extends AuthController
{
	//订单列表
	public function index()
	{
		$where='1';
		//ID
		if(trim(I('get.id'))) {
		    $id=trim(I('get.id'));
		    $where="id=$id";
		}
		//商品名称
		if(trim(I('get.skuName'))) {
			$skuName=trim(I('get.skuName'));
			$where="skuName like '%$skuName%'";
		}
		//订单号
		if(trim(I('get.orderId'))) {
			$orderId=trim(I('get.orderId'));
			$JingdongOrder=new \Common\Model\JingdongOrderModel();
			$res_o=$JingdongOrder->where("orderId='$orderId' or parentId='$orderId'")->find();
			if($res_o['id'])
			{
				$order_id=$res_o['id'];
				$where.=" and order_id='$order_id'";
			}else {
				layout(false);
				$this->error('您查询的订单不存在，请核实');
			}
		}
		//订单状态
		if(trim(I('get.validCode'))) {
			$validCode=trim(I('get.validCode'));
			if($validCode==18){
			    $where.=" and status='2'";
			}else {
			    $where.=" and validCode='$validCode'";
			}
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
		$JingdongOrderDetail=new \Common\Model\JingdongOrderDetailModel();
		$count=$JingdongOrderDetail->where($where)->count();
		$per = 15;
		if($_GET['p']) {
			$p=$_GET['p'];
		}else {
			$p=1;
		}
		$Page=new \Common\Model\PageModel();
		$show= $Page->show($count,$per);// 分页显示输出
		$this->assign('page',$show);
			
		$list = $JingdongOrderDetail->where($where)->page($p.','.$per)->order('id desc')->select();
		$this->assign('list',$list);
			
		$this->display();
	}
	
	//订单详情
	public function msg($id)
	{
		$JingdongOrderDetail=new \Common\Model\JingdongOrderDetailModel();
		$msg=$JingdongOrderDetail->getOrderMsg($id);
		$this->assign('msg',$msg);
	
		$this->display();
	}
	
	// 处理遗漏京东订单
	public function treatOrder()
	{
	    if (I ( 'post.' )) {
	        layout ( false );
	        if ( trim (I('post.time')) ) {
	            
	            // 订单查询开始时间，格式：2016-05-23 12:18:22
	            $Time = new \Common\Model\TimeModel ();
	            $now=trim (I('post.time'));
	            if(is_datetime($now)===false) {
	                $this->error('请填写正确的订单时间！');
	                exit();
	            }
	            // 当前时间往前10分钟
	            $start_time = $Time->getAfterDateTime ( $now, $type = '2', $year = '', $month = '', $day = '', $hour = '', $minute = '-10', $second = '', $week = '' );
	            $start_update_time = strtotime($start_time);
	            $time=date('YmdH',$start_update_time);
	            //$time='2018120922';
	            $page_size = 500;
	            // 循环查询10万条订单最多，1小时内最多10万条
	            $JingdongOrder = new \Common\Model\JingdongOrderModel();
	            $JingdongOrderDetail = new \Common\Model\JingdongOrderDetailModel();
	            $User = new \Common\Model\UserModel ();
	            $num = 100000 / $page_size;
	            // 成功条数
	            $count = 0;
	            Vendor('JingDong.JingDong','','.class.php');
	            $JindDong=new \JindDong();
	            for($i = 0; $i < $num; $i ++) {
	                $page = $i + 1;
	                // 京东订单接口
	                $res_jd=$JindDong->queryOrderList($time,$page,$page_size);
	                if ($res_jd ['data']['list']) {
	                    // 本次查询有结果
	                    // 处理所有的订单
	                    foreach ( $res_jd ['data']['list'] as $l ) {
	                        // 判断订单是否存在，存在不处理
	                        $orderId = $l ['orderId'];
	                        $res_exist = $JingdongOrder->where ( "orderId='$orderId'" )->find ();
	                        if ($res_exist) {
	                            // 存在
	                            // 修改订单的一些重要参数
	                            $data = array (
	                                'finishTime' => $l ['finishTime'],
	                                'orderEmt' => $l ['orderEmt'],
	                                'orderTime' => $l ['orderTime'],
	                                'parentId' => $l ['parentId'],
	                                'payMonth' => $l ['payMonth'],
	                                'plus' => $l ['plus'],
	                                'popId' => $l ['popId'],
	                                'unionId' => $l ['unionId'],
	                                'ext1' => $l ['unionUserName'],
	                                'validCode' => $l ['validCode'],
	                            );
	                            // 保存订单
	                            $res_save = $JingdongOrder->where("orderId='$orderId'")->save( $data );
	                            // 查询订单详情
	                            $order_id=$res_exist['id'];
	                            $detailList=$JingdongOrderDetail->where("order_id='$order_id'")->select();
	                            $num2=count($detailList);
	                            for($j=0;$j<$num2;$j++)
	                            {
	                                if($detailList[$j]['status']=='1')
	                                {
	                                    //未结算订单才进行处理
	                                    // 修改订单的一些重要参数
	                                    $gl=$l['skuList'][$j];
	                                    $user_id = $gl['subUnionId'];
	                                    $data_detail=array(
	                                        'user_id'=>$user_id,
	                                        'actualCosPrice'=>$gl['actualCosPrice'],
	                                        'actualFee'=>$gl['actualFee'],
	                                        'commissionRate'=>$gl['commissionRate'],
	                                        'estimateCosPrice'=>$gl['estimateCosPrice'],
	                                        'estimateFee'=>$gl['estimateFee'],
	                                        'finalRate'=>$gl['finalRate'],
	                                        'cid1'=>$gl['firstLevel'],
	                                        'frozenSkuNum'=>$gl['frozenSkuNum'],
	                                        'pid'=>$gl['pid'],
	                                        'positionId'=>$gl['positionId'],
	                                        'price'=>$gl['price'],
	                                        'payPrice'=>$gl['payPrice'],
	                                        'cid2'=>$gl['secondLevel'],
	                                        'siteId'=>$gl['siteId'],
	                                        'skuId'=>$gl['skuId'],
	                                        'skuName'=>$gl['skuName'],
	                                        'skuNum'=>$gl['skuNum'],
	                                        'skuReturnNum'=>$gl['skuReturnNum'],
	                                        'subSideRate'=>$gl['subSideRate'],
	                                        'subsidyRate'=>$gl['subsidyRate'],
	                                        'cid3'=>$gl['thirdLevel'],
	                                        'unionAlias'=>$gl['unionAlias'],
	                                        'unionTag'=>$gl['unionTag'],
	                                        'unionTrafficGroup'=>$gl['unionTrafficGroup'],
	                                        'validCode'=>$gl['validCode'],
	                                        'subUnionId'=>$gl['subUnionId'],
	                                        'traceType'=>$gl['traceType'],
	                                        'payMonth'=>$gl['payMonth'],
	                                        'popId'=>$gl['popId'],
	                                        'ext1'=>$gl['ext1'],
	                                        'orderTime' => $l ['orderTime'],
	                                    );
	                                    $order_detail_id=$detailList[$j]['id'];
	                                    //开启事务
	                                    $JingdongOrder->startTrans();
	                                    $res_od=$JingdongOrderDetail->where("id='$order_detail_id'")->save($data_detail);
	                                    if($res_od!==false)
	                                    {
	                                        // 给用户返利
	                                        // 给用户返利
	                                        if ($gl ['validCode'] == '17' and !empty($l ['payMonth']) ) {
	                                            //订单有效码[validCode]为已完成且预估结算时间[paymonth]不为空，则该订单为可结算订单），原有订单有效码[validCode]不再更新已结算（validcode=18)状态
	                                            // 只有结算订单才给用户返利
	                                            if ($user_id) {
	                                                // 用户存在，给对应用户返利
	                                                $res_treat = $JingdongOrderDetail->treat ( $order_detail_id, $gl ['actualFee'] );
	                                            }
	                                        }
	                                        //提交事务
	                                        $JingdongOrder->commit();
	                                    }else {
	                                        //回滚
	                                        $JingdongOrder->rollback();
	                                        exit();
	                                    }
	                                }
	                            }
	                            // 成功执行次数
	                            $count ++;
	                        } else {
	                            //不存在
	                            $data = array (
	                                'finishTime' => $l ['finishTime'],
	                                'orderEmt' => $l ['orderEmt'],
	                                'orderId' => $l ['orderId'],
	                                'orderTime' => $l ['orderTime'],
	                                'parentId' => $l ['parentId'],
	                                'payMonth' => $l ['payMonth'],
	                                'plus' => $l ['plus'],
	                                'popId' => $l ['popId'],
	                                'unionId' => $l ['unionId'],
	                                'ext1' => $l ['unionUserName'],
	                                'validCode' => $l ['validCode'],
	                            );
	                            //开启事务
	                            $JingdongOrder->startTrans();
	                            // 保存订单
	                            $res_add = $JingdongOrder->add ( $data );
	                            if($res_add!==false)
	                            {
	                                //保存订单详情
	                                $order_id=$res_add;
	                                foreach ($l['skuList'] as $gl)
	                                {
	                                    $user_id = $gl['subUnionId'];
	                                    $data_detail=array(
	                                        'order_id'=>$order_id,
	                                        'user_id'=>$user_id,
	                                        'actualCosPrice'=>$gl['actualCosPrice'],
	                                        'actualFee'=>$gl['actualFee'],
	                                        'commissionRate'=>$gl['commissionRate'],
	                                        'estimateCosPrice'=>$gl['estimateCosPrice'],
	                                        'estimateFee'=>$gl['estimateFee'],
	                                        'finalRate'=>$gl['finalRate'],
	                                        'cid1'=>$gl['firstLevel'],
	                                        'frozenSkuNum'=>$gl['frozenSkuNum'],
	                                        'pid'=>$gl['pid'],
	                                        'positionId'=>$gl['positionId'],
	                                        'price'=>$gl['price'],
	                                        'payPrice'=>$gl['payPrice'],
	                                        'cid2'=>$gl['secondLevel'],
	                                        'siteId'=>$gl['siteId'],
	                                        'skuId'=>$gl['skuId'],
	                                        'skuName'=>$gl['skuName'],
	                                        'skuNum'=>$gl['skuNum'],
	                                        'skuReturnNum'=>$gl['skuReturnNum'],
	                                        'subSideRate'=>$gl['subSideRate'],
	                                        'subsidyRate'=>$gl['subsidyRate'],
	                                        'cid3'=>$gl['thirdLevel'],
	                                        'unionAlias'=>$gl['unionAlias'],
	                                        'unionTag'=>$gl['unionTag'],
	                                        'unionTrafficGroup'=>$gl['unionTrafficGroup'],
	                                        'validCode'=>$gl['validCode'],
	                                        'subUnionId'=>$gl['subUnionId'],
	                                        'traceType'=>$gl['traceType'],
	                                        'payMonth'=>$gl['payMonth'],
	                                        'popId'=>$gl['popId'],
	                                        'ext1'=>$gl['ext1'],
	                                        'status'=>'1',//未结算
	                                        'orderTime' => $l ['orderTime'],
	                                    );
	                                    $res_od=$JingdongOrderDetail->add($data_detail);
	                                    if($res_od!==false)
	                                    {
	                                        $order_detail_id=$res_od;
	                                        // 给用户返利
	                                        // 给用户返利
	                                        if ($gl ['validCode'] == '17' and !empty($l ['payMonth']) ) {
	                                            //订单有效码[validCode]为已完成且预估结算时间[paymonth]不为空，则该订单为可结算订单），原有订单有效码[validCode]不再更新已结算（validcode=18)状态
	                                            // 只有结算订单才给用户返利
	                                            if ($user_id) {
	                                                // 用户存在，给对应用户返利
	                                                $res_treat = $JingdongOrderDetail->treat ( $order_detail_id, $gl ['actualFee'] );
	                                            }
	                                        }
	                                        //提交事务
	                                        $JingdongOrder->commit();
	                                    }else {
	                                        //回滚
	                                        $JingdongOrder->rollback();
	                                        exit();
	                                    }
	                                }
	                                // 成功次数
	                                $count ++;
	                            }else {
	                                //回滚
	                                $JingdongOrder->rollback();
	                                exit();
	                            }
	                        }
	                    }
	                    if ($res_jd['hasMore'])
	                    {
	                        // 500条，可能还有更多订单，继续查询
	                        continue;
	                    } else {
	                        // 不超出500条，没有更多结果
	                        // 跳出循环
	                        break;
	                    }
	                } else {
	                    // 本次查询无结果
	                    // 跳出循环
	                    break;
	                }
	            }
	            $success_msg='成功执行：' . $count;
	            $this->success($success_msg);
	        } else {
	            $this->error ( '请填写订单时间！' );
	        }
	    } else {
	        $this->display ();
	    }
	}
}
?>