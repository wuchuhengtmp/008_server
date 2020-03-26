<?php
/**
 * 淘宝客管理接口
 */
namespace App\Controller;
use App\Common\Controller\AuthController;

class TbkController extends AuthController
{
	/**
	 * 获取淘宝客商品列表（新）
	 * @param string $search:查询词，如女装
	 * @param string $has_coupon:是否有优惠券，设置为true表示该商品有优惠券，设置为false或不设置表示不判断这个属性
	 * @param string $cat:后台类目ID，用,分割，如16,18，最大10个，该ID可以通过taobao.itemcats.get接口获取到
	 * @param string $itemloc:所在地
	 * @param string $sort:排序_des（降序），排序_asc（升序），销量（total_sales），淘客佣金比率（tk_rate）， 累计推广量（tk_total_sales），总支出佣金（tk_total_commi）,默认tk_rate_des
	 * @param string $is_tmall:是否商城商品，设置为true表示该商品是属于淘宝商城商品，设置为false或不设置表示不判断这个属性，默认false
	 * @param string $is_overseas:是否海外商品，设置为true表示该商品是属于海外商品，设置为false或不设置表示不判断这个属性，默认false
	 * @param number $start_price:折扣价范围下限，单位：元，默认10元
	 * @param number $end_price:折扣价范围上限，单位：元，默认10元
	 * @param number $start_tk_rate:淘客佣金比率上限，如：1234表示12.34%
	 * @param number $end_tk_rate:淘客佣金比率下限，如：1234表示12.34%
	 * @param int $platform:链接形式：1：PC，2：无线，默认：2
	 * @param int $page_no:第几页，默认：１
	 * @param int $page_size:页大小，默认20，1~100
	 * @return array
	 * @return @param code:返回码
	 * @return @param msg:返回码说明
	 * @return @param data:返回数据
	 * @return @param data->list:淘宝客商品列表
	 */
	public function getTbkList_new()
	{
		//查询词
		if(trim(I('post.search')))
		{
			$search=trim(I('post.search'));
			//统计搜索
			$HotSearch=new \Common\Model\HotSearchModel();
			$res_search=$HotSearch->statistics($search);
			if($res_search===false)
			{
				//数据库错误
				$res=array(
						'code'=>$this->ERROR_CODE_COMMON['DB_ERROR'],
						'msg'=>$this->ERROR_CODE_COMMON_ZH[$this->ERROR_CODE_COMMON['DB_ERROR']]
				);
				echo json_encode ($res,JSON_UNESCAPED_UNICODE);
				exit();
			}
		}
		//是否有优惠券
		if(trim(I('post.has_coupon')))
		{
			$has_coupon=trim(I('post.has_coupon'));
		}
		//后台类目ID
		if(trim(I('post.cat')))
		{
			$cat=trim(I('post.cat'));
		}
		//所在地
		if(trim(I('post.itemloc')))
		{
			$itemloc=trim(I('post.itemloc'));
		}
		//排序
		if(trim(I('post.sort')))
		{
			$sort=trim(I('post.sort'));
		}else {
			$sort=='tk_rate_des';
		}
		//是否商城商品
		if(trim(I('post.is_tmall')))
		{
			$is_tmall=trim(I('post.is_tmall'));
		}else {
			$is_tmall='false';
		}
		//是否是否海外商品
		if(trim(I('post.is_overseas')))
		{
			$is_overseas=trim(I('post.is_overseas'));
		}else {
			$is_overseas='false';
		}
		//折扣价范围下限
		if(trim(I('post.start_price')))
		{
			$start_price=trim(I('post.start_price'));
		}else {
			$start_price='';
		}
		//折扣价范围上限
		if(trim(I('post.end_price')))
		{
			$end_price=trim(I('post.end_price'));
		}else {
			$end_price='';
		}
		//淘客佣金比率上限
		if(trim(I('post.start_tk_rate')))
		{
			$start_tk_rate=trim(I('post.start_tk_rate'));
		}
		//淘客佣金比率下限
		if(trim(I('post.end_tk_rate')))
		{
			$end_tk_rate=trim(I('post.end_tk_rate'));
		}
		//链接形式
		if(trim(I('post.platform')))
		{
			$platform=trim(I('post.platform'));
		}else {
			$platform='2';
		}
		//第几页
		if(trim(I('post.page_no')))
		{
			$page_no=trim(I('post.page_no'));
		}else {
			$page_no=1;
		}
		//页大小
		if(trim(I('post.page_size')))
		{
			$page_size=trim(I('post.page_size'));
		}else {
			$page_size=20;
		}
		//$ip=getIP();
		$ip='';
		Vendor('tbk.tbk','','.class.php');
		$tbk=new \tbk();
		$adzone_id=$tbk->adzone_id;//广告位
		//获取用户所绑定的推广位
		$pid='';
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
				$userMsg=$User->getUserMsg($uid);
				//如果是微信群主，使用微信群主推广位pid
				if($userMsg['tb_pid_master'])
				{
					$pid=$userMsg['tb_pid_master'];
				}else {
					$pid=$userMsg['tb_pid'];//淘宝推广位
				}
				if($pid)
				{
					//广告位
					$start=strripos($pid, '_');
					$adzone_id=substr($pid, $start+1);
				}
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
		
		$res_tbk=$tbk->dgMaterialOptional($adzone_id,$search,$cat,$has_coupon,$sort,$page_no,$page_size,$end_tk_rate,$start_tk_rate,$end_price,$start_price,$is_overseas,$is_tmall,$need_free_shipment='',$need_prepay='',$include_pay_rate_30='',$include_good_rate='',$include_rfd_rate='',$npx_level='',$itemloc,$platform,$start_dsr='',$ip);
		if($res_tbk['code']==0)
		{
			$list=array();
			foreach ($res_tbk['data'] as $l)
			{
				//根据会员组计算相应佣金
				//佣金
				$l['commission']=$l['commission']*$fee_user/100;
				//保留2位小数，四舍五不入
				$l['commission']=substr(sprintf("%.3f",$l['commission']),0,-1);
				
				//过滤掉不符合佣金比率的商品
				//佣金上下限都有
				if($start_tk_rate and $end_tk_rate)
				{
					if($l['commission_rate']>=$start_tk_rate and $l['commission_rate']<=$end_tk_rate)
					{
						$list[]=$l;
					}
				}
				//只有下限
				if($start_tk_rate and $end_tk_rate=='')
				{
					if($l['commission_rate']>=$start_tk_rate)
					{
						$list[]=$l;
					}
				}
				//只有上限
				if($start_tk_rate=='' and $end_tk_rate)
				{
					if($l['commission_rate']<=$end_tk_rate)
					{
						$list[]=$l;
					}
				}
				//没有佣金比率搜索条件
				if($start_tk_rate=='' and $end_tk_rate=='')
				{
					$list[]=$l;
				}
			}
			//成功
			$data=array(
					//'list'=>$res_tbk['data']
					'list'=>$list
			);
			$res=array(
					'code'=>$this->ERROR_CODE_COMMON['SUCCESS'],
					'msg'=>'成功',
					'data'=>$data
			);
		}else {
			//数据库错误
			$res=$res_tbk;
		}
		echo json_encode ($res,JSON_UNESCAPED_UNICODE);
	}
	
	/**
	 * 获取推荐淘宝客商品列表
	 * @param string $sort:排序_des（降序），排序_asc（升序），销量（total_sales），淘客佣金比率（tk_rate）， 累计推广量（tk_total_sales），总支出佣金（tk_total_commi）,默认tk_rate_des
	 * @param string $is_tmall:是否商城商品，设置为true表示该商品是属于淘宝商城商品，设置为false或不设置表示不判断这个属性，默认false
	 * @param string $is_overseas:是否海外商品，设置为true表示该商品是属于海外商品，设置为false或不设置表示不判断这个属性，默认false
	 * @param number $start_price:折扣价范围下限，单位：元，默认10元
	 * @param number $end_price:折扣价范围上限，单位：元，默认10元
	 * @param number $start_tk_rate:淘客佣金比率上限，如：1234表示12.34%
	 * @param number $end_tk_rate:淘客佣金比率下限，如：1234表示12.34%
	 * @param int $platform:链接形式：1：PC，2：无线，默认：2
	 * @param int $page_no:第几页，默认：１
	 * @param int $page_size:页大小，默认10，1~100
	 * @return array
	 * @return @param code:返回码
	 * @return @param msg:返回码说明
	 * @return @param data:返回数据
	 * @return @param data->list:淘宝客商品列表
	 */
	public function getTopList()
	{
		//查询词
		$search='';
		//是否有优惠券
		$has_coupon='true';
		//排序
		if(trim(I('post.sort')))
		{
			$sort=trim(I('post.sort'));
		}else {
			$sort=='tk_rate_des';
		}
		//是否商城商品
		if(trim(I('post.is_tmall')))
		{
			$is_tmall=trim(I('post.is_tmall'));
		}else {
			$is_tmall='false';
		}
		//是否是否海外商品
		if(trim(I('post.is_overseas')))
		{
			$is_overseas=trim(I('post.is_overseas'));
		}else {
			$is_overseas='false';
		}
		//折扣价范围下限
		if(trim(I('post.start_price')))
		{
			$start_price=trim(I('post.start_price'));
		}else {
			$start_price=1;
		}
		//折扣价范围上限
		if(trim(I('post.end_price')))
		{
			$end_price=trim(I('post.end_price'));
		}else {
			$end_price=150;
		}
		//淘客佣金比率上限
		if(trim(I('post.start_tk_rate')))
		{
			$start_tk_rate=trim(I('post.start_tk_rate'));
		}else {
			$start_tk_rate=1000;
		}
		//淘客佣金比率下限
		if(trim(I('post.end_tk_rate')))
		{
			$end_tk_rate=trim(I('post.end_tk_rate'));
		}else {
			//$end_tk_rate=1000;
		}
		//链接形式
		if(trim(I('post.platform')))
		{
			$platform=trim(I('post.platform'));
		}else {
			$platform='2';
		}
		//第几页
		if(trim(I('post.page_no')))
		{
			$page_no=trim(I('post.page_no'));
		}else {
			$page_no=1;
		}
		//页大小
		if(trim(I('post.page_size')))
		{
			$page_size=trim(I('post.page_size'));
		}else {
			$page_size=10;
		}
		//$ip=getIP();
		$ip='';
		Vendor('tbk.tbk','','.class.php');
		$tbk=new \tbk();
		$adzone_id=$tbk->adzone_id;//广告位
		//获取用户所绑定的推广位
		$pid='';
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
				$userMsg=$User->getUserMsg($uid);
				//如果是微信群主，使用微信群主推广位pid
				if($userMsg['tb_pid_master'])
				{
					$pid=$userMsg['tb_pid_master'];
				}else {
					$pid=$userMsg['tb_pid'];//淘宝推广位
				}
				if($pid)
				{
					//广告位
					$start=strripos($pid, '_');
					$adzone_id=substr($pid, $start+1);
				}
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
		
		//后台类目ID
		$sql="select distinct(category_id) from __PREFIX__tb_cat where 1 ORDER BY RAND() limit 0,30";
		$catlist=M()->query($sql);
		/* $cat_allid='';
		foreach ($catlist as $l)
		{
			$cat_allid.=$l['category_id'].',';
		}
		if($cat_allid)
		{
			$cat_allid=substr($cat_allid, 0,-1);
		}else {
			//数据库错误
			$res=array(
					'code'=>$this->ERROR_CODE_COMMON['DB_ERROR'],
					'msg'=>$this->ERROR_CODE_COMMON_ZH[$this->ERROR_CODE_COMMON['DB_ERROR']]
			);
			echo json_encode ($res,JSON_UNESCAPED_UNICODE);
			exit();
		} */
		$list=array();
		foreach ($catlist as $l)
		{
			$category_id=$l['category_id'];
			$res_tbk=$tbk->dgMaterialOptional($adzone_id,$search,$category_id,$has_coupon,$sort,$page_no,$page_size=1,$end_tk_rate,$start_tk_rate,$end_price,$start_price,$is_overseas,$is_tmall,$need_free_shipment='',$need_prepay='',$include_pay_rate_30='',$include_good_rate='',$include_rfd_rate='',$npx_level='',$itemloc='',$platform,$start_dsr='',$ip);
			if($res_tbk['code']==0)
			{
				//成功
				//将销量为0的过滤掉
				if($res_tbk['data'][0]['volume']>500)
				{
					//根据会员组计算相应佣金
					//佣金
					$res_tbk['data'][0]['commission']=$res_tbk['data'][0]['commission']*$fee_user/100;
					//保留2位小数，四舍五不入
					$res_tbk['data'][0]['commission']=substr(sprintf("%.3f",$res_tbk['data'][0]['commission']),0,-1);
					
					$list[]=$res_tbk['data'][0];
				}
			}else {
				//数据库错误
				//$res=$res_tbk;
			}
		}
		//成功
		$data=array(
				'list'=>$list
		);
		$res=array(
				'code'=>$this->ERROR_CODE_COMMON['SUCCESS'],
				'msg'=>'成功',
				'data'=>$data
		);
		echo json_encode ($res,JSON_UNESCAPED_UNICODE);
	}
	
	/**
	 * 获取9块9包邮商品列表
	 * @param string $sort:排序_des（降序），排序_asc（升序），销量（total_sales），淘客佣金比率（tk_rate）， 累计推广量（tk_total_sales），总支出佣金（tk_total_commi）,默认total_sales_des
	 * @param string $is_tmall:是否商城商品，设置为true表示该商品是属于淘宝商城商品，设置为false或不设置表示不判断这个属性，默认false
	 * @param string $is_overseas:是否海外商品，设置为true表示该商品是属于海外商品，设置为false或不设置表示不判断这个属性，默认false
	 * @param number $start_price:折扣价范围下限，单位：元，默认10元
	 * @param number $end_price:折扣价范围上限，单位：元，默认10元
	 * @param number $start_tk_rate:淘客佣金比率上限，如：1234表示12.34%
	 * @param number $end_tk_rate:淘客佣金比率下限，如：1234表示12.34%
	 * @param int $platform:链接形式：1：PC，2：无线，默认：2
	 * @param int $page_no:第几页，默认：１
	 * @param int $page_size:页大小，默认10，1~100
	 * @return array
	 * @return @param code:返回码
	 * @return @param msg:返回码说明
	 * @return @param data:返回数据
	 * @return @param data->list:淘宝客商品列表
	 */
	public function get99List()
	{
		//查询词
		$search='';
		//是否有优惠券
		$has_coupon='true';
		//排序
		if(trim(I('post.sort')))
		{
			$sort=trim(I('post.sort'));
		}else {
			$sort=='total_sales_des';
		}
		//是否商城商品
		if(trim(I('post.is_tmall')))
		{
			$is_tmall=trim(I('post.is_tmall'));
		}else {
			$is_tmall='false';
		}
		//是否是否海外商品
		if(trim(I('post.is_overseas')))
		{
			$is_overseas=trim(I('post.is_overseas'));
		}else {
			$is_overseas='false';
		}
		//折扣价范围下限
		if(trim(I('post.start_price')))
		{
			$start_price=trim(I('post.start_price'));
		}else {
			//$start_price=1;
		}
		//折扣价范围上限
		if(trim(I('post.end_price')))
		{
			$end_price=trim(I('post.end_price'));
		}else {
			$end_price=10;
		}
		//淘客佣金比率上限
		if(trim(I('post.start_tk_rate')))
		{
			$start_tk_rate=trim(I('post.start_tk_rate'));
		}else {
			//$start_tk_rate=1000;
		}
		//淘客佣金比率下限
		if(trim(I('post.end_tk_rate')))
		{
			$end_tk_rate=trim(I('post.end_tk_rate'));
		}else {
			//$end_tk_rate=1000;
		}
		//链接形式
		if(trim(I('post.platform')))
		{
			$platform=trim(I('post.platform'));
		}else {
			$platform='2';
		}
		//第几页
		if(trim(I('post.page_no')))
		{
			$page_no=trim(I('post.page_no'));
		}else {
			$page_no=1;
		}
		//页大小
		if(trim(I('post.page_size')))
		{
			$page_size=trim(I('post.page_size'));
		}else {
			$page_size=10;
		}
		//$ip=getIP();
		$ip='';
		Vendor('tbk.tbk','','.class.php');
		$tbk=new \tbk();
		$adzone_id=$tbk->adzone_id;//广告位
		//获取用户所绑定的推广位
		$pid='';
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
				$userMsg=$User->getUserMsg($uid);
				//如果是微信群主，使用微信群主推广位pid
				if($userMsg['tb_pid_master'])
				{
					$pid=$userMsg['tb_pid_master'];
				}else {
					$pid=$userMsg['tb_pid'];//淘宝推广位
				}
				if($pid)
				{
					//广告位
					$start=strripos($pid, '_');
					$adzone_id=substr($pid, $start+1);
				}
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
	
		//后台类目ID
		$sql="select distinct(category_id) from __PREFIX__tb_cat where 1 ORDER BY RAND() limit 0,10";
		$catlist=M()->query($sql);
		$cat_allid='';
		foreach ($catlist as $l)
		{
			$cat_allid.=$l['category_id'].',';
		}
		if($cat_allid)
		{
			$cat_allid=substr($cat_allid, 0,-1);
		}else {
			//数据库错误
			$res=array(
					'code'=>$this->ERROR_CODE_COMMON['DB_ERROR'],
					'msg'=>$this->ERROR_CODE_COMMON_ZH[$this->ERROR_CODE_COMMON['DB_ERROR']]
			);
			echo json_encode ($res,JSON_UNESCAPED_UNICODE);
			exit();
		}
		$list=array();
		$res_tbk=$tbk->dgMaterialOptional($adzone_id,$search,$cat_allid,$has_coupon,$sort,$page_no,$page_size,$end_tk_rate,$start_tk_rate,$end_price,$start_price,$is_overseas,$is_tmall,$need_free_shipment='true',$need_prepay='',$include_pay_rate_30='',$include_good_rate='',$include_rfd_rate='',$npx_level='',$itemloc='',$platform,$start_dsr='',$ip);
		if($res_tbk['code']==0)
		{
			//成功
			foreach ($res_tbk['data'] as $l)
			{
				//根据会员组计算相应佣金
				//佣金
				$commission=$l['commission']*$fee_user/100;
				//保留2位小数，四舍五不入
				$commission=substr(sprintf("%.3f",$commission),0,-1);
				
				$list[]=array(
						'commission_rate'=>$l['commission_rate'],
						'coupon_end_time'=>$l['commission_rate'],
						'coupon_id'=>$l['coupon_id'],
						'coupon_info'=>$l['coupon_info'],
						'coupon_remain_count'=>$l['coupon_remain_count'],
						'coupon_share_url'=>$l['coupon_share_url'],
						'coupon_start_time'=>$l['coupon_start_time'],
						'coupon_total_count'=>$l['coupon_total_count'],
						'item_url'=>$l['item_url'],
						'num_iid'=>$l['num_iid'],
						'pict_url'=>$l['pict_url'],
						'title'=>$l['title'],
						'zk_final_price'=>$l['zk_final_price'],
						'is_tmall'=>$l['is_tmall'],
						'coupon_amount'=>$l['coupon_amount'],
						'commission'=>$commission,
						'volume'=>$l['volume'],
				);
			}
		}else {
			//数据库错误
			$res=$res_tbk;
		}
		//成功
		$data=array(
				'list'=>$list
		);
		$res=array(
				'code'=>$this->ERROR_CODE_COMMON['SUCCESS'],
				'msg'=>'成功',
				'data'=>$data
		);
		echo json_encode ($res,JSON_UNESCAPED_UNICODE);
	}
	
	/**
	 * 获取淘宝客商品详情-新
	 * @param string $num_iid:商品ID
	 * @param string $token:用户身份令牌
	 * @return array
	 * @return @param code:返回码
	 * @return @param msg:返回码说明
	 * @return @param data:返回数据
	 */
	public function getGoodsDetail()
	{
	    if(trim(I('post.num_iid'))) {
	        //获取用户所绑定的推广位
	        $pid='';
	        $relationId='';
	        if(trim(I('post.token'))) {
	            //判断用户身份
	            $token=trim(I('post.token'));
	            $User=new \Common\Model\UserModel();
	            $res_token=$User->checkToken($token);
	            if($res_token['code']!=0) {
	                //用户身份不合法
	                $res=$res_token;
	                echo json_encode ($res,JSON_UNESCAPED_UNICODE);
	                exit();
	            }else {
	                $uid=$res_token['uid'];
	                $userMsg=$User->getUserMsg($uid);
	                //如果是微信群主，使用微信群主推广位pid
	                if($userMsg['tb_pid_master']) {
	                    $pid=$userMsg['tb_pid_master'];
	                }else {
	                    $pid=$userMsg['tb_pid'];//淘宝推广位
	                }
	                //会员组
	                $group_id=$userMsg['group_id'];
	                //渠道ID
	                $relationId=$userMsg['tb_rid'];
	            }
	        }else {
	            //普通会员组
	            $group_id=1;
	        }
	        $UserGroup=new \Common\Model\UserGroupModel();
	        $groupMsg=$UserGroup->getGroupMsg($group_id);
	        $fee_user=$groupMsg['fee_user'];
	        
	        //获取商品详情
	        $num_iid=trim(I('post.num_iid'));
	        Vendor('tbk.tbk','','.class.php');
	        $tbk=new \tbk();
	        $ip='';
	        $res_tbk=$tbk->getItemDetailNew($num_iid,'2',$ip,$pid,$relationId);
	        //VIP佣金
	        $res_tbk['data']['commission_vip']=$res_tbk['data']['commission']*0.9;
	        //保留2位小数，四舍五不入
	        $res_tbk['data']['commission_vip']=substr(sprintf("%.3f",$res_tbk['data']['commission_vip']),0,-1);
	        //根据会员组计算相应佣金
	        //佣金
	        $res_tbk['data']['commission']=$res_tbk['data']['commission']*$fee_user/100;
	        //保留2位小数，四舍五不入
	        $res_tbk['data']['commission']=substr(sprintf("%.3f",$res_tbk['data']['commission']),0,-1);
	        
	        $res=$res_tbk;
	    }else {
	        //参数不正确，参数缺失
	        $res=array(
	            'code'=>$this->ERROR_CODE_COMMON['PARAMETER_ERROR'],
	            'msg'=>$this->ERROR_CODE_COMMON_ZH[$this->ERROR_CODE_COMMON['PARAMETER_ERROR']]
	        );
	    }
	    echo json_encode ($res,JSON_UNESCAPED_UNICODE);
	}
	public function getGoodsMsg()
	{
		if(trim(I('post.num_iid'))) {
			//获取用户所绑定的推广位
			$pid='';
			$relationId='';
			if(trim(I('post.token'))) {
				//判断用户身份
				$token=trim(I('post.token'));
				$User=new \Common\Model\UserModel();
				$res_token=$User->checkToken($token);
				if($res_token['code']!=0) {
					//用户身份不合法
					$res=$res_token;
					echo json_encode ($res,JSON_UNESCAPED_UNICODE);
					exit();
				}else {
					$uid=$res_token['uid'];
					$userMsg=$User->getUserMsg($uid);
					//如果是微信群主，使用微信群主推广位pid
					if($userMsg['tb_pid_master']) {
						$pid=$userMsg['tb_pid_master'];
					}else {
						$pid=$userMsg['tb_pid'];//淘宝推广位
					}
					//会员组
					$group_id=$userMsg['group_id'];
					//渠道ID
					$relationId=$userMsg['tb_rid'];
				}
			}else {
				//普通会员组
				$group_id=1;
			}
			$UserGroup=new \Common\Model\UserGroupModel();
			$groupMsg=$UserGroup->getGroupMsg($group_id);
			$fee_user=$groupMsg['fee_user'];
			
			//获取商品详情
			$num_iid=trim(I('post.num_iid'));
			Vendor('tbk.tbk','','.class.php');
			$tbk=new \tbk();
			$ip='';
			$res_tbk=$tbk->getItemDetail($num_iid,'2',$ip,$pid,$relationId);
			//VIP佣金
			$res_tbk['data']['commission_vip']=$res_tbk['data']['commission']*0.9;
			//保留2位小数，四舍五不入
			$res_tbk['data']['commission_vip']=substr(sprintf("%.3f",$res_tbk['data']['commission_vip']),0,-1);
			//根据会员组计算相应佣金
			//佣金
			$res_tbk['data']['commission']=$res_tbk['data']['commission']*$fee_user/100;
			//保留2位小数，四舍五不入
			$res_tbk['data']['commission']=substr(sprintf("%.3f",$res_tbk['data']['commission']),0,-1);
			
			$res=$res_tbk;
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
	 * 获取淘宝客商品推荐文案
	 * @param string $num_iid:商品ID
	 * @return array
	 * @return @param code:返回码
	 * @return @param msg:返回码说明
	 * @return @param data:返回数据
	 */
	public function getGoodsDesc()
	{
	    if(trim(I('post.num_iid'))){
	        $num_iid=trim(I('post.num_iid'));
	        //好单库商品详情
	        $Haodanku=new \Common\Model\HaodankuModel();
	        $res_hdk=$Haodanku->getItemDetailLocal($num_iid);
	        if($res_hdk['code']==1){
	            //调用成功
	            $hgMsg=$res_hdk['data'];
	            $goodsMsg=array(
	                'title'=>$hgMsg['itemtitle'],//宝贝标题
	                'short_title'=>$hgMsg['itemshorttitle'],//宝贝短标题
	                'desc'=>$hgMsg['itemdesc'],//宝贝推荐语
	            );
	            $data=array(
	                'goodsMsg'=>$goodsMsg
	            );
	            $res=array(
	                'code'=>$this->ERROR_CODE_COMMON['SUCCESS'],
	                'msg'=>'成功',
	                'data'=>$data
	            );
	        }else {
	            $res=$res_hdk;
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
	 * 高佣转链
	 * @param string $num_iid:商品ID
	 * @param string $token:用户身份令牌
	 * @return array
	 * @return @param code:返回码
	 * @return @param msg:返回码说明
	 * @return @param data:返回数据
	 */
	public function generateUrl()
	{
	    if(trim(I('post.num_iid'))) {
	        //获取用户所绑定的推广位
	        $pid='';
	        $relationId='';
	        if(trim(I('post.token'))) {
	            //判断用户身份
	            $token=trim(I('post.token'));
	            $User=new \Common\Model\UserModel();
	            $res_token=$User->checkToken($token);
	            if($res_token['code']!=0) {
	                //用户身份不合法
	                $res=$res_token;
	                echo json_encode ($res,JSON_UNESCAPED_UNICODE);
	                exit();
	            }else {
	                $uid=$res_token['uid'];
	                $userMsg=$User->getUserMsg($uid);
	                //如果是微信群主，使用微信群主推广位pid
	                if($userMsg['tb_pid_master']) {
	                    $pid=$userMsg['tb_pid_master'];
	                }else {
	                    $pid=$userMsg['tb_pid'];//淘宝推广位
	                }
	                //会员组
	                $group_id=$userMsg['group_id'];
	                //渠道ID
	                $relationId=$userMsg['tb_rid'];
	            }
	        }else {
	            //普通会员组
	            $group_id=1;
	        }
	        $UserGroup=new \Common\Model\UserGroupModel();
	        $groupMsg=$UserGroup->getGroupMsg($group_id);
	        $fee_user=$groupMsg['fee_user'];
	        
	        //获取商品详情
	        $num_iid=trim(I('post.num_iid'));
	        Vendor('tbk.tbk','','.class.php');
	        $tbk=new \tbk();
	        $res_tbk=$tbk->generateUrl($num_iid,$pid,$relationId);
	        //VIP佣金
	        $res_tbk['data']['commission_vip']=$res_tbk['data']['commission']*0.9;
	        //保留2位小数，四舍五不入
	        $res_tbk['data']['commission_vip']=substr(sprintf("%.3f",$res_tbk['data']['commission_vip']),0,-1);
	        //根据会员组计算相应佣金
	        //佣金
	        $res_tbk['data']['commission']=$res_tbk['data']['commission']*$fee_user/100;
	        //保留2位小数，四舍五不入
	        $res_tbk['data']['commission']=substr(sprintf("%.3f",$res_tbk['data']['commission']),0,-1);
	        
	        $res=$res_tbk;
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
	 * 获取关联推荐商品
	 * @param string $num_iid:商品ID
	 * @param int $num:返回数量，默认4，最大值40
	 * @return array
	 * @return @param code:返回码
	 * @return @param msg:返回码说明
	 * @return @param data:返回数据
	 * @return @param data->list:商品列表
	 */
	public function getRecommendList()
	{
	    if(trim(I('post.num_iid'))){
	        Vendor('tbk.tbk','','.class.php');
	        $tbk=new \tbk();
	        $num_iid=trim(I('post.num_iid'));
	        if(trim(I('post.num'))){
	            $num=trim(I('post.num'));
	        }else {
	            $num=4;
	        }
	        $res=$tbk->getRecommendItem($num_iid,$num);
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
	 * 根据商品链接/淘口令查询淘宝商品信息-免费版
	 * @param string $tkl:淘口令，如：€rIEwb0PmM16€
	 * @return array
	 * @return @param code:返回码
	 * @return @param msg:返回码说明
	 * @return @param data:返回数据
	 * @return @param data->goodsMsg:淘宝商品信息
	 */
	public function searchTkl2()
	{
		if(trim(I('post.tkl')))
		{
			$str=trim(I('post.tkl'));
			$pos1=strpos($str, '€')+strlen('€');//第一次出现的位置
			$pos2=strripos($str, '€');//最后一次出现的位置
			$tkl=substr($str, $pos1,$pos2-$pos1);
			//$tkl=trim(I('post.tkl'));
			//解析淘口令
			Vendor('tbk.tbk','','.class.php');
			$tbk=new \tbk();
			$res_tkl=$tbk->resolveTkl($tkl);
			if($res_tkl['data']['url'])
			{
				//淘宝商品地址
				$taobao_url=$res_tkl['data']['url'];
				//商品ID
				$num_iid=taoid($taobao_url);
				if($num_iid==0)
				{
					//没有匹配到商品ID，重新截取
					$pos1=strpos($taobao_url, 'com/i')+5;
					$pos2=strpos($taobao_url, '.htm');
					$num_iid=substr($taobao_url, $pos1,$pos2-$pos1);
				}
				$data=array(
						'num_iid'=>$num_iid,
				);
				$res=array(
						'code'=>0,
						'msg'=>'成功！',
						'data'=>$data
				);
				echo json_encode ($res,JSON_UNESCAPED_UNICODE);
				exit();
				
				//获取商品详情
				//$ip=getIP();
				$ip='';
				$res_tbk=$tbk->getItemDetail($num_iid,$platform='2',$ip);
				if($res_tbk['code']==0)
				{
					$res=$res_tbk;
				}else {
					//该商品不是淘宝客商品
					$res=array(
							'code'=>2,
							'msg'=>'该商品不是淘宝客商品，没有佣金！'
					);
				}
			}else {
				$res=array(
						'code'=>1,
						'msg'=>'淘口令不存在/淘口令解析失败！'
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
	
	/**
	 * 根据商品链接/淘口令查询淘宝商品信息
	 * @param string $tkl:淘口令，如：€rIEwb0PmM16€
	 * @return array
	 * @return @param code:返回码
	 * @return @param msg:返回码说明
	 * @return @param data:返回数据
	 * @return @param data->goodsMsg:淘宝商品信息
	 */
	public function searchTkl()
	{
	    if(trim(I('post.tkl'))) {
	        $tkl=trim(I('post.tkl'));
	        //先调用维易淘客的解析接口
	        $url="http://mvapi.vephp.com/dec?vekey=V00000783Y50944006&para=".urlencode($tkl);
	        $res_json=https_request($url);
	        $res_vy=json_decode($res_json,true);
	        if($res_vy['num_iid']){
	            $data=array(
	                'num_iid'=>$res_vy['num_iid'],//商品ID
	            );
	        }else {
	            //使用淘宝联盟官方接口
	            //解析淘口令
	            Vendor('tbk.tbk','','.class.php');
	            $tbk=new \tbk();
	            $res_tkl=$tbk->wirelessShareTpwdQuery($tkl);
	            if($res_tkl['code']==0) {
	                //淘宝商品地址
	                $taobao_url=$res_tkl['data']['url'];
	                //echo json_encode ($res_tkl,JSON_UNESCAPED_UNICODE);die();
	                
	                //商品ID
	                $num_iid=taoid($taobao_url);
	                if($num_iid==0) {
	                    //没有匹配到商品ID，重新截取
	                    $pos1=strpos($taobao_url, 'com/i')+5;
	                    $pos2=strpos($taobao_url, '.htm');
	                    $num_iid=substr($taobao_url, $pos1,$pos2-$pos1);
	                }
	                $data=array(
	                    'num_iid'=>$num_iid,
	                );
	            }else {
	                //淘口令不存在/淘口令解析失败！
	                $res=array(
	                    'code'=>1,
	                    'msg'=>'该商品没有参与淘宝客活动，可查询同类商品！'
	                );
	                echo json_encode ($res,JSON_UNESCAPED_UNICODE);
	                exit();
	            }
	        }
	        $res=array(
	            'code'=>0,
	            'msg'=>'成功！',
	            'data'=>$data
	        );
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
	 * 生成淘宝客淘口令
	 * @param string $token:用户身份令牌
	 * @param string $text:口令弹框内容，长度大于5个字符，建议传递商品名称
	 * @param string $url:口令跳转目标页，建议传递商品链接
	 * @param string $logo:口令弹框logoURL，建议传递商品图片链接
	 * @return array
	 * @return @param code:返回码
	 * @return @param msg:返回码说明
	 * @return @param data:返回数据
	 * @return @param data->list:用户余额变动记录
	 */
	public function createTpwd()
	{
		if(trim(I('post.token')) and trim(I('post.text')) and trim(I('post.url')) and trim(I('post.logo')))
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
				$data_ext=array(
						'user_id'=>$uid
				);
				$ext=json_encode($data_ext);
				$text=trim(I('post.text'));
				if(strpos( trim(I('post.url')),'http')===false)
				{
					$url='https:'.trim(I('post.url'));//商品链接
				}else {
					$url=trim(I('post.url'));//商品链接
				}
				$logo=trim(I('post.logo'));
				//淘宝客淘口令
				Vendor('tbk.tbk','','.class.php');
				$tbk=new \tbk();
				$res_tbk=$tbk->createTpwd($user_id='',$text,$url,$logo,$ext);
				$res=$res_tbk;
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
	 * 获取热门搜索
	 * @param number $type:类型 1淘宝（默认） 2拼多多 3京东 4自营商城
	 * @param number $num:条数，默认10条
	 * @return array
	 * @return @param code:返回码
	 * @return @param msg:返回码说明
	 * @return @param data:返回数据
	 * @return @param data->list:热门搜索列表
	 */
	public function getHotSearch()
	{
	    if(trim(I('post.type'))) {
	        $type=trim(I('post.type'));
	    }else {
	        $type=1;
	    }
	    $where="type=$type";
		if(trim(I('post.num'))) {
			$num=trim(I('post.num'));
		}else {
			$num=10;
		}
		$HotSearch=new \Common\Model\HotSearchModel();
		$list=$HotSearch->where($where)->limit(0,$num)->order('num desc,id asc')->select();
		if($list!==false)
		{
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
	 * 获取聚划算商品列表
	 * @param string $token:用户身份令牌
	 * @param int $page_no:第几页，默认：１
	 * @param int $page_size:页大小，默认20，1~100
	 * @return array
	 * @return @param code:返回码
	 * @return @param msg:返回码说明
	 * @return @param data:返回数据
	 * @return @param data->list:淘宝客商品列表
	 */
	public function searchJuItems()
	{
		//获取用户所绑定的推广位
		$pid='';
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
				$userMsg=$User->getUserMsg($uid);
				//如果是微信群主，使用微信群主推广位pid
				if($userMsg['tb_pid_master'])
				{
					$pid=$userMsg['tb_pid_master'];
				}else {
					$pid=$userMsg['tb_pid'];//淘宝推广位
				}
				$pid='mm_131714348_0_0';
				//会员组
				$group_id=$userMsg['group_id'];
				$UserGroup=new \Common\Model\UserGroupModel();
				$groupMsg=$UserGroup->getGroupMsg($group_id);
				$fee_user=$groupMsg['fee_user'];
				
				//第几页
				if(trim(I('post.page_no')))
				{
					$page_no=trim(I('post.page_no'));
				}else {
					$page_no=1;
				}
				//页大小
				if(trim(I('post.page_size')))
				{
					$page_size=trim(I('post.page_size'));
				}else {
					$page_size=20;
				}
				
				//获取商品列表
				$num_iid=trim(I('post.num_iid'));
				Vendor('tbk.tbk','','.class.php');
				$tbk=new \tbk();
				$res_tbk=$tbk->searchJuItems($page_no,$page_size,$pid,$postage='',$status='',$taobao_category_id='',$word='');
					
				$res=$res_tbk;
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
	 * 获取淘抢购商品列表
	 * @param string $token:用户身份令牌
	 * @param DateTime $start_time:最早开团时间
	 * @param DateTime $end_time:最晚开团时间
	 * @param int $page_no:第几页，默认：１
	 * @param int $page_size:页大小，默认6，1~100
	 * @return array
	 * @return @param code:返回码
	 * @return @param msg:返回码说明
	 * @return @param data:返回数据
	 * @return @param data->list:淘宝客商品列表
	 */
	public function getTqgJu()
	{
	    //第几页
	    if(trim(I('post.page_no'))) {
	        $page_no=trim(I('post.page_no'));
	    }else {
	        $page_no=1;
	    }
	    //页大小
	    if(trim(I('post.page_size'))) {
	        $page_size=trim(I('post.page_size'));
	    }else {
	        $page_size=6;
	    }
	    //获取商品列表
	    Vendor('tbk.tbk','','.class.php');
	    $tbk=new \tbk();
	    $pid=$tbk->pid;
	    $adzone_id=$tbk->adzone_id;
	    if(trim(I('post.token'))) {
	        //判断用户身份
	        $token=trim(I('post.token'));
	        $User=new \Common\Model\UserModel();
	        $res_token=$User->checkToken($token);
	        if($res_token['code']!=0) {
	            //用户身份不合法
	            $res=$res_token;
	            echo json_encode ($res,JSON_UNESCAPED_UNICODE);
	            exit();
	        }else {
	            $uid=$res_token['uid'];
	            $userMsg=$User->getUserMsg($uid);
	            //如果是微信群主，使用微信群主推广位pid
	            if($userMsg['tb_pid_master']) {
	                $pid=$userMsg['tb_pid_master'];
	                //广告位
	                $start=strripos($pid, '_');
	                $adzone_id=substr($pid, $start+1);
	            }else {
	                $pid=$userMsg['tb_pid'];//淘宝推广位
	                //广告位
	                $start=strripos($pid, '_');
	                $adzone_id=substr($pid, $start+1);
	            }
	        }
	    }
	    
	    //最早开团时间
	    if(trim(I('post.start_time'))) {
	        $start_time=trim(I('post.start_time'));
	    }else {
	        $start_time=date('Y-m-d H:i:s');
	    }
	    //最晚开团时间
	    if(trim(I('post.end_time'))) {
	        $end_time=trim(I('post.end_time'));
	    }else {
	        $Time=new \Common\Model\TimeModel();
	        $hour='+2';
	        $end_time=$Time->getAfterDateTime($start_time,'2','','','',$hour);
	    }
	    $res_tbk=$tbk->getTqgJu($pid,$adzone_id,$start_time,$end_time,$page_no,$page_size);
	    $res=$res_tbk;
		echo json_encode ($res,JSON_UNESCAPED_UNICODE);
	}
	
	/**
	 * 获取推荐淘宝客商品列表-本地
	 * @param int $agent_id:代理商ID
	 * @param int $p:第几页，默认：１
	 * @param int $per:页大小，默认10
	 * @return array
	 * @return @param code:返回码
	 * @return @param msg:返回码说明
	 * @return @param data:返回数据
	 * @return @param data->list:淘宝客商品列表
	 */
	public function getHotGoodsList()
	{
	    $agent_id=0;
	    if(trim(I('post.agent_id'))){
	        $agent_id=trim(I('post.agent_id'));
	    }
	    $where="agent_id=$agent_id";
	    //第几页
	    if(trim(I('post.p'))) {
	        $p=trim(I('post.p'));
	    }else {
	        $p=1;
	    }
	    //页大小
	    if(trim(I('post.per'))) {
	        $per=trim(I('post.per'));
	    }else {
	        $per=10;
	    }
	    //获取推荐淘宝商品列表
	    $TbGoods=new \Common\Model\TbGoodsModel();
	    $list=$TbGoods->where($where)->field('goods_id,goods_name,zk_final_price,pict_url,small_images,description,commission_rate,coupon_amount,volume,create_time')->order('sort desc,id desc')->page ( $p, $per )->select();
	    if($list!==false) {
	        $num=count($list);
	        for ($i=0;$i<$num;$i++) {
	            $list[$i]['small_images']=json_decode($list[$i]['small_images'],true);
	        }
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
	 * 获取0元购淘宝客商品列表-本地
	 * @param int $p:第几页，默认：１
	 * @param int $per:页大小，默认10
	 * @return array
	 * @return @param code:返回码
	 * @return @param msg:返回码说明
	 * @return @param data:返回数据
	 * @return @param data->list:淘宝客商品列表
	 */
	public function getFreeGoodsList()
	{
	    //第几页
	    if(trim(I('post.p'))) {
	        $p=trim(I('post.p'));
	    }else {
	        $p=1;
	    }
	    //页大小
	    if(trim(I('post.per'))) {
	        $per=trim(I('post.per'));
	    }else {
	        $per=10;
	    }
	    //获取推荐淘宝商品列表
	    $TbGoodsFree=new \Common\Model\TbGoodsFreeModel();
	    $list=$TbGoodsFree->field('goods_id,goods_name,zk_final_price,pict_url,small_images,description,commission_rate,coupon_amount,volume,subsidy_amount,create_time')->order('sort desc,id desc')->page ( $p, $per )->select();
	    if($list!==false) {
	        $num=count($list);
	        for ($i=0;$i<$num;$i++) {
	            $list[$i]['small_images']=json_decode($list[$i]['small_images'],true);
	        }
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
	 * 获取淘宝商品销量排行
	 * @param string $token:用户身份令牌
	 * @param int $time_type:查询时间类型，1昨日 2今日 3近7日 4本月，默认全部
	 * @param int $p:页码，默认第1页
	 * @param int $per:每页条数，默认6条
	 * @return array
	 * @return @param code:返回码
	 * @return @param msg:返回码说明
	 * @return @param data:返回数据
	 * @return @param data->list:商品列表
	 */
	public function getSellRanking()
	{
	    //获取用户所绑定的推广位
	    $pid='';
	    if(trim(I('post.token'))) {
	        //判断用户身份
	        $token=trim(I('post.token'));
	        $User=new \Common\Model\UserModel();
	        $res_token=$User->checkToken($token);
	        if($res_token['code']!=0) {
	            //用户身份不合法
	            $res=$res_token;
	            echo json_encode ($res,JSON_UNESCAPED_UNICODE);
	            exit();
	        }else {
	            $uid=$res_token['uid'];
	            $userMsg=$User->getUserMsg($uid);
	            //如果是微信群主，使用微信群主推广位pid
	            if($userMsg['tb_pid_master']) {
	                $pid=$userMsg['tb_pid_master'];
	            }else {
	                $pid=$userMsg['tb_pid'];//淘宝推广位
	            }
	            //会员组
	            $group_id=$userMsg['group_id'];
	        }
	    }else {
	        //普通会员组
	        $group_id=1;
	    }
	    
	    if(trim(I('post.p'))) {
	        $p=trim(I('post.p'));
	    }else {
	        $p=1;
	    }
	    if(trim(I('post.per'))) {
	        $per=trim(I('post.per'));
	    }else {
	        $per=6;
	    }
	    $begin=($p-1)*$per;
	    
	    //查询时间段
	    $where='1';
	    if(trim(I('post.time_type'))){
	        $time_type=trim(I('post.time_type'));
	        switch ($time_type){
	            //昨日
	            case 1:
	                $where.=" and DATE_SUB(CURDATE(), INTERVAL 1 DAY) = date(create_time)";
	                break;
	            //近日
	            case 2:
	                $where.=" and date_format(create_time,'%Y-%m-%d')=date_format(now(),'%Y-%m-%d')";
	                break;
	            //近7日
	            case 3:
	                $where.=" and DATE_SUB(CURDATE(), INTERVAL 7 DAY) = date(create_time)";
	                break;
	            //本月
	            case 4:
	                $where.=" and date_format(create_time,'%Y-%m')=date_format(now(),'%Y-%m')";
	                break;
	            default:
	                break;
	        }
	    }
	    //获取销量排行
	    $sql="select count(item_num) as num,num_iid from __PREFIX__tb_order where $where group by num_iid order by num desc limit $begin,$per";
	    $goodslist=M()->query($sql);
	    
	    //获取淘宝商品
	    $goods_allid='';
	    foreach ($goodslist as $l) {
	        $goods_allid.=$l['num_iid'].',';
	    }
	    if($goods_allid) {
	        $goods_allid=substr($goods_allid, 0,-1);
	        Vendor('tbk.tbk','','.class.php');
	        $tbk=new \tbk();
	        $ip='';
	        $res=$tbk->getItemList($goods_allid,$platform='2',$ip);
	        if($res['code']==0) {
	            //查询用户会员组
	            $UserGroup=new \Common\Model\UserGroupModel();
	            $groupMsg=$UserGroup->getGroupMsg($group_id);
	            $fee_user=$groupMsg['fee_user'];
	            
	            $list=$res['data']['list'];
	            $num=count($list);
	            for($i=0;$i<$num;$i++) {
	                //根据会员组计算相应佣金
	                //佣金
	                $list[$i]['commission']=$list[$i]['commission']*$fee_user/100;
	                //保留2位小数，四舍五不入
	                $list[$i]['commission']=substr(sprintf("%.3f",$list[$i]['commission']),0,-1);
	                
	                //销量
	                foreach ($goodslist as $l) {
	                    if($list[$i]['num_iid']==$l['num_iid']){
	                        $list[$i]['all_num']=$l['num'];
	                    }
	                }
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
	    echo json_encode ($res,JSON_UNESCAPED_UNICODE);
	}
	
	/**
	 * 商品烟雾弹
	 */
	public function getGoodsSmoke()
	{
	    //电话号码前缀
	    $phone_str_arr=array(130,131,132,133,134,135,136,137,138,139,147,150,151,152,153,154,155,156,157,158,159,166,170,171,172,173,174,175,176,177,178,179,180,181,182,183,184,185,186,187,188,189,191,198,199);
	    $phone_str_count=count($phone_str_arr);
	    //用户头像
	    $UserDetail=new \Common\Model\UserDetailModel();
	    $avatarlist=$UserDetail->where("avatar!=''")->field("avatar")->order('rand(id)')->limit(50)->select();
	    //已有会员头像数量
	    $avatarcount=count($avatarlist);
	    for($i=0;$i<50;$i++)
	    {
	        $avatar='';
	        if($i<=$avatarcount)
	        {
	            $avatar=$avatarlist[$i]['avatar'];
	            //判断头像是否是微信头像
	            if(is_url($avatar===false))
	            {
	                //不是网址形式
	                $avatar=WEB_URL.$avatar;
	            }
	        }
	        $phone_str_id=rand(0,$phone_str_count-1);
	        $phone_str=$phone_str_arr[$phone_str_id];
	        //电话号码后四位
	        $phone_last_four=rand(0000,9999);
	        $phone=$phone_str.'****'.$phone_last_four;
	        $smoke='用户'.$phone;
	        //操作行为
	        $action_id=rand(1,3);
	        switch($action_id)
	        {
	        case 1:
	        $action_str='领取了优惠券';
	        break;
            case 2:
            $action_str='分享了宝贝';
            break;
            case 3:
            $action_str='购买了宝贝';
            break;
            default:
            break;
	        }
	        $smoke.=$action_str;
	        //时间
            $time=rand(1,59);
            $smoke.=$time.'分钟前';
            $smoke_arr[]=array(
            'phone'=>$phone,
            'action_id'=>$action_id,
            'action'=>$action_str,
            'time'=>$time,
            'smoke'=>$smoke,
            'avatar'=>$avatar
            );
	    }
	    //二维数根据time排序（正序）
	    array_multisort(array_column($smoke_arr,'time'),SORT_ASC,$smoke_arr);
        $data=array(
            'list'=>$smoke_arr
        );
        $res=array(
        'code'=>$this->ERROR_CODE_COMMON['SUCCESS'],
        'msg'=>'成功',
        'data'=>$data
        );
	   echo json_encode ($res,JSON_UNESCAPED_UNICODE);
	}
	
	/**
	 * 获取选品库商品列表
	 * @param int $favorites_id:选品库ID
	 * @param int $p:页码，默认第1页
	 * @param int $per:每页条数，默认6条
	 * @return array
	 * @return @param code:返回码
	 * @return @param msg:返回码说明
	 * @return @param data:返回数据
	 * @return @param data->list:选品库商品列表
	 */
	public function getFavoritesUatm()
	{
	    if(trim(I('post.favorites_id'))){
	        $favorites_id=trim(I('post.favorites_id'));
	        if(trim(I('post.p'))) {
	            $p=trim(I('post.p'));
	        }else {
	            $p=1;
	        }
	        if(trim(I('post.per'))) {
	            $per=trim(I('post.per'));
	        }else {
	            $per=6;
	        }
	        Vendor('tbk.tbk','','.class.php');
	        $tbk=new \tbk();
	        $adzone_id=$tbk->adzone_id;
	        $res_tbk=$tbk->getFavoritesUatmItem($favorites_id,'2',$p,$per,$adzone_id);
	        if($res_tbk['code']===0){
	            $list=$res_tbk['data'];
	            $data=array(
	                'list'=>$list
	            );
	            $res=array(
	                'code'=>$this->ERROR_CODE_COMMON['SUCCESS'],
	                'msg'=>'成功',
	                'data'=>$data
	            );
	        }else {
	            $res=$res_tbk;
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