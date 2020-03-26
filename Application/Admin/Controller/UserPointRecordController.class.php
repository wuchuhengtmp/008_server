<?php
/**
 * 会员管理
 * 会员积分变动记录管理
 */
namespace Admin\Controller;
use Admin\Common\Controller\AuthController;
class UserPointRecordController extends AuthController
{
	public function index($user_id='')
	{
		$where='1';
		if($user_id) {
			$where.=" and user_id='$user_id'";
		}
		//用户手机号码
		if(trim(I('get.phone'))){
		    $phone=trim(I('get.phone'));
		    $User=new \Common\Model\UserModel();
		    $res_u=$User->where("phone='$phone'")->find();
		    if($res_u['uid']){
		        $user_id=$res_u['uid'];
		        $where.=" and user_id='$user_id'";
		    }else {
		        layout(false);
		        $this->error('查询用户不存在！');
		    }
		}
		
		$UserPointRecord=new \Common\Model\UserPointRecordModel();
		$count=$UserPointRecord->where($where)->count();
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
		 
		$list = $UserPointRecord->where($where)->page($p.','.$per)->order('id desc')->select();
		$this->assign('list',$list);
		 
		$this->display();
	}
}