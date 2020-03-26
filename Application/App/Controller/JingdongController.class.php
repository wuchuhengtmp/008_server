<?php
/**
 * 京东商品管理接口
 */
namespace App\Controller;
use App\Common\Controller\AuthController;

class JingdongController extends AuthController 
{
	/**
	 * 获取顶级京东商品分类列表
	 * @return array
	 * @return @param code:返回码
	 * @return @param msg:返回码说明
	 * @return @param data:返回数据
	 * @return @param data->list:顶级京东商品分类列表
	 */
	public function getCatList()
	{
		Vendor('JingDong.JingDong','','.class.php');
		$JindDong=new \JindDong();
		$res=$JindDong->searchGoodsCategory($parent_id=0,$grade=0);
		echo json_encode ($res,JSON_UNESCAPED_UNICODE);
	}
	
	/**
	 * 获取爆款商品列表
	 * @param string $token:用户身份令牌
	 * @param number $page:非必填，默认值1，商品分页数
	 * @param number $page_size:非必填，默认10，每页商品数量
	 * @param string $cid3:三级类目
	 * @return array
	 * @return @param code:返回码
	 * @return @param msg:返回码说明
	 * @return @param data:返回数据
	 * @return @param data->list:顶级京东商品分类列表
	 */
	public function getTopGoodsList()
	{
		//第几页
		if(trim(I('post.page')))
		{
			$page=trim(I('post.page'));
		}else {
			$page=1;
		}
		//页大小
		if(trim(I('post.page_size')))
		{
			$page_size=trim(I('post.page_size'));
		}else {
			$page_size=10;
		}
		Vendor('JingDong.JingDong','','.class.php');
		$JindDong=new \JindDong();
		$from=($page-1)*$page_size;
		$res=$JindDong->queryExplosiveGoods($from,$page_size,$cid3='');
		echo json_encode ($res,JSON_UNESCAPED_UNICODE);
	}
	
	/**
	 * 关键词查询选品
	 * @param string $token:用户身份令牌
	 * @param number $page:非必填，默认值1，商品分页数
	 * @param number $page_size:非必填，默认10，每页商品数量
	 * @param number $cat1Id:一级类目
	 * @param number $cat2Id:二级类目
	 * @param number $cat3Id:三级类目
	 * @param string $keyword:关键词
	 * @param string $sort_name:排序字段[pcPrice pc价],[pcCommission pc佣金],[pcCommissionShare pc佣金比例],[inOrderCount30Days 30天引入订单量],[inOrderComm30Days 30天支出佣金]
	 * @param string $sort:	asc,desc升降序,默认降序
	 * @return array
	 * @return @param code:返回码
	 * @return @param msg:返回码说明
	 * @return @param data:返回数据
	 * @return @param data->list:商品列表
	 */
	public function getGoodsList()
	{
		//第几页
		if(trim(I('post.page')))
		{
			$page=trim(I('post.page'));
		}else {
			$page=1;
		}
		//页大小
		if(trim(I('post.page_size')))
		{
			$page_size=trim(I('post.page_size'));
		}else {
			$page_size=10;
		}
		if(trim(I('post.cat1Id')))
		{
			$cat1Id=trim(I('post.cat1Id'));
		}
		if(trim(I('post.cat2Id')))
		{
			$cat2Id=trim(I('post.cat2Id'));
		}
		if(trim(I('post.cat3Id')))
		{
			$cat3Id=trim(I('post.cat3Id'));
		}
		//关键字
		if(trim(I('post.keyword')))
		{
			$keyword=trim(I('post.keyword'));
		}
		//排序规则
		if(trim(I('post.sort_name')))
		{
			$sort_name=trim(I('post.sort_name'));
		}
		if(trim(I('post.sort')))
		{
			$sort=trim(I('post.sort'));
		}else {
			$sort='desc';
		}
		Vendor('JingDong.JingDong','','.class.php');
		$JindDong=new \JindDong();
		$res=$JindDong->searchGoods($cat1Id,$cat2Id,$cat3Id,$keyword,$page,$page_size,$sort_name,$sort);
		echo json_encode ($res,JSON_UNESCAPED_UNICODE);
	}
}
?>