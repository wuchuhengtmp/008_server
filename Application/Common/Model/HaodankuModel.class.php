<?php
//好单库API接口
namespace Common\Model;
use Think\Model;

class HaodankuModel
{
    protected $apiKey='dmooo';
    protected $apiUrl='http://v2.api.haodanku.com';
    
    /**
     * 统一请求参数说明：
     * @param integer $nav:必填，默认全部商品（1实时跑单商品，2爆单榜商品，3全部商品，4纯视频单，5聚淘专区）
     * @param integer $type:必填，商品筛选类型：type=1是今日上新（当天新券商品），type=2是9.9包邮，type=3是30元封顶，type=4是聚划算，type=5是淘抢购，type=6是0点过夜单，type=7是预告单，type=8是品牌单，type=9是天猫商品，type=10是视频单
     * @param integer $cid:非必填，商品类目：0全部，1女装，2男装，3内衣，4美妆，5配饰，6鞋品，7箱包，8儿童，9母婴，10居家，11美食，12数码，13家电，14其他，15车品，16文体，17宠物（支持多类目筛选，如1,2获取类目为女装、男装的商品，逗号仅限英文逗号）
     * @param integer $back:必填，每页返回条数（请在1,2,10,20,50,100,120,200,500,1000中选择一个数值返回）
     * @param integer $min_id:必填，分页，用于实现类似分页抓取效果，来源于上次获取后的数据的min_id值，默认开始请求值为1（该方案比单纯123分页的优势在于：数据更新的情况下保证不会重复也无需关注和计算页数）
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
     * 
     * 统一返回参数说明：
     * @return array
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
    
    /**
     * 获取商品类目列表
     * @return array
     */
    public function getCidList()
    {
        $list0=array(
            'cid'=>0,
            'name'=>'全部'
        );
        $list1=array(
            'cid'=>1,
            'name'=>'女装'
        );
        $list2=array(
            'cid'=>2,
            'name'=>'男装'
        );
        $list3=array(
            'cid'=>3,
            'name'=>'内衣'
        );
        $list4=array(
            'cid'=>4,
            'name'=>'美妆'
        );
        $list5=array(
            'cid'=>5,
            'name'=>'配饰'
        );
        $list6=array(
            'cid'=>6,
            'name'=>'鞋品'
        );
        $list7=array(
            'cid'=>7,
            'name'=>'箱包'
        );
        $list8=array(
            'cid'=>8,
            'name'=>'儿童'
        );
        $list9=array(
            'cid'=>9,
            'name'=>'母婴'
        );
        $list10=array(
            'cid'=>10,
            'name'=>'居家'
        );
        $list11=array(
            'cid'=>11,
            'name'=>'美食'
        );
        $list12=array(
            'cid'=>12,
            'name'=>'数码'
        );
        $list13=array(
            'cid'=>13,
            'name'=>'家电'
        );
        $list14=array(
            'cid'=>14,
            'name'=>'其他'
        );
        $list15=array(
            'cid'=>15,
            'name'=>'车品'
        );
        $list16=array(
            'cid'=>16,
            'name'=>'文体'
        );
        $list17=array(
            'cid'=>17,
            'name'=>'宠物'
        );
        $list=array($list0,$list1,$list2,$list3,$list4,$list5,$list6,$list7,$list8,$list9,$list10,$list11,$list12,$list13,$list14,$list15,$list16,$list17);
        $data=array(
            'list'=>$list
        );
        $res=array(
            'code'=>0,
            'msg'=>'成功',
            'data'=>$data
        );
        return $res;
    }
    
    /**
     * 获取商品列表
     * @param integer $nav:必填
     * @param integer $cid:非必填
     * @param integer $back:必填
     * @param integer $min_id:必填
     * @param integer $sort:非必填
     * @param integer $price_min:非必填
     * @param integer $price_max:非必填
     * @param integer $sale_min:非必填
     * @param integer $sale_max:非必填
     * @param integer $coupon_min:非必填
     * @param integer $coupon_max:非必填
     * @param integer $tkrates_min:非必填
     * @param integer $tkrates_max:非必填
     * @param integer $tkmoney_min:非必填
     * @param string $item_type:非必填
     * @return array
     */
    public function getItemList($nav,$cid=0,$back=10,$min_id=1,$sort=0,$price_min='',$price_max='',$sale_min='',$sale_max='',$coupon_min='',$coupon_max='',$tkrates_min='',$tkrates_max='',$tkmoney_min='',$item_type='')
    {
        $url=$this->apiUrl."/itemlist/apikey/$this->apiKey/nav/$nav/cid/$cid/back/$back/min_id/$min_id/sort/$sort";
        if($price_min){
            $url.="/price_min/$price_min";
        }
        if($price_max){
            $url.="/price_max/$price_max";
        }
        if($sale_min){
            $url.="/sale_min/$sale_min";
        }
        if($sale_max){
            $url.="/sale_max/$sale_max";
        }
        if($coupon_min){
            $url.="/coupon_min/$coupon_min";
        }
        if($coupon_max){
            $url.="/coupon_max/$coupon_max";
        }
        if($tkrates_min){
            $url.="/tkrates_min/$tkrates_min";
        }
        if($tkrates_max){
            $url.="/tkrates_max/$tkrates_max";
        }
        if($tkmoney_min){
            $url.="/tkmoney_min/$tkmoney_min";
        }
        if($item_type){
            $url.="/item_type/$item_type";
        }
        $res_json=https_request($url);
        $result=json_decode($res_json,true);
        return $result;
        /* if($result['code']==1){
            //成功
        }else{
            //失败
        } */
    }
    
    /**
     * 商品筛选
     * @param integer $type:必填
     * @param integer $cid:非必填
     * @param integer $back:必填
     * @param integer $min_id:必填
     * @param integer $sort:非必填
     * @param integer $price_min:非必填
     * @param integer $price_max:非必填
     * @param integer $sale_min:非必填
     * @param integer $sale_max:非必填
     * @param integer $coupon_min:非必填
     * @param integer $coupon_max:非必填
     * @param integer $tkrates_min:非必填
     * @param integer $tkrates_max:非必填
     * @param integer $tkmoney_min:非必填
     * @param string $item_type:非必填
     * @return array
     */
    public function getGoodsList($type,$cid=0,$back=10,$min_id=1,$sort=0,$price_min='',$price_max='',$sale_min='',$sale_max='',$coupon_min='',$coupon_max='',$tkrates_min='',$tkrates_max='',$tkmoney_min='',$item_type='')
    {
        $url=$this->apiUrl."/column/apikey/$this->apiKey/type/$type/cid/$cid/back/$back/min_id/$min_id/sort/$sort";
        if($price_min){
            $url.="/price_min/$price_min";
        }
        if($price_max){
            $url.="/price_max/$price_max";
        }
        if($sale_min){
            $url.="/sale_min/$sale_min";
        }
        if($sale_max){
            $url.="/sale_max/$sale_max";
        }
        if($coupon_min){
            $url.="/coupon_min/$coupon_min";
        }
        if($coupon_max){
            $url.="/coupon_max/$coupon_max";
        }
        if($tkrates_min){
            $url.="/tkrates_min/$tkrates_min";
        }
        if($tkrates_max){
            $url.="/tkrates_max/$tkrates_max";
        }
        if($tkmoney_min){
            $url.="/tkmoney_min/$tkmoney_min";
        }
        if($item_type){
            $url.="/item_type/$item_type";
        }
        $res_json=https_request($url);
        $result=json_decode($res_json,true);
        return $result;
    }
    
    /**
     * 商品搜索
     * @param string $keyword:必填，搜索关键词 支持宝贝ID搜索即keyword=itemid（由于存在特殊符号搜索的关键词必须进行两次urlencode编码）
     * @param integer $type:非必填
     * @param string $shopid:非必填，根据店铺id搜索商品 （需要注意的是店铺id搜索暂不支持筛选和排序，如果链接里有关键词和shopid优先搜索店铺id商品）
     * @param integer $cid:非必填
     * @param integer $back:必填
     * @param integer $min_id:必填
     * @param integer $sort:非必填
     * @param integer $price_min:非必填
     * @param integer $price_max:非必填
     * @param integer $sale_min:非必填
     * @param integer $sale_max:非必填
     * @param integer $coupon_min:非必填
     * @param integer $coupon_max:非必填
     * @param integer $tkrates_min:非必填
     * @param integer $tkrates_max:非必填
     * @param integer $tkmoney_min:非必填
     * @param string $item_type:非必填
     * @return array
     */
    public function search($keyword,$cid=0,$back=10,$min_id=1,$sort=0,$type='',$shopid='',$price_min='',$price_max='',$sale_min='',$sale_max='',$coupon_min='',$coupon_max='',$tkrates_min='',$tkrates_max='',$tkmoney_min='',$item_type='')
    {
        $keyword=urlencode(urlencode($keyword));//进行两次urlencode编码
        $url=$this->apiUrl."/get_keyword_items/apikey/$this->apiKey/keyword/$keyword/cid/$cid/back/$back/min_id/$min_id/sort/$sort";
        if($type){
            $url.="/type/$type";
        }
        if($shopid){
            $url.="/shopid/$shopid";
        }
        if($price_min){
            $url.="/price_min/$price_min";
        }
        if($price_max){
            $url.="/price_max/$price_max";
        }
        if($sale_min){
            $url.="/sale_min/$sale_min";
        }
        if($sale_max){
            $url.="/sale_max/$sale_max";
        }
        if($coupon_min){
            $url.="/coupon_min/$coupon_min";
        }
        if($coupon_max){
            $url.="/coupon_max/$coupon_max";
        }
        if($tkrates_min){
            $url.="/tkrates_min/$tkrates_min";
        }
        if($tkrates_max){
            $url.="/tkrates_max/$tkrates_max";
        }
        if($tkmoney_min){
            $url.="/tkmoney_min/$tkmoney_min";
        }
        if($item_type){
            $url.="/item_type/$item_type";
        }
        $res_json=https_request($url);
        $result=json_decode($res_json,true);
        return $result;
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
     * @return array
     */
    public function supersearch($keyword,$back=10,$min_id=1,$tb_p=1,$sort=0,$is_tmall='',$is_coupon='',$limitrate='',$startprice='')
    {
        $keyword=urlencode(urlencode($keyword));//进行两次urlencode编码
        $url=$this->apiUrl."/supersearch/apikey/$this->apiKey/keyword/$keyword/back/$back/min_id/$min_id/tb_p/$tb_p/sort/$sort";
        if($is_tmall){
            $url.="/is_tmall/$is_tmall";
        }
        if($is_coupon){
            $url.="/is_coupon/$is_coupon";
        }
        if($limitrate){
            $url.="/limitrate/$limitrate";
        }
        if($startprice){
            $url.="/startprice/$startprice";
        }
        $res_json=https_request($url);
        $result=json_decode($res_json,true);
        return $result;
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
    public function getSalesList($sale_type,$cid=0,$min_id=1,$back=10,$item_type='')
    {
        $url=$this->apiUrl."/sales_list/apikey/$this->apiKey/sale_type/$sale_type/cid/$cid/back/$back/min_id/$min_id";
        if($item_type){
            $url.="/item_type/$item_type";
        }
        $res_json=https_request($url);
        $result=json_decode($res_json,true);
        return $result;
    }
    
    /**
     * 获取商品详情-好单库
     * @param integer $itemid:获取详情的宝贝ID（目前仅支持好单库站内商品获取详情）
     * @return mixed
     */
    public function getItemDetailLocal($itemid)
    {
        $url=$this->apiUrl."/item_detail/apikey/$this->apiKey/itemid/$itemid";
        $res_json=https_request($url);
        $result=json_decode($res_json,true);
        return $result;
    }
    
    /**
     * 获取高佣、优惠券、转链-需要授权给好单库
     * @param integer $itemid:必填，商品ID
     * @param string $pid:必填，推广位ID（*必要 需是授权淘宝号下的推广位，如果请求的时候携带了渠道id请求，则该pid需是渠道管理下面的渠道专属推广位）
     * @param string $relation_id:非必填，渠道ID
     * @param string $tb_name:必填，授权后的淘宝名称（*必要，多授权淘宝号时用于区分哪个淘宝账户的）
     * @param string $activityid:非必填，阿里妈妈推广券ID （选填）
     * @param string $me:非必填，营销计划（选填）
     * @param string $access_token:非必填，授权之后的淘宝授权token（选填）
     * @return array
     * @return integer code:状态码（1成功，0失败或没有数据返回）
     * @return string msg:返回信息说明，SUCCESS代表成功获取，失败则有具体原因
     * @return string coupon_click_url:高佣优惠券链接
     * @return string max_commission_rate:佣金比例
     * @return string couponmoney:优惠券金额，若没有优惠券则返回0
     * @return string couponstarttime:优惠券开始时间，若没有则返回空
     * @return string couponendtime:优惠券结束时间，若没有则返回空
     * @return string couponexplain:优惠券使用条件，若没有则返回空
     * @return string couponnum:优惠券总量，若没有则返回空
     * @return string couponsurplus:优惠券剩余量，若没有则返回空
     * @return string couponreceive:优惠券领取量，若没有则返回空
     * @return string item_url:单品链接，没有券的情况可以直接进入淘宝购买页
     */
    public function ratesurl($itemid,$pid,$relation_id='',$tb_name,$activityid='',$me='',$access_token='')
    {
        $url=$this->apiUrl."/ratesurl/apikey/$this->apiKey/itemid/$itemid/pid/$pid/tb_name";
        if($relation_id){
            $url.="/relation_id/$relation_id";
        }
        if($activityid){
            $url.="/activityid/$activityid";
        }
        if($me){
            $url.="/me/$me";
        }
        if($access_token){
            $url.="/access_token/$access_token";
        }
        $res_json=https_request($url);
        $result=json_decode($res_json,true);
        return $result;
    }
    
    /**
     * 获取快抢商品
     * @param string apikey 放单后台获取的Apikey值
     * @param integer hour_type 快抢时间点：1.昨天的0点，2.昨天10点，3.昨天12点，4.昨天15点，5.昨天20点，6.今天的0点，7.今天10点，8.今天12点，9.今天15点，10.今天20点，11.明天的0点，12.明天10点，13.明天12点，14.明天15点，15.明天20点快抢时间点：1.昨天的0点，2.昨天10点，3.昨天12点，4.昨天15点，5.昨天20点，6.今天的0点，7.今天10点，8.今天12点，9.今天15点，10.今天20点，11.明天的0点，12.明天10点，13.明天12点，14.明天15点，15.明天20点
     * @param min_id integer 分页，用于实现类似分页抓取效果，来源于上次获取后的数据的min_id值，默认开始请求值为1（该方案比单纯123分页的优势在于：数据更新的情况下保证不会重复也无需关注和计算页数）
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
     * @return activity_type	string	普通活动	活动类型：普通活动 聚划算 淘抢购
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
    public function getFastBuyList($min_id,$hour_type='')
    {
        $url=$this->apiUrl."/fastbuy/apikey/$this->apiKey/min_id/$min_id";
        if($hour_type) {
            $url.="/hour_type/$hour_type";
        }
        $res_json=https_request($url);
        $result=json_decode($res_json,true);
        return $result;
    }
    
    /**
     * 获取品牌列表
     * @param string $apikey:必填 放单后台获取的Apikey值
     * @param integer $back:必填 返回个数，默认返回20条数据
     * @param integer $min_id:必填 根据上一次请求作为下一次请求的参数值，默认是1
     * @param integer $brandcat:必填 品牌分类：默认选择全部分类，1是母婴童品，2百变女装，3是食品酒水，4是居家日用，5是美妆洗护，6是品质男装，7是舒适内衣，8是箱包配饰，9是男女鞋靴，10是宠物用品，11是数码家电，12是车品文体
     */
    public function getBrandList($back=20,$min_id=1,$brandcat)
    {
        //品牌接口地址
        $url=$this->apiUrl."/brand/apikey/$this->apiKey/back/$back/min_id/$min_id";
        if($brandcat) {
            $url.="/brandcat/$brandcat";
        }
        $res_json=https_request($url);
        $result=json_decode($res_json,true);
        return $result;
    }
    
    /**
     * 获取品牌信息
     */
    public function getBrandMsg($id)
    {
        //品牌信息
        $url=$this->apiUrl."/singlebrand/apikey/$this->apiKey/id/$id";
        $res_json=https_request($url);
        $res=json_decode($res_json,true);
        return $res;
    }
    
    /**
     * 获取抖货商品类目列表
     * @return array
     */
    public function getDouHuoCat()
    {
        $list0=array(
            'cat_id'=>0,
            'name'=>'热门',
            'icon'=>'/Public/Upload/hdk/0.png',
        );
        $list1=array(
            'cat_id'=>1,
            'name'=>'百变穿搭',
            'icon'=>'/Public/Upload/hdk/1.png',
        );
        $list2=array(
            'cat_id'=>2,
            'name'=>'时尚潮男',
            'icon'=>'/Public/Upload/hdk/2.png',
        );
        $list3=array(
            'cat_id'=>3,
            'name'=>'舒适好物',
            'icon'=>'/Public/Upload/hdk/3.png',
        );
        $list4=array(
            'cat_id'=>4,
            'name'=>'美妆达人',
            'icon'=>'/Public/Upload/hdk/4.png',
        );
        $list5=array(
            'cat_id'=>5,
            'name'=>'魅力配饰',
            'icon'=>'/Public/Upload/hdk/5.png',
        );
        $list6=array(
            'cat_id'=>6,
            'name'=>'步履不停',
            'icon'=>'/Public/Upload/hdk/6.png',
        );
        $list7=array(
            'cat_id'=>7,
            'name'=>'包罗万象',
            'icon'=>'/Public/Upload/hdk/7.png',
        );
        $list8=array(
            'cat_id'=>8,
            'name'=>'萌娃驾到',
            'icon'=>'/Public/Upload/hdk/8.png',
        );
        $list9=array(
            'cat_id'=>9,
            'name'=>'宝妈神器',
            'icon'=>'/Public/Upload/hdk/9.png',
        );
        $list10=array(
            'cat_id'=>10,
            'name'=>'居家好物',
            'icon'=>'/Public/Upload/hdk/10.png',
        );
        $list11=array(
            'cat_id'=>11,
            'name'=>'吃货专区',
            'icon'=>'/Public/Upload/hdk/11.png',
        );
        $list12=array(
            'cat_id'=>12,
            'name'=>'数码达人',
            'icon'=>'/Public/Upload/hdk/12.png',
        );
        $list13=array(
            'cat_id'=>13,
            'name'=>'用电能手',
            'icon'=>'/Public/Upload/hdk/13.png',
        );
        $list14=array(
            'cat_id'=>14,
            'name'=>'其他',
            'icon'=>'/Public/Upload/hdk/14.png',
        );
        $list15=array(
            'cat_id'=>15,
            'name'=>'伴你前行',
            'icon'=>'/Public/Upload/hdk/15.png',
        );
        $list16=array(
            'cat_id'=>16,
            'name'=>'学习娱乐',
            'icon'=>'/Public/Upload/hdk/16.png',
        );
        $list17=array(
            'cat_id'=>17,
            'name'=>'萌宠世界',
            'icon'=>'/Public/Upload/hdk/17.png',
        );
        $list=array($list0,$list1,$list2,$list3,$list4,$list5,$list6,$list7,$list8,$list9,$list10,$list11,$list12,$list13,$list14,$list15,$list16,$list17);
        $data=array(
            'list'=>$list
        );
        $res=array(
            'code'=>0,
            'msg'=>'成功',
            'data'=>$data
        );
        return $res;
    }
    
    /**
     * 获取抖货商品列表
     * @param integer $cat_id:非必填，商品类目
     * @param integer $min_id:非必填，作为请求地址中获取下一页的参数值，默认是1
     * @param integer $back:非必填，每页返回条数（请在1,2,5,10,20,50,100中选择一个数值返回，最多一页返回100条数据）（默认是10）
     * @return array
     */
    public function getDouHuoItemList($cat_id=0,$back=10,$min_id=1)
    {
        $url=$this->apiUrl."/get_trill_data/apikey/$this->apiKey/min_id/$min_id/back/$back/cat_id/$cat_id";
        $res_json=https_request($url);
        $result=json_decode($res_json,true);
        return $result;
    }
}