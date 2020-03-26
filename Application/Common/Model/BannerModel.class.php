<?php
/**
 * Banner/广告图管理
 */
namespace Common\Model;
use Think\Model;

class BannerModel extends Model
{
	//验证规则
	protected $_validate =array(
			array('cat_id','require','分类名称不能为空！',self::EXISTS_VALIDATE),  //存在验证，必填
			array('cat_id','is_positive_int','请选择正确的分类',self::EXISTS_VALIDATE,'function'),  //存在验证，必须是正整数
			array('title','require','名称不能为空！',self::EXISTS_VALIDATE),  //存在验证，必填
			array('title','1,200','名称不超过200个字符！',self::EXISTS_VALIDATE,'length'),  //存在验证，不超过200个字符
			array('img','1,100','图片路径不正确！',self::VALUE_VALIDATE,'length'),  //值不为空的时候验证 ，不超过100个字符
			array('href','url','不是正确的网址格式！',self::VALUE_VALIDATE),  //值不为空的时候验证 ，URL地址格式验证
			array('sort','is_natural_num','排序必须为不小于零的整数！',self::VALUE_VALIDATE,'function'),  //值不为空的时候验证 ，必须是自然数
			array('createtime','require','创建时间不能为空！',self::EXISTS_VALIDATE),  //存在验证，必填
			array('createtime','is_datetime','创建时间格式不正确！',self::EXISTS_VALIDATE,'function'),  //存在验证，必须是正确的时间格式
	);
	
	/**
	 * 获取广告图类型描述
	 * @param int $type:广告图类型
	 * @return string 广告图类型描述
	 */
	public function getTypeZh($type)
	{
	    switch ($type){
	        case '1':
	            $type_str='网页';
	            break;
	        case '2':
	            $type_str='淘宝';
	            break;
	        case '3':
	            $type_str='京东';
	            break;
	        case '4':
	            $type_str='拼多多';
	            break;
	        case '5':
	            $type_str='支付宝';
	            break;
	        case '6':
	            $type_str='淘宝年货节';
	            break;
	        case '7':
	            $type_str='春节红包';
	            break;
	        case '8':
	            $type_str='新人红包';
	            break;
	        case '9':
	            $type_str='淘宝商品';
	            break;
	        case '10':
	            $type_str='拉新活动';
	            break;
	        case '11':
	            $type_str='0元购';
	            break;
	        default:
	            $type_str='';
	            break;
	    }
	    return $type_str;
	}
	
	/**
	 * 获取广告图列表
	 * @param int $cat_id:分类ID
	 * @param string $is_show:是否显示 Y显示 N不显示
	 * @param int $agent_id:代理商ID
	 * @return array|boolean
	 */
	public function getBannerList($cat_id,$is_show='',$agent_id=0)
	{
	    $where="cat_id=$cat_id";
	    if ($is_show) {
	        $where.=" and is_show='$is_show'";
	    }
	    $where.=" and agent_id=$agent_id";
	    $res=$this->where($where)->order('sort desc')->select();
	    if($res!==false)
	    {
	        return $res;
	    }else {
	        return false;
	    }
	}
	
	/**
	 * 获取广告图信息
	 * @param int $id:Banner/广告图ID
	 * @return array|boolean
	 */
	public function getBannerMsg($id)
	{
		$res=$this->where("id=$id")->find();
		if($res!==false)
		{
			return $res;
		}else {
			return false;
		}
	}
}