<?php
/**
 * 淘宝官方商品分类管理
 */
namespace Admin\Controller;
use Admin\Common\Controller\AuthController;
class TbCatController extends AuthController
{
    public function index()
    {
    	$where="1";
    	if(trim(I('get.category_name')))
    	{
    		$category_name=trim(I('get.category_name'));
    		$where.=" and category_name like '%$category_name%'";
    	}
    	$TbCat=new \Common\Model\TbCatModel();
    	$count=$TbCat->where($where)->count();
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
    	 
    	$list = $TbCat->where($where)->page($p.','.$per)->select();
    	$this->assign('list',$list);
    	 
    	$this->display();
    }
    
    //添加淘宝商品分类
    public function add()
    {
    	if($_POST) {
    		layout(false);
    		if(trim(I('post.category_id')) and trim(I('post.category_name')))
    		{
    			//保存到数据库
    			$data=array(
    					'category_id'=>trim(I('post.category_id')),
    					'category_name'=>trim(I('post.category_name')),
    			);
    			$TbCat=new \Common\Model\TbCatModel();
    			if(!$TbCat->create($data)) {
    				// 验证不通过
    				$this->error($TbCat->getError());
    			}else {
    				// 验证成功
    				$res_add=$TbCat->add($data);
    				if($res_add!==false) {
    					$this->success('新增淘宝商品分类成功！',U('index'));
    				}else {
    					$this->error('操作失败！');
    				}
    			}
    		}else {
    			$this->error('商品分类ID、名称不能为空！');
    		}
    	}else {
    		$this->display();
    	}
    }
    
    //编辑淘宝商品分类
    public function edit($id)
    {
    	//获取淘宝官方商品分类信息
    	$TbCat=new \Common\Model\TbCatModel();
    	$msg=$TbCat->getCatMsg2($id);
    	
    	if($_POST) {
    		layout(false);
    		if(trim(I('post.category_id')) and trim(I('post.category_name')))
    		{
    			//保存到数据库
    			$data=array(
    					'category_id'=>trim(I('post.category_id')),
    					'category_name'=>trim(I('post.category_name')),
    			);
    			if(!$TbCat->create($data)) {
    				// 验证不通过
    				$this->error($TbCat->getError());
    			}else {
    				// 验证成功
    			    $res_edit=$TbCat->where("id='$id'")->save($data);
    				if($res_edit!==false) {
    					$this->success('编辑淘宝商品分类成功！',U('index'));
    				}else {
    					$this->error('操作失败！');
    				}
    			}
    		}else {
    			$this->error('商品分类ID、名称不能为空！');
    		}
    	}else {
    	    $this->assign('msg',$msg);
    	    
    		$this->display();
    	}
    }
    
    //删除商品分类
    public function del($category_id)
    {
    	$TbCat=new \Common\Model\TbCatModel();
    	$res_del=$TbCat->where("category_id='$category_id'")->delete();
    	if($res_del!==false) {
    		echo '1';
    	}else {
    		echo '0';
    	}
    }
}