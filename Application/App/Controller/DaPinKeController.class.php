<?php
/**
 * 大拼客拼多多商品管理接口
 */
namespace App\Controller;
use App\Common\Controller\AuthController;

class DaPinKeController extends AuthController
{
    /**
     * 2小时销量排行榜
     * @param number $page:分页
     * @param number $pageSize:分页数（1-100）
     */
    public function rankTwoHour()
    {
        $page=1;
        if( trim(I('post.page'))){
            $page=trim(I('post.page'));
        }
        $pageSize=100;
        if( trim(I('post.pageSize'))){
            $pageSize=trim(I('post.pageSize'));
        }
        $DaPinKe=new \Common\Model\DaPinKeModel();
        $res=$DaPinKe->rankTwoHour($page,$pageSize);
        echo json_encode ($res,JSON_UNESCAPED_UNICODE);
    }
    
    /**
     * 24小时销量排行榜
     * @param number $page:分页
     * @param number $pageSize:分页数（1-100）
     */
    public function rankDay()
    {
        $page=1;
        if( trim(I('post.page'))){
            $page=trim(I('post.page'));
        }
        $pageSize=100;
        if( trim(I('post.pageSize'))){
            $pageSize=trim(I('post.pageSize'));
        }
        $DaPinKe=new \Common\Model\DaPinKeModel();
        $res=$DaPinKe->rankDay($page,$pageSize);
        echo json_encode ($res,JSON_UNESCAPED_UNICODE);
    }
    
    /**
     * 高佣金排行榜
     * @param number $page:分页
     * @param number $pageSize:分页数（1-100）
     */
    public function rankCommision()
    {
        $page=1;
        if( trim(I('post.page'))){
            $page=trim(I('post.page'));
        }
        $pageSize=100;
        if( trim(I('post.pageSize'))){
            $pageSize=trim(I('post.pageSize'));
        }
        $DaPinKe=new \Common\Model\DaPinKeModel();
        $res=$DaPinKe->rankCommision($page,$pageSize);
        echo json_encode ($res,JSON_UNESCAPED_UNICODE);
    }
    
    /**
     * 精编商品
     * @param number $page:分页
     * @param number $pageSize:分页数（1-100）
     */
    public function getGoodsDiscovery()
    {
        $page=1;
        if( trim(I('post.page'))){
            $page=trim(I('post.page'));
        }
        $pageSize=100;
        if( trim(I('post.pageSize'))){
            $pageSize=trim(I('post.pageSize'));
        }
        $DaPinKe=new \Common\Model\DaPinKeModel();
        $res=$DaPinKe->getGoodsDiscovery($page,$pageSize);
        echo json_encode ($res,JSON_UNESCAPED_UNICODE);
    }
    
    /**
     * 推荐商品
     * @param number $page:分页
     * @param number $pageSize:分页数（1-100）
     */
    public function commend()
    {
        $page=1;
        if( trim(I('post.page'))){
            $page=trim(I('post.page'));
        }
        $pageSize=100;
        if( trim(I('post.pageSize'))){
            $pageSize=trim(I('post.pageSize'));
        }
        $DaPinKe=new \Common\Model\DaPinKeModel();
        $res=$DaPinKe->commend($page,$pageSize);
        echo json_encode ($res,JSON_UNESCAPED_UNICODE);
    }
    
    /**
     * 限时精品
     * @param number $page:分页
     * @param number $pageSize:分页数（1-100）
     */
    public function getBoutique()
    {
        $page=1;
        if( trim(I('post.page'))){
            $page=trim(I('post.page'));
        }
        $pageSize=100;
        if( trim(I('post.pageSize'))){
            $pageSize=trim(I('post.pageSize'));
        }
        $DaPinKe=new \Common\Model\DaPinKeModel();
        $res=$DaPinKe->getBoutique($page,$pageSize);
        echo json_encode ($res,JSON_UNESCAPED_UNICODE);
    }
    
    /**
     * 获取商品详情
     * @param int $goods_id:拼多多商品id
     */
    public function detail()
    {
        if( trim(I('post.goods_id'))){
            $goods_id=trim(I('post.goods_id'));
            $DaPinKe=new \Common\Model\DaPinKeModel();
            $res=$DaPinKe->detail($goods_id);
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