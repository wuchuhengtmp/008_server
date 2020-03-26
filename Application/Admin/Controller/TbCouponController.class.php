<?php
/**
 * 淘宝隐藏优惠券管理
 */
namespace Admin\Controller;
use Admin\Common\Controller\AuthController;

class TbCouponController extends AuthController
{
	//订单列表
	public function index()
	{
		$where='1';
		//商品名称
		if(trim(I('get.item_title')))
		{
			$item_title=trim(I('get.item_title'));
			$where="item_title like '%$item_title%'";
		}
		$TbCoupon=new \Common\Model\TbCouponModel();
		$count=$TbCoupon->where($where)->count();
		$per = 15;
		if($_GET['p'])
		{
			$p=$_GET['p'];
		}else {
			$p=1;
		}
		$Page=new \Common\Model\PageModel();
		$show= $Page->show($count,$per);// 分页显示输出
		$this->assign('page',$show);
			
		$list = $TbCoupon->where($where)->page($p.','.$per)->order('id desc')->select();
		$this->assign('list',$list);
			
		$this->display();
	}
	
	//添加优惠券
	public function add()
	{
		if(I('post.'))
		{
			layout(false);
			if(trim(I('post.tkl')))
			{
				$tkl=trim(I('post.tkl'));
				//解析淘口令
				Vendor('tbk.tbk','','.class.php');
				$tbk=new \tbk();
				$res_tkl=$tbk->wirelessShareTpwdQuery($tkl);
				//dump($res_tkl);die();
				if($res_tkl['code']==0)
				{
					//淘宝商品地址
					$taobao_url=$res_tkl['data']['url'];
					//$taobao_url='https://uland.taobao.com/coupon/edetail?itemId=577183072185&pid=mm_21742772_104250451_20294800352&activityId=553d8e1aaeff4c29a4d3297bfc274dd2';
					$url_arr=getUrlParam($taobao_url);
					//dump($url_arr);die();
					//商品ID
					$num_iid=$url_arr['num_iid'];
					//优惠券ID
					$activityId=$url_arr['activityId'];
					if($activityId)
					{
						//根据商品ID、优惠券ID获取优惠券信息
						$res_coupon=$tbk->getCouponMsg($me='',$num_iid,$activityId);
						if($res_coupon['code']==0)
						{
							//是单品优惠券
							$couponMsg=$res_coupon['data'];
						}else {
							//不是单品优惠券，是店铺优惠券
							//获取商品信息
							$res_goods=$tbk->getItemInfo($num_iid,$platform='2',$ip='');
							if($res_goods['code']==0)
							{
								$goodsMsg=$res_goods['data'];
								$sellerId=$goodsMsg['seller_id'];
								$seller_name=$goodsMsg['nick'];
								//抓包店铺优惠券
								$coupon_url="https://uland.taobao.com/quan/detail?sellerId=$sellerId&activityId=$activityId";
							}else {
								$this->error('商品不存在！');
							}
						}
					}else {
						$this->error('优惠券不存在！');
					}
				}else {
					//淘口令不存在/淘口令解析失败！
					$this->error('淘口令不存在/淘口令解析失败！');
				}
			}else {
				$this->error('请输入要解析的淘口令！');
			}
		}else {
			$this->display();
		}
	}
}
?>