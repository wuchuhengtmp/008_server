<?php
/**
 * 内容管理-文章分类管理
 */
namespace Admin\Controller;
use Admin\Common\Controller\AuthController;
class ArticleCatController extends AuthController
{
    public function index()
    {
    	//获取文章分类列表
    	$ArticleCat=new \Common\Model\ArticleCatModel();
    	$catlist=$ArticleCat->getCatList();
    	$this->assign('catlist',$catlist);
        $this->display();
    }
    
    //添加文章分类
    public function add()
    {
    	$ArticleCat=new \Common\Model\ArticleCatModel();
    	//获取文章分类列表
    	$catlist=$ArticleCat->getCatList();
    	$this->assign('catlist',$catlist);
    	if(I('post.'))
    	{
    		layout(false);
    		$content=$_POST['content'];
    		//新增内容
    		//转移ueditor文件：将file和img从ueditor_tmp文件夹中转移到正式目录ueditor中
    		if(!empty($content))
    		{
    			$ueditor=new \Admin\Common\Controller\UeditorController;
    			$content=$ueditor->add($content);
    		}
    		//上传文件
    		if(!empty($_FILES['img']['name']))
    		{
    			$config = array(
    					'mimes'         =>  array(), //允许上传的文件MiMe类型
    					'maxSize'       =>  1024*1024*2, //上传的文件大小限制 (0-不做限制)
    					'exts'          =>  array('jpg', 'gif', 'png', 'jpeg'), //允许上传的文件后缀
    					'rootPath'      =>  './Public/Upload/ArticleCat/', //保存根路径
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
    				'cat_name'=>trim(I('post.cat_name')),
    				'sort'=>I('post.sort'),
    				'parent_id'=>I('post.parent_id'),
    				'is_show'=>I('post.is_show'),
    				'keywords'=>I('post.keywords'),
    				'description'=>I('post.description'),
    				'content'=>$content,
    				'img'=>$img,
    				'create_time'=>date('Y-m-d H:i:s')
    		);
    		if(!$ArticleCat->create($data))
    		{
    			// 如果创建失败 表示验证没有通过 输出错误提示信息
    			// 删除图片
    			@unlink($filepath);
    			$this->error($ArticleCat->getError());
    		}else {
    			// 验证成功
    			$res=$ArticleCat->add($data);
    			if($res!==false)
    			{
    				$this->success('新增文章分类成功！',U('index'));
    			}else {
    				// 删除图片
    				@unlink($filepath);
    				$this->error('操作失败！');
    			}
    		}
    	}else {
    		$this->display();
    	}
    }
    
    //编辑文章分类
    public function edit($cat_id)
    {
    	//获取文章分类列表
    	$ArticleCat=new \Common\Model\ArticleCatModel();
    	$catlist=$ArticleCat->getCatList();
    	$this->assign('catlist',$catlist);
    	//根据文章分类ID获取子分类
    	$subcatlist=$ArticleCat->getSubCatList($cat_id);
    	$subnum=count($subcatlist);
    	for($i=0;$i<$subnum;$i++)
    	{
    		$subarr[]=$subcatlist[$i]['cat_id'];
    	}
    	$this->assign('subarr',$subarr);
    	//获取文章分类信息
    	$msg=$ArticleCat->getCatMsg($cat_id);
    	$this->assign('msg',$msg);
    	if(I('post.'))
    	{
    		layout(false);
    		$content=$_POST['content'];
    		//编辑内容
    		//先上传新的内容，再删除原有内容中被删除的文件
    		if (! empty ( $content ) || !empty($_POST['oldcontent']))
    		{
    			$ueditor=new \Admin\Common\Controller\UeditorController;
    			$content=$ueditor->edit($content,$_POST['oldcontent']);
    		}
    		//上传文件
    		if(!empty($_FILES['img']['name']))
    		{
    			$config = array(
    					'mimes'         =>  array(), //允许上传的文件MiMe类型
    					'maxSize'       =>  1024*1024*2, //上传的文件大小限制 (0-不做限制)
    					'exts'          =>  array('jpg', 'gif', 'png', 'jpeg'), //允许上传的文件后缀
    					'rootPath'      =>  './Public/Upload/ArticleCat/', //保存根路径
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
    				'cat_name'=>trim(I('post.cat_name')),
    				'sort'=>I('post.sort'),
    				'parent_id'=>I('post.parent_id'),
    				'is_show'=>I('post.is_show'),
    				'keywords'=>I('post.keywords'),
    				'description'=>I('post.description'),
    				'content'=>$content,
    				'img'=>$img,
    				'create_time'=>date('Y-m-d H:i:s')
    		);
    		if(!$ArticleCat->create($data))
    		{
    			// 如果创建失败 表示验证没有通过 输出错误提示信息
    			// 删除图片
    			@unlink($filepath);
    			$this->error($ArticleCat->getError());
    		}else {
    			// 验证成功
    			$res=$ArticleCat->where("cat_id=$cat_id")->save($data);
    			if($res!==false)
    			{
    				// 修改成功
    				// 原图片存在，并且上传了新图片的情况下，删除原标题图片
    				if(I('post.oldimg') and $img!=I('post.oldimg'))
    				{
    					$oldimg='.'.I('post.oldimg');
    					unlink($oldimg);
    				}
    				$this->success('修改文章分类成功！',U('index'));
    			}else {
    				// 删除图片
    				@unlink($filepath);
    				$this->error('操作失败！');
    			}
    		}
    	}else {
    		$this->display();
    	}
    }
    
    //删除文章分类
    public function del($cat_id)
    {
    	$ArticleCat=new \Common\Model\ArticleCatModel();
    	/* 
    	 * 先判断分类下是否有二级分类
    	 * 存在不准删除，需要先删除二级分类
    	 *  */
    	$res_p=$ArticleCat->where("parent_id=$cat_id")->field('cat_id')->find();
    	if($res_p['cat_id']!='')
    	{
    		echo '2';
    		exit();
    	}
    	
    	$res1=$ArticleCat->where("cat_id=$cat_id")->field('img,content')->find();
    	$cat_img=$res1['img'];
    	$content=htmlspecialchars_decode(html_entity_decode($res1['content']));
    	$res=$ArticleCat->where("cat_id=$cat_id")->delete();
    	if($res)
    	{
    		//删除分类图片
    		if(!empty($cat_img))
    		{
    			$cat_img='.'.$cat_img;
    			unlink($cat_img);
    		}
    		// 删除内容中的图片和文件
			if (! empty ( $content )) 
			{
				$ueditor=new \Admin\Common\Controller\UeditorController;
				$ueditor->del($content);
			}
    		//删除分类下的所有文章
    		$Article=new \Common\Model\ArticleModel();
    		$data=$Article->where("cat_id=$cat_id")->select();
    		if(!empty($data))
    		{
    			$num=count($data);
    			for($i=0;$i<$num;$i++)
    			{
    				$aid=$data[$i]['article_id'];
    				$Article->del($aid);
    			    $a.='a';
    			}
    			$a.='true';
    			$str=str_repeat('a',$num).'true';;
    			if($str==$a)
    			{
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
    
    //修改文章分类状态
    public function changestatus($id,$status)
    {
    	$data=array(
    			'is_show'=>$status
    	);
    	$ArticleCat=new \Common\Model\ArticleCatModel();
    	if(!$ArticleCat->create($data))
    	{
    		// 如果创建失败 表示验证没有通过 输出错误提示信息
    		// $this->error($articlecat->getError());
    		echo '0';
    	}else {
    		// 验证成功
    		$res=$ArticleCat->where("cat_id='$id'")->save($data);
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
    	$sql = "UPDATE __PREFIX__article_cat SET sort = CASE cat_id ";
    	foreach ($sort_array as $id => $sort) {
    		$sql .= sprintf("WHEN %d THEN %d ", $id, $sort);
    	}
    	$sql.= "END WHERE cat_id IN ($ids)";
    	$res = M()->execute($sql);
    	layout(false);
    	if($res===false)
    	{
    		$this->error('操作失败!');
    	}else {
    		$this->success('排序成功!',U('index'),3);
    	}
    }
    
    //删除原分类图片
    public function deloldimg($cat_id)
    {
    	$ArticleCat=new \Common\Model\ArticleCatModel();
    	$res=$ArticleCat->where("cat_id=$cat_id")->find();
    	if($res===false)
    	{
    		echo '0';
    	}else {
    		$oldimg=$res['img'];
    		//修改img为空
    		$data=array(
    				'img'=>''
    		);
    		$res2=$ArticleCat->where("cat_id=$cat_id")->save($data);
    		if($res2!==false)
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