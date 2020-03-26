<?php
/**
 * 代理商系统
 */
namespace Admin\Controller;
use Admin\Common\Controller\AuthController;
class AgentController extends AuthController
{
    public function index()
    {
    	//获取用户组列表
    	$UserGroup=new \Common\Model\UserGroupModel();
    	$glist=$UserGroup->getGroupList();
    	$this->assign('glist',$glist);
    	
    	//获取代理商所属区域
    	$agent_id=$_SESSION['admin_id'];
    	$Admin=new \Admin\Model\AdminModel();
    	$adminMsg=$Admin->where("uid='$agent_id'")->find();
    	$province=$adminMsg['province'];
    	$province=str_replace('省', ' ', $province);
    	$province=str_replace('市', ' ', $province);
    	$city=$adminMsg['city'];
    	$city=str_replace('市', ' ', $city);
    	
    	if($province and $city)
    	{
    		$where="phone_province='$province' and phone_city='$city'";
    		if(I('get.group_id'))
    		{
    			$group_id=I('get.group_id');
    			$this->assign('group_id',$group_id);
    			$where.=" and group_id='$group_id'";
    		}
    		 
    		if(trim(I('get.search')))
    		{
    			$search=I('get.search');
    			$where.=" and (username='$search' or email='$search' or phone='$search')";
    		}
    		 
    		$User=new \Common\Model\UserModel();
    		$count=$User->where($where)->count();
    		$per = 30;
    		if($_GET['p'])
    		{
    			$p=$_GET['p'];
    		}else {
    			$p=1;
    		}
    		$Page=new \Common\Model\PageModel();
    		$show= $Page->show($count,$per);// 分页显示输出
    		$this->assign('page',$show);
    		
    		$list = $User->where($where)->page($p.','.$per)->select();
    		$this->assign('list',$list);
    		$this->display();
    	}else {
    		layout(false);
    		$this->error('代理商没有设置地区！');
    	}
    }
}
?>