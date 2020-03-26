<?php
/**
 * 淘宝订单管理类
 */
namespace Common\Model;
use Think\Model;

class TbOrderModel extends Model
{
	//验证规则
	protected $_validate =array(
			array('user_id','require','用户不能为空！',self::EXISTS_VALIDATE),  //存在验证，必填
			array('user_id','is_positive_int','用户不存在',self::EXISTS_VALIDATE,'function'),  //存在验证，必须是正整数
	);
	
	/**
	 * 获取订单详情
	 * @param int $id:订单ID
	 * @return array|boolean
	 */
	public function getOrderMsg($id)
	{
		$msg=$this->where("id='$id'")->find();
		if($msg)
		{
			if($msg['user_id'])
			{
				$User=new \Common\Model\UserModel();
				$userMsg=$User->getUserMsg($msg['user_id']);
				$msg['user_account']=$userMsg['phone'];
			}
			return $msg;
		}else {
			return false;
		}
	}
	
	/**
	 * 获取订单详情
	 * @param int $trade_id:淘宝订单号
	 * @return array|boolean
	 */
	public function getOrderMsgByTradeId($trade_id)
	{
		$msg=$this->where("trade_id='$trade_id'")->find();
		if($msg)
		{
			return $msg;
		}else {
			return false;
		}
	}
	
	/**
	 * 淘宝客-推广者-所有订单查询
	 * taobao.tbk.order.details.get
	 * 新订单查询API变更情况：
	 * 商品价格：price->item_price
	 * 商品id：num_iid->item_id
	 * 结算预估收入：commission->pub_share_fee
	 * 推广者获得的佣金比率：commission_rate->pub_share_rate
	 * 订单创建的时间：create_time->tk_create_time
	 * 订单确认收货后且商家完成佣金支付的时间：earning_time->tk_earning_time
	 * 原第三方服务来源——>名称调整为：产品类型：tk3rd_type->flow_source
	 * 原第三方id——>名称调整为：第三方的账户id：tk3rd_pub_id->pub_id
	 * 商品所属的根类目，即一级类目的名称：auction_category->item_category_name
	 * 新增字段如下：
	 * 订单在淘宝拍下付款的时间：tb_paid_time
	 * 订单付款的时间，该时间同步淘宝，可能会略晚于买家在淘宝的订单创建时间：tk_paid_time
	 * 身份说明。二方：佣金收益的第一归属者； 三方：从其他淘宝客佣金中进行分成的推广者：tk_order_role
	 * 维权标签，0 含义为非维权 1 含义为维权订单。即如果订单发生维权退款，会给予提示标识。所有的维权推广订单也能在维权推广订单API查询。本API只做提示：refund_tag
	 * 提成比例，提成比例=推广者获得的佣金比例*收入比例：tk_total_rate
	 * 推广者赚取佣金后支付给阿里妈妈的技术服务费用的比率：alimama_rate
	 * 技术服务费=结算金额*收入比率*技术服务费率。推广者赚取佣金后支付给阿里妈妈的技术服务费用：alimama_share_fee
	 * 商品图片：item_img
	 * @param number $query_type:查询时间类型，1：按照订单淘客创建时间查询，2:按照订单淘客付款时间查询，3:按照订单淘客结算时间查询
	 * @param number $tk_status:淘客订单状态，12-付款，13-关闭，14-确认收货，3-结算成功;不传，表示所有状态
	 * @param number $order_scene:场景订单场景类型，1:常规订单，2:渠道订单，3:会员运营订单，默认为1
	 * @param number $member_type:推广者角色类型,2:二方，3:三方，不传，表示所有角色
	 * @param number $page_no:第几页，默认1，1~100
	 * @param number $page_size:页大小，默认20，1~100
	 * @param string $position_index:位点，除第一页之外，都需要传递；前端原样返回。
	 * @param number $jump_type:跳转类型，当向前或者向后翻页必须提供,-1: 向前翻页,1：向后翻页
	 */
	public function pullOrder($query_type,$tk_status,$order_scene,$start_time,$end_time,$member_type,$page_no,$page_size,$position_index,$jump_type)
	{
	    // 成功条数
	    $count = 0;
	    
	    $User = new \Common\Model\UserModel();
	    //注意：只要没有走确认收货的订单状态，就不会有 total_commission_fee 和commission
	    Vendor('tbk.tbk','','.class.php');
	    $tbk=new \tbk();
	    $results=$tbk->getOrderListNew($query_type,$tk_status,$order_scene,$start_time,$end_time,$member_type,$page_no,$page_size,$position_index,$jump_type);
	   
	    if ($results ['data']['results']['publisher_order_dto']) {
	        // 本次查询有结果
	        $position_index=$results['data']['position_index'];
	        if($results ['data']['results']['publisher_order_dto'][0]['trade_id']) {
	            //多件商品
	            $list_tmp=$results ['data']['results']['publisher_order_dto'];
	            $num1=count($list_tmp);
	            $list=array();
	            for ($i=0;$i<$num1;$i++) {
	                $list[]=$list_tmp[$i];
	            }
	        }else {
	            //一件商品
	            $list[]=$results ['data']['results']['publisher_order_dto'];
	        }
	        $TbOrderTmp=new \Common\Model\TbOrderTmpModel();
	        foreach ( $list as $l ) {
	            // 判断订单是否存在，存在不处理
	            // 淘宝订单号
	            $trade_id = $l ['trade_id'];
	            // 淘宝父订单号
	            $trade_parent_id = $l ['trade_parent_id'];
	            // 淘宝推广位pid
	            $tb_pid = 'mm_'.TBK_APPID.'_' . $l ['site_id'] . '_' . $l ['adzone_id'];
	            // 淘宝用户uid
	            $tb_uid = substr ( $trade_id, - 6 );
	            // 渠道ID
	            $tb_rid = $l['relation_id'];
	            
	            $res_exist = $TbOrderTmp->where ( "trade_id='$trade_id'" )->find ();
	            if ($res_exist) {
	                // 存在
	                // 判断是常规订单还是渠道订单
	                if($order_scene=='1'){
	                    // 常规订单
	                    // 查询是否有用户绑定了该推广位
	                    // 先判断微信群主推广位pid
	                    $res_user = $User->where ( "tb_pid_master='$tb_pid'" )->field('uid')->find ();
	                    if ($res_user ['uid']) {
	                        // 用户存在，给对应用户返利
	                        $user_id = $res_user ['uid'];
	                    } else {
	                        // 再判断淘宝用户个人pid
	                        $res_user2 = $User->where ( "tb_pid='$tb_pid' and tb_uid='$tb_uid'" )->field('uid')->find ();
	                        if ($res_user2 ['uid']) {
	                            // 用户存在，给对应用户返利
	                            $user_id = $res_user2 ['uid'];
	                        } else {
	                            $user_id = $res_exist['user_id'];
	                        }
	                    }
	                }else if($order_scene=='2' and !empty($tb_rid)){
	                    // 渠道订单
	                    $res_user = $User->where ( "tb_rid='$tb_rid'" )->field('uid')->find ();
	                    if ($res_user ['uid']) {
	                        // 用户存在，给对应用户返利
	                        $user_id = $res_user ['uid'];
	                    }else {
	                        $user_id = $res_exist['user_id'];
	                    }
	                }
	                // 修改订单的一些重要参数
	                $data_o = array (
	                    'user_id' => $user_id,
	                    'price' => $l ['item_price'],
	                    'pay_price' => $l ['pay_price'],
	                    'tb_commission' => $l ['pub_share_fee'],
	                    'commission_rate' => $l ['pub_share_rate']/100,
	                    'alipay_total_price' => $l ['alipay_total_price'],
	                    'total_commission_rate' => $l ['total_commission_rate']/100,
	                    'total_commission_fee' => $l ['total_commission_fee'],
	                    'earning_time' => $l ['tk_earning_time'],
	                    'tk_status' => $l ['tk_status']
	                );
	                if(!empty($tb_rid)){
	                    $data_o['relation_id']=$tb_rid;
	                }
	                //保存订单到临时表中
	                $res_order_tmp=$TbOrderTmp->where ( "trade_id='$trade_id'" )->save ( $data_o );
	                
	                // 成功执行次数
	                $count ++;
	            } else {
	                //不存在
	                // 判断是常规订单还是渠道订单
	                if($order_scene=='1'){
	                    // 常规订单
	                    // 查询是否有用户绑定了该推广位
	                    // 先判断微信群主推广位pid
	                    $res_user = $User->where ( "tb_pid_master='$tb_pid'" )->field('uid')->find ();
	                    if ($res_user ['uid']) {
	                        // 用户存在，给对应用户返利
	                        $user_id = $res_user ['uid'];
	                    } else {
	                        // 再判断淘宝用户个人pid
	                        $res_user2 = $User->where ( "tb_pid='$tb_pid' and tb_uid='$tb_uid'" )->field('uid')->find ();
	                        if ($res_user2 ['uid']) {
	                            // 用户存在，给对应用户返利
	                            $user_id = $res_user2 ['uid'];
	                        } else {
	                            $user_id = null;
	                        }
	                    }
	                }else if($order_scene=='2' and !empty($tb_rid)){
	                    // 渠道订单
	                    $res_user = $User->where ( "tb_rid='$tb_rid'" )->field('uid')->find ();
	                    //并且判断VIP会员没有到期
	                    if ($res_user ['uid']) {
	                        // 用户存在，给对应用户返利
	                        $user_id = $res_user ['uid'];
	                    }
	                }
	                $data = array (
	                    'user_id' => $user_id,
	                    'trade_parent_id' => $trade_parent_id,
	                    'trade_id' => $trade_id,
	                    'num_iid' => $l ['item_id'],
	                    'item_title' => $l ['item_title'],
	                    'item_num' => $l ['item_num'],
	                    'price' => $l ['item_price'],
	                    'pay_price' => $l ['pay_price'],
	                    'seller_nick' => $l ['seller_nick'],
	                    'seller_shop_title' => $l ['seller_shop_title'],
	                    'tb_commission' => $l ['pub_share_fee'],
	                    'commission_rate' => $l ['pub_share_rate']/100,
	                    'create_time' => $l ['tk_create_time'],
	                    'earning_time' => $l ['tk_earning_time'],
	                    'tk_status' => $l ['tk_status'],
	                    'tk3rd_type' => $l ['flow_source'],
	                    'tk3rd_pub_id' => $l ['pub_id'],
	                    'order_type' => $l ['order_type'],
	                    'income_rate' => $l ['income_rate'],
	                    'pub_share_pre_fee' => $l ['pub_share_pre_fee'],
	                    'subsidy_rate' => $l ['subsidy_rate'],
	                    'subsidy_type' => $l ['subsidy_type'],
	                    'subsidy_fee' => $l ['subsidy_fee'],
	                    'terminal_type' => $l ['terminal_type'],
	                    'auction_category' => $l ['item_category_name'],
	                    'site_id' => $l ['site_id'],
	                    'site_name' => $l ['site_name'],
	                    'adzone_id' => $l ['adzone_id'],
	                    'adzone_name' => $l ['adzone_name'],
	                    'alipay_total_price' => $l ['alipay_total_price'],
	                    'total_commission_rate' => $l ['total_commission_rate']/100,
	                    'total_commission_fee' => $l ['total_commission_fee'],
	                    'item_img' => $l['item_img'],
	                    'relation_id'=>$tb_rid,
	                    'status' => '1'  // 是否结算给用户，1未结算，2已结算
	                );
	                //保存订单到临时表中
	                $res_add_tmp=$TbOrderTmp->add($data);
	                
	                // 成功次数
	                $count ++;
	            }
	        }
	        
	        //保存到临时表中完成，处理当前所有的淘宝订单
	        //$TbOrderTmp->treat();
	    }
	    $data=array(
	        'count'=>$count,//成功执行条数
	        'list_num' =>count ( $list ),//本次订单总数
	        'position_index'=>$position_index
	    );
	    $res=array(
	        'code'=>0,
	        'msg'=>'成功',
	        'data'=>$data
	    );
	    return $res;
	}
	
	public function pullOrder_old($query_type,$tk_status,$order_scene,$start_time,$end_time,$member_type,$page_no,$page_size,$position_index,$jump_type)
	{
	    // 成功条数
	    $count = 0;
	    
	    $User = new \Common\Model\UserModel ();
	    //注意：只要没有走确认收货的订单状态，就不会有 total_commission_fee 和commission
	    Vendor('tbk.tbk','','.class.php');
	    $tbk=new \tbk();
	    $results=$tbk->getOrderListNew($query_type,$tk_status,$order_scene,$start_time,$end_time,$member_type,$page_no,$page_size,$position_index,$jump_type);
	    //dump($results);die();
	    if ($results ['data']['results']['publisher_order_dto']) {
	        // 本次查询有结果
	        $position_index=$results['data']['position_index'];
	        if($results ['data']['results']['publisher_order_dto'][0]['trade_id']) {
	            //多件商品
	            $list_tmp=$results ['data']['results']['publisher_order_dto'];
	            $num1=count($list_tmp);
	            $list=array();
	            for ($i=0;$i<$num1;$i++) {
	                $list[]=$list_tmp[$i];
	            }
	        }else {
	            //一件商品
	            $list[]=$results ['data']['results']['publisher_order_dto'];
	        }
	        $TbOrderTmp=new \Common\Model\TbOrderTmpModel();
	        foreach ( $list as $l ) {
	            // 判断订单是否存在，存在不处理
	            // 淘宝订单号
	            $trade_id = $l ['trade_id'];
	            // 淘宝父订单号
	            $trade_parent_id = $l ['trade_parent_id'];
	            // 淘宝推广位pid
	            $tb_pid = 'mm_'.TBK_APPID.'_' . $l ['site_id'] . '_' . $l ['adzone_id'];
	            // 淘宝用户uid
	            $tb_uid = substr ( $trade_id, - 6 );
	            // 渠道ID
	            $tb_rid = $l['relation_id'];
	            
	            $res_exist = $this->where ( "trade_id='$trade_id'" )->find ();
	            if ($res_exist) {
	                // 存在
	                // 判断是常规订单还是渠道订单
	                if($order_scene=='1'){
	                    // 常规订单
	                    // 查询是否有用户绑定了该推广位
	                    // 先判断微信群主推广位pid
	                    $res_user = $User->where ( "tb_pid_master='$tb_pid'" )->field('uid')->find ();
	                    if ($res_user ['uid']) {
	                        // 用户存在，给对应用户返利
	                        $user_id = $res_user ['uid'];
	                    } else {
	                        // 再判断淘宝用户个人pid
	                        $res_user2 = $User->where ( "tb_pid='$tb_pid' and tb_uid='$tb_uid'" )->field('uid')->find ();
	                        if ($res_user2 ['uid']) {
	                            // 用户存在，给对应用户返利
	                            $user_id = $res_user2 ['uid'];
	                        } else {
	                            $user_id = $res_exist['user_id'];
	                        }
	                    }
	                }else if($order_scene=='2'){
	                    // 渠道订单
	                    $res_user = $User->where ( "tb_rid='$tb_rid'" )->field('uid')->find ();
	                    if ($res_user ['uid']) {
	                        // 用户存在，给对应用户返利
	                        $user_id = $res_user ['uid'];
	                    }else {
	                        $user_id = $res_exist['user_id'];
	                    }
	                }
	                // 修改订单的一些重要参数
	                $data_o = array (
	                    'user_id' => $user_id,
	                    'price' => $l ['item_price'],
	                    'pay_price' => $l ['pay_price'],
	                    'tb_commission' => $l ['pub_share_fee'],
	                    'commission_rate' => $l ['pub_share_rate']/100,
	                    'alipay_total_price' => $l ['alipay_total_price'],
	                    'total_commission_rate' => $l ['total_commission_rate']/100,
	                    'total_commission_fee' => $l ['total_commission_fee'],
	                    'earning_time' => $l ['tk_earning_time'],
	                    'tk_status' => $l ['tk_status']
	                );
	                //保存订单到临时表中
	                $res_order_tmp=$TbOrderTmp->where ( "trade_id='$trade_id'" )->save ( $data_o );
	                
	                
	                $res_order = $this->where ( "trade_id='$trade_id'" )->save ( $data_o );
	                
	                //如果之前没有所属用户，再做一次预估统计和消息推送
	                if($user_id and $res_exist['user_id']==''){
	                    //极光推送消息
	                    Vendor('jpush.jpush','','.class.php');
	                    $jpush=new \jpush();
	                    $alias=$user_id;//推送别名
	                    $title=APP_NAME.'通知您有新订单';
	                    $content='您有一笔新订单：'.$l ['item_title'];
	                    $key='order';
	                    $value='taobao';
	                    $res_push=$jpush->push($alias,$title,$content,'','','',$key,$value);
	                    
	                    //对订单做预估收入处理
	                    $res_treat_tmp = $this->treat_tmp ( $trade_id, $l ['pub_share_pre_fee'] );
	                }
	                
	                // 判断订单状态，如果尚未结算，给用户返利
	                // 原来未结算，现在结算的订单进行返利
	                if ($res_exist ['status'] == '1' and $l ['tk_status'] == '3') {
	                    // 尚未结算，给用户返利
	                    if ($user_id) {
	                        // 用户存在，给对应用户返利
	                        $res_treat = $this->treat ( $trade_id, $l ['total_commission_fee'] );
	                    } else {
	                        // 不存在对应用户，不去处理
	                    }
	                } else {
	                    // 已结算，不处理
	                }
	                
	                //针对已经存在，失效的订单，删除预估记录
	                if($l ['tk_status'] == '13'){
	                    $UserBalanceRecordTmp=new \Common\Model\UserBalanceRecordTmpModel();
	                    $res_tmp_del=$UserBalanceRecordTmp->where("order_id='$trade_id' and type='1'")->delete();
	                }
	                
	                // 成功执行次数
	                $count ++;
	            } else {
	                //不存在
	                // 判断是常规订单还是渠道订单
	                if($order_scene=='1'){
	                    // 常规订单
	                    // 查询是否有用户绑定了该推广位
	                    // 先判断微信群主推广位pid
	                    $res_user = $User->where ( "tb_pid_master='$tb_pid'" )->field('uid')->find ();
	                    if ($res_user ['uid']) {
	                        // 用户存在，给对应用户返利
	                        $user_id = $res_user ['uid'];
	                    } else {
	                        // 再判断淘宝用户个人pid
	                        $res_user2 = $User->where ( "tb_pid='$tb_pid' and tb_uid='$tb_uid'" )->field('uid')->find ();
	                        if ($res_user2 ['uid']) {
	                            // 用户存在，给对应用户返利
	                            $user_id = $res_user2 ['uid'];
	                        } else {
	                            $user_id = null;
	                        }
	                    }
	                }else if($order_scene=='2'){
	                    // 渠道订单
	                    $res_user = $User->where ( "tb_rid='$tb_rid'" )->field('uid')->find ();
	                    //并且判断VIP会员没有到期
	                    if ($res_user ['uid']) {
	                        // 用户存在，给对应用户返利
	                        $user_id = $res_user ['uid'];
	                    }
	                }
	                $data = array (
	                    'user_id' => $user_id,
	                    'trade_parent_id' => $trade_parent_id,
	                    'trade_id' => $trade_id,
	                    'num_iid' => $l ['item_id'],
	                    'item_title' => $l ['item_title'],
	                    'item_num' => $l ['item_num'],
	                    'price' => $l ['item_price'],
	                    'pay_price' => $l ['pay_price'],
	                    'seller_nick' => $l ['seller_nick'],
	                    'seller_shop_title' => $l ['seller_shop_title'],
	                    'tb_commission' => $l ['pub_share_fee'],
	                    'commission_rate' => $l ['pub_share_rate']/100,
	                    'create_time' => $l ['tk_create_time'],
	                    'earning_time' => $l ['tk_earning_time'],
	                    'tk_status' => $l ['tk_status'],
	                    'tk3rd_type' => $l ['flow_source'],
	                    'tk3rd_pub_id' => $l ['pub_id'],
	                    'order_type' => $l ['order_type'],
	                    'income_rate' => $l ['income_rate'],
	                    'pub_share_pre_fee' => $l ['pub_share_pre_fee'],
	                    'subsidy_rate' => $l ['subsidy_rate'],
	                    'subsidy_type' => $l ['subsidy_type'],
	                    'subsidy_fee' => $l ['subsidy_fee'],
	                    'terminal_type' => $l ['terminal_type'],
	                    'auction_category' => $l ['item_category_name'],
	                    'site_id' => $l ['site_id'],
	                    'site_name' => $l ['site_name'],
	                    'adzone_id' => $l ['adzone_id'],
	                    'adzone_name' => $l ['adzone_name'],
	                    'alipay_total_price' => $l ['alipay_total_price'],
	                    'total_commission_rate' => $l ['total_commission_rate']/100,
	                    'total_commission_fee' => $l ['total_commission_fee'],
	                    'item_img' => $l['item_img'],
	                    'status' => '1'  // 是否结算给用户，1未结算，2已结算
	                );
	                //保存订单到临时表中
	                $res_add_tmp=$TbOrderTmp->add($data);
	                
	                // 保存订单
	                $res_add = $this->add ( $data );
	                // 给用户返利
	                if ($l ['tk_status'] == '3') {
	                    // 只有结算订单才给用户返利
	                    if ($user_id) {
	                        $res_treat = $this->treat ( $trade_id, $l ['total_commission_fee'] );
	                    }
	                }
	                
	                if ($user_id) {
	                    //极光推送消息
	                    Vendor('jpush.jpush','','.class.php');
	                    $jpush=new \jpush();
	                    $alias=$user_id;//推送别名
	                    $title=APP_NAME.'通知您有新订单';
	                    $content='您有一笔新订单：'.$l ['item_title'];
	                    $key='order';
	                    $value='taobao';
	                    $res_push=$jpush->push($alias,$title,$content,'','','',$key,$value);
	                    
	                    //给推荐人推送
	                    $userMsg=$User->getUserMsg($user_id);
	                    
	                    //给直接推荐人加经验值
	                    if($userMsg['referrer_id']) {
	                        //是否购物
	                        $old_is_buy=$userMsg['is_buy'];
	                        if($old_is_buy=='N') {
	                            $referrer_id=$userMsg['referrer_id'];
	                            $referrerMsg=$User->getUserMsg($referrer_id);
	                            //判断推荐人是否可以升级为VIP
	                            $new_exp=$referrerMsg['exp']+USER_UPGRADE_BUY;
	                            $data_referrer=array(
	                                'exp'=>$new_exp
	                            );
	                            //判断推荐人应该升级到那个会员组
	                            //大于当前会员组，并且小于新经验值的最大值
	                            $group_id=$referrerMsg['group_id'];
	                            $UserGroup=new \Common\Model\UserGroupModel();
	                            $res_group=$UserGroup->where("id>$group_id and exp<=$new_exp")->order('exp desc')->field('id')->find();
	                            if($res_group['id']){
	                                $data_referrer['group_id']=$res_group['id'];
	                            }
	                            $res_referrer_g=$User->where("uid='$referrer_id'")->save($data_referrer);
	                            //设置用户为已购物
	                            $data_user=array(
	                                'is_buy'=>'Y',//是否购物，Y是
	                            );
	                            $res_buy=$User->where("uid='$user_id'")->save($data_user);
	                            
	                            //保存经验值变动记录-首次购物
	                            $UserExpRecord=new \Common\Model\UserExpRecordModel();
	                            $res_exp_record=$UserExpRecord->addLog($referrer_id,USER_UPGRADE_BUY,$new_exp,'buy_first_r');
	                            
	                        }
	                    }
	                    
	                    if($userMsg['group_id']=='1') {
	                        //普通会员订单，才给上级推送
	                        if($userMsg['referrer_id']) {
	                            $referrer_id=$userMsg['referrer_id'];
	                            $referrerMsg=$User->getUserMsg($referrer_id);
	                            if($referrerMsg['group_id']!='1') {
	                                $alias=$referrer_id;//推送别名
	                                $title=APP_NAME.'通知您有新订单';
	                                $content='您有一笔淘宝一级订单：'.$l ['item_title'];
	                                $key='order';
	                                $value='taobao1';
	                                $res_push=$jpush->push($alias,$title,$content,'','','',$key,$value);
	                            }
	                            
	                            if($referrerMsg['referrer_id']) {
	                                $referrer_id2=$referrerMsg['referrer_id'];
	                                $referrerMsg2=$User->getUserMsg($referrer_id2);
	                                if($referrerMsg2['group_id']!='1') {
	                                    $alias=$referrer_id2;//推送别名
	                                    $title=APP_NAME.'通知您有新订单';
	                                    $content='您有一笔淘宝二级订单：'.$l ['item_title'];
	                                    $key='order';
	                                    $value='taobao2';
	                                    $res_push=$jpush->push($alias,$title,$content,'','','',$key,$value);
	                                }
	                            }
	                        }
	                    }
	                    
	                    //对订单做预估收入处理
	                    $res_treat_tmp = $this->treat_tmp ( $trade_id, $l ['pub_share_pre_fee'] );
	                }
	                // 成功次数
	                $count ++;
	            }
	        }
	    }
	    $data=array(
	        'count'=>$count,//成功执行条数
	        'list_num' =>count ( $list ),//本次订单总数
	        'position_index'=>$position_index
	    );
	    $res=array(
	        'code'=>0,
	        'msg'=>'成功',
	        'data'=>$data
	    );
	    return $res;
	}
	
	/**
	 * 给会员返利
	 * @param string $trade_id:淘宝订单号
	 * @param string $money:淘宝总佣金
	 * @return boolean
	 */
	public function treat($trade_id,$money)
	{
		$msg=$this->where("trade_id='$trade_id'")->find();
		if($msg) {
			//给购买会员返利
			$uid=$msg['user_id'];
			$User=new \Common\Model\UserModel();
			$UserMsg=$User->getUserMsg($uid);
			//根据用户所在的组获取相应收益比例
			$UserGroup=new \Common\Model\UserGroupModel();
			$groupMsg=$UserGroup->getGroupMsg($UserMsg['group_id']);
			if($groupMsg and $UserMsg) {
				//佣金-客户
				$money_user=$money*$groupMsg['fee_user']/100;
				//四舍五入
				$money_user=round($money_user, 2);
				//佣金-扣税
				$money_service=$money*$groupMsg['fee_service']/100;
				//四舍五入
				$money_service=round($money_service, 2);
				//佣金-平台
				$money_plantform=$money*$groupMsg['fee_plantform']/100;
				//四舍五入
				$money_plantform=round($money_plantform, 2);
				$data_user=array(
						//'balance'=>$UserMsg['balance']+$money_user,
						'balance_user'=>$UserMsg['balance_user']+$money_user,
						'balance_service'=>$UserMsg['balance_service']+$money_service,
						'balance_plantform'=>$UserMsg['balance_plantform']+$money_plantform,
						'is_buy'=>'Y',//是否购物，Y是
				);
				//是否购物
				$old_is_buy=$UserMsg['is_buy'];
				//判断用户是否已领取过0元购
				$subsidy_amount=0;
				if($UserMsg['is_buy_free']=='N'){
				    //未领取
				    //判断商品是否在0元购商品中
				    $TbGoodsFree=new \Common\Model\TbGoodsFreeModel();
				    $goods_id=$msg['num_iid'];
				    $goodsFreeMsg=$TbGoodsFree->where("goods_id=$goods_id")->find();
				    if($goodsFreeMsg['subsidy_amount']>0){
				        //存在商品并且有补贴金额
				        $subsidy_amount=$goodsFreeMsg['subsidy_amount'];
				        $data_user['is_buy_free']='Y';
				        $data_user['balance']=$UserMsg['balance']+$money_user+$subsidy_amount;
				    }else {
				        //不在0元购商品中
				        $data_user['balance']=$UserMsg['balance']+$money_user;
				    }
				}else {
				    //已领取0元购商品补贴
				    $data_user['balance']=$UserMsg['balance']+$money_user;
				}
				if(!$User->create($data_user)) {
					//验证不通过
					return false;
				}else {
					//开启事务
					$User->startTrans();
					//增加用户余额
					$res_balance=$User->where("uid='$uid'")->save($data_user);
					//保存余额变动记录
					$UserBalanceRecord=new \Common\Model\UserBalanceRecordModel();
					$all_money=$UserMsg['balance']+$money_user;
					$res_record=$UserBalanceRecord->addLog($uid, $money_user, $all_money, 'tbk','2',$trade_id,'1');
					//保存0元购余额变动记录
					if($subsidy_amount>0){
					    $all_money_free=$UserMsg['balance']+$money_user+$subsidy_amount;
					    $res_record_free=$UserBalanceRecord->addLog($uid, $subsidy_amount, $all_money_free, 'tbk_free','2',$trade_id,'1');
					}else {
					    $res_record_free=true;
					}
					//修改本订单状态
					//计算实际佣金
					$commission=$money_user;
					$data_order=array(
							'user_id'=>$uid,
							'status'=>'2',
							'commission'=>$commission
					);
					$res_order=$this->where("trade_id='$trade_id'")->save($data_order);
					
					if($res_balance!==false and $res_record!==false and $res_record_free!==false and $res_order!==false) {
						//给推荐人返利
					    $res_treat_commission=$User->treatCommission($trade_id, $money, '1', $UserMsg,$money_user);
						if($res_treat_commission===true){
						    //提交事务
						    $User->commit();
						    return true;
						}else {
						    //回滚
						    $User->rollback();
						    return false;
						}
					}else {
						//回滚
						$User->rollback();
						return false;
					}
				}
			}else {
				//用户组不存在
				return false;
			}
		}else {
			return false;
		}
	}
	
	/**
	 * 给会员返利-预估
	 * @param string $trade_id:淘宝订单号
	 * @param string $money:淘宝总佣金
	 * @return boolean
	 */
	public function treat_tmp($trade_id,$money)
	{
		$msg=$this->where("trade_id='$trade_id'")->find();
		if($msg and $msg['tk_status']!='13') {
			$UserBalanceRecordTmp=new \Common\Model\UserBalanceRecordTmpModel();
			//判断该订单是否已存在
			$where=array(
					'type'=>'1',//淘宝
					'order_id'=>$trade_id
			);
			$res_exist=$UserBalanceRecordTmp->where($where)->order('id desc')->find();
			if($res_exist) {
				//已存在，不处理
				return true;
			}else {
				//给购买会员返利
				$uid=$msg['user_id'];
				$User=new \Common\Model\UserModel();
				$res_treat_commission=$User->treatCommissionTmp($trade_id, $money, '1', $uid, $msg['create_time']);
				if($res_treat_commission===true){
				    return true;
				}else {
				    return false;
				}
			}
		}else {
			return false;
		}
	}
}
?>