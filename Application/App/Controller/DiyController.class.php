<?php
/**
 * 样式管理接口
 */
namespace App\Controller;
use App\Common\Controller\AuthController;

class DiyController extends AuthController
{
	/**
	 * 获取样式设置
	 * @return array
	 * @return @param code:返回码
	 * @return @param msg:返回码说明
	 * @return @param data:返回数据
	 * @return @param data->moduleList:功能模块列表
	 */
	public function set()
	{
	    // 读取缓存
	    $moduleList = S('diy_moduleList');
	    if($moduleList===false) {
	        //未设置缓存，进行设置
	        $DiyModule=new \Common\Model\DiyModuleModel();
	        $moduleList=$DiyModule->getModuleList('Y');
	        if($moduleList!==false) {
	            //设置缓存
	            //不设置过期时间
	            S('diy_moduleList',$moduleList,array('type'=>'file','expire'=>0));
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
		$data=array(
		    'moduleList'=>$moduleList
		);
		$res=array(
				'code'=>0,
				'msg'=>'成功',
				'data'=>$data
		);
		echo json_encode ($res,JSON_UNESCAPED_UNICODE);
	}
}
?>