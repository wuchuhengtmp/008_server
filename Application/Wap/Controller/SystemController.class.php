<?php
namespace Wap\Controller;
use Think\Controller;
class SystemController extends Controller 
{
    //文章详情页
    public function article1($id)
    {
        $Article=new \Common\Model\ArticleModel();
        $msg=$Article->getArticleMsg($id);
        $this->assign('msg',$msg);
        $this->display();
    }
    
	//单图文素材文章显示
    public function article($id)
    {
    	$msg=D('WechatNews')->getNewsMsg($id);
    	$this->assign('msg',$msg);
        $this->display();
    }
    
    //微信网页通知页面
    public function notice()
    {
    	$this->display();
    }
}