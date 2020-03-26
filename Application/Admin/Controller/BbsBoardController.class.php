<?php
/**
 * 论坛管理-版块管理
 */
namespace Admin\Controller;
use Admin\Common\Controller\AuthController;
class BbsBoardController extends AuthController
{
    public function index()
    {
    	//获取版块分类列表
    	$BbsBoard=new \Common\Model\BbsBoardModel();
    	$list=$BbsBoard->getList();
    	$this->assign('list',$list);
    	
        $this->display();
    }
    
    //添加版块
    Public function add()
    {
    	//获取版块分类列表
    	$BbsBoard=new \Common\Model\BbsBoardModel();
    	$boardlist=$BbsBoard->getList();
    	$this->assign('boardlist',$boardlist);
    	
    	if(I('post.'))
    	{
    		layout(false);
    		//上传文件
    		if(!empty($_FILES['img']['name']))
    		{
    			$config = array(
    					'mimes'         =>  array(), //允许上传的文件MiMe类型
    					'maxSize'       =>  1024*1024*4, //上传的文件大小限制 (0-不做限制)
    					'exts'          =>  array('jpg', 'gif', 'png', 'jpeg'), //允许上传的文件后缀
    					'rootPath'      =>  './Public/Upload/BbsBoard/', //保存根路径
    					'savePath'      =>  '', //保存路径
    					'saveExt'       =>  '', //文件保存后缀，空则使用原后缀
    			);
    			$upload = new \Think\Upload($config);
    			// 上传单个文件
    			$info = $upload->uploadOne($_FILES['img']);
    			if(!$info) {
    				// 上传错误提示错误信息
    				$this->error($upload->getError());
    			}else{
    				// 上传成功
    				// 文件完成路径
    				$filepath=$config['rootPath'].$info['savepath'].$info['savename'];
    				$img=substr($filepath,1);
    			}
    		}
    		//保存到数据库
    		$data=array(
    				'board_name'=>trim(I('post.board_name')),
    				'sort'=>I('post.sort'),
    				'pid'=>I('post.pid'),
    				'is_show'=>I('post.is_show'),
    				'keyword'=>I('post.keyword'),
    				'description'=>I('post.description'),
    				'createtime'=>date('Y-m-d H:i:s'),
    				'img'=>$img
    		);
    		$BbsBoard=new \Common\Model\BbsBoardModel();
    		if(!$BbsBoard->create($data))
    		{
    			// 如果创建失败 表示验证没有通过 输出错误提示信息
    			// 删除图片
    			@unlink($filepath);
    			$this->error($BbsBoard->getError());
    		}else {
    			// 验证成功
    			$res=$BbsBoard->add($data);
    			if($res!==false)
    			{
    				$this->success('新增论坛版块成功！',U('index'));
    			}else {
    				//删除图片
    				@unlink($filepath);
    				$this->error('操作失败！');
    			}
    		}
    	}else {
    		$this->display();
    	}
    }
    
    //编辑版块
    Public function edit($board_id)
    {
    	//获取版块分类列表
    	$BbsBoard=new \Common\Model\BbsBoardModel();
    	$boardlist=$BbsBoard->getList();
    	$this->assign('boardlist',$boardlist);
    	
    	//根据版块ID获取子版块
    	$sublist=$BbsBoard->getSubList($board_id);
    	$subnum=count($sublist);
    	for($i=0;$i<$subnum;$i++)
    	{
    		$subarr[]=$sublist[$i]['board_id'];
    	}
    	$this->assign('subarr',$subarr);
    	//获取版块信息
    	$msg=$BbsBoard->getBoardMsg($board_id);
    	$this->assign('msg',$msg);
    	if(I('post.'))
    	{
    		layout(false);
    		//上传文件
    		if(!empty($_FILES['img']['name']))
    		{
    			$config = array(
    					'mimes'         =>  array(), //允许上传的文件MiMe类型
    					'maxSize'       =>  2000000, //上传的文件大小限制 (0-不做限制)
    					'exts'          =>  array('jpg', 'gif', 'png', 'jpeg'), //允许上传的文件后缀
    					'rootPath'      =>  './Public/Upload/BbsBoard/', //保存根路径
    					'savePath'      =>  '', //保存路径
    					'saveExt'       =>  '', //文件保存后缀，空则使用原后缀
    			);
    			$upload = new \Think\Upload($config);
    			// 上传单个文件
    			$info = $upload->uploadOne($_FILES['img']);
    			if(!$info) {
    				// 上传错误提示错误信息
    				$this->error($upload->getError());
    			}else{
    				// 上传成功
    				// 文件完成路径
    				$filepath=$config['rootPath'].$info['savepath'].$info['savename'];
    				$img=substr($filepath,1);
    			}
    		}else {
    			$img=I('post.oldimg');
    		}
    		//保存到数据库
    		$data=array(
    				'board_name'=>trim(I('post.board_name')),
    				'sort'=>I('post.sort'),
    				'pid'=>I('post.pid'),
    				'is_show'=>I('post.is_show'),
    				'keyword'=>I('post.keyword'),
    				'description'=>I('post.description'),
    				'createtime'=>date('Y-m-d H:i:s'),
    				'img'=>$img
    		);
    		if(!$BbsBoard->create($data))
    		{
    			// 如果创建失败 表示验证没有通过 输出错误提示信息
    			// 删除图片
    			@unlink($filepath);
    			$this->error($BbsBoard->getError());
    		}else {
    			// 验证成功
    			$res=$BbsBoard->where("board_id='$board_id'")->save($data);
    			if($res!==false)
    			{
    				// 修改成功
    				// 原图片存在，并且上传了新图片的情况下，删除原图片
    				if(I('post.oldimg') and $img!=I('post.oldimg'))
    				{
    					$oldimg='.'.I('post.oldimg');
    					unlink($oldimg);
    				}
    				$this->success('修改版块成功！',U('index'));
    			}else {
    				//删除图片
    				@unlink($filepath);
    				$this->error('操作失败！');
    			}
    		}
    	}else {
    		$this->display();
    	}
    }
    
    //删除版块
    public function del($board_id)
    {
    	$BbsBoard=new \Common\Model\BbsBoardModel();
    	/*
    	 * 先判断版块下是否有二级版块
    	 * 存在不准删除，需要先删除二级版块
    	 *  */
    	$res_p=$BbsBoard->where("pid=$board_id")->field('board_id')->find();
    	if($res_p['board_id']!='')
    	{
    		echo '2';
    		exit();
    	}
    	/*
    	 * 判断版块下是否存在帖子
    	 * 存在不准删除，需要先删除版块
    	 */
    	$BbsArticle=new \Common\Model\BbsArticleModel();
    	$a_num=$BbsArticle->where("board_id='$board_id'")->count();
    	if($a_num>0)
    	{
    		echo '3';
    		exit();
    	}
    	
    	$res1=$BbsBoard->where("board_id='$board_id'")->field('img')->find();
    	$board_img=$res1['img'];
    	//删除版块
    	$res=$BbsBoard->where("board_id='$board_id'")->delete();
    	if($res)
    	{
    		//删除分类图片
    		if(!empty($board_img))
    		{
    			$board_img='.'.$board_img;
    			unlink($board_img);
    		}
    		echo '1';
    	}else {
    		echo '0';
    	}
    }
    
    //修改版块状态
    public function changestatus($board_id,$status)
    {
    	$data=array(
    			'is_show'=>$status
    	);
    	$BbsBoard=new \Common\Model\BbsBoardModel();
    	if(!$BbsBoard->create($data))
    	{
    		// 如果创建失败 表示验证没有通过 输出错误提示信息
    		// $this->error($BbsBoard->getError());
    		echo '0';
    	}else {
    		// 验证成功
    		$res=$BbsBoard->where("board_id='$board_id'")->save($data);
    		if($res===false)
    		{
    			echo '0';
    		}else {
    			echo '1';
    		}
    	}
    }
    
    //批量修改排序
    public function changesort()
    {
    	$sort_array=I('post.sort');
    	$ids = implode(',', array_keys($sort_array));
    	$sql = "UPDATE __PREFIX__bbs_board SET sort = CASE board_id ";
    	foreach ($sort_array as $id => $sort) {
    		$sql .= sprintf("WHEN %d THEN %d ", $id, $sort);
    	}
    	$sql.= "END WHERE board_id IN ($ids)";
    	$res = M()->execute($sql);
    	layout(false);
    	if($res===false)
    	{
    		$this->error('操作失败!');
    	}else {
    		$this->success('排序成功!',U('index'));
    	}
    }
    
    //删除原版块图片
    public function deloldimg($board_id)
    {
    	$BbsBoard=new \Common\Model\BbsBoardModel();
    	$res=$BbsBoard->where("board_id=$board_id")->find();
    	if($res===false)
    	{
    		echo '0';
    	}else {
    		$oldimg=$res['img'];
    		//修改img为空
    		$data=array(
    				'img'=>''
    		);
    		$res2=$BbsBoard->where("board_id=$board_id")->save($data);
    		if($res2)
    		{
    			$oldimg='.'.$oldimg;
    			unlink($oldimg);
    			echo '1';
    		}else {
    			echo '0';
    		}
    	}
    }
}