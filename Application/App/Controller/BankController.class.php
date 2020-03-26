<?php
/**
 * 银行管理接口
 */
namespace App\Controller;
use App\Common\Controller\AuthController;

class BankController extends AuthController
{
	/**
	 * 获取银行列表
	 * @param int $bank_name:银行名称
	 * @param int $p:页码
	 * @param int $per:每页条数，不进行分页获取的时候，默认获取全部
	 * @return array
	 * @return @param code:返回码
	 * @return @param msg:返回码说明
	 * @return @param data:返回数据
	 * @return @param data->list:银行列表
	 */
	public function getBankList()
	{
		$where="is_show='Y'";
		if(trim(I('post.bank_name'))) {
			$bank_name=trim(I('post.bank_name'));
			$where.=" and bank_name like '%$bank_name%'";
		}
		if(trim(I('post.p')) and trim(I('post.per'))) {
			$p=trim(I('post.p'));
			$per=trim(I('post.per'));
		}else {
			$p=1;
			$per=100;
		}
		$Bank=new \Common\Model\BankModel();
		$list=$Bank->where($where)->field('bank_id,bank_name,icon,bg_img')->page($p,$per)->order('sort desc')->select();
		if($list!==false) {
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