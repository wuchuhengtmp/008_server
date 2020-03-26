<?php
/**
 * 投递简历管理
 */
namespace Admin\Controller;
use Admin\Common\Controller\AuthController;
class JobEmploymentController extends AuthController
{
	public function index($job_id)
	{
		//获取简历列表
		$where="job_id='$job_id'";
		$JobEmployment=new \Common\Model\JobEmploymentModel();
		$count=$JobEmployment->where($where)->count();
		$per = 15;
		if($_GET['p'])
		{
			$p=$_GET['p'];
		}else {
			$p=1;
		}
		// 分页显示输出
		$Page=new \Common\Model\PageModel();
		$show= $Page->show($count,$per);
		$this->assign('page',$show);
		
		$list = $JobEmployment->where($where)->page($p.','.$per)->order('job_employment_id desc')->select();
		$this->assign('list',$list);
			
		$this->display();
	}
	
	//查看简历
	public function edit($job_employment_id)
	{
		//获取简历详情
		$JobEmployment=new \Common\Model\JobEmploymentModel();
		$msg=$JobEmployment->getEmploymentDetail($job_employment_id);
		$this->assign('msg',$msg);
		
		$this->display();
	}
	
	//删除简历
	public function del($job_employment_id)
	{
		//获取简历详情
		$JobEmployment=new \Common\Model\JobEmploymentModel();
		$msg=$JobEmployment->getEmploymentMsg($job_employment_id);
		$res=$JobEmployment->where("job_employment_id='$job_employment_id'")->delete();
		if($res!==false)
		{
			if($msg['file'])
			{
				$file='.'.$msg['file'];
				@unlink($file);
			}
			echo '1';
		}else {
			echo '0';
		}
	}
}