<?php
/**
 * 管理员组管理
 */
namespace Admin\Controller;
use Admin\Common\Controller\AuthController;
class AdminGroupController extends AuthController 
{
    public function index()
    {
    	//获取管理员组列表
    	$group=new \Admin\Model\AdminGroupModel();
    	$grouplist=$group->getGroupList();
    	$this->assign('glist',$grouplist);
        $this->display();
    }
    
    //新增管理员组
    public function addgroup()
    {
    	//获取权限列表
    	$auth_rule=new \Admin\Model\AuthRuleModel();
    	$rule_arr=$auth_rule->getRuleList();
    	$this->assign('rlist',$rule_arr);
    	if(I('post.'))
    	{
    		layout(false);
    		$title=I('post.title');
    		if($title=='')
    		{
    			$this->assign('error1','管理员组名不能为空！');
    			$this->display();
    			exit();
    		}
    		$introduce=I('post.introduce');
    		$status=I('post.status');
    		$rule_array=I('post.rules');
    		$rules = implode(',', array_values($rule_array));
    		if($rules=='')
    		{
    			$this->error('管理员组拥有权限不能为空！');
    		}
    		$create_time=date('Y-m-d H:i:s');
    		$data=array(
    				'title'=>$title,
    				'introduce'=>$introduce,
    				'status'=>$status,
    				'rules'=>$rules,
    				'create_time'=>$create_time
    		);
    		$group=new \Admin\Model\AdminGroupModel();
    		$res=$group->add($data);
    		if ($res)
    		{
    			$this->success('新增管理员组成功！',U('index'),3);
    		}else {
    			$this->error('操作失败！');
    		}
    	}else {
    		$this->display();
    	}
    }
    
    //编辑管理员组
    public function editgroup($group_id)
    {
    	//获取管理员组信息
    	$group=new \Admin\Model\AdminGroupModel();
    	$gMsg=$group->getGroupMsg($group_id);
    	$this->assign('msg',$gMsg);
    	//获取权限列表
    	$auth_rule=new \Admin\Model\AuthRuleModel();
    	$rule_arr=$auth_rule->getRuleList();
    	$this->assign('rlist',$rule_arr);
    	if(I('post.'))
    	{
    		layout(false);
    		$title=I('post.title');
    		if($title=='')
    		{
    			$this->assign('error1','管理员组名不能为空！');
    			$this->display();
    			exit();
    		}
    		$introduce=I('post.introduce');
    		$status=I('post.status');
    		$rule_array=I('post.rules');
    		$rules = implode(',', array_values($rule_array));
    		$data=array(
    				'title'=>$title,
    				'introduce'=>$introduce,
    				'status'=>$status,
    				'rules'=>$rules
    		);
    		$res=$group->where("id='$group_id'")->save($data);
    		if($res===false)
    		{
    			$this->error('操作失败!');
    		}else {
    			$this->success('编辑成功!',U('index'),3);
    		}
    	}else {
    		$this->display();
    	}
    }
    
    //修改分组状态
    public function changestatus($id,$status)
    {
    	$data=array(
    			'status'=>$status
    	);
    	$group=new \Admin\Model\AdminGroupModel();
    	$res=$group->where("id=$id")->save($data);
    	if($res===false)
    	{
    		echo '0';
    	}else {
    		echo '1';
    	}
    }
    
    //删除分组
    public function delgroup($id)
    {
    	$group=new \Admin\Model\AdminGroupModel();
    	$res=$group->where("id=$id")->delete();
    	if($res===false)
    	{
    		echo '0';
    	}else {
    		//删除分组下的所有会员
    		$Admin=new \Admin\Model\AdminModel();
    		$res_a=$Admin->where("group_id=$id")->delete();
    		if($res_a!==false)
    		{
    			echo '1';
    		}else {
    			echo '0';
    		}
    	}
    }
}