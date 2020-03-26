<?php
/**
 * 商城系统-商品分类管理
 */
namespace Admin\Controller;
use Admin\Common\Controller\AuthController;
class GoodsCatController extends AuthController
{
    public function index()
    {
    	//获取商品分类列表
    	$GoodsCat=new \Common\Model\GoodsCatModel();
    	$catlist=$GoodsCat->getCatList();
    	$this->assign('catlist',$catlist);
        $this->display();
    }
    
    //添加商品分类
    public function add()
    {
    	//获取商品分类列表
    	$GoodsCat=new \Common\Model\GoodsCatModel();
    	//获取商品分类列表
    	$catlist=$GoodsCat->getCatList();
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
    					'maxSize'       =>  1024*1024*4, //上传的文件大小限制 (0-不做限制)
    					'exts'          =>  array('jpg', 'gif', 'png', 'jpeg'), //允许上传的文件后缀
    					'subName'       =>  '', //子目录创建方式，为空
    					'rootPath'      =>  './Public/Upload/GoodsCat/', //保存根路径
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
    				'sort'=>trim(I('post.sort')),
    				'parent_id'=>I('post.parent_id'),
    				'is_show'=>I('post.is_show'),
    				'is_top'=>I('post.is_top'),
    				'keywords'=>trim(I('post.keywords')),
    				'description'=>I('post.description'),
    				'content'=>$content,
    				'create_time'=>date('Y-m-d H:i:s'),
    				'img'=>$img
    		);
    		if(!$GoodsCat->create($data))
    		{
    			// 如果创建失败 表示验证没有通过 输出错误提示信息
    			// 删除图片
    			if($filepath)
    			{
    				@unlink($filepath);
    			}
    			$this->error($GoodsCat->getError());
    		}else {
    			// 验证成功
    			$res=$GoodsCat->add($data);
    			if($res)
    			{
    				$this->success('新增商品分类成功！',U('index'));
    			}else {
    				//删除图片
    				if($filepath)
    				{
    					@unlink($filepath);
    				}
    				$this->error('操作失败！');
    			}
    		}
    	}else {
    		$this->display();
    	}
    }
    
    //编辑商品分类
    public function edit($cat_id)
    {
    	$GoodsCat=new \Common\Model\GoodsCatModel();
    	//获取商品分类列表
    	$catlist=$GoodsCat->getCatList();
    	$this->assign('catlist',$catlist);
    	//根据商品分类ID获取子分类
    	$subcatlist=$GoodsCat->getSubCatList($cat_id);
    	$subnum=count($subcatlist);
    	for($i=0;$i<$subnum;$i++)
    	{
    		$subarr[]=$subcatlist[$i]['cat_id'];
    	}
    	$this->assign('subarr',$subarr);
    	//获取商品分类信息
    	$msg=$GoodsCat->getCatMsg($cat_id);
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
    					'maxSize'       =>  1024*1024*4, //上传的文件大小限制 (0-不做限制)
    					'exts'          =>  array('jpg', 'gif', 'png', 'jpeg'), //允许上传的文件后缀
    					'subName'       =>  '', //子目录创建方式，为空
    					'rootPath'      =>  './Public/Upload/GoodsCat/', //保存根路径
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
    				'sort'=>trim(I('post.sort')),
    				'parent_id'=>I('post.parent_id'),
    				'is_show'=>I('post.is_show'),
    				'is_top'=>I('post.is_top'),
    				'keywords'=>trim(I('post.keywords')),
    				'description'=>I('post.description'),
    				'content'=>$content,
    				'create_time'=>date('Y-m-d H:i:s'),
    				'img'=>$img
    		);
    		if(!$GoodsCat->create($data))
    		{
    			// 如果创建失败 表示验证没有通过 输出错误提示信息
    			// 删除图片
    			if($filepath)
    			{
    				@unlink($filepath);
    			}
    			$this->error($GoodsCat->getError());
    		}else {
    			//验证成功
    			$res=$GoodsCat->where("cat_id=$cat_id")->save($data);
    			if($res!==false)
    			{
    				// 修改成功
    				// 原图片存在，并且上传了新图片的情况下，删除原标题图片
    				if(I('post.oldimg') and $img!=I('post.oldimg'))
    				{
    					$oldimg='.'.I('post.oldimg');
    					unlink($oldimg);
    				}
    				$this->success('修改商品分类成功！',U('index'));
    			}else {
    				//删除图片
    				if($filepath)
    				{
    					@unlink($filepath);
    				}
    				$this->error('操作失败！');
    			}
    		}
    	}else {
    		$this->display();
    	}
    }
    
    //删除商品分类
    public function del($cat_id)
    {
    	$GoodsCat=new \Common\Model\GoodsCatModel();
    	/* 
    	 * 先判断分类下是否有二级分类
    	 * 存在不准删除，需要先删除二级分类
    	 *  */
    	$res_p=$GoodsCat->where("parent_id=$cat_id")->field('cat_id')->find();
    	if($res_p['cat_id']!='')
    	{
    		//存在子级分类，不准删除
    		echo '3';
    		exit();
    	}
    	
    	/*
    	 * 先判断分类下是否有商品
    	* 存在不准删除，需要先删除商品
    	*  */
    	$Goods=new \Common\Model\GoodsModel();
    	$goods_num=$Goods->where("cat_id='$cat_id'")->count();
    	if($goods_num>0)
    	{
    		//分类下存在商品，不准删除
    		echo '2';
    		exit();
    	}else {
    		//进行删除操作
    		$msg=$GoodsCat->getCatMsg($cat_id);
    		$res=$GoodsCat->where("cat_id='$cat_id'")->delete();
    		if($res!==false)
    		{
    			//删除图片
    			if($msg['img'])
    			{
    				$img='.'.$msg['img'];
    				@unlink($img);
    			}
    			//删除内容中的文件
    			if($msg['content'])
    			{
    				$Ueditor=new \Admin\Common\Controller\UeditorController();
    				$Ueditor->del($msg['content']);
    			}
    			echo '1';
    		}else {
    			echo '0';
    		}
    	}
    }
    
    //修改商品分类状态
    public function changestatus($id,$status)
    {
    	$data=array(
    			'is_show'=>$status
    	);
    	$GoodsCat=new \Common\Model\GoodsCatModel();
    	if(!$GoodsCat->create($data))
    	{
    		// 如果创建失败 表示验证没有通过 输出错误提示信息
    		// $this->error($GoodsCat->getError());
    		echo '0';
    	}else {
    		// 验证成功
    		$res=$GoodsCat->where("cat_id=$id")->save($data);
    		if($res===false)
    		{
    			echo '0';
    		}else {
    			echo '1';
    		}
    	}
    }
    
    //修改推荐/置顶状态
    public function changetop($id,$status)
    {
    	$data=array(
    			'is_top'=>$status
    	);
    	$GoodsCat=new \Common\Model\GoodsCatModel();
    	if(!$GoodsCat->create($data))
    	{
    		// 验证不通过
    		echo '0';
    	}else {
    		// 验证成功
    		$res=$GoodsCat->where("cat_id=$id")->save($data);
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
    	$sql = "UPDATE __PREFIX__goods_cat SET sort = CASE cat_id ";
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
    		$this->success('排序成功!',U('index'));
    	}
    }
    
    //删除原分类图片
    public function deloldimg($cat_id)
    {
    	$GoodsCat=new \Common\Model\GoodsCatModel();
    	$res=$GoodsCat->where("cat_id=$cat_id")->find();
    	if($res===false)
    	{
    		echo '0';
    	}else {
    		$oldimg=$res['img'];
    		//修改img为空
    		$data=array(
    				'img'=>''
    		);
    		$res2=$GoodsCat->where("cat_id=$cat_id")->save($data);
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