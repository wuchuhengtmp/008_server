<?php
/**
 * 用户账号管理接口
 */
namespace Wap\Controller;
use Think\Controller;

class UserAccountController extends Controller
{
	/**
	 * 注册
	 * @param string $phone:手机号码
	 * @param string $pwd1:密码
	 * @param string $pwd2:重复密码
	 * @param string $code:手机短信验证码
	 * @param string $referrer_phone:推荐人手机号
	 * @return array
	 * @return @param code:返回码
	 * @return @param msg:返回码说明
	 */
	public function register()
	{
	    $referrer_id=trim(I('request.referrer_id'));
	    if($referrer_id){
	        $User=new \Common\Model\UserModel();
	        $rMsg=$User->where("uid=$referrer_id")->field('auth_code')->find();
	        $auth_code=$rMsg['auth_code'];
	    }
	    
		if($_POST) {
			layout(false);
			if(trim(I('post.phone')) and trim(I('post.pwd1')) /* and trim(I('post.pwd2')) */ and trim(I('post.code')) )
			{
				$phone=trim(I('post.phone'));
				$code=trim(I('post.code'));
				$pwd1=trim(I('post.pwd1'));
				$pwd2=trim(I('post.pwd1'));
				/* $pwd2=trim(I('post.pwd2')); */
				$data=array(
						'phone'=>$phone,
						'code'=>$code,
						'pwd1'=>$pwd1,
						'pwd2'=>$pwd2,
						'referrer_id'=>$referrer_id
				);
				$url=WEB_URL."/app.php?c=UserAccount&a=register";
				$res_json=https_request($url,$data);
				$res=json_decode($res_json,true);
				if($res['code']==0)
				{
					//注册成功，跳转到下载页面
				    $this->success('恭喜您注册成功！',U('Index/down',array('inviteCode'=>$auth_code)));
				}else {
					$this->error($res['msg']);
				}
			}else {
				$this->error('手机号码、密码、短信验证码不能为空！');
			}
		}else {
			$this->assign('web_title','注册');
			$this->assign('web_keywords','');
			$this->assign('web_description','');
			
			$this->assign('referrer_id',$referrer_id);
			$this->assign('auth_code',$auth_code);
			
			$this->display();
		}
	}
}