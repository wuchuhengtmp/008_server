<?php
namespace Admin\Controller;
use Admin\Common\Controller\AuthController;
class SystemController extends AuthController
{
    public function index_show()
    {
    	if($_SESSION['a_group_id']==4)
    	{
    		$this->display('index_agent');
    	}else {
    	    //统计会员数量
    	    $User=new \Common\Model\UserModel();
    	    //总会员数
    	    $user_allnum=$User->count();
    	    $this->assign('user_allnum',$user_allnum);
    	    //普通会员数
    	    $user_num1=$User->where("group_id=1")->count();
    	    $this->assign('user_num1',$user_num1);
    	    //VIP会员
    	    $this->assign('user_vipnum',$user_allnum-$user_num1);
    	    //今日新增会员
    	    $user_today_num=$User->where("date_format(register_time,'%Y-%m-%d')=date_format(now(),'%Y-%m-%d')")->count();
    	    $this->assign('user_today_num',$user_today_num);
    	    //本月新增会员
    	    $user_month_num=$User->where("date_format(register_time,'%Y-%m')=date_format(now(),'%Y-%m')")->count();
    	    $this->assign('user_month_num',$user_month_num);
    	    
    	    //统计淘宝订单数
    	    $TbOrder=new \Common\Model\TbOrderModel();
    	    //已结算订单
    	    $tb_order_finished_num=$TbOrder->where("tk_status='3'")->count();
    	    $this->assign('tb_order_finished_num',$tb_order_finished_num);
    	    //已付款订单
    	    $tb_order_pay_num=$TbOrder->where("tk_status='12'")->count();
    	    $this->assign('tb_order_pay_num',$tb_order_pay_num);
    	    //今日订单
    	    $tb_order_today_num=$TbOrder->where("date_format(create_time,'%Y-%m-%d')=date_format(now(),'%Y-%m-%d')")->count();
    	    $this->assign('tb_order_today_num',$tb_order_today_num);
    	    //本月订单
    	    $tb_order_month_num=$TbOrder->where("date_format(create_time,'%Y-%m')=date_format(now(),'%Y-%m')")->count();
    	    $this->assign('tb_order_month_num',$tb_order_month_num);
    	    
    	    //统计拼多多订单数
    	    $PddOrder=new \Common\Model\PddOrderModel();
    	    //已结算订单
    	    $pdd_order_finished_num=$PddOrder->where("order_status='5'")->count();
    	    $this->assign('pdd_order_finished_num',$pdd_order_finished_num);
    	    //已付款订单
    	    $pdd_order_pay_num=$PddOrder->where("order_status in (0,1,2,3)")->count();
    	    $this->assign('pdd_order_pay_num',$pdd_order_pay_num);
    	    //今日订单
    	    $pdd_order_today_num=$PddOrder->where("date_format(order_create_time,'%Y-%m-%d')=date_format(now(),'%Y-%m-%d')")->count();
    	    $this->assign('pdd_order_today_num',$pdd_order_today_num);
    	    //本月订单
    	    $pdd_order_month_num=$PddOrder->where("date_format(order_create_time,'%Y-%m')=date_format(now(),'%Y-%m')")->count();
    	    $this->assign('pdd_order_month_num',$pdd_order_month_num);
    	    
    	    //统计京东订单数
    	    $JingdongOrderDetail=new \Common\Model\JingdongOrderDetailModel();
    	    //已结算订单
    	    $jd_order_finished_num=$JingdongOrderDetail->where("validCode=18")->count();
    	    $this->assign('jd_order_finished_num',$jd_order_finished_num);
    	    //已付款订单
    	    $jd_order_pay_num=$JingdongOrderDetail->where("validCode in (16,17)")->count();
    	    $this->assign('jd_order_pay_num',$jd_order_pay_num);
    	    //今日订单
    	    $jd_order_today_num=$JingdongOrderDetail->where("date_format(orderTime,'%Y-%m-%d')=date_format(now(),'%Y-%m-%d')")->count();
    	    $this->assign('jd_order_today_num',$jd_order_today_num);
    	    //本月订单
    	    $jd_order_month_num=$JingdongOrderDetail->where("date_format(orderTime,'%Y-%m')=date_format(now(),'%Y-%m')")->count();
    	    $this->assign('jd_order_month_num',$jd_order_month_num);

            //统计唯品会订单数
            $VipOrder=new \Common\Model\VipOrderModel();
            //已结算订单
            $vip_order_finished_num=$VipOrder->where("settled=2")->count();
            $this->assign('vip_order_finished_num',$vip_order_finished_num);
            //已付款订单
            $vip_order_pay_num=$VipOrder->where("orderSubStatusName in ('已下单','已付款','已签收','待结算')")->count();
            $this->assign('vip_order_pay_num',$vip_order_pay_num);
            //今日订单
            $vip_order_today_num=$VipOrder->where("date_format(orderTime/1000,'%Y-%m-%d')=date_format(now(),'%Y-%m-%d')")->count();
            $this->assign('vip_order_today_num',$vip_order_today_num);
            //本月订单
            $vip_order_month_num=$VipOrder->where("date_format(orderTime/1000,'%Y-%m')=date_format(now(),'%Y-%m')")->count();
            $this->assign('vip_order_month_num',$vip_order_month_num);
    	    
    	    //收益统计
    	    //淘宝总结算收益-未扣除分配给用户的
    	    $amount_tb=$TbOrder->where("tk_status='3'")->sum('tb_commission');
    	    //淘宝今日结算收益-未扣除分配给用户的
    	    $amount_tb_today=$TbOrder->where("tk_status='3' and date_format(create_time,'%Y-%m-%d')=date_format(now(),'%Y-%m-%d')")->sum('tb_commission');
    	    //淘宝本月结算收益-未扣除分配给用户的
    	    $amount_tb_month=$TbOrder->where("tk_status='3' and date_format(create_time,'%Y-%m')=date_format(now(),'%Y-%m')")->sum('tb_commission');
    	    
    	    //拼多多总结算收益-未扣除分配给用户的
    	    $amount_pdd=$PddOrder->where("order_status='5'")->sum('pdd_commission');
    	    $amount_pdd/=100;
    	    //拼多多今日结算收益-未扣除分配给用户的
    	    $amount_pdd_today=$PddOrder->where("order_status='5' and date_format(order_create_time,'%Y-%m-%d')=date_format(now(),'%Y-%m-%d')")->sum('pdd_commission');
    	    $amount_pdd_today/=100;
    	    //拼多多本月结算收益-未扣除分配给用户的
    	    $amount_pdd_month=$PddOrder->where("order_status='5' and date_format(order_create_time,'%Y-%m')=date_format(now(),'%Y-%m')")->sum('pdd_commission');
    	    $amount_pdd_month/=100;
    	    
    	    //京东总结算收益-未扣除分配给用户的
    	    $amount_jd=$JingdongOrderDetail->where("validCode=18")->sum('actualFee');
    	    //京东今日结算收益-未扣除分配给用户的
    	    $amount_jd_today=$JingdongOrderDetail->where("validCode=18 and date_format(orderTime,'%Y-%m-%d')=date_format(now(),'%Y-%m-%d')")->sum('actualFee');
    	    //京东本月结算收益-未扣除分配给用户的
    	    $amount_jd_month=$JingdongOrderDetail->where("validCode=18 and date_format(orderTime,'%Y-%m')=date_format(now(),'%Y-%m')")->sum('actualFee');

            //唯品会总结算收益-未扣除分配给用户的
            $amount_vip=$VipOrder->where("settled=2")->sum('vipCommission');
            //唯品会今日结算收益-未扣除分配给用户的
            $amount_vip_today=$VipOrder->where("settled=2 and date_format(orderTime/1000,'%Y-%m-%d')=date_format(now(),'%Y-%m-%d')")->sum('vipCommission');
            //唯品会本月结算收益-未扣除分配给用户的
            $amount_vip_month=$VipOrder->where("settled=2 and date_format(orderTime/1000,'%Y-%m')=date_format(now(),'%Y-%m')")->sum('vipCommission');

    	    //总收益
    	    $amount=$amount_tb+$amount_pdd+$amount_jd+$amount_vip;
    	    $this->assign('amount',$amount);
    	    //今日收益
    	    $amount_today=$amount_tb_today+$amount_pdd_today+$amount_jd_today+$amount_vip_today;
    	    $this->assign('amount_today',$amount_today);
    	    //本月收益
    	    $amount_month=$amount_tb_month+$amount_pdd_month+$amount_jd_month+$amount_vip_month;
    	    $this->assign('amount_month',$amount_month);
    	    
    	    //获取最近30天淘宝订单
    	    $sql="SELECT count(id) as num,date(create_time) as date FROM __PREFIX__tb_order WHERE DATE_SUB(CURDATE(), INTERVAL 30 DAY) <= date(create_time) GROUP BY date(create_time)";
    	    $list=M()->query($sql);
    	    $this->assign('list',$list);
    	    
    	    $this->display();
    	}
    }
    
    //站点设置
    public function webset()
    {
        if($_POST) {
            //App名称
            $app_name=I('post.app_name');
            $old_app_name=I('post.old_app_name');
            //苹果版本号
            $version_ios=I('post.version_ios');
            $old_version_ios=I('post.old_version_ios');
            //安卓版本号
            $version_android=I('post.version_android');
            $old_version_android=I('post.old_version_android');
            //苹果下载地址
            $down_ios=I('post.down_ios');
            $old_down_ios=I('post.old_down_ios');
            //安卓下载地址
            $down_android=I('post.down_android');
            $old_down_android=I('post.old_down_android');
            //苹果新版本更新内容
            $update_content_ios=I('post.update_content_ios');
            $old_update_content_ios=I('post.old_update_content_ios');
            //安卓新版本更新内容
            $update_content_android=I('post.update_content_android');
            $old_update_content_android=I('post.old_update_content_android');
            
            //网址
            $web_url=I('post.web_url');
            $old_web_url=I('post.old_web_url');
            //web_title
            $web_title=I('post.web_title');
            $old_web_title=I('post.old_web_title');
            //keywords
            $keywords=I('post.keywords');
            $old_keywords=I('post.old_keywords');
            //description
            $description=I('post.description');
            $old_description=I('post.old_description');
            //copyright
            $copyright=I('post.copyright');
            $old_copyright=I('post.old_copyright');
            //web_title_en
            $web_title_en=I('post.web_title_en');
            $old_web_title_en=I('post.old_web_title_en');
            //keywords_en
            $keywords_en=I('post.keywords_en');
            $old_keywords_en=I('post.old_keywords_en');
            //description_en
            $description_en=I('post.description_en');
            $old_description_en=I('post.old_description_en');
            //copyright_en
            $copyright_en=I('post.copyright_en');
            $old_copyright_en=I('post.old_copyright_en');
            
            //载入系统配置文件
            $old_str=file_get_contents('./Public/inc/config.php');
            //替换WEB_TITLE
            $find_str_web_title="define('WEB_TITLE','$old_web_title');";
            $replace_str_web_title="define('WEB_TITLE','$web_title');";
            $str=str_replace($find_str_web_title,$replace_str_web_title,$old_str);
            //替换App名称
            $fs_app_name="define('APP_NAME','$old_app_name');";
            $rs_app_name="define('APP_NAME','$app_name');";
            $str=str_replace($fs_app_name,$rs_app_name,$str);
            //替换苹果版本号
            $fs_version_ios="define('VERSION_IOS','$old_version_ios');";
            $rs_version_ios="define('VERSION_IOS','$version_ios');";
            $str=str_replace($fs_version_ios,$rs_version_ios,$str);
            //替换安卓版本号
            $fs_version_android="define('VERSION_ANDROID','$old_version_android');";
            $rs_version_android="define('VERSION_ANDROID','$version_android');";
            $str=str_replace($fs_version_android,$rs_version_android,$str);
            //替换苹果下载地址
            $fs_down_ios="define('DOWN_IOS','$old_down_ios');";
            $rs_down_ios="define('DOWN_IOS','$down_ios');";
            $str=str_replace($fs_down_ios,$rs_down_ios,$str);
            //替换安卓下载地址
            $fs_down_android="define('DOWN_ANDROID','$old_down_android');";
            $rs_down_android="define('DOWN_ANDROID','$down_android');";
            $str=str_replace($fs_down_android,$rs_down_android,$str);
            //替换苹果新版本更新内容
            $fs_update_content_ios="define('UPDATE_CONTENT_IOS','$old_update_content_ios');";
            $rs_update_content_ios="define('UPDATE_CONTENT_IOS','$update_content_ios');";
            $str=str_replace($fs_update_content_ios,$rs_update_content_ios,$str);
            //替换安卓下载地址
            $fs_update_content_android="define('UPDATE_CONTENT_ANDROID','$old_update_content_android');";
            $rs_update_content_android="define('UPDATE_CONTENT_ANDROID','$update_content_android');";
            $str=str_replace($fs_update_content_android,$rs_update_content_android,$str);
            
            //替换网址
            $fs_web_url="define('WEB_URL','$old_web_url');";
            $rs_web_url="define('WEB_URL','$web_url');";
            $str=str_replace($fs_web_url,$rs_web_url,$str);
            //替换keywords
            $find_str_keywords="define('seo_keywords','$old_keywords');";
            $replace_str_keywords="define('seo_keywords','$keywords');";
            $str=str_replace($find_str_keywords,$replace_str_keywords,$str);
            //替换description
            $find_str_description="define('seo_description','$old_description');";
            $replace_str_description="define('seo_description','$description');";
            $str=str_replace($find_str_description,$replace_str_description,$str);
            //替换copyright
            $find_str_copyright="define('seo_copyright','$old_copyright');";
            $replace_str_copyright="define('seo_copyright','$copyright');";
            $str=str_replace($find_str_copyright,$replace_str_copyright,$str);
            //替换web_title_en
            $find_str_web_title_en="define('WEB_TITLE_EN','$old_web_title_en');";
            $replace_str_web_title_en="define('WEB_TITLE_EN','$web_title_en');";
            $str=str_replace($find_str_web_title_en,$replace_str_web_title_en,$str);
            //替换keywords_en
            $find_str_keywords_en="define('seo_keywords_en','$old_keywords_en');";
            $replace_str_keywords_en="define('seo_keywords_en','$keywords_en');";
            $str=str_replace($find_str_keywords_en,$replace_str_keywords_en,$str);
            //替换description_en
            $find_str_description_en="define('seo_description_en','$old_description_en');";
            $replace_str_description_en="define('seo_description_en','$description_en');";
            $str=str_replace($find_str_description_en,$replace_str_description_en,$str);
            //替换copyright_en
            $find_str_copyright_en="define('seo_copyright_en','$old_copyright_en');";
            $replace_str_copyright_en="define('seo_copyright_en','$copyright_en');";
            $str=str_replace($find_str_copyright_en,$replace_str_copyright_en,$str);
            
            //上传logo
            if(!empty($_FILES['logo']['name'])) {
                $config = array(
                    'mimes'         =>  array(), //允许上传的文件MiMe类型
                    'maxSize'       =>  1024*1024*4, //上传的文件大小限制 (0-不做限制)
                    'exts'          =>  array( 'png' ), //允许上传的文件后缀
                    'subName'       =>  '', //子目录创建方式，[0]-函数名，[1]-参数，多个参数使用数组
                    'rootPath'      =>  './Public/static/admin/img/', //保存根路径
                    'savePath'      =>  '', //保存路径
                    'saveExt'       =>  'png', //文件保存后缀，空则使用原后缀
                    'replace'       =>  true, //存在同名是否覆盖
                    'saveName'      =>  'logo', //上传文件命名规则，[0]-函数名，[1]-参数，多个参数使用数组
                );
                $upload = new \Think\Upload($config);
                // 上传单个文件
                $info = $upload->uploadOne($_FILES['logo'],1);
                if(!$info) {
                    // 上传错误提示错误信息
                    $this->error($upload->getError());
                }else{
                    // 上传成功
                    // 文件完成路径
                    $filepath=$config['rootPath'].$info['savepath'].$info['savename'];
                }
            }
            
            //写入系统配置文件
            file_put_contents('./Public/inc/config.php',$str);
            layout(false);
            $this->success('更新成功！');
        }else {
            //获取网站设置信息
            $msg['app_name']=APP_NAME;//App名称
            $msg['version_ios']=VERSION_IOS;//苹果版本号
            $msg['version_android']=VERSION_ANDROID;//安卓版本号
            $msg['down_ios']=DOWN_IOS;//苹果下载地址
            $msg['down_android']=DOWN_ANDROID;//安卓下载地址
            $msg['update_content_ios']=UPDATE_CONTENT_IOS;//苹果新版本更新内容
            $msg['update_content_android']=UPDATE_CONTENT_ANDROID;//安卓新版本更新内容
            
            $msg['web_title']=WEB_TITLE;
            $msg['keywords']=seo_keywords;
            $msg['description']=seo_description;
            $msg['copyright']=seo_copyright;
            $msg['web_title_en']=WEB_TITLE_EN;
            $msg['keywords_en']=seo_keywords_en;
            $msg['description_en']=seo_description_en;
            $msg['copyright_en']=seo_copyright_en;
            $msg['web_url']=WEB_URL;//网址
            $this->assign('msg',$msg);
            
            $this->display();
        }
    }
    
    //敏感词过滤
    public function sensitive()
    {
    	//载入配置文件
    	$str=file_get_contents('./Public/inc/sensitive_word.txt');
    	$msg=trim($str);
    	$this->assign('msg',$msg);
    	if(I('post.'))
    	{
    		$sensitive_word=I('post.sensitive_word');
    		//写入配置文件
    		file_put_contents('./Public/inc/sensitive_word.txt',$sensitive_word);
    		layout(false);
    		$this->success('更新成功！');
    	}else {
    		$this->display();
    	}
    }
    
    //费用规则设置
    public function feeset()
    {
    	if($_POST) {
    		layout(false);
    		//直接推荐返利比例-百分比
    		$referrer_rate=I('post.referrer_rate');
    		$old_referrer_rate=I('post.old_referrer_rate');
    		//间接推荐返利比例-百分比
    		$referrer_rate2=I('post.referrer_rate2');
    		$old_referrer_rate2=I('post.old_referrer_rate2');
    		
    		//会员升级费用-1个月
    		$upgrade_fee_month=I('post.upgrade_fee_month');
    		//必须为整数
    		if(is_natural_num($upgrade_fee_month)===false) {
    			$this->error('会员升级费用-1个月必须为不小于零的整数！');
    		}
    		$old_upgrade_fee_month=I('post.old_upgrade_fee_month');
    		
    		//会员升级费用-1年
    		$upgrade_fee_year=I('post.upgrade_fee_year');
    		//必须为整数
    		if(is_natural_num($upgrade_fee_year)===false) {
    			$this->error('会员升级费用-1年必须为不小于零的整数！');
    		}
    		$old_upgrade_fee_year=I('post.old_upgrade_fee_year');
    		
    		//会员升级费用-终生
    		$upgrade_fee_forever=I('post.upgrade_fee_forever');
    		//必须为整数
    		if(is_natural_num($upgrade_fee_forever)===false) {
    			$this->error('会员升级费用-终生必须为不小于零的整数！');
    		}
    		$old_upgrade_fee_forever=I('post.old_upgrade_fee_forever');
    		
    		//平台微信号
    		$platform_wx=I('post.platform_wx');
    		$old_platform_wx=I('post.old_platform_wx');
    		
    		//分享淘宝商品网址
    		$share_url=I('post.share_url');
    		$old_share_url=I('post.old_share_url');
    		//分享注册下载网址
    		$share_url_register=I('post.share_url_register');
    		$old_share_url_register=I('post.old_share_url_register');
    		//VIP专用分享网址
    		$share_url_vip=I('post.share_url_vip');
    		$old_share_url_vip=I('post.old_share_url_vip');
    
    		//载入系统配置文件
    		$str=file_get_contents('./Public/inc/fee.config.php');
    		
    		//替换直接推荐返利比例-百分比
    		$find_str_referrer_rate="define('REFERRER_RATE','$old_referrer_rate');";
    		$replace_str_referrer_rate="define('REFERRER_RATE','$referrer_rate');";
    		$str=str_replace($find_str_referrer_rate,$replace_str_referrer_rate,$str);
    		//替换间接推荐返利比例-百分比
    		$find_str_referrer_rate2="define('REFERRER_RATE2','$old_referrer_rate2');";
    		$replace_str_referrer_rate2="define('REFERRER_RATE2','$referrer_rate2');";
    		$str=str_replace($find_str_referrer_rate2,$replace_str_referrer_rate2,$str);
    		
    		//会员升级费用-1个月
    		$fs_upgrade_fee_month="define('UPGRADE_FEE_MONTH','$old_upgrade_fee_month');";
    		$rs_upgrade_fee_month="define('UPGRADE_FEE_MONTH','$upgrade_fee_month');";
    		$str=str_replace($fs_upgrade_fee_month,$rs_upgrade_fee_month,$str);
    		
    		//会员升级费用-1年
    		$fs_upgrade_fee_year="define('UPGRADE_FEE_YEAR','$old_upgrade_fee_year');";
    		$rs_upgrade_fee_year="define('UPGRADE_FEE_YEAR','$upgrade_fee_year');";
    		$str=str_replace($fs_upgrade_fee_year,$rs_upgrade_fee_year,$str);
    		
    		//会员升级费用-终生
    		$fs_upgrade_fee_forever="define('UPGRADE_FEE_FOREVER','$old_upgrade_fee_forever');";
    		$rs_upgrade_fee_forever="define('UPGRADE_FEE_FOREVER','$upgrade_fee_forever');";
    		$str=str_replace($fs_upgrade_fee_forever,$rs_upgrade_fee_forever,$str);
    		
    		//平台微信号
    		$fs_platform_wx="define('PLATFORM_WX','$old_platform_wx');";
    		$rs_platform_wx="define('PLATFORM_WX','$platform_wx');";
    		$str=str_replace($fs_platform_wx,$rs_platform_wx,$str);
    		
    		//分享淘宝商品网址
    		$fs_share_url="define('SHARE_URL','$old_share_url');";
    		$rs_share_url="define('SHARE_URL','$share_url');";
    		$str=str_replace($fs_share_url,$rs_share_url,$str);
    		//分享注册下载网址
    		$fs_share_url_register="define('SHARE_URL_REGISTER','$old_share_url_register');";
    		$rs_share_url_register="define('SHARE_URL_REGISTER','$share_url_register');";
    		$str=str_replace($fs_share_url_register,$rs_share_url_register,$str);
    		//VIP专用分享网址
    		$fs_share_url_vip="define('SHARE_URL_VIP','$old_share_url_vip');";
    		$rs_share_url_vip="define('SHARE_URL_VIP','$share_url_vip');";
    		$str=str_replace($fs_share_url_vip,$rs_share_url_vip,$str);
    
    		//写入配置文件
    		file_put_contents('./Public/inc/fee.config.php',$str);
    		$this->success('更新成功！');
    	}else {
    	    //获取配置文件
    	    require_once './Public/inc/fee.config.php';
    	    $msg=array(
    	        'point_register'=>POINT_REGISTER,
    	        'point_recommend_register'=>POINT_RECOMMEND_REGISTER,
    	        'referrer_rate'=>REFERRER_RATE,
    	        'referrer_rate2'=>REFERRER_RATE2,
    	        'upgrade_fee_month'=>UPGRADE_FEE_MONTH,
    	        'upgrade_fee_year'=>UPGRADE_FEE_YEAR,
    	        'upgrade_fee_forever'=>UPGRADE_FEE_FOREVER,
    	        'platform_wx'=>PLATFORM_WX,
    	        'share_url'=>SHARE_URL,
    	        'share_url_register'=>SHARE_URL_REGISTER,
    	        'share_url_vip'=>SHARE_URL_VIP,//VIP专用分享网址
    	    );
    	    $this->assign('msg',$msg);
    	    
    		$this->display();
    	}
    }
    
    //应用账号配置
    public function accountSet()
    {
        //获取配置文件
        require_once './Public/inc/account.config.php';
        
        if(I('post.')) {
            layout(false);
            //淘宝客AppID
            $tbk_appid=I('post.tbk_appid');
            $old_tbk_appid=I('post.old_tbk_appid');
            //淘宝客App key
            $tbk_appkey=I('post.tbk_appkey');
            $old_tbk_appkey=I('post.old_tbk_appkey');
            //淘宝客App secret
            $tbk_appsecret=I('post.tbk_appsecret');
            $old_tbk_appsecret=I('post.old_tbk_appsecret');
            //淘宝客PID
            $tbk_pid=I('post.tbk_pid');
            $old_tbk_pid=I('post.old_tbk_pid');
            //淘宝客广告位ID
            $tbk_adzone_id=I('post.tbk_adzone_id');
            $old_tbk_adzone_id=I('post.old_tbk_adzone_id');
            //维易淘宝客key
            $wy_appkey=I('post.wy_appkey');
            $old_wy_appkey=I('post.old_wy_appkey');
            //淘宝客渠道专用PID
            $tbk_relation_pid=I('post.tbk_relation_pid');
            $old_tbk_relation_pid=I('post.old_tbk_relation_pid');
            //广告位ID
            $adzone_id=I('post.adzone_id');
            $old_adzone_id=I('post.old_adzone_id');
            //联盟授权码
            $auth_code=I('post.auth_code');
            $old_auth_code=I('post.old_auth_code');
            
            //拼多多client_id
            $pdd_client_id=I('post.pdd_client_id');
            $old_pdd_client_id=I('post.old_pdd_client_id');
            //拼多多client_secret
            $pdd_client_secret=I('post.pdd_client_secret');
            $old_pdd_client_secret=I('post.old_pdd_client_secret');
            //拼多多推广位pid
            $pdd_pid=I('post.pdd_pid');
            $old_pdd_pid=I('post.old_pdd_pid');
            
            //极光推送key
            $jpush_key=I('post.jpush_key');
            $old_jpush_key=I('post.old_jpush_key');
            //极光推送secret
            $jpush_secret=I('post.jpush_secret');
            $old_jpush_secret=I('post.old_jpush_secret');
            
            //支付宝appid
            $alipay_appid=I('post.alipay_appid');
            $old_alipay_appid=I('post.old_alipay_appid');
            //支付宝私钥
            $alipay_private_key=I('post.alipay_private_key');
            $old_alipay_private_key=I('post.old_alipay_private_key');
            //支付宝公钥
            $alipay_public_key=I('post.alipay_public_key');
            $old_alipay_public_key=I('post.old_alipay_public_key');

            //唯品会appkey
            $vip_appkey=I('post.vip_appkey');
            $old_vip_appkey=I('post.old_vip_appkey');
            //唯品会appsecret
            $vip_appsecret=I('post.vip_appsecret');
            $old_vip_appsecret=I('post.old_vip_appsecret');


            //add by jimphei
            //京东参数
            $jd_unionid = trim(I('post.jd_unionid'));
            $jd_auth_key = trim(I('post.jd_auth_key'));
            $android_appkey = trim(I('post.android_appkey'));
            $android_appsecret = trim(I('post.android_appsecret'));
            $ios_appkey = trim(I('post.ios_appkey'));
            $ios_appsecret = trim(I('post.ios_appsecret'));

            $tk_pid = I('post.tk_pid');
            $sms_apikey = trim(I('post.sms_apikey'));
            $sms_tpl = trim(I('post.sms_tpl'));
            $sms_sid = trim(I('post.sms_sid'));

            $tempcontent = file_get_contents('./Public/inc/extra.config.temp.php');

            $tempcontent = str_replace('[jd_unionid]',$jd_unionid,$tempcontent);
            $tempcontent = str_replace('[jd_auth_key]',$jd_auth_key,$tempcontent);
            $tempcontent = str_replace('[android_appkey]',$android_appkey,$tempcontent);
            $tempcontent = str_replace('[android_appsecret]',$android_appsecret,$tempcontent);
            $tempcontent = str_replace('[ios_appkey]',$ios_appkey,$tempcontent);
            $tempcontent = str_replace('[ios_appsecret]',$ios_appsecret,$tempcontent);
            $tempcontent = str_replace('[tk_pid]',json_encode($tk_pid),$tempcontent);
            $tempcontent = str_replace('[sms_apikey]',$sms_apikey,$tempcontent);
            $tempcontent = str_replace('[sms_tpl]',$sms_tpl,$tempcontent);
            $tempcontent = str_replace('[sms_sid]',$sms_sid,$tempcontent);

            $dist_file = './Public/inc/extra.config.php';
            file_put_contents($dist_file,$tempcontent);
            
            //载入系统配置文件
            $str=file_get_contents('./Public/inc/account.config.php');
            //替换淘宝客AppID
            $fs_point_register="define('TBK_APPID','$old_tbk_appid');";
            $rs_point_register="define('TBK_APPID','$tbk_appid');";
            $str=str_replace($fs_point_register,$rs_point_register,$str);
            //替换淘宝客App key
            $fs_tbk_appkey="define('TBK_APPKEY','$old_tbk_appkey');";
            $rs_tbk_appkey="define('TBK_APPKEY','$tbk_appkey');";
            $str=str_replace($fs_tbk_appkey,$rs_tbk_appkey,$str);
            //替换淘宝客App secret
            $fs_tbk_appsecret="define('TBK_APPSECRET','$old_tbk_appsecret');";
            $rs_tbk_appsecret="define('TBK_APPSECRET','$tbk_appsecret');";
            $str=str_replace($fs_tbk_appsecret,$rs_tbk_appsecret,$str);
            //替换淘宝客PID
            $fs_tbk_pid="define('TBK_PID','$old_tbk_pid');";
            $rs_tbk_pid="define('TBK_PID','$tbk_pid');";
            $str=str_replace($fs_tbk_pid,$rs_tbk_pid,$str);
            //替换淘宝客广告位ID
            $fs_tbk_adzone_id="define('TBK_ADZONE_ID','$old_tbk_adzone_id');";
            $rs_tbk_adzone_id="define('TBK_ADZONE_ID','$tbk_adzone_id');";
            $str=str_replace($fs_tbk_adzone_id,$rs_tbk_adzone_id,$str);
            //替换淘宝客渠道专用PID
            $fs_tbk_relation_pid="define('TBK_RELATION_PID','$old_tbk_relation_pid');";
            $rs_tbk_relation_pid="define('TBK_RELATION_PID','$tbk_relation_pid');";
            $str=str_replace($fs_tbk_relation_pid,$rs_tbk_relation_pid,$str);
            //替换维易淘宝客key
            $fs_wy_appkey="define('WY_APPKEY','$old_wy_appkey');";
            $rs_wy_appkey="define('WY_APPKEY','$wy_appkey');";
            $str=str_replace($fs_wy_appkey,$rs_wy_appkey,$str);
            //替换广告位ID
            $fs_adzone_id="define('ADZONE_ID','$old_adzone_id');";
            $rs_adzone_id="define('ADZONE_ID','$adzone_id');";
            $str=str_replace($fs_adzone_id,$rs_adzone_id,$str);
            //替换联盟授权码
            $fs_auth_code="define('AUTH_CODE','$old_auth_code');";
            $rs_auth_code="define('AUTH_CODE','$auth_code');";
            $str=str_replace($fs_auth_code,$rs_auth_code,$str);
            
            //替换拼多多client_id
            $fs_pdd_client_id="define('PDD_CLIENT_ID','$old_pdd_client_id');";
            $rs_pdd_client_id="define('PDD_CLIENT_ID','$pdd_client_id');";
            $str=str_replace($fs_pdd_client_id,$rs_pdd_client_id,$str);
            //替换拼多多client_secret
            $fs_pdd_client_secret="define('PDD_CLIENT_SECRET','$old_pdd_client_secret');";
            $rs_pdd_client_secret="define('PDD_CLIENT_SECRET','$pdd_client_secret');";
            $str=str_replace($fs_pdd_client_secret,$rs_pdd_client_secret,$str);
            //替换拼多多推广位pid
            $fs_pdd_pid="define('PDD_PID','$old_pdd_pid');";
            $rs_pdd_pid="define('PDD_PID','$pdd_pid');";
            $str=str_replace($fs_pdd_pid,$rs_pdd_pid,$str);
            
            //替换极光推送key
            $fs_jpush_key="define('JPUSH_KEY','$old_jpush_key');";
            $rs_jpush_key="define('JPUSH_KEY','$jpush_key');";
            $str=str_replace($fs_jpush_key,$rs_jpush_key,$str);
            //替换极光推送secret
            $fs_jpush_secret="define('JPUSH_SECRET','$old_jpush_secret');";
            $rs_jpush_secret="define('JPUSH_SECRET','$jpush_secret');";
            $str=str_replace($fs_jpush_secret,$rs_jpush_secret,$str);
            
            //替换支付宝appid
            $fs_alipay_appid="define('ALIPAY_APPID','$old_alipay_appid');";
            $rs_alipay_appid="define('ALIPAY_APPID','$alipay_appid');";
            $str=str_replace($fs_alipay_appid,$rs_alipay_appid,$str);
            //替换支付宝私钥
            $fs_alipay_private_key="define('ALIPAY_PRIVATE_KEY','$old_alipay_private_key');";
            $rs_alipay_private_key="define('ALIPAY_PRIVATE_KEY','$alipay_private_key');";
            $str=str_replace($fs_alipay_private_key,$rs_alipay_private_key,$str);
            //替换支付宝公钥
            $fs_alipay_public_key="define('ALIPAY_PUBLIC_KEY','$old_alipay_public_key');";
            $rs_alipay_public_key="define('ALIPAY_PUBLIC_KEY','$alipay_public_key');";
            $str=str_replace($fs_alipay_public_key,$rs_alipay_public_key,$str);

            //替换唯品会appkey
            $fs_vip_appkey="define('VIP_APPKEY','$old_vip_appkey');";
            $rs_vip_appkey="define('VIP_APPKEY','$vip_appkey');";
            $str=str_replace($fs_vip_appkey,$rs_vip_appkey,$str);
            //替换唯品会appsecret
            $fs_vip_appsecret="define('VIP_APPSECRET','$old_vip_appsecret');";
            $rs_vip_appsecret="define('VIP_APPSECRET','$vip_appsecret');";
            $str=str_replace($fs_vip_appsecret,$rs_vip_appsecret,$str);
            
            
            //写入配置文件
            file_put_contents('./Public/inc/account.config.php',$str);
            $this->success('更新成功！');
        }else {
            $msg=array(
                'tbk_appid'=>TBK_APPID,//淘宝客AppID
                'tbk_appkey'=>TBK_APPKEY,//淘宝客App key
                'tbk_appsecret'=>TBK_APPSECRET,//淘宝客App secret
                'tbk_pid'=>TBK_PID,//淘宝客PID
                'tbk_adzone_id'=>TBK_ADZONE_ID,//淘宝客广告位ID
                'tbk_relation_pid'=>TBK_RELATION_PID,//淘宝客渠道专用PID
                'wy_appkey'=>WY_APPKEY,//维易淘宝客key
                'adzone_id'=>ADZONE_ID,//广告位ID
                'auth_code'=>AUTH_CODE,//联盟授权码
                'pdd_client_id'=>PDD_CLIENT_ID,//拼多多client_id
                'pdd_client_secret'=>PDD_CLIENT_SECRET,//拼多多client_secret
                'pdd_pid'=>PDD_PID,//拼多多推广位pid
                'jpush_key'=>JPUSH_KEY,//极光推送key
                'jpush_secret'=>JPUSH_SECRET,//极光推送secret
                'alipay_appid'=>ALIPAY_APPID,//支付宝appid
                'alipay_private_key'=>ALIPAY_PRIVATE_KEY,//支付宝私钥
                'alipay_public_key'=>ALIPAY_PUBLIC_KEY,//支付宝公钥
                'jd_unionid' =>JD_UNIONID,
                'jd_auth_key' =>JD_AUTH_KEY,
                'android_appkey' =>ANDROID_APPKEY,
                'android_appsecret' =>ANDROID_APPSECRET,
                'ios_appkey' =>IOS_APPKEY,
                'ios_appsecret' =>IOS_APPSECRET,
                'tk_pid' =>json_decode(tb_pid,true),
                'sms_apikey' =>SMS_APIKEY,
                'sms_tpl' =>SMS_TPL,
                'sms_sid' =>SMS_SID,
                'vip_appkey' =>VIP_APPKEY,//唯品会App key
                'vip_appsecret' =>VIP_APPSECRET,//唯品会App secret
            );
           // var_dump($msg);exit;
            $this->assign('msg',$msg);
            
            $this->display();
        }
    }

    //会员升级规则配置
    public function userSet()
    {
        //获取配置文件
        require_once './Public/inc/user.config.php';
        
        if(I('post.')) {
            layout(false);
            //推荐注册增加经验值
            $user_upgrade_register=I('post.user_upgrade_register');
            $old_user_upgrade_register=I('post.old_user_upgrade_register');
            //推荐用户购物增加经验值
            $user_upgrade_buy=I('post.user_upgrade_buy');
            $old_user_upgrade_buy=I('post.old_user_upgrade_buy');
            
            //载入系统配置文件
            $str=file_get_contents('./Public/inc/user.config.php');
            //替换推荐注册增加经验值
            $fs_user_upgrade_register="define('USER_UPGRADE_REGISTER','$old_user_upgrade_register');";
            $rs_user_upgrade_register="define('USER_UPGRADE_REGISTER','$user_upgrade_register');";
            $str=str_replace($fs_user_upgrade_register,$rs_user_upgrade_register,$str);
            //替换推荐用户购物增加经验值
            $fs_user_upgrade_buy="define('USER_UPGRADE_BUY','$old_user_upgrade_buy');";
            $rs_user_upgrade_buy="define('USER_UPGRADE_BUY','$user_upgrade_buy');";
            $str=str_replace($fs_user_upgrade_buy,$rs_user_upgrade_buy,$str);
            
            
            //写入配置文件
            file_put_contents('./Public/inc/user.config.php',$str);
            $this->success('更新成功！');
        }else {
            $msg=array(
                'user_upgrade_register'=>USER_UPGRADE_REGISTER,//推荐注册增加经验值
                'user_upgrade_buy'=>USER_UPGRADE_BUY,//推荐用户购物增加经验值
            );
            $this->assign('msg',$msg);
            
            $this->display();
        }
    }
    
    //提现设置
    public function drawSet()
    {
        if($_POST) {
            layout(false);
            //提现方式
            $draw_method=I('post.draw_method');
            $old_draw_method=I('post.old_draw_method');
            //自动转账金额
            $draw_auto_money=I('post.draw_auto_money');
            $old_draw_auto_money=I('post.old_draw_auto_money');
            //自动转账-大额提现后台审核是否自动转账
            $draw_auto_type=I('post.draw_auto_type');
            $old_draw_auto_type=I('post.old_draw_auto_type');
            //可提现起始日期
            $draw_start_date=I('post.draw_start_date');
            $old_draw_start_date=I('post.old_draw_start_date');
            //可提现截止日期
            $draw_end_date=I('post.draw_end_date');
            $old_draw_end_date=I('post.old_draw_end_date');
            //最低提现金额
            $draw_limit_money=I('post.draw_limit_money');
            $old_draw_limit_money=I('post.old_draw_limit_money');
            //选择自动提现必须填写审核金额
            if($draw_method=='2') {
                if(empty($draw_auto_money)) {
                    $this->error('请输入自动提现金额!');
                }
            }
            
            //载入系统配置文件
            $str=file_get_contents('./Public/inc/draw.config.php');
            //替换提现方式
            $fs_draw_method="define('DRAW_METHOD','$old_draw_method');";
            $rs_draw_method="define('DRAW_METHOD','$draw_method');";
            $str=str_replace($fs_draw_method,$rs_draw_method,$str);
            //替换自动转账金额
            $fs_draw_auto_money="define('DRAW_AUTO_MONEY','$old_draw_auto_money');";
            $rs_draw_auto_money="define('DRAW_AUTO_MONEY','$draw_auto_money');";
            $str=str_replace($fs_draw_auto_money,$rs_draw_auto_money,$str);
            //替换自动转账-大额提现后台审核是否自动转账
            $fs_draw_auto_type="define('DRAW_AUTO_TYPE','$old_draw_auto_type');";
            $rs_draw_auto_type="define('DRAW_AUTO_TYPE','$draw_auto_type');";
            $str=str_replace($fs_draw_auto_type,$rs_draw_auto_type,$str);
            //替换可提现起始日期
            $fs_draw_start_date="define('DRAW_START_DATE','$old_draw_start_date');";
            $rs_draw_start_date="define('DRAW_START_DATE','$draw_start_date');";
            $str=str_replace($fs_draw_start_date,$rs_draw_start_date,$str);
            //替换可提现截止日期
            $fs_draw_end_date="define('DRAW_END_DATE','$old_draw_end_date');";
            $rs_draw_end_date="define('DRAW_END_DATE','$draw_end_date');";
            $str=str_replace($fs_draw_end_date,$rs_draw_end_date,$str);
            //替换最低提现金额
            $fs_draw_limit_money="define('DRAW_LIMIT_MONEY','$old_draw_limit_money');";
            $rs_draw_limit_money="define('DRAW_LIMIT_MONEY','$draw_limit_money');";
            $str=str_replace($fs_draw_limit_money,$rs_draw_limit_money,$str);
            
            //写入配置文件
            file_put_contents('./Public/inc/draw.config.php',$str);
            $this->success('保存成功！');
        }else {
            //获取配置文件
            require_once './Public/inc/draw.config.php';
            
            $msg=array(
                'draw_method'=>DRAW_METHOD,//提现方式
                'draw_auto_money'=>DRAW_AUTO_MONEY,//自动转账金额
                'draw_auto_type'=>DRAW_AUTO_TYPE,//自动转账-大额提现后台审核是否自动转账
                'draw_start_date'=>DRAW_START_DATE,//可提现起始日期
                'draw_end_date'=>DRAW_END_DATE,//可提现截止日期
                'draw_limit_money'=>DRAW_LIMIT_MONEY,//最低提现金额
            );
            $this->assign('msg',$msg);
            
            $this->display();
        }
    }
    
    //清理缓存
    public function cleancache()
    {
    	$dirName=APP_PATH.'Runtime';
    	delDirAndFile($dirName);
    	layout(false);
    	$this->success('清理缓存成功！');
    }
    
    //清理垃圾文件
    public function clearrubbish()
    {
    	$dirName='./Public/Upload/ueditor_temp';
    	delDirAndFile($dirName);
    	layout(false);
    	$this->success('清理垃圾文件成功！');
    }
    
    //中文分词
    public function scws($title)
    {
    	Vendor('scws.pscws4');
    	$pscws = new \PSCWS4();
    	$pscws->set_dict(VENDOR_PATH.'scws/lib/dict.utf8.xdb');
    	$pscws->set_rule(VENDOR_PATH.'scws/lib/rules.utf8.ini');
    	$pscws->set_ignore(true);
    	$pscws->send_text(trim($title));
    	$words = $pscws->get_tops(5);
    	$tags = array();
    	foreach ($words as $val) {
    		$tags[] = $val['word'];
    	}
    	$pscws->close();
    	$keywords=implode(',',$tags);
    	echo $keywords;
    }
    
    //生成sitemap
    public function sitemap()
    {
    	
    }
}