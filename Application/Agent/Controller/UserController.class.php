<?php
/**
 * 会员管理
 */
namespace Agent\Controller;
use Agent\Common\Controller\AuthController;
class UserController extends AuthController
{
    public function index()
    {
        $agent_id=$_SESSION['agent_id'];
        
    	//获取用户组列表
    	$UserGroup=new \Common\Model\UserGroupModel();
    	$glist=$UserGroup->getGroupList();
    	$this->assign('glist',$glist);
    	
    	$where="FIND_IN_SET($agent_id,path) and uid!=$agent_id";
    	if(trim(I('get.group_id'))) {
    		$group_id=trim(I('get.group_id'));
    		$this->assign('group_id',$group_id);
    		$where.=" and group_id='$group_id'";
    	}
    	
    	if(trim(I('get.search'))) {
    		$search=trim(I('get.search'));
    		$where.=" and (username='$search' or email='$search' or phone='$search')";
    	}
    	//备注姓名
    	if(trim(I('get.remark'))) {
    		$remark=trim(I('get.remark'));
    		$where.=" and remark like '%$remark%'";
    	}
    	//城市
    	if(trim(I('get.city'))) {
    		$city=trim(I('get.city'));
    		$where.=" and phone_city='$city'";
    	}
    	$User=new \Common\Model\UserModel();
    	//推荐人手机
    	if(trim(I('get.referrer_phone'))) {
    		$referrer_phone=trim(I('get.referrer_phone'));
    		$res_referrer=$User->where("phone='$referrer_phone'")->find();
    		if($res_referrer['uid']) {
    			$referrer_id=$res_referrer['uid'];
    			$where.=" and referrer_id='$referrer_id'";
    		}else {
    			layout(false);
    			$this->error('推荐人不存在！');
    		}
    	}
    	$count=$User->where($where)->count();
    	$per = 30;
    	if($_GET['p']) {
    		$p=$_GET['p'];
    	}else {
    		$p=1;
    	}
    	$Page=new \Common\Model\PageModel();
    	$show= $Page->show($count,$per);// 分页显示输出
    	$this->assign('page',$show);
    	 
    	$list = $User->where($where)->page($p.','.$per)->order('uid desc')->select();
    	$this->assign('list',$list);
        $this->display();
    }
}
?>