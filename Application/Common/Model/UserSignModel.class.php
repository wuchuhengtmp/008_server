<?php
/**
 * 用户签到管理
 */
namespace Common\Model;
use Think\Model;

class UserSignModel extends Model
{
	//验证规则
	protected $_validate =array(
			array('user_id','require','会员不能为空！',self::EXISTS_VALIDATE),  //存在验证，必填
			array('user_id','is_positive_int','会员不存在',self::EXISTS_VALIDATE,'function'),  //存在验证，必须是正整数
			array('sign_date','require','签到日期不能为空！',self::EXISTS_VALIDATE),  //存在验证，必填
			array('sign_date','is_date','签到日期不正确！',self::EXISTS_VALIDATE,'function'),  //存在验证，必须是正确的日期格式
			array('sign_time','require','签到时间不能为空！',self::EXISTS_VALIDATE),  //存在验证，必填
			array('sign_time','is_datetime','签到时间格式不正确！',self::EXISTS_VALIDATE,'function'),  //存在验证，必须是正确的时间格式
			//array('point','is_natural_num','签到获取积分数必须为大于零的整数！',self::EXISTS_VALIDATE,'function'),  //存在验证 ，必须是自然数
			array('continuous_day','is_positive_int','连续签到天数必须为大于零的整数！',self::EXISTS_VALIDATE,'function'),  //存在验证，必须是正整数	
	);
	
	/**
	 * 获取用户签到记录
	 * @param int $uid:用户ID
	 * @param int $page:页码，默认第1页
	 * @param int $per:每页条数，默认10条
	 * @return array|boolean
	 */
	public function getSignRecordByPage($uid,$page=1,$per=10)
	{
		$list=$this->where("user_id='$uid'")->page($page,$per)->order('id desc')->select();
		if($list!==false)
		{
		    $num=count($list);
		    for($i=0;$i<$num;$i++){
		        $list[$i]['point']*=1;
		    }
			return $list;
		}else {
			return false;
		}
	}
}
?>