<?php
/**
 * 论坛管理-帖子回复管理
 */
namespace Admin\Controller;
use Admin\Common\Controller\AuthController;
class BbsArticleReplyController extends AuthController
{
    public function index($comment_id)
    {
    	//获取评论列表
    	$BbsArticleReply=new \Common\Model\BbsArticleReplyModel();
    	$replylist = $BbsArticleReply->getList($comment_id);
    	$this->assign('replylist',$replylist);
        $this->display();
    }
    
    //删除回复
    public function del($id)
    {
    	//删除操作
    	$BbsArticleReply=new \Common\Model\BbsArticleReplyModel();
    	$res=$BbsArticleReply->del($id);
    	if($res)
    	{
    		echo '1';
    	}else {
    		echo '0';
    	}
    }
         
    //修改回复显示状态
    public function changeshow($id,$status)
    {
    	$data=array(
    			'is_show'=>$status
    	);
    	$BbsArticleReply=new \Common\Model\BbsArticleReplyModel();
    	$res=$BbsArticleReply->where("id=$id")->save($data);
    	if($res===false)
    	{
    		echo '0';
    	}else {
    		echo '1';
    	}
    }
}