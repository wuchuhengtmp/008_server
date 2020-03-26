<?php
/**
 * 会员授权码管理
 */
namespace Admin\Controller;
use Admin\Common\Controller\AuthController;
class UserAuthCodeController extends AuthController
{
	public function index()
	{
		$where='1';
		if(I('get.auth_code'))
		{
			$auth_code=I('get.auth_code');
			$where.=" and auth_code='$auth_code'";
		}
		 
		$UserAuthCode=new \Common\Model\UserAuthCodeModel();
		$count=$UserAuthCode->where($where)->count();
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

		$list = $UserAuthCode->where($where)->page($p.','.$per)->select();
		$this->assign('list',$list);
		$this->display();
	}
	
	//添加授权码
	public function add()
	{
		if(I('post.'))
		{
			layout(false);
			if(trim(I('post.auth_code')))
			{
				$auth_code=trim(I('post.auth_code'));
				//判断授权码是否存在
				$UserAuthCode=new \Common\Model\UserAuthCodeModel();
				$res_exist=$UserAuthCode->where("auth_code='$auth_code'")->find();
				if($res_exist)
				{
					$this->error('该授权码已存在，请勿重复添加！');
				}else {
					$data=array(
							'auth_code'=>$auth_code
					);
					if(!$UserAuthCode->create($data))
					{
						$this->error($UserAuthCode->getError());
					}else {
						$res=$UserAuthCode->add($data);
						if($res!==false)
						{
							$this->success('添加授权码成功！',U('index'));
						}else {
							$this->error('添加授权码失败！');
						}
					}
				}
			}else {
				$this->error('授权码不能为空！');
			}
		}else {
			$this->display();
		}
	}
	
	//编辑授权码
	public function edit($id)
	{
		//获取授权码信息
		$UserAuthCode=new \Common\Model\UserAuthCodeModel();
		$msg=$UserAuthCode->getMsg($id);
		$this->assign('msg',$msg);
		
		if(I('post.'))
		{
			layout(false);
			if(trim(I('post.auth_code')))
			{
				$auth_code=trim(I('post.auth_code'));
				//判断授权码是否存在
				$res_exist=$UserAuthCode->where("auth_code='$auth_code' and id!='$id'")->find();
				if($res_exist)
				{
					$this->error('该授权码已存在，请勿重复添加！');
				}else {
					$data=array(
							'auth_code'=>$auth_code
					);
					if(!$UserAuthCode->create($data))
					{
						$this->error($UserAuthCode->getError());
					}else {
						$res=$UserAuthCode->where("id='$id'")->save($data);
						if($res!==false)
						{
							$this->success('编辑授权码成功！',U('index'));
						}else {
							$this->error('编辑授权码失败！');
						}
					}
				}
			}else {
				$this->error('授权码不能为空！');
			}
		}else {
			$this->display();
		}
	}
	
	//删除授权码
	public function del($id)
	{
		//判断授权码是否已被使用
		$UserAuthCode=new \Common\Model\UserAuthCodeModel();
		$res_used=$UserAuthCode->getMsg($id);
		if($res_used)
		{
			if($res_used['is_used']=='Y')
			{
				//对不起，该授权码已被使用，不准删除！
				echo '2';
			}else {
				$res_del=$UserAuthCode->where("id='$id'")->delete();
				if($res_del!==false)
				{
					echo '1';
				}else {
					echo '0';
				}
			}
		}else {
			echo '0';
		}
	}
}
?>