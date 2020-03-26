<?php
/**
 * 商城系统-品牌商管理
 */
namespace Common\Model;
use Think\Model;

class BrandModel extends Model
{
	//验证规则
	protected $_validate =array(
			array('name','require','品牌名称不能为空！',self::EXISTS_VALIDATE),  //存在验证，必填
			array('name','1,50','品牌名称不超过50个字符！',self::EXISTS_VALIDATE,'length'),  //存在验证，不超过50个字符
			array('logo','1,255','品牌logo图片路径不正确！',self::VALUE_VALIDATE,'length'),  //值不为空的时候验证 ，不超过255个字符
			array('url','url','不是正确的网址格式！',self::VALUE_VALIDATE),  //值不为空的时候验证 ，URL地址格式验证
			array('contractor','1,50','联系人不超过50个字符！',self::VALUE_VALIDATE,'length'),  //值不为空的时候验证，不超过50个字符
			array('phone','1,50','联系方式不超过50个字符！',self::VALUE_VALIDATE,'length'),  //值不为空的时候验证，不超过50个字符
			array('address','1,100','公司地址不超过100个字符！',self::VALUE_VALIDATE,'length'),  //值不为空的时候验证，不超过100个字符
			array('sort','is_natural_num','排序必须为不小于零的整数！',self::VALUE_VALIDATE,'function'),  //值不为空的时候验证 ，必须是自然数
			array('is_show','require','请选择是否显示！',self::EXISTS_VALIDATE),  //存在验证，必填
			array('is_show',array('Y','N'),'请选择是否显示！',self::EXISTS_VALIDATE,'in'),  //存在验证，只能是Y是 N否
	);
	
	/**
	 * 获取品牌商列表
	 * @param string $order:排序规则 desc降序 asc升序
	 * @param string $is_show:是否显示 Y显示 N不显示，默认显示全部
	 * @return array|boolean
	 */
	public function getBrandList($order='asc',$is_show='')
	{
		if($is_show)
		{
			$where="is_show='$is_show'";
		}else {
			$where='';
		}
		$list=$this->where($where)->order("sort desc,brand_id $order")->select();
		if($list!==false)
		{
			return $list;
		}else {
			return false;
		}
	}
	
	/**
	 * 获取品牌信息
	 * @param int $brand_id:品牌ID
	 * @return array
	 */
	public function getBrandMsg($brand_id)
	{
		$res=$this->where("brand_id=$brand_id")->find();
		if($res!==false)
		{
			return $res;
		}else {
			return false;
		}
	}
	
	/**
	 * 删除品牌
	 * 同步删除品牌的logo图片以及内容中的图片和文件
	 * @param int $brand_id:品牌ID
	 * @return boolean
	 */
	public function del($brand_id)
	{
		//获取品牌信息
		$res=$this->getBrandMsg($brand_id);
		//删除品牌的同时需要删除图片和文件
		$img=$res['logo'];
		$content=htmlspecialchars_decode(html_entity_decode($res['introduce']));
		$res2=$this->where("brand_id=$brand_id")->delete();
		if($res2)
		{
			if(!empty($img))
			{
				$img='.'.$img;
				unlink($img);
			}
			// 删除内容中的图片和文件
			if (! empty ( $content ))
			{
				$ueditor=new \Admin\Common\Controller\UeditorController;
				$ueditor->del($content);
			}
			return true;
		}else {
			return false;
		}
	}
}
?>