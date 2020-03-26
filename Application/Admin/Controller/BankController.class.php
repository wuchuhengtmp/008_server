<?php
/**
 * 银行管理
 */
namespace Admin\Controller;
use Admin\Common\Controller\AuthController;
class BankController extends AuthController
{
	public function index()
	{
		//获取银行列表
		$Bank=new \Common\Model\BankModel();
		$list=$Bank->getBankList();
		$this->assign('list',$list);
		$this->display();
	}
	
	//添加银行
	public function add()
	{
		if(I('post.'))
		{
			layout(false);
			if(trim(I('post.bank_name')))
			{
				//银行名称不能重复
				$bank_name=trim(I('post.bank_name'));
				$Bank=new \Common\Model\BankModel();
				$res_exist=$Bank->where("bank_name='$bank_name'")->field('bank_id')->find();
				if($res_exist['bank_id'])
				{
					$this->error('该银行已存在，请勿重复添加！');
				}else {
					//上传银行logo
					if(!empty($_FILES['img']['name']))
					{
						$config = array(
								'mimes'         =>  array(), //允许上传的文件MiMe类型
								'maxSize'       =>  1024*1024*4, //上传的文件大小限制 (0-不做限制)
								'exts'          =>  array('jpg', 'gif', 'png', 'jpeg'), //允许上传的文件后缀
								'rootPath'      =>  './Public/Upload/Bank/', //保存根路径
								'savePath'      =>  '', //保存路径
								'saveExt'       =>  '', //文件保存后缀，空则使用原后缀
								'subName'       =>  '', //子目录创建方式
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
					//上传背景图
					if(!empty($_FILES['bg_img']['name']))
					{
						$config = array(
								'mimes'         =>  array(), //允许上传的文件MiMe类型
								'maxSize'       =>  1024*1024*4, //上传的文件大小限制 (0-不做限制)
								'exts'          =>  array('jpg', 'gif', 'png', 'jpeg'), //允许上传的文件后缀
								'rootPath'      =>  './Public/Upload/Bank/', //保存根路径
								'savePath'      =>  '', //保存路径
								'saveExt'       =>  '', //文件保存后缀，空则使用原后缀
								'subName'       =>  '', //子目录创建方式
						);
						$upload = new \Think\Upload($config);
						// 上传单个文件
						$info = $upload->uploadOne($_FILES['bg_img']);
						if(!$info) {
							// 上传错误提示错误信息
							$this->error($upload->getError());
						}else{
							// 上传成功
							// 文件完成路径
							$bg_filepath=$config['rootPath'].$info['savepath'].$info['savename'];
							$bg_img=substr($bg_filepath,1);
						}
					}
					//保存到数据库
					$data=array(
							'bank_name'=>$bank_name,
							'icon'=>$img,
							'bg_img'=>$bg_img,
							'sort'=>I('post.sort'),
							'is_show'=>I('post.is_show'),
					);
					if(!$Bank->create($data))
					{
						// 验证不通过
						// 删除图片
						if($filepath)
						{
							@unlink($filepath);
						}
						if($bg_filepath)
						{
							@unlink($bg_filepath);
						}
						$this->error($Bank->getError());
					}else {
						// 验证成功
						$res=$Bank->add($data);
						if($res!==false)
						{
							$this->success('新增银行成功！',U('index'));
						}else {
							// 删除图片
							if($filepath)
							{
								@unlink($filepath);
							}
							if($bg_filepath)
							{
								@unlink($bg_filepath);
							}
							$this->error('操作失败！');
						}
					}
				}
			}else {
				$this->error('银行名称不能为空！');
			}
		}else {
			$this->display();
		}
	}
	
	//编辑银行
	public function edit($bank_id)
	{
		//获取银行信息
		$Bank=new \Common\Model\BankModel();
		$msg=$Bank->getBankMsg($bank_id);
		$this->assign('msg',$msg);
		
		if(I('post.'))
		{
			layout(false);
			if(trim(I('post.bank_name')))
			{
				//银行名称不能重复
				$bank_name=trim(I('post.bank_name'));
				$res_exist=$Bank->where("bank_name='$bank_name' and bank_id!='$bank_id'")->field('bank_id')->find();
				if($res_exist['bank_id'])
				{
					$this->error('该银行已存在，请勿重复添加！');
				}else {
					//上传银行logo
					if(!empty($_FILES['img']['name']))
					{
						$config = array(
								'mimes'         =>  array(), //允许上传的文件MiMe类型
								'maxSize'       =>  1024*1024*4, //上传的文件大小限制 (0-不做限制)
								'exts'          =>  array('jpg', 'gif', 'png', 'jpeg'), //允许上传的文件后缀
								'rootPath'      =>  './Public/Upload/Bank/', //保存根路径
								'savePath'      =>  '', //保存路径
								'saveExt'       =>  '', //文件保存后缀，空则使用原后缀
								'subName'       =>  '', //子目录创建方式
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
						$img=$msg['icon'];
					}
					//上传背景图
					if(!empty($_FILES['bg_img']['name']))
					{
						$config = array(
								'mimes'         =>  array(), //允许上传的文件MiMe类型
								'maxSize'       =>  1024*1024*4, //上传的文件大小限制 (0-不做限制)
								'exts'          =>  array('jpg', 'gif', 'png', 'jpeg'), //允许上传的文件后缀
								'rootPath'      =>  './Public/Upload/Bank/', //保存根路径
								'savePath'      =>  '', //保存路径
								'saveExt'       =>  '', //文件保存后缀，空则使用原后缀
								'subName'       =>  '', //子目录创建方式
						);
						$upload = new \Think\Upload($config);
						// 上传单个文件
						$info = $upload->uploadOne($_FILES['bg_img']);
						if(!$info) {
							// 上传错误提示错误信息
							$this->error($upload->getError());
						}else{
							// 上传成功
							// 文件完成路径
							$bg_filepath=$config['rootPath'].$info['savepath'].$info['savename'];
							$bg_img=substr($bg_filepath,1);
						}
					}else {
						$bg_img=$msg['bg_img'];
					}
					//保存到数据库
					$data=array(
							'bank_name'=>$bank_name,
							'icon'=>$img,
							'bg_img'=>$bg_img,
							'sort'=>I('post.sort'),
							'is_show'=>I('post.is_show'),
					);
					if(!$Bank->create($data))
					{
						// 验证不通过
						// 删除图片
						if($filepath)
						{
							@unlink($filepath);
						}
						if($bg_filepath)
						{
							@unlink($bg_filepath);
						}
						$this->error($Bank->getError());
					}else {
						// 验证成功
						$res=$Bank->where("bank_id='$bank_id'")->save($data);
						if($res!==false)
						{
							// 原图片存在，并且上传了新图片的情况下，删除原标题图片
							if($msg['icon'] and $img!=$msg['icon'])
							{
								$oldimg='.'.$msg['icon'];
								@unlink($oldimg);
							}
							if($msg['bg_img'] and $bg_img!=$msg['bg_img'])
							{
								$old_bgimg='.'.$msg['bg_img'];
								@unlink($old_bgimg);
							}
							$this->success('编辑银行成功！',U('index'));
						}else {
							// 删除图片
							if($filepath)
							{
								@unlink($filepath);
							}
							if($bg_filepath)
							{
								@unlink($bg_filepath);
							}
							$this->error('操作失败！');
						}
					}
				}
			}else {
				$this->error('银行名称不能为空！');
			}
		}else {
			$this->display();
		}
	}
	
	//修改显示状态
	public function changeStatus($id,$status)
	{
		$data=array(
				'is_show'=>$status
		);
		$Bank=new \Common\Model\BankModel();
		if(!$Bank->create($data))
		{
			// 验证不通过
			echo '0';
		}else {
			// 验证成功
			$res=$Bank->where("bank_id='$id'")->save($data);
			if($res===false)
			{
				echo '0';
			}else {
				echo '1';
			}
		}
	}
	
	//删除银行
	public function del($bank_id)
	{
		//先判断银行下是否存在账号
		$BankAccount=new \Common\Model\BankAccountModel();
		$res_exist=$BankAccount->where("bank_id='$bank_id'")->count();
		if($res_exist>0)
		{
			//该银行下已存在用户银行账号，不准直接删除
			echo '2';
		}else {
			//删除银行
			$Bank=new \Common\Model\BankModel();
			$msg=$Bank->getBankMsg($bank_id);
			if($msg)
			{
				$res=$Bank->where("bank_id='$bank_id'")->delete();
				if($res!==false)
				{
					//删除图片
					if($msg['icon'])
					{
						$icon='.'.$msg['icon'];
						@unlink($icon);
					}
					if($msg['bg_img'])
					{
						$bg_img='.'.$msg['bg_img'];
						@unlink($bg_img);
					}
					echo '1';
				}else {
					echo '0';
				}
			}else {
				echo '0';
			}
		}
	}
	
	//批量修改排序
	public function changesort()
	{
		$sort_array=I('post.sort');
		$ids = implode(',', array_keys($sort_array));
		$sql = "UPDATE __PREFIX__bank SET sort = CASE bank_id ";
		foreach ($sort_array as $id => $sort) {
			$sql .= sprintf("WHEN %d THEN %d ", $id, $sort);
		}
		$sql.= "END WHERE bank_id IN ($ids)";
		$res = M()->execute($sql);
		layout(false);
		if($res===false)
		{
			$this->error('操作失败!');
		}else {
			$this->success('排序成功!',U('index'),3);
		}
	}
	
	//删除原银行logo
	public function deloldimg($bank_id)
	{
		$Bank=new \Common\Model\BankModel();
		$msg=$Bank->where("bank_id='$bank_id'")->find();
		if($msg===false)
		{
			echo '0';
		}else {
			//修改img为空
			$data=array(
					'icon'=>''
			);
			$res=$Bank->where("bank_id='$bank_id'")->save($data);
			if($res)
			{
				if($msg['icon'])
				{
					$oldimg='.'.$msg['icon'];
					@unlink($oldimg);
				}
				echo '1';
			}else {
				echo '0';
			}
		}
	}
}
?>