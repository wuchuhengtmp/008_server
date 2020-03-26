<?php
/**
 * 商城系统-商品分类属性管理
 */
namespace Admin\Controller;
use Admin\Common\Controller\AuthController;

class GoodsAttributeController extends AuthController
{
	public function index($goods_cat_id)
	{
		$this->assign('goods_cat_id',$goods_cat_id);
		//获取商品分类信息
		$GoodsCat=new \Common\Model\GoodsCatModel();
		$GoodsCatMsg=$GoodsCat->getCatMsg($goods_cat_id);
		$this->assign('GoodsCatMsg',$GoodsCatMsg);
		
		// 获取商品分类属性列表
		$GoodsAttribute=new \Common\Model\GoodsAttributeModel();
		$list=$GoodsAttribute->getList($goods_cat_id);
		$this->assign('list',$list);
		$this->display();
	}
	
	//添加商品分类属性
	public function add()
	{
		layout(false);
		if(I('post.goods_attribute_name'))
		{
			$goods_cat_id=I('post.goods_cat_id');
			$data=array(
					'goods_cat_id'=>$goods_cat_id,
					'goods_attribute_name'=>trim(I('post.goods_attribute_name')),
					'sort'=>trim(I('post.sort')),
					'is_show'=>trim(I('post.is_show')),
			);
			$GoodsAttribute=new \Common\Model\GoodsAttributeModel();
			if(!$GoodsAttribute->create($data))
			{
				// 验证失败
				$this->error($GoodsAttribute->getError());
			}else {
				// 验证成功
				$res=$GoodsAttribute->add($data);
				if($res!==false)
				{
					$this->success('新增商品分类属性成功！',U('index',array('goods_cat_id'=>$goods_cat_id)));
				}else {
					$this->error('新增商品分类属性失败！');
				}
			}
		}else {
			$this->error('商品分类属性名称不能为空！');
		}
	}
	
	//编辑商品分类属性
	public function edit($goods_attribute_id)
	{
		//获取商品分类属性详情
		$GoodsAttribute=new \Common\Model\GoodsAttributeModel();
		$msg=$GoodsAttribute->getMsg($goods_attribute_id);
		$this->assign('msg',$msg);
		
		//获取商品分类信息
		$GoodsCat=new \Common\Model\GoodsCatModel();
		$GoodsCatMsg=$GoodsCat->getCatMsg($msg['goods_cat_id']);
		$this->assign('GoodsCatMsg',$GoodsCatMsg);
		
		if(I('post.'))
		{
			layout(false);
			$data=array(
					'goods_attribute_name'=>trim(I('post.goods_attribute_name')),
					'sort'=>trim(I('post.sort')),
					'is_show'=>trim(I('post.is_show')),
			);
			if(!$GoodsAttribute->create($data))
			{
				// 验证通过
				$this->error($GoodsAttribute->getError());
			}else {
				// 验证成功
				$res=$GoodsAttribute->where("goods_attribute_id='$goods_attribute_id'")->save($data);
				if($res!==false)
				{
					$this->success('编辑商品分类属性成功！',U('index',array('goods_cat_id'=>$msg['goods_cat_id'])));
				}else {
					$this->error('编辑商品分类属性失败！');
				}
			}
		}else {
			$this->display();
		}
	}
	
	//删除商品分类属性
	public function del($goods_attribute_id)
	{
		//先判断是否已设置属性值，已设置不准删除，需要先删除属性值
		$GoodsAttributeValue=new \Common\Model\GoodsAttributeValueModel();
		$num=$GoodsAttributeValue->getValueNum($goods_attribute_id);
		if($num>0)
		{
			echo '2';
			exit();
		}else {
			//未设置属性值，可以删除
			$GoodsAttribute=new \Common\Model\GoodsAttributeModel();
			$res=$GoodsAttribute->where("goods_attribute_id='$goods_attribute_id'")->delete();
			if($res!==false)
			{
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
		$GoodsAttribute=new \Common\Model\GoodsAttributeModel();
		if(!$GoodsAttribute->create($data))
		{
			// 验证不通过
			echo '0';
		}else {
			// 验证成功
			$res=$GoodsAttribute->where("goods_attribute_id=$id")->save($data);
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
		$goods_cat_id=I('get.goods_cat_id');
		$sort_array=I('post.sort');
		$ids = implode(',', array_keys($sort_array));
		$sql = "UPDATE __PREFIX__goods_attribute SET sort = CASE goods_attribute_id ";
		foreach ($sort_array as $id => $sort) {
			$sql .= sprintf("WHEN %d THEN %d ", $id, $sort);
		}
		$sql.= "END WHERE goods_attribute_id IN ($ids)";
		$res = M()->execute($sql);
		layout(false);
		if($res===false)
		{
			$this->error('操作失败!');
		}else {
			$this->success('排序成功!',U('index',array('goods_cat_id'=>$goods_cat_id)));
		}
	}
}