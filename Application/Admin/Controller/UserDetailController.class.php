<?php
/**
 * 会员管理
 * 会员详情管理
 */
namespace Admin\Controller;
use Admin\Common\Controller\AuthController;
class UserDetailController extends AuthController
{
    public function index($uid)
    {
    	$this->assign('group_id',I('get.group_id'));
    	
    	//获取会员详细信息
    	$UserDetail=new \Common\Model\UserDetailModel();
    	$msg=$UserDetail->getUserDetailMsg($uid);
    	$this->assign('msg',$msg);
    	
    	if(I('post.'))
    	{
    		layout(false);
    		//确保昵称唯一
    		$nickname=trim(I('post.nickname'));
    		$res_exist=$UserDetail->where("nickname='$nickname' and user_id!='$uid'")->find();
    		if($res_exist)
    		{
    			//该昵称已存在
    			$this->error('该昵称已存在！');
    		}else {
    			//上传头像
    			if(!empty($_FILES['img']['name']))
    			{
    				$config = array(
    						'mimes'         =>  array(), //允许上传的文件MiMe类型
    						'maxSize'       =>  1024*1024*4, //上传的文件大小限制 (0-不做限制)
    						'exts'          =>  array('jpg', 'gif', 'png', 'jpeg'), //允许上传的文件后缀
    						'rootPath'      =>  './Public/Upload/User/avatar/', //保存根路径
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
    				$img=$msg['avatar'];
    			}
    			
    			$data=array(
    					'nickname'=>$nickname,
    					'avatar'=>$img,
    					'truename'=>trim(I('post.truename')),
    					'sex'=>I('post.sex'),
    					'height'=>trim(I('post.height')),
    					'weight'=>trim(I('post.weight')),
    					'blood'=>I('post.blood'),
    					'birthday'=>trim(I('post.birthday')),
    					'qq'=>trim(I('post.qq')),
    					'weixin'=>trim(I('post.weixin')),
    					'province'=>I('post.province'),
    					'city'=>I('post.city'),
    					'county'=>I('post.county'),
    					'detail_address'=>trim(I('post.detail_address')),
    					'signature'=>trim(I('post.signature')),
    			);
    			if(!$UserDetail->create($data))
    			{
    				// 验证不通过
    				$this->error($UserDetail->getError());
    			}else {
    				// 验证成功
    				$res=$UserDetail->where("user_id=$uid")->save($data);
    				if($res!==false)
    				{
    					$this->success('编辑成功！');
    				}else {
    					$this->error('操作失败！');
    				}
    			}
    		}
    	}else {
    		$this->display();
    	}
    }
}