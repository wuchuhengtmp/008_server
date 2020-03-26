<?php
namespace Agent\Controller;
use Agent\Common\Controller\AuthController;
class SystemController extends AuthController
{
    public function index_show()
    {
        $agent_id=$_SESSION['agent_id'];
        //统计会员数量
        $User=new \Common\Model\UserModel();
        //总会员数
        $user_allnum=$User->where("FIND_IN_SET($agent_id,path) and uid!=$agent_id")->count();
        $this->assign('user_allnum',$user_allnum);
        //普通会员数
        $user_num1=$User->where("group_id=1 and FIND_IN_SET($agent_id,path)")->count();
        $this->assign('user_num1',$user_num1);
        //VIP会员
        $this->assign('user_vipnum',$user_allnum-$user_num1);
        //今日新增会员
        $user_today_num=$User->where("date_format(register_time,'%Y-%m-%d')=date_format(now(),'%Y-%m-%d')  and FIND_IN_SET($agent_id,path)")->count();
        $this->assign('user_today_num',$user_today_num);
        //本月新增会员
        $user_month_num=$User->where("date_format(register_time,'%Y-%m')=date_format(now(),'%Y-%m')  and FIND_IN_SET($agent_id,path)")->count();
        $this->assign('user_month_num',$user_month_num);
        
        //获取代理商所有团队成员
        $teamList=$User->where("FIND_IN_SET($agent_id,path) and uid!=$agent_id")->select();
        $all_uid='';
        foreach ($teamList as $l){
            $all_uid.=$l['uid'].',';
        }
        $tb_order_finished_num=$tb_order_pay_num=$tb_order_today_num=$tb_order_month_num=0;
        $pdd_order_finished_num=$pdd_order_pay_num=$pdd_order_today_num=$pdd_order_month_num=0;
        $jd_order_finished_num=$jd_order_pay_num=$jd_order_today_num=$jd_order_month_num=0;
        
        if($all_uid){
            $all_uid=substr($all_uid, 0,-1);
            
            //统计淘宝订单数
            $TbOrder=new \Common\Model\TbOrderModel();
            //已结算订单
            $tb_order_finished_num=$TbOrder->where("tk_status='3' and user_id in ($all_uid)")->count();
            //已付款订单
            $tb_order_pay_num=$TbOrder->where("tk_status='12' and user_id in ($all_uid)")->count();
            //今日订单
            $tb_order_today_num=$TbOrder->where("date_format(create_time,'%Y-%m-%d')=date_format(now(),'%Y-%m-%d') and user_id in ($all_uid)")->count();
            //本月订单
            $tb_order_month_num=$TbOrder->where("date_format(create_time,'%Y-%m')=date_format(now(),'%Y-%m') and user_id in ($all_uid)")->count();
            
            //统计拼多多订单数
            $PddOrder=new \Common\Model\PddOrderModel();
            //已结算订单
            $pdd_order_finished_num=$PddOrder->where("order_status='5' and user_id in ($all_uid)")->count();
            //已付款订单
            $pdd_order_pay_num=$PddOrder->where("order_status in (0,1,2,3) and user_id in ($all_uid)")->count();
            //今日订单
            $pdd_order_today_num=$PddOrder->where("date_format(order_create_time,'%Y-%m-%d')=date_format(now(),'%Y-%m-%d') and user_id in ($all_uid)")->count();
            //本月订单
            $pdd_order_month_num=$PddOrder->where("date_format(order_create_time,'%Y-%m')=date_format(now(),'%Y-%m') and user_id in ($all_uid)")->count();
            
            
            //统计京东订单数
            $JingdongOrderDetail=new \Common\Model\JingdongOrderDetailModel();
            //已结算订单
            $jd_order_finished_num=$JingdongOrderDetail->where("validCode=18 and user_id in ($all_uid)")->count();
            //已付款订单
            $jd_order_pay_num=$JingdongOrderDetail->where("validCode in (16,17) and user_id in ($all_uid)")->count();
            //今日订单
            $jd_order_today_num=$JingdongOrderDetail->where("date_format(orderTime,'%Y-%m-%d')=date_format(now(),'%Y-%m-%d') and user_id in ($all_uid)")->count();
            //本月订单
            $jd_order_month_num=$JingdongOrderDetail->where("date_format(orderTime,'%Y-%m')=date_format(now(),'%Y-%m') and user_id in ($all_uid)")->count();
             
        }
        $this->assign('tb_order_finished_num',$tb_order_finished_num);
        $this->assign('tb_order_pay_num',$tb_order_pay_num);
        $this->assign('tb_order_today_num',$tb_order_today_num);
        $this->assign('tb_order_month_num',$tb_order_month_num);
        
        $this->assign('pdd_order_finished_num',$pdd_order_finished_num);
        $this->assign('pdd_order_pay_num',$pdd_order_pay_num);
        $this->assign('pdd_order_today_num',$pdd_order_today_num);
        $this->assign('pdd_order_month_num',$pdd_order_month_num);
        
        $this->assign('jd_order_finished_num',$jd_order_finished_num);
        $this->assign('jd_order_pay_num',$jd_order_pay_num);
        $this->assign('jd_order_today_num',$jd_order_today_num);
        $this->assign('jd_order_month_num',$jd_order_month_num);
        
        //收益统计
        //累计收益
        $UserBalanceRecord=new \Common\Model\UserBalanceRecordModel();
        $amount=$UserBalanceRecord->where("user_id=$agent_id and action!='draw'")->sum('money');
        $this->assign('amount',$amount/100);
        
        //今日收益
        $amount_today=$UserBalanceRecord->where("user_id=$agent_id and action!='draw' and date_format(pay_time,'%Y-%m-%d')=date_format(now(),'%Y-%m-%d')")->sum('money');
        $this->assign('amount_today',$amount_today/100);
        //本月收益
        $amount_month=$UserBalanceRecord->where("user_id=$agent_id and action!='draw' and date_format(pay_time,'%Y-%m')=date_format(now(),'%Y-%m')")->sum('money');
        $this->assign('amount_month',$amount_month/100);
        
        //获取最近30天淘宝订单
        $sql="SELECT count(id) as num,date(create_time) as date FROM __PREFIX__tb_order WHERE user_id in ($all_uid) and DATE_SUB(CURDATE(), INTERVAL 30 DAY) <= date(create_time) GROUP BY date(create_time)";
        $list=M()->query($sql);
        $this->assign('list',$list);
        
        $this->display();
    }
}