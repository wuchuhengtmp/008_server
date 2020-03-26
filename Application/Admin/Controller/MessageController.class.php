<?php
/**
 * 留言管理
 */
namespace Admin\Controller;
use Admin\Common\Controller\AuthController;
class MessageController extends AuthController
{
    public function index($cat_id)
    {
    	$this->assign('cat_id',$cat_id);
    	//获取分类信息
    	$MessageCat=new \Common\Model\MessageCatModel();
    	$catMsg=$MessageCat->getCatMsg($cat_id);
    	$this->assign('cat_title',$catMsg['title']);
    	
    	//根据分类ID获取链接列表
    	$where="cat_id='$cat_id'";
    	$Message=new \Common\Model\MessageModel();
    	
    	$count=$Message->where($where)->count();
    	$per = 15;
    	if($_GET['p']) {
    		$p=$_GET['p'];
    	}else {
    		$p=1;
    	}
    	$Page=new \Common\Model\PageModel();
    	$show= $Page->show($count,$per);// 分页显示输出
    	$this->assign('page',$show);
    	
    	$mlist = $Message->where($where)->page($p.','.$per)->order('id desc')->select();
    	$this->assign('mlist',$mlist);
    	
        $this->display();
    }
    
    //编辑留言
    public function edit($id,$cat_id)
    {
    	$this->assign('id',$id);
    	$this->assign('cat_id',$cat_id);
    	//获取留言分类列表
    	$MessageCat=new \Common\Model\MessageCatModel();
    	$list=$MessageCat->getMessageCatList();
    	
    	//根据ID获取留言信息
    	$Message=new \Common\Model\MessageModel();
    	$msg=$Message->getMessageMsg($id);
    	
    	if($_POST) {
    		layout(false);
    		$data=array(
    				'cat_id'=>$cat_id,
    				'content'=>I('post.content'),
    				'linkman'=>I('post.linkman'),
    				'phone'=>I('post.phone'),
    				'email'=>I('post.email'),
    				'ip'=>I('post.ip'),
    				'is_show'=>I('post.is_show')
    		);
    		if(!$Message->create($data)) {
    			// 验证不通过
    		    $this->error($Message->getError());
    		}else {
    			// 验证成功
    		    $res_edit=$Message->where("id='$id'")->save($data);
    		    if($res_edit!==false) {
    				$this->success('修改留言成功！');
    			}else {
    				$this->error('操作失败！');
    			}
    		}
    	}else {
    	    $this->assign('list',$list);
    	    $this->assign('msg',$msg);
    	    
    		$this->display();
    	}
    }
    
    //站长回复留言
    public function reply($id,$cat_id)
    {
    	$this->assign('id',$id);
    	$this->assign('cat_id',$cat_id);
    	//获取留言分类列表
    	$MessageCat=new \Common\Model\MessageCatModel();
    	$list=$MessageCat->getMessageCatList();
    	$this->assign('list',$list);
    	//根据ID获取留言信息
    	$message=new \Common\Model\MessageModel();
    	$msg=$message->getMessageMsg($id);
    	$this->assign('msg',$msg);
    	//根据留言ID获取站长回复内容
    	$MessageReply=new \Common\Model\MessageReplyModel();
    	$res_r=$MessageReply->where("message_id=$id")->find();
    	if($res_r['id']!='')
    	{
    		$this->assign('reply_content',$res_r['content']);
    		$this->assign('ac','save');
    	}else {
    		$this->assign('ac','add');
    	}
    	if($_POST) {
    		layout(false);
    		$ac=I('post.ac');
    		$content=I('post.content');
    		if($ac=='add')
    		{
    			$data=array(
    					'message_id'=>$id,
    					'content'=>$content,
    					'reply_time'=>date('Y-m-d H:i:s')
    			);
    			if(!$MessageReply->create($data))
    			{
    				// 如果创建失败 表示验证没有通过 输出错误提示信息
    				$this->error($MessageReply->getError());
    			}else {
    				// 验证成功
    				$res=$MessageReply->add($data);
    				if($res!==false)
    				{
    					$this->success('回复留言成功！');
    				}else {
    					$this->error('操作失败！');
    				}
    			}
    		}else {
    			$data=array(
    					'content'=>$content,
    					'reply_time'=>date('Y-m-d H:i:s')
    			);
    			if(!$MessageReply->create($data))
    			{
    				// 如果创建失败 表示验证没有通过 输出错误提示信息
    				$this->error($MessageReply->getError());
    			}else {
    				// 验证成功
    				$res=$MessageReply->where("message_id=$id")->save($data);
    				if($res)
    				{
    					$this->success('回复留言成功！');
    				}else {
    					$this->error('操作失败！');
    				}
    			}
    		}
    	}else {
    		$this->display();
    	}
    }
    
    //删除留言
    public function del($id)
    {
    	$message=new \Common\Model\MessageModel();
    	$res_del=$message->del($id);
    	if($res_del) {
    		echo '1';
    	}else {
    		echo '0';
    	}
    }
    
    //批量删除留言
    public function batchdel($all_id)
    {
    	$all_id=substr($all_id,0,-1);
    	$id_arr=explode(',',$all_id);
    	$num=count($id_arr);
    	$message=new \Common\Model\MessageModel();
    	for($i=0;$i<$num;$i++) {
    		$id=$id_arr[$i];
    		$res=$message->del($id);
    		if($res) {
    			$a.='a';
    		}
    	}
    	$a.='true';
    	$str=str_repeat('a',$num).'true';
    	if($str==$a) {
    		echo '1';
    	}else {
    		echo '0';
    	}
    }
    
    //修改留言显示状态
    public function changestatus($id,$status)
    {
    	$data=array(
    			'is_show'=>$status
    	);
    	$message=new \Common\Model\MessageModel();
    	if(!$message->create($data)) {
    		// 验证不通过
    		echo '0';
    	}else {
    		// 验证成功
    		$res_save=$message->where("id=$id")->save($data);
    		if($res_save===false) {
    			echo '0';
    		}else {
    			echo '1';
    		}
    	}
    }
}