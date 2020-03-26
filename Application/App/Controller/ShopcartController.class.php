<?php
/**
 * 购物车管理接口
 */
namespace App\Controller;
use App\Common\Controller\AuthController;

class ShopcartController extends AuthController
{
	/**
	 * 加入购物车
	 * @param string $token:用户身份令牌
	 * @param int $goods_id:商品ID
	 * @param int $goods_num:购买数量，非必填，默认1
	 * @param int $goods_sku:商品规格属性，json数组
	 * @return array
	 * @return @param code:返回码
	 * @return @param msg:返回码说明
	 */
	public function add()
	{
		if( trim(I('post.token')) and trim(I('post.goods_id')) ) {
			//判断用户身份
			$token=trim(I('post.token'));
			$User=new \Common\Model\UserModel();
			$res_token=$User->checkToken($token);
			if($res_token['code']!=0) {
				//用户身份不合法
				$res=$res_token;
			}else {
				$uid=$res_token['uid'];
				if(I('post.goods_num')) {
					$goods_num=I('post.goods_num');
				}else {
					$goods_num=1;
				}
				//判断商品是否存在
				$goods_id=trim(I('post.goods_id'));
				$Goods=new \Common\Model\GoodsModel();
				$GoodsMsg=$Goods->getGoodsMsg($goods_id);
				if($GoodsMsg) {
					//判断是否已加入购物车
					$where="user_id='$uid' and goods_id='$goods_id'";
					if($_POST['goods_sku']) {
						$goods_sku=$_POST['goods_sku'];
						$goods_sku=str_replace("\\", '', $goods_sku);
						//$goods_sku=json_encode($_POST['goods_sku'],JSON_UNESCAPED_UNICODE);//处理数组
						$GoodsSku=new \Common\Model\GoodsSkuModel();
						$skuMsg=$GoodsSku->getSkuMsg($goods_sku,$goods_id);
						if($skuMsg=='')
						{
							//商品规格配置不存在
							$res=array(
									'code'=>$this->ERROR_CODE_GOODS['GOODS_SKU_NOT_EXIST'],
									'msg'=>$this->ERROR_CODE_GOODS_ZH[$this->ERROR_CODE_GOODS['GOODS_SKU_NOT_EXIST']]
							);
							echo json_encode ($res,JSON_UNESCAPED_UNICODE);
							exit();
						}else {
							$where.=" and goods_sku='$goods_sku'";
						}
					}
					$Shopcart=new \Common\Model\ShopcartModel();
					$res_exist=$Shopcart->where($where)->find();
					if($res_exist) {
						//已加入过购物车，不作操作
						$res=array(
								'code'=>$this->ERROR_CODE_COMMON['SUCCESS'],
								'msg'=>'成功'
						);
					}else {
						//新增
						$data=array(
								'user_id'=>$uid,
								'goods_id'=>$goods_id,
								'goods_num'=>$goods_num,
								'goods_sku'=>$goods_sku
						);
						$res_add=$Shopcart->add($data);
						if($res_add!==false) {
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
					//该商品不存在
					$res=array(
							'code'=>$this->ERROR_CODE_GOODS['GOODS_NOT_EXIST'],
							'msg'=>$this->ERROR_CODE_GOODS_ZH[$this->ERROR_CODE_GOODS['GOODS_NOT_EXIST']]
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
	
	/**
	 * 获取用户购物车列表
	 * @param string $token:用户身份令牌
	 * @return array
	 * @return @param code:返回码
	 * @return @param msg:返回码说明
	 * @return @param data:返回数据
	 * @return @param data->list:购物车列表
	 */
	public function getShopcartList()
	{
		if( trim(I('post.token')) ) {
			//判断用户身份
			$token=trim(I('post.token'));
			$User=new \Common\Model\UserModel();
			$res_token=$User->checkToken($token);
			if($res_token['code']!=0) {
				//用户身份不合法
				$res=$res_token;
			}else {
				$uid=$res_token['uid'];
				//获取用户购物车列表
				$Shopcart=new \Common\Model\ShopcartModel();
				$list=$Shopcart->getUserShopcart($uid);
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
	 * 从购物车删除
	 * @param string $token:用户身份令牌
	 * @param int $goods_id:商品ID
	 * @param int $goods_sku:商品规格属性，json数组
	 * @return array
	 * @return @param code:返回码
	 * @return @param msg:返回码说明
	 */
	public function del()
	{
		if( trim(I('post.token')) and trim(I('post.goods_id')) ) {
			//判断用户身份
			$token=trim(I('post.token'));
			$User=new \Common\Model\UserModel();
			$res_token=$User->checkToken($token);
			if($res_token['code']!=0) {
				//用户身份不合法
				$res=$res_token;
			}else {
				$uid=$res_token['uid'];
				$goods_id=trim(I('post.goods_id'));
				$where="user_id='$uid' and goods_id='$goods_id'";
				if($_POST['goods_sku']) {
					$goods_sku=$_POST['goods_sku'];
					$where.=" and goods_sku='$goods_sku'";
				}
				$Shopcart=new \Common\Model\ShopcartModel();
				$res_del=$Shopcart->where($where)->delete();
				if($res_del!==false) {
					$res=array(
							'code'=>$this->ERROR_CODE_COMMON['SUCCESS'],
							'msg'=>'成功',
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