<?php
/**
 * 商城系统-商品分类属性值配置
 */
namespace Admin\Controller;
use Admin\Common\Controller\AuthController;

class GoodsAttributeValueController extends AuthController
{
	public function index($goods_attribute_id)
	{
		//获取商品分类属性信息
		$GoodsAttribute=new \Common\Model\GoodsAttributeModel();
		$GoodsAttributeMsg=$GoodsAttribute->getMsg($goods_attribute_id);
		$this->assign('GoodsAttributeMsg',$GoodsAttributeMsg);
		
		//获取商品分类属性值列表
		$GoodsAttributeValue=new \Common\Model\GoodsAttributeValueModel();
		$list=$GoodsAttributeValue->getList($goods_attribute_id);
		$this->assign('list',$list);
		$this->display();
	}
	
	//添加商品分类属性值
	public function add($goods_attribute_id)
	{
		$this->assign('goods_attribute_id',$goods_attribute_id);
		
		if(I('post.'))
		{
			layout(false);
			if(I('post.name'))
			{
				//上传商品分类属性值配图
				if(!empty($_FILES['img']['name']))
				{
					$config = array(
							'mimes'         =>  array(), //允许上传的文件MiMe类型
							'maxSize'       =>  1024*1024*4, //上传的文件大小限制 (0-不做限制)
							'exts'          =>  array('jpg', 'gif', 'png', 'jpeg'), //允许上传的文件后缀
							'rootPath'      =>  './Public/Upload/GoodsAttributeValue/', //保存根路径
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
					$img='';
				}
				//保存到数据库
				$data=array(
						'goods_attribute_id'=>$goods_attribute_id,
						'name'=>trim(I('post.name')),
						'img'=>$img,
						'sort'=>trim(I('post.sort')),
						'is_show'=>trim(I('post.is_show')),
				);
				$GoodsAttributeValue=new \Common\Model\GoodsAttributeValueModel();
				if(!$GoodsAttributeValue->create($data))
				{
					// 验证不通过
					// 删除图片
					if($img)
					{
						@unlink($filepath);
					}
					$this->error($GoodsAttributeValue->getError());
				}else {
					// 验证成功
					$res=$GoodsAttributeValue->add($data);
					if($res)
					{
						$this->success('新增商品分类属性值成功！',U('index',array('goods_attribute_id'=>$goods_attribute_id)));
					}else {
						// 删除图片
						if($img)
						{
							@unlink($filepath);
						}
						$this->error('新增商品分类属性值失败！');
					}
				}
			}else {
				$this->error('商品分类属性值名称不能为空！');
			}
		}else {
			$this->display();
		}
	}
	
	//编辑商品分类属性值
	public function edit($goods_attribute_value_id)
	{
		//获取属性值详情
		$GoodsAttributeValue=new \Common\Model\GoodsAttributeValueModel();
		$msg=$GoodsAttributeValue->getMsg($goods_attribute_value_id);
		$this->assign('msg',$msg);
		
		if(I('post.'))
		{
			layout(false);
			if(I('post.name'))
			{
				//上传标题图片
				if(!empty($_FILES['img']['name']))
				{
					$config = array(
							'mimes'         =>  array(), //允许上传的文件MiMe类型
							'maxSize'       =>  1024*1024*4, //上传的文件大小限制 (0-不做限制)
							'exts'          =>  array('jpg', 'gif', 'png', 'jpeg'), //允许上传的文件后缀
							'rootPath'      =>  './Public/Upload/GoodsAttributeValue/', //保存根路径
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
						'name'=>trim(I('post.name')),
						'img'=>$img,
						'sort'=>trim(I('post.sort')),
						'is_show'=>trim(I('post.is_show')),
				);
				if(!$GoodsAttributeValue->create($data))
				{
					// 验证不通过
					// 删除图片
					if($filepath)
					{
						@unlink($filepath);
					}
					$this->error($GoodsAttributeValue->getError());
				}else {
					// 验证成功
					$res=$GoodsAttributeValue->where("goods_attribute_value_id='$goods_attribute_value_id'")->save($data);
					if($res!==false)
					{
						// 修改成功
						// 原图片存在，并且上传了新图片的情况下，删除原图片
						if(I('post.oldimg') and $img!=I('post.oldimg'))
						{
							$oldimg='.'.I('post.oldimg');
							unlink($oldimg);
						}
						$this->success('修改商品分类属性值成功！',U('index',array('goods_attribute_id'=>$msg['goods_attribute_id'])));
					}else {
						// 删除图片
						if($filepath)
						{
							@unlink($filepath);
						}
						$this->error('操作失败！');
					}
				}
			}else {
				$this->error('商品分类属性值名称不能为空！');
			}
		}else {
			$this->display();
		}
	}
	
	//删除商品分类属性值
	public function del($goods_attribute_id,$goods_attribute_value_id,$value)
	{
		//先判断是否已被商品配置使用，已使用不准删除
		$str='{"attribute_id":'.$goods_attribute_id.',"value":"'.$value.'"}';
		$GoodsSku=new \Common\Model\GoodsSkuModel();
		$num=$GoodsSku->where("sku like '%$str%'")->count();
		if($num>0)
		{
			//已被使用，不准删除
			echo '2';
		}else {
			//删除该商品分类属性值
			$GoodsAttributeValue=new \Common\Model\GoodsAttributeValueModel();
			$res=$GoodsAttributeValue->where("goods_attribute_value_id='$goods_attribute_value_id'")->delete();
			if($res!==false)
			{
				echo '1';
			}else {
				echo '0';
			}
		}
	}
	
	//删除原商品分类属性值配图
	public function deloldimg($goods_attribute_value_id)
	{
		$GoodsAttributeValue=new \Common\Model\GoodsAttributeValueModel();
		$msg=$GoodsAttributeValue->getMsg($goods_attribute_value_id);
		if($msg===false)
		{
			echo '0';
		}else {
			//修改img为空
			$data=array(
					'img'=>''
			);
			$res=$GoodsAttributeValue->where("goods_attribute_value_id='$goods_attribute_value_id'")->save($data);
			if($res)
			{
				$oldimg='.'.$msg['img'];
				unlink($oldimg);
				echo '1';
			}else {
				echo '0';
			}
		}
	}
	
	//修改显示状态
	public function changeshow($id,$status)
	{
		$data=array(
				'is_show'=>$status
		);
		$GoodsAttributeValue=new \Common\Model\GoodsAttributeValueModel();
		if(!$GoodsAttributeValue->create($data))
		{
			// 验证不通过
			echo '0';
		}else {
			// 验证成功
			$res=$GoodsAttributeValue->where("goods_attribute_value_id=$id")->save($data);
			if($res===false)
			{
				echo '0';
			}else {
				echo '1';
			}
		}
	}
	
	//批量修改排序
	public function changesort($goods_attribute_id)
	{
		$sort_array=I('post.sort');
		$ids = implode(',', array_keys($sort_array));
		$sql = "UPDATE __PREFIX__goods_attribute_value SET sort = CASE goods_attribute_value_id ";
		foreach ($sort_array as $id => $sort) {
			$sql .= sprintf("WHEN %d THEN %d ", $id, $sort);
		}
		$sql.= "END WHERE goods_attribute_value_id IN ($ids)";
		$res = M()->execute($sql);
		layout(false);
		if($res===false)
		{
			$this->error('操作失败!');
		}else {
			$this->success('排序成功!',U('index',array('goods_attribute_id'=>$goods_attribute_id)));
		}
	}
}