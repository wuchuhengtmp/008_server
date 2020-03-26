<?php
/**
 * 招聘管理
 */
namespace Home\Controller;
use Think\Controller;
class JobController extends Controller
{
	function _empty()
	{
		header('HTTP/1.1 404 Not Found');
		header('Status:404 Not Found');
		$this->display ( 'Public:404' );
	}

	//构造函数
	function _initialize()
	{
		//调用客服样式
		$this->assign('qq_file','Qq:qq'.QQ_CSS);
	}
	
	//招聘岗位列表
	public function index()
	{
		$this->assign('web_title','岗位列表');
		$this->assign('web_keywords','岗位列表,招聘管理');
		$this->assign('web_description','');
		
		//内页banner图
		$Banner=new \Common\Model\BannerModel();
		$bannerMsg=$Banner->getBannerMsg(4);
		$this->assign('bannerMsg',$bannerMsg);
		
		$where='1';
		$Job=new \Common\Model\JobModel();
		$count=$Job->where($where)->count();
		$per = 8;
		if($_GET['p'])
		{
			$p=$_GET['p'];
		}else {
			$p=1;
		}
		$Page=new \Common\Model\PageModel();
		$show= $Page->show($count,$per);// 分页显示输出
		$this->assign('page',$show);
		 
		$list = $Job->where($where)->page($p.','.$per)->order('job_id desc')->select();
		$this->assign('list',$list);
		 
		$this->display();
	}
	
	//岗位详情
	public function jobview($job_id)
	{
		//内页banner图
		$Banner=new \Common\Model\BannerModel();
		$bannerMsg=$Banner->getBannerMsg(4);
		$this->assign('bannerMsg',$bannerMsg);
		
		//获取岗位详情
		$Job=new \Common\Model\JobModel();
		$msg=$Job->getJobMsg($job_id);
		$this->assign('msg',$msg);
		
		$web_title=$msg['job_name'].'-岗位详情';
		$web_keywords=$msg['job_name'].',岗位详情,招聘管理';
		$this->assign('web_title',$web_title);
		$this->assign('web_keywords',$web_keywords);
		$this->assign('web_description','');
		
		$this->display();
	}
	
	//投递简历
	public function employment($job_id)
	{
		if(I('post.'))
		{
			layout(false);
			if(trim(I('post.truename')) and trim(I('post.phone')) and trim(I('post.nationality')) and trim(I('post.age')) and trim(I('post.education')) and trim(I('post.school')) and trim(I('post.salary')))
			{
				//上传简历文件
				if(!empty($_FILES['file']['name']))
				{
					$config = array(
							'mimes'         =>  array(), //允许上传的文件MiMe类型
							'maxSize'       =>  1024*1024*10, //上传的文件大小限制 (0-不做限制)
							'rootPath'      =>  './Public/Upload/Job/', //保存根路径
							'savePath'      =>  '', //保存路径
							'saveExt'       =>  '', //文件保存后缀，空则使用原后缀
					);
					$upload = new \Think\Upload($config);
					// 上传单个文件
					$info = $upload->uploadOne($_FILES['file']);
					if(!$info) {
						// 上传错误提示错误信息
						$this->error($upload->getError());
					}else{
						// 上传成功
						// 文件完成路径
						$filepath=$config['rootPath'].$info['savepath'].$info['savename'];
						$file=substr($filepath,1);
					}
				}else {
					$file='';
				}
				$data=array(
						'job_id'=>$job_id,
						'truename'=>trim(I('post.truename')),
						'phone'=>trim(I('post.phone')),
						'address'=>trim(I('post.address')),
						'salary'=>trim(I('post.salary')),
						'sex'=>trim(I('post.sex')),
						'age'=>trim(I('post.age')),
						'nationality'=>trim(I('post.nationality')),
						'school'=>trim(I('post.school')),
						'education'=>trim(I('post.education')),
						'major'=>trim(I('post.major')),
						'speciality'=>trim(I('post.speciality')),
						'job_intension'=>trim(I('post.job_intension')),
						'skill'=>trim(I('post.skill')),
						'exp'=>trim(I('post.exp')),
						'self_assessment'=>trim(I('post.self_assessment')),
						'file'=>$file,
						'apply_time'=>date('Y-m-d H:i:s')
				);
				$JobEmployment=new \Common\Model\JobEmploymentModel();
				if(!$JobEmployment->create($data))
				{
					//验证不通过
					//删除已上传文件
					if($filepath)
					{
						@unlink($filepath);
					}
					$this->error($JobEmployment->getError());
				}else {
					//验证通过
					$res=$JobEmployment->add($data);
					if($res!==false)
					{
						$this->success('填写简历成功！',U('index'));
					}else {
						//删除已上传文件
						if($filepath)
						{
							@unlink($filepath);
						}
						$this->error('填写简历失败！');
					}
				}
			}else {
				$this->error('姓名、电话、民族、年龄、学历、毕业学校、目标薪资不能为空！');
			}
		}else {
			//内页banner图
			$Banner=new \Common\Model\BannerModel();
			$bannerMsg=$Banner->getBannerMsg(4);
			$this->assign('bannerMsg',$bannerMsg);
			
			//获取岗位详情
			$Job=new \Common\Model\JobModel();
			$msg=$Job->getJobMsg($job_id);
			$this->assign('msg',$msg);
			
			$web_title='投递简历-'.$msg['job_name'];
			$web_keywords=$msg['job_name'].',投递简历,招聘管理';
			$this->assign('web_title',$web_title);
			$this->assign('web_keywords',$web_keywords);
			$this->assign('web_description','');
			
			$this->display();
		}
	}
}