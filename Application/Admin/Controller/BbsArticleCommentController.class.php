<?php
/**
 * 论坛管理-帖子评论管理
 */
namespace Admin\Controller;
use Admin\Common\Controller\AuthController;
class BbsArticleCommentController extends AuthController
{
    public function index($article_id)
    {
    	//获取文章信息
    	$bbsarticle=D('BbsArticle');
    	$article_msg=$bbsarticle->getArticleMsg($article_id);
    	$this->assign('article_msg',$article_msg);
    	//获取评论列表
    	$BbsArticleComment=D('BbsArticleComment');
    	$count=$BbsArticleComment->where('1')->count();
    	$per = 15;
    	if($_GET['p'])
    	{
    		$p=$_GET['p'];
    	}else {
    		$p=1;
    	}
    	// 分页显示输出
    	$Page=new \Common\Model\PageModel();
    	$show= $Page->show($count,$per);
    	$this->assign('page',$show);
    	
    	$commentlist = $BbsArticleComment->where('1')->page($p.','.$per)->order('id asc')->select();
    	$this->assign('commentlist',$commentlist);
        $this->display();
    }
    
    //删除评论
    public function del($id)
    {
    	//删除操作
    	$BbsArticleComment=D('BbsArticleComment');
    	$res=$BbsArticleComment->del($id);
    	if($res)
    	{
    		echo '1';
    	}else {
    		echo '0';
    	}
    }
         
    //修改评论显示状态
    public function changeshow($id,$status)
    {
    	$data=array(
    			'is_show'=>$status
    	);
    	$BbsArticleComment=D('BbsArticleComment');
    	$res=$BbsArticleComment->where("id=$id")->save($data);
    	if($res===false)
    	{
    		echo '0';
    	}else {
    		echo '1';
    	}
    }
}