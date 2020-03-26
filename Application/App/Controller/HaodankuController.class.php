<?php
/**
 * 好单库淘宝商品管理接口
 */
namespace App\Controller;
use App\Common\Controller\AuthController;

class HaodankuController extends AuthController
{
    /**
     * 获取商品类目列表
     * @return array
     */
    public function getCidList()
    {
        $Haodanku=new \Common\Model\HaodankuModel();
        $res=$Haodanku->getCidList();
        echo json_encode ($res,JSON_UNESCAPED_UNICODE);
    }
    
    /**
     * 获取商品列表
     * @param integer $nav:必填，默认全部商品（1实时跑单商品，2爆单榜商品，3全部商品，4纯视频单，5聚淘专区）
     * @param integer $cid:非必填，商品类目：0全部，1女装，2男装，3内衣，4美妆，5配饰，6鞋品，7箱包，8儿童，9母婴，10居家，11美食，12数码，13家电，14其他，15车品，16文体，17宠物（支持多类目筛选，如1,2获取类目为女装、男装的商品，逗号仅限英文逗号）
     * @param integer $back:非必填，默认10，每页返回条数（请在1,2,10,20,50,100,120,200,500,1000中选择一个数值返回）
     * @param integer $min_id:非必填，默认1，分页，用于实现类似分页抓取效果，来源于上次获取后的数据的min_id值，默认开始请求值为1（该方案比单纯123分页的优势在于：数据更新的情况下保证不会重复也无需关注和计算页数）
     * @param integer $sort:非必填，0.综合（最新），1.券后价(低到高)，2.券后价（高到低），3.券面额（高到低），4.月销量（高到低），5.佣金比例（高到低），6.券面额（低到高），7.月销量（低到高），8.佣金比例（低到高），9.全天销量（高到低），10全天销量（低到高），11.近2小时销量（高到低），12.近2小时销量（低到高），13.优惠券领取量（高到低）注意：该排序仅对nav=3，4，5有效，1，2无效
     * @param integer $price_min:非必填，券后价筛选，筛选大于等于所设置的券后价的商品
     * @param integer $price_max:非必填，券后价筛选，筛选小于等于所设置的券后价的商品
     * @param integer $sale_min:非必填，销量筛选，筛选大于等于所设置的销量的商品
     * @param integer $sale_max:非必填，销量筛选，筛选小于等于所设置的销量的商品
     * @param integer $coupon_min:非必填，券金额筛选，筛选大于等于所设置的券金额的商品
     * @param integer $coupon_max:非必填，券金额筛选，筛选小于等于所设置的券金额的商品
     * @param integer $tkrates_min:非必填，佣金比例筛选，筛选大于等于所设置的佣金比例的商品
     * @param integer $tkrates_max:非必填，佣金比例筛选，筛选小于所设置的佣金比例的商品
     * @param integer $tkmoney_min:非必填，佣金筛选，筛选大于等于所设置的佣金的商品
     * @param string $item_type:非必填，是否只获取营销返利商品
     * @return
     * @return integer code:状态码（1成功，0失败或没有数据返回）
     * @return integer min_id:作为请求地址中获取下一页的参数值
     * @return string msg:返回信息说明，SUCCESS代表成功获取，失败则有具体原因
     * @return integer data:返回数据
     * @return integer data->product_id:自增ID
     * @return integer data->itemid:宝贝ID
     * @return string data->itemtitle:宝贝标题
     * @return string data->itemshorttitle:宝贝短标题
     * @return string data->itemdesc:宝贝推荐语
     * @return float data->itemprice:在售价
     * @return string data->itemsale:宝贝月销量
     * @return string data->itemsale2:宝贝近2小时跑单
     * @return string data->todaysale:当天销量
     * @return string data->itempic:宝贝主图原始图像（由于图片原图过大影响加载速度，建议加上后缀_310x310.jpg，如https://img.alicdn.com/imgextra/i2/3412518427/TB26gs7bb7U5uJjSZFFXXaYHpXa_!!3412518427.jpg_310x310.jpg）
     * @return string data->itempic_copy:推广长图（带http://img.haodanku.com/0_553757100845_1509175123.jpg-600进行访问）
     * @return integer data->fqcat:商品类目：1女装，2男装，3内衣，4美妆，5配饰，6鞋品，7箱包，8儿童，9母婴，10居家，11美食，12数码，13家电，14其他，15车品，16文体，17宠物
     * @return float data->itemendprice:宝贝券后价
     * @return string data->shoptype:店铺类型：天猫店（B）淘宝店（C）
     * @return string data->couponurl:优惠券链接
     * @return float data->couponmoney:优惠券金额
     * @return integer data->is_brand:是否为品牌产品（1是）
     * @return integer data->is_live:是否为直播（1是）
     * @return string data->guide_article:推广导购文案
     * @return integer data->videoid:商品视频ID（id大于0的为有视频单，视频拼接地址http://cloud.video.taobao.com/play/u/1/p/1/e/6/t/1/+videoid+.mp4）
     * @return string data->activity_type:活动类型：普通活动 聚划算 淘抢购
     * @return string data->planlink:营销计划链接
     * @return integer data->userid:店主的userid
     * @return string data->sellernick:店铺掌柜名
     * @return string data->shopname:店铺名
     * @return string data->tktype:佣金计划：隐藏 营销
     * @return float data->tkrates:佣金比例
     * @return integer data->cuntao:是否村淘（1是）
     * @return float data->tkmoney:预计可得（宝贝价格 * 佣金比例 / 100）
     * @return integer data->couponreceive2:当天优惠券领取量
     * @return integer data->couponsurplus:优惠券剩余量
     * @return integer data->couponnum:优惠券总数量
     * @return string data->couponexplain:优惠券使用条件
     * @return integer data->couponstarttime:优惠券开始时间
     * @return integer data->couponendtime:优惠券结束时间
     * @return integer data->start_time:活动开始时间
     * @return integer data->end_time:活动结束时间
     * @return integer data->starttime:发布时间
     * @return integer data->report_status:举报处理条件0未举报1为待处理2为忽略3为下架
     * @return integer data->general_index:好单指数
     * @return string data->seller_name:放单人名号
     * @return float data->discount:折扣力度
     */
    public function getItemList()
    {
        if( trim(I('post.nav')) ){
            $nav=trim(I('post.nav'));
            $cid=0;
            if( trim(I('post.cid')) ){
                $cid=trim(I('post.cid'));
            }
            $back=10;
            if( trim(I('post.back')) ){
                $back=trim(I('post.back'));
            }
            $min_id=1;
            if( trim(I('post.min_id')) ){
                $min_id=trim(I('post.min_id'));
            }
            $sort=0;
            if( trim(I('post.sort')) ){
                $sort=trim(I('post.sort'));
            }
            $price_min='';
            if( trim(I('post.price_min')) ){
                $price_min=trim(I('post.price_min'));
            }
            $price_max='';
            if( trim(I('post.price_max')) ){
                $price_max=trim(I('post.price_max'));
            }
            $sale_min='';
            if( trim(I('post.sale_min')) ){
                $sale_min=trim(I('post.sale_min'));
            }
            $sale_max='';
            if( trim(I('post.sale_max')) ){
                $sale_max=trim(I('post.sale_max'));
            }
            $coupon_min='';
            if( trim(I('post.coupon_min')) ){
                $coupon_min=trim(I('post.coupon_min'));
            }
            $coupon_max='';
            if( trim(I('post.coupon_max')) ){
                $coupon_max=trim(I('post.coupon_max'));
            }
            $tkrates_min='';
            if( trim(I('post.tkrates_min')) ){
                $tkrates_min=trim(I('post.tkrates_min'));
            }
            $tkrates_max='';
            if( trim(I('post.tkrates_max')) ){
                $tkrates_max=trim(I('post.tkrates_max'));
            }
            $tkmoney_min='';
            if( trim(I('post.tkmoney_min')) ){
                $tkmoney_min=trim(I('post.tkmoney_min'));
            }
            $item_type='';
            if( trim(I('post.item_type')) ){
                $item_type=trim(I('post.item_type'));
            }
            $Haodanku=new \Common\Model\HaodankuModel();
            $res=$Haodanku->getItemList($nav,$cid,$back,$min_id,$sort,$price_min,$price_max,$sale_min,$sale_max,$coupon_min,$coupon_max,$tkrates_min,$tkrates_max,$tkmoney_min,$item_type);
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
     * 获取商品列表
     * @param integer $type:必填，商品筛选类型：type=1是今日上新（当天新券商品），type=2是9.9包邮，type=3是30元封顶，type=4是聚划算，type=5是淘抢购，type=6是0点过夜单，type=7是预告单，type=8是品牌单，type=9是天猫商品，type=10是视频单
     * @param integer $cid:非必填，商品类目：0全部，1女装，2男装，3内衣，4美妆，5配饰，6鞋品，7箱包，8儿童，9母婴，10居家，11美食，12数码，13家电，14其他，15车品，16文体，17宠物（支持多类目筛选，如1,2获取类目为女装、男装的商品，逗号仅限英文逗号）
     * @param integer $back:非必填，默认10，每页返回条数（请在1,2,10,20,50,100,120,200,500,1000中选择一个数值返回）
     * @param integer $min_id:非必填，默认1，分页，用于实现类似分页抓取效果，来源于上次获取后的数据的min_id值，默认开始请求值为1（该方案比单纯123分页的优势在于：数据更新的情况下保证不会重复也无需关注和计算页数）
     * @param integer $sort:非必填，0.综合（最新），1.券后价(低到高)，2.券后价（高到低），3.券面额（高到低），4.月销量（高到低），5.佣金比例（高到低），6.券面额（低到高），7.月销量（低到高），8.佣金比例（低到高），9.全天销量（高到低），10全天销量（低到高），11.近2小时销量（高到低），12.近2小时销量（低到高），13.优惠券领取量（高到低）注意：该排序仅对nav=3，4，5有效，1，2无效
     * @param integer $price_min:非必填，券后价筛选，筛选大于等于所设置的券后价的商品
     * @param integer $price_max:非必填，券后价筛选，筛选小于等于所设置的券后价的商品
     * @param integer $sale_min:非必填，销量筛选，筛选大于等于所设置的销量的商品
     * @param integer $sale_max:非必填，销量筛选，筛选小于等于所设置的销量的商品
     * @param integer $coupon_min:非必填，券金额筛选，筛选大于等于所设置的券金额的商品
     * @param integer $coupon_max:非必填，券金额筛选，筛选小于等于所设置的券金额的商品
     * @param integer $tkrates_min:非必填，佣金比例筛选，筛选大于等于所设置的佣金比例的商品
     * @param integer $tkrates_max:非必填，佣金比例筛选，筛选小于所设置的佣金比例的商品
     * @param integer $tkmoney_min:非必填，佣金筛选，筛选大于等于所设置的佣金的商品
     * @param string $item_type:非必填，是否只获取营销返利商品
     * @return
     * @return integer code:状态码（1成功，0失败或没有数据返回）
     * @return integer min_id:作为请求地址中获取下一页的参数值
     * @return string msg:返回信息说明，SUCCESS代表成功获取，失败则有具体原因
     * @return integer data:返回数据
     * @return integer data->product_id:自增ID
     * @return integer data->itemid:宝贝ID
     * @return string data->itemtitle:宝贝标题
     * @return string data->itemshorttitle:宝贝短标题
     * @return string data->itemdesc:宝贝推荐语
     * @return float data->itemprice:在售价
     * @return string data->itemsale:宝贝月销量
     * @return string data->itemsale2:宝贝近2小时跑单
     * @return string data->todaysale:当天销量
     * @return string data->itempic:宝贝主图原始图像（由于图片原图过大影响加载速度，建议加上后缀_310x310.jpg，如https://img.alicdn.com/imgextra/i2/3412518427/TB26gs7bb7U5uJjSZFFXXaYHpXa_!!3412518427.jpg_310x310.jpg）
     * @return string data->itempic_copy:推广长图（带http://img.haodanku.com/0_553757100845_1509175123.jpg-600进行访问）
     * @return integer data->fqcat:商品类目：1女装，2男装，3内衣，4美妆，5配饰，6鞋品，7箱包，8儿童，9母婴，10居家，11美食，12数码，13家电，14其他，15车品，16文体，17宠物
     * @return float data->itemendprice:宝贝券后价
     * @return string data->shoptype:店铺类型：天猫店（B）淘宝店（C）
     * @return string data->couponurl:优惠券链接
     * @return float data->couponmoney:优惠券金额
     * @return integer data->is_brand:是否为品牌产品（1是）
     * @return integer data->is_live:是否为直播（1是）
     * @return string data->guide_article:推广导购文案
     * @return integer data->videoid:商品视频ID（id大于0的为有视频单，视频拼接地址http://cloud.video.taobao.com/play/u/1/p/1/e/6/t/1/+videoid+.mp4）
     * @return string data->activity_type:活动类型：普通活动 聚划算 淘抢购
     * @return string data->planlink:营销计划链接
     * @return integer data->userid:店主的userid
     * @return string data->sellernick:店铺掌柜名
     * @return string data->shopname:店铺名
     * @return string data->tktype:佣金计划：隐藏 营销
     * @return float data->tkrates:佣金比例
     * @return integer data->cuntao:是否村淘（1是）
     * @return float data->tkmoney:预计可得（宝贝价格 * 佣金比例 / 100）
     * @return integer data->couponreceive2:当天优惠券领取量
     * @return integer data->couponsurplus:优惠券剩余量
     * @return integer data->couponnum:优惠券总数量
     * @return string data->couponexplain:优惠券使用条件
     * @return integer data->couponstarttime:优惠券开始时间
     * @return integer data->couponendtime:优惠券结束时间
     * @return integer data->start_time:活动开始时间
     * @return integer data->end_time:活动结束时间
     * @return integer data->starttime:发布时间
     * @return integer data->report_status:举报处理条件0未举报1为待处理2为忽略3为下架
     * @return integer data->general_index:好单指数
     * @return string data->seller_name:放单人名号
     * @return float data->discount:折扣力度
     */
    public function getGoodsList()
    {
        if( trim(I('post.type')) ){
            $type=trim(I('post.type'));
            $cid=0;
            if( trim(I('post.cid')) ){
                $cid=trim(I('post.cid'));
            }
            $back=10;
            if( trim(I('post.back')) ){
                $back=trim(I('post.back'));
            }
            $min_id=1;
            if( trim(I('post.min_id')) ){
                $min_id=trim(I('post.min_id'));
            }
            $sort=0;
            if( trim(I('post.sort')) ){
                $sort=trim(I('post.sort'));
            }
            $price_min='';
            if( trim(I('post.price_min')) ){
                $price_min=trim(I('post.price_min'));
            }
            $price_max='';
            if( trim(I('post.price_max')) ){
                $price_max=trim(I('post.price_max'));
            }
            $sale_min='';
            if( trim(I('post.sale_min')) ){
                $sale_min=trim(I('post.sale_min'));
            }
            $sale_max='';
            if( trim(I('post.sale_max')) ){
                $sale_max=trim(I('post.sale_max'));
            }
            $coupon_min='';
            if( trim(I('post.coupon_min')) ){
                $coupon_min=trim(I('post.coupon_min'));
            }
            $coupon_max='';
            if( trim(I('post.coupon_max')) ){
                $coupon_max=trim(I('post.coupon_max'));
            }
            $tkrates_min='';
            if( trim(I('post.tkrates_min')) ){
                $tkrates_min=trim(I('post.tkrates_min'));
            }
            $tkrates_max='';
            if( trim(I('post.tkrates_max')) ){
                $tkrates_max=trim(I('post.tkrates_max'));
            }
            $tkmoney_min='';
            if( trim(I('post.tkmoney_min')) ){
                $tkmoney_min=trim(I('post.tkmoney_min'));
            }
            $item_type='';
            if( trim(I('post.item_type')) ){
                $item_type=trim(I('post.item_type'));
            }
            $Haodanku=new \Common\Model\HaodankuModel();
            $res=$Haodanku->getGoodsList($type,$cid,$back,$min_id,$sort,$price_min,$price_max,$sale_min,$sale_max,$coupon_min,$coupon_max,$tkrates_min,$tkrates_max,$tkmoney_min,$item_type);
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
     * 各大榜单
     * @param integer $sale_type:必填，榜单类型：sale_type=1是实时销量榜（近2小时销量），type=2是今日爆单榜，type=3是昨日爆单榜，type=4是出单指数版
     * @param integer $cid:非必填
     * @param integer $back:必填
     * @param integer $min_id:必填
     * @param string $item_type:非必填
     * @return mixed
     */
    public function getSalesList()
    {
        if( trim(I('post.sale_type')) ){
            $sale_type=trim(I('post.sale_type'));
            $cid=0;
            if( trim(I('post.cid')) ){
                $cid=trim(I('post.cid'));
            }
            $back=10;
            if( trim(I('post.back')) ){
                $back=trim(I('post.back'));
            }
            $min_id=1;
            if( trim(I('post.min_id')) ){
                $min_id=trim(I('post.min_id'));
            }
            $item_type='';
            if( trim(I('post.item_type')) ){
                $item_type=trim(I('post.item_type'));
            }
            $Haodanku=new \Common\Model\HaodankuModel();
            $res=$Haodanku->getSalesList($sale_type,$cid,$min_id,$back=10,$item_type);
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
     * 商品搜索
     * @param string $keyword:必填，搜索关键词 支持宝贝ID搜索即keyword=itemid（由于存在特殊符号搜索的关键词必须进行两次urlencode编码）
     * @param integer $type:非必填，商品筛选类型：type=1是今日上新（当天新券商品），type=2是9.9包邮，type=3是30元封顶，type=4是聚划算，type=5是淘抢购，type=6是0点过夜单，type=7是预告单，type=8是品牌单，type=9是天猫商品，type=10是视频单
     * @param string $shopid:非必填，根据店铺id搜索商品 （需要注意的是店铺id搜索暂不支持筛选和排序，如果链接里有关键词和shopid优先搜索店铺id商品）
     * @param integer $cid:非必填，商品类目：0全部，1女装，2男装，3内衣，4美妆，5配饰，6鞋品，7箱包，8儿童，9母婴，10居家，11美食，12数码，13家电，14其他，15车品，16文体，17宠物（支持多类目筛选，如1,2获取类目为女装、男装的商品，逗号仅限英文逗号）
     * @param integer $back:非必填，默认10，每页返回条数（请在1,2,10,20,50,100,120,200,500,1000中选择一个数值返回）
     * @param integer $min_id:非必填，默认1，分页，用于实现类似分页抓取效果，来源于上次获取后的数据的min_id值，默认开始请求值为1（该方案比单纯123分页的优势在于：数据更新的情况下保证不会重复也无需关注和计算页数）
     * @param integer $sort:非必填，0.综合（最新），1.券后价(低到高)，2.券后价（高到低），3.券面额（高到低），4.月销量（高到低），5.佣金比例（高到低），6.券面额（低到高），7.月销量（低到高），8.佣金比例（低到高），9.全天销量（高到低），10全天销量（低到高），11.近2小时销量（高到低），12.近2小时销量（低到高），13.优惠券领取量（高到低）注意：该排序仅对nav=3，4，5有效，1，2无效
     * @param integer $price_min:非必填，券后价筛选，筛选大于等于所设置的券后价的商品
     * @param integer $price_max:非必填，券后价筛选，筛选小于等于所设置的券后价的商品
     * @param integer $sale_min:非必填，销量筛选，筛选大于等于所设置的销量的商品
     * @param integer $sale_max:非必填，销量筛选，筛选小于等于所设置的销量的商品
     * @param integer $coupon_min:非必填，券金额筛选，筛选大于等于所设置的券金额的商品
     * @param integer $coupon_max:非必填，券金额筛选，筛选小于等于所设置的券金额的商品
     * @param integer $tkrates_min:非必填，佣金比例筛选，筛选大于等于所设置的佣金比例的商品
     * @param integer $tkrates_max:非必填，佣金比例筛选，筛选小于所设置的佣金比例的商品
     * @param integer $tkmoney_min:非必填，佣金筛选，筛选大于等于所设置的佣金的商品
     * @param string $item_type:非必填，是否只获取营销返利商品
     * @return
     * @return integer code:状态码（1成功，0失败或没有数据返回）
     * @return integer min_id:作为请求地址中获取下一页的参数值
     * @return string msg:返回信息说明，SUCCESS代表成功获取，失败则有具体原因
     * @return integer data:返回数据
     * @return integer data->product_id:自增ID
     * @return integer data->itemid:宝贝ID
     * @return string data->itemtitle:宝贝标题
     * @return string data->itemshorttitle:宝贝短标题
     * @return string data->itemdesc:宝贝推荐语
     * @return float data->itemprice:在售价
     * @return string data->itemsale:宝贝月销量
     * @return string data->itemsale2:宝贝近2小时跑单
     * @return string data->todaysale:当天销量
     * @return string data->itempic:宝贝主图原始图像（由于图片原图过大影响加载速度，建议加上后缀_310x310.jpg，如https://img.alicdn.com/imgextra/i2/3412518427/TB26gs7bb7U5uJjSZFFXXaYHpXa_!!3412518427.jpg_310x310.jpg）
     * @return string data->itempic_copy:推广长图（带http://img.haodanku.com/0_553757100845_1509175123.jpg-600进行访问）
     * @return integer data->fqcat:商品类目：1女装，2男装，3内衣，4美妆，5配饰，6鞋品，7箱包，8儿童，9母婴，10居家，11美食，12数码，13家电，14其他，15车品，16文体，17宠物
     * @return float data->itemendprice:宝贝券后价
     * @return string data->shoptype:店铺类型：天猫店（B）淘宝店（C）
     * @return string data->couponurl:优惠券链接
     * @return float data->couponmoney:优惠券金额
     * @return integer data->is_brand:是否为品牌产品（1是）
     * @return integer data->is_live:是否为直播（1是）
     * @return string data->guide_article:推广导购文案
     * @return integer data->videoid:商品视频ID（id大于0的为有视频单，视频拼接地址http://cloud.video.taobao.com/play/u/1/p/1/e/6/t/1/+videoid+.mp4）
     * @return string data->activity_type:活动类型：普通活动 聚划算 淘抢购
     * @return string data->planlink:营销计划链接
     * @return integer data->userid:店主的userid
     * @return string data->sellernick:店铺掌柜名
     * @return string data->shopname:店铺名
     * @return string data->tktype:佣金计划：隐藏 营销
     * @return float data->tkrates:佣金比例
     * @return integer data->cuntao:是否村淘（1是）
     * @return float data->tkmoney:预计可得（宝贝价格 * 佣金比例 / 100）
     * @return integer data->couponreceive2:当天优惠券领取量
     * @return integer data->couponsurplus:优惠券剩余量
     * @return integer data->couponnum:优惠券总数量
     * @return string data->couponexplain:优惠券使用条件
     * @return integer data->couponstarttime:优惠券开始时间
     * @return integer data->couponendtime:优惠券结束时间
     * @return integer data->start_time:活动开始时间
     * @return integer data->end_time:活动结束时间
     * @return integer data->starttime:发布时间
     * @return integer data->report_status:举报处理条件0未举报1为待处理2为忽略3为下架
     * @return integer data->general_index:好单指数
     * @return string data->seller_name:放单人名号
     * @return float data->discount:折扣力度
     */
    public function search()
    {
        if( trim(I('post.keyword')) ){
            $keyword=trim(I('post.keyword'));
            if( trim(I('post.type')) ){
                $type=trim(I('post.type'));
            }
            if( trim(I('post.shopid')) ){
                $shopid=trim(I('post.shopid'));
            }
            $cid=0;
            if( trim(I('post.cid')) ){
                $cid=trim(I('post.cid'));
            }
            $back=10;
            if( trim(I('post.back')) ){
                $back=trim(I('post.back'));
            }
            $min_id=1;
            if( trim(I('post.min_id')) ){
                $min_id=trim(I('post.min_id'));
            }
            $sort=0;
            if( trim(I('post.sort')) ){
                $sort=trim(I('post.sort'));
            }
            $price_min='';
            if( trim(I('post.price_min')) ){
                $price_min=trim(I('post.price_min'));
            }
            $price_max='';
            if( trim(I('post.price_max')) ){
                $price_max=trim(I('post.price_max'));
            }
            $sale_min='';
            if( trim(I('post.sale_min')) ){
                $sale_min=trim(I('post.sale_min'));
            }
            $sale_max='';
            if( trim(I('post.sale_max')) ){
                $sale_max=trim(I('post.sale_max'));
            }
            $coupon_min='';
            if( trim(I('post.coupon_min')) ){
                $coupon_min=trim(I('post.coupon_min'));
            }
            $coupon_max='';
            if( trim(I('post.coupon_max')) ){
                $coupon_max=trim(I('post.coupon_max'));
            }
            $tkrates_min='';
            if( trim(I('post.tkrates_min')) ){
                $tkrates_min=trim(I('post.tkrates_min'));
            }
            $tkrates_max='';
            if( trim(I('post.tkrates_max')) ){
                $tkrates_max=trim(I('post.tkrates_max'));
            }
            $tkmoney_min='';
            if( trim(I('post.tkmoney_min')) ){
                $tkmoney_min=trim(I('post.tkmoney_min'));
            }
            $item_type='';
            if( trim(I('post.item_type')) ){
                $item_type=trim(I('post.item_type'));
            }
            $Haodanku=new \Common\Model\HaodankuModel();
            $res=$Haodanku->search($keyword,$cid,$back,$min_id,$sort,$type,$shopid,$price_min,$price_max,$sale_min,$sale_max,$coupon_min,$coupon_max,$tkrates_min,$tkrates_max,$tkmoney_min,$item_type);
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
     * 超级搜索
     * @param string $keyword:必填，搜索关键词 支持宝贝ID搜索即keyword=itemid（由于存在特殊符号搜索的关键词必须进行两次urlencode编码）
     * @param integer $back:必填
     * @param integer $min_id:必填
     * @param integer $tb_p:必填，淘宝分页，用于实现类似分页抓取效果，来源于上次获取后的数据的tb_p值，默认开始请求值为1（该方案比单纯123分页的优势在于：数据更新的情况下保证不会重复也无需关注和计算页数）
     * @param integer $sort:非必填
     * @param integer $is_tmall:非必填，是否只取天猫商品：0否；1是，默认是0
     * @param integer $is_coupon:非必填，是否只取有券商品：0否；1是，默认是0
     * @param integer $limitrate:非必填，佣金比例过滤0~100
     * @param integer $startprice:非必填，最低原价（默认为0），例如传10则只取大于等于10元的原价商品数据
     * @return
     * @return integer code:状态码（1成功，0失败或没有数据返回）
     * @return integer min_id:作为请求地址中获取下一页的参数值
     * @return integer tb_p:作为请求地址中获取下一页联盟超级搜数据的参数值
     * @return string msg:返回信息说明，SUCCESS代表成功获取，失败则有具体原因
     * @return integer data:返回数据
     * @return integer data->product_id:自增ID
     * @return integer data->itemid:宝贝ID
     * @return string data->itemtitle:宝贝标题
     * @return string data->itemshorttitle:宝贝短标题
     * @return string data->itemdesc:宝贝推荐语
     * @return float data->itemprice:在售价
     * @return string data->itemsale:宝贝月销量
     * @return string data->itemsale2:宝贝近2小时跑单
     * @return string data->todaysale:当天销量
     * @return string data->itempic:宝贝主图原始图像（由于图片原图过大影响加载速度，建议加上后缀_310x310.jpg，如https://img.alicdn.com/imgextra/i2/3412518427/TB26gs7bb7U5uJjSZFFXXaYHpXa_!!3412518427.jpg_310x310.jpg）
     * @return string data->itempic_copy:推广长图（带http://img.haodanku.com/0_553757100845_1509175123.jpg-600进行访问）
     * @return integer data->fqcat:商品类目：1女装，2男装，3内衣，4美妆，5配饰，6鞋品，7箱包，8儿童，9母婴，10居家，11美食，12数码，13家电，14其他，15车品，16文体，17宠物
     * @return float data->itemendprice:宝贝券后价
     * @return string data->shoptype:店铺类型：天猫店（B）淘宝店（C）
     * @return string data->couponurl:优惠券链接
     * @return float data->couponmoney:优惠券金额
     * @return integer data->is_brand:是否为品牌产品（1是）
     * @return integer data->is_live:是否为直播（1是）
     * @return string data->guide_article:推广导购文案
     * @return integer data->videoid:商品视频ID（id大于0的为有视频单，视频拼接地址http://cloud.video.taobao.com/play/u/1/p/1/e/6/t/1/+videoid+.mp4）
     * @return string data->activity_type:活动类型：普通活动 聚划算 淘抢购
     * @return string data->planlink:营销计划链接
     * @return integer data->userid:店主的userid
     * @return string data->sellernick:店铺掌柜名
     * @return string data->shopname:店铺名
     * @return string data->tktype:佣金计划：隐藏 营销
     * @return float data->tkrates:佣金比例
     * @return integer data->cuntao:是否村淘（1是）
     * @return float data->tkmoney:预计可得（宝贝价格 * 佣金比例 / 100）
     * @return integer data->couponreceive2:当天优惠券领取量
     * @return integer data->couponsurplus:优惠券剩余量
     * @return integer data->couponnum:优惠券总数量
     * @return string data->couponexplain:优惠券使用条件
     * @return integer data->couponstarttime:优惠券开始时间
     * @return integer data->couponendtime:优惠券结束时间
     * @return integer data->start_time:活动开始时间
     * @return integer data->end_time:活动结束时间
     * @return integer data->starttime:发布时间
     * @return integer data->report_status:举报处理条件0未举报1为待处理2为忽略3为下架
     * @return integer data->general_index:好单指数
     * @return string data->seller_name:放单人名号
     * @return float data->discount:折扣力度
     */
    public function supersearch()
    {
        if( trim(I('post.keyword')) ){
            $keyword=trim(I('post.keyword'));
            if( trim(I('post.type')) ){
                $type=trim(I('post.type'));
            }
            $back=10;
            if( trim(I('post.back')) ){
                $back=trim(I('post.back'));
            }
            $min_id=1;
            if( trim(I('post.min_id')) ){
                $min_id=trim(I('post.min_id'));
            }
            $tb_p=1;
            if( trim(I('post.tb_p')) ){
                $tb_p=trim(I('post.tb_p'));
            }
            $sort=0;
            if( trim(I('post.sort')) ){
                $sort=trim(I('post.sort'));
            }
            $is_tmall='';
            if( trim(I('post.is_tmall')) ){
                $is_tmall=trim(I('post.is_tmall'));
            }
            $is_coupon='';
            if( trim(I('post.is_coupon')) ){
                $is_coupon=trim(I('post.is_coupon'));
            }
            $limitrate='';
            if( trim(I('post.limitrate')) ){
                $limitrate=trim(I('post.limitrate'));
            }
            $startprice='';
            if( trim(I('post.startprice')) ){
                $startprice=trim(I('post.startprice'));
            }
            $Haodanku=new \Common\Model\HaodankuModel();
            $res=$Haodanku->supersearch($keyword,$back,$min_id,$tb_p,$sort,$is_tmall,$is_coupon,$limitrate,$startprice);
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
     * 获取好单库快抢商品
     * @param $hour_type integer  快抢时间点：1.昨天的0点，2.昨天10点，3.昨天12点，4.昨天15点，5.昨天20点，6.今天的0点，7.今天10点，8.今天12点，9.今天15点，10.今天20点，11.明天的0点，12.明天10点，13.明天12点，14.明天15点，15.明天20点快抢时间点：1.昨天的0点，2.昨天10点，3.昨天12点，4.昨天15点，5.昨天20点，6.今天的0点，7.今天10点，8.今天12点，9.今天15点，10.今天20点，11.明天的0点，12.明天10点，13.明天12点，14.明天15点，15.明天20点
     * @param $min_id integer 必填 分页，用于实现类似分页抓取效果，来源于上次获取后的数据的min_id值，默认开始请求值为1（该方案比单纯123分页的优势在于：数据更新的情况下保证不会重复也无需关注和计算页数）
     * @return Array
     * @return code Integer 状态码（1成功，0失败或没有数据返回）
     * @return min_id	 Integer 作为请求地址中获取下一页的参数值
     * @return msg string 返回信息说明，SUCCESS代表成功获取，失败则有具体原因
     * @return product_id	Integer	自增ID
     * @return itemid	Integer	    宝贝ID
     * @return itemtitle	string   宝贝标题
     * @return itemshorttitle	string	宝贝短标题
     * @return itemdesc	string	宝贝推荐语
     * @return itemprice	float 	在售价
     * @return  itemsale	integer	宝贝月销量
     * @return itemsale2	integer	宝贝近2小时跑单
     * @return itempic	string	宝贝主图原始图像（由于图片原图过大影响加载速度，建议加上后缀_310x310.jpg，如https://img.alicdn.com/imgextra/i2/3412518427/TB26gs7bb7U5uJjSZFFXXaYHpXa_!!3412518427.jpg_310x310.jpg）
     * @return itempic_copy	string	推广长图（带http://img.haodanku.com/0_553757100845_1509175123.jpg-600进行访问）
     * @return fqcat	integer	商品类目：1女装，2男装，3内衣，4美妆，5配饰，6鞋品，7箱包，8儿童，9母婴，10居家，11美食，12数码，13家电，14其他，15车品，16文体，17宠物
     * @return itemendprice	float	3.10	宝贝券后价
     * @return shoptype	string	店铺类型：天猫店（B） 淘宝店（C）
     * @return couponurl	string	优惠券链接
     * @return   couponmoney	float		优惠券金额
     * @return  videoid	integer	0	商品视频ID（id大于0的为有视频单，视频拼接地址http://cloud.video.taobao.com/play/u/1/p/1/e/6/t/1/+videoid+.mp4）
     * @return activity_type 	string	普通活动	活动类型：普通活动 聚划算 淘抢购
     * @return   planlink	string	https://s.click.taobao.com/KjSknfw	营销计划链接
     * @return  userid	integer	3162813958	店主的userid
     * @return  sellernick	string	南极人唯欲专卖店	店铺掌柜名
     * @return  tkrates	float	70.50	佣金比例
     * @return   tkmoney	float	2.19	预计可得（宝贝价格 * 佣金比例 / 100）
     * @return  couponstarttime	integer	1509120000	优惠券开始时间
     * @return   couponendtime	integer	1509292799	优惠券结束时间
     * @return  start_time	integer	1509174000	快抢开始时间
     * @return  end_time	integer	1509292799	快抢结束时间
     * @return starttime	integer	1509174000	发布时间
     * @return down_type	integer	0	下架类型：1用户自主下架；2被拉黑下架；3被举报下架；4取消认证下架；5过期下架；6结算下架；7优惠券失效下架；8平台下架（一般7是已抢光的情况）
     * @return general_index	integer	344	好单指数
     * @return  seller_name	string	若梦****追梦	放单人名号
     * @return  material_id	string	15	素材id
     * @return short_itemdesc	string	让味道留住你卧龙的记忆	导购短语
     * @return material_info	string	****	素材内容，里面含image是更多实拍图，seckill_content是图文素材（图片调用方法http://img.haodanku.com/{image}-600）
     * @return main_video_url	string	http:\/\/video.haodanku.com\/3YMFGz-lZxvmSH99SNqRz3mtU2s=\/FpU1L80LuAE1VrKUCcqPDSnMKYo0	实拍视频地址
     * @return detail_video_url	string	http:\/\/video.haodanku.com\/3YMFGz-lZxvmSH99SNqRz3mtU2s=\/FkKQiCloerAraG1B5hG6K0EwVwBL	更多实拍视频地址
     * @return detial_video_cover	string	10	实拍视频封面，可以通过改变链接中的w和h值分别改变宽高
     * @return main_video_cover	string	15	更多实拍视频封面，可以通过改变链接中的w和h值分别改变宽高
     * @return grab_type	string	3	快抢开抢状态：1快抢即将开始；2快抢商品已抢光；3快抢商品正在快抢中
     */
    public function getFastBuyList()
    {
        if(trim(I('post.min_id'))) {
            //分页
            $min_id=trim(I('post.min_id'));
            //抢购时间类型id
            if(trim(I('post.hour_type'))) {
                $hour_type=trim(I('post.hour_type'));
            }else{
                $hour_type="";
            }
            $Haodanku=new \Common\Model\HaodankuModel();
            $res=$Haodanku->getFastBuyList($min_id,$hour_type);
        }else{
            //参数不正确，参数缺失
            $res=array(
                'code'=>$this->ERROR_CODE_COMMON['PARAMETER_ERROR'],
                'msg'=>$this->ERROR_CODE_COMMON_ZH[$this->ERROR_CODE_COMMON['PARAMETER_ERROR']]
            );
        }
        echo json_encode ($res,JSON_UNESCAPED_UNICODE);
    }
    
    /**
     * 获取品牌列表
     * @param string $apikey:必填 放单后台获取的Apikey值
     * @param integer $back:必填 返回个数，默认返回20条数据
     * @param integer $min_id:必填 根据上一次请求作为下一次请求的参数值，默认是1
     * @param integer $brandcat:必填 品牌分类：默认选择全部分类，1是母婴童品，2百变女装，3是食品酒水，4是居家日用，5是美妆洗护，6是品质男装，7是舒适内衣，8是箱包配饰，9是男女鞋靴，10是宠物用品，11是数码家电，12是车品文体
     */
    public function getBrandList()
    {
        //每页展示个数
        if(trim(I('post.back'))){
            $back=trim(I('post.back'));
        }else{
            $back=20;
        }
        //页码
        if(trim(I('post.min_id'))) {
            $min_id=trim(I('post.min_id'));
        }else{
            $min_id=1;
        }
        if(trim(I('post.brandcat'))) {
            $brandcat=trim(I('post.brandcat'));
        }else{
            $brandcat="";
        }
        $Haodanku=new \Common\Model\HaodankuModel();
        $res=$Haodanku->getBrandList($back,$min_id,$brandcat);
        echo json_encode ($res,JSON_UNESCAPED_UNICODE);
    }
    
    /**
     * 获取品牌信息
     */
    public function getBrandMsg()
    {
        if(trim(I('post.id'))) {
            $id=trim(I('post.id'));
            $Haodanku=new \Common\Model\HaodankuModel();
            $res=$Haodanku->getBrandMsg($id);
        }else{
            //参数不正确，参数缺失
            $res=array(
                'code'=>$this->ERROR_CODE_COMMON['PARAMETER_ERROR'],
                'msg'=>$this->ERROR_CODE_COMMON_ZH[$this->ERROR_CODE_COMMON['PARAMETER_ERROR']]
            );
        }
        echo json_encode($res,JSON_UNESCAPED_UNICODE);
    }
    
    /**
     * 获取抖货商品类目列表
     * @return array
     */
    public function getDouHuoCat()
    {
        $Haodanku=new \Common\Model\HaodankuModel();
        $res=$Haodanku->getDouHuoCat();
        echo json_encode ($res,JSON_UNESCAPED_UNICODE);
    }
    
    /**
     * 获取抖货商品列表
     * @param integer $cat_id:非必填，商品类目
     * @param integer $min_id:非必填，作为请求地址中获取下一页的参数值，默认是1
     * @param integer $back:非必填，每页返回条数（请在1,2,5,10,20,50,100中选择一个数值返回，最多一页返回100条数据）（默认是10）
     * @return array
     */
    public function getDouHuoItemList()
    {
        $cat_id=0;
        if( trim(I('post.cat_id')) ){
            $cat_id=trim(I('post.cat_id'));
        }
        $back=10;
        if( trim(I('post.back')) ){
            $back=trim(I('post.back'));
        }
        $min_id=1;
        if( trim(I('post.min_id')) ){
            $min_id=trim(I('post.min_id'));
        }
        $Haodanku=new \Common\Model\HaodankuModel();
        $res=$Haodanku->getDouHuoItemList($cat_id,$back,$min_id);
        echo json_encode ($res,JSON_UNESCAPED_UNICODE);
    }
}