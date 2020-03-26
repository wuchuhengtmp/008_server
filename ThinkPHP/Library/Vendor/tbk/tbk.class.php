<?php
/**
 * 淘宝客
 * 2018-01-06
 * @author 葛阳
 * 淘客商品都是有佣金的商品， 有的商家会通过设置优惠券的方式来让淘客推广。 
 * 所以淘客商品，不都是有优惠券的，也有没有优惠券的，但是肯定都是有佣金的。
 * 通用物料这个接口，包括有券和没券的， 有券的商品给二合一优惠券链接， 没有券的商品给单品淘客返利链接。
 * 好券清单，只能搜索到有券的商品。通用物料针对没有券的商品，也可以给返利。
 */
include "TopSdk.php";

class tbk
{
	protected $appkey=TBK_APPKEY;
	protected $secret=TBK_APPSECRET;
	public $pid=TBK_PID;
	public $relation_pid=TBK_RELATION_PID;//渠道专用pid
	public $adzone_id=TBK_ADZONE_ID;//广告位ID
	protected $gy_appkey='dianmo';//高佣接口appkey
	protected $gy_appkey_wy=WY_APPKEY;//高佣接口appkey-维易淘客
	protected $url_wy='http://mvapi.vephp.com';//接口地址-维易淘客
	
	/**
	 * 获取淘宝商品列表
	 * @param string $search:查询词，如女装
	 * @param string $cat:后台类目ID，用,分割，如16,18，最大10个，该ID可以通过taobao.itemcats.get接口获取到
	 * @param string $itemloc:所在地
	 * @param string $sort:排序_des（降序），排序_asc（升序），销量（total_sales），淘客佣金比率（tk_rate）， 累计推广量（tk_total_sales），总支出佣金（tk_total_commi）,默认tk_rate_des
	 * @param string $is_tmall:是否商城商品，设置为true表示该商品是属于淘宝商城商品，设置为false或不设置表示不判断这个属性，默认false
	 * @param string $is_overseas:是否海外商品，设置为true表示该商品是属于海外商品，设置为false或不设置表示不判断这个属性，默认false
	 * @param number $start_price:折扣价范围下限，单位：元，默认10元
	 * @param number $end_price:折扣价范围上限，单位：元，默认10元
	 * @param number $start_tk_rate:淘客佣金比率上限，如：1234表示12.34%
	 * @param number $end_tk_rate:淘客佣金比率下限，如：1234表示12.34%
	 * @param int $platform:链接形式：1：PC，2：无线，默认：１
	 * @param int $page_no:第几页，默认：１
	 * @param int $page_size:页大小，默认20，1~100
	 * @return array
	 * @return @param code:返回码
	 * @return @param msg:返回码说明
	 * @return @param data:返回数据
	 * @return @param data->num_iid:商品ID
	 * @return @param data->title:商品标题
	 * @return @param data->pict_url:商品主图
	 * @return @param data->small_images:商品小图列表
	 * @return @param data->reserve_price:商品一口价格
	 * @return @param data->zk_final_price:商品折扣价格
	 * @return @param data->user_type:卖家类型，0表示集市，1表示商城
	 * @return @param data->provcity:商品所在地
	 * @return @param data->item_url:商品链接
	 * @return @param data->nick:店铺名称
	 * @return @param data->seller_id:卖家id
	 * @return @param data->volume:30天销量
	 * @return @param data->coupon_total_count:优惠券总量
	 * @return @param data->coupon_remain_count:优惠券剩余量
	 * @return @param data->coupon_start_time:优惠券开始时间
	 * @return @param data->coupon_end_time:优惠券结束时间
	 * @return @param data->coupon_info:优惠券信息
	 * @return @param data->coupon_amount:优惠券面额
	 * @return @param data->commission_rate:佣金比率(%)
	 * @return @param data->commission:佣金
	 */
	public function getTbkItem($search='',$cat='',$itemloc='',$sort='tk_rate_des',$is_tmall=false,$is_overseas=false,$start_price='',$end_price='',$start_tk_rate='',$end_tk_rate='',$platform='1',$page_no=1,$page_size=20)
	{
		$c = new TopClient;
		$c->appkey = $this->appkey;
		$c->secretKey = $this->secret;
		$req = new TbkItemGetRequest;
		$req->setFields("num_iid,title,pict_url,small_images,reserve_price,zk_final_price,user_type,provcity,item_url,seller_id,volume,nick");
		if($search) {
			$req->setQ($search);
		}
		if($cat) {
			$req->setCat($cat);
		}
		if($itemloc) {
			$req->setItemloc($itemloc);
		}
		$req->setSort($sort);
		$req->setIsTmall($is_tmall);
		$req->setIsOverseas($is_overseas);
		if($start_price) {
			$req->setStartPrice($start_price);
		}
		if($end_price) {
			$req->setEndPrice($end_price);
		}
		if($start_tk_rate) {
			$req->setStartTkRate($start_tk_rate);
		}
		if($end_tk_rate) {
			$req->setEndTkRate($end_tk_rate);
		}
		$req->setPlatform($platform);
		$req->setPageNo($page_no);
		$req->setPageSize($page_size);
		$resp = $c->execute($req);
		$result=json_decode(json_encode($resp),true);
		if($result['code']) {
			$res=array(
					'code'=>$result['code'],
					'msg'=>$result['msg'],
			);
		}else {
			//商品列表
			$list=$result['results']['n_tbk_item'];
			$num=count($list);
			$pid=$this->pid;
			for($i=0;$i<$num;$i++) {
				//查询商品佣金以及优惠券
				//优惠券总量
				$list[$i]['coupon_total_count']=0;
				//优惠券剩余量
				$list[$i]['coupon_remain_count']=0;
				//优惠券开始时间
				$list[$i]['coupon_start_time']='';
				//优惠券结束时间
				$list[$i]['coupon_end_time']='';
				//优惠券信息
				$list[$i]['coupon_info']='';
				//优惠券面额
				$list[$i]['coupon_amount']=0;
				//优惠券推广链接
				$list[$i]['coupon_click_url']='';
				//佣金比率(%)
				$list[$i]['commission_rate']=0;
				//佣金
				$list[$i]['commission']=0;
				
				//商品ID
				$num_iid=$list[$i]['num_iid'];
				//商品价格，保留2位小数，四舍五不入
				$list[$i]['zk_final_price']=substr(sprintf("%.3f",$list[$i]['zk_final_price']),0,-1);
				$zk_final_price=$list[$i]['zk_final_price'];
				
				$q=$list[$i]['title'];
				//好券清单
				$res_coupon=$this->getCouponItem($this->adzone_id,$platform='2',$q,$cat='',$page_no=1,$page_size=100);
				if($res_coupon['code']==0 and $res_coupon['data'][0])
				{
					//该商品有优惠券，获取对应商品列表
					foreach ($res_coupon['data'] as $l)
					{
						if($l['num_iid']==$num_iid)
						{
							//优惠券总量
							$list[$i]['coupon_total_count']=$l['coupon_total_count'];
							//优惠券剩余量
							$list[$i]['coupon_remain_count']=$l['coupon_remain_count'];
							//优惠券开始时间
							$list[$i]['coupon_start_time']=$l['coupon_start_time'];
							//优惠券结束时间
							$list[$i]['coupon_end_time']=$l['coupon_end_time'];
							//优惠券信息
							$list[$i]['coupon_info']=$l['coupon_info'];
							//优惠券面额
							$pos1=strpos($l['coupon_info'],'减');
							$pos2=strripos($l['coupon_info'],'元');
							$list[$i]['coupon_amount']=substr($l['coupon_info'], $pos1+3,$pos2-$pos1-3);
							//优惠券推广链接
							$list[$i]['coupon_click_url']=$l['coupon_click_url'];
							//佣金比率(%)
							$list[$i]['commission_rate']=$l['commission_rate'];
							//佣金，保留2位小数，四舍五不入
							$list[$i]['commission']=$l['zk_final_price']*$l['commission_rate']/100;
							$list[$i]['commission']=substr(sprintf("%.3f",$list[$i]['commission']),0,-1);
							//$list[$i]['commission']=sprintf("%.2f",substr(sprintf("%.3f", $list[$i]['commission']), 0, -2));
						}
					}
				}else {
					//该商品没有优惠券
					
					//调用商品查询接口
					$q=urlencode($list[$i]['title']);
					$url="http://www.mapprouter.com/api/search/taobaoke_item_list?pid=$pid&q=$q&sort=1&pageNum=1";
					$res_json_tbk=https_request($url);
					$result_tbk=json_decode($res_json_tbk,true);
					if($result_tbk['data'])
					{
						foreach ($result_tbk['data'] as $l)
						{
							if($l['itemId']==$num_iid)
							{
								//佣金比率(%)
								$list[$i]['commission_rate']=$l['tkRate']/100;
								//佣金，保留2位小数，四舍五不入
								$list[$i]['commission']=$zk_final_price*$l['tkRate']/10000;
								$list[$i]['commission']=substr(sprintf("%.3f",$list[$i]['commission']),0,-1);
							}
						}
					}
				}
			}
			$res=array(
					'code'=>0,
					'msg'=>'成功',
					'data'=>$list
			);
		}
		return $res;
	}
	
	/**
	 * 淘宝客商品关联推荐查询
	 * @param string $num_iid:商品Id，必填
	 * @param number $count:返回数量，默认20，最大值40
	 * @param string $platform:链接形式：1：PC，2：无线，默认：１
	 * 
	 */
	public function getRecommendItem($num_iid,$count=4,$platform='2')
	{
		$c = new TopClient;
		$c->appkey = $this->appkey;
		$c->secretKey = $this->secret;
		$req = new TbkItemRecommendGetRequest;
		$req->setFields("num_iid,title,pict_url,small_images,reserve_price,zk_final_price,user_type,provcity,item_url");
		$req->setNumIid($num_iid);
		$req->setCount($count);
		$req->setPlatform($platform);
		$resp = $c->execute($req);
		$result=json_decode(json_encode($resp),true);
		if($result['code']) {
		    $res=array(
		        'code'=>$result['sub_code'],
		        'msg'=>$result['sub_msg'],
		    );
		}else {
		    //查询优惠券及佣金
		    $list=$result['results']['n_tbk_item'];
		    $num=count($list);
		    $gy_appkey=$this->gy_appkey_wy;
		    $pid=$this->pid;
		    for($i=0;$i<$num;$i++){
		        //查询商品佣金以及优惠券
		        $num_iid=$list[$i]['num_iid'];
		        
		        //维易淘客-【辅助接口使用】id转高佣淘口令接口
		        //此API不需要授权，适用于在已知产品有优惠券情况下（比如产品列表页传参）可以直接调用。不适用于对无优惠券商品的转链。
		        $url_gy=$this->url_wy."/hcapi?vekey=$gy_appkey&para=$num_iid&pid=$pid";
		        $result_json_gy=https_request($url_gy);
		        $result_gy=json_decode($result_json_gy,true);
		        $gy_data=$result_gy['data'];
		        
		        //优惠券总量
		        if($gy_data['coupon_total_count']) {
		            $list[$i]['coupon_total_count']=$gy_data['coupon_total_count'];
		        }else {
		            $list[$i]['coupon_total_count']=0;
		        }
		        //优惠券剩余量
		        if($gy_data['coupon_remain_count']) {
		            $list[$i]['coupon_remain_count']=$gy_data['coupon_remain_count'];
		        }else {
		            $list[$i]['coupon_remain_count']=0;
		        }
		        //优惠券开始时间
		        if($gy_data['coupon_start_time']) {
		            $list[$i]['coupon_start_time']=$gy_data['coupon_start_time'];
		        }else {
		            $list[$i]['coupon_start_time']='';
		        }
		        //优惠券结束时间
		        if($gy_data['coupon_end_time']) {
		            $list[$i]['coupon_end_time']=$gy_data['coupon_end_time'];
		        }else {
		            $list[$i]['coupon_end_time']='';
		        }
		        //优惠券信息
		        if($gy_data['coupon_info']) {
		            $list[$i]['coupon_info']=$gy_data['coupon_info'];
		            //优惠券面额
		            $pos1=strpos($gy_data['coupon_info'],'减');
		            $pos2=strripos($gy_data['coupon_info'],'元');
		            $list[$i]['coupon_amount']=substr($gy_data['coupon_info'], $pos1+3,$pos2-$pos1-3);
		        }else {
		            $list[$i]['coupon_info']='';
		            $list[$i]['coupon_amount']=0;
		        }
		        //佣金比率(%)
		        $list[$i]['commission_rate']=$gy_data['commission_rate'];
		        //佣金
		        $list[$i]['commission']=($list[$i]['zk_final_price']-$list[$i]['coupon_amount'])*$list[$i]['commission_rate']/100;
		        //保留2位小数，四舍五不入
		        $list[$i]['commission']=substr(sprintf("%.3f",$list[$i]['commission']),0,-1);
		    }
		    $data=array(
		        'list'=>$list
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
	 * 淘宝客商品详情（简版）
	 * @param string $num_iids:商品ID串，用,分割，最大40个
	 * @param string $platform:链接形式：1：PC，2：无线，默认：１
	 * @param string $ip:ip地址，影响邮费获取，如果不传或者传入不准确，邮费无法精准提供
	 * @return array
	 * @return @param code:返回码
	 * @return @param msg:返回码说明
	 * @return @param data:返回数据
	 * @return @param data->cat_name:一级类目名称
	 * @return @param data->num_iid:商品ID
	 * @return @param data->title:商品标题
	 * @return @param data->pict_url:商品主图
	 * @return @param data->small_images:商品小图列表
	 * @return @param data->reserve_price:商品一口价格
	 * @return @param data->zk_final_price:商品折扣价格
	 * @return @param data->user_type:卖家类型，0表示集市，1表示商城
	 * @return @param data->provcity:商品所在地
	 * @return @param data->item_url:商品链接
	 * @return @param data->seller_id:卖家id
	 * @return @param data->volume:30天销量
	 * @return @param data->nick:店铺名称
	 * @return @param data->cat_leaf_name:叶子类目名称
	 * @return @param data->is_prepay:是否加入消费者保障
	 * @return @param data->shop_dsr:店铺dsr 评分
	 * @return @param data->ratesum:卖家等级
	 * @return @param data->i_rfd_rate:退款率是否低于行业均值
	 * @return @param data->h_good_rate:好评率是否高于行业均值
	 * @return @param data->h_pay_rate30:成交转化是否高于行业均值
	 * @return @param data->free_shipment:是否包邮
	 */
	public function getItemInfo($num_iids,$platform='1',$ip='')
	{
		$c = new TopClient;
		$c->appkey = $this->appkey;
		$c->secretKey = $this->secret;
		$req = new TbkItemInfoGetRequest;
		$req->setNumIids($num_iids);
		$req->setPlatform($platform);
		if($ip) {
			$req->setIp($ip);
		}
		$resp = $c->execute($req);
		$result=json_decode(json_encode($resp),true);
		if($result['code']) {
			$res=array(
					'code'=>$result['sub_code'],
					'msg'=>$result['sub_msg'],
			);
		}else {
			$res=array(
					'code'=>0,
					'msg'=>'成功',
					'data'=>$result['results']['n_tbk_item']
			);
		}
		return $res;
	}
	
	/**
	 * 获取单件淘宝客商品详情
	 * @param string $num_iid:商品ID
	 * @param string $platform:链接形式：1：PC，2：无线，默认：１
	 * @param string $ip:ip地址，影响邮费获取，如果不传或者传入不准确，邮费无法精准提供
	 * @param string $pid:淘宝推广位ID
	 * @return array
	 * @return @param code:返回码
	 * @return @param msg:返回码说明
	 * @return @param data:返回数据
	 * @return @param data->cat_name:一级类目名称
	 * @return @param data->num_iid:商品ID
	 * @return @param data->title:商品标题
	 * @return @param data->pict_url:商品主图
	 * @return @param data->small_images:商品小图列表
	 * @return @param data->reserve_price:商品一口价格
	 * @return @param data->zk_final_price:商品折扣价格
	 * @return @param data->user_type:卖家类型，0表示集市，1表示商城
	 * @return @param data->provcity:商品所在地
	 * @return @param data->item_url:商品链接
	 * @return @param data->seller_id:卖家id
	 * @return @param data->volume:30天销量
	 * @return @param data->nick:店铺名称
	 * @return @param data->cat_leaf_name:叶子类目名称
	 * @return @param data->is_prepay:是否加入消费者保障
	 * @return @param data->shop_dsr:店铺dsr 评分
	 * @return @param data->ratesum:卖家等级
	 * @return @param data->i_rfd_rate:退款率是否低于行业均值
	 * @return @param data->h_good_rate:好评率是否高于行业均值
	 * @return @param data->h_pay_rate30:成交转化是否高于行业均值
	 * @return @param data->free_shipment:是否包邮
	 * @return @param data->coupon_total_count:优惠券总量
	 * @return @param data->coupon_remain_count:优惠券剩余量
	 * @return @param data->coupon_start_time:优惠券开始时间
	 * @return @param data->coupon_end_time:优惠券结束时间
	 * @return @param data->coupon_info:优惠券信息
	 * @return @param data->coupon_amount:优惠券面额
	 * @return @param data->commission_rate:佣金比率(%)
	 * @return @param data->commission:佣金
	 */
	public function getItemDetail($num_iid,$platform='1',$ip='',$pid='',$relationId ='')
	{
		if($pid=='') {
			//没有的情况下使用默认推广位
			$pid=$this->pid;
			$adzone_id=$this->adzone_id;
		}else {
			//使用用户绑定的推广位、广告位
			$start=strripos($pid, '_');
			$adzone_id=substr($pid, $start+1);
		}
		$res_info=$this->getItemInfo($num_iid,$platform,$ip);
		if($res_info['code']==0) {
			//获取卖点信息
			$res_info['data']['item_description']='';
			
			//商品折扣价格，保留2位小数，四舍五不入
			$res_info['data']['zk_final_price']=substr(sprintf("%.3f",$res_info['data']['zk_final_price']),0,-1);
			
			//查询商品佣金以及优惠券
			$num_iid=$res_info['data']['num_iid'];
			
			//调用高佣接口
			//维易淘客-【辅助接口使用】id转高佣淘口令接口
			$gy_appkey=$this->gy_appkey_wy;
			//此API不需要授权，适用于在已知产品有优惠券情况下（比如产品列表页传参）可以直接调用。不适用于对无优惠券商品的转链。
			$url_gy=$this->url_wy."/hcapi?vekey=$gy_appkey&para=$num_iid&pid=$pid";
			$result_json_gy=https_request($url_gy);
			$result_gy=json_decode($result_json_gy,true);
			$gy_data=$result_gy['data'];
			/* //上海淘客高佣接口
			$gy_appkey=$this->gy_appkey;
			$url_gy="http://taokelink.sc2yun.com/gethigh.php?appkey=$gy_appkey&pid=$pid&itemid=$num_iid";
			$result_json_gy=https_request($url_gy);
			$result_gy=json_decode($result_json_gy,true);
			$gy_data=$result_gy['tbk_privilege_get_response']['result']['data']; */
			
			//生成渠道分享链接
			if($relationId){
			    $url_gy_r=$this->url_wy."/hcapi?vekey=$gy_appkey&para=$num_iid&pid=$this->relation_pid&relationId=$relationId";
			    $result_json_gy_r=https_request($url_gy_r);
			    $result_gy_r=json_decode($result_json_gy_r,true);
			    $gy_data_r=$result_gy_r['data'];
			    $res_info['data']['coupon_click_url_r']=$gy_data_r['coupon_click_url'];
			}else {
			    $res_info['data']['coupon_click_url_r']='';
			}
			
			//优惠券总量
			if($gy_data['coupon_total_count']) {
				$res_info['data']['coupon_total_count']=$gy_data['coupon_total_count'];
			}else {
				$res_info['data']['coupon_total_count']=0;
			}
			//优惠券剩余量
			if($gy_data['coupon_remain_count']) {
				$res_info['data']['coupon_remain_count']=$gy_data['coupon_remain_count'];
			}else {
				$res_info['data']['coupon_remain_count']=0;
			}
			//优惠券开始时间
			if($gy_data['coupon_start_time']) {
				$res_info['data']['coupon_start_time']=$gy_data['coupon_start_time'];
			}else {
				$res_info['data']['coupon_start_time']='';
			}
			//优惠券结束时间
			if($gy_data['coupon_end_time']) {
				$res_info['data']['coupon_end_time']=$gy_data['coupon_end_time'];
			}else {
				$res_info['data']['coupon_end_time']='';
			}
			//优惠券信息
			if($gy_data['coupon_info']) {
				$res_info['data']['coupon_info']=$gy_data['coupon_info'];
				//优惠券面额
				$pos1=strpos($gy_data['coupon_info'],'减');
				$pos2=strripos($gy_data['coupon_info'],'元');
				$res_info['data']['coupon_amount']=substr($gy_data['coupon_info'], $pos1+3,$pos2-$pos1-3);
			}else {
				$res_info['data']['coupon_info']='';
				$res_info['data']['coupon_amount']=0;
			}
			//优惠券推广链接-默认链接地址，有优惠券的情况下会进行替换
			if($gy_data['coupon_click_url']) {
				$res_info['data']['coupon_click_url']=$gy_data['coupon_click_url'];
			}else {
				$res_info['data']['coupon_click_url']="https://uland.taobao.com/coupon/edetail?itemId=$num_iid&pid=$pid";
			}
			//佣金比率(%)
			//维易淘客
			$res_info['data']['commission_rate']=$gy_data['commission_rate'];
			//上海淘客
			//$res_info['data']['commission_rate']=$gy_data['max_commission_rate'];
			//佣金
			$res_info['data']['commission']=($res_info['data']['zk_final_price']-$res_info['data']['coupon_amount'])*$res_info['data']['commission_rate']/100;
			//保留2位小数，四舍五不入
			$res_info['data']['commission']=substr(sprintf("%.3f",$res_info['data']['commission']),0,-1);
			
			//商品详情页面地址
			$res_info['data']['item_url']=$gy_data['item_url'];
			
			//获取商品详情内容
			$content_url='https://mdetail.tmall.com/templates/pages/desc?id='.$num_iid;
			$res_info['data']['content_url']=$content_url;
			
			$res=$res_info;
		}else {
			$res=$res_info;
		}
		return $res;
	}
	
	/**
	 * 高佣转链
	 * @param int $num_iid:淘宝商品ID
	 * @param string $pid:推广位
	 * @param string $relationId:渠道ID
	 */
	public function generateUrl($num_iid,$pid='',$relationId ='')
	{
	    if($pid=='') {
	        //没有的情况下使用默认推广位
	        $pid=$this->pid;
	        $adzone_id=$this->adzone_id;
	    }
	    //查询商品佣金以及优惠券
	    //调用高佣接口
	    //维易淘客-【辅助接口使用】id转高佣淘口令接口
	    $gy_appkey=$this->gy_appkey_wy;
	    //此API不需要授权，适用于在已知产品有优惠券情况下（比如产品列表页传参）可以直接调用。不适用于对无优惠券商品的转链。
	    $url_gy=$this->url_wy."/hcapi?vekey=$gy_appkey&para=$num_iid&pid=$pid";
	    $result_json_gy=https_request($url_gy);
	    $result_gy=json_decode($result_json_gy,true);
	    $gy_data=$result_gy['data'];
	    echo json_encode ($gy_data,JSON_UNESCAPED_UNICODE);die();
	    
	    //生成渠道分享链接
	    if($relationId){
	        $url_gy_r=$this->url_wy."/hcapi?vekey=$gy_appkey&para=$num_iid&pid=$this->relation_pid&relationId=$relationId";
	        $result_json_gy_r=https_request($url_gy_r);
	        $result_gy_r=json_decode($result_json_gy_r,true);
	        $gy_data_r=$result_gy_r['data'];
	        $res_info['data']['coupon_click_url_r']=$gy_data_r['coupon_click_url'];
	    }else {
	        $res_info['data']['coupon_click_url_r']='';
	    }
	    
	    //优惠券总量
	    if($gy_data['coupon_total_count']) {
	        $res_info['data']['coupon_total_count']=$gy_data['coupon_total_count'];
	    }else {
	        $res_info['data']['coupon_total_count']=0;
	    }
	    //优惠券剩余量
	    if($gy_data['coupon_remain_count']) {
	        $res_info['data']['coupon_remain_count']=$gy_data['coupon_remain_count'];
	    }else {
	        $res_info['data']['coupon_remain_count']=0;
	    }
	    //优惠券开始时间
	    if($gy_data['coupon_start_time']) {
	        $res_info['data']['coupon_start_time']=$gy_data['coupon_start_time'];
	    }else {
	        $res_info['data']['coupon_start_time']='';
	    }
	    //优惠券结束时间
	    if($gy_data['coupon_end_time']) {
	        $res_info['data']['coupon_end_time']=$gy_data['coupon_end_time'];
	    }else {
	        $res_info['data']['coupon_end_time']='';
	    }
	    //优惠券信息
	    if($gy_data['coupon_info']) {
	        $res_info['data']['coupon_info']=$gy_data['coupon_info'];
	        //优惠券面额
	        $pos1=strpos($gy_data['coupon_info'],'减');
	        $pos2=strripos($gy_data['coupon_info'],'元');
	        $res_info['data']['coupon_amount']=substr($gy_data['coupon_info'], $pos1+3,$pos2-$pos1-3);
	    }else {
	        $res_info['data']['coupon_info']='';
	        $res_info['data']['coupon_amount']=0;
	    }
	    //优惠券推广链接-默认链接地址，有优惠券的情况下会进行替换
	    if($gy_data['coupon_click_url']) {
	        $res_info['data']['coupon_click_url']=$gy_data['coupon_click_url'];
	    }else {
	        $res_info['data']['coupon_click_url']="https://uland.taobao.com/coupon/edetail?itemId=$num_iid&pid=$pid";
	    }
	    //佣金比率(%)
	    //维易淘客
	    $res_info['data']['commission_rate']=$gy_data['commission_rate'];
	    //佣金
	    $res_info['data']['commission']=($res_info['data']['zk_final_price']-$res_info['data']['coupon_amount'])*$res_info['data']['commission_rate']/100;
	    //保留2位小数，四舍五不入
	    $res_info['data']['commission']=substr(sprintf("%.3f",$res_info['data']['commission']),0,-1);
	    
	    //商品详情页面地址
	    $res_info['data']['item_url']=$gy_data['item_url'];
	    
	    //获取商品详情内容
	    $content_url='https://mdetail.tmall.com/templates/pages/desc?id='.$num_iid;
	    $res_info['data']['content_url']=$content_url;
	    
	    $res=$res_info;
	}
	
	/**
	 * 淘宝客商品详情
	 * @param string $num_iids:商品ID串，用,分割，最大40个
	 * @param string $platform:链接形式：1：PC，2：无线，默认：１
	 * @param string $ip:ip地址，影响邮费获取，如果不传或者传入不准确，邮费无法精准提供
	 * @return array
	 * @return @param code:返回码
	 * @return @param msg:返回码说明
	 * @return @param data:返回数据
	 * @return @param data->cat_name:一级类目名称
	 * @return @param data->num_iid:商品ID
	 * @return @param data->title:商品标题
	 * @return @param data->pict_url:商品主图
	 * @return @param data->small_images:商品小图列表
	 * @return @param data->reserve_price:商品一口价格
	 * @return @param data->zk_final_price:商品折扣价格
	 * @return @param data->user_type:卖家类型，0表示集市，1表示商城
	 * @return @param data->provcity:商品所在地
	 * @return @param data->item_url:商品链接
	 * @return @param data->seller_id:卖家id
	 * @return @param data->volume:30天销量
	 * @return @param data->nick:店铺名称
	 * @return @param data->cat_leaf_name:叶子类目名称
	 * @return @param data->is_prepay:是否加入消费者保障
	 * @return @param data->shop_dsr:店铺dsr 评分
	 * @return @param data->ratesum:卖家等级
	 * @return @param data->i_rfd_rate:退款率是否低于行业均值
	 * @return @param data->h_good_rate:好评率是否高于行业均值
	 * @return @param data->h_pay_rate30:成交转化是否高于行业均值
	 * @return @param data->free_shipment:是否包邮
	 * @return @param data->coupon_total_count:优惠券总量
	 * @return @param data->coupon_remain_count:优惠券剩余量
	 * @return @param data->coupon_start_time:优惠券开始时间
	 * @return @param data->coupon_end_time:优惠券结束时间
	 * @return @param data->coupon_info:优惠券信息
	 * @return @param data->coupon_amount:优惠券面额
	 * @return @param data->commission_rate:佣金比率(%)
	 * @return @param data->commission:佣金
	 */
	public function getItemList($num_iids,$platform='1',$ip='')
	{
		$res_info=$this->getItemInfo($num_iids,$platform,$ip);
		if($res_info['code']==0) {
			if($res_info['data'][0]['num_iid']) {
				//多件商品
				$list_tmp=$res_info['data'];
				$num1=count($list_tmp);
				$list=array();
				for ($i=0;$i<$num1;$i++)
				{
					$list[]=$list_tmp[$i];
				}
			}else {
				//一件商品
				$list[]=$res_info['data'];
			}
			$num=count($list);
			
			$pid=$this->pid;
			for ($i=0;$i<$num;$i++) {
				//判断是天猫还是淘宝商品
				if($list[$i]['user_type']=='1') {
					$list[$i]['is_tmall']=true;
				}else {
					$list[$i]['is_tmall']=false;
				}
				
				//查询商品佣金以及优惠券
				$num_iid=$list[$i]['num_iid'];
				
				//调用高佣接口
				$gy_appkey=$this->gy_appkey_wy;
				//维易淘客-【辅助接口使用】id转高佣淘口令接口
				//此API不需要授权，适用于在已知产品有优惠券情况下（比如产品列表页传参）可以直接调用。不适用于对无优惠券商品的转链。
				$url_gy=$this->url_wy."/hcapi?vekey=$gy_appkey&para=$num_iid&pid=$pid";
				$result_json_gy=https_request($url_gy);
				$result_gy=json_decode($result_json_gy,true);
				$gy_data=$result_gy['data'];
				
				//优惠券总量
				if($gy_data['coupon_total_count']) {
					$list[$i]['coupon_total_count']=$gy_data['coupon_total_count'];
				}else {
					$list[$i]['coupon_total_count']=0;
				}
				//优惠券剩余量
				if($gy_data['coupon_remain_count']) {
					$list[$i]['coupon_remain_count']=$gy_data['coupon_remain_count'];
				}else {
					$list[$i]['coupon_remain_count']=0;
				}
				//优惠券开始时间
				if($gy_data['coupon_start_time']) {
					$list[$i]['coupon_start_time']=$gy_data['coupon_start_time'];
				}else {
					$list[$i]['coupon_start_time']='';
				}
				//优惠券结束时间
				if($gy_data['coupon_end_time']) {
					$list[$i]['coupon_end_time']=$gy_data['coupon_end_time'];
				}else {
					$list[$i]['coupon_end_time']='';
				}
				//优惠券信息
				if($gy_data['coupon_info']) {
					$list[$i]['coupon_info']=$gy_data['coupon_info'];
					//优惠券面额
					$pos1=strpos($gy_data['coupon_info'],'减');
					$pos2=strripos($gy_data['coupon_info'],'元');
					$list[$i]['coupon_amount']=substr($gy_data['coupon_info'], $pos1+3,$pos2-$pos1-3);
				}else {
					$list[$i]['coupon_info']='';
					$list[$i]['coupon_amount']=0;
				}
				//优惠券推广链接-默认链接地址，有优惠券的情况下会进行替换
				if($gy_data['coupon_click_url']) {
					$list[$i]['coupon_click_url']=$gy_data['coupon_click_url'];
				}else {
					$list[$i]['coupon_click_url']="https://uland.taobao.com/coupon/edetail?itemId=$num_iid&pid=$pid";
				}
				//佣金比率(%)
				$list[$i]['commission_rate']=$gy_data['commission_rate'];
				//佣金
				$list[$i]['commission']=($list[$i]['zk_final_price']-$list[$i]['coupon_amount'])*$list[$i]['commission_rate']/100;
				//保留2位小数，四舍五不入
				$list[$i]['commission']=substr(sprintf("%.3f",$list[$i]['commission']),0,-1);
					
				//商品详情页面地址
				$list[$i]['item_url']=$gy_data['item_url'];
					
				//获取商品详情内容
				$content_url='https://mdetail.tmall.com/templates/pages/desc?id='.$num_iid;
				$list[$i]['content_url']=$content_url;
				
			}
			$data=array(
					'list'=>$list
			);
			$res=array(
					'code'=>0,
					'msg'=>'成功',
					'data'=>$data
			);
		}else {
			$res=$res_info;
		}
		return $res;
	}
	
	/**
	 * 获取商品优惠券信息
	 * @param int $num_iid:商品ID
	 * @return array
	 */
	public function getCouponCommission($num_iid)
	{
	    $pid=$this->pid;
	    //调用高佣接口
	    //维易淘客-【辅助接口使用】id转高佣淘口令接口
	    $gy_appkey=$this->gy_appkey_wy;
	    //此API不需要授权，适用于在已知产品有优惠券情况下（比如产品列表页传参）可以直接调用。不适用于对无优惠券商品的转链。
	    $url_gy=$this->url_wy."/hcapi?vekey=$gy_appkey&para=$num_iid&pid=$pid";
	    $result_json_gy=https_request($url_gy);
	    $result_gy=json_decode($result_json_gy,true);
	    $gy_data=$result_gy['data'];
	    return $gy_data;
	}
	
	/**
	 * 获取淘宝联盟选品库列表
	 * @param number $page_no:第几页，从1开始计数
	 * @param number $page_size:默认20，页大小，即每一页的活动个数
	 * @param string $type:默认值-1；选品库类型，1：普通选品组，2：高佣选品组，-1，同时输出所有类型的选品组
	 * @return array
	 * @return @param code:返回码
	 * @return @param msg:返回码说明
	 * @return @param data:返回数据
	 * @return @param data->favorites_id:选品库id
	 * @return @param data->favorites_title:选品组名称
	 * @return @param data->type:选品库类型，1：普通类型，2高佣金类型
	 */
	public function getFavoritesUatm($page_no=1,$page_size=20,$type='-1')
	{
		$c = new TopClient;
		$c->appkey = $this->appkey;
		$c->secretKey = $this->secret;
		$req = new TbkUatmFavoritesGetRequest;
		$req->setPageNo($page_no);
		$req->setPageSize($page_size);
		$req->setFields("favorites_title,favorites_id,type");
		$req->setType($type);
		$resp = $c->execute($req);
		$result=json_decode(json_encode($resp),true);
		if($result['code']) {
			$res=array(
					'code'=>$result['sub_code'],
					'msg'=>$result['sub_msg'],
			);
		}else {
			$res=array(
					'code'=>0,
					'msg'=>'成功',
					'data'=>$result['results']['tbk_favorites']
			);
		}
		return $res;
	}
	
	/**
	 * 获取淘宝联盟选品库的宝贝信息
	 * @param unknown $favorites_id:选品库的id
	 * @param string $platform:链接形式：1：PC，2：无线，默认：１
	 * @param number $page_no:第几页，默认：1，从1开始计数
	 * @param number $page_size:页大小，默认20，1~100
	 * @param unknown $adzone_id:推广位id，需要在淘宝联盟后台创建；且属于appkey备案的媒体id（siteid），如何获取adzoneid，请参考，http://club.alimama.com/read-htm-tid-6333967.html?spm=0.0.0.0.msZnx5
	 * @param string $unid:自定义输入串，英文和数字组成，长度不能大于12个字符，区分不同的推广渠道
	 * @return array
	 * @return @param code:返回码
	 * @return @param msg:返回码说明
	 * @return @param data:返回数据
	 * @return @param data->num_iid:商品ID
	 * @return @param data->title:商品标题
	 * @return @param data->pict_url:商品主图
	 * @return @param data->small_images:商品小图列表
	 * @return @param data->reserve_price:商品一口价格
	 * @return @param data->zk_final_price:商品折扣价格
	 * @return @param data->user_type:卖家类型，0表示集市，1表示商城
	 * @return @param data->provcity:宝贝所在地
	 * @return @param data->item_url:商品地址
	 * @return @param data->click_url:淘客地址
	 * @return @param data->nick:卖家昵称
	 * @return @param data->seller_id:卖家id
	 * @return @param data->volume:30天销量
	 * @return @param data->tk_rate:收入比例，举例，取值为20.00，表示比例20.00%
	 * @return @param data->zk_final_price_wap:无线折扣价，即宝贝在无线上的实际售卖价格。
	 * @return @param data->shop_title:店铺名称
	 * @return @param data->event_start_time:招商活动开始时间；如果该宝贝取自普通选品组，则取值为1970-01-01 00:00:00；
	 * @return @param data->event_end_time:招行活动的结束时间；如果该宝贝取自普通的选品组，则取值为1970-01-01 00:00:00
	 * @return @param data->type:宝贝类型：1 普通商品； 2 鹊桥高佣金商品；3 定向招商商品；4 营销计划商品;
	 * @return @param data->status:宝贝状态，0失效，1有效；注：失效可能是宝贝已经下线或者是被处罚不能在进行推广
	 * @return @param data->category:后台一级类目
	 * @return @param data->coupon_click_url:商品优惠券推广链接
	 * @return @param data->coupon_end_time:优惠券结束时间
	 * @return @param data->coupon_info:优惠券面额
	 * @return @param data->coupon_start_time:优惠券开始时间
	 * @return @param data->coupon_total_count:优惠券总量
	 * @return @param data->coupon_remain_count:优惠券剩余量
	 */
	public function getFavoritesUatmItem($favorites_id,$platform='1',$page_no=1,$page_size=20,$adzone_id,$unid='')
	{
		$c = new TopClient;
		$c->appkey = $this->appkey;
		$c->secretKey = $this->secret;
		$req = new TbkUatmFavoritesItemGetRequest;
		$req->setPlatform($platform);
		$req->setPageSize($page_size);
		$req->setAdzoneId($adzone_id);
		if($unid) {
			$req->setUnid($unid);
		}
		$req->setFavoritesId($favorites_id);
		$req->setPageNo($page_no);
		$req->setFields("num_iid,title,pict_url,small_images,reserve_price,zk_final_price,user_type,provcity,item_url,seller_id,volume,nick,shop_title,zk_final_price_wap,event_start_time,event_end_time,tk_rate,status,type");
		$resp = $c->execute($req);
		$result=json_decode(json_encode($resp),true);
		if($result['code']) {
			$res=array(
					'code'=>$result['sub_code'],
					'msg'=>$result['sub_msg'],
			);
		}else {
			$res=array(
					'code'=>0,
					'msg'=>'成功',
					'data'=>$result['results']['uatm_tbk_item']
			);
		}
		return $res;
	}
	
	/**
	 * 获取淘抢购的数据，淘客商品转淘客链接，非淘客商品输出普通链接
	 * @param Number $adzone_id:推广位id（推广位申请方式：http://club.alimama.com/read.php?spm=0.0.0.0.npQdST&tid=6306396&ds=1&page=1&toread=1）
	 * @param DateTime $start_time:最早开团时间，2016-08-09 09:00:00
	 * @param DateTime $end_time:最晚开团时间，2016-08-09 09:00:00
	 * @param number $page_no:第几页，默认1，1~100
	 * @param number $page_size:页大小，默认40，1~40
	 * @return array
	 * @return @param code:返回码
	 * @return @param msg:返回码说明
	 * @return @param data:返回数据
	 * @return @param data->title:商品标题
	 * @return @param data->total_amount:总库存
	 * @return @param data->click_url:商品链接（是淘客商品返回淘客链接，非淘客商品返回普通h5链接）
	 * @return @param data->category_name:类目名称
	 * @return @param data->zk_final_price:淘抢购活动价
	 * @return @param data->end_time:结束时间
	 * @return @param data->sold_num:已抢购数量
	 * @return @param data->start_time:开团时间
	 * @return @param data->reserve_price:商品原价
	 * @return @param data->pic_url:商品主图
	 * @return @param data->num_iid:商品ID
	 */
	public function getTqgJu($pid,$adzone_id,$start_time,$end_time,$page_no=1,$page_size=40,$fee_user=100)
	{
		$c = new TopClient;
		$c->appkey = $this->appkey;
		$c->secretKey = $this->secret;
		$req = new TbkJuTqgGetRequest;
		$req->setAdzoneId($adzone_id);
		$req->setFields("click_url,pic_url,reserve_price,zk_final_price,total_amount,sold_num,title,category_name,start_time,end_time");
		$req->setStartTime($start_time);
		$req->setEndTime($end_time);
		$req->setPageNo($page_no);
		$req->setPageSize($page_size);
		$resp = $c->execute($req);
		$result=json_decode(json_encode($resp),true);
		if($result['code']) {
			$res=array(
					'code'=>$result['code'],
					'msg'=>$result['msg'],
			);
		}else {
			//商品列表
			$goodslist=$result['results']['results'];
			$num=count($goodslist);
			$gy_appkey=$this->gy_appkey_wy;
			for($i=0;$i<$num;$i++) {
				$num_iid=$goodslist[$i]['num_iid'];
				//查询商品佣金
				//调用高佣接口
				//维易淘客-【辅助接口使用】id转高佣淘口令接口
				//此API不需要授权，适用于在已知产品有优惠券情况下（比如产品列表页传参）可以直接调用。不适用于对无优惠券商品的转链。
				$url_gy=$this->url_wy."/hcapi?vekey=$gy_appkey&para=$num_iid&pid=$pid";
				$result_json_gy=https_request($url_gy);
				$result_gy=json_decode($result_json_gy,true);
				$gy_data=$result_gy['data'];
					
				//优惠券总量
				if($gy_data['coupon_total_count']) {
					$goodslist[$i]['coupon_total_count']=$gy_data['coupon_total_count'];
				}else {
					$goodslist[$i]['coupon_total_count']=0;
				}
				//优惠券剩余量
				if($gy_data['coupon_remain_count']) {
					$goodslist[$i]['coupon_remain_count']=$gy_data['coupon_remain_count'];
				}else {
					$goodslist[$i]['coupon_remain_count']=0;
				}
				//优惠券开始时间
				if($gy_data['coupon_start_time']) {
					$goodslist[$i]['coupon_start_time']=$gy_data['coupon_start_time'];
				}else {
					$goodslist[$i]['coupon_start_time']='';
				}
				//优惠券结束时间
				if($gy_data['coupon_end_time']) {
					$goodslist[$i]['coupon_end_time']=$gy_data['coupon_end_time'];
				}else {
					$goodslist[$i]['coupon_end_time']='';
				}
				//优惠券信息
				if($gy_data['coupon_info']) {
					$goodslist[$i]['coupon_info']=$gy_data['coupon_info'];
					//优惠券面额
					$pos1=strpos($gy_data['coupon_info'],'减');
					$pos2=strripos($gy_data['coupon_info'],'元');
					$goodslist[$i]['coupon_amount']=substr($gy_data['coupon_info'], $pos1+3,$pos2-$pos1-3);
				}else {
					$goodslist[$i]['coupon_info']='';
					$goodslist[$i]['coupon_amount']=0;
				}
				//佣金比率(%)
				$goodslist[$i]['commission_rate']=$gy_data['commission_rate'];
				//佣金
				$goodslist[$i]['commission']=($goodslist[$i]['zk_final_price']-$goodslist[$i]['coupon_amount'])*$goodslist[$i]['commission_rate']/100*$fee_user/100;;
				//保留2位小数，四舍五不入
				$goodslist[$i]['commission']=substr(sprintf("%.3f",$goodslist[$i]['commission']),0,-1);	
			}
			$data=array(
					'list'=>$goodslist
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
	 * 搜索聚划算商品
	 * @param number $current_page:页码,必传,默认1
	 * @param number $page_size:一页大小,必传，默认20
	 * @param unknown $pid:媒体pid,必传
	 * @param string $postage:是否包邮,可不传
	 * @param string $status:状态，预热：1，正在进行中：2,可不传
	 * @param string $taobao_category_id:淘宝类目id,可不传
	 * @param string $word:搜索关键词,可不传
	 * @return array
	 * @return @param code:返回码
	 * @return @param msg:返回码说明
	 * @return @param data:返回数据
	 * @return @param data->current_page:当前页码，默认1
	 * @return @param data->model_list:商品数据
	 * @return @param data->model_list->usp_desc_list:卖点描述
	 * @return @param data->model_list->tb_first_cat_id:淘宝类目id
	 * @return @param data->model_list->orig_price:原价
	 * @return @param data->model_list->item_id:商品ID
	 * @return @param data->model_list->show_end_time:展示结束时间
	 * @return @param data->model_list->pc_url:pc链接
	 * @return @param data->model_list->platform_id:频道id
	 * @return @param data->model_list->ju_id:聚划算id
	 * @return @param data->model_list->pic_url_for_w_l:无线主图
	 * @return @param data->model_list->online_start_time:开团时间
	 * @return @param data->model_list->category_name:类目名称
	 * @return @param data->model_list->act_price:聚划算价格，单位分
	 * @return @param data->model_list->title:商品标题
	 * @return @param data->model_list->wap_url:无线链接
	 * @return @param data->model_list->item_usp_list:商品卖点
	 * @return @param data->model_list->show_start_time:开始展示时间
	 * @return @param data->model_list->online_end_time:开团结束时间
	 * @return @param data->model_list->pic_url_for_p_c:pc主图
	 * @return @param data->model_list->price_usp_list:pc价格卖点
	 * @return @param data->model_list->pay_postage:是否包邮
	 * @return @param data->page_size:一页大小
	 * @return @param data->success:请求是否成功
	 * @return @param data->total_item:商品总数
	 * @return @param data->total_page:总页数
	 */
	public function searchJuItems($current_page=1,$page_size=20,$pid='',$postage='',$status='2',$taobao_category_id='',$word='')
	{
		if($pid=='') {
			//没有的情况下使用默认推广位
			$pid=$this->pid;
			$adzone_id=$this->adzone_id;
		}else {
			//使用用户绑定的推广位、广告位
			$start=strripos($pid, '_');
			$adzone_id=substr($pid, $start+1);
		}
		$c = new TopClient;
		$c->appkey = $this->appkey;
		$c->secretKey = $this->secret;
		$req = new JuItemsSearchRequest;
		$param_top_item_query = new TopItemQuery;
		$param_top_item_query->current_page=$current_page;
		$param_top_item_query->page_size=$page_size;
		$param_top_item_query->pid=$pid;
		if($postage) {
			$param_top_item_query->postage=$postage;
		}
		$param_top_item_query->status=$status;
		if($taobao_category_id) {
			$param_top_item_query->taobao_category_id=$taobao_category_id;
		}
		if($word) {
			$param_top_item_query->word=$word;
		}
		$req->setParamTopItemQuery(json_encode($param_top_item_query));
		$resp = $c->execute($req);
		$result=json_decode(json_encode($resp),true);
		
		if($result['code']) {
			$res=array(
					'code'=>$result['code'],
					'msg'=>$result['msg'],
			);
		}else {
			$res=array(
					'code'=>0,
					'msg'=>'成功',
					'data'=>$result['result']
			);
		}
		return $res;
	}
	
	/**
	 * 好券清单
	 * @param Number $adzone_id:mm_xxx_xxx_xxx的第三位
	 * @param string $platform:1：PC，2：无线，默认：1
	 * @param string $q:查询词
	 * @param string $cat:后台类目ID，用,分割，最大10个，该ID可以通过taobao.itemcats.get接口获取到
	 * @param number $page_no:第几页，默认：1（当后台类目和查询词均不指定的时候，最多出10000个结果，即page_no*page_size不能超过10000；当指定类目或关键词的时候，则最多出100个结果）
	 * @param number $page_size:页大小，默认20，1~100
	 * @return array
	 * @return @param code:返回码
	 * @return @param msg:返回码说明
	 * @return @param data:返回数据
	 * @return @param data->small_images:商品小图列表
	 * @return @param data->shop_title:店铺名称
	 * @return @param data->user_type:卖家类型，0表示集市，1表示商城
	 * @return @param data->zk_final_price:折扣价
	 * @return @param data->title:商品标题
	 * @return @param data->nick:卖家昵称
	 * @return @param data->seller_id:卖家id
	 * @return @param data->volume:30天销量
	 * @return @param data->pict_url:商品主图
	 * @return @param data->item_url:商品详情页链接地址
	 * @return @param data->coupon_total_count:优惠券总量
	 * @return @param data->commission_rate:佣金比率(%)
	 * @return @param data->coupon_info:优惠券面额
	 * @return @param data->category:后台一级类目
	 * @return @param data->num_iid:itemId
	 * @return @param data->coupon_remain_count:优惠券剩余量
	 * @return @param data->coupon_start_time:优惠券开始时间
	 * @return @param data->coupon_end_time:优惠券结束时间
	 * @return @param data->coupon_click_url:商品优惠券推广链接
	 * @return @param data->item_description:宝贝描述（推荐理由）
	 */
	public function getCouponItem($adzone_id,$platform='1',$q='',$cat='',$page_no=1,$page_size=20)
	{
		$c = new TopClient;
		$c->appkey = $this->appkey;
		$c->secretKey = $this->secret;
		$req = new TbkDgItemCouponGetRequest;
		$req->setAdzoneId($adzone_id);
		$req->setPlatform($platform);
		if($cat)
		{
			$req->setCat($cat);
		}
		$req->setPageNo($page_no);
		$req->setPageSize($page_size);
		if($q)
		{
			$req->setQ($q);
		}
		$resp = $c->execute($req);
		$result=json_decode(json_encode($resp),true);
		if($result['code'])
		{
			$res=array(
					'code'=>$result['sub_code'],
					'msg'=>$result['sub_msg'],
			);
		}else {
			if($result['results']['tbk_coupon'][0]['category'])
			{
				$res=array(
						'code'=>0,
						'msg'=>'成功',
						'data'=>$result['results']['tbk_coupon']
				);
			}else {
				$list[0]=$result['results']['tbk_coupon'];
				$res=array(
						'code'=>0,
						'msg'=>'成功',
						'data'=>$list
				);
			}
		}
		return $res;
	}
	
	/**
	 * 淘宝客淘口令
	 * @param string $user_id:生成口令的淘宝用户ID
	 * @param string $text:口令弹框内容，长度大于5个字符，必填
	 * @param string $url:口令跳转目标页，必填，口令跳转url不支持口令转换=》修改口令跳转url，且以https开头
	 * @param string $logo:口令弹框logoURL
	 * @param string $ext:扩展字段JSON格式
	 * @return array
	 */
	public function createTpwd($user_id='',$text,$url,$logo='',$ext='')
	{
		$c = new TopClient;
		$c->appkey = $this->appkey;
		$c->secretKey = $this->secret;
		$req = new TbkTpwdCreateRequest;
		if($user_id) {
			$req->setUserId($user_id);
		}
		$req->setText($text);
		$req->setUrl($url);
		if($logo) {
			$req->setLogo($logo);
		}
		if($ext) {
			$req->setExt($ext);
		}
		$resp = $c->execute($req);
		$result=json_decode(json_encode($resp),true);
		if($result['code']) {
			$res=array(
					'code'=>$result['sub_code'],
					'msg'=>$result['sub_msg'],
			);
		}else {
			$res=array(
					'code'=>0,
					'msg'=>'成功',
					'data'=>$result['data']['model']
			);
		}
		return $res;
	}
	
	/**
	 * 查询解析淘口令
	 * @param string $content:淘口令，如：【天猫品牌号】，复制这条信息￥sMCl0Yra3Ae￥后打开手机淘宝
	 * @return array
	 * @return @param code:返回码
	 * @return @param msg:返回码说明
	 * @return @param data:返回数据
	 * @return @param data->content:淘口令-文案
	 * @return @param data->title:淘口令-宝贝
	 * @return @param data->price:如果是宝贝，则为宝贝价格
	 * @return @param data->pic_url:图片url
	 * @return @param data->suc:是否成功
	 * @return @param data->url:跳转url(长链)
	 * @return @param data->native_url:nativeUrl
	 * @return @param data->thumb_pic_url:thumbPicUrl
	 */
	public function wirelessShareTpwdQuery($content)
	{
		$c = new TopClient;
		//这里用的是网站推广-联盟合作网站API
		$c->appkey = $this->appkey;
		$c->secretKey = $this->secret;
		$req = new WirelessShareTpwdQueryRequest;
		$req->setPasswordContent($content);
		$resp = $c->execute($req);
		$result=json_decode(json_encode($resp),true);
		if($result['suc']=='true') {
			$res=array(
					'code'=>0,
					'msg'=>'成功',
					'data'=>$result
			);
		}else {
			$res=array(
					'code'=>1,
					'msg'=>'淘口令不存在/淘口令解析失败！',
			);
		}
		return $res;
	}
	
	/**
	 * 解析淘口令
	 * @param string $tkl:淘口令，如rIEwb0PmM16
	 * @return array
	 */
	public function resolveTkl($tkl)
	{
		// 获取cookie
		$url = 'http://api.m.taobao.com/h5/com.taobao.redbull.getpassworddetail/1.0?v=1.0'; // url地址
		$ch = curl_init ( $url );
		curl_setopt ( $ch, CURLOPT_HEADER, 1 );
		curl_setopt ( $ch, CURLOPT_HTTPHEADER, array(
				'User-Agent: Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/55.0.2883.87 Safari/537.36',
				'Accept: */*',
				'Referer: http://api.m.taobao.com/h5/com.taobao.redbull.getpassworddetail/1.0?v=1.0',
				'Accept-Language: zh-cn',
				'Content-Type: application/x-www-form-urlencoded',
				'Host: api.m.taobao.com',
				'Cache-Control: no-cache'
		));
		curl_setopt ( $ch, CURLOPT_ENCODING, "gzip, deflate, sdch");
		curl_setopt ( $ch, CURLOPT_RETURNTRANSFER, 1 );
		$content = curl_exec ( $ch );
		preg_match_all ( '/Set-Cookie:(.*);/iU', $content, $str );
		curl_close ( $ch );
		$cookie = null;
		for($i = 0; $i < count ( $str ); $i ++) 
		{
			$cookie .= $str [1] [$i] . ';';
		}
		
		// 利用cookie去解析
		preg_match ( '/_m_h5_tk=(.*)_/iU', $cookie, $_m_h5_tk ); // 正则匹配;
		$time=time();
		$sign = md5 ( $_m_h5_tk [1] . '&' . $time . '&12574478&{"password":"' . $tkl . '"}' );
		$url .= '&api=com.taobao.redbull.getpassworddetail&appKey=12574478&t=' . $time . '&sign=' . $sign . '&data=' . urlencode ( '{"password":"' . $tkl . '"}' );
		
		$ch = curl_init ( $url );
		curl_setopt ( $ch, CURLOPT_RETURNTRANSFER, 1 );
		curl_setopt ( $ch, CURLOPT_HTTPHEADER, array(
				'User-Agent: Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/55.0.2883.87 Safari/537.36',
				'Accept: */*',
				'Referer: '.$url,
				'Accept-Language: zh-cn',
				'Content-Type: application/x-www-form-urlencoded',
				'Host: api.m.taobao.com',
				'Cache-Control: no-cache',
				"Cookie: ".$cookie,
		));
		curl_setopt ( $ch, CURLOPT_CONNECTTIMEOUT, 5 );
		curl_setopt ( $ch, CURLOPT_HEADER, false );
		//curl_setopt ( $ch, CURLOPT_COOKIE, $cookie );
		$taotoken = curl_exec ( $ch );
		curl_close ( $ch );
		$GetTaotoken = json_decode ( $taotoken, true );
		return $GetTaotoken;
	}
	
	/**
	 * 阿里妈妈推广券信息查询
	 * @param string $me:带券ID与商品ID的加密串
	 * @param string $item_id:商品ID
	 * @param string $activity_id:券ID
	 * @return array
	 */
	public function getCouponMsg($me='',$item_id='',$activity_id='')
	{
		$c = new TopClient;
		$c->appkey = $this->appkey;
		$c->secretKey = $this->secret;
		$req = new TbkCouponGetRequest;
		if($me) {
			$req->setMe($me);
		}
		if($item_id) {
			$req->setItemId($item_id);
		}
		if($activity_id) {
			$req->setActivityId($activity_id);
		}
		$resp = $c->execute($req);
		$result=json_decode(json_encode($resp),true);
		if($result['code']) {
			$res=array(
					'code'=>$result['sub_code'],
					'msg'=>$result['sub_msg'],
			);
		}else {
			$res=array(
					'code'=>0,
					'msg'=>'成功',
					'data'=>$result['data']
			);
		}
		return $res;
	}
	
	/**
	 * 解析店铺优惠券
	 * @param string $tkl:淘口令，如rIEwb0PmM16
	 * @return array
	 */
	public function resolveCoupon($url)
	{
		// 获取cookie
		//$url = 'http://api.m.taobao.com/h5/com.taobao.redbull.getpassworddetail/1.0?v=1.0'; // url地址
		$ch = curl_init ( $url );
		curl_setopt ( $ch, CURLOPT_HEADER, 1 );
		curl_setopt ( $ch, CURLOPT_HTTPHEADER, array(
			'User-Agent: Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/55.0.2883.87 Safari/537.36',
			'Accept: */*',
			'Referer: http://api.m.taobao.com/h5/com.taobao.redbull.getpassworddetail/1.0?v=1.0',
			'Accept-Language: zh-cn',
			'Content-Type: application/x-www-form-urlencoded',
			'Host: uland.taobao.com',
			'Cache-Control: no-cache'
		));
		curl_setopt ( $ch, CURLOPT_ENCODING, "gzip, deflate, sdch");
		curl_setopt ( $ch, CURLOPT_RETURNTRANSFER, 1 );
		$content = curl_exec ( $ch );
		dump($content);die();
		preg_match_all ( '/Set-Cookie:(.*);/iU', $content, $str );
		curl_close ( $ch );
		$cookie = null;
		for($i = 0; $i < count ( $str ); $i ++)
		{
			$cookie .= $str [1] [$i] . ';';
		}
	
				// 利用cookie去解析
				preg_match ( '/_m_h5_tk=(.*)_/iU', $cookie, $_m_h5_tk ); // 正则匹配;
				$time=time();
				$sign = md5 ( $_m_h5_tk [1] . '&' . $time . '&12574478&{"password":"' . $tkl . '"}' );
				$url .= '&api=com.taobao.redbull.getpassworddetail&appKey=12574478&t=' . $time . '&sign=' . $sign . '&data=' . urlencode ( '{"password":"' . $tkl . '"}' );
	
				$ch = curl_init ( $url );
				curl_setopt ( $ch, CURLOPT_RETURNTRANSFER, 1 );
				curl_setopt ( $ch, CURLOPT_HTTPHEADER, array(
				'User-Agent: Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/55.0.2883.87 Safari/537.36',
				'Accept: */*',
				'Referer: '.$url,
				'Accept-Language: zh-cn',
				'Content-Type: application/x-www-form-urlencoded',
				'Host: api.m.taobao.com',
				'Cache-Control: no-cache',
				"Cookie: ".$cookie,
				));
				curl_setopt ( $ch, CURLOPT_CONNECTTIMEOUT, 5 );
				curl_setopt ( $ch, CURLOPT_HEADER, false );
				//curl_setopt ( $ch, CURLOPT_COOKIE, $cookie );
				$taotoken = curl_exec ( $ch );
				curl_close ( $ch );
				$GetTaotoken = json_decode ( $taotoken, true );
				return $GetTaotoken;
	}
	
	/**
	 * 
	 * @param unknown $adzone_id
	 * @param string $material_id
	 * @param number $page_no
	 * @param number $page_size
	 * @return mixed|Ambigous <multitype:number string unknown , multitype:unknown mixed >
	 */
	public function dgOptimusMaterial($adzone_id,$material_id='',$page_no=1,$page_size=20)
	{
		$c = new TopClient;
		$c->appkey = $this->appkey;
		$c->secretKey = $this->secret;
		$req = new TbkDgOptimusMaterialRequest;
		$req->setPageNo($page_no);
		$req->setPageSize($page_size);
		$req->setAdzoneId($adzone_id);
		if($material_id) {
			$req->setMaterialId($material_id);
		}
		$resp = $c->execute($req);
		$result=json_decode(json_encode($resp),true);
		return $result;
		if($result['code'])
		{
			$res=array(
					'code'=>$result['sub_code'],
					'msg'=>$result['sub_msg'],
			);
		}else {
			$res=array(
					'code'=>0,
					'msg'=>'成功',
					'data'=>$result['result_list']
			);
		}
		return $res;
	}
	
	/**
	 * 通用物料搜索
	 * @param string $sessionKey:淘宝用户授权key
	 * @param unknown $site_id:site_id
	 * @param unknown $adzone_id:adzone_id
	 * @param string $q:查询词
	 * @param string $has_coupon:是否有优惠券，设置为true表示该商品有优惠券，设置为false或不设置表示不判断这个属性
	 * @param number $page_no:第几页，默认：１
	 * @param number $page_size:页大小，默认20，1~100
	 * @param string $platform:链接形式：1：PC，2：无线，默认：１
	 * @param string $sort:排序_des（降序），排序_asc（升序），销量（total_sales），淘客佣金比率（tk_rate）， 累计推广量（tk_total_sales），总支出佣金（tk_total_commi），价格（price）
	 * @param string $end_tk_rate:淘客佣金比率上限，如：1234表示12.34%
	 * @param string $start_tk_rate:淘客佣金比率下限，如：1234表示12.34%
	 * @param string $end_price:折扣价范围上限，单位：元
	 * @param string $start_price:折扣价范围下限，单位：元
	 * @param string $is_overseas:是否海外商品，设置为true表示该商品是属于海外商品，设置为false或不设置表示不判断这个属性
	 * @param string $is_tmall:是否商城商品，设置为true表示该商品是属于淘宝商城商品，设置为false或不设置表示不判断这个属性
	 * @param string $itemloc:所在地
	 * @param string $cat:后台类目ID，用,分割，最大10个，该ID可以通过taobao.itemcats.get接口获取到
	 * @param string $start_dsr:店铺dsr评分，筛选高于等于当前设置的店铺dsr评分的商品0-50000之间
	 * @param string $ip:ip参数影响邮费获取，如果不传或者传入不准确，邮费无法精准提供
	 * @param string $include_rfd_rate:退款率是否低于行业均值
	 * @param string $include_good_rate:好评率是否高于行业均值
	 * @param string $include_pay_rate_30:成交转化是否高于行业均值
	 * @param string $need_prepay:是否加入消费者保障，true表示加入，空或false表示不限
	 * @param string $need_free_shipment:是否包邮，true表示包邮，空或false表示不限
	 * @param string $npx_level:牛皮癣程度，取值：1:不限，2:无，3:轻微
	 * @return array
	 * @return @param code:返回码
	 * @return @param msg:返回码说明
	 * @return @param data:返回数据
	 * @return @param data->coupon_start_time:优惠券开始时间
	 * @return @param data->coupon_end_time:优惠券结束时间
	 * @return @param data->info_dxjh:定向计划信息
	 * @return @param data->tk_total_sales:淘客30天月推广量
	 * @return @param data->tk_total_commi:月支出佣金
	 * @return @param data->coupon_id:优惠券id
	 * @return @param data->num_iid:宝贝id
	 * @return @param data->title:商品标题
	 * @return @param data->pict_url:商品主图
	 * @return @param data->small_images:商品小图列表
	 * @return @param data->reserve_price:商品一口价格
	 * @return @param data->zk_final_price:商品折扣价格
	 * @return @param data->user_type:卖家类型，0表示集市，1表示商城
	 * @return @param data->provcity:宝贝所在地
	 * @return @param data->item_url:商品地址
	 * @return @param data->include_mkt:是否包含营销计划
	 * @return @param data->include_dxjh:是否包含定向计划
	 * @return @param data->commission_rate:佣金比率
	 * @return @param data->volume:30天销量
	 * @return @param data->seller_id:卖家id
	 * @return @param data->coupon_total_count:优惠券总量
	 * @return @param data->coupon_remain_count:优惠券剩余量
	 * @return @param data->coupon_info:优惠券面额
	 * @return @param data->commission_type:佣金类型
	 * @return @param data->shop_title:店铺名称
	 * @return @param data->url:商品淘客链接
	 * @return @param data->coupon_share_url:券二合一页面链接
	 * @return @param data->shop_dsr:店铺dsr评分
	 * */
	public function scMaterialOptional($sessionKey,$site_id,$adzone_id,$q='',$has_coupon='',$page_no=1,$page_size=20,$platform='1',$sort='tk_total_commi_desc',$end_tk_rate='',$start_tk_rate='',$end_price='',$start_price='',$is_overseas='',$is_tmall='',$itemloc='',$cat='',$start_dsr='',$ip='',$include_rfd_rate='',$include_good_rate='',$include_pay_rate_30='',$need_prepay='',$need_free_shipment='',$npx_level='')
	{
		$c = new TopClient;
		$c->appkey = $this->appkey;
		$c->secretKey = $this->secret;
		$req = new TbkScMaterialOptionalRequest;
		if($start_dsr) {
			$req->setStartDsr($start_dsr);
		}
		$req->setPageNo($page_no);
		$req->setPageSize($page_size);
		$req->setPlatform($platform);
		if($end_tk_rate) {
			$req->setEndTkRate($end_tk_rate);
		}
		if($start_tk_rate) {
			$req->setStartTkRate($start_tk_rate);
		}
		if($end_price) {
			$req->setEndPrice($end_price);
		}
		if($start_price) {
			$req->setStartPrice($start_price);
		}
		if($is_overseas) {
			$req->setIsOverseas($is_overseas);
		}
		if($is_tmall) {
			$req->setIsTmall($is_tmall);
		}
		if($sort) {
			$req->setSort($sort);
		}
		if($itemloc) {
			$req->setItemloc($itemloc);
		}
		if($cat) {
			$req->setCat($cat);
		}
		if($q) {
			$req->setQ($q);
		}
		$req->setAdzoneId($adzone_id);
		$req->setSiteId($site_id);
		if($has_coupon) {
			$req->setHasCoupon($has_coupon);
		}
		if($ip) {
			$req->setIp($ip);
		}
		if($include_rfd_rate) {
			$req->setIncludeRfdRate($include_rfd_rate);
		}
		if($include_good_rate) {
			$req->setIncludeGoodRate($include_good_rate);
		}
		if($include_pay_rate_30) {
			$req->setIncludePayRate30($include_pay_rate_30);
		}
		if($need_prepay) {
			$req->setNeedPrepay($need_prepay);
		}
		if($need_free_shipment) {
			$req->setNeedFreeShipment($need_free_shipment);
		}
		if($npx_level) {
			$req->setNpxLevel($npx_level);
		}
		$resp = $c->execute($req, $sessionKey);
		$result=json_decode(json_encode($resp),true);
		return $result;
		if($result['code']) {
			$res=array(
					'code'=>$result['code'],
					'msg'=>$result['sub_msg'],
			);
		}else {
			$res=array(
					'code'=>0,
					'msg'=>'成功',
					'data'=>$result['result_list']
			);
		}
		return $res;
	}
	
	/**
	 * 通用物料搜索API（导购）
	 * @param number $adzone_id:mm_xxx_xxx_xxx的第三位
	 * @param string $q:查询词
	 * @param string $cat:后台类目ID，用,分割，最大10个，该ID可以通过taobao.itemcats.get接口获取到
	 * @param boolean $has_coupon:是否有优惠券，设置为true表示该商品有优惠券，设置为false或不设置表示不判断这个属性
	 * @param string $sort:排序_des（降序），排序_asc（升序），销量（total_sales），淘客佣金比率（tk_rate）， 累计推广量（tk_total_sales），总支出佣金（tk_total_commi），价格（price）
	 * @param number $page_no:第几页，默认：１
	 * @param number $page_size:页大小，默认20，1~100
	 * @param number $end_tk_rate:淘客佣金比率上限，如：1234表示12.34%
	 * @param number $start_tk_rate:淘客佣金比率下限，如：1234表示12.34%
	 * @param number $end_price:折扣价范围上限，单位：元
	 * @param number $start_price:折扣价范围下限，单位：元
	 * @param boolean $is_overseas:是否海外商品，设置为true表示该商品是属于海外商品，设置为false或不设置表示不判断这个属性
	 * @param boolean $is_tmall:是否商城商品，设置为true表示该商品是属于淘宝商城商品，设置为false或不设置表示不判断这个属性
	 * @param boolean $need_free_shipment:是否包邮，true表示包邮，空或false表示不限
	 * @param boolean $need_prepay:是否加入消费者保障，true表示加入，空或false表示不限
	 * @param boolean $include_pay_rate_30:成交转化是否高于行业均值
	 * @param boolean $include_good_rate:好评率是否高于行业均值
	 * @param boolean $include_rfd_rate:退款率是否低于行业均值
	 * @param boolean $npx_level:牛皮癣程度，取值：1:不限，2:无，3:轻微
	 * @param string $itemloc:所在地
	 * @param string $platform:链接形式：1：PC，2：无线，默认：１
	 * @param string $start_dsr:店铺dsr评分，筛选高于等于当前设置的店铺dsr评分的商品0-50000之间
	 * @param string $ip:ip参数影响邮费获取，如果不传或者传入不准确，邮费无法精准提供
	 * @return array
	 * @return @param code:返回码
	 * @return @param msg:返回码说明
	 * @return @param data:返回数据
	 * @return @param data->num_iid:商品ID
	 * @return @param data->title:商品标题
	 * @return @param data->pict_url:商品主图
	 * @return @param data->small_images:商品小图列表
	 * @return @param data->reserve_price:商品一口价格
	 * @return @param data->zk_final_price:商品折扣价格
	 * @return @param data->user_type:卖家类型，0表示集市，1表示商城
	 * @return @param data->provcity:商品所在地
	 * @return @param data->item_url:商品链接
	 * @return @param data->volume:30天销量
	 * 
	 * @return @param data->commission_rate:佣金比率(%)，1550表示15.5%
	 * @return @param data->commission_type:佣金类型
	 * @return @param data->commission:佣金
	 * 
	 * @return @param data->coupon_id:优惠券id
	 * @return @param data->coupon_start_time:优惠券开始时间
	 * @return @param data->coupon_end_time:优惠券结束时间
	 * @return @param data->coupon_info:优惠券信息，满299元减20元
	 * @return @param data->coupon_amount:优惠券面额
	 * @return @param data->coupon_total_count:优惠券总量
	 * @return @param data->coupon_remain_count:优惠券剩余量
	 * 
	 * @return @param data->coupon_share_url:券二合一页面链接，即领券页面
	 * @return @param data->url:商品淘客链接，如果商品没有券时，返回这个链接，此时客户购买也是有佣金的。
	 * 
	 * @return @param data->tk_total_sales:淘客30天月推广量
	 * @return @param data->tk_total_commi:月支出佣金
	 * 
	 * @return @param data->info_dxjh:定向计划信息
	 * @return @param data->include_mkt:是否包含营销计划
	 * @return @param data->include_dxjh:是否包含定向计划
	 * 
	 * @return @param data->seller_id:卖家id
	 * @return @param data->shop_title:店铺名称
	 * @return @param data->shop_dsr:店铺dsr评分
	 */
	public function dgMaterialOptional($adzone_id,$q='',$cat='',$has_coupon='',$sort='tk_rate_des',$page_no=1,$page_size=20,$end_tk_rate='',$start_tk_rate='',$end_price='',$start_price='',$is_overseas='',$is_tmall='',$need_free_shipment='',$need_prepay='',$include_pay_rate_30='',$include_good_rate='',$include_rfd_rate='',$npx_level='',$itemloc='',$platform='2',$start_dsr='',$ip='')
	{
		$c = new TopClient;
		$c->appkey = $this->appkey;
		$c->secretKey = $this->secret;
		$req = new TbkDgMaterialOptionalRequest;
		if($start_dsr) {
			$req->setStartDsr($start_dsr);
		}
		$req->setPageSize($page_size);
		$req->setPageNo($page_no);
		if($platform) {
			$req->setPlatform($platform);
		}
		if($end_tk_rate) {
			$req->setEndTkRate($end_tk_rate);
		}
		if($start_tk_rate) {
			$req->setStartTkRate($start_tk_rate);
		}
		if($end_price) {
			$req->setEndPrice($end_price);
		}
		if($start_price) {
			$req->setStartPrice($start_price);
		}
		if($is_overseas) {
			$req->setIsOverseas($is_overseas);
		}
		if($is_tmall) {
			$req->setIsTmall($is_tmall);
		}
		if($sort) {
			$req->setSort($sort);
		}
		if($itemloc) {
			$req->setItemloc($itemloc);
		}
		if($cat) {
			$req->setCat($cat);
		}
		if($q) {
			$req->setQ($q);
		}
		if($has_coupon) {
			$req->setHasCoupon($has_coupon);
		}
		if($ip) {
			$req->setIp($ip);
		}
		$req->setAdzoneId($adzone_id);
		if($need_free_shipment) {
			$req->setNeedFreeShipment($need_free_shipment);
		}
		if($need_prepay) {
			$req->setNeedPrepay($need_prepay);
		}
		if($include_pay_rate_30) {
			$req->setIncludePayRate30($include_pay_rate_30);
		}
		if($include_good_rate) {
			$req->setIncludeGoodRate($include_good_rate);
		}
		if($include_rfd_rate) {
			$req->setIncludeRfdRate($include_rfd_rate);
		}
		if($npx_level) {
			$req->setNpxLevel($npx_level);
		}
		$resp = $c->execute($req);
		$result=json_decode(json_encode($resp),true);
		if($result['code']) {
			$res=array(
					'code'=>$result['code'],
					'msg'=>$result['sub_msg'],
			);
		}else {
			if($result['result_list']['map_data'][0]['num_iid']) {
				//多件商品
				$list=$result['result_list']['map_data'];
			}else {
				//一件
				$list=array(
					0=>$result['result_list']['map_data']
				);
			}
			$num=count($list);
			for($i=0;$i<$num;$i++) {
				//判断是天猫还是淘宝商品
				if($list[$i]['user_type']=='1') {
					$list[$i]['is_tmall']=true;
				}else {
					$list[$i]['is_tmall']=false;
				}
				
				//商品折扣价格，保留2位小数，四舍五不入
				$list[$i]['zk_final_price']=substr(sprintf("%.3f",$list[$i]['zk_final_price']),0,-1);
				
				//优惠券面额
				$pos1=strpos($list[$i]['coupon_info'],'减');
				$pos2=strripos($list[$i]['coupon_info'],'元');
				$list[$i]['coupon_amount']=substr($list[$i]['coupon_info'], $pos1+3,$pos2-$pos1-3);
				//佣金
				$list[$i]['commission']=($list[$i]['zk_final_price']-$list[$i]['coupon_amount'])*$list[$i]['commission_rate']/10000;
				//保留2位小数，四舍五不入
				$list[$i]['commission']=substr(sprintf("%.3f",$list[$i]['commission']),0,-1);
			}
			$res=array(
					'code'=>0,
					'msg'=>'成功',
					'data'=>$list
			);
		}
		return $res;
	}
	
	/**
	 * 获取订单列表
	 * @param Date $start_time:订单查询开始时间
	 * @param number $span:订单查询时间范围，单位：秒，最小60，最大1200，如不填写，默认60。查询常规订单、三方订单、渠道，及会员订单时均需要设置此参数，直接通过设置page_size,page_no 翻页查询数据即可
	 * @param number $page_no:第几页，默认1，1~100
	 * @param number $page_size:页大小，默认20，1~100
	 * @param number $tk_status:订单状态，1: 全部订单，3：订单结算，12：订单付款， 13：订单失效，14：订单成功； 订单查询类型为‘结算时间’时，只能查订单结算状态
	 * @param String $order_query_type:订单查询类型，创建时间“create_time”，或结算时间“settle_time”。当查询渠道或会员运营订单时，建议入参创建时间“create_time”进行查询
	 * @param Number $order_scene:订单场景类型，1:常规订单，2:渠道订单，3:会员运营订单，默认为1，通过设置订单场景类型，媒体可以查询指定场景下的订单信息，例如不设置，或者设置为1，表示查询常规订单，常规订单包含淘宝客所有的订单数据，含渠道，及会员运营订单，但不包含3方分成，及维权订单
	 * @param number $order_count_type:订单数据统计类型，1: 2方订单，2: 3方订单，如果不设置，或者设置为1，表示2方订单
	 */
	public function getOrderList($start_time,$span=1200,$page_no=1,$page_size=20,$tk_status=1,$order_query_type,$order_scene=1,$order_count_type=1)
	{
	    $c = new TopClient;
	    $c->appkey = $this->appkey;
	    $c->secretKey = $this->secret;
	    $req = new TbkOrderGetRequest;
	    $req->setFields("trade_parent_id,trade_id,num_iid,item_title,item_num,price,pay_price,seller_nick,seller_shop_title,commission,commission_rate,unid,create_time,earning_time,tk_status,tk3rd_type,tk3rd_pub_id,order_type,income_rate,pub_share_pre_fee,subsidy_rate,subsidy_type,terminal_type,auction_category,site_id,site_name,adzone_id,adzone_name,alipay_total_price,total_commission_rate,total_commission_fee,subsidy_fee,relation_id,special_id,click_time,tk_commission_pre_fee_for_media_platform,tk_commission_fee_for_media_platform,tk_commission_rate_for_media_platform");
	    $req->setStartTime($start_time);
	    $req->setSpan($span);
	    $req->setPageNo($page_no);
	    $req->setPageSize($page_size);
	    $req->setTkStatus($tk_status);
	    $req->setOrderQueryType($order_query_type);
	    $req->setOrderScene($order_scene);
	    $req->setOrderCountType($order_count_type);
	    $resp = $c->execute($req);
	    $result=json_decode(json_encode($resp),true);
	    return $result;
	}
	
	/**
	 * 淘宝客-推广者-所有订单查询
	 * taobao.tbk.order.details.get
	 * 新订单查询API变更情况：
	 * 商品价格：price->item_price
	 * 商品id：num_iid->item_id
	 * 结算预估收入：commission->pub_share_fee
	 * 推广者获得的佣金比率：commission_rate->pub_share_rate
	 * 订单创建的时间：create_time->tk_create_time
	 * 订单确认收货后且商家完成佣金支付的时间：earning_time->tk_earning_time
	 * 原第三方服务来源——>名称调整为：产品类型：tk3rd_type->flow_source
	 * 原第三方id——>名称调整为：第三方的账户id：tk3rd_pub_id->pub_id
	 * 商品所属的根类目，即一级类目的名称：auction_category->item_category_name
	 * 新增字段如下：
	 * 订单在淘宝拍下付款的时间：tb_paid_time
	 * 订单付款的时间，该时间同步淘宝，可能会略晚于买家在淘宝的订单创建时间：tk_paid_time
	 * 身份说明。二方：佣金收益的第一归属者； 三方：从其他淘宝客佣金中进行分成的推广者：tk_order_role
	 * 维权标签，0 含义为非维权 1 含义为维权订单。即如果订单发生维权退款，会给予提示标识。所有的维权推广订单也能在维权推广订单API查询。本API只做提示：refund_tag
	 * 提成比例，提成比例=推广者获得的佣金比例*收入比例：tk_total_rate
	 * 推广者赚取佣金后支付给阿里妈妈的技术服务费用的比率：alimama_rate
	 * 技术服务费=结算金额*收入比率*技术服务费率。推广者赚取佣金后支付给阿里妈妈的技术服务费用：alimama_share_fee
	 * 商品图片：item_img
	 * @param number $query_type:查询时间类型，1：按照订单淘客创建时间查询，2:按照订单淘客付款时间查询，3:按照订单淘客结算时间查询
	 * @param number $tk_status:淘客订单状态，12-付款，13-关闭，14-确认收货，3-结算成功;不传，表示所有状态
	 * @param number $order_scene:场景订单场景类型，1:常规订单，2:渠道订单，3:会员运营订单，默认为1
	 * @param number $member_type:推广者角色类型,2:二方，3:三方，不传，表示所有角色
	 * @param number $page_no:第几页，默认1，1~100
	 * @param number $page_size:页大小，默认20，1~100
	 * @param string $position_index:位点，除第一页之外，都需要传递；前端原样返回。
	 * @param number $jump_type:跳转类型，当向前或者向后翻页必须提供,-1: 向前翻页,1：向后翻页
	 */
	public function getOrderListNew($query_type,$tk_status='',$order_scene=1,$start_time,$end_time,$member_type='',$page_no=1,$page_size=20,$position_index='',$jump_type=1)
	{
	    $c = new TopClient;
	    $c->appkey = $this->appkey;
	    $c->secretKey = $this->secret;
	    $req = new TbkOrderDetailsGetRequest;
	    $req->setQueryType($query_type);
	    if($tk_status){
	        $req->setTkStatus($tk_status);
	    }
	    $req->setOrderScene($order_scene);
	    $req->setStartTime($start_time);
	    $req->setEndTime($end_time);
	    if($member_type){
	        $req->setMemberType($member_type);
	    }
	    $req->setPageNo($page_no);
	    $req->setPageSize($page_size);
	    if($position_index){
	        $req->setPositionIndex($position_index);
	    }
	    $req->setJumpType($jump_type);
	    
	    
	    $resp = $c->execute($req);
	    $result=json_decode(json_encode($resp),true);
	    return $result;
	}
}