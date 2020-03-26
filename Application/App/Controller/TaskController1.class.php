<?php

/**
 * 任务管理接口
 */
namespace App\Controller;

class TaskController 
{
    
    // 处理淘宝临时订单
    // 每1分钟执行一次
    // 网址：http://taobao.mjuapp.com/app.php/Task/treatTbOrder
    // 自动执行命令：*/1 * * * * /usr/bin/curl http://taobao.mjuapp.com/app.php/Task/treatTbOrder
    public function treatTbOrder()
    {
        //记录处理淘宝临时订单时间
        $now=date('Y-m-d H:i:s');
        writeLog('记录处理淘宝临时订单时间：'.$now);
        //保存到临时表中完成，
        $TbOrderTmp=new \Common\Model\TbOrderTmpModel();
        $TbOrderTmp->treat();
    }
    
    
	/**
	 * 拉取淘宝常规订单-已付款
	 * 每5分钟执行一次
	 * 网址：http://taobao.mjuapp.com/app.php/Task/treatOrder
	 * 自动执行命令：0,5,10,15,20,25,30,35,40,45,50,55,58 * * * * /usr/bin/curl http://taobao.mjuapp.com/app.php/Task/treatOrder
	 */
    public function treatOrder() 
	{
	    //查询时间类型，1：按照订单淘客创建时间查询，2:按照订单淘客付款时间查询，3:按照订单淘客结算时间查询
	    $query_type=2;
	    //淘客订单状态，12-付款，13-关闭，14-确认收货，3-结算成功;不传，表示所有状态
	    $tk_status='';
	    //场景订单场景类型，1:常规订单，2:渠道订单，3:会员运营订单
	    $order_scene=1;
	    
	    // 订单查询开始时间，格式：2016-05-23 12:18:22
	    $Time = new \Common\Model\TimeModel ();
	    $now = date ( 'Y-m-d H:i:s' );
	    //$now='2019-09-05 10:40:00';
	    $end_time=$now;
	    // 当前时间往前20分钟
	    $start_time = $Time->getAfterDateTime ( $now, '2', '', '', '', '', '-20');
	    //记录拉取淘宝订单时间
	    writeLog('拉取淘宝订单时间'.$start_time);

	    //推广者角色类型,2:二方，3:三方，不传，表示所有角色
	    $member_type='';
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
		for($i = 0; $i < $num; $i ++)  {
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
			    // 跳出循环
			    break;
			}
		}
		echo '成功执行：' . $count;
	}
	
	/**
	 * 拉取淘宝常规订单-已结算
	 * 每6分钟执行一次
	 * 网址：http://taobao.mjuapp.com/app.php/Task/treatOrder2
	 * 自动执行命令：1,7,13,19,25,31,37,43,49,55 * * * * /usr/bin/curl http://taobao.mjuapp.com/app.php/Task/treatOrder2
	 */
	public function treatOrder2()
	{
	    //查询时间类型，1：按照订单淘客创建时间查询，2:按照订单淘客付款时间查询，3:按照订单淘客结算时间查询
	    $query_type=3;
	    //淘客订单状态，12-付款，13-关闭，14-确认收货，3-结算成功;不传，表示所有状态
	    $tk_status='';
	    //场景订单场景类型，1:常规订单，2:渠道订单，3:会员运营订单
	    $order_scene=1;
	    
	    // 订单查询开始时间，格式：2016-05-23 12:18:22
	    $Time = new \Common\Model\TimeModel ();
	    $now = date ( 'Y-m-d H:i:s' );
	    //$now='2019-06-23 23:10:00';
	    $end_time=$now;
	    // 当前时间往前20分钟
	    $start_time = $Time->getAfterDateTime ( $now, '2', '', '', '', '', '-20');
	    //记录拉取淘宝订单时间
	    writeLog('拉取淘宝结算订单时间'.$start_time);
	    
	    //推广者角色类型,2:二方，3:三方，不传，表示所有角色
	    $member_type='';
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
		for($i = 0; $i < $num; $i ++)  {
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
			    // 跳出循环
			    break;
			}
		}
		echo '成功执行：' . $count;
	}
	
	/**
	 * 拉取淘宝渠道订单-已付款
	 * 每5分钟执行一次
	 * 网址：http://taobao.mjuapp.com/app.php/Task/treatOrderR
	 * 自动执行命令：0,5,10,15,20,25,30,35,40,45,50,55,58 * * * * /usr/bin/curl http://taobao.mjuapp.com/app.php/Task/treatOrderR
	 */
	public function treatOrderR()
	{
	    //查询时间类型，1：按照订单淘客创建时间查询，2:按照订单淘客付款时间查询，3:按照订单淘客结算时间查询
	    $query_type=2;
	    //淘客订单状态，12-付款，13-关闭，14-确认收货，3-结算成功;不传，表示所有状态
	    $tk_status='';
	    //场景订单场景类型，1:常规订单，2:渠道订单，3:会员运营订单
	    $order_scene=2;
	    
	    // 订单查询开始时间，格式：2016-05-23 12:18:22
	    $Time = new \Common\Model\TimeModel ();
	    $now = date ( 'Y-m-d H:i:s' );
	    //$now='2019-06-23 23:10:00';
	    $end_time=$now;
	    // 当前时间往前20分钟
	    $start_time = $Time->getAfterDateTime ( $now, '2', '', '', '', '', '-20');
	    //记录拉取淘宝订单时间
	    writeLog('拉取淘宝渠道订单时间'.$start_time);
	    
	    //推广者角色类型,2:二方，3:三方，不传，表示所有角色
	    $member_type='';
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
	            // 跳出循环
	            break;
	        }
	    }
	    echo '成功执行：' . $count;
	}
	
	/**
	 * 拉取淘宝渠道订单-已结算
	 * 每10分钟执行一次
	 * 网址：http://taobao.mjuapp.com/app.php/Task/treatOrderR2
	 * 自动执行命令：1,7,13,19,25,31,37,43,49,55 * * * * /usr/bin/curl http://taobao.mjuapp.com/app.php/Task/treatOrderR2
	 */
	public function treatOrderR2()
	{
	    //查询时间类型，1：按照订单淘客创建时间查询，2:按照订单淘客付款时间查询，3:按照订单淘客结算时间查询
	    $query_type=3;
	    //淘客订单状态，12-付款，13-关闭，14-确认收货，3-结算成功;不传，表示所有状态
	    $tk_status='';
	    //场景订单场景类型，1:常规订单，2:渠道订单，3:会员运营订单
	    $order_scene=2;
	    
	    // 订单查询开始时间，格式：2016-05-23 12:18:22
	    $Time = new \Common\Model\TimeModel ();
	    $now = date ( 'Y-m-d H:i:s' );
	    //$now='2019-06-23 23:10:00';
	    $end_time=$now;
	    // 当前时间往前20分钟
	    $start_time = $Time->getAfterDateTime ( $now, '2', '', '', '', '', '-20');
	    //记录拉取淘宝订单时间
	    writeLog('拉取淘宝渠道结算订单时间'.$start_time);
	    
	    //推广者角色类型,2:二方，3:三方，不传，表示所有角色
	    $member_type='';
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
	    for($i = 0; $i < $num; $i ++)
	    {
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
	            // 跳出循环
	            break;
	        }
	    }
	    echo '成功执行：' . $count;
	}
	
	// 拉取前一天所有淘宝订单
	// 网址：http://taobao.mjuapp.com/app.php/Task/treatOrderYesterday
	// 每天00:10分执行一次
	// 自动执行命令：10 0 * * * /usr/bin/curl http://taobao.mjuapp.com/app.php/Task/treatOrderYesterday
	public function treatOrderYesterday()
	{
		writeLog('拉取前一天所有淘宝订单');
		
		//查询时间类型，1：按照订单淘客创建时间查询，2:按照订单淘客付款时间查询，3:按照订单淘客结算时间查询
		$query_type=2;
		//淘客订单状态，12-付款，13-关闭，14-确认收货，3-结算成功;不传，表示所有状态
		$tk_status='';
		//场景订单场景类型，1:常规订单，2:渠道订单，3:会员运营订单
		$order_scene=1;
		
		//推广者角色类型,2:二方，3:三方，不传，表示所有角色
		$member_type='';
		$page_size=100;
		
		//跳转类型，当向前或者向后翻页必须提供,-1: 向前翻页,1：向后翻页
		$jump_type=1;
		//拉取前一天所有淘宝订单
		$Time = new \Common\Model\TimeModel ();
		$now=date("Y-m-d",strtotime("-2 day")).' 23:40:00';
		$start_time = $Time->getAfterDateTime ( $now, '2', '', '','', '', '+20');
		
		$TbOrder = new \Common\Model\TbOrderModel ();
		$page_size = 100;
		//循环72次=24小时*3
		for($ti=0;$ti<72;$ti++) {
			//每20分钟加一次
		    $end_time = $Time->getAfterDateTime ( $start_time, '2', '', '','', '', '+20');
		    
			// 循环查询10万条订单最多，10分钟内最多10万条
			$num = 100000 / $page_size;
			// 成功条数
			$count = 0;
			for($i = 0; $i < $num; $i ++) {
				$page_no = $i + 1;
				// 淘宝订单接口
				//位点，除第一页之外，都需要传递；前端原样返回。
				$position_index='';
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
				    // 跳出循环
				    break;
				}
			}
			
			$start_time = $Time->getAfterDateTime ( $start_time, '2', '', '','', '', '+20');
			
		}
	}
	
	/**
	 * 拉取淘宝常规失效订单
	 * 每3小时执行一次
	 * 网址：http://taobao.mjuapp.com/app.php/Task/treatOrderClose
	 * 自动执行命令：0 0,2,4,6,8,10,12,14,16,18,20,22 * * * /usr/bin/curl http://taobao.mjuapp.com/app.php/Task/treatOrderClose
	 */
	public function treatOrderClose()
	{
	    //查询时间类型，1：按照订单淘客创建时间查询，2:按照订单淘客付款时间查询，3:按照订单淘客结算时间查询
	    $query_type=2;
	    //淘客订单状态，12-付款，13-关闭，14-确认收货，3-结算成功;不传，表示所有状态
	    $tk_status='';
	    //场景订单场景类型，1:常规订单，2:渠道订单，3:会员运营订单
	    $order_scene=1;
	    
	    // 订单查询开始时间，格式：2016-05-23 12:18:22
	    $Time = new \Common\Model\TimeModel ();
	    $now = date ( 'Y-m-d H:i:s' );
	    // 当前时间往前1天
	    $start_time = $Time->getAfterDateTime ( $now, '2', '', '', '-1');
	    //3小时后
	    $end_time = $Time->getAfterDateTime($now,'2','','','','+3');
	    //记录拉取淘宝订单时间
	    writeLog('拉取淘宝常规失效订单时间'.$start_time);
	    
	    //推广者角色类型,2:二方，3:三方，不传，表示所有角色
	    $member_type='';
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
	    for($i = 0; $i < $num; $i ++)  {
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
	            // 跳出循环
	            break;
	        }
	    }
	    echo '成功执行：' . $count;
	}
	
	/**
	 * 拉取淘宝渠道失效订单
	 * 每3小时执行一次
	 * 网址：http://taobao.mjuapp.com/app.php/Task/treatOrderClose2
	 * 自动执行命令：0 0,2,4,6,8,10,12,14,16,18,20,22 * * * /usr/bin/curl http://taobao.mjuapp.com/app.php/Task/treatOrderClose2
	 */
	public function treatOrderClose2()
	{
	    //查询时间类型，1：按照订单淘客创建时间查询，2:按照订单淘客付款时间查询，3:按照订单淘客结算时间查询
	    $query_type=2;
	    //淘客订单状态，12-付款，13-关闭，14-确认收货，3-结算成功;不传，表示所有状态
	    $tk_status='';
	    //场景订单场景类型，1:常规订单，2:渠道订单，3:会员运营订单
	    $order_scene=2;
	    
	    // 订单查询开始时间，格式：2016-05-23 12:18:22
	    $Time = new \Common\Model\TimeModel ();
	    $now = date ( 'Y-m-d H:i:s' );
	    // 当前时间往前1天
	    $start_time = $Time->getAfterDateTime ( $now, '2', '', '', '-1');
	    //3小时后
	    $end_time = $Time->getAfterDateTime($now,'2','','','','+3');
	    //记录拉取淘宝订单时间
	    writeLog('拉取淘宝渠道失效订单时间'.$start_time);
	    
	    //推广者角色类型,2:二方，3:三方，不传，表示所有角色
	    $member_type='';
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
	    for($i = 0; $i < $num; $i ++)  {
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
	            // 跳出循环
	            break;
	        }
	    }
	    echo '成功执行：' . $count;
	}
	
	// 自动执行订单-10分钟一次
	// 网址：http://taobao.mjuapp.com/app.php/Task/treatPddOrder
	// 每10分钟执行一次
	// 自动执行命令：0,9,18,27,36,45,54 * * * * /usr/bin/curl http://taobao.mjuapp.com/app.php/Task/treatPddOrder
	public function treatPddOrder()
	{
		// 订单查询截止时间
		$end_update_time=time();
		// 订单查询开始时间
		$Time = new \Common\Model\TimeModel ();
		$now = date ( 'Y-m-d H:i:s' );
		// 当前时间往前30分钟
		$start_time = $Time->getAfterDateTime ( $now, '2', '', '','', '', '-30');
		$start_update_time = strtotime($start_time);
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
		echo '成功执行：' . $count;
	}
	
	// 自动执行订单-10分钟一次
	// 网址：http://taobao.mjuapp.com/app.php/Task/treatJdOrder
	// 每10分钟执行一次
	// 自动执行命令：0,9,18,27,36,45,54,59 * * * * /usr/bin/curl http://taobao.mjuapp.com/app.php/Task/treatJdOrder
	public function treatJdOrder()
	{
		// 订单查询开始时间
		$Time = new \Common\Model\TimeModel ();
		$now = date ( 'Y-m-d H:i:s' );
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
		
		
		Vendor('JingDong.JdUnion','','.class.php');
		$JdUnion=new \JdUnion();
		
	
		
		//Vendor('JingDong.JingDong','','.class.php');
		//$JindDong=new \JindDong();
		for($i = 0; $i < $num; $i ++)
		{
			$page = $i + 1;
			// 京东订单接口
			//$res_jd=$JindDong->queryOrderList($time,$page,$page_size);

			$res_jd=$JdUnion->queryOpenOrders($time,$page,$page_size);
			if ($res_jd ['data'])
			{
				// 本次查询有结果
				// 处理所有的订单
				foreach ( $res_jd ['data'] as $l )
				{
					// 判断订单是否存在，存在不处理
					$orderId = $l ['orderId'];
					$res_exist = $JingdongOrder->where ( "orderId='$orderId'" )->find ();
					if ($res_exist)
					{
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
								$jd_pid = $gl['positionId'];
								$user_id = $User->where("jd_pid='".$jd_pid."'")->getField('uid');
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
								$jd_pid = $gl['positionId'];
								$user_id = $User->where("jd_pid='".$jd_pid."'")->getField('uid');
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
									
									if ($user_id)
									{
										//极光推送消息
										Vendor('jpush.jpush','','.class.php');
										$jpush=new \jpush();
										$alias=$user_id;//推送别名
										$title=APP_NAME.'通知您有新订单';
										$content='您有一笔新订单：'.$gl['skuName'];
										$key='order';
										$value='jingdong';
										$res_push=$jpush->push($alias,$title,$content,'','','',$key,$value);
										
										//给推荐人推送
										$userMsg=$User->getUserMsg($user_id);
										if($userMsg['group_id']=='1')
										{
											//普通会员订单，才给上级推送
											if($userMsg['referrer_id'])
											{
												$referrer_id=$userMsg['referrer_id'];
												$referrerMsg=$User->getUserMsg($referrer_id);
												if($referrerMsg['group_id']!='1')
												{
													$alias=$referrer_id;//推送别名
													$title=APP_NAME.'通知您有新订单';
													$content='您有一笔新订单：'.$gl['skuName'];
													$key='order';
													$value='jingdong1';
													$res_push=$jpush->push($alias,$title,$content,'','','',$key,$value);
												}
									
												if($referrerMsg['referrer_id'])
												{
													$referrer_id2=$referrerMsg['referrer_id'];
													$referrerMsg2=$User->getUserMsg($referrer_id2);
													if($referrerMsg2['group_id']!='1')
													{
														$alias=$referrer_id2;//推送别名
														$title=APP_NAME.'通知您有新订单';
														$content='您有一笔新订单：'.$gl['skuName'];
														$key='order';
														$value='jingdong2';
														$res_push=$jpush->push($alias,$title,$content,'','','',$key,$value);
													}
												}
											}
										}
										
										//对订单做预估收入处理-付款时候得不到最终佣金，只能用预估佣金去计算
										$res_treat_tmp = $JingdongOrderDetail->treat_tmp ( $order_detail_id, $gl ['estimateFee'] );
										
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
		echo '成功执行：' . $count;
	}

    // 自动执行订单-10分钟一次
    // 网址：http://taobao.mjuapp.com/app.php/Task/treatVipOrder
    // 每10分钟执行一次
    // 自动执行命令：0,9,18,27,36,45,54,59 * * * * /usr/bin/curl http://taobao.mjuapp.com/app.php/Task/treatVipOrder
    public function treatVipOrder(){
        // 订单查询开始时间
        $end_update_time=time();
        // 订单查询截止时间
        $Time = new \Common\Model\TimeModel ();
        // 当前时间往前30分钟
        $start_time = $Time->getAfterDateTime ( $end_update_time, '2', '', '','', '', '-30');
        $start_update_time = strtotime($start_time);

        $page_size = 100;
        // 循环查询10万条订单最多，30分钟内最多10万条
        $VipOrder = new \Common\Model\VipOrderModel();
        $User = new \Common\Model\UserModel ();
        // 拼多多订单接口
        Vendor('vip.vip','','.class.php');
        $vip=new \vip();
        $num = 100000 / $page_size;
        // 成功条数
        $count = 0;
        for($i = 0; $i < $num; $i ++) {
            $page = $i + 1;
            $res_vip=$vip->getOrderList($page_size,$page,$start_update_time,$end_update_time);
            if ($res_vip ['data']['order_list']) {
                // 本次查询有结果
                // 处理所有的订单
                foreach ( $res_vip ['data']['order_list'] as $l ) {
                    // 判断订单是否存在，存在不处理
                    $order_sn = $l ['orderSn'];
                    $res_exist = $VipOrder->where ( "orderSn='$order_sn'" )->find ();
                    if ($res_exist) {
                        // 存在
                        // 修改订单的一些重要参数
                        $pid = explode('_', $l['pid']);
                        $user_id = $pid[1];
                        $data_o = array (
                            'user_id' => $user_id,
                            'orderSn' => $l ['orderSn'],
                            'goodsId' => $l ['detailList'][0]['goodsId'],
                            'goodsName' => $l ['detailList'][0]['goodsName'],
                            'goodsThumb' => $l ['detailList'][0]['goodsThumb'],
                            'goodsCount' => $l ['detailList'][0]['goodsCount'],
                            'commissionTotalCost' => $l ['detailList'][0]['commissionTotalCost'],
                            'vipCommission' => $l ['commission'],
                            'commission' => $l ['detailList'][0]['commission'],
                            'commissionRate' => $l ['detailList'][0]['commissionRate'],
                            'commCode' => $l ['detailList'][0]['commCode'],
                            'commName' => $l ['detailList'][0]['commName'],
                            'vipStatus' => $l ['status'],
                            'settled' => $l ['settled'],
                            'orderSubStatusName' => $l ['orderSubStatusName'],
                            'newCustomer' => $l ['newCustomer'],
                            'selfBuy' => $l ['selfBuy'],
                            'channelTag' => $l ['channelTag'],
                            'orderSource' => $l ['orderSource'],
                            'isPrepay' => $l ['isPrepay'],
                            'pid' => $l ['pid'],
                            'orderTime' => $l ['orderTime'],
                            'signTime' => $l ['signTime'],
                            'settledTime' => $l ['settledTime'],
                            'lastUpdateTime' => $l ['lastUpdateTime'],
                            'commissionEnterTime' => $l ['commissionEnterTime'],
                            'afterSaleChangeCommission' => $l ['afterSaleChangeCommission'],
                            'afterSaleChangeGoodsCount' => 111,
                        );
                        $res_order = $VipOrder->where ( "orderSn='$order_sn'" )->save ( $data_o );
                        // 判断订单状态，如果尚未结算，给用户返利
                        // 原来未结算，现在结算的订单进行返利
                        if ($res_exist ['status'] == '1' and $l ['settled'] == 1) {
                            // 尚未结算，给用户返利
                            if($user_id) {
                                // 用户存在，给对应用户返利
                                $res_treat = $VipOrder->treat ( $order_sn, $l['commission'] );
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
                        $pid = explode('_', $l['pid']);
                        $user_id = $pid[1];
                        $data = array (
                            'user_id' => $user_id,
                            'orderSn' => $l ['orderSn'],
                            'goodsId' => $l ['detailList'][0]['goodsId'],
                            'goodsName' => $l ['detailList'][0]['goodsName'],
                            'goodsThumb' => $l ['detailList'][0]['goodsThumb'],
                            'goodsCount' => $l ['detailList'][0]['goodsCount'],
                            'commissionTotalCost' => $l ['detailList'][0]['commissionTotalCost'],
                            'vipCommission' => $l ['commission'],
                            'commission' => $l ['detailList'][0]['commission'],
                            'commissionRate' => $l ['detailList'][0]['commissionRate'],
                            'commCode' => $l ['detailList'][0]['commCode'],
                            'commName' => $l ['detailList'][0]['commName'],
                            'vipStatus' => $l ['status'],
                            'settled' => $l ['settled'],
                            'orderSubStatusName' => $l ['orderSubStatusName'],
                            'newCustomer' => $l ['newCustomer'],
                            'selfBuy' => $l ['selfBuy'],
                            'channelTag' => $l ['channelTag'],
                            'orderSource' => $l ['orderSource'],
                            'isPrepay' => $l ['isPrepay'],
                            'pid' => $l ['pid'],
                            'orderTime' => $l ['orderTime'],
                            'signTime' => $l ['signTime'],
                            'settledTime' => $l ['settledTime'],
                            'lastUpdateTime' => $l ['lastUpdateTime'],
                            'commissionEnterTime' => $l ['commissionEnterTime'],
                            'afterSaleChangeCommission' => $l ['afterSaleChangeCommission'],
                            'afterSaleChangeGoodsCount' => $l ['afterSaleChangeGoodsCount'],
                            'status' => '1'  // 是否结算给用户，1未结算，2已结算
                        );
                        // 保存订单
                        $res_add = $VipOrder->add ( $data );
                        // 给用户返利
                        if ($l ['settled'] == 1) {
                            // 只有结算订单才给用户返利
                            if ($user_id) {
                                // 用户存在，给对应用户返利
                                $res_treat = $VipOrder->treat ( $order_sn, $l ['commission'] );
                            }
                        }

                        if ($user_id) {
                            //极光推送消息
                            Vendor('jpush.jpush','','.class.php');
                            $jpush=new \jpush();
                            $alias=$user_id;//推送别名
                            $title=APP_NAME.'通知您有新订单';
                            $content='您有一笔新订单：'.$data ['goodsName'];
                            $key='order';
                            $value='vip';
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
                                        $content='您有一笔新订单：'.$data ['goodsName'];
                                        $key='order';
                                        $value='vip1';
                                        $res_push=$jpush->push($alias,$title,$content,'','','',$key,$value);
                                    }

                                    if($referrerMsg['referrer_id']) {
                                        $referrer_id2=$referrerMsg['referrer_id'];
                                        $referrerMsg2=$User->getUserMsg($referrer_id2);
                                        if($referrerMsg2['group_id']!='1') {
                                            $alias=$referrer_id2;//推送别名
                                            $title=APP_NAME.'通知您有新订单';
                                            $content='您有一笔新订单：'.$data ['goodsName'];
                                            $key='order';
                                            $value='vip2';
                                            $res_push=$jpush->push($alias,$title,$content,'','','',$key,$value);
                                        }
                                    }
                                }
                            }

                            //对订单做预估收入处理
                            $res_treat_tmp = $VipOrder->treat_tmp ( $order_sn, $l ['commission'] );
                        }
                        // 成功次数
                        $count ++;
                    }
                }
                $list_num = count ( $res_vip ['data']['order_list'] );
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
        echo '成功执行：' . $count;
    }
}
?>