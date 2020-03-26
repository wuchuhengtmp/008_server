<?php
/**
 * 留言管理接口
 */
namespace App\Controller;
use App\Common\Controller\AuthController;

class MessageController extends AuthController
{
	/**
	 * 留言
	 * @param int $cat_id:留言分类ID
	 * @param string $linkman:联系人
	 * @param string $phone:联系电话
	 * @param string $content:留言内容
	 * @return array
	 * @return @param code:返回码
	 * @return @param msg:返回码说明
	 */
	public function online()
	{
		if(trim(I('post.cat_id')) and trim(I('post.linkman')) and trim(I('post.phone')) and trim(I('post.content')))
		{
			$cat_id=trim(I('post.cat_id'));
			$data=array(
					'cat_id'=>$cat_id,
					'content'=>trim(I('post.content')),
					'linkman'=>trim(I('post.linkman')),
					'phone'=>trim(I('post.phone')),
					'ip'=>getIP(),
					'createtime'=>date('Y-m-d H:i:s')
			);
			$Message=new \Common\Model\MessageModel();
			if(!$Message->create($data))
			{
				//验证不通过
				$res=array(
						'code'=>$this->ERROR_CODE_COMMON['PARAMETER_FORMAT_ERROR'],
						'msg'=>$Message->getError()
				);
			}else {
				$res_add=$Message->add($data);
				if($res_add!==false)
				{
					$res=array(
							'code'=>$this->ERROR_CODE_COMMON['SUCCESS'],
							'msg'=>'成功'
					);
				}else {
					//数据库错误
					$res=array(
							'code'=>$this->ERROR_CODE_COMMON['DB_ERROR'],
							'msg'=>$this->ERROR_CODE_COMMON_ZH[$this->ERROR_CODE_COMMON['DB_ERROR']]
					);
				}
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