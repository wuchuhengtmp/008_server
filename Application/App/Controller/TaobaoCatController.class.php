<?php
/**
 * 淘宝商品分类管理接口
 */
namespace App\Controller;
use App\Common\Controller\AuthController;

class TaobaoCatController extends AuthController 
{
	/**
	 * 获取顶级淘宝商品分类列表
	 * @return array
	 * @return @param code:返回码
	 * @return @param msg:返回码说明
	 * @return @param data:返回数据
	 * @return @param data->list:顶级淘宝商品分类列表
	 */
	public function getTopCatList()
	{
	    // 读取缓存
	    $list = S('tbCatList');
	    if($list===false) {
	        //未设置缓存，进行设置
	        $TaobaoCat=new \Common\Model\TaobaoCatModel();
	        $list=$TaobaoCat->getParentList('Y');
	        if($list!==false) {
	            //设置缓存
	            //不设置过期时间
	            S('tbCatList',$list,array('type'=>'file','expire'=>0));
	        }else {
	            //数据库错误
	            $res=array(
	                'code'=>$this->ERROR_CODE_COMMON['DB_ERROR'],
	                'msg'=>$this->ERROR_CODE_COMMON_ZH[$this->ERROR_CODE_COMMON['DB_ERROR']]
	            );
	            echo json_encode ($res,JSON_UNESCAPED_UNICODE);
	            exit();
	        }
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
		echo json_encode ($res,JSON_UNESCAPED_UNICODE);
	}
	
	/**
	 * 获取子级淘宝商品分类列表
	 * @param int $pid:父级淘宝商品分类ID
	 * @return array
	 * @return @param code:返回码
	 * @return @param msg:返回码说明
	 * @return @param data:返回数据
	 * @return @param data->list:子级淘宝商品分类列表
	 */
	public function getSubListByParent()
	{
		if(trim(I('post.pid')))
		{
			$pid=trim(I('post.pid'));
			$TaobaoCat=new \Common\Model\TaobaoCatModel();
			$list=$TaobaoCat->getSubListByParent($pid,'asc','Y');
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