<?php
/**
 * 京东订单管理
 */
namespace Agent\Controller;
use Agent\Common\Controller\AuthController;

class JingdongOrderController extends AuthController
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
	    }
			
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
}
?>