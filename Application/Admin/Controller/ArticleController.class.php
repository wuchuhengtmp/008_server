<?php
/**
 * 内容管理-文章管理
 */
namespace Admin\Controller;
use Admin\Common\Controller\AuthController;
class ArticleController extends AuthController
{
    public function index($cat_id)
    {
    	$this->assign('cat_id',$cat_id);
    	//分类名称
    	$ArticleCat=new \Common\Model\ArticleCatModel();
    	//获取文章分类列表
    	$catlist=$ArticleCat->getCatList();
    	$this->assign('catlist',$catlist);
    	$catMsg=$ArticleCat->getCatMsg($cat_id);
    	$this->assign('cname',$catMsg['cat_name']);
    	
    	if(I('get.search'))
    	{
    		$search=I('get.search');
    		$where="title like '%$search%' and cat_id='$cat_id'";
    	}else {
    		$where="cat_id=$cat_id";
    	}
    	$Article=new \Common\Model\ArticleModel();
    	$count=$Article->where($where)->count();
    	$per = 15;
    	if($_GET['p'])
    	{
    		$p=$_GET['p'];
    	}else {
    		$p=1;
    	}
    	$Page=new \Common\Model\PageModel();
    	$show= $Page->show($count,$per);// 分页显示输出
    	$this->assign('page',$show);
    	
    	$articlelist = $Article->where($where)->page($p.','.$per)->order('sort desc,article_id desc')->select();
    	$this->assign('articlelist',$articlelist);
    	
        $this->display();
    }
    
    //添加文章
    public function add($cat_id)
    {
    	$this->assign('cat_id',$cat_id);
    	//获取文章分类列表
    	$ArticleCat=new \Common\Model\ArticleCatModel();
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
    		//上传标题图片
    		if(!empty($_FILES['img']['name']))
    		{
    			$config = array(
    					'mimes'         =>  array(), //允许上传的文件MiMe类型
    					'maxSize'       =>  1024*1024*4, //上传的文件大小限制 (0-不做限制)
    					'exts'          =>  array('jpg', 'gif', 'png', 'jpeg'), //允许上传的文件后缀
    					'rootPath'      =>  './Public/Upload/Article/', //保存根路径
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
    		//上传大图片
    		if(!empty($_FILES['bigimg']['name']))
    		{
    			$config = array(
    					'mimes'         =>  array(), //允许上传的文件MiMe类型
    					'maxSize'       =>  1024*1024*4, //上传的文件大小限制 (0-不做限制)
    					'exts'          =>  array('jpg', 'gif', 'png', 'jpeg'), //允许上传的文件后缀
    					'rootPath'      =>  './Public/Upload/Article/', //保存根路径
    					'savePath'      =>  '', //保存路径
    					'saveExt'       =>  '', //文件保存后缀，空则使用原后缀
    			);
    			$upload = new \Think\Upload($config);
    			// 上传单个文件
    			$info = $upload->uploadOne($_FILES['bigimg']);
    			if(!$info) {
    				// 上传错误提示错误信息
    				$this->error($upload->getError());
    			}else{
    				// 上传成功
    				// 文件完成路径
    				$filepath2=$config['rootPath'].$info['savepath'].$info['savename'];
    				$bigimg=substr($filepath2,1);
    			}
    		}
    		//上传文件
    		if(!empty($_FILES['file']['name']))
    		{
    			$config = array(
    					'mimes'         =>  array(), //允许上传的文件MiMe类型
    					'maxSize'       =>  1024*1024*200, //上传的文件大小限制 (0-不做限制)
    					'rootPath'      =>  './Public/Upload/Article/file/', //保存根路径
    					'savePath'      =>  '', //保存路径
    					'saveExt'       =>  '', //文件保存后缀，空则使用原后缀
    			);
    			$upload = new \Think\Upload($config);
    			// 上传单个文件
    			$info = $upload->uploadOne($_FILES['file']);
    			if(!$info) {
    				// 上传错误提示错误信息
    				$this->error($upload->getError());
    			}else{
    				// 上传成功
    				// 文件完成路径
    				$filepath3=$config['rootPath'].$info['savepath'].$info['savename'];
    				$file=substr($filepath3,1);
    			}
    		}
    		//保存到数据库
    		$data=array(
    				'cat_id'=>I('post.cat_id'),
    				'title'=>trim(I('post.title')),
    				'title_font_color'=>I('post.title_font_color'),
    				'author'=>I('post.author'),
    				'keywords'=>I('post.keywords'),
    				'description'=>I('post.description'),
    				'content'=>$content,
    				'is_show'=>I('post.is_show'),
    				'is_top'=>I('post.is_top'),
    				'pubtime'=>date('Y-m-d H:i:s'),
    				'img'=>$img,
    				'bigimg'=>$bigimg,
    				'file'=>$file,
    				'sort'=>I('post.sort'),
    				'clicknum'=>I('post.clicknum'),
    				'href'=>I('post.href')
    		);
    		$Article=new \Common\Model\ArticleModel();
    		if(!$Article->create($data))
    		{
    			// 如果创建失败 表示验证没有通过 输出错误提示信息
    			// 删除图片
    			@unlink($filepath);
    			@unlink($filepath2);
    			@unlink($filepath3);
    			$this->error($Article->getError());
    		}else {
    			// 验证成功
    			$res=$Article->add($data);
    			if($res)
    			{
    				$this->success('新增文章成功！',U('index',array('cat_id'=>$cat_id)));
    			}else {
    				//删除文件
    				@unlink($filepath);
    				@unlink($filepath2);
    				@unlink($filepath3);
    				$this->error('操作失败！');
    			}
    		}
    	}else {
    		$this->display();
    	}
    }
    
    //编辑文章
    public function edit($article_id)
    {
    	//获取文章分类列表
    	$ArticleCat=new \Common\Model\ArticleCatModel();
    	$catlist=$ArticleCat->getCatList();
    	$this->assign('catlist',$catlist);
    	//获取文章信息
    	$Article=new \Common\Model\ArticleModel();
    	$msg=$Article->getArticleMsg($article_id);
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
    		//上传标题图片
    		if(!empty($_FILES['img']['name']))
    		{
    			$config = array(
    					'mimes'         =>  array(), //允许上传的文件MiMe类型
    					'maxSize'       =>  1024*1024*4, //上传的文件大小限制 (0-不做限制)
    					'exts'          =>  array('jpg', 'gif', 'png', 'jpeg'), //允许上传的文件后缀
    					'rootPath'      =>  './Public/Upload/Article/', //保存根路径
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
    		//上传大图片
    		if(!empty($_FILES['bigimg']['name']))
    		{
    			$config = array(
    					'mimes'         =>  array(), //允许上传的文件MiMe类型
    					'maxSize'       =>  1024*1024*4, //上传的文件大小限制 (0-不做限制)
    					'exts'          =>  array('jpg', 'gif', 'png', 'jpeg'), //允许上传的文件后缀
    					'rootPath'      =>  './Public/Upload/Article/', //保存根路径
    					'savePath'      =>  '', //保存路径
    					'saveExt'       =>  '', //文件保存后缀，空则使用原后缀
    			);
    			$upload = new \Think\Upload($config);
    			// 上传单个文件
    			$info = $upload->uploadOne($_FILES['bigimg']);
    			if(!$info) {
    				// 上传错误提示错误信息
    				$this->error($upload->getError());
    			}else{
    				// 上传成功
    				// 文件完成路径
    				$filepath2=$config['rootPath'].$info['savepath'].$info['savename'];
    				$bigimg=substr($filepath2,1);
    			}
    		}else {
    			$bigimg=I('post.oldbigimg');
    		}
    		//上传文件
    		if(!empty($_FILES['file']['name']))
    		{
    			$config = array(
    					'mimes'         =>  array(), //允许上传的文件MiMe类型
    					'maxSize'       =>  1024*1024*200, //上传的文件大小限制 (0-不做限制)
    					'rootPath'      =>  './Public/Upload/Article/file/', //保存根路径
    					'savePath'      =>  '', //保存路径
    					'saveExt'       =>  '', //文件保存后缀，空则使用原后缀
    			);
    			$upload = new \Think\Upload($config);
    			// 上传单个文件
    			$info = $upload->uploadOne($_FILES['file']);
    			if(!$info) {
    				// 上传错误提示错误信息
    				$this->error($upload->getError());
    			}else{
    				// 上传成功
    				// 文件完成路径
    				$filepath3=$config['rootPath'].$info['savepath'].$info['savename'];
    				$file=substr($filepath3,1);
    			}
    		}else {
    			$file=I('post.oldfile');
    		}
    		//保存到数据库
    		$data=array(
    				'cat_id'=>I('post.cat_id'),
    				'title'=>trim(I('post.title')),
    				'title_font_color'=>I('post.title_font_color'),
    				'author'=>I('post.author'),
    				'keywords'=>I('post.keywords'),
    				'description'=>I('post.description'),
    				'content'=>$content,
    				'is_show'=>I('post.is_show'),
    				'is_top'=>I('post.is_top'),
    				'pubtime'=>date('Y-m-d H:i:s'),
    				'img'=>$img,
    				'bigimg'=>$bigimg,
    				'file'=>$file,
    				'sort'=>I('post.sort'),
    				'clicknum'=>I('post.clicknum'),
    				'href'=>I('post.href')
    		);
    		if(!$Article->create($data))
    		{
    			// 如果创建失败 表示验证没有通过 输出错误提示信息
    			// 删除图片
    			@unlink($filepath);
    			@unlink($filepath2);
    			@unlink($filepath3);
    			$this->error($Article->getError());
    		}else {
    			// 验证成功
    			$res=$Article->where("article_id=$article_id")->save($data);
    			if($res!==false)
    			{
    				// 修改成功
    				// 原图片存在，并且上传了新图片的情况下，删除原标题图片
    				if(I('post.oldimg') and $img!=I('post.oldimg'))
    				{
    					$oldimg='.'.I('post.oldimg');
    					unlink($oldimg);
    				}
    				// 删除原大图片
    				if(I('post.oldbigimg') and $bigimg!=I('post.oldbigimg'))
    				{
    					$oldbigimg='.'.I('post.oldbigimg');
    					unlink($oldbigimg);
    				}
    				// 删除原文件
    				if(I('post.oldfile') and $file!=I('post.oldfile'))
    				{
    					$oldfile='.'.I('post.oldfile');
    					unlink($oldfile);
    				}
    				$this->success('修改文章成功！');
    			}else {
    				//删除文件
    				@unlink($filepath);
    				@unlink($filepath2);
    				@unlink($filepath3);
    				$this->error('操作失败！');
    			}
    		}
    	}else {
    		$this->display();
    	}
    }
    
    //删除文章
    public function del($id)
    {
    	// 删除操作
    	$Article=new \Common\Model\ArticleModel();
    	$res=$Article->del($id);
    	if($res)
    	{
    		echo '1';
    	}else {
    		echo '0';
    	}
    }
    
    //批量删除文章
    public function batchdel($all_id)
    {
    	$all_id=substr($all_id,0,-1);
    	$id_arr=explode(',',$all_id);
    	$num=count($id_arr);
    	$Article=new \Common\Model\ArticleModel();
    	for($i=0;$i<$num;$i++)
    	{
    		$id=$id_arr[$i];
    		$Article->del($id);
    		$a.='a';
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
    
    //批量转移文章
    public function transfer($all_id,$cat_id)
    {
    	$all_id=substr($all_id,0,-1);
    	$update="UPDATE __PREFIX__article SET cat_id=$cat_id WHERE article_id in($all_id)";
    	$res=M()->execute($update);
    	if($res)
    	{
    		echo '1';
    	}else {
    		echo '0';
    	}
    }
    
    //修改文章显示状态
    public function changeshow($id,$status)
    {
    	$data=array(
    			'is_show'=>$status
    	);
    	$Article=new \Common\Model\ArticleModel();
    	if(!$Article->create($data))
    	{
    		// 如果创建失败 表示验证没有通过 输出错误提示信息
    		// $this->error($article->getError());
    		echo '0';
    	}else {
    		// 验证成功
    		$res=$Article->where("article_id=$id")->save($data);
    		if($res===false)
    		{
    			echo '0';
    		}else {
    			echo '1';
    		}
    	}
    }
    
    //修改文章置顶状态
    public function changetop($id,$status)
    {
    	$data=array(
    			'is_top'=>$status
    	);
    	$Article=new \Common\Model\ArticleModel();
    	if(!$Article->create($data))
    	{
    		// 如果创建失败 表示验证没有通过 输出错误提示信息
    		// $this->error($article->getError());
    		echo '0';
    	}else {
    		// 验证成功
    		$res=$Article->where("article_id=$id")->save($data);
    		if($res===false)
    		{
    			echo '0';
    		}else {
    			echo '1';
    		}
    	}
    }
    
    //批量修改排序
    public function changesort($cat_id)
    {
    	$sort_array=I('post.sort');
    	$ids = implode(',', array_keys($sort_array));
    	$sql = "UPDATE __PREFIX__article SET sort = CASE article_id ";
    	foreach ($sort_array as $id => $sort) {
    		$sql .= sprintf("WHEN %d THEN %d ", $id, $sort);
    	}
    	$sql.= "END WHERE article_id IN ($ids)";
    	$res = M()->execute($sql);
    	layout(false);
    	if($res===false)
    	{
    		$this->error('操作失败!');
    	}else {
    		$this->success('排序成功!');
    	}
    }
    
    //删除原标题图片
    public function deloldimg($article_id)
    {
    	$Article=new \Common\Model\ArticleModel();
    	$res=$Article->where("article_id='$article_id'")->find();
    	if($res===false)
    	{
    		echo '0';
    	}else {
    		$oldimg=$res['img'];
    		//修改img为空
    		$data=array(
    				'img'=>''
    		);
    		$res2=$Article->where("article_id='$article_id'")->save($data);
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
    
    //删除原大图片
    public function deloldbigimg($article_id)
    {
    	$Article=new \Common\Model\ArticleModel();
    	$res=$Article->where("article_id='$article_id'")->find();
    	if($res===false)
    	{
    		echo '0';
    	}else {
    		$oldbigimg=$res['bigimg'];
    		//修改img为空
    		$data=array(
    				'bigimg'=>''
    		);
    		$res2=$Article->where("article_id='$article_id'")->save($data);
    		if($res2)
    		{
    			$oldbigimg='.'.$oldbigimg;
    			unlink($oldbigimg);
    			echo '1';
    		}else {
    			echo '0';
    		}
    	}
    }
    
    //删除原文件
    public function deloldfile($article_id)
    {
    	$Article=new \Common\Model\ArticleModel();
    	$res=$Article->where("article_id='$article_id'")->find();
    	if($res===false)
    	{
    		echo '0';
    	}else {
    		$oldfile=$res['file'];
    		//修改img为空
    		$data=array(
    				'file'=>''
    		);
    		$res2=$Article->where("article_id='$article_id'")->save($data);
    		if($res2)
    		{
    			$oldfile='.'.$oldfile;
    			unlink($oldfile);
    			echo '1';
    		}else {
    			echo '0';
    		}
    	}
    }
}