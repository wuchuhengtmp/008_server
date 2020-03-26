<?php
/**
 * 商品分类管理接口
 */
namespace App\Controller;
use App\Common\Controller\AuthController;

class GoodsCatController extends AuthController 
{
	/**
	 * 获取顶级商品分类列表
	 * @return array
	 * @return @param code:返回码
	 * @return @param msg:返回码说明
	 * @return @param data:返回数据
	 * @return @param data->list:顶级商品分类列表
	 */
	public function getParentCatList()
	{
		$GoodsCat=new \Common\Model\GoodsCatModel();
		$list=$GoodsCat->where("parent_id=0 and is_show='Y'")->field('cat_id,cat_name,keywords,description,img')->order('is_top desc,sort desc,cat_id asc')->select();
		if($list!==false)
		{
			//成功
			$data=array(
					'list'=>$list
			);
			$res=array(
					'code'=>$this->ERROR_CODE_COMMON['SUCCESS'],
					'msg'=>'成功',
					'data'=>$data
			);
		}else {
			//数据库错误
			$res=array(
					'code'=>$this->ERROR_CODE_COMMON['DB_ERROR'],
					'msg'=>$this->ERROR_CODE_COMMON_ZH[$this->ERROR_CODE_COMMON['DB_ERROR']]
			);
		}
		echo json_encode ($res,JSON_UNESCAPED_UNICODE);
	}
	
	/**
	 * 获取推荐商品分类列表
	 * @return array
	 * @return @param code:返回码
	 * @return @param msg:返回码说明
	 * @return @param data:返回数据
	 * @return @param data->list:推荐商品分类列表
	 */
	public function getTopCatList()
	{
		$GoodsCat=new \Common\Model\GoodsCatModel();
		$list=$GoodsCat->where("is_top='Y' and is_show='Y'")->field('cat_id,cat_name,keywords,description,img')->order('sort desc,cat_id asc')->select();
		if($list!==false)
		{
			//成功
			$data=array(
					'list'=>$list
			);
			$res=array(
					'code'=>$this->ERROR_CODE_COMMON['SUCCESS'],
					'msg'=>'成功',
					'data'=>$data
			);
		}else {
			//数据库错误
			$res=array(
					'code'=>$this->ERROR_CODE_COMMON['DB_ERROR'],
					'msg'=>$this->ERROR_CODE_COMMON_ZH[$this->ERROR_CODE_COMMON['DB_ERROR']]
			);
		}
		echo json_encode ($res,JSON_UNESCAPED_UNICODE);
	}
	
	/**
	 * 获取子商品分类列表
	 * @param int $pid:父级商品分类ID
	 * @return array
	 * @return @param code:返回码
	 * @return @param msg:返回码说明
	 * @return @param data:返回数据
	 * @return @param data->list:子商品分类列表
	 */
	public function getSubCatList()
	{
		if(trim(I('post.pid')))
		{
			$pid=trim(I('post.pid'));
			$GoodsCat=new \Common\Model\GoodsCatModel();
			$list=$GoodsCat->where("parent_id=$pid and is_show='Y'")->field('cat_id,cat_name,keywords,description,img')->order('is_top desc,sort desc,cat_id asc')->select();
			if($list!==false)
			{
				//成功
				$data=array(
						'list'=>$list
				);
				$res=array(
						'code'=>$this->ERROR_CODE_COMMON['SUCCESS'],
						'msg'=>'成功',
						'data'=>$data
				);
			}else {
				//数据库错误
				$res=array(
						'code'=>$this->ERROR_CODE_COMMON['DB_ERROR'],
						'msg'=>$this->ERROR_CODE_COMMON_ZH[$this->ERROR_CODE_COMMON['DB_ERROR']]
				);
			}
		}else {
			//参数不正确，参数缺失
			$res=array(
					'code'=>$this->ERROR_CODE_COMMON['PARAMETER_ERROR'],
					'msg'=>$this->ERROR_CODE_COMMON_ZH[$this->ERROR_CODE_COMMON['PARAMETER_ERROR']]
			);
		}
		echo json_encode ($res,JSON_UNESCAPED_UNICODE);
	}
	
	/**
	 * 获取所有商品分类列表
	 * @return array
	 * @return @param code:返回码
	 * @return @param msg:返回码说明
	 * @return @param data:返回数据
	 * @return @param data->list:所有商品分类列表
	 * @return @param data->list->sublist:子商品分类列表
	 */
	public function getAllCatList()
	{
		//获取顶级分类列表
		$GoodsCat=new \Common\Model\GoodsCatModel();
		$list=$GoodsCat->where("parent_id=0 and is_show='Y'")->field('cat_id,cat_name,keywords,description,img')->order('is_top desc,sort desc,cat_id asc')->select();
		if($list!==false)
		{
			//获取子分类列表
			$num=count($list);
			for($i=0;$i<$num;$i++)
			{
				$subcat=$GoodsCat->getSubCatList($list[$i]['cat_id']);
				$sublist=array();
				foreach ($subcat as $sl)
				{
					if($sl['is_show']=='Y')
					{
						$sublist[]=array(
								'cat_id'=>$sl['cat_id'],
								'cat_name'=>$sl['cat_name'],
								'keywords'=>$sl['keywords'],
								'description'=>$sl['description'],
								'img'=>$sl['img'],
						);
					}
				}
				$list[$i]['sublist']=$sublist;
			}
			//成功
			$data=array(
					'list'=>$list
			);
			$res=array(
					'code'=>$this->ERROR_CODE_COMMON['SUCCESS'],
					'msg'=>'成功',
					'data'=>$data
			);
		}else {
			//数据库错误
			$res=array(
					'code'=>$this->ERROR_CODE_COMMON['DB_ERROR'],
					'msg'=>$this->ERROR_CODE_COMMON_ZH[$this->ERROR_CODE_COMMON['DB_ERROR']]
			);
		}
		echo json_encode ($res,JSON_UNESCAPED_UNICODE);
	}
}
?>