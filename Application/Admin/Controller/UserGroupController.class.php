<?php
/**
 * 会员管理
 * 会员组管理
 */
namespace Admin\Controller;
use Admin\Common\Controller\AuthController;
class UserGroupController extends AuthController
{
    public function index()
    {
    	//获取用户组列表
    	$UserGroup=new \Common\Model\UserGroupModel();
    	$glist=$UserGroup->getGroupList();
    	$this->assign('glist',$glist);
        $this->display();
    }
    
    //新增会员组
    public function add()
    {
    	if(I('post.'))
    	{
    		layout(false);
    		//判断用户组是否重复
    		$UserGroup=new \Common\Model\UserGroupModel();
    		$title=trim(I('post.title'));
    		$res_exist=$UserGroup->where("title='$title'")->find();
    		if($res_exist)
    		{
    			$this->error('该会员组名已存在，不准重复！');
    		}else {
    			$data=array(
    					'title'=>trim(I('post.title')),
    					'exp'=>trim(I('post.exp')),
    					'discount'=>trim(I('post.discount')),
    					'introduce'=>trim(I('post.introduce')),
    					'is_freeze'=>I('post.is_freeze'),
    					'createtime'=>date('Y-m-d H:i:s'),
    					'fee_user'=>trim(I('post.fee_user')),
    					'fee_service'=>trim(I('post.fee_service')),
    					'fee_plantform'=>trim(I('post.fee_plantform')),
    					'self_rate'=>trim(I('post.self_rate')),
    					'referrer_rate'=>trim(I('post.referrer_rate')),
    					'referrer_rate2'=>trim(I('post.referrer_rate2'))
    			);
    			if(!$UserGroup->create($data))
    			{
    				// 如果创建失败 表示验证没有通过 输出错误提示信息
    				$this->error($UserGroup->getError());
    			}else {
    				// 验证成功
    				$res=$UserGroup->add($data);
    				if ($res!==false)
    				{
    					$this->success('新增用户组成功！',U('index'));
    				}else {
    					$this->error('操作失败！');
    				}
    			}
    		}
    	}else {
    		$this->display();
    	}
    }
    
    //编辑会员组
    public function edit($group_id)
    {
    	//获取用户组信息
    	$UserGroup=new \Common\Model\UserGroupModel();
    	$gMsg=$UserGroup->getGroupMsg($group_id);
    	$this->assign('msg',$gMsg);
    	if(I('post.'))
    	{
    		layout(false);
    		//判断用户组是否重复
    		$title=trim(I('post.title'));
    		$res_exist=$UserGroup->where("title='$title' and id!='$group_id'")->find();
    		if($res_exist)
    		{
    			$this->error('该会员组名已存在，不准重复！');
    		}else {
    			$data=array(
    					'title'=>trim(I('post.title')),
    					'exp'=>trim(I('post.exp')),
    					'discount'=>trim(I('post.discount')),
    					'introduce'=>trim(I('post.introduce')),
    					'is_freeze'=>I('post.is_freeze'),
    					'fee_user'=>trim(I('post.fee_user')),
    					'fee_service'=>trim(I('post.fee_service')),
    					'fee_plantform'=>trim(I('post.fee_plantform')),
                        'self_rate'=>trim(I('post.self_rate')),
                        'referrer_rate'=>trim(I('post.referrer_rate')),
                        'referrer_rate2'=>trim(I('post.referrer_rate2'))
    			);
    			if(!$UserGroup->create($data))
    			{
    				// 如果创建失败 表示验证没有通过 输出错误提示信息
    				$this->error($UserGroup->getError());
    			}else {
    				// 验证成功
    				$res=$UserGroup->where("id='$group_id'")->save($data);
    				if($res===false)
    				{
    					$this->error('操作失败!');
    				}else {
    					$this->success('编辑成功!',U('index'));
    				}
    			}
    		}
    	}else {
    		$this->display();
    	}
    }
    
    //修改分组状态
    public function changestatus($id,$status)
    {
    	$data=array(
    			'is_freeze'=>$status
    	);
    	$group=new \Common\Model\UserGroupModel();
    	if(!$group->create($data))
    	{
    		// 如果创建失败 表示验证没有通过 输出错误提示信息
    		// $this->error($group->getError());
    		echo '0';
    	}else {
    		// 验证成功
    		$res=$group->where("id=$id")->save($data);
    		if($res===false)
    		{
    			echo '0';
    		}else {
    			echo '1';
    		}
    	}
    }
    
    //删除会员组
    public function del($id)
    {
    	//先判断会员组下是否存在会员，存在不允许删除
    	$User=new \Common\Model\UserModel();
    	$user_num=$User->where("group_id='$id'")->count();
    	if($user_num>0)
    	{
    		echo '2';
    	}else {
    		//进行删除操作
    		$UserGroup=new \Common\Model\UserGroupModel();
    		$res=$UserGroup->where("id='$id'")->delete();
    		if($res!==false)
    		{
    			echo '1';
    		}else {
    			echo '0';
    		}
    	}
    }
}