<?php
/**
 * 应聘申请管理
 */
namespace Common\Model;
use Think\Model;

class JobEmploymentModel extends Model
{
	//验证规则
	protected $_validate =array(
			array('job_id','require','应聘岗位不能为空！',self::EXISTS_VALIDATE),  //存在验证，必填
			array('job_id','is_positive_int','应聘岗位不存在',self::EXISTS_VALIDATE,'function'),  //存在验证，必须是正整数
			array('truename','require','姓名不能为空！',self::EXISTS_VALIDATE),  //存在验证，必填
			array('truename','1,30','姓名不超过30个字符！',self::EXISTS_VALIDATE,'length'),  //存在验证，不超过30个字符
			array('phone','require','电话不能为空！',self::EXISTS_VALIDATE),  //存在验证，必填
			array('phone','1,30','电话不超过30个字符！',self::EXISTS_VALIDATE,'length'),  //存在验证，不超过30个字符
			array('address','1,100','住址不超过100个字符！',self::VALUE_VALIDATE,'length'),  //值不为空的时候验证，不超过100个字符
			array('salary','1,20','期望薪资不超过20个字符！',self::VALUE_VALIDATE,'length'),  //值不为空的时候验证，不超过20个字符
			array('sex',array('1','2','3'),'请选择性别！',self::VALUE_VALIDATE,'in'),  //值不为空的时候验证，只能是1男 2女 3保密		
			array('age','1,20','年龄不超过20个字符！',self::VALUE_VALIDATE,'length'),  //值不为空的时候验证，不超过20个字符
			array('nationality','1,30','名族不超过30个字符！',self::VALUE_VALIDATE,'length'),  //值不为空的时候验证，不超过30个字符
			array('school','1,30','毕业学校不超过30个字符！',self::VALUE_VALIDATE,'length'),  //值不为空的时候验证，不超过30个字符
			array('education','1,30','学历不超过30个字符！',self::VALUE_VALIDATE,'length'),  //值不为空的时候验证，不超过30个字符
			array('major','1,30','专业不超过30个字符！',self::VALUE_VALIDATE,'length'),  //值不为空的时候验证，不超过30个字符
			array('speciality','1,300','特长不超过300个字符！',self::VALUE_VALIDATE,'length'),  //值不为空的时候验证，不超过300个字符
			array('job_intension','1,100','求职意向不超过100个字符！',self::VALUE_VALIDATE,'length'),  //值不为空的时候验证，不超过100个字符
			array('skill','1,300','个人技能不超过300个字符！',self::VALUE_VALIDATE,'length'),  //值不为空的时候验证，不超过300个字符
			array('exp','1,300','学习及工作经历不超过300个字符！',self::VALUE_VALIDATE,'length'),  //值不为空的时候验证，不超过300个字符
			array('self_assessment','1,300','自我评价不超过300个字符！',self::VALUE_VALIDATE,'length'),  //值不为空的时候验证，不超过300个字符
			array('file','1,255','简历文件路径不正确！',self::VALUE_VALIDATE,'length'),  //值不为空的时候验证，不超过255个字符
			array('apply_time','require','应聘时间不能为空！',self::EXISTS_VALIDATE),  //存在验证，必填
			array('apply_time','is_datetime','应聘时间格式不正确！',self::EXISTS_VALIDATE,'function'),  //存在验证，必须是正确的时间格式
	);
	
	/**
	 * 获取岗位投递简历数
	 * @param int $job_id:工作岗位ID
	 * @return number|boolean
	 */
	public function getApplyNum($job_id)
	{
		$num=$this->where("job_id='$job_id'")->count();
		if($num!==false)
		{
			return $num;
		}else {
			return false;
		}
	}
	
	/**
	 * 获取简历信息
	 * @param int $job_employment_id:投递简历ID
	 * @return array|boolean
	 */
	public function getEmploymentMsg($job_employment_id)
	{
		$msg=$this->where("job_employment_id='$job_employment_id'")->find();
		if($msg!==false)
		{
			return $msg;
		}else {
			return false;
		}
	}
	
	/**
	 * 获取简历详情
	 * @param int $job_employment_id:投递简历ID
	 * @return array|boolean
	 */
	public function getEmploymentDetail($job_employment_id)
	{
		$msg=$this->getEmploymentMsg($job_employment_id);
		if($msg!==false)
		{
			//岗位名称
			$Job=new \Common\Model\JobModel();
			$JobMsg=$Job->getJobMsg($msg['job_id']);
			$msg['job_name']=$JobMsg['job_name'];
			return $msg;
		}else {
			return false;
		}
	}
}