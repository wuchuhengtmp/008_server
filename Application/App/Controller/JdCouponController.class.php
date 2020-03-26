<?php
namespace App\Controller;
use App\Common\Controller\AuthController;
class JdCouponController extends AuthController{
    protected $authkey = JD_AUTH_KEY;//京东授权key
    protected $unionid = JD_UNIONID;//京东联盟id
    public function index(){
        $this->show('<style type="text/css">*{ padding: 0; margin: 0; } div{ padding: 4px 48px;} body{ background: #fff; font-family: "微软雅黑"; color: #333;font-size:24px} h1{ font-size: 100px; font-weight: normal; margin-bottom: 12px; } p{ line-height: 1.8em; font-size: 36px } a,a:hover{color:blue;}</style><div style="padding: 24px 48px;"> <h1>:)</h1><p>欢迎使用 <b>ThinkPHP</b>！</p><br/>版本 V{$Think.version}</div><script type="text/javascript" src="http://ad.topthink.com/Public/static/client.js"></script><thinkad id="ad_55e75dfae343f5a1"></thinkad><script type="text/javascript" src="http://tajs.qq.com/stats?sId=9347272" charset="UTF-8"></script>','utf-8');
    }
    public function getUrl(){
    	
		$token=trim(I('get.token'));
		$User=new \Common\Model\UserModel();
		$res_token=$User->checkToken($token);
		if($res_token['code']!=0)
		{
			//用户身份不合法
			$res=$res_token;
			echo json_encode ($res,JSON_UNESCAPED_UNICODE);
			exit;			
		}else {  
			$uid=$res_token['uid'];
			$userinfo = $User->getUserMsg($uid);
			
			if(empty($userinfo['jd_pid'])){
				$jd_pid = $this->create_pid($uid);
			}
			else
			{
				$jd_pid = $userinfo['jd_pid'];
			}
		}
		if(empty($jd_pid))
		{
			$res=array(
					'code'=>$this->ERROR_CODE_COMMON['DB_ERROR'],
					'msg'=>$this->ERROR_CODE_COMMON_ZH[$this->ERROR_CODE_COMMON['DB_ERROR']]
			);
			echo json_encode ($res,JSON_UNESCAPED_UNICODE);
			exit;
		}
    	$unionid = I('get.unionId');
    	$pid = $jd_pid;
    	$skuid = I('get.skuid');
    	$url = 'http://api.josapi.net/goodsquery?skuIds='.$skuid;//30043288398
    	$q1 = file_get_contents($url);
		
    	$res = json_decode($q1,true);
		if($res['error']==0){
			$data = $res['data'][0];
			if(empty($data['couponInfo']['couponList']))
			{
				echo json_encode(['error'=>"1",'msg'=>'优惠券不存在!']);
				exit;
			}
			
			$link = $data['couponInfo']['couponList'][0]['link'];
		
			$url2 = 'http://api.josapi.net/prombyuid?unionId='.$this->unionid.'&materialId=item.jd.com/'.$skuid.'.html&positionId='.$pid.'&couponUrl='.urlencode($link);
			$q2 = file_get_contents($url2);
			//$result = json_decode($q2,true);
			echo $q2;
			exit;
			//var_dump($data);
		}
    }
    public function create_pid($uid){
    	$keyurl = "http://api.josapi.net/createpid?unionId={$this->unionid}&key={$this->authkey}&type=4&spaceNameList=pid_for_{$uid}";
    	$pidinfo = file_get_contents($keyurl);
    	$result = json_decode($pidinfo,true);
    	$jd_pid = "";
    	if($result['error']=="0")
    	{
    		$jd_idx = "pid_for_{$uid}";
    		$jd_pid = $result['data']['resultList'][$jd_idx];
    		$User=new \Common\Model\UserModel();
    		$User->where("uid=".$uid)->save(['jd_pid'=>$jd_pid]);
    	}
		return $jd_pid;
    }
    
}