<?php
/**
 * 招聘管理
 */
namespace Common\Model;
use Think\Model;

class JobModel extends Model
{
	//验证规则
	protected $_validate =array(
			array('job_name','require','岗位名称不能为空！',self::EXISTS_VALIDATE),  //存在验证，必填
			array('job_name','1,20','岗位名称不超过20个字符！',self::EXISTS_VALIDATE,'length'),  //存在验证，不超过20个字符
			array('salary','1,20','薪资不超过20个字符！',self::VALUE_VALIDATE,'length'),  //值不为空的时候验证，不超过20个字符
			array('address','1,100','工作地点不超过100个字符！',self::VALUE_VALIDATE,'length'),  //值不为空的时候验证，不超过100个字符
			array('education','1,20','学历要求不超过20个字符！',self::VALUE_VALIDATE,'length'),  //值不为空的时候验证，不超过20个字符
			array('major','1,30','专业不超过30个字符！',self::VALUE_VALIDATE,'length'),  //值不为空的时候验证，不超过30个字符	
			array('sex',array('1','2','3'),'请选择性别！',self::VALUE_VALIDATE,'in'),  //值不为空的时候验证，只能是1男 2女 3男女不限
			array('age','1,20','年龄不超过20个字符！',self::VALUE_VALIDATE,'length'),  //值不为空的时候验证，不超过20个字符
			array('is_full','require','请选择是否全职！',self::EXISTS_VALIDATE),  //存在验证，必填
			array('is_full',array('1','2'),'请选择是否全职！',self::EXISTS_VALIDATE,'in'),  //存在验证，只能是1全职 2兼职
			array('exp','1,20','工作经验不超过20个字符！',self::VALUE_VALIDATE,'length'),  //值不为空的时候验证，不超过20个字符
			array('mark_salary','1,200','福利-薪酬标签选择过多！',self::VALUE_VALIDATE,'length'),  //值不为空的时候验证，不超过200个字符
			array('mark_insurance','1,200','福利-保障标签选择过多！',self::VALUE_VALIDATE,'length'),  //值不为空的时候验证，不超过200个字符
			array('mark_holiday','1,200','福利-假期标签选择过多！',self::VALUE_VALIDATE,'length'),  //值不为空的时候验证，不超过200个字符
			array('mark_subsidy','1,200','福利-补贴活动标签选择过多！',self::VALUE_VALIDATE,'length'),  //值不为空的时候验证，不超过200个字符
			array('num','1,20','招聘人数不超过20个字符！',self::VALUE_VALIDATE,'length'),  //值不为空的时候验证，不超过20个字符
			array('deadline','is_date','招聘截止时间格式不正确！',self::VALUE_VALIDATE,'function'),  //值不为空的时候验证，必须是正确的日期格式
			array('pubtime','require','发布时间不能为空！',self::EXISTS_VALIDATE),  //存在验证，必填
			array('pubtime','is_datetime','发布时间格式不正确！',self::EXISTS_VALIDATE,'function'),  //存在验证，必须是正确的时间格式
	);
	
	/**
	 * 获取岗位列表
	 * @return array|boolean
	 */
	public function getJobList()
	{
		$list=$this->where(1)->select();
		if($list!==false)
		{
			return $list;
		}else {
			return false;
		}
	}
	
	/**
	 * 获取岗位信息
	 * @param int $job_id:岗位ID
	 * @return array|boolean
	 */
	public function getJobMsg($job_id)
	{
		$msg=$this->where("job_id='$job_id'")->find();
		if($msg!==false)
		{
			return $msg;
		}else {
			return false;
		}
	}
}