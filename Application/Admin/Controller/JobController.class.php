<?php
/**
 * 岗位管理
 */
namespace Admin\Controller;
use Admin\Common\Controller\AuthController;
class JobController extends AuthController
{
	public function index()
	{
		//获取岗位列表
		$where='1';
		if(I('get.job_name'))
		{
			$job_name=trim(I('get.job_name'));
			$where.=" and job_name like '%$job_name%'";
		}
		$Job=new \Common\Model\JobModel();
		$count=$Job->where($where)->count();
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
		
		$list = $Job->where($where)->page($p.','.$per)->order('job_id desc')->select();
		$this->assign('list',$list);
		 
		$this->display();
	}
	
	//添加岗位
	public function add()
	{
		if(I('post.'))
		{
			layout(false);
			if(I('post.job_name'))
			{
				//福利-薪酬
				if(I('post.mark_salary'))
				{
					$mark_salary=implode(',', I('post.mark_salary'));
				}else {
					$mark_salary='';
				}
				//福利-保障
				if(I('post.mark_insurance'))
				{
					$mark_insurance=implode(',', I('post.mark_insurance'));
				}else {
					$mark_insurance='';
				}
				//福利-假期
				if(I('post.mark_holiday'))
				{
					$mark_holiday=implode(',', I('post.mark_holiday'));
				}else {
					$mark_holiday='';
				}
				//福利-补贴活动
				if(I('post.mark_subsidy'))
				{
					$mark_subsidy=implode(',', I('post.mark_subsidy'));
				}else {
					$mark_subsidy='';
				}
				
				$data=array(
						'job_name'=>trim(I('post.job_name')),
						'salary'=>trim(I('post.salary')),
						'address'=>trim(I('post.address')),
						'education'=>trim(I('post.education')),
						'major'=>trim(I('post.major')),
						'sex'=>I('post.sex'),
						'age'=>trim(I('post.age')),
						'is_full'=>trim(I('post.is_full')),
						'exp'=>trim(I('post.exp')),
						'mark_salary'=>$mark_salary,
						'mark_insurance'=>$mark_insurance,
						'mark_holiday'=>$mark_holiday,
						'mark_subsidy'=>$mark_subsidy,
						'duty'=>trim(I('post.duty')),
						'requirement'=>trim(I('post.requirement')),
						'contact'=>trim(I('post.contact')),
						'num'=>trim(I('post.num')),
						'deadline'=>trim(I('post.deadline')),
						'pubtime'=>date('Y-m-d H:i:s'),
				);
				$Job=new \Common\Model\JobModel();
				if(!$Job->create($data))
				{
					//验证不通过
					$this->error($Job->getError());
				}else {
					//验证通过
					$res=$Job->add($data);
					if($res!==false)
					{
						$this->success('添加岗位成功！',U('index'));
					}else {
						$this->error('添加岗位失败！');
					}
				}
			}else {
				$this->error('岗位名称不能为空！');
			}
		}else {
			$this->display();
		}
	}
	
	//编辑岗位
	public function edit($job_id)
	{
		//获取岗位信息
		$Job=new \Common\Model\JobModel();
		$msg=$Job->getJobMsg($job_id);
		$this->assign('msg',$msg);
		
		if(I('post.'))
		{
			layout(false);
			if(I('post.job_name'))
			{
				//福利-薪酬
				if(I('post.mark_salary'))
				{
					$mark_salary=implode(',', I('post.mark_salary'));
				}else {
					$mark_salary='';
				}
				//福利-保障
				if(I('post.mark_insurance'))
				{
					$mark_insurance=implode(',', I('post.mark_insurance'));
				}else {
					$mark_insurance='';
				}
				//福利-假期
				if(I('post.mark_holiday'))
				{
					$mark_holiday=implode(',', I('post.mark_holiday'));
				}else {
					$mark_holiday='';
				}
				//福利-补贴活动
				if(I('post.mark_subsidy'))
				{
					$mark_subsidy=implode(',', I('post.mark_subsidy'));
				}else {
					$mark_subsidy='';
				}
	
				$data=array(
						'job_name'=>trim(I('post.job_name')),
						'salary'=>trim(I('post.salary')),
						'address'=>trim(I('post.address')),
						'education'=>trim(I('post.education')),
						'major'=>trim(I('post.major')),
						'sex'=>I('post.sex'),
						'age'=>trim(I('post.age')),
						'is_full'=>trim(I('post.is_full')),
						'exp'=>trim(I('post.exp')),
						'mark_salary'=>$mark_salary,
						'mark_insurance'=>$mark_insurance,
						'mark_holiday'=>$mark_holiday,
						'mark_subsidy'=>$mark_subsidy,
						'duty'=>trim(I('post.duty')),
						'requirement'=>trim(I('post.requirement')),
						'contact'=>trim(I('post.contact')),
						'num'=>trim(I('post.num')),
						'deadline'=>trim(I('post.deadline')),
						'pubtime'=>date('Y-m-d H:i:s'),
				);
				if(!$Job->create($data))
				{
					//验证不通过
					$this->error($Job->getError());
				}else {
					//验证通过
					$res=$Job->where("job_id='$job_id'")->save($data);
					if($res!==false)
					{
						$this->success('编辑岗位成功！',U('index'));
					}else {
						$this->error('编辑岗位失败！');
					}
				}
			}else {
				$this->error('岗位名称不能为空！');
			}
		}else {
			$this->display();
		}
	}
	
	//删除岗位
	public function del($job_id)
	{
		//判断岗位下是否有投递简历，存在不准直接删除
		$JobEmployment=new \Common\Model\JobEmploymentModel();
		$res_exist=$JobEmployment->getApplyNum($job_id);
		if($res_exist>0)
		{
			echo '2';
		}else {
			$Job=new \Common\Model\JobModel();
			$res=$Job->where("job_id='$job_id'")->delete();
			if($res!==false)
			{
				echo '1';
			}else {
				echo '0';
			}
		}
	}
}