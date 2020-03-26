<?php
/**
 * 友情链接管理
 */
namespace Admin\Controller;
use Admin\Common\Controller\AuthController;
class HrefController extends AuthController
{
    public function index($cat_id)
    {
    	$this->assign('cat_id',$cat_id);
    	//获取分类信息
    	$HrefCat=new \Common\Model\HrefCatModel();
    	$catMsg=$HrefCat->getCatMsg($cat_id);
    	$this->assign('cat_title',$catMsg['title']);
    	
    	//根据分类ID获取链接列表
    	$Href=new \Common\Model\HrefModel();
    	$hlist=$Href->getHrefList($cat_id);
    	$this->assign('hlist',$hlist);
    	
    	$count=$Href->count();
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
    	
        $this->display();
    }
    
    //添加链接
    public function add($cat_id)
    {
    	$this->assign('cat_id',$cat_id);
    	//获取分类信息
    	$HrefCat=new \Common\Model\HrefCatModel();
    	$catMsg=$HrefCat->getCatMsg($cat_id);
    	$this->assign('cat_title',$catMsg['title']);
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
    					'rootPath'      =>  './Public/Upload/Href/', //保存根路径
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
    				//文件完成路径
    				$filepath=$config['rootPath'].$info['savepath'].$info['savename'];
    				$img=substr($filepath,1);
    			}
    		}
    		//保存到数据库
    		$data=array(
    				'cat_id'=>$cat_id,
    				'title'=>trim(I('post.title')),
    				'href'=>trim(I('post.href')),
    				'sort'=>trim(I('post.sort')),
    				'img'=>$img,
    				'createtime'=>date('Y-m-d H:i:s')
    		);
    		$Href=new \Common\Model\HrefModel();
    		if(!$Href->create($data))
    		{
    			// 如果创建失败 表示验证没有通过 输出错误提示信息
    			// 删除图片
    			@unlink($filepath);
    			$this->error($Href->getError());
    		}else {
    			// 验证成功
    			$res=$Href->add($data);
    			if($res!==false)
    			{
    				$this->success('新增链接成功！',U('index',array('cat_id'=>$cat_id)));
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
    
    //编辑链接
    public function edit($id,$cat_id)
    {
    	$this->assign('id',$id);
    	$this->assign('cat_id',$cat_id);
    	//根据ID获取图片信息
    	$Href=new \Common\Model\HrefModel();
    	$msg=$Href->getHrefMsg($id);
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
    					'rootPath'      =>  './Public/Upload/Href/', //保存根路径
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
    		if(!$Href->create($data))
    		{
    			// 如果创建失败 表示验证没有通过 输出错误提示信息
    			// 删除图片
    			@unlink($filepath);
    			$this->error($Href->getError());
    		}else {
    			// 验证成功
    			$res=$Href->where("id=$id")->save($data);
    			if($res!==false)
    			{
    				// 修改成功
    				// 原图片存在，并且上传了新图片的情况下，删除原标题图片
    				if(I('post.oldimg') and $img!=I('post.oldimg'))
    				{
    					$oldimg='.'.I('post.oldimg');
    					unlink($oldimg);
    				}
    				$this->success('修改链接成功！');
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
    
    //删除链接
    public function del($id)
    {
    	$Href=new \Common\Model\HrefModel();
    	$res1=$Href->getHrefMsg($id);
    	$img=$res1['img'];
    	$res=$Href->where("id=$id")->delete();
    	if($res)
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
    
    //批量删除链接
    public function batchdel($all_id)
    {
    	$all_id=substr($all_id,0,-1);
    	$id_arr=explode(',',$all_id);
    	$num=count($id_arr);
    	$Href=new \Common\Model\HrefModel();
    	for($i=0;$i<$num;$i++)
    	{
    		$id=$id_arr[$i];
    		$res1=$Href->getHrefMsg($id);
    		$img=$res1['img'];
    		$res=$Href->where("id=$id")->delete();
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
    	$sql = "UPDATE __PREFIX__href SET sort = CASE id ";
    	foreach ($sort_array as $id => $sort) {
    		$sql .= sprintf("WHEN %d THEN %d ", $id, $sort);
    	}
    	$sql.= "END WHERE id IN ($ids)";
    	$res = M()->execute($sql);
    	layout(false);
    	if($res===false)
    	{
    		$this->error('操作失败!');
    	}else {
    		$this->success('排序成功!');
    	}
    }
}