<?php
/**
 * 热门搜索管理
 */
namespace Common\Model;
use Think\Model;

class HotSearchModel extends Model
{
	//验证规则
	protected $_validate =array(
			array('search','require','搜索的名称不能为空！',self::EXISTS_VALIDATE),  //存在验证，必填
			array('search','1,100','搜索的名称不超过100个字符！',self::EXISTS_VALIDATE,'length'),  //存在验证，不超过100个字符
			array('num','is_positive_int','搜索总量必须为大于零的整数！',self::EXISTS_VALIDATE,'function'),  //存在验证 ，必须是正整数
	);
	
	/**
	 * 获取热门搜索信息
	 * @param int $id:ID
	 * @return array|boolean
	 */
	public function getMsg($id)
	{
	    $msg=$this->where("id=$id")->find();
	    if($msg) {
	        switch($msg['type']){
	            case 1:
	                $type_zh='淘宝';
	                break;
	            case 2:
	                $type_zh='拼多多';
	                break;
	            case 3:
	                $type_zh='京东';
	                break;
	            case 4:
	                $type_zh='自营商城';
	                break;
	            default:
	                $type_zh='';
	                break;
	        }
	        $msg['type_zh']=$type_zh;
	        return $msg;
	    }else {
	        return false;
	    }
	}
	
	/**
	 * 统计搜索
	 * @param string $search:搜索的名称
	 * @return boolean
	 */
	public function statistics($search)
	{
		$msg=$this->where("search='$search'")->find();
		if($msg) {
			//已搜索过
			//增加搜索次数
			$id=$msg['id'];
			$res=$this->where("id='$id'")->setInc('num',1);
			if($res!==false) {
				return true;
			}else {
				return false;
			}
		}else{
			//未搜索过
			$data=array(
					'search'=>$search,
					'num'=>1
			);
			if(!$this->create($data)) {
				return false;
			}else {
				$res=$this->add($data);
				if($res!==false) {
					return true;
				}else {
					return false;
				}
			}
		}
	}
}
?>