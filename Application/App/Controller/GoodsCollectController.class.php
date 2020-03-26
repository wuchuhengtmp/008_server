<?php
/**
 * 淘宝收藏商品管理接口
 */
namespace App\Controller;
use App\Common\Controller\AuthController;

class GoodsCollectController extends AuthController
{
    /**
     * 收藏商品
     * @param string $token:用户身份令牌
     * @param int $goods_id:淘宝商品ID
     * @return array
     * @return @param code:返回码
     * @return @param msg:返回码说明
     */
    public function collect()
    {
        if(I('post.token') and I('post.goods_id'))
        {
            //判断用户身份
            $token=trim(I('post.token'));
            $User=new \Common\Model\UserModel();
            $res_token=$User->checkToken($token);
            if($res_token['code']!=0)
            {
                //用户身份不合法
                $res=$res_token;
            }else {
                $uid=$res_token['uid'];
                //判断商品是否存在
                $goods_id=trim(I('post.goods_id'));
                //判断是否已收藏该商品
                $TbGoodsCollect=new \Common\Model\TbGoodsCollectModel();
                $res_c=$TbGoodsCollect->where("goods_id='$goods_id' and user_id='$uid'")->find();
                if($res_c)
                {
                    //已收藏该商品，请勿重复收藏
                    $res=array(
                        'code'=>$this->ERROR_CODE_GOODS['GOODS_ALREADY_COLLECTED'],
                        'msg'=>$this->ERROR_CODE_GOODS_ZH[$this->ERROR_CODE_GOODS['GOODS_ALREADY_COLLECTED']]
                    );
                }else {
                    $data=array(
                        'goods_id'=>$goods_id,
                        'user_id'=>$uid,
                        'collect_time'=>date('Y-m-d H:i:s')
                    );
                    $res_add=$TbGoodsCollect->add($data);
                    if($res_add!==false)
                    {
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
     * 取消收藏
     * @param string $token:用户身份令牌
     * @param int $goods_id:商品ID
     * @return array
     * @return @param code:返回码
     * @return @param msg:返回码说明
     */
    public function cancelCollect()
    {
        if(I('post.token') and I('post.goods_id'))
        {
            //判断用户身份
            $token=trim(I('post.token'));
            $User=new \Common\Model\UserModel();
            $res_token=$User->checkToken($token);
            if($res_token['code']!=0)
            {
                //用户身份不合法
                $res=$res_token;
            }else {
                $uid=$res_token['uid'];
                $goods_id=trim(I('post.goods_id'));
                $TbGoodsCollect=new \Common\Model\TbGoodsCollectModel();
                $res_del=$TbGoodsCollect->where("goods_id='$goods_id' and user_id='$uid'")->delete();
                if($res_del)
                {
                    $res=array(
                        'code'=>$this->ERROR_CODE_COMMON['SUCCESS'],
                        'msg'=>'成功',
                    );
                }else {
                    //您尚未收藏该商品
                    $res=array(
                        'code'=>$this->ERROR_CODE_GOODS['GOODS_NOT_COLLECT'],
                        'msg'=>$this->ERROR_CODE_GOODS_ZH[$this->ERROR_CODE_GOODS['GOODS_NOT_COLLECT']]
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
     * 获取用户收藏商品列表
     * @param string $token:用户身份令牌
     * @return array
     * @return @param code:返回码
     * @return @param msg:返回码说明
     * @return @param data:返回数据
     * @return @param data->list:收藏商品列表
     */
    public function getCollectList()
    {
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
            }else {
                $uid=$res_token['uid'];
                $TbGoodsCollect=new \Common\Model\TbGoodsCollectModel();
                $goodslist=$TbGoodsCollect->where("user_id='$uid'")->select();
                if($goodslist!==false)
                {
                    $goods_allid='';
                    foreach ($goodslist as $l)
                    {
                        $goods_allid.=$l['goods_id'].',';
                    }
                    if($goods_allid)
                    {
                        $goods_allid=substr($goods_allid, 0,-1);
                        Vendor('tbk.tbk','','.class.php');
                        $tbk=new \tbk();
                        $ip=getIP();
                        $res=$tbk->getItemList($goods_allid,$platform='2',$ip);
                        if($res['code']==0)
                        {
                            //查询用户会员组
                            $userMsg=$User->getUserMsg($uid);
                            $UserGroup=new \Common\Model\UserGroupModel();
                            $groupMsg=$UserGroup->getGroupMsg($userMsg['group_id']);
                            $fee_user=$groupMsg['fee_user'];
                            
                            $list=$res['data']['list'];
                            $num=count($list);
                            for($i=0;$i<$num;$i++)
                            {
                                //根据会员组计算相应佣金
                                //佣金
                                $list[$i]['commission']=$list[$i]['commission']*$fee_user/100;
                                //保留2位小数，四舍五不入
                                $list[$i]['commission']=substr(sprintf("%.3f",$list[$i]['commission']),0,-1);
                            }
                            $data=array(
                                'list'=>$list
                            );
                            $res=array(
                                'code'=>$this->ERROR_CODE_COMMON['SUCCESS'],
                                'msg'=>'成功',
                                'data'=>$data
                            );
                            
                        }
                        
                    }else {
                        //没有收藏的商品
                        $list=array();
                        $data=array(
                            'list'=>$list
                        );
                        $res=array(
                            'code'=>$this->ERROR_CODE_COMMON['SUCCESS'],
                            'msg'=>'成功',
                            'data'=>$data
                        );
                    }
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
     * 用户是否收藏商品
     * @param string $token:用户身份令牌
     * @param int $goods_id:商品ID
     * @return array
     * @return @param code:返回码
     * @return @param msg:返回码说明
     * @return @param data:返回数据
     * @return @param data->is_collect:是否收藏 Y已收藏 N未收藏
     */
    public function is_collect()
    {
        if(I('post.token') and I('post.goods_id'))
        {
            //判断用户身份
            $token=trim(I('post.token'));
            $User=new \Common\Model\UserModel();
            $res_token=$User->checkToken($token);
            if($res_token['code']!=0)
            {
                //用户身份不合法
                $res=$res_token;
            }else {
                $uid=$res_token['uid'];
                //判断是否收藏
                $goods_id=trim(I('post.goods_id'));
                $TbGoodsCollect=new \Common\Model\TbGoodsCollectModel();
                $res_exist=$TbGoodsCollect->where("goods_id='$goods_id' and user_id='$uid'")->find();
                if($res_exist)
                {
                    $is_collect='Y';
                }else {
                    $is_collect='N';
                }
                $data=array(
                    'is_collect'=>$is_collect
                );
                $res=array(
                    'code'=>$this->ERROR_CODE_COMMON['SUCCESS'],
                    'msg'=>'成功',
                    'data'=>$data
                );
                
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