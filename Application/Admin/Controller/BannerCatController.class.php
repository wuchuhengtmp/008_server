<?php
/**
 * 其他管理-Banner/广告分类管理
 */
namespace Admin\Controller;
use Admin\Common\Controller\AuthController;
class BannerCatController extends AuthController
{
    public function index()
    {
    	//获取分类列表
    	$BannerCat=new \Common\Model\BannerCatModel();
    	$list=$BannerCat->getBannerCatList();
    	$this->assign('list',$list);
        $this->display();
    }
    
    //添加分类
    public function add()
    {
    	if($_POST) {
    		layout(false);
    		$data=array(
    				'title'=>trim(I('post.title')),
    				'createtime'=>date('Y-m-d H:i:s')
    		);
    		$BannerCat=new \Common\Model\BannerCatModel();
    		if(!$BannerCat->create($data)) {
    			// 验证不通过
    			$this->error($BannerCat->getError());
    		}else {
    			// 验证成功
    			$res_add=$BannerCat->add($data);
    			if($res_add!==false) {
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
    	$BannerCat=new \Common\Model\BannerCatModel();
    	$msg=$BannerCat->getCatMsg($id);
    	
    	if($_POST) {
    		layout(false);
    		$data=array(
    				'title'=>trim(I('post.title')),
    				'createtime'=>date('Y-m-d H:i:s')
    		);
    		if(!$BannerCat->create($data)) {
    			// 验证不通过
    			$this->error($BannerCat->getError());
    		}else {
    			// 验证成功
    			$res_edit=$BannerCat->where("id=$id")->save($data);
    			if($res_edit!==false) {
    				$this->success('编辑成功！',U('index'));
    			}else {
    				$this->error('操作失败！');
    			}
    		}
    	}else {
    	    $this->assign('msg',$msg);
    	    
    		$this->display();
    	}
    }
    
    //删除分类
    public function del($id)
    {
    	$BannerCat=new \Common\Model\BannerCatModel();
    	$res=$BannerCat->where("id=$id")->delete();
    	if($res!==false) {
    		//删除分类下的所有广告图
    		$Banner=new \Common\Model\BannerModel();
    		$res2=$Banner->where("cat_id=$id")->select();
    		if(!empty($res2)) {
    			$num=count($res2);
    			for($i=0;$i<$num;$i++) {
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
    				$res3=$Banner->where("cat_id=$id")->delete();
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
}