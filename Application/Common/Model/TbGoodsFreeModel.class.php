<?php
/**
 * 淘宝0元购商品管理
 */
namespace Common\Model;
use Think\Model;

class TbGoodsFreeModel extends Model
{
	/**
	 * 获取商品详情
	 * @param int $id:ID
	 * @return array|boolean
	 */
	public function getGoodsMsg($id)
	{
		$msg=$this->where("id='$id'")->find();
		if($msg) {
			return $msg;
		}else {
			return false;
		}
	}
}
?>