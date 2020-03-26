<?php
/**
 * 京东商品分类管理接口
 */
namespace App\Controller;
use App\Common\Controller\AuthController;

class JingdongCatController extends AuthController 
{
	/**
	 * 获取顶级京东商品分类列表
	 * @return array
	 * @return @param code:返回码
	 * @return @param msg:返回码说明
	 * @return @param data:返回数据
	 * @return @param data->list:顶级京东商品分类列表
	 */
	public function getTopCatList()
	{
		$JingdongCat=new \Common\Model\JingdongCatModel();
		$list=$JingdongCat->getParentList('Y');
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
	 * 获取子级京东商品分类列表
	 * @param int $pid:父级京东商品分类ID
	 * @return array
	 * @return @param code:返回码
	 * @return @param msg:返回码说明
	 * @return @param data:返回数据
	 * @return @param data->list:子级京东商品分类列表
	 */
	public function getSubListByParent()
	{
		if(trim(I('post.pid')))
		{
			$pid=trim(I('post.pid'));
			$JingdongCat=new \Common\Model\JingdongCatModel();
			$list=$JingdongCat->getSubListByParent($pid,'asc','Y');
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
}
?>