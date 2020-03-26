<?php

/**
 * 唯品会订单管理
 */
namespace Admin\Controller;
use Admin\Common\Controller\AuthController;

class VipOrderController extends AuthController
{
    //订单列表
    public function index()
    {
        $where='1';
        //商品名称
        if(trim(I('get.goodsName')))
        {
            $goodsName=trim(I('get.goodsName'));
            $where="goodsName like '%$goodsName%'";
        }
        //订单号
        if(trim(I('get.orderSn')))
        {
            $orderSn=trim(I('get.orderSn'));
            $where.=" and orderSn='$orderSn'";
        }
        //订单状态
        if(trim(I('get.orderSubStatusName'))!=='')
        {
            $orderSubStatusName=trim(I('get.orderSubStatusName'));
            $where.=" and orderSubStatusName='$orderSubStatusName'";
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
        $VipOrder=new \Common\Model\VipOrderModel();
        $count=$VipOrder->where($where)->count();
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

        $list = $VipOrder->where($where)->page($p.','.$per)->order('id desc')->select();
        $this->assign('list',$list);

        $this->display();
    }

    //订单详情
    public function msg($id)
    {
        $VipOrder=new \Common\Model\VipOrderModel();
        $msg=$VipOrder->getOrderMsg($id);
        $this->assign('msg',$msg);

        $this->display();
    }

    // 处理拼多多遗漏订单
    public function treatOrder()
    {
        if($_POST){
            layout(false);
            if(trim(I('post.time'))){
                // 订单查询开始时间
                $start_time=trim(I('post.time'));
                $start_update_time = strtotime($start_time);

                // 订单查询截止时间
                $Time = new \Common\Model\TimeModel ();
                // 当前时间往后30分钟
                $end_time = $Time->getAfterDateTime ( $start_time, '2', '', '','', '', '+30');
                $end_update_time = strtotime($end_time);

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