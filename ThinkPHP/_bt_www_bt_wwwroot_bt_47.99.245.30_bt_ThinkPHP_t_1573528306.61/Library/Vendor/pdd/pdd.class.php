<?php
/**
 * 拼多多联盟
 * 2018-11-20
 * @author 葛阳
 * 只能使用POST请求进行API调用。
 * API调用除了必须包含公共参数外，如果API本身有业务级的参数也必须传入
 */

include "PddClient.php";
class pdd
{
	protected $client_id=PDD_CLIENT_ID;
	protected $client_secret=PDD_CLIENT_SECRET;
	public $pid=PDD_PID;
	
	/**
	 * 查询商品标准类目
	 * @param integer $parent_cat_id:必填，值=0时为顶点cat_id,通过树顶级节点获取cat树
	 * @return mixed
	 */
	public function getGoodsCat($parent_cat_id=0)
	{
		$c = new PddClient();
		$c->type = 'pdd.goods.cats.get';
		$req=array(
				'parent_cat_id'=>$parent_cat_id,
		);
		$resp = $c->execute($req);
		$result=json_decode($resp,true);
		return $result;
	}
	
	/**
	 * 查询商品标签列表
	 * @param integer $parent_opt_id:必填，值=0时为顶点opt_id,通过树顶级节点获取opt树
	 * @return mixed
	 */
	public function getGoodsOpt($parent_opt_id=0)
	{
		$c = new PddClient();
		$c->type = 'pdd.goods.opt.get';
		$req=array(
				'parent_opt_id'=>$parent_opt_id,
		);
		$resp = $c->execute($req);
		$result=json_decode($resp,true);
		return $result;
	}
	
	/**
	 * 商品查询
	 * @param string $keyword:非必填，商品关键词，与opt_id字段选填一个或全部填写
	 * @param number $opt_id:非必填，商品标签类目ID，使用pdd.goods.opt.get获取
	 * @param number $page:非必填，默认值1，商品分页数
	 * @param number $page_size:非必填，默认100，每页商品数量
	 * @param string $sort_type:必填，排序方式:0-综合排序;1-按佣金比率升序;2-按佣金比例降序;3-按价格升序;4-按价格降序;5-按销量升序;6-按销量降序;7-优惠券金额排序升序;8-优惠券金额排序降序;9-券后价升序排序;10-券后价降序排序;11-按照加入多多进宝时间升序;12-按照加入多多进宝时间降序;13-按佣金金额升序排序;14-按佣金金额降序排序;15-店铺描述评分升序;16-店铺描述评分降序;17-店铺物流评分升序;18-店铺物流评分降序;19-店铺服务评分升序;20-店铺服务评分降序;27-描述评分击败同类店铺百分比升序，28-描述评分击败同类店铺百分比降序，29-物流评分击败同类店铺百分比升序，30-物流评分击败同类店铺百分比降序，31-服务评分击败同类店铺百分比升序，32-服务评分击败同类店铺百分比降序
	 * @param boolean $with_coupon:必填，是否只返回优惠券的商品，false返回所有商品，true只返回有优惠券的商品
	 * @param string $range_list:非必填，范围列表，可选值：[{"range_id":0,"range_from":1,"range_to":1500},{"range_id":1,"range_from":1,"range_to":1500}]
	 * @param number $cat_id:非必填，商品类目ID，使用pdd.goods.cats.get接口获取
	 * @param number $goods_id_list:非必填，商品ID列表。例如：[123456,123]，当入参带有goods_id_list字段，将不会以opt_id、 cat_id、keyword维度筛选商品
	 * @param number $zs_duo_id:非必填，招商多多客ID
	 * @param number $merchant_type:非必填，店铺类型，1-个人，2-企业，3-旗舰店，4-专卖店，5-专营店，6-普通店（未传为全部）
	 */
	public function searchGoods($keyword='',$opt_id='',$page=1,$page_size=100,$sort_type,$with_coupon,$range_list='',$cat_id='',$goods_id_list='',$zs_duo_id='',$merchant_type='')
	{
		$c = new PddClient();
		$c->type = 'pdd.ddk.goods.search';
		$req=array(
				'keyword'=>$keyword,
				'opt_id'=>$opt_id,
				'page'=>$page,
				'page_size'=>$page_size,
				'sort_type'=>$sort_type,
				'with_coupon'=>$with_coupon,
				'range_list'=>$range_list,
				'cat_id'=>$cat_id,
				'goods_id_list'=>$goods_id_list,
				'zs_duo_id'=>$zs_duo_id,
				'merchant_type'=>$merchant_type
		);
		$resp = $c->execute($req);
		$result=json_decode($resp,true);
		if($result['error_response']['error_code'])
		{
			$res=array(
					'code'=>$result['error_response']['error_code'],
					'msg'=>$result['error_response']['error_msg'],
					'data'=>array()
			);
		}else {
			$data=array(
					'list'=>$result['goods_search_response']['goods_list']
			);
			$res=array(
					'code'=>0,
					'msg'=>'成功',
					'data'=>$data
			);
		}
		return $res;
	}
	
	/**
	 * 运营频道商品查询-推荐商品
	 * @param number $offset:非必填，从多少位置开始请求；默认值 ： 0
	 * @param number $limit:非必填，请求数量；默认值 ： 400
	 * @param number $channel_type:非必填，频道类型；0, "1.9包邮", 1, "今日爆款", 2, "品牌清仓", 3, "默认商城", 非必填 ,默认是1
	 * @return mixed
	 */
	public function getGoodsRecommend($offset=0,$limit=400,$channel_type=1)
	{
		$c = new PddClient();
		$c->type = 'pdd.ddk.goods.recommend.get';
		$req=array(
				'offset'=>$offset,
				'limit'=>$limit,
				'channel_type'=>$channel_type
		);
		$resp = $c->execute($req);
		$result=json_decode($resp,true);
		if($result['error_response']['error_code'])
		{
			$res=array(
					'code'=>$result['error_response']['error_code'],
					'msg'=>$result['error_response']['error_msg'],
					'data'=>array()
			);
		}else {
			$data=array(
					'list'=>$result['goods_basic_detail_response']['list']
			);
			$res=array(
					'code'=>0,
					'msg'=>'成功',
					'data'=>$data
			);
		}
		return $res;
	}
	
	/**
	 * 商品详情查询
	 * @param LONG[] $goods_id_list:必填，商品ID，仅支持单个查询。例如：[123456]
	 * @return mixed
	 */
	public function getGoodsDetail($goods_id_list)
	{
		$c = new PddClient();
		$c->type = 'pdd.ddk.goods.detail';
		$req=array(
				'goods_id_list'=>$goods_id_list,
		);
		$resp = $c->execute($req);
		$result=json_decode($resp,true);
		if($result['error_response']['error_code'])
		{
			$res=array(
					'code'=>$result['error_response']['error_code'],
					'msg'=>$result['error_response']['error_msg'],
					'data'=>array()
			);
		}else {
			$data=array(
					'goods_details'=>$result['goods_detail_response']['goods_details'][0]
			);
			$res=array(
					'code'=>0,
					'msg'=>'成功',
					'data'=>$data
			);
		}
		return $res;
	}
	
	/**
	 * 推广链接生成
	 * @param string $p_id:必填，推广位ID
	 * @param LONG[] $goods_id_list:必填，商品ID，仅支持单个查询。例如：[123456]
	 * @param boolean $generate_short_url:非必填，是否生成短链接，true-是，false-否
	 * @param boolean $multi_group:非必填，true--生成多人团推广链接 false--生成单人团推广链接（默认false）1、单人团推广链接：用户访问单人团推广链接，可直接购买商品无需拼团。2、多人团推广链接：用户访问双人团推广链接开团，若用户分享给他人参团，则开团者和参团者的佣金均结算给推手
	 * @param string $custom_parameters:非必填，自定义参数，为链接打上自定义标签。自定义参数最长限制64个字节。
	 * @param boolean $pull_new:非必填，是否开启订单拉新，true表示开启（订单拉新奖励特权仅支持白名单，请联系工作人员开通）
	 * @param boolean $generate_weapp_webview:非必填，是否生成唤起微信客户端链接，true-是，false-否，默认false
	 * @param string $zs_duo_id:非必填，招商多多客ID
	 * @param boolean $generate_we_app:非必填，是否生成小程序推广
	 * @return mixed
	 */
	public function goodsPromotionUrlGenerate($p_id,$goods_id_list,$generate_short_url='',$multi_group='false',$custom_parameters='',$pull_new='',$generate_weapp_webview='false',$zs_duo_id='',$generate_we_app='')
	{
		$c = new PddClient();
		$c->type = 'pdd.ddk.goods.promotion.url.generate';
		$req=array(
				'p_id'=>$p_id,
				'goods_id_list'=>$goods_id_list,
				'generate_short_url'=>$generate_short_url,
				'multi_group'=>$multi_group,
				'custom_parameters'=>$custom_parameters,
				'pull_new'=>$pull_new,
				'generate_weapp_webview'=>$generate_weapp_webview,
				'zs_duo_id'=>$zs_duo_id,
				'generate_we_app'=>$generate_we_app,
		);
		$resp = $c->execute($req);
		$result=json_decode($resp,true);
		if($result['error_response']['error_code'])
		{
			$res=array(
					'code'=>$result['error_response']['error_code'],
					'msg'=>$result['error_response']['error_msg'],
					'data'=>array()
			);
		}else {
			$data=array(
					'url_list'=>$result['goods_promotion_url_generate_response']['goods_promotion_url_list']
			);
			$res=array(
					'code'=>0,
					'msg'=>'成功',
					'data'=>$data
			);
		}
		return $res;
	}
	
	/**
	 * 生成商城-频道推广链接
	 * @param boolean $generate_short_url:非必填，是否生成短链接，true-是，false-否
	 * @param string $p_id_list:必填，推广位列表，例如：["60005_612"]
	 * @param boolean $generate_mobile:非必填，是否生成手机跳转链接。true-是，false-否，默认false
	 * @param boolean $multi_group:非必填，单人团多人团标志。true-多人团，false-单人团 默认false
	 * @param string $custom_parameters:必填，自定义参数，为链接打上自定义标签。自定义参数最长限制64个字节。
	 * @param boolean $generate_weapp_webview:非必填，是否唤起微信客户端， 默认false 否，true 是
	 * @param boolean $we_app_web_view_short_url:必填，唤起微信app推广短链接
	 * @param boolean $we_app_web_view_url:必填，唤起微信app推广链接
	 * @param number $channel_type:非必填，0, "1.9包邮"；1, "今日爆款"； 2, "品牌清仓"； 4,"PC端专属商城"；5，“福利商城”；不传值为默认商城；
	 * @return array
	 */
	public function promUrlGenerate($generate_short_url='true',$p_id_list,$generate_mobile=false,$multi_group=false,$custom_parameters='',$generate_weapp_webview=false,$we_app_web_view_short_url=true,$we_app_web_view_url=true,$channel_type='')
	{
		$c = new PddClient();
		$c->type = 'pdd.ddk.cms.prom.url.generate';
		$req=array(
				'generate_short_url'=>$generate_short_url,
				'p_id_list'=>$p_id_list,
				'generate_mobile'=>$generate_mobile,
				'multi_group'=>$multi_group,
				'custom_parameters'=>$custom_parameters,
				'generate_weapp_webview'=>$generate_weapp_webview,
				'we_app_web_view_short_url'=>$we_app_web_view_short_url,
				'we_app_web_view_url'=>$we_app_web_view_url,
				'channel_type'=>$channel_type
		);
		$resp = $c->execute($req);
		$result=json_decode($resp,true);
		return $result;
	}
	
	/**
	 * 查询订单列表
	 * @param long $start_update_time:必填，最近24小时内多多进宝商品订单更新时间--查询时间开始。note：此时间为时间戳，指格林威治时间 1970 年01 月 01 日 00 时 00 分 00 秒(北京时间 1970 年 01 月 01 日 08 时 00 分 00 秒)起至现在的总秒数
	 * @param long $end_update_time:必填，最近24小时内多多进宝商品订单更新时间--查询时间结束。note：此时间为时间戳，指格林威治时间 1970 年01 月 01 日 00 时 00 分 00 秒(北京时间 1970 年 01 月 01 日 08 时 00 分 00 秒)起至现在的总秒数
	 * @param number $page_size:非必填，返回的每页结果订单数，默认为100，范围为10到100，建议使用40~50，可以提高成功率，减少超时数量。
	 * @param number $page:非必填，第几页，从1到10000，默认1，注：使用最后更新时间范围增量同步时，必须采用倒序的分页方式（从最后一页往回取）才能避免漏单问题。
	 * @param number $return_count:非必填，是否返回总数，默认为true，如果指定false, 则返回的结果中不包含总记录数，通过此种方式获取增量数据，效率在原有的基础上有80%的提升。
	 * @return mixed
	 */
	public function getOrderList($start_update_time,$end_update_time,$page_size=100,$page=1,$return_count='true')
	{
		$c = new PddClient();
		$c->type = 'pdd.ddk.order.list.increment.get';
		$req=array(
				'start_update_time'=>$start_update_time,
				'end_update_time'=>$end_update_time,
				'page_size'=>$page_size,
				'page'=>$page,
				'return_count'=>$return_count,
		);
		$resp = $c->execute($req);
		$result=json_decode($resp,true);
		if($result['error_response']['error_code'])
		{
			$res=array(
					'code'=>$result['error_response']['error_code'],
					'msg'=>$result['error_response']['error_msg'],
					'data'=>array()
			);
		}else {
			$order_list=array();
			//获取订单详情
			foreach ($result['order_list_get_response']['order_list'] as $l)
			{
				$res_detail=$this->getOrderDetail($l['order_sn']);
				$order_list[]=$res_detail['data']['order_detail'];
			}
			$data=array(
					'order_list'=>$order_list
			);
			$res=array(
					'code'=>0,
					'msg'=>'成功',
					'data'=>$data
			);
		}
		return $res;
	}
	
	/**
	 * 查询订单详情
	 * @param number $offset:非必填，从多少位置开始请求；默认值 ： 0
	 * @param number $limit:非必填，请求数量；默认值 ： 400
	 * @param number $channel_type:非必填，频道类型；0, "1.9包邮", 1, "今日爆款", 2, "品牌清仓", 3, "默认商城", 非必填 ,默认是1
	 * @return mixed
	 */
	public function getOrderDetail($order_sn)
	{
		$c = new PddClient();
		$c->type = 'pdd.ddk.order.detail.get';
		$req=array(
				'order_sn'=>$order_sn
		);
		$resp = $c->execute($req);
		$result=json_decode($resp,true);
		if($result['error_response']['error_code'])
		{
			$res=array(
					'code'=>$result['error_response']['error_code'],
					'msg'=>$result['error_response']['error_msg'],
					'data'=>array()
			);
		}else {
			$data=array(
					'order_detail'=>$result['order_detail_response']
			);
			$res=array(
					'code'=>0,
					'msg'=>'成功',
					'data'=>$data
			);
		}
		return $res;
	}
}
?>