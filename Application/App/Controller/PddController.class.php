<?php
/**
 * 拼多多商品管理接口
 */
namespace App\Controller;
use App\Common\Controller\AuthController;

class PddController extends AuthController 
{
	/**
	 * 获取顶级拼多多商品分类列表
	 * @return array
	 * @return @param code:返回码
	 * @return @param msg:返回码说明
	 * @return @param data:返回数据
	 * @return @param data->list:顶级拼多多商品分类列表
	 */
	public function getTopCatList()
	{
		$PddCat=new \Common\Model\PddCatModel();
		$list=$PddCat->getParentList('Y');
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
		echo json_encode ($res,JSON_UNESCAPED_UNICODE);
	}
	
	/**
	 * 获取商品标准类目
	 * @param int $parent_cat_id:非必填，值=0时为顶点cat_id,通过树顶级节点获取cat树
	 * @return array
	 * @return @param code:返回码
	 * @return @param msg:返回码说明
	 * @return @param data:返回数据
	 */
	public function getPddGoodsCat()
	{
		if(trim(I('post.parent_cat_id')))
		{
			$parent_cat_id=trim(I('post.parent_cat_id'));
		}else {
			$parent_cat_id=0;
		}
		Vendor('pdd.pdd','','.class.php');
		$pdd=new \pdd();
		$res=$pdd->getGoodsCat($parent_cat_id);
		dump($res);die();
		//echo json_encode ($res,JSON_UNESCAPED_UNICODE);
	}
	
	/**
	 * 获取商品标签列表
	 * @param int $parent_opt_id:非必填，值=0时为顶点opt_id,通过树顶级节点获取opt树
	 * @return array
	 * @return @param code:返回码
	 * @return @param msg:返回码说明
	 * @return @param data:返回数据
	 */
	public function getPddGoodsOpt()
	{
		if(trim(I('post.parent_opt_id')))
		{
			$parent_opt_id=trim(I('post.parent_opt_id'));
		}else {
			$parent_opt_id=0;
		}
		Vendor('pdd.pdd','','.class.php');
		$pdd=new \pdd();
		$res=$pdd->getGoodsOpt($parent_opt_id=0);
		echo json_encode ($res,JSON_UNESCAPED_UNICODE);
	}
	
	/**
	 * 获取推荐商品列表
	 * @param int $channel_type:非必填，0, "1.9包邮"；1, "今日爆款"； 2, "品牌清仓"； 4,"PC端专属商城"；5，“福利商城”；不传值为默认商城；
	 * @return array
	 * @return @param code:返回码
	 * @return @param msg:返回码说明
	 * @return @param data:返回数据
	 * @return @param data->list:顶级京东商品分类列表
	 */
	public function getTopGoodsList()
	{
		if(trim(I('post.channel_type')))
		{
			$channel_type=trim(I('post.channel_type'));
		}else {
			$channel_type='';
		}
		Vendor('pdd.pdd','','.class.php');
		$pdd=new \pdd();
		$p_id_list='["'.$pdd->pid.'"]';
		$res=$pdd->promUrlGenerate($generate_short_url='true',$p_id_list,$generate_mobile=false,$multi_group='true',$custom_parameters='',$generate_weapp_webview=false,$we_app_web_view_short_url=true,$we_app_web_view_url=true,$channel_type);
		echo json_encode ($res,JSON_UNESCAPED_UNICODE);
	}
	
	/**
	 * 运营频道商品查询-推荐商品
	 * @param string $token:用户身份令牌
	 * @param number $page:非必填，默认值1，商品分页数
	 * @param number $page_size:非必填，默认10，每页商品数量
	 * @param string $channel_type:非必填，频道类型；0, "1.9包邮", 1, "今日爆款", 2, "品牌清仓", 3, "默认商城", 非必填 ,默认是1
	 * @return array
	 * @return @param code:返回码
	 * @return @param msg:返回码说明
	 * @return @param data:返回数据
	 * @return @param data->list:顶级京东商品分类列表
	 */
	public function getGoodsRecommend()
	{
		//第几页
		if(trim(I('post.page')))
		{
			$page=trim(I('post.page'));
		}else {
			$page=1;
		}
		//页大小
		if(trim(I('post.page_size')))
		{
			$page_size=trim(I('post.page_size'));
		}else {
			$page_size=10;
		}
		//频道类型
		if(trim(I('post.channel_type')))
		{
			$channel_type=trim(I('post.channel_type'));
		}else {
			$channel_type=1;
		}
		//用户账号
		if(trim(I('post.token')))
		{
			//判断用户身份
			$token=trim(I('post.token'));
			$User=new \Common\Model\UserModel();
			$res_token=$User->checkToken($token);
			if($res_token['code']!=0)
			{
				//用户身份不合法
				$res=$res_token;
				echo json_encode ($res,JSON_UNESCAPED_UNICODE);
				exit();
			}else {
				$uid=$res_token['uid'];
				$userMsg=$User->getUserMsg($uid);
				//会员组
				$group_id=$userMsg['group_id'];
			}
		}else {
			//普通会员组
			$group_id=1;
		}
		$UserGroup=new \Common\Model\UserGroupModel();
		$groupMsg=$UserGroup->getGroupMsg($group_id);
		$fee_user=$groupMsg['fee_user'];
		
		//获取商品列表
		Vendor('pdd.pdd','','.class.php');
		$pdd=new \pdd();
		$offset=($page-1)*$page_size;
		$res_pdd=$pdd->getGoodsRecommend($offset,$page_size,$channel_type);
		if($res_pdd['code']==0)
		{
			$num=count($res_pdd['data']['list']);
			for($i=0;$i<$num;$i++)
			{
				//根据会员组计算相应佣金
				//佣金
				$price=$res_pdd['data']['list'][$i]['min_group_price']-$res_pdd['data']['list'][$i]['coupon_discount'];
				$commission=($price*$res_pdd['data']['list'][$i]['promotion_rate']/1000)*$fee_user/100;
				//保留2位小数，四舍五不入
				$res_pdd['data']['list'][$i]['commission']=substr(sprintf("%.3f",$commission),0,-1);
			}
		}
		$res=$res_pdd;
		echo json_encode ($res,JSON_UNESCAPED_UNICODE);
	}
	
	/**
	 * 获取商品列表
	 * @param string $token:用户身份令牌
	 * @param string $keyword:非必填，商品关键词，与opt_id字段选填一个或全部填写
	 * @param number $opt_id:非必填，商品标签类目ID，使用pdd.goods.opt.get获取
	 * @param number $page:非必填，默认值1，商品分页数
	 * @param number $page_size:非必填，默认10，每页商品数量
	 * @param string $sort_type:非必填，排序方式:0-综合排序（默认）;1-按佣金比率升序;2-按佣金比例降序;3-按价格升序;4-按价格降序;5-按销量升序;6-按销量降序;7-优惠券金额排序升序;8-优惠券金额排序降序;9-券后价升序排序;10-券后价降序排序;11-按照加入多多进宝时间升序;12-按照加入多多进宝时间降序;13-按佣金金额升序排序;14-按佣金金额降序排序;15-店铺描述评分升序;16-店铺描述评分降序;17-店铺物流评分升序;18-店铺物流评分降序;19-店铺服务评分升序;20-店铺服务评分降序;27-描述评分击败同类店铺百分比升序，28-描述评分击败同类店铺百分比降序，29-物流评分击败同类店铺百分比升序，30-物流评分击败同类店铺百分比降序，31-服务评分击败同类店铺百分比升序，32-服务评分击败同类店铺百分比降序
	 * @param string $with_coupon:非必填，是否只返回优惠券的商品，false返回所有商品，true只返回有优惠券的商品（默认）
	 * @param number $cat_id:非必填，商品类目ID，使用pdd.goods.cats.get接口获取
	 * @return array
	 * @return @param code:返回码
	 * @return @param msg:返回码说明
	 * @return @param data:返回数据
	 * @return @param data->list:顶级京东商品分类列表
	 */
	public function getGoodsList()
	{
		//商品关键词
		if(trim(I('post.keyword')))
		{
			$keyword=trim(I('post.keyword'));
		}
		//商品标签类目ID
		if(trim(I('post.opt_id')))
		{
			$opt_id=trim(I('post.opt_id'));
		}
		//排序方式
		if(trim(I('post.sort_type')))
		{
			$sort_type=trim(I('post.sort_type'));
		}else {
			$sort_type=0;
		}
		//商品分类ID
		if(trim(I('post.cat_id')))
		{
			$cat_id=trim(I('post.cat_id'));
		}
		//是否只返回优惠券的商品
		if(trim(I('post.with_coupon')))
		{
			$with_coupon=trim(I('post.with_coupon'));
		}else {
			$with_coupon='true';
		}
		//第几页
		if(trim(I('post.page')))
		{
			$page=trim(I('post.page'));
		}else {
			$page=1;
		}
		//页大小
		if(trim(I('post.page_size')))
		{
			$page_size=trim(I('post.page_size'));
		}else {
			$page_size=10;
		}
		//用户账号
		if(trim(I('post.token')))
		{
			//判断用户身份
			$token=trim(I('post.token'));
			$User=new \Common\Model\UserModel();
			$res_token=$User->checkToken($token);
			if($res_token['code']!=0)
			{
				//用户身份不合法
				$res=$res_token;
				echo json_encode ($res,JSON_UNESCAPED_UNICODE);
				exit();
			}else {
				$uid=$res_token['uid'];
				$userMsg=$User->getUserMsg($uid);
				//会员组
				$group_id=$userMsg['group_id'];
			}
		}else {
			//普通会员组
			$group_id=1;
		}
		$UserGroup=new \Common\Model\UserGroupModel();
		$groupMsg=$UserGroup->getGroupMsg($group_id);
		$fee_user=$groupMsg['fee_user'];
		
		//获取商品列表
		Vendor('pdd.pdd','','.class.php');
		$pdd=new \pdd();
		$res_pdd=$pdd->searchGoods($keyword,$opt_id,$page,$page_size,$sort_type,$with_coupon,$range_list='',$cat_id,$goods_id_list='',$zs_duo_id='',$merchant_type='');
		if($res_pdd['code']==0)
		{
			$num=count($res_pdd['data']['list']);
			for($i=0;$i<$num;$i++)
			{
				//根据会员组计算相应佣金
				//佣金
				$price=$res_pdd['data']['list'][$i]['min_group_price']-$res_pdd['data']['list'][$i]['coupon_discount'];
				$commission=($price*$res_pdd['data']['list'][$i]['promotion_rate']/1000)*$fee_user/100;
				//保留2位小数，四舍五不入
				$res_pdd['data']['list'][$i]['commission']=substr(sprintf("%.3f",$commission),0,-1);
			}
		}
		$res=$res_pdd;
		echo json_encode ($res,JSON_UNESCAPED_UNICODE);
	}
	
	/**
	 * 获取商品详情
	 * @param string $token:用户身份令牌
	 * @param int $goods_id:拼多多商品ID
	 * @return array
	 * @return @param code:返回码
	 * @return @param msg:返回码说明
	 * @return @param data:返回数据
	 * @return @param data->goods_details:商品详情
	 */
	public function getGoodsDetail()
	{
		if(trim(I('post.goods_id')) and trim(I('post.token')))
		{
			$goods_id=trim(I('post.goods_id'));
			$goods_id_list="[$goods_id]";
			//用户账号
			if(trim(I('post.token')))
			{
				//判断用户身份
				$token=trim(I('post.token'));
				$User=new \Common\Model\UserModel();
				$res_token=$User->checkToken($token);
				if($res_token['code']!=0)
				{
					//用户身份不合法
					$res=$res_token;
					echo json_encode ($res,JSON_UNESCAPED_UNICODE);
					exit();
				}else {
					$uid=$res_token['uid'];
					$userMsg=$User->getUserMsg($uid);
					//会员组
					$group_id=$userMsg['group_id'];
				}
			}else {
				//普通会员组
				$group_id=1;
			}
			$UserGroup=new \Common\Model\UserGroupModel();
			$groupMsg=$UserGroup->getGroupMsg($group_id);
			$fee_user=$groupMsg['fee_user'];
			
			//获取商品列表
			Vendor('pdd.pdd','','.class.php');
			$pdd=new \pdd();
			$res_pdd=$pdd->getGoodsDetail($goods_id_list);
			if($res_pdd['code']==0)
			{
				//根据会员组计算相应佣金
				//佣金
				$price=$res_pdd['data']['goods_details']['min_group_price']-$res_pdd['data']['goods_details']['coupon_discount'];
				$commission=($price*$res_pdd['data']['goods_details']['promotion_rate']/1000)*$fee_user/100;
				//保留2位小数，四舍五不入
				$res_pdd['data']['goods_details']['commission']=substr(sprintf("%.3f",$commission),0,-1);
				//VIP佣金
				$res_pdd['data']['commission_vip']=($price*$res_pdd['data']['goods_details']['promotion_rate']/1000)*0.9;
				//保留2位小数，四舍五不入
				$res_pdd['data']['commission_vip']=substr(sprintf("%.3f",$res_pdd['data']['commission_vip']),0,-1);
					
				//生成推广链接
				$p_id=$pdd->pid;
				$custom_parameters=$uid;
				$res_pdd_url=$pdd->goodsPromotionUrlGenerate($p_id,$goods_id_list,$generate_short_url='true',$multi_group='false',$custom_parameters,$pull_new='true',$generate_weapp_webview='true',$zs_duo_id='',$generate_we_app='');
				if($res_pdd_url['code']==0)
				{
					$res_pdd['data']['url_list']=$res_pdd_url['data']['url_list'];
				}else {
					$res_pdd['data']['url_list']=array();
				}
			}
			$res=$res_pdd;
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