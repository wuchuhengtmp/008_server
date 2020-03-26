<?php
/**
 * 论坛版块管理接口
 */
namespace App\Controller;
use App\Common\Controller\AuthController;

class BbsBoardController extends AuthController
{
	/**
	 * 获取顶级论坛版块列表
	 * @return array
	 * @return @param code:返回码
	 * @return @param msg:返回码说明
	 * @return @param data:返回数据
	 * @return @param data->list:顶级论坛版块列表
	 */
	public function getTopBoardList()
	{
		$BbsBoard=new \Common\Model\BbsBoardModel();
		$list=$BbsBoard->getTopList('Y');
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
		echo json_encode ($res,JSON_UNESCAPED_UNICODE);
	}
	
	/**
	 * 获取论坛子版块分类列表
	 * @param int $pid:父级版块ID
	 * @return array
	 * @return @param code:返回码
	 * @return @param msg:返回码说明
	 * @return @param data:返回数据
	 * @return @param data->sublist:论坛子版块分类列表
	 */
	public function getSubCatList()
	{
		if(trim(I('post.pid')))
		{
			$pid=trim(I('post.pid'));
			$BbsBoard=new \Common\Model\BbsBoardModel();
			$sublist=$BbsBoard->getSubList2($pid,'Y');
			if($sublist!==false)
			{
				$data=array(
						'sublist'=>$sublist
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
	 * 获取论坛版块内容
	 * @param int $board_id:版块ID
	 * @return array
	 * @return @param code:返回码
	 * @return @param msg:返回码说明
	 * @return @param data:返回数据
	 * @return @param data->boardMsg:论坛版块内容
	 */
	public function getBoardMsg()
	{
		if(trim(I('post.board_id')))
		{
			$board_id=trim(I('post.board_id'));
			$BbsBoard=new \Common\Model\BbsBoardModel();
			$boardMsg=$BbsBoard->getBoardMsg($board_id);
			if($boardMsg!==false)
			{
				$data=array(
						'boardMsg'=>$boardMsg
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