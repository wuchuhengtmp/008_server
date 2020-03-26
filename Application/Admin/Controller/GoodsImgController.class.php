<?php
/**
 * 商品管理-商品图片管理
 */
namespace Admin\Controller;
use Admin\Common\Controller\AuthController;
class GoodsImgController extends AuthController
{
    public function index($goods_id)
    {
    	$this->assign('goods_id',$goods_id);
    	if(I('get.cat_id'))
    	{
    		$cat_id=I('get.cat_id');
    		$this->assign('cat_id',$cat_id);
    	}  
    	
    	//获取商品信息
    	$Goods=new \Common\Model\GoodsModel();
    	$goodsMsg=$Goods->getGoodsMsg($goods_id);
    	$this->assign('goods_name',$goodsMsg['goods_name']);
    	
    	//根据商品ID获取图片列表
    	$GoodsImg=new \Common\Model\GoodsImgModel();
    	$list=$GoodsImg->getImgList($goods_id);
    	$this->assign('list',$list);
        $this->display();
    }
      
    //添加图片
    public function add($goods_id)
    {
    	$this->assign('goods_id',$goods_id);
    	if(I('get.cat_id'))
    	{
    		$cat_id=I('get.cat_id');
    		$this->assign('cat_id',$cat_id);
    	}
    	//获取商品信息
    	$Goods=new \Common\Model\GoodsModel();
    	$goodsMsg=$Goods->getGoodsMsg($goods_id);
    	$this->assign('goods_name',$goodsMsg['goods_name']);
    	
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
    					'rootPath'      =>  './Public/Upload/GoodsImg/', //保存根路径
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
    					'goods_id'=>$goods_id,
    					'title'=>trim(I('post.title')),
    					'sort'=>trim(I('post.sort')),
    					'img'=>$img,
    					'createtime'=>date('Y-m-d H:i:s')
    			);
    			$GoodsImg=new \Common\Model\GoodsImgModel();
    			if(!$GoodsImg->create($data))
    			{
    				// 验证不通过
    				// 删除图片
    				if($img)
    				{
    					@unlink($filepath);
    				}
    				$this->error($GoodsImg->getError());
    			}else {
    				// 验证成功
    				$res=$GoodsImg->add($data);
    				if($res!==false)
    				{
    					$this->success('新增商品图片成功！',U('index',array('goods_id'=>$goods_id,'cat_id'=>$cat_id)));
    				}else {
    					//删除图片
    					if($img)
    					{
    						@unlink($filepath);
    					}
    					$this->error('新增商品图片失败！');
    				}
    			}
    		}else {
    			$this->error('请选择上传图片!');
    		}
    	}else {
    		$this->display();
    	}
    }
    
    //编辑图片
    public function edit($goods_img_id)
    {
    	if(I('get.cat_id'))
    	{
    		$cat_id=I('get.cat_id');
    		$this->assign('cat_id',$cat_id);
    	}
    	//根据ID获取图片信息
    	$GoodsImg=new \Common\Model\GoodsImgModel();
    	$msg=$GoodsImg->getImgMsg($goods_img_id);
    	$this->assign('msg',$msg);
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
    					'rootPath'      =>  './Public/Upload/GoodsImg/', //保存根路径
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
    				'sort'=>trim(I('post.sort')),
    				'img'=>$img,
    				'createtime'=>date('Y-m-d H:i:s')
    		);
    		if(!$GoodsImg->create($data))
    		{
    			// 验证不通过
    			// 删除图片
    			if($filepath)
    			{
    				@unlink($filepath);
    			}
    			$this->error($GoodsImg->getError());
    		}else {
    			// 验证成功
    			$res=$GoodsImg->where("goods_img_id='$goods_img_id'")->save($data);
    			if($res!==false)
    			{
    				// 修改成功
    				// 原图片存在，并且上传了新图片的情况下，删除原标题图片
    				if(I('post.oldimg') and $img!=I('post.oldimg'))
    				{
    					$oldimg='.'.I('post.oldimg');
    					unlink($oldimg);
    				}
    				$this->success('修改商品图片成功！');
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
    
    //删除图片
    public function del($goods_img_id)
    {
    	$GoodsImg=new \Common\Model\GoodsImgModel();
    	$msg=$GoodsImg->getImgMsg($goods_img_id);
    	$res=$GoodsImg->where("goods_img_id='$goods_img_id'")->delete();
    	if($res)
    	{
    		//删除图片
    		if(!empty($msg['img']))
    		{
    			$img='.'.$msg['img'];
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
    	$GoodsImg=new \Common\Model\GoodsImgModel();
    	for($i=0;$i<$num;$i++)
    	{
    		$goods_img_id=$id_arr[$i];
    		$msg=$GoodsImg->getImgMsg($goods_img_id);
    		$res=$GoodsImg->where("goods_img_id='$goods_img_id'")->delete();
    		if($res)
    		{
    			//删除图片
    			if(!empty($msg['img']))
    			{
    				$img='.'.$msg['img'];
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
    	$sql = "UPDATE __PREFIX__goods_img SET sort = CASE goods_img_id ";
    	foreach ($sort_array as $id => $sort) {
    		$sql .= sprintf("WHEN %d THEN %d ", $id, $sort);
    	}
    	$sql.= "END WHERE goods_img_id IN ($ids)";
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