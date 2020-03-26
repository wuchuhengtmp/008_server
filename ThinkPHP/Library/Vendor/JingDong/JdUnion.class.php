<?php
/**
 * 京东联盟
 * 2018-11-20
 * @author 葛阳
 */
include "JdSdk.php";

class JdUnion
{
	protected $unionId= JD_UNIONID;//京东联盟id
	protected $appkey=ANDROID_APPKEY;//京东appkey
	protected $appSecret=ANDROID_APPSECRET;//京东appsecret
	public $serverUrl='https://router.jd.com/api';
	//protected $accessToken='dad7745d9b324f2d8a339d4c15917f8dknzc';
	//protected $authKey='ece3b6ab1c8b87a7d1dadf7af4826e33f12d2303eaee387322782fce23c72c2286129775921c7c61';
	//protected $auth_url='http://taobao.mjuapp.com/app.php/JdNotify/notify';
	
	/**
	 * 授权说明：https://jos.jd.com/doc/channel.htm?id=152
	 * Authorization Code方法授权
	 * 授权有效期说明：如果应用appkey状态为“在线测试”，授权token有效期只有24小时，“上线运行”状态的应用授权token有效期为一年。另如果是通用应用，需要先发布到服务市场，审核通过后方可授权获取token。
	 * client_id：即创建应用时的Appkey（从JOS控制台->管理应用中获取）
	 * redirect_uri：应用的回调地址，必须与创建应用时所填回调页面url一致：http://taobao.mjuapp.com/app.php/JdNotify/notify
	 * 获取授权码code网址：https://oauth.jd.com/oauth/authorize?response_type=code&client_id=B424D08CF2B503446D81DC01A8674DEB&redirect_uri=http://taobao.mjuapp.com/app.php/JdNotify/notify&state=1
	 * 获取code之后，调用该方法获取访问令牌Access token
	 */
	public function getAccessToken($code)
	{
		$url="https://oauth.jd.com/oauth/token?grant_type=authorization_code&client_id=$this->appkey&client_secret=$this->appSecret&scope=read&redirect_uri=$this->auth_url&code=$code&state=";
		$res_json=https_request($url);
		$res=json_decode($res_json,true);
		return $res;
	}
	/**
	 * 商品类目查询
	 * @param number $parent_id:父类目id(一级父类目为0) 
	 * @param number $grade:类目级别 0，1，2 代表一、二、三级类目 
	 */
	public function searchGoodsCategory($parent_id=0,$grade=0)
	{
		$c = new JdClient();
		$c->accessToken = $this->accessToken;
		$req = new UnionSearchGoodsCategoryQueryRequest();
		$req->setParentId($parent_id); 
		$req->setGrade($grade);
		$resp = $c->execute($req, $c->accessToken);
		$result=json_decode(json_encode($resp),true);
		if($result['code']==0)
		{
			$list=json_decode($result['querygoodscategory_result'],true);
			$data=array(
					'list'=>$list['data']
			);
			$res=array(
					'code'=>0,
					'msg'=>'成功',
					'data'=>$data
			);
		}else {
			$res=array(
					'code'=>$result['code'],
					'msg'=>$result['zh_desc'],
					'data'=>array()
			);
		}
		return $res;
	}
	
	/**
	 * 获取爆款商品
	 * @param number $from:起始条数（例如，每页100条，第一页0，第二页100)
	 * @param number $pageSize:每页多少条 
	 * @param string $cid3:三级类目
	 * @return Ambigous <unknown, mixed>
	 */
	public function queryExplosiveGoods($from=0,$pageSize=10,$cid3='')
	{
		$c = new JdClient();
		$c->accessToken = $this->accessToken;
		$req = new UnionThemeGoodsServiceQueryExplosiveGoodsRequest();
		$req->setFrom( $from ); 
		$req->setPageSize($pageSize);
		if($cid3)
		{
			$req->setCid3($cid3);
		}
		$resp = $c->execute($req, $c->accessToken);
		$result=json_decode(json_encode($resp),true);
		$res2=json_decode($result['queryExplosiveGoods_result'],true);
		if($res2['resultCode']==0)
		{
			$list=json_decode($result['queryExplosiveGoods_result'],true);
			$data=array(
					'list'=>$list
			);
			$res=array(
					'code'=>0,
					'msg'=>'成功',
					'data'=>$data
			);
		}else {
			$res=array(
					'code'=>$res2['resultCode'],
					'msg'=>$res2['resultMessage'],
					'data'=>array()
			);
		}
		return $res;
	}
	
	/**
	 * 关键词查询选品
	 * @param number $cat1Id:一级类目
	 * @param number $cat2Id:二级类目
	 * @param number $cat3Id:三级类目
	 * @param string $keyword:关键词
	 * @param number $page_index:页码
	 * @param number $page_size:每页数量
	 * @param string $sort_name:排序字段[pcPrice pc价],[pcCommission pc佣金],[pcCommissionShare pc佣金比例],[inOrderCount30Days 30天引入订单量],[inOrderComm30Days 30天支出佣金]
	 * @param string $sort:	asc,desc升降序,默认降序 
	 * @return Ambigous <unknown, mixed>
	 */
	public function searchGoods($cat1Id='',$cat2Id='',$cat3Id='',$keyword='',$page_index=1,$page_size=10,$sort_name='',$sort='desc')
	{
		$c = new JdClient();
		$c->accessToken = $this->accessToken;
		$req = new UnionSearchGoodsKeywordQueryRequest();
		if($cat1Id)
		{
			$req->setCat1Id($cat1Id);
		}
		if($cat2Id)
		{
			$req->setCat2Id($cat2Id);
		}
		if($cat3Id)
		{
			$req->setCat3Id($cat3Id);
		}
		if($keyword)
		{
			$req->setKeyword($keyword);
		}
		$req->setPageIndex($page_index); 
		$req->setPageSize($page_size);
		if($sort_name)
		{
			$req->setSortName($sort_name);
		}
		$req->setSort($sort);
		$resp = $c->execute($req, $c->accessToken);
		$result=json_decode(json_encode($resp),true);
		if($result['code']==0)
		{
			$list=json_decode($result['querygoodsbykeyword_result'],true);
			$data=array(
					'list'=>$list
			);
			$res=array(
					'code'=>0,
					'msg'=>'成功',
					'data'=>$data
			);
		}else {
			$res=array(
					'code'=>$result['code'],
					'msg'=>$result['zh_desc'],
					'data'=>array()
			);
		}
		return $res;
	}
	
	/**
	 * 获取推广商品信息
	 * @param string $accessToken:用户授权
	 * @param string $skuIds:京东skuID串，逗号分割，最多100个（非常重要 请大家关注：：：如果输入的sk串中某个skuID的商品不在推广中[就是没有佣金]，返回结果中不会包含这个商品的信息）
	 * @return Ambigous <unknown, mixed>
	 */
	public function getgoodsInfo($skuIds)
	{
		$c = new JdClient();
		$c->accessToken = $this->accessToken;
		$req = new ServicePromotionGoodsInfoRequest();
		$req->setSkuIds($skuIds);
		$resp = $c->execute($req, $c->accessToken);
		$result=json_decode(json_encode($resp),true);
		if($result['code']==0)
		{
			$list=json_decode($result['queryExplosiveGoods_result'],true);
			$data=array(
					'list'=>$list
			);
			$res=array(
					'code'=>0,
					'msg'=>'成功',
					'data'=>$data
			);
		}else {
			$res=array(
					'code'=>$result['code'],
					'msg'=>$result['zh_desc'],
					'data'=>array()
			);
		}
		return $res;
	}
	
	/**
	 * 订单查询
	 * @param string $time:查询时间 ，格式yyyyMMddHH:2018012316 (按数据更新时间查询) 
	 * @param number $pageIndex:页数，从1开始 
	 * @param number $pageSize:每页条数 ，上限500
	 * @return array
	 */
	public function queryOrderList($time,$pageIndex=1,$pageSize=500)
	{
		/* $c = new JdClient();
		$c->accessToken = $this->accessToken;
		$req = new UnionServiceQueryOrderListRequest();
		$req->setUnionId( $this->unionId ); 
		$req->setTime($time); 
		$req->setPageIndex($pageIndex); 
		$req->setPageSize($pageSize);
		$resp = $c->execute($req, $c->accessToken);
		$result=json_decode(json_encode($resp),true);
		$res_or=json_decode($result['result'],true); */
		
		$url="http://api.josapi.net/orders?authkey=$this->authKey&time=$time&page=$pageIndex&pagesize=$pageSize";
		$res_json=https_request($url);
		$result=json_decode($res_json,true);
		if($result['error']==0)
		{
			$data=array(
					'list'=>$result['data'],
					'hasMore'=>$result['hasmore']
			);
			$res=array(
					'code'=>0,
					'msg'=>'成功',
					'data'=>$data
			);
		}else {
			$res=array(
					'code'=>$result['error'],
					'msg'=>$result['msg'],
					'data'=>array()
			);
		}
		return $res;
	}
	
	/**
	 * 订单查询
	 * @param string $time:查询时间 ，格式yyyyMMddHH:2018012316 (按数据更新时间查询)
	 * @param number $pageIndex:页数，从1开始
	 * @param number $pageSize:每页条数 ，上限500
	 * @return array
	 */
	public function queryCommissionOrdersWithKey($time,$pageIndex=1,$pageSize=500)
	{
		$c = new JdClient();
		$c->accessToken = $this->accessToken;
		$req = new UnionServiceQueryCommissionOrdersWithKeyRequest();
		$req->setUnionId($this->unionId);
		$req->setKey( $this->authKey);
		$req->setTime($time);
		$req->setPageIndex($pageIndex);
		$req->setPageSize($pageSize);
		$resp = $c->execute($req, $c->accessToken);
		dump($resp);die();
		$result=json_decode(json_encode($resp),true);
		$res_or=json_decode($result['result'],true);
		//dump($res_or);die();
		if($res_or['success']==1)
		{
			$res_or=json_decode($result['result'],true);
			$data=array(
					'list'=>$res_or['data'],
					'hasMore'=>$result['result']['hasMore']
			);
			$res=array(
					'code'=>0,
					'msg'=>'成功',
					'data'=>$data
			);
		}else {
			$res=array(
					'code'=>1,
					'msg'=>$res_or['msg'],
					'data'=>array()
			);
		}
		return $res;
	}
	
	//新版联盟订单查询
	public function queryOpenOrders($time,$page,$pageSize){
		//$queryDate = '2019120915';
		$serverUrl='https://router.jd.com/api';
		$params_yewu = [
			'orderReq' =>[
				'time'=>$time,
				'pageNo'=>strval($page),
				'pageSize'=>strval($pageSize),
				'type'=>"1"
				]
			];
		$param_json =json_encode($params_yewu);
		$params = [
			'v'=>'1.0',
			'method'=>'jd.union.open.order.query',
			'access_token'=>'',
			'app_key'=>$this->appkey,
			'sign_method'=>'md5',
			'format'=>'json',
			'timestamp'=>date('Y-m-d H:i:s'),
			'param_json'=>$param_json
			];

		$params['sign'] = $this->genSign($params);
		
		$finalUrl=$this->serverUrl.'?'.http_build_query($params);
				$result = file_get_contents($finalUrl);
		$res = json_decode($result,true);
		
		return json_decode($res['jd_union_open_order_query_response']['result'],true);
	}
	public function genSign($params){
		ksort($params);
		$queryString = "";
		foreach($params as $k=>$val)
		{
			if(!empty($val)){
			$queryString.=$k.$val;
			}
		}
		
		$queryString = $this->appSecret.$queryString.$this->appSecret;
		
		//$md5str = $this->encodeHexString($this->md5Hex($queryString));

		$sign = strtoupper(md5($queryString));
		return $sign;
	}

}
?>