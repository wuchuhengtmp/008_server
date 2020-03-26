<?php
/**
 * 留言分类管理
 */
namespace Admin\Controller;
use Admin\Common\Controller\AuthController;
class MessageCatController extends AuthController
{
    public function index()
    {
    	//获取留言分类列表
    	$MessageCat=new \Common\Model\MessageCatModel();
    	$list=$MessageCat->getMessageCatList();
    	$this->assign('list',$list);
        $this->display();
    }
    
    //添加分类
    public function add()
    {
    	if($_POST) {
    		layout(false);
    		$data=array(
    		    'title'=>I('post.title'),
    			'createtime'=>date('Y-m-d H:i:s')
    		);
    		$MessageCat=new \Common\Model\MessageCatModel();
    		if(!$MessageCat->create($data)) {
    			// 验证不通过
    			$this->error($MessageCat->getError());
    		}else {
    			// 验证成功
    			$res_add=$MessageCat->add($data);
    			if($res_add) {
    				$this->success('添加成功！');
    			}else {
    				$this->error('操作失败！');
    			}
    		}
    	}
    }
    
    //编辑分类
    public function edit($id)
    {
    	//根据ID获取分类信息
    	$MessageCat=new \Common\Model\MessageCatModel();
    	$msg=$MessageCat->getCatMsg($id);
    	
    	if($_POST) {
    		layout(false);
    		$data=array(
    				'title'=>I('post.title'),
    				'createtime'=>date('Y-m-d H:i:s')
    		);
    		if(!$MessageCat->create($data)) {
    		    // 验证不通过
    			$this->error($MessageCat->getError());
    		}else {
    			// 验证成功
    			$res_edit=$MessageCat->where("id=$id")->save($data);
    			if($res_edit) {
    				$this->success('编辑成功！',U('index'));
    			}else {
    				$this->error('操作失败！');
    			}
    		}
    	}else {
    	    $this->assign('msg',$msg);
    	    
    		$this->display();
    	}
    }
    
    //删除分类
    public function del($id)
    {
    	$MessageCat=new \Common\Model\MessageCatModel();
    	$res_del=$MessageCat->where("id=$id")->delete();
    	if($res_del!==false) {
    		//删除分类下的所有留言
    		$message=new \Common\Model\MessageModel();
    		$res2=$message->where("cat_id=$id")->select();
    		if(!empty($res2)) {
    			$num=count($res2);
    			for($i=0;$i<$num;$i++) {
    				$message_id=$res2[$i]['id'];
    				$res3=$message->del($message_id);
    				if($res3) {
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
    		}else {
    			echo '1';
    		}
    	}else {
    		echo '0';
    	}
    }
}