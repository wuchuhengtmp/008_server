<?php

/**
 * 拼多多订单管理
 */
namespace Agent\Controller;
use Agent\Common\Controller\AuthController;

class PddOrderController extends AuthController 
{
	//订单列表
	public function index()
	{
	    $agent_id=$_SESSION['agent_id'];
	    //获取代理商所有团队成员
	    $User=new \Common\Model\UserModel();
	    $teamList=$User->where("FIND_IN_SET($agent_id,path) and uid!=$agent_id")->select();
	    $all_uid='';
	    foreach ($teamList as $l){
	        $all_uid.=$l['uid'].',';
	    }
	    if($all_uid){
	        $all_uid=substr($all_uid, 0,-1);
	        $where="user_id in ($all_uid)";
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
	    }
		 
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
}
?>