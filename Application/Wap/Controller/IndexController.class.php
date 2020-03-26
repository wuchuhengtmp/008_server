<?php
namespace Wap\Controller;
use Think\Controller;
class IndexController extends Controller 
{
    public function index()
    {
    	echo 'ok';
    }
    
    //淘宝商品分享页面
    public function share($num_iid,$uid)
    {
    	layout(false);
    	Vendor('tbk.tbk','','.class.php');
    	$tbk=new \tbk();
    	//$ip=getIP();
    	$ip='';
    	//获取用户所绑定的推广位
    	$pid='';
    	$relationId='';
    	$User=new \Common\Model\UserModel();
    	$userMsg=$User->getUserMsg($uid);
    	$this->assign('auth_code',$userMsg['auth_code']);
    	
    	//如果是微信群主，使用微信群主推广位pid
    	if($userMsg['tb_pid_master'])
    	{
    		$pid=$userMsg['tb_pid_master'];
    	}else {
    		$pid=$userMsg['tb_pid'];//淘宝推广位
    	}
    	//渠道ID
    	if($userMsg['tb_rid']){
    	    $relationId=$userMsg['tb_rid'];
    	}
    	$res_tbk=$tbk->getItemDetail($num_iid,'2',$ip,$pid,$relationId);
    	if($res_tbk['code']==0)
    	{
    		$msg=$res_tbk['data'];
    		//生成淘口令
    		$data_ext=array(
    				'user_id'=>$uid
    		);
    		$ext=json_encode($data_ext);
    		$text=$msg['title'];//商品名称
    		if(strpos( $msg['coupon_click_url'],'http')===false)
    		{
    			$url='https:'.$msg['coupon_click_url'];//商品链接
    		}else {
    			$url=$msg['coupon_click_url'];//商品链接
    		}
    		if($msg['coupon_click_url_r']) {
    		    $url=$msg['coupon_click_url_r'];//商品渠道链接
    		}
    		$logo=$msg['pict_url'];//商品图片
    		//淘宝客淘口令
    		$res_tkl=$tbk->createTpwd($user_id='',$text,$url,$logo,$ext);
    		if($res_tkl['code']==0)
    		{
    			$this->assign('tkl',$res_tkl['data']);
    			$this->assign('msg',$msg);
    			$this->display();
    		}else {
    			$this->error($res_tkl['msg']);
    		}
    	}else {
    		$this->error($res_tbk['msg']);
    	}
    }
    
    //淘宝年货节分享页面
    public function share2($uid)
    {
    	layout(false);
    	$pid='mm_21742772_104250451_20294800352';
    	//获取微信群主推广位
    	$User=new \Common\Model\UserModel();
    	$userMsg=$User->getUserMsg($uid);
    	if($userMsg['tb_pid_master'])
    	{
    		$pid=$userMsg['tb_pid_master'];
    	}
    	//年货节链接地址-2019天猫年货合家-主会场（带超级红包）
    	$url='https://s.click.taobao.com/t?e=m%3D2%26s%3DgXFSl9omEmccQipKwQzePCperVdZeJviK7Vc7tFgwiFRAdhuF14FMS6kS8yTp6kRAYPhC%2B%2FgaE8zuTGl041QAqD5kstin37fU2%2FAEepMzfHGq4iT%2BDv5w0JUXBtwD3jmeK6AyaN9cNFkkwrO4VzIZmsFOLhERZOUamkFHdIFxeOp%2B9oXPRnHb8s%2Fhc73tO6KVYo%2BqyT%2FBa1NrKwvDJNPXsxESlRLQlPP&pid='.$pid;
    	//生成淘口令
    	$data_ext=array(
    			'user_id'=>$uid
    	);
    	$ext=json_encode($data_ext);
    	$text='2019天猫年货合家-主会场（带超级红包）';
    	$logo=WEB_URL.'/Public/static/wap/images/tb1.jpg';
    	//淘宝客淘口令
    	Vendor('tbk.tbk','','.class.php');
    	$tbk=new \tbk();
    	$res_tbk=$tbk->createTpwd($user_id='',$text,$url,$logo,$ext);
    	if($res_tbk['code']==0)
    	{
    		$tkl=$res_tbk['data'];
    		$this->assign('tkl',$tkl);
    		$this->assign('url',$url);
    		$this->display();
    	}else {
    		$this->error($res_tbk['msg']);
    	}
    }
    
    //下载
    public function down()
    {
    	layout(false);
    	$this->assign('web_title','APP下载');
    	$this->assign('web_keywords','');
    	$this->assign('web_description','');
    
    	$this->assign('inviteCode',$_REQUEST['inviteCode']);
    	$this->display();
    }
    
    //视频播放
    public function video($id=1)
    {
    	layout(false);
    	switch ($id)
    	{
    		//汇客熊安装使用教程
    		case '1':
    			$file='install.mp4';
    			$title='汇客熊安装使用教程';
    			break;
    		//汇客熊网购使用教程
    		case '2':
    			$file='buy.mp4';
    			$title='汇客熊网购使用教程';
    			break;
    		default:
    			$file='install.mp4';
    			$title='汇客熊安装使用教程';
    			break;
    	}
    	$this->assign('file',$file);
    	
    	$this->assign('web_title',$title);
    	$this->assign('web_keywords','');
    	$this->assign('web_description','');
    	$this->display();
    }
}