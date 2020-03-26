<?php
/**
 * 商城系统-品牌商管理
 */
namespace Admin\Controller;
use Admin\Common\Controller\AuthController;

class BrandController extends AuthController
{
	public function index()
	{
		if(trim(I('get.search')))
		{
		    $search=trim(I('get.search'));
			$where="name like '%$search%'";
		}else {
			$where='1';
		}
		//获取品牌商列表
		$Brand=new \Common\Model\BrandModel();
		$count=$Brand->where($where)->count();
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
		 
		$brandlist = $Brand->where($where)->page($p.','.$per)->order('sort desc,brand_id desc')->select();
		$this->assign('brandlist',$brandlist);
		$this->display();
	}
	
	//添加品牌
	public function add()
	{
		if(I('post.'))
		{
			layout(false);
			$content=$_POST['introduce'];
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
						'subName'       =>  '', //子目录创建方式，为空
						'rootPath'      =>  './Public/Upload/Brand/', //保存根路径
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
					'name'=>trim(I('post.name')),
					'logo'=>$img,
					'url'=>trim(I('post.url')),
					'contractor'=>trim(I('post.contractor')),
					'phone'=>trim(I('post.phone')),
					'address'=>trim(I('post.address')),
					'sort'=>I('post.sort'),
					'is_show'=>I('post.is_show'),
					'introduce'=>$content
			);
			$Brand=new \Common\Model\BrandModel();
			if(!$Brand->create($data))
			{
				// 如果创建失败 表示验证没有通过 输出错误提示信息
				// 删除图片
				@unlink($filepath);
				$this->error($Brand->getError());
			}else {
				// 验证成功
				$res=$Brand->add($data);
				if($res)
				{
					$this->success('新增品牌成功！',U('index'));
				}else {
					//删除文件
					@unlink($filepath);
					$this->error('操作失败！');
				}
			}
		}else {
			$this->display();
		}
	}
	
	//编辑品牌
	public function edit($brand_id)
	{
		//获取品牌信息
		$Brand=new \Common\Model\BrandModel();
		$msg=$Brand->getBrandMsg($brand_id);
		
		if($_POST) {
			layout(false);
			$content=$_POST['introduce'];
			 
			//编辑内容
			//先上传新的内容，再删除原有内容中被删除的文件
			if (! empty ( $content ) || !empty($msg['content'])) {
				$ueditor=new \Admin\Common\Controller\UeditorController;
				$content=$ueditor->edit($content,$msg['content']);
			}
			//上传标题图片
			if(!empty($_FILES['img']['name']))
			{
				$config = array(
						'mimes'         =>  array(), //允许上传的文件MiMe类型
						'maxSize'       =>  1024*1024*4, //上传的文件大小限制 (0-不做限制)
						'exts'          =>  array('jpg', 'gif', 'png', 'jpeg'), //允许上传的文件后缀
						'subName'       =>  '', //子目录创建方式，为空
						'rootPath'      =>  './Public/Upload/Brand/', //保存根路径
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
				$img=$msg['logo'];
			}
			//保存到数据库
			$data=array(
					'name'=>trim(I('post.name')),
					'logo'=>$img,
					'url'=>trim(I('post.url')),
					'contractor'=>trim(I('post.contractor')),
					'phone'=>trim(I('post.phone')),
					'address'=>trim(I('post.address')),
					'sort'=>I('post.sort'),
					'is_show'=>I('post.is_show'),
					'introduce'=>$content
			);
			if(!$Brand->create($data)) {
				// 验证不通过
				// 删除图片
				if($filepath){
				    @unlink($filepath);
				}
				$this->error($Brand->getError());
			}else {
				// 验证成功
				$res_edit=$Brand->where("brand_id='$brand_id'")->save($data);
				if($res_edit!==false) {
					// 修改成功
					// 原图片存在，并且上传了新图片的情况下，删除原标题图片
				    if( $msg['logo'] and $img!=$msg['logo'] ) {
				        $oldimg='.'.$msg['logo'];
						@unlink($oldimg);
					}
					$this->success('修改品牌成功！');
				}else {
					//删除文件
				    if($filepath){
				        @unlink($filepath);
				    }
					$this->error('操作失败！');
				}
			}
		}else {
		    $this->assign('msg',$msg);
		    
			$this->display();
		}
	}
	
	//删除品牌
	public function del($brand_id)
	{
		// 删除操作
		$Brand=new \Common\Model\BrandModel();
		$res=$Brand->del($brand_id);
		if($res)
		{
			echo '1';
		}else {
			echo '0';
		}
	}
	
	//批量删除品牌
	public function batchdel($all_id)
	{
		$all_id=substr($all_id,0,-1);
		$id_arr=explode(',',$all_id);
		$num=count($id_arr);
		$Brand=new \Common\Model\BrandModel();
		for($i=0;$i<$num;$i++)
		{
			$brand_id=$id_arr[$i];
			$Brand->del($brand_id);
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
	
	//修改品牌显示状态
	public function changeshow($brand_id,$status)
	{
		$data=array(
				'is_show'=>$status
		);
		$Brand=new \Common\Model\BrandModel();
		if(!$Brand->create($data)) {
			// 验证不通过
			echo '0';
		}else {
			// 验证成功
			$res=$Brand->where("brand_id=$brand_id")->save($data);
			if($res===false) {
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
		$sql = "UPDATE __PREFIX__brand SET sort = CASE brand_id ";
		foreach ($sort_array as $id => $sort) {
			$sql .= sprintf("WHEN %d THEN %d ", $id, $sort);
		}
		$sql.= "END WHERE brand_id IN ($ids)";
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