<?php
/**
 * 管理员管理
 */
namespace Admin\Controller;
use Admin\Common\Controller\AuthController;
class AdminController extends AuthController 
{
    public function index()
    {
    	if(I('get.search'))
    	{
    		$search=I('get.search');
    		$where="adminname='$search' or email='$search' or phone='$search'";
    	}
    	if(I('get.group_id'))
    	{
    		$group_id=I('get.group_id');
    		$where="group_id='$group_id'";
    	}
    	if(I('get.group_name'))
    	{
    		$group_name=I('get.group_name');
    		$res_ag=D('AdminGroup')->where("title='$group_name'")->field('id')->find();
    		$group_id=$res_ag['id'];
    		$where="group_id='$group_id'";
    	}
    	if(I('get.search')=='' and I('get.group_id')=='' and I('get.group_name')=='')
    	{
    		$where='1';
    	}
    	$Admin=new \Admin\Model\AdminModel();
    	$count=$Admin->where($where)->count();
    	$per = 15;
    	if($_GET['p'])
    	{
    		$p=$_GET['p'];
    	}else {
    		$p=1;
    	}
    	$Page= new \Think\Page($count,$per);// 实例化分页类 传入总记录数和每页显示的记录数(25)
    	$Page->rollPage=10; // 分页栏每页显示的页数
    	$Page -> setConfig('header','共%TOTAL_ROW%条');
    	$Page -> setConfig('first','首页');
    	$Page -> setConfig('last','共%TOTAL_PAGE%页');
    	$Page -> setConfig('prev','上一页');
    	$Page -> setConfig('next','下一页');
    	$Page -> setConfig('link','indexpagenumb');//pagenumb 会替换成页码
    	$Page -> setConfig('theme','%FIRST% %UP_PAGE% %LINK_PAGE% %DOWN_PAGE% %END% 第 '.I('p',1).' 页/共 %TOTAL_PAGE% 页 (<font color="red">'.$per.'</font> 条/页 共 %TOTAL_ROW% 条)');
    	$show= $Page->show();// 分页显示输出
    	 
    	$adminlist = $Admin->where($where)->page($p.','.$per)->select();
    	$this->assign('alist',$adminlist);// 赋值数据集
    	$this->assign('page',$show);
        $this->display();
    }
    
    //新增管理员
    public function add()
    {
    	//获取管理员组列表
    	$group=new \Admin\Model\AdminGroupModel();
    	$grouplist=$group->getGroupList2();
    	$this->assign('glist',$grouplist);
    	if(I('post.'))
    	{
    		if(I('post.adminname'))
    		{
    			$adminname=I('post.adminname');
    			$Admin=new \Admin\Model\AdminModel();
    			$res=$Admin->where("adminname='$adminname'")->find();
    			if($res)
    			{
    				$str='X该用户名已存在';
    				echo $str;
    				exit();
    			}else {
    				$str='';
    				$a='a';
    			}
    		}
    		
    		if(I('post.password') and I('post.password2'))
    		{
    			$password=I('post.password');
    			$password2=I('post.password2');
    			if (strlen($password2)<=5)
    			{
    				$str='X密码不少于6位';
    				echo $str;
    				exit();
    			}else {
    				if($password!=$password2)
    				{
    					$str='X两次密码不相同';
    					echo $str;
    					exit();
    				}else {
    					$str='';
    					$a.='a';
    				}
    			}
    		}
    		
    		if(I('post.email'))
    		{
    			$email=I('post.email');
    			if(is_email($email))
    			{
    				$str='';
    			}else {
    				$str='X邮箱格式不正确';
    				echo $str;
    				exit();
    			}
    		}
    		
    		if(I('post.phone'))
    		{
    			$phone=I('post.phone');
    			if(is_phone($phone))
    			{
    				$str='';
    			}else {
    				$str='X手机号码格式不正确';
    				echo $str;
    				exit();
    			}
    		}
    		
    		if($a=='aa')
    		{
    			$adminname=I('post.adminname');
    			$password2=I('post.password2');
    			//MD5加密
    			$pwd=$Admin->encrypt($password2);
    			$email=I('post.email');
    			$phone=I('post.phone');
    			$group_id=I('post.group_id');
    			$status=I('post.status');
    			$register_time=date('Y-m-d H:i:s');
    			//php获取ip的算法
    			$ip = ($_SERVER["HTTP_VIA"]) ? $_SERVER["HTTP_X_FORWARDED_FOR"] : $_SERVER["REMOTE_ADDR"];
    			$ip = ($ip) ? $ip : $_SERVER["REMOTE_ADDR"];
    			$data=array(
    					'adminname'=>$adminname,
    					'password'=>$pwd,
    					'email'=>$email,
    					'phone'=>$phone,
    					'province'=>trim(I('post.province')),
    					'city'=>trim(I('post.city')),
    					'group_id'=>$group_id,
    					'status'=>$status,
    					'register_time'=>$register_time,
    					'register_ip'=>$ip,
    					'last_login_time'=>date('Y-m-d H:i:s')
    			);
    			$res=$Admin->add($data);
    			if($res)
    			{
    				echo '1';
    			}else {
    				echo '0';
    			}
    		}
    	}else {
    		$this->display();
    	}
    }
    
    //编辑管理员
    public function edit($uid)
    {
    	//获取管理员信息
    	$Admin=new \Admin\Model\AdminModel();
    	$aMsg=$Admin->getAdminMsg($uid);
    	$this->assign('msg',$aMsg);
    	//获取管理员组列表
    	$group=D('admin_group');
    	$grouplist=$group->getGroupList2();
    	$this->assign('glist',$grouplist);
    	if(I('post.'))
    	{
    		if(I('post.adminname'))
    		{
    			$adminname=I('post.adminname');
    			$res=$Admin->where("adminname='$adminname' and uid!='$uid'")->find();
    			if($res)
    			{
    				$error='X该用户名已存在';
    				$this->assign('error',$error);
    				$this->display();
    				exit();
    			}
    		}
    		
    		$email=I('post.email');
    		if($email)
    		{
    			if(is_email($email)!==true)
    			{
    				$error1='X邮箱格式不正确';
    				$this->assign('error1',$error1);
    				$this->display();
    				exit();
    			}
    		}
    		
    		$phone=I('post.phone');
    		if($phone)
    		{
    			if(is_phone($phone)!==true)
    			{
    				$error2='X手机号码格式不正确';
    				$this->assign('error2',$error2);
    				$this->display();
    				exit();
    			}
    		}
    		$group_id=I('post.group_id');
    		$login_num=I('post.login_num');
    		$status=I('post.status');
    		
    		$data=array(
    				'adminname'=>$adminname,
    				'email'=>$email,
    				'phone'=>$phone,
    				'province'=>trim(I('post.province')),
    				'city'=>trim(I('post.city')),
    				'group_id'=>$group_id,
    				'login_num'=>$login_num,
    				'status'=>$status,
    		);
    		if(I('post.password'))
    		{
    			$password=I('post.password');
    			//MD5加密
    			$pwd=$Admin->encrypt($password);
    			$data['password']=$pwd;
    		}
    		$res=$Admin->where("uid=$uid")->save($data);
    		if($res!==false)
    		{
    			layout(false);
    			$this->success('编辑管理员成功！',U('index'),3);
    		}else {
    			layout(false);
    			$this->error('操作失败！');
    		}
    	}else {
    		$this->display();
    	}
    }
    
    //修改管理员禁用状态
    public function changestatus($id,$status)
    {
    	$data=array(
    			'status'=>$status
    	);
    	$Admin=new \Admin\Model\AdminModel();
    	$res=$Admin->where("uid=$id")->save($data);
    	if($res===false)
    	{
    		echo '0';
    	}else {
    		echo '1';
    	}
    }
    
    //删除管理员
    public function del($id)
    {
    	$Admin=new \Admin\Model\AdminModel();
    	$res=$Admin->where("uid=$id")->delete();
    	if($res===false)
    	{
    		echo '0';
    	}else {
    		echo '1';
    	}
    }
    
    //修改密码
    public function changepwd()
    {
    	if($_SESSION['admin_id']!='')
    	{
    		$admin_id=$_SESSION['admin_id'];
    		if(I('post.'))
    		{
    			$oldpwd=I('post.oldpwd');
    			if($oldpwd=='')
    			{
    				$this->assign('error1','原密码不能为空！');
    				$this->display();
    				exit();
    			}
    			$pwd1=I('post.pwd1');
    			$pwd2=I('post.pwd2');
    			if($pwd1=='')
    			{
    				$this->assign('error2','新密码不能为空！');
    				$this->display();
    				exit();
    			}
    			if($pwd2=='')
    			{
    				$this->assign('error3','重复密码不能为空！');
    				$this->display();
    				exit();
    			}
    			if($pwd1==$pwd2)
    			{
    				if(strlen($pwd2)>5)
    				{
    					//验证原密码是否正确
    					$Admin=new \Admin\Model\AdminModel();
    					$res=$Admin->checkPwd($admin_id,$oldpwd);
    					if($res)
    					{
    						//修改密码
    						$newpwd=$Admin->encrypt($pwd2);
    						$data=array(
    								'password'=>$newpwd
    						);
    						$res2=$Admin->where("uid=$admin_id")->save($data);
    						layout(false);
    						if($res2===false)
    						{
    							$this->error('修改密码失败！');
    						}else {
    							$this->success('编辑密码成功！');
    						}
    					}else {
    						$this->assign('error1','原密码错误！');
    						$this->display();
    						exit();
    					}
    				}else {
    					$this->assign('error3','新密码长度不少于5位！');
    					$this->display();
    					exit();
    				}
    			}else {
    				$this->assign('error3','两次密码不相同！');
    				$this->display();
    				exit();
    			}
    		}else {
    			$this->display();
    		}
    	}else {
    		$this->redirect('Index/index');
    	}
    }
}