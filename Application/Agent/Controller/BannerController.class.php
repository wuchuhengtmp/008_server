<?php
/**
 * Banner/广告管理
 */
namespace Agent\Controller;
use Agent\Common\Controller\AuthController;
class BannerController extends AuthController
{
    public function index()
    {
        $cat_id=1;
        if(trim(I('get.cat_id'))){
            $cat_id=trim(I('get.cat_id'));
        }
        $this->assign('cat_id',$cat_id);
        
        $agent_id=$_SESSION['agent_id'];
        $where="agent_id=$agent_id and cat_id=$cat_id";
        //根据分类ID获取链接列表
        $Banner=new \Common\Model\BannerModel();
        $hlist=$Banner->where($where)->order('sort desc,id asc')->select();
        $this->assign('hlist',$hlist);
        
        if($cat_id==1){
            $cat_name='首页广告图';
            $this->assign('cat_name',$cat_name);
            $this->display();
        }else {
            $cat_name='分享海报';
            $this->assign('cat_name',$cat_name);
            $this->display('index2');
        }
    }
    
    //添加banner/广告图
    public function add($cat_id)
    {
    	if($_POST) {
    		layout(false);
    		//上传文件
    		if(!empty($_FILES['img']['name']))
    		{
    			$config = array(
    					'mimes'         =>  array(), //允许上传的文件MiMe类型
    					'maxSize'       =>  1024*1024*4, //上传的文件大小限制 (0-不做限制)
    					'exts'          =>  array('jpg', 'gif', 'png', 'jpeg'), //允许上传的文件后缀
    					'rootPath'      =>  './Public/Upload/Banner/', //保存根路径
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
    			'cat_id'=>$cat_id,
    			'title'=>trim(I('post.title')),
    			'href'=>trim(I('post.href')),
    			'sort'=>trim(I('post.sort')),
    			'img'=>$img,
    		    'color'=>trim(I('post.color')),
    			'is_show'=>trim(I('post.is_show')),
    			'type'=>trim(I('post.type')),
    			'type_value'=>trim(I('post.type_value')),
    			'createtime'=>date('Y-m-d H:i:s'),
    		    'agent_id'=>$_SESSION['agent_id']
    		);
    		$Banner=new \Common\Model\BannerModel();
    		if(!$Banner->create($data)) {
    			// 验证不通过
    			// 删除图片
    			if($filepath){
    			    @unlink($filepath);
    			}
    			$this->error($Banner->getError());
    		}else {
    			// 验证成功
    			$res_add=$Banner->add($data);
    			if($res_add!==false) {
    				$this->success('新增图片成功！',U('index',array('cat_id'=>$cat_id)));
    			}else {
    				//删除图片
    			    if($filepath){
    			        @unlink($filepath);
    			    }
    				$this->error('操作失败！');
    			}
    		}
    	}else {
    	    $this->assign('cat_id',$cat_id);
    		if($cat_id==1){
    		    $cat_name='首页广告图';
    		    $this->assign('cat_name',$cat_name);
    		    $this->display();
    		}else {
    		    $cat_name='分享海报';
    		    $this->assign('cat_name',$cat_name);
    		    $this->display('add2');
    		}
    	}
    }
    
    //编辑banner/广告图
    public function edit($id)
    {
    	//根据ID获取图片信息
    	$Banner=new \Common\Model\BannerModel();
    	$msg=$Banner->getBannerMsg($id);
    	
    	if($_POST) {
    		layout(false);
    		//上传文件
    		if(!empty($_FILES['img']['name']))
    		{
    			$config = array(
    					'mimes'         =>  array(), //允许上传的文件MiMe类型
    					'maxSize'       =>  1024*1024*4, //上传的文件大小限制 (0-不做限制)
    					'exts'          =>  array('jpg', 'gif', 'png', 'jpeg'), //允许上传的文件后缀
    					'rootPath'      =>  './Public/Upload/Banner/', //保存根路径
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
    			$img=$msg['img'];
    		}
    		//保存到数据库
    		$data=array(
    			'title'=>trim(I('post.title')),
    			'href'=>trim(I('post.href')),
    			'sort'=>trim(I('post.sort')),
    			'img'=>$img,
    		    'color'=>trim(I('post.color')),
    			'is_show'=>trim(I('post.is_show')),
    			'type'=>trim(I('post.type')),
    			'type_value'=>trim(I('post.type_value')),
    			'createtime'=>date('Y-m-d H:i:s')
    		);
    		if(!$Banner->create($data)) {
    			// 验证不通过
    			// 删除图片
    			if($filepath){
    			    @unlink($filepath);
    			}
    			$this->error($Banner->getError());
    		}else {
    			// 验证成功
    			$res_edit=$Banner->where("id=$id")->save($data);
    			if($res_edit!==false) {
    				// 修改成功
    				// 原图片存在，并且上传了新图片的情况下，删除原标题图片
    			    if($msg['img'] and $img!=$msg['img']) {
    				    $oldimg='.'.$msg['img'];
    					@unlink($oldimg);
    				}
    				$this->success('修改图片成功！',U('index',array('cat_id'=>$msg['cat_id'])));
    			}else {
    				//删除图片
    			    if($filepath){
    			        @unlink($filepath);
    			    }
    				$this->error('操作失败！');
    			}
    		}
    	}else {
    	    $this->assign('msg',$msg);
    	    
    		if($msg['cat_id']==1){
    		    $cat_name='首页广告图';
    		    $this->assign('cat_name',$cat_name);
    		    $this->display();
    		}else {
    		    $cat_name='分享海报';
    		    $this->assign('cat_name',$cat_name);
    		    $this->display('edit2');
    		}
    	}
    }
    
    //删除banner/广告图
    public function del($id)
    {
    	$Banner=new \Common\Model\BannerModel();
    	$msg=$Banner->getBannerMsg($id);
    	$res_del=$Banner->where("id=$id")->delete();
    	if($res_del!==false) {
    		//删除图片
    	    if(!empty($msg['img'])) {
    	        $img='.'.$msg['img'];
    			@unlink($img);
    		}
    		echo '1';
    	}else {
    		echo '0';
    	}
    }
    
    //批量删除banner/广告图
    public function batchdel($all_id)
    {
    	$all_id=substr($all_id,0,-1);
    	$id_arr=explode(',',$all_id);
    	$num=count($id_arr);
    	$Banner=new \Common\Model\BannerModel();
    	for($i=0;$i<$num;$i++)
    	{
    		$id=$id_arr[$i];
    		$res1=$Banner->getBannerMsg($id);
    		$img=$res1['img'];
    		$res=$Banner->where("id=$id")->delete();
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
    	$sql = "UPDATE __PREFIX__banner SET sort = CASE id ";
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
    
    //修改显示状态
    public function changeshow($id,$status)
    {
    	$data=array(
    			'is_show'=>$status
    	);
    	$Banner=new \Common\Model\BannerModel();
    	if(!$Banner->create($data)) {
    		// 验证不通过
    		echo '0';
    	}else {
    		// 验证成功
    		$res=$Banner->where("id=$id")->save($data);
    		if($res===false) {
    			echo '0';
    		}else {
    			echo '1';
    		}
    	}
    }
}
?>