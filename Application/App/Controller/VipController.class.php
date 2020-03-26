<?php
/**
 * 唯品会商品管理接口
 */
namespace App\Controller;
use App\Common\Controller\AuthController;

class VipController extends AuthController
{
    /**
     * 获取顶级唯品会商品分类列表
     * @return array
     * @return @param code:返回码
     * @return @param msg:返回码说明
     * @return @param data:返回数据
     * @return @param data->list:顶级拼多多商品分类列表
     */
    public function getTopCatList()
    {
        $VipCat=new \Common\Model\VipCatModel();
        $list=$VipCat->getParentList('Y');
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
     * 类目接口用于用户根据分类ID获取节点树信息
     * @param int $category_id:非必填，值=0时为顶点cat_id,通过树顶级节点获取cat树
     * @return array
     * @return @param code:返回码
     * @return @param msg:返回码说明
     * @return @param data:返回数据
     */
    public function getVipGoodsCat()
    {
        if(trim(I('post.category_id')))
        {
            $category_id=trim(I('post.category_id'));
        }else {
            $category_id=0;
        }
        Vendor('vip.vip','','.class.php');
        $vip=new \vip();
        $res=$vip->getGoodsCat($category_id);
        //成功
        $data=array(
            'res'=>$res
        );
        $res=array(
            'list'=>$this->ERROR_CODE_COMMON['SUCCESS'],
            'msg'=>'成功',
            'data'=>$data
        );
        echo json_encode ($res,JSON_UNESCAPED_UNICODE);
    }

    /**
     * 获取唯品会联盟在推商品列表
     * @param string $token:用户身份令牌
     * @param integer $channel_type:必填，频道类型:3-超高佣，1-出单爆款，2-每日精选
     * @param integer $page:必填，页码
     * @param integer $page_size:分页大小:默认20，最大100
     * @param string $field_name:排序字段: COMMISSION-佣金，PRICE-价格,COMM_RATIO-佣金比例，DISCOUNT-折扣
     * @param integer $order:排序顺序：0-正序，1-逆序，默认正序
     * @return array
     * @return @param code:返回码
     * @return @param msg:返回码说明
     * @return @param data:返回数据
     * @return @param data:返回数据
     */
    public function getGoodsList()
    {
        //频道类型
        if(trim(I('post.channel_type')))
        {
            $channel_type=trim(I('post.channel_type'));
            if (trim(I('post.channel_type')) == 3){
                $channel_type=0;
            }
        }else{
            $channel_type=0;
        }
        //页码
        if(trim(I('post.page')))
        {
            $page=trim(I('post.page'));
        }
        //分页大小
        if(trim(I('post.page_size')))
        {
            $page_size=trim(I('post.page_size'));
        }
        //排序
        if(trim(I('post.field_name')))
        {
            $field_name=trim(I('post.field_name'));
        }
        //排序顺序
        if(trim(I('post.order')))
        {
            $order=trim(I('post.order'));
        }else {
            $order=1;
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
        Vendor('vip.vip','','.class.php');
        $vip=new \vip();
        $res_vip=$vip->getGoodsList($channel_type,$page,$page_size,$field_name,$order);
        if($res_vip['data']['list'])
        {
            $num=count($res_vip['data']['list']);
            for($i=0;$i<$num;$i++)
            {
                //根据会员组计算相应佣金
                //佣金
//                $price=$res_vip['goodsInfoList'][$i]['min_group_price']-$res_vip['goodsInfoList'][$i]['coupon_discount'];
                $commission=$res_vip['data']['list'][$i]['commission']*$fee_user/100;
                //保留2位小数，四舍五不入
                $res_vip['data']['list'][$i]['commission']=substr(sprintf("%.3f",$commission),0,-1);
            }
        }
        $res=$res_vip;
        echo json_encode ($res,JSON_UNESCAPED_UNICODE);
    }

    /**
     * 根据关键字查询唯品会联盟商品列表
     * @param string $token:用户身份令牌
     * @param string $keyword:必填，关键词
     * @param integer $page:必填，页码
     * @param integer $page_size:页面大小：默认20,最大50
     * @param string $field_name:排序字段: PRICE-价格,SALES-销量排序，DISCOUNT-折扣
     * @param integer $order:排序顺序：0-正序，1-逆序，默认正序
     * @param String $price_start:价格区间---start
     * @param String $price_end:价格区间---end
     * @return array
     * @return @param code:返回码
     * @return @param msg:返回码说明
     * @return @param data:返回数据
     * @return @param data:返回数据
     */
    public function getKeywordGoods()
    {
        //关键词
        if(trim(I('post.keyword')))
        {
            $keyword=trim(I('post.keyword'));
        }else{
            $keyword='随机';
        }
        //页码
        if(trim(I('post.page')))
        {
            $page=trim(I('post.page'));
        }else{
            $page=1;
        }
        //分页大小
        if(trim(I('post.page_size')))
        {
            $page_size=trim(I('post.page_size'));
        }else{
            $page_size=20;
        }
        //排序
        if(trim(I('post.field_name')))
        {
            $field_name=trim(I('post.field_name'));
        }else{
            $field_name='SALES';
        }
        //排序顺序
        if(trim(I('post.order')))
        {
            $order=trim(I('post.order'));
        }else{
            $order=1;
        }
        //价格区间---start
        if(trim(I('post.price_start')))
        {
            $price_start=trim(I('post.price_start'));
        }
        //价格区间---end
        if(trim(I('post.price_end')))
        {
            $price_end=trim(I('post.price_end'));
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

        //获取关键词商品
        Vendor('vip.vip','','.class.php');
        $vip=new \vip();
        $res_vip=$vip->getKeywordGoods($keyword,$page,$page_size,$field_name,$order,$price_start,$price_end);
        if($res_vip['data']['list'])
        {
            $num=count($res_vip['data']['list']);
            for($i=0;$i<$num;$i++)
            {
                //根据会员组计算相应佣金
                //佣金
//                $price=$res_vip['goodsInfoList'][$i]['min_group_price']-$res_vip['goodsInfoList'][$i]['coupon_discount'];
                $commission=$res_vip['data']['list'][$i]['commission']*$fee_user/100;
                //保留2位小数，四舍五不入
                $res_vip['data']['list'][$i]['commission']=substr(sprintf("%.3f",$commission),0,-1);
            }
        }
        $res=$res_vip;
        echo json_encode ($res,JSON_UNESCAPED_UNICODE);
    }

    /**
     * 获取唯品会联盟指定商品id集合的商品详情信息
     * @param string $token:用户身份令牌
     * @param array $goods_id:拼多多商品ID,可以多个一起
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
            $goods_ids = explode(",",$goods_id);
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
            Vendor('vip.vip','','.class.php');
            $vip=new \vip();
            $res_vip=$vip->getGoodsDetail($goods_ids);
            if($res_vip['code']==0)
            {
                if (isset($res_vip['data']['list'])){
                    $num=count($res_vip['data']['list']);
                    for ($i=0;$i<$num;$i++){
                        //根据会员组计算相应佣金
                        //佣金
//                      $price=$res_vip['data']['goods_details']['min_group_price']-$res_vip['data']['goods_details']['coupon_discount'];
                        $commission=$res_vip['data']['list'][$i]['commission']*$fee_user/100;
                        //VIP佣金
                        $res_vip['data']['list'][$i]['commission_vip']=$res_vip['data']['list'][$i]['commission']*0.9;
                        //保留2位小数，四舍五不入
                        $res_vip['data']['list'][$i]['commission_vip']=substr(sprintf("%.3f",$res_vip['data']['list'][$i]['commission_vip']),0,-1);
                        //保留2位小数，四舍五不入
                        $res_vip['data']['list'][$i]['commission']=substr(sprintf("%.3f",$commission),0,-1);

                        //生成分享推广链接
                        $res_vip_url=$vip->goodsByGoodsIdGetUrl($goods_ids[$i],'share_'.$uid);
                        if($res_vip_url['code']==0)
                        {
                            $res_vip['data']['list'][$i]['share_url']=$res_vip_url['data']['url_list'];
                        }else {
                            $res_vip['data']['list'][$i]['share_url']=array();
                        }
                        //生成自购推广链接
                        $res_vip_url=$vip->goodsByGoodsIdGetUrl($goods_ids[$i],'zg_'.$uid);
                        if($res_vip_url['code']==0)
                        {
                            $res_vip['data']['list'][$i]['zg_url']=$res_vip_url['data']['url_list'];
                        }else {
                            $res_vip['data']['list'][$i]['zg_url']=array();
                        }
                    }
                }elseif (isset($res_vip['data']['goods_details'])){
                    //根据会员组计算相应佣金
                    //佣金
//                $price=$res_vip['data']['goods_details']['min_group_price']-$res_vip['data']['goods_details']['coupon_discount'];
                    $commission=$res_vip['data']['goods_details']['commission']*$fee_user/100;
                    //VIP佣金
                    $res_vip['data']['goods_details']['commission_vip']=$res_vip['data']['goods_details']['commission']*0.9;
                    //保留2位小数，四舍五不入
                    $res_vip['data']['goods_details']['commission_vip']=substr(sprintf("%.3f",$res_vip['data']['goods_details']['commission_vip']),0,-1);
                    //保留2位小数，四舍五不入
                    $res_vip['data']['goods_details']['commission']=substr(sprintf("%.3f",$commission),0,-1);

                    //生成分享推广链接
                    $res_vip_url=$vip->goodsByGoodsIdGetUrl($goods_id,'share_'.$uid);
                    if($res_vip_url['code']==0)
                    {
                        $res_vip['data']['goods_details']['share_url']=$res_vip_url['data']['url_list'];
                    }else {
                        $res_vip['data']['goods_details']['share_url']=array();
                    }
                    //生成自购推广链接
                    $res_vip_url=$vip->goodsByGoodsIdGetUrl($goods_id,'zg_'.$uid);
                    if($res_vip_url['code']==0)
                    {
                        $res_vip['data']['goods_details']['zg_url']=$res_vip_url['data']['url_list'];
                    }else {
                        $res_vip['data']['goods_details']['zg_url']=array();
                    }
                }
            }
            $res=$res_vip;
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