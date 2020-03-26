<?php
/**
 * 友情链接分类管理
 */
namespace Admin\Controller;
use Admin\Common\Controller\AuthController;
class HrefCatController extends AuthController
{
    public function index()
    {
    	//获取分类列表
    	$HrefCat=new \Common\Model\HrefCatModel();
    	$list=$HrefCat->getHrefCatList();
    	$this->assign('list',$list);
        $this->display();
    }
    
    //添加分类
    public function add()
    {
    	if(I('post.'))
    	{
    		layout(false);
    		$data=array(
    				'title'=>trim(I('post.title')),
    				'sort'=>trim(I('post.sort')),
    				'createtime'=>date('Y-m-d H:i:s')
    		);
    		$HrefCat=new \Common\Model\HrefCatModel();
    		if(!$HrefCat->create($data))
    		{
    			// 如果创建失败 表示验证没有通过 输出错误提示信息
    			$this->error($HrefCat->getError());
    		}else {
    			// 验证成功
    			$res=$HrefCat->add($data);
    			if($res!==false)
    			{
    				$this->success('添加成功！');
    			}else {
    				$this->error('操作失败！');
    			}
    		}
    	}
    }
    
    //编辑分类
    public function edit($id)
    {
    	//根据ID获取分类信息
    	$HrefCat=new \Common\Model\HrefCatModel();
    	$msg=$HrefCat->getCatMsg($id);
    	$this->assign('msg',$msg);
    	if(I('post.'))
    	{
    		layout(false);
    		$data=array(
    				'title'=>trim(I('post.title')),
    				'sort'=>trim(I('post.sort')),
    				'createtime'=>date('Y-m-d H:i:s')
    		);
    		if(!$HrefCat->create($data))
    		{
    			// 如果创建失败 表示验证没有通过 输出错误提示信息
    			$this->error($HrefCat->getError());
    		}else {
    			// 验证成功
    			$res=$HrefCat->where("id=$id")->save($data);
    			if($res!==false)
    			{
    				$this->success('编辑成功！');
    			}else {
    				$this->error('操作失败！');
    			}
    		}
    	}else {
    		$this->display();
    	}
    }
    
    //删除分类
    public function del($id)
    {
    	$HrefCat=new \Common\Model\HrefCatModel();
    	$res=$HrefCat->where("id=$id")->delete();
    	if($res!==false)
    	{
    		//删除分类下的所有链接
    		$Href=new \Common\Model\HrefModel();
    		$res2=$Href->where("cat_id=$id")->select();
    		if(!empty($res2))
    		{
    			$num=count($res2);
    			for($i=0;$i<$num;$i++)
    			{
    				$img=$res2[$i]['img'];
    				if(!empty($img))
    				{
    					$img='.'.$img;
    					unlink($img);
    				}
    				$a.='a';
    			}
    			$a=$a.'true';
    			$str=str_repeat('a',$num).'true';
    			if($str==$a)
    			{
    				$res3=$Href->where("cat_id=$id")->delete();
    				if($res3!=false)
    				{
    					echo '1';
    				}else {
    					echo '0';
    				}
    			}
    		}else {
    			echo '1';
    		}
    	}else {
    		echo '0';
    	}
    }
    
    //批量修改排序
    public function changesort()
    {
    	$sort_array=I('post.sort');
    	$ids = implode(',', array_keys($sort_array));
    	$sql = "UPDATE __PREFIX__href_cat SET sort = CASE id ";
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