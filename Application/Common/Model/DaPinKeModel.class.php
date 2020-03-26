<?php
/**
 * 大拼客API接口
 * 接口文档地址：https://www.dapinke.com/open-api/index
 */
namespace Common\Model;
use Think\Model;

class DaPinKeModel
{
    protected $app_key='Fsjq9i9I_H7k';
    protected $apiUrl='https://openapi.dapinke.com';
    
    /**
     * 商品列表获取
     * @param int $categoryId:分类ID
     * @param int $pdd_ddjb_category_id
     * @param int $page:分页
     * @param int $pageSize:分页数（1-100）
     * @param float $maxPrice:最大价格（元）
     * @param float $minPrice:最小价格（元）
     * @param float $couponPrice:券面额（元）
     * @param float $commision:佣金比例（百分比）
     * @param int $saleCount:月销量
     * @param int $announce_time:预告时间 （时间戳格式）
     */
    public function getGoodsList($categoryId=0,$pdd_ddjb_category_id=0,$page=1,$pageSize=20,$maxPrice='',$minPrice='',$couponPrice='',$commision='',$saleCount='',$announce_time='')
    {
        $url=$this->apiUrl.'/goods/index';
        $data=array(
            'app_key'=>$this->app_key,
            'categoryId'=>$categoryId,
            'pdd_ddjb_category_id'=>$pdd_ddjb_category_id,
            'page'=>$page,
            'pageSize'=>$pageSize,
            'maxPrice'=>$maxPrice,
            'minPrice'=>$minPrice,
            'couponPrice'=>$couponPrice,
            'commision'=>$commision,
            'saleCount'=>$saleCount,
            'announce_time'=>$announce_time
        );
        $res_json=https_request($url,$data);
        $res=json_decode($res_json,true);
        return $res;
    }
    
    /**
     * 2小时销量排行榜
     * @param number $page:分页
     * @param number $pageSize:分页数（1-100）
     */
    public function rankTwoHour($page=1,$pageSize=100)
    {
        $url=$this->apiUrl.'/goods/rank-hour-two';
        $data=array(
            'app_key'=>$this->app_key,
            'page'=>$page,
            'pageSize'=>$pageSize
        );
        $res_json=https_request($url,$data);
        $res=json_decode($res_json,true);
        return $res;
    }
    
    /**
     * 24小时销量排行榜
     * @param number $page:分页
     * @param number $pageSize:分页数（1-100）
     */
    public function rankDay($page=1,$pageSize=100)
    {
        $url=$this->apiUrl.'/goods/rank-day';
        $data=array(
            'app_key'=>$this->app_key,
            'page'=>$page,
            'pageSize'=>$pageSize
        );
        $res_json=https_request($url,$data);
        $res=json_decode($res_json,true);
        return $res;
    }
    
    /**
     * 商品关键词搜索
     * @param string $keyword:关键词
     * @param number $page:分页
     * @param number $pageSize:分页数（1-100）
     * @param string $sort:（默认综合排序），sale:销量倒序、commision:佣金比例倒序、priceDesc:价格倒序、priceAsc:价格升序 
     */
    public function searchKeyword($keyword,$page=1,$pageSize=100,$sort='')
    {
        $url=$this->apiUrl.'/goods/search-keyword';
        $data=array(
            'app_key'=>$this->app_key,
            'keyword'=>$keyword,
            'page'=>$page,
            'pageSize'=>$pageSize,
            'sort'=>$sort
        );
        $res_json=https_request($url,$data);
        $res=json_decode($res_json,true);
        return $res;
    }
    
    /**
     * 高佣金排行榜
     * @param number $page:分页
     * @param number $pageSize:分页数（1-100）
     */
    public function rankCommision($page=1,$pageSize=100)
    {
        $url=$this->apiUrl.'/goods/rank-commision';
        $data=array(
            'app_key'=>$this->app_key,
            'page'=>$page,
            'pageSize'=>$pageSize
        );
        $res_json=https_request($url,$data);
        $res=json_decode($res_json,true);
        return $res;
    }
    
    /**
     * 精编商品
     * @param number $page:分页
     * @param number $pageSize:分页数（1-100）
     */
    public function getGoodsDiscovery($page=1,$pageSize=100)
    {
        $url=$this->apiUrl.'/goods/get-goods-discovery';
        $data=array(
            'app_key'=>$this->app_key,
            'page'=>$page,
            'pageSize'=>$pageSize
        );
        $res_json=https_request($url,$data);
        $res=json_decode($res_json,true);
        return $res;
    }
    
    /**
     * 推荐商品
     * @param number $page:分页
     * @param number $pageSize:分页数（1-100）
     */
    public function commend($page=1,$pageSize=100)
    {
        $url=$this->apiUrl.'/goods/commend';
        $data=array(
            'app_key'=>$this->app_key,
            'page'=>$page,
            'pageSize'=>$pageSize
        );
        $res_json=https_request($url,$data);
        $res=json_decode($res_json,true);
        return $res;
    }
    
    /**
     * 商品详情
     * @param int $goods_id:拼多多商品id
     */
    public function detail($goods_id)
    {
        $url=$this->apiUrl.'/goods/detail';
        $data=array(
            'app_key'=>$this->app_key,
            'goods_id'=>$goods_id,
        );
        $res_json=https_request($url,$data);
        $res=json_decode($res_json,true);
        return $res;
    }
    
    /**
     * 限时精品
     * @param number $page:分页
     * @param number $pageSize:分页数（1-100）
     */
    public function getBoutique($page=1,$pageSize=100)
    {
        $url=$this->apiUrl.'/goods/get-boutique';
        $data=array(
            'app_key'=>$this->app_key,
            'page'=>$page,
            'pageSize'=>$pageSize
        );
        $res_json=https_request($url,$data);
        $res=json_decode($res_json,true);
        return $res;
    }
}