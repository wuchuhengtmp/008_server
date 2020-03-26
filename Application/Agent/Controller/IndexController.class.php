<?php
namespace Agent\Controller;
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
        if(I('post.adminuser') and I('post.adminpwd')) {
            $adminuser=I('post.adminuser');
            $adminpwd=I('post.adminpwd');
            
            //记住账号
            $remember=I('post.remember');
            if(!empty($remember)) {
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
            if($res==false) {
                $this->assign('error','验证码不正确！');
                $this->display('index');
                exit();
            }
            $User=new \Common\Model\UserModel();
            $userMsg=$User->where("username='$adminuser' or phone='$adminuser'")->find();
            if($userMsg['uid']) {
                if($userMsg['is_freeze']=='Y') {
                    $this->assign('error','该账号已被禁用！');
                    $this->display('index');
                    exit();
                }else {
                    if($userMsg['is_agent']=='N') {
                        $this->assign('error','对不起，您不是代理商！');
                        $this->display('index');
                        exit();
                    }else {
                        $password=$userMsg['password'];
                        //MD5加密
                        $pwd=$User->encrypt($adminpwd);
                        if($password!=$pwd) {
                            $this->assign('error','账号或密码错误！');
                            $this->display('index');
                            exit();
                        }else {
                            //保存用户SESSION
                            $_SESSION['agent_id']=$userMsg['uid'];
                            //跳转页面
                            $this->redirect('System/index');
                        }
                    }
                }
            }else {
                $this->assign('error','代理商账号错误！');
                $this->display('index');
            }
        }else {
            $this->assign('error','账号、密码不能为空！');
            $this->display('index');
        }
    }
    
    //修改密码
    public function changePwd()
    {
        if($_SESSION['agent_id']!='') {
            $agent_id=$_SESSION['agent_id'];
            if($_POST) {
                $oldpwd=I('post.oldpwd');
                if($oldpwd=='') {
                    $this->assign('error1','原密码不能为空！');
                    $this->display();
                    exit();
                }
                $pwd1=I('post.pwd1');
                $pwd2=I('post.pwd2');
                if($pwd1=='') {
                    $this->assign('error2','新密码不能为空！');
                    $this->display();
                    exit();
                }
                if($pwd2=='') {
                    $this->assign('error3','重复密码不能为空！');
                    $this->display();
                    exit();
                }
                if($pwd1==$pwd2) {
                    if(strlen($pwd2)>5) {
                        //验证原密码是否正确
                        $User=new \Common\Model\UserModel();
                        $res=$User->checkPwd($agent_id,$oldpwd);
                        if($res) {
                            //修改密码
                            $newpwd=$User->encrypt($pwd2);
                            $data=array(
                                'password'=>$newpwd
                            );
                            $res2=$User->where("uid=$agent_id")->save($data);
                            layout(false);
                            if($res2===false) {
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
    
    //退出登录
    public function loginout()
    {
        $_SESSION['agent_id']=null;
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