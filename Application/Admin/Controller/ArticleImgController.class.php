<?php
/**
 * 内容管理-文章图片管理
 */
namespace Admin\Controller;
use Admin\Common\Controller\AuthController;
class ArticleImgController extends AuthController
{
    public function index($article_id)
    {
    	$this->assign('article_id',$article_id);
    	if(I('get.cat_id'))
    	{
    		$cat_id=I('get.cat_id');
    		$this->assign('cat_id',$cat_id);
    	}
    	//获取文章信息
    	$Article=new \Common\Model\ArticleModel();
    	$artMsg=$Article->getArticleMsg($article_id);
    	$this->assign('article_title',$artMsg['title']);
    	//根据文章ID获取图片列表
    	$ArticleImg=new \Common\Model\ArticleImgModel();
    	$list=$ArticleImg->getImgList($article_id);
    	$this->assign('list',$list);
        $this->display();
    }
    
    //添加图片
    public function add($article_id)
    {
    	$this->assign('article_id',$article_id);
    	if(I('get.cat_id'))
    	{
    		$cat_id=I('get.cat_id');
    		$this->assign('cat_id',$cat_id);
    	}
    	//获取文章信息
    	$Article=new \Common\Model\ArticleModel();
    	$artMsg=$Article->getArticleMsg($article_id);
    	$this->assign('article_title',$artMsg['title']);
    	if(I('post.'))
    	{
    		layout(false);
    		//上传文件
    		if(!empty($_FILES['img']['name']))
    		{
    			$config = array(
    					'mimes'         =>  array(), //允许上传的文件MiMe类型
    					'maxSize'       =>  1024*1024*2, //上传的文件大小限制 (0-不做限制)
    					'exts'          =>  array('jpg', 'gif', 'png', 'jpeg'), //允许上传的文件后缀
    					'rootPath'      =>  './Public/Upload/ArticleImg/', //保存根路径
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
    			//保存到数据库
    			$data=array(
    					'article_id'=>$article_id,
    					'title'=>trim(I('post.title')),
    					'href'=>trim(I('post.href')),
    					'sort'=>trim(I('post.sort')),
    					'img'=>$img,
    					'createtime'=>date('Y-m-d H:i:s')
    			);
    			$ArticleImg=new \Common\Model\ArticleImgModel();
    			if(!$ArticleImg->create($data))
    			{
    				// 如果创建失败 表示验证没有通过 输出错误提示信息
    				// 删除图片
    				@unlink($filepath);
    				$this->error($ArticleImg->getError());
    			}else {
    				// 验证成功
    				$res=$ArticleImg->add($data);
    				if($res)
    				{
    					$this->success('新增文章图片成功！',U('index',array('article_id'=>$article_id,'cat_id'=>$cat_id)));
    				}else {
    					//删除图片
    					@unlink($filepath);
    					$this->error('操作失败！');
    				}
    			}
    		}else {
    			$this->error('X请选择上传图片！');
    			$this->display();
    			exit();
    		}
    	}else {
    		$this->display();
    	}
    }
    
    //编辑图片
    public function edit($id)
    {
    	$this->assign('id',$id);
    	if(I('get.cat_id'))
    	{
    		$cat_id=I('get.cat_id');
    		$this->assign('cat_id',$cat_id);
    	}
    	//根据ID获取图片信息
    	$ArticleImg=new \Common\Model\ArticleImgModel();
    	$msg=$ArticleImg->getImgMsg($id);
    	$this->assign('msg',$msg);
    	if(I('post.'))
    	{
    		layout(false);
    		//上传文件
    		if(!empty($_FILES['img']['name']))
    		{
    			$config = array(
    					'mimes'         =>  array(), //允许上传的文件MiMe类型
    					'maxSize'       =>  1024*1024*2, //上传的文件大小限制 (0-不做限制)
    					'exts'          =>  array('jpg', 'gif', 'png', 'jpeg'), //允许上传的文件后缀
    					'rootPath'      =>  './Public/Upload/ArticleImg/', //保存根路径
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
    				'title'=>trim(I('post.title')),
    				'href'=>trim(I('post.href')),
    				'sort'=>trim(I('post.sort')),
    				'img'=>$img,
    				'createtime'=>date('Y-m-d H:i:s')
    		);
    		$ArticleImg=new \Common\Model\ArticleImgModel();
    		if(!$ArticleImg->create($data))
    		{
    			// 如果创建失败 表示验证没有通过 输出错误提示信息
    			// 删除图片
    			@unlink($filepath);
    			$this->error($ArticleImg->getError());
    		}else {
    			// 验证成功
    			$res=$ArticleImg->where("id=$id")->save($data);
    			if($res!==false)
    			{
    				// 修改成功
    				// 原图片存在，并且上传了新图片的情况下，删除原标题图片
    				if(I('post.oldimg') and $img!=I('post.oldimg'))
    				{
    					$oldimg='.'.I('post.oldimg');
    					unlink($oldimg);
    				}
    				$this->success('修改文章图片成功！');
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
    
    //删除图片
    public function del($id)
    {
    	$ArticleImg=new \Common\Model\ArticleImgModel();
    	$res1=$ArticleImg->getImgMsg($id);
    	$img=$res1['img'];
    	$res=$ArticleImg->where("id=$id")->delete();
    	if($res!==false)
    	{
    		//删除图片
    		if(!empty($img))
    		{
    			$img='.'.$img;
    			unlink($img);
    		}
    		echo '1';
    	}else {
    		echo '0';
    	}
    }
    
    //批量删除图片
    public function batchdel($all_id)
    {
    	$all_id=substr($all_id,0,-1);
    	$id_arr=explode(',',$all_id);
    	$num=count($id_arr);
    	$ArticleImg=new \Common\Model\ArticleImgModel();
    	for($i=0;$i<$num;$i++)
    	{
    		$id=$id_arr[$i];
    		$res1=$ArticleImg->getImgMsg($id);
    		$img=$res1['img'];
    		$res=$ArticleImg->where("id=$id")->delete();
    		if($res)
    		{
    			//删除图片
    			if(!empty($img))
    			{
    				$img='.'.$img;
    				unlink($img);
    			}
    			$a.='a';
    		}
    	}
    	$a.='true';
    	$str=str_repeat('a',$num).'true';
    	if($str==$a)
    	{
    		echo '1';
    	}else {
    		echo '0';
    	}
    }
    
    //批量修改排序
    public function changesort()
    {
    	$sort_array=I('post.sort');
    	$ids = implode(',', array_keys($sort_array));
    	$sql = "UPDATE __PREFIX__article_img SET sort = CASE id ";
    	foreach ($sort_array as $id => $sort) {
    		$sql .= sprintf("WHEN %d THEN %d ", $id, $sort);
    	}
    	$sql.= "END WHERE id IN ($ids)";
    	$res = M()->execute($sql);
    	layout(false);
    	if($res!==false)
    	{
    		$this->success('排序成功!');
    	}else {
    		$this->error('操作失败!');
    	}
    }
}