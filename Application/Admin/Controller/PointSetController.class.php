<?php
namespace Admin\Controller;
use Admin\Common\Controller\AuthController;
class PointSetController extends AuthController
{
    //参数设置
    public function set()
    {
    	if($_POST) {
    		layout(false);
    		//每个积分价值金额
    		$point_value=I('post.point_value');
    		$old_point_value=I('post.old_point_value');
    		
    		//注册赠送积分
    		$point_register=I('post.point_register');
    		//必须为整数
    		if(is_natural_num($point_register)===false) {
    			$this->error('注册赠送积分必须为不小于零的整数！');
    		}
    		$old_point_register=I('post.old_point_register');
    		//推荐注册赠送积分
    		$point_recommend_register=I('post.point_recommend_register');
    		//必须为整数
    		if(is_natural_num($point_recommend_register)===false) {
    			$this->error('推荐注册赠送积分必须为不小于零的整数！');
    		}
    		$old_point_recommend_register=I('post.old_point_recommend_register');
    		
    		//签到奖励类型，1积分 2余额 3成长值
    		$sign_award_type=I('post.sign_award_type');
    		$old_sign_award_type=I('post.old_sign_award_type');
    		//签到奖励模式，1固定 2连续
    		$sign_award_model=I('post.sign_award_model');
    		$old_sign_award_model=I('post.old_sign_award_model');
    		//固定签到奖励数值
    		$sign_award_fixed_num=I('post.sign_award_fixed_num');
    		$old_sign_award_fixed_num=I('post.old_sign_award_fixed_num');
    		//连续签到奖励数值-第1天
    		$sign_award_continuous_num1=I('post.sign_award_continuous_num1');
    		$old_sign_award_continuous_num1=I('post.old_sign_award_continuous_num1');
    		//连续签到奖励数值-第2天
    		$sign_award_continuous_num2=I('post.sign_award_continuous_num2');
    		$old_sign_award_continuous_num2=I('post.old_sign_award_continuous_num2');
    		//连续签到奖励数值-第3天
    		$sign_award_continuous_num3=I('post.sign_award_continuous_num3');
    		$old_sign_award_continuous_num3=I('post.old_sign_award_continuous_num3');
    		//连续签到奖励数值-第4天
    		$sign_award_continuous_num4=I('post.sign_award_continuous_num4');
    		$old_sign_award_continuous_num4=I('post.old_sign_award_continuous_num4');
    		//连续签到奖励数值-第5天
    		$sign_award_continuous_num5=I('post.sign_award_continuous_num5');
    		$old_sign_award_continuous_num5=I('post.old_sign_award_continuous_num5');
    		//连续签到奖励数值-第6天
    		$sign_award_continuous_num6=I('post.sign_award_continuous_num6');
    		$old_sign_award_continuous_num6=I('post.old_sign_award_continuous_num6');
    		//连续签到奖励数值-第7天
    		$sign_award_continuous_num7=I('post.sign_award_continuous_num7');
    		$old_sign_award_continuous_num7=I('post.old_sign_award_continuous_num7');
    		
    		//载入系统配置文件
    		$str=file_get_contents('./Public/inc/point.config.php');
    		//每个积分价值金额
    		$fs_point_value="define('POINT_VALUE','$old_point_value');";
    		$rs_point_value="define('POINT_VALUE','$point_value');";
    		$str=str_replace($fs_point_value,$rs_point_value,$str);
    		
    		//替换注册赠送积分
    		$fs_point_register="define('POINT_REGISTER','$old_point_register');";
    		$rs_point_register="define('POINT_REGISTER','$point_register');";
    		$str=str_replace($fs_point_register,$rs_point_register,$str);
    		//替换推荐注册赠送积分
    		$fs_point_recommend_register="define('POINT_RECOMMEND_REGISTER','$old_point_recommend_register');";
    		$rs_point_recommend_register="define('POINT_RECOMMEND_REGISTER','$point_recommend_register');";
    		$str=str_replace($fs_point_recommend_register,$rs_point_recommend_register,$str);
    		//替换签到奖励类型
    		$fs_sign_award_type="define('SIGN_AWARD_TYPE','$old_sign_award_type');";
    		$rs_sign_award_type="define('SIGN_AWARD_TYPE','$sign_award_type');";
    		$str=str_replace($fs_sign_award_type,$rs_sign_award_type,$str);
    		//替换签到奖励模式
    		$fs_sign_award_model="define('SIGN_AWARD_MODEL','$old_sign_award_model');";
    		$rs_sign_award_model="define('SIGN_AWARD_MODEL','$sign_award_model');";
    		$str=str_replace($fs_sign_award_model,$rs_sign_award_model,$str);
    		//替换固定签到奖励数值
    		$fs_sign_award_fixed_num="define('SIGN_AWARD_FIXED_NUM','$old_sign_award_fixed_num');";
    		$rs_sign_award_fixed_num="define('SIGN_AWARD_FIXED_NUM','$sign_award_fixed_num');";
    		$str=str_replace($fs_sign_award_fixed_num,$rs_sign_award_fixed_num,$str);
    		//替换连续签到奖励数值-第1天
    		$fs_sign_award_continuous_num1="define('SIGN_AWARD_CONTINUOUS_NUM1','$old_sign_award_continuous_num1');";
    		$rs_sign_award_continuous_num1="define('SIGN_AWARD_CONTINUOUS_NUM1','$sign_award_continuous_num1');";
    		$str=str_replace($fs_sign_award_continuous_num1,$rs_sign_award_continuous_num1,$str);
    		//替换连续签到奖励数值-第2天
    		$fs_sign_award_continuous_num2="define('SIGN_AWARD_CONTINUOUS_NUM2','$old_sign_award_continuous_num2');";
    		$rs_sign_award_continuous_num2="define('SIGN_AWARD_CONTINUOUS_NUM2','$sign_award_continuous_num2');";
    		$str=str_replace($fs_sign_award_continuous_num2,$rs_sign_award_continuous_num2,$str);
    		//替换连续签到奖励数值-第3天
    		$fs_sign_award_continuous_num3="define('SIGN_AWARD_CONTINUOUS_NUM3','$old_sign_award_continuous_num3');";
    		$rs_sign_award_continuous_num3="define('SIGN_AWARD_CONTINUOUS_NUM3','$sign_award_continuous_num3');";
    		$str=str_replace($fs_sign_award_continuous_num3,$rs_sign_award_continuous_num3,$str);
    		//替换连续签到奖励数值-第4天
    		$fs_sign_award_continuous_num4="define('SIGN_AWARD_CONTINUOUS_NUM4','$old_sign_award_continuous_num4');";
    		$rs_sign_award_continuous_num4="define('SIGN_AWARD_CONTINUOUS_NUM4','$sign_award_continuous_num4');";
    		$str=str_replace($fs_sign_award_continuous_num4,$rs_sign_award_continuous_num4,$str);
    		//替换连续签到奖励数值-第5天
    		$fs_sign_award_continuous_num5="define('SIGN_AWARD_CONTINUOUS_NUM5','$old_sign_award_continuous_num5');";
    		$rs_sign_award_continuous_num5="define('SIGN_AWARD_CONTINUOUS_NUM5','$sign_award_continuous_num5');";
    		$str=str_replace($fs_sign_award_continuous_num5,$rs_sign_award_continuous_num5,$str);
    		//替换连续签到奖励数值-第6天
    		$fs_sign_award_continuous_num6="define('SIGN_AWARD_CONTINUOUS_NUM6','$old_sign_award_continuous_num6');";
    		$rs_sign_award_continuous_num6="define('SIGN_AWARD_CONTINUOUS_NUM6','$sign_award_continuous_num6');";
    		$str=str_replace($fs_sign_award_continuous_num6,$rs_sign_award_continuous_num6,$str);
    		//替换连续签到奖励数值-第7天
    		$fs_sign_award_continuous_num7="define('SIGN_AWARD_CONTINUOUS_NUM7','$old_sign_award_continuous_num7');";
    		$rs_sign_award_continuous_num7="define('SIGN_AWARD_CONTINUOUS_NUM7','$sign_award_continuous_num7');";
    		$str=str_replace($fs_sign_award_continuous_num7,$rs_sign_award_continuous_num7,$str);
    		
    		//写入配置文件
    		file_put_contents('./Public/inc/point.config.php',$str);
    		$this->success('更新成功！');
    	}else {
    	    //获取配置文件
    	    require_once './Public/inc/point.config.php';
    	    $msg=array(
    	        'point_value'=>POINT_VALUE,//每个积分价值金额
    	        'point_register'=>POINT_REGISTER,//注册赠送积分
    	        'point_recommend_register'=>POINT_RECOMMEND_REGISTER,//推荐注册赠送积分
    	        'sign_award_type'=>SIGN_AWARD_TYPE,//签到奖励类型，1积分 2余额 3成长值
    	        'sign_award_model'=>SIGN_AWARD_MODEL,//签到奖励模式，1固定 2连续
    	        'sign_award_fixed_num'=>SIGN_AWARD_FIXED_NUM,//固定签到奖励数值
    	        'sign_award_continuous_num1'=>SIGN_AWARD_CONTINUOUS_NUM1,//连续签到奖励数值-第1天
    	        'sign_award_continuous_num2'=>SIGN_AWARD_CONTINUOUS_NUM2,//连续签到奖励数值-第2天
    	        'sign_award_continuous_num3'=>SIGN_AWARD_CONTINUOUS_NUM3,//连续签到奖励数值-第3天
    	        'sign_award_continuous_num4'=>SIGN_AWARD_CONTINUOUS_NUM4,//连续签到奖励数值-第4天
    	        'sign_award_continuous_num5'=>SIGN_AWARD_CONTINUOUS_NUM5,//连续签到奖励数值-第5天
    	        'sign_award_continuous_num6'=>SIGN_AWARD_CONTINUOUS_NUM6,//连续签到奖励数值-第6天
    	        'sign_award_continuous_num7'=>SIGN_AWARD_CONTINUOUS_NUM7,//连续签到奖励数值-第7天
    	    );
    	    $this->assign('msg',$msg);
    	    
    		$this->display();
    	}
    }
    
    //完善资料
    public function infoSet()
    {
        if($_POST) {
            layout(false);
            
            //完善资料奖励类型，1积分 2余额 3成长值
            $task_info_award_type=I('post.task_info_award_type');
            $old_task_info_award_type=I('post.old_task_info_award_type');
            //完善资料奖励数值
            $task_info_award_num=I('post.task_info_award_num');
            $old_task_info_award_num=I('post.old_task_info_award_num');
            
            //载入系统配置文件
            $str=file_get_contents('./Public/inc/point.config.php');
            //完善资料奖励类型
            $fs_task_info_award_type="define('TASK_INFO_AWARD_TYPE','$old_task_info_award_type');";
            $rs_task_info_award_type="define('TASK_INFO_AWARD_TYPE','$task_info_award_type');";
            $str=str_replace($fs_task_info_award_type,$rs_task_info_award_type,$str);
            //完善资料奖励数值
            $fs_task_info_award_num="define('TASK_INFO_AWARD_NUM','$old_task_info_award_num');";
            $rs_task_info_award_num="define('TASK_INFO_AWARD_NUM','$task_info_award_num');";
            $str=str_replace($fs_task_info_award_num,$rs_task_info_award_num,$str);
            
            //写入配置文件
            file_put_contents('./Public/inc/point.config.php',$str);
            $this->success('更新成功！');
        }else {
            //获取配置文件
            require_once './Public/inc/point.config.php';
            $msg=array(
                'task_info_award_type'=>TASK_INFO_AWARD_TYPE,//完善资料奖励类型，1积分 2余额 3成长值
                'task_info_award_num'=>TASK_INFO_AWARD_NUM,//完善资料奖励数值
            );
            $this->assign('msg',$msg);
            
            $this->display();
        }
    }
}