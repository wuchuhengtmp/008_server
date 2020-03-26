<?php
/**
 * 淘宝订单-临时管理类
 */
namespace Common\Model;
use Think\Model;

class TbOrderTmpModel extends Model
{
	//验证规则
	protected $_validate =array();
	
	public function treat()
	{
	    $config_id=1;
	    //判断当前是否正在处理
	    $TaskConfig=new \Common\Model\TaskConfigModel();
	    $config=$TaskConfig->getConfig($config_id);
	    
	    if(!empty($config) and $config['status']=='N'){
	        //记录处理淘宝临时订单时间
	        $now=date('Y-m-d H:i:s');
	        writeLog('记录处理淘宝临时订单时间：'.$now);
	        
	        //可以处理
	        //修改处理标记
	        $data_c=array(
	            'status'=>'Y'
	        );
	        $res_c=$TaskConfig->where("id=$config_id")->save($data_c);
	        
	        if($res_c!==false){
	            //获取全部当前记录
	            $list=$this->order('id asc')->select();
	            
	            if(!empty($list)){
	                $TbOrder=new \Common\Model\TbOrderModel();
	                $User=new \Common\Model\UserModel();
	                $UserGroup=new \Common\Model\UserGroupModel();
	                $UserBalanceRecordTmp=new \Common\Model\UserBalanceRecordTmpModel();
	                $UserExpRecord=new \Common\Model\UserExpRecordModel();
	                
	                $count=0;
	                foreach ($list as $l){
	                    // 判断订单是否存在，存在不处理
	                    // 淘宝订单号
	                    $trade_id = $l ['trade_id'];
	                    // 淘宝父订单号
	                    $trade_parent_id = $l ['trade_parent_id'];
	                    //用户ID
	                    $user_id = $l['user_id'];
	                    
	                    $res_exist = $TbOrder->where ( "trade_id='$trade_id'" )->find ();
	                    if ($res_exist) {
	                        // 存在
	                        if(empty($user_id)){
	                            $user_id = $res_exist['user_id'];
	                        }
	                        // 修改订单的一些重要参数
	                        $data_o = array (
	                            'user_id' => $user_id,
	                            'price' => $l ['price'],
	                            'pay_price' => $l ['pay_price'],
	                            'tb_commission' => $l ['tb_commission'],
	                            'commission_rate' => $l ['tb_commission'],
	                            'alipay_total_price' => $l ['alipay_total_price'],
	                            'total_commission_rate' => $l ['total_commission_rate'],
	                            'total_commission_fee' => $l ['total_commission_fee'],
	                            'earning_time' => $l ['earning_time'],
	                            'tk_status' => $l ['tk_status']
	                        );
	                        if(!empty($l ['relation_id'])){
	                            $data_o['relation_id']=$l ['relation_id'];
	                        }
	                        $res_order = $TbOrder->where ( "trade_id='$trade_id'" )->save ( $data_o );
	                        
	                        //如果之前没有所属用户，再做一次预估统计和消息推送
	                        if($user_id and $res_exist['user_id']==''){
	                            //对订单做预估收入处理
	                            $res_treat_tmp = $TbOrder->treat_tmp ( $trade_id, $l ['pub_share_pre_fee'] );
	                        }
	                        
	                        // 判断订单状态，如果尚未结算，给用户返利
	                        // 原来未结算，现在结算的订单进行返利
	                        if ($res_exist ['status'] == '1' and $l ['tk_status'] == '3') {
	                            // 尚未结算，给用户返利
	                            if ($user_id) {
	                                // 用户存在，给对应用户返利
	                                $res_treat = $TbOrder->treat ( $trade_id, $l ['total_commission_fee'] );
	                            } else {
	                                // 不存在对应用户，不去处理
	                            }
	                        } else {
	                            // 已结算，不处理
	                        }
	                        
	                        //针对已经存在，失效的订单，删除预估记录
	                        if($l ['tk_status'] == '13'){
	                            $res_tmp_del=$UserBalanceRecordTmp->where("order_id='$trade_id' and type='1'")->delete();
	                        }
	                    }else {
	                        // 不存在
	                        $data = array (
	                            'user_id' => $user_id,
	                            'trade_parent_id' => $trade_parent_id,
	                            'trade_id' => $trade_id,
	                            'num_iid' => $l ['num_iid'],
	                            'item_title' => $l ['item_title'],
	                            'item_num' => $l ['item_num'],
	                            'price' => $l ['price'],
	                            'pay_price' => $l ['pay_price'],
	                            'seller_nick' => $l ['seller_nick'],
	                            'seller_shop_title' => $l ['seller_shop_title'],
	                            'tb_commission' => $l ['tb_commission'],
	                            'commission_rate' => $l ['commission_rate'],
	                            'create_time' => $l ['create_time'],
	                            'earning_time' => $l ['earning_time'],
	                            'tk_status' => $l ['tk_status'],
	                            'tk3rd_type' => $l ['tk3rd_type'],
	                            'tk3rd_pub_id' => $l ['tk3rd_pub_id'],
	                            'order_type' => $l ['order_type'],
	                            'income_rate' => $l ['income_rate'],
	                            'pub_share_pre_fee' => $l ['pub_share_pre_fee'],
	                            'subsidy_rate' => $l ['subsidy_rate'],
	                            'subsidy_type' => $l ['subsidy_type'],
	                            'subsidy_fee' => $l ['subsidy_fee'],
	                            'terminal_type' => $l ['terminal_type'],
	                            'auction_category' => $l ['auction_category'],
	                            'site_id' => $l ['site_id'],
	                            'site_name' => $l ['site_name'],
	                            'adzone_id' => $l ['adzone_id'],
	                            'adzone_name' => $l ['adzone_name'],
	                            'alipay_total_price' => $l ['alipay_total_price'],
	                            'total_commission_rate' => $l ['total_commission_rate'],
	                            'total_commission_fee' => $l ['total_commission_fee'],
	                            'item_img' => $l['item_img'],
	                            'relation_id'=> $l['relation_id'],
	                            'status' => '1'  // 是否结算给用户，1未结算，2已结算
	                        );
	                        // 保存订单
	                        $res_add = $TbOrder->add ( $data );
	                        // 给用户返利
	                        if ($l ['tk_status'] == '3' and !empty($user_id)) {
	                            // 只有结算订单才给用户返利
	                            $res_treat = $TbOrder->treat ( $trade_id, $l ['total_commission_fee'] );
	                        }
	                        
	                        if ($user_id) {
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
	                                    $res_exp_record=$UserExpRecord->addLog($referrer_id,USER_UPGRADE_BUY,$new_exp,'buy_first_r');
	                                }
	                            }
	                            //对订单做预估收入处理
	                            $res_treat_tmp = $TbOrder->treat_tmp ( $trade_id, $l ['pub_share_pre_fee'] );
	                        }
	                    }
	                    
	                    //处理成功后，删除该条记录
	                    $res_del=$this->where("trade_id='$trade_id'")->delete();
	                    // 成功次数
	                    $count ++;
	                }
	                
	                //处理完成
	                //修改处理标记
	                $data_c=array(
	                    'status'=>'N'
	                );
	                $res_c=$TaskConfig->where("id=$config_id")->save($data_c);
	                //判断是否有新的充值记录进队列，存在继续处理
	                $res_exist=$this->count();
	                if($res_exist>0){
	                    //继续处理
	                    self::treat();
	                }
	            }else {
	                //没有待处理任务
	                //处理完成
	                //修改处理标记
	                $data_c=array(
	                    'status'=>'N'
	                );
	                $res_c=$TaskConfig->where("id=$config_id")->save($data_c);
	            }
	        }
	    }
	}
}
?>