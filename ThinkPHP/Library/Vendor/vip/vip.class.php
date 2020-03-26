<?php
/**
 * 唯品会联盟
 * 2020-03-05
 * 只能使用POST请求进行API调用。
 * API调用除了必须包含公共参数外，如果API本身有业务级的参数也必须传入
 */

class vip
{
    protected $app_key=VIP_APPKEY;
    protected $app_secret=VIP_APPSECRET;
    // todo 临时使用，工具商权限需要用户授权完的token，渠道商不需要
    protected $access_token='742E8AD852B5DF558F86FF3683BA48F5C0C4E619';


    /**
     * 获取唯品会联盟类目,接口用于用户根据分类ID获取节点树信息
     * @param integer $category_id:必填，值=0时为顶点cat_id,通过树顶级节点获取cat树
     * @return mixed
     */
    public function getGoodsCat($category_id=0)
    {
        require_once "vipapis/category/CategoryServiceClient.php";
        $service=\vipapis\category\CategoryServiceClient::getService();
        $ctx=\Osp\Context\InvocationContextFactory::getInstance();
        $ctx->setAppKey($this->app_key);
        $ctx->setAppSecret($this->app_secret);
        $ctx->setAppURL("https://gw.vipapis.com/");
        $ctx->setLanguage("zh");
        $resp=$service->getCategoryTreeById($category_id);
        $result=json_decode(json_encode($resp),true);
        return $result;
    }

    /**
     * 获取唯品会联盟在推商品列表
     * @param integer $channelType:必填，频道类型:0-超高佣，1-出单爆款，2-每日精选; 当请求类型为频道时必传
     * @param integer $page:必填，页码
     * @param integer $pageSize:必填，分页大小:默认20，最大100
     * @param string $fieldName:排序字段: COMMISSION-佣金，PRICE-价格,COMM_RATIO-佣金比例，DISCOUNT-折扣
     * @param integer $order:排序顺序：0-正序，1-逆序，默认正序
     * @return mixed
     */
    public function getGoodsList($channelType=0,$page=1,$pageSize=20,$fieldName='COMMISSION',$order=0)
    {
        require_once "com/vip/adp/api/open/service/UnionGoodsServiceClient.php";
        $service=\com\vip\adp\api\open\service\UnionGoodsServiceClient::getService();
        $ctx=\Osp\Context\InvocationContextFactory::getInstance();
        $ctx->setAppKey($this->app_key);
        $ctx->setAppSecret($this->app_secret);
        $ctx->setAppURL("https://gw.vipapis.com/");
        $ctx->setLanguage("zh");
        // todo 临时测试使用工具商账号
        $ctx->setAccessToken($this->access_token);
        $request1=new \com\vip\adp\api\open\service\GoodsInfoRequest();
        $request1->channelType=$channelType;
        $request1->page=$page;
        $request1->pageSize=$pageSize;
        $request1->fieldName=$fieldName;
        $request1->order=$order;
        $request1->requestId="requestId";
        $request1->sourceType=0;
        // todo 临时测试使用工具商接口，后面换成$service->goodsList
        $resp=$service->goodsListWithOauth($request1);
        $result=json_decode(json_encode($resp),true);
        if(!$result)
        {
            $res=array(
                'code'=>1,
                'msg'=>'商品列表请求失败',
                'data'=>array()
            );
        }else {
            $data=array(
                'list'=>$result['goodsInfoList']
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
     * 根据关键字查询唯品会联盟商品列表
     * @param string $keyword:必填，关键词
     * @param integer $page:必填，页码
     * @param integer $page_size:页面大小，默认20,最大50
     * @param string $field_name:排序字段，PRICE-价格,SALES-销量排序，DISCOUNT-折扣
     * @param integer $order:排序顺序：0-正序，1-逆序，默认正序
     * @param String $price_start:价格区间---start
     * @param String $price_end:价格区间---end
     * @return mixed
     */
    public function getKeywordGoods($keyword,$page,$page_size,$field_name,$order,$price_start,$price_end)
    {
        require_once "com/vip/adp/api/open/service/UnionGoodsServiceClient.php";
        $service=\com\vip\adp\api\open\service\UnionGoodsServiceClient::getService();
        $ctx=\Osp\Context\InvocationContextFactory::getInstance();
        $ctx->setAppKey($this->app_key);
        $ctx->setAppSecret($this->app_secret);
        $ctx->setAppURL("https://gw.vipapis.com/");
        $ctx->setLanguage("zh");
        // todo 临时测试使用工具商账号
        $ctx->setAccessToken($this->access_token);
        $request1=new \com\vip\adp\api\open\service\QueryGoodsRequest();
        $request1->keyword=$keyword;
        $request1->page=$page;
        $request1->pageSize=$page_size;
        $request1->fieldName=$field_name;
        $request1->order=$order;
        $request1->requestId="requestId";
        if ($price_start){
            $request1->priceStart=$price_start;

        }
        if ($price_end){
        $request1->priceEnd=$price_end;

        }
        // todo 临时测试使用工具商接口，后面换成$service->query
        $resp=$service->queryWithOauth($request1);
        $result=json_decode(json_encode($resp),true);
        if(empty($result))
        {
            $res=array(
                'code'=>1,
                'msg'=>'关键词查询失败',
                'data'=>array()
            );
        }else {
            $data=array(
                'list'=>$result['goodsInfoList']
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
     * @param array $goods_ids:必填，商品ID
     * @return mixed
     */
    public function getGoodsDetail($goods_ids)
    {
        require_once "com/vip/adp/api/open/service/UnionGoodsServiceClient.php";
        $service=\com\vip\adp\api\open\service\UnionGoodsServiceClient::getService();
        $ctx=\Osp\Context\InvocationContextFactory::getInstance();
        $ctx->setAppKey($this->app_key);
        $ctx->setAppSecret($this->app_secret);
        $ctx->setAppURL("https://gw.vipapis.com/");
        $ctx->setLanguage("zh");
        // todo 临时测试使用工具商账号
        $ctx->setAccessToken($this->access_token);
//        $goodsIdList1=array();
//        $goodsIdList1[0]=$goods_id;
        // todo 临时测试使用工具商接口，后面换成$service->getByGoodsIds
        $resp = $service->getByGoodsIdsWithOauth($goods_ids,"requestId");
        $result=json_decode(json_encode($resp),true);
        if(empty($result))
        {
            $res=array(
                'code'=>1,
                'msg'=>'商品详情查询失败',
                'data'=>array()
            );
        }else {
            //唯品会收藏列表多个ID一起查询
            if (count($result)>1){
                $data=array(
                    'list'=>$result
                );
                $res=array(
                    'code'=>0,
                    'msg'=>'成功',
                    'data'=>$data
                );
            //唯品会单个商品ID查询
            }else{
                $data=array(
                    'goods_details'=>$result[0]
                );
                $res=array(
                    'code'=>0,
                    'msg'=>'成功',
                    'data'=>$data
                );
            }
        }
        return $res;
    }

    /**
     * 根据商品id生成唯品会联盟链接
     * @param integer $goods_id:必填，商品ID
     * @param string $user_tag:用户标示，用于找回订单，区分自购和分享
     * @return mixed
     */
    public function goodsByGoodsIdGetUrl($goods_id,$user_tag)
    {
        require_once "com/vip/adp/api/open/service/UnionUrlServiceClient.php";
        $service=\com\vip\adp\api\open\service\UnionUrlServiceClient::getService();
        $ctx=\Osp\Context\InvocationContextFactory::getInstance();
        $ctx->setAppKey($this->app_key);
        $ctx->setAppSecret($this->app_secret);
        $ctx->setAppURL("https://gw.vipapis.com/");
        $ctx->setLanguage("zh");
        // todo 临时测试使用工具商账号
        $ctx->setAccessToken($this->access_token);
        $goodsIdList1=array();
        $goodsIdList1[0]=$goods_id;
        // todo 临时测试使用工具商接口，后面换成$service->genByGoodsId
        $resp=$service->genByGoodsIdWithOauth($goodsIdList1,$user_tag,"requestId");
        $result=json_decode(json_encode($resp),true);
        if(empty($result))
        {
            $res=array(
                'code'=>1,
                'msg'=>'链接查询失败',
                'data'=>array()
            );
        }else {
            $data=array(
                'url_list'=>$result['urlInfoList'][0]['url']
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
     * 查询订单列表
//     * @param long $orderTimeStart:必填，订单时间起始 时间戳 单位毫秒
//     * @param long $orderTimeEnd:必填，订单时间结束 时间戳 单位毫秒
     * @param Integer $pageSize:页面大小：默认20
     * @param Integer $page:必填，页码：从1开始
     * @param Integer $updateTimeStart:更新时间-起始 时间戳 单位毫秒
     * @param Integer $updateTimeEnd:更新时间-结束 时间戳 单位毫秒
//     * @param string $orderSnList:订单号列表：当传入订单号列表时，订单时间和更新时间区间可不传入 不能用
     * @return mixed
     */
    public function getOrderList($pageSize,$page,$updateTimeStart,$updateTimeEnd)
    {
        require_once "com/vip/adp/api/open/service/UnionOrderServiceClient.php";
        $service=\com\vip\adp\api\open\service\UnionOrderServiceClient::getService();
        $ctx=\Osp\Context\InvocationContextFactory::getInstance();
        $ctx->setAppKey($this->app_key);
        $ctx->setAppSecret($this->app_secret);
        $ctx->setAppURL("https://gw.vipapis.com/");
        $ctx->setLanguage("zh");
        // todo 临时测试使用工具商账号
        $ctx->setAccessToken($this->access_token);
        $queryModel1=new \com\vip\adp\api\open\service\OrderQueryModel();
//        $queryModel1->orderTimeStart=$orderTimeStart;
//        $queryModel1->orderTimeEnd=$orderTimeEnd;
        $queryModel1->page=$page;
        $queryModel1->pageSize=$pageSize;
        $queryModel1->requestId="requestId";
        $queryModel1->updateTimeStart=$updateTimeStart*1000;
        $queryModel1->updateTimeEnd=$updateTimeEnd*1000;
//        if ($orderSnList){
//            $orderSnList2=array();
//            $orderSnList2[0]=$orderSnList;
//            $queryModel1->orderSnList=$orderSnList2;
//        }

        // todo 临时测试使用工具商接口，后面换成$service->orderList
        $resp=$service->orderListWithOauth($queryModel1);
        $result=json_decode(json_encode($resp),true);
        if(empty($result))
        {
            $res=array(
                'code'=>1,
                'msg'=>'订单查询失败',
                'data'=>array()
            );
        }else {
            $data=array(
                'order_list'=>$result['orderInfoList']
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