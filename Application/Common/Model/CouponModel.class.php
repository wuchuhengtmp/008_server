<?php
/**
 * 优惠券管理
 */
namespace Common\Model;
use Think\Model;

class CouponModel extends Model
{
	//验证规则
	protected $_validate =array(
			array('name','require','优惠券名称不能为空！',self::EXISTS_VALIDATE),  //存在验证，必填
			array('name','1,50','优惠券名称不超过50个字符！',self::EXISTS_VALIDATE,'length'),  //存在验证，不超过50个字符
			array('type','require','请选择正确的优惠券类型！',self::EXISTS_VALIDATE),  //存在验证，必填
			array('type',array(1,5),'请选择正确的优惠券类型！',self::EXISTS_VALIDATE,'between'),  //存在验证，只能是1-5
			array('money','currency','优惠券面额不是正确的货币格式！',self::VALUE_VALIDATE),  //值不为空的时候验证 ，必须是货币格式
			array('condition','currency','使用条件不是正确的货币格式！',self::VALUE_VALIDATE),  //值不为空的时候验证 ，必须是货币格式
			array('createnum','is_natural_num','发放数量必须为不小于零的整数！',self::VALUE_VALIDATE,'function'),  //值不为空的时候验证 ，必须是自然数
			array('send_num','is_natural_num','优惠券已领取数量必须为不小于零的整数！',self::VALUE_VALIDATE,'function'),  //值不为空的时候验证 ，必须是自然数
			array('use_num','is_natural_num','优惠券已使用数量必须为不小于零的整数！',self::VALUE_VALIDATE,'function'),  //值不为空的时候验证 ，必须是自然数
			array('send_start_time','require','优惠券开始发放时间不能为空！',self::EXISTS_VALIDATE),  //存在验证，必填
			array('send_start_time','is_datetime','优惠券开始发放时间格式不正确！',self::EXISTS_VALIDATE,'function'),  //存在验证，必须是正确的时间格式
			array('send_end_time','require','优惠券结束发放时间不能为空！',self::EXISTS_VALIDATE),  //存在验证，必填
			array('send_end_time','is_datetime','优惠券结束发放时间格式不正确！',self::EXISTS_VALIDATE,'function'),  //存在验证，必须是正确的时间格式
			array('use_start_time','require','优惠券使用开始时间不能为空！',self::EXISTS_VALIDATE),  //存在验证，必填
			array('use_start_time','is_datetime','优惠券使用开始时间格式不正确！',self::EXISTS_VALIDATE,'function'),  //存在验证，必须是正确的时间格式
			array('use_end_time','require','优惠券使用结束时间不能为空！',self::EXISTS_VALIDATE),  //存在验证，必填
			array('use_end_time','is_datetime','优惠券使用结束时间格式不正确！',self::EXISTS_VALIDATE,'function'),  //存在验证，必须是正确的时间格式
			array('add_time','require','优惠券创建时间不能为空！',self::EXISTS_VALIDATE),  //存在验证，必填
			array('add_time','is_datetime','优惠券创建时间格式不正确！',self::EXISTS_VALIDATE,'function'),  //存在验证，必须是正确的时间格式
	);
}
?>