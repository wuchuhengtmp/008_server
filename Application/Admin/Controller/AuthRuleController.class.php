<?php
/**
 * 权限管理
 */
namespace Admin\Controller;
use Admin\Common\Controller\AuthController;
class AuthRuleController extends AuthController
{
    public function index()
    {
    	//获取权限列表
    	$auth_rule=new \Admin\Model\AuthRuleModel();
    	$rule_arr=$auth_rule->getRuleList();
    	$this->assign('admin_rule',$rule_arr);//权限列表
    	$this->display();
    }
    
    //添加权限规则
    public function addrule()
    {
    	layout(false);
    	if(I('post.title') and I('post.name'))
    	{
    		$data=array(
    				'name'=>trim(I('post.name')),
    				'title'=>trim(I('post.title')),
    				'status'=>I('post.status'),
    				'pid'=>I('post.pid'),
    				'sort'=>I('post.sort'),
    				'create_time'=>date('Y-m-d H:i:s')
    		);
    		$auth_rule=new \Admin\Model\AuthRuleModel();
    		$res=$auth_rule->add($data);
    		if ($res!==false)
    		{
    			$this->success('新增权限成功！',U('index'));
    		}else {
    			$this->error('操作失败！',U('index'));
    		}
    	}else {
    		$this->error('名称和控制器/方法不能为空！',U('index'));
    	}
    }
    
    //编辑权限规则
    public function editrule($rule_id)
    {
    	//获取权限规则记录
    	$auth_rule=new \Admin\Model\AuthRuleModel();
    	$ruleMsg=$auth_rule->getRuleMsg($rule_id);
    	$this->assign('msg',$ruleMsg);
    	//获取权限列表
    	$rule_arr=$auth_rule->getRuleList();
    	$this->assign('admin_rule',$rule_arr);//权限列表
    	if(I('post.'))
    	{
    		layout(false);
    		$title=I('post.title');
    		if($title=='')
    		{
    			$this->assign('error1','权限名称不能为空！');
    			$this->display();
    			exit();
    		}
    		$name=trim(I('post.name'));
    		if($name=='')
    		{
    			$this->assign('error2','控制器/方法不能为空！');
    			$this->display();
    			exit();
    		}
    		$pid=I('post.pid');
    		$data=array(
    				'name'=>$name,
    				'title'=>$title,
    				'status'=>I('post.status'),
    				'sort'=>I('post.sort'),
    				'pid'=>$pid
    		);
    		$res=$auth_rule->where("id=$rule_id")->save($data); // 根据条件保存修改的数据
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
    
    //修改权限状态
    public function changestatus($id,$status)
    {
    	$data=array(
    			'status'=>$status
    	);
    	$auth_rule=new \Admin\Model\AuthRuleModel();
    	$res=$auth_rule->where("id=$id")->save($data);
    	if($res===false)
    	{
    		echo '0';
    	}else {
    		echo '1';
    	}
    }
    
    //删除权限规则
    public function delrule($id)
    {
    	$auth_rule=new \Admin\Model\AuthRuleModel();
    	$res=$auth_rule->where("id=$id")->delete();
    	if($res===false)
    	{
    		echo '0';
    	}else {
    		echo '1';
    	}
    }
    
    //批量修改排序
    public function changesort()
    {
    	layout(false);
    	$sort_array=I('post.sort');
    	$ids = implode(',', array_keys($sort_array));
    	$sql = "UPDATE __PREFIX__auth_rule SET sort = CASE id ";
    	foreach ($sort_array as $id => $sort) {
    		$sql .= sprintf("WHEN %d THEN %d ", $id, $sort);
    	}
    	$sql.= "END WHERE id IN ($ids)";
    	$res = M()->execute($sql);
    	if($res===false)
    	{
    		$this->error('操作失败!');
    	}else {
    		$this->success('排序成功!',U('index'),3);
    	}
    }
}