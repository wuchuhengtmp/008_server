<?php
/**
 *  Banner/广告管理接口
 */
namespace App\Controller;
use App\Common\Controller\AuthController;

class BannerController extends AuthController
{
	/**
	 * 获取Banner/广告图列表
	 * @param int $cat_id:Banner/广告分类ID
	 * @param int $agent_id:代理商ID
	 * @return array
	 * @return @param code:返回码
	 * @return @param msg:返回码说明
	 * @return @param data:返回数据
	 * @return @param data->list:Banner/广告图列表
	 */
	public function getBannerList()
	{
		if(trim(I('post.cat_id')))
		{
			$cat_id=trim(I('post.cat_id'));
			$agent_id=0;
			if(trim(I('post.agent_id'))){
			    $agent_id=trim(I('post.agent_id'));
			}
			$Banner=new \Common\Model\BannerModel();
			$list=$Banner->getBannerList($cat_id,'Y',$agent_id);
			if($list!==false)
			{
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
	 * 获取Banner/广告图信息
	 * @param int $id:Banner/广告ID
	 * @return array
	 * @return @param code:返回码
	 * @return @param msg:返回码说明
	 * @return @param data:返回数据
	 * @return @param data->bannerMsg:Banner/广告图信息
	 */
	public function getBannerMsg()
	{
		if(trim(I('post.id')))
		{
			$id=trim(I('post.id'));
			$Banner=new \Common\Model\BannerModel();
			$bannerMsg=$Banner->getBannerMsg($id);
			if($bannerMsg!==false)
			{
				$data=array(
						'bannerMsg'=>$bannerMsg
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