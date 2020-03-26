<?php
namespace Admin\Controller;
use Think\Controller;
class IndexController extends Controller
{
    public function index()
    {
    	layout(false);
        $this->display();
    }
    
    //登录
    public function loginin()
    {
    	layout(false);
    	if(I('post.adminuser') and I('post.adminpwd'))
    	{
    		$adminuser=I('post.adminuser');
    		$adminpwd=I('post.adminpwd');
    		
    		//记住账号
    		$remember=I('post.remember');
    		if(!empty($remember))
    		{
    			cookie('remember',$remember,3600*24*30);
    			cookie('loginname',$adminuser,3600*24*30);
    			cookie('loginpwd',$adminpwd,3600*24*30);
    		}else {
    			cookie('remember',null);
    			cookie('loginname',null);
    			cookie('loginpwd',null);
    		}
    		
    		$auth=I('post.auth');
    		$verify = new \Think\Verify();
    		$res=$verify->check($auth, '');
    		if($res==false)
    		{
    			$this->assign('error','验证码不正确！');
    			$this->display('index');
    			exit();
    		}
    		$admin=new \Admin\Model\AdminModel();
    		$res=$admin->where("adminname='$adminuser'")->find();
    		if($res)
    		{
    			$status=$res['status'];
    			if($status==0)
    			{
    				$this->assign('error','该管理员已被禁用！');
    				$this->display('index');
    				exit();
    			}else {
    				$password=$res['password'];
    				//MD5加密
    				$pwd=$admin->encrypt($adminpwd);
    				if($password!=$pwd)
    				{
    					$this->assign('error','用户名或密码错误！');
    					$this->display('index');
    					exit();
    				}else {
    					//判断管理员组是否被禁用
    					$group_id=$res['group_id'];
    					$AdminGroup=new \Admin\Model\AdminGroupModel();
    					$res_g=$AdminGroup->where("id=$group_id")->field('status')->find();
    					if($res_g['status']=='1')
    					{
    						//更新登录状态
    						$ip=getIP();
    						$login_num=$res['login_num']+1;
    						$data=array(
    								'last_login_time'=>date('Y-m-d H:i:s'),
    								'last_login_ip'=>$ip,
    								'login_num'=>$login_num
    						);
    						$res2=$admin->where("adminname='$adminuser'")->save($data);
    						if($res2)
    						{
    							//保存用户SESSION
    							$_SESSION['admin_id']=$res['uid'];
    							$_SESSION['a_group_id']=$res['group_id'];
    							//跳转页面
    							$this->redirect('System/index');
    						}else {
    							$this->assign('error','登录失败！');
    							$this->display('index');
    						}
    					}else {
    						$this->assign('error','您所在的管理员组已被禁用！');
    						$this->display('index');
    						exit();
    					}
    				}
    			}
    		}else {
    			$this->assign('error','该管理员不存在！');
    			$this->display('index');
    		}
    	}else {
    		$this->assign('error','账号、密码不能为空！');
    		$this->display('index');
    	}
    }
    
    //退出登录
    public function loginout()
    {
    	$_SESSION['admin_id']=null;
    	$_SESSION['a_group_id']=null;
    	//跳转页面
    	$this->redirect('Index/index');
    }
    
    //生成验证码
    public function verify()
    {
    	ob_end_clean();
    	$config =	array(
    			'expire'    =>  1800,            // 验证码过期时间（s）
    			'useImgBg'  =>  false,           // 使用背景图片
    			'fontSize'  =>  10,              // 验证码字体大小(px)
    			'useCurve'  =>  false,            // 是否画混淆曲线
    			'useNoise'  =>  false,            // 是否添加杂点
    			'imageH'    =>  30,               // 验证码图片高度
    			'imageW'    =>  80,               // 验证码图片宽度
    			'length'    =>  4,               // 验证码位数
    			'fontttf'   =>  '5.ttf',              // 验证码字体，不设置随机获取
    			'bg'        =>  array(243, 251, 254),  // 背景颜色
    	);
    	$verify=new \Think\Verify($config);
    	/**
    	 * 输出验证码并把验证码的值保存的session中
    	 * 验证码保存到session的格式为： array('verify_code' => '验证码值', 'verify_time' => '验证码创建时间');
    	* */
    	$verify->entry();
    }
}