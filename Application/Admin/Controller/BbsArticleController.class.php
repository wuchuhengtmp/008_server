<?php
/**
 * 论坛管理-帖子管理
 */
namespace Admin\Controller;
use Admin\Common\Controller\AuthController;
class BbsArticleController extends AuthController
{
    public function index()
    {
    	//获取版块列表
    	$BbsBoard=new \Common\Model\BbsBoardModel();
    	$boardlist=$BbsBoard->getList();
    	$this->assign('boardlist',$boardlist);
    	
    	$where="is_check='Y'";
    	if(I('get.board_id'))
    	{
    		$board_id=I('get.board_id');
    		$this->assign('board_id',$board_id);
    		//分类名称
    		$BbsBoard=new \Common\Model\BbsBoardModel();
    		$boardMsg=$BbsBoard->getBoardMsg($board_id);
    		$this->assign('board_name',$boardMsg['board_name']);
    		
    		$where.=" and board_id=$board_id";
    	}
    	
    	if(I('get.search'))
    	{
    		$search=I('get.search');
    		$where.=" and title like '%$search%'";
    	}
    	$BbsArticle=new \Common\Model\BbsArticleModel();
    	$count=$BbsArticle->where($where)->count();
    	$per = 15;
    	if($_GET['p'])
    	{
    		$p=$_GET['p'];
    	}else {
    		$p=1;
    	}
    	// 分页显示输出
    	$Page=new \Common\Model\PageModel();
    	$show= $Page->show($count,$per);
    	$this->assign('page',$show);
    	
    	$articlelist = $BbsArticle->where($where)->page($p.','.$per)->order('id desc')->select();
    	$this->assign('articlelist',$articlelist);
        $this->display();
    }
    
    //待审核帖子列表
    public function checkPending()
    {
    	$where="is_check='N'";
    	 
    	//标题
    	if(trim(I('get.search')))
    	{
    		$search=trim(I('get.search'));
    		$where.=" and (title like '%$search%' or mob_text like '%$search%')";
    	}
    	$BbsArticle=new \Common\Model\BbsArticleModel();
    	$count=$BbsArticle->where($where)->count();
    	$per = 15;
    	if($_GET['p'])
    	{
    		$p=$_GET['p'];
    	}else {
    		$p=1;
    	}
    	// 分页显示输出
    	$Page=new \Common\Model\PageModel();
    	$show= $Page->show($count,$per);
    	$this->assign('page',$show);
    
    	$list = $BbsArticle->where($where)->page($p.','.$per)->order('id desc')->select();
    	$this->assign('list',$list);
    	$this->display();
    }
    
    //已审核帖子列表
    public function checkPass()
    {
    	$where="is_check='Y' and check_result='Y'";
    	//版块
    	if(trim(I('get.board_id')))
    	{
    		$board_id=trim(I('get.board_id'));
    		$this->assign('board_id',$board_id);
    		$where.=" and board_id='$board_id'";
    	}
    	//标题
    	if(trim(I('get.search')))
    	{
    		$search=trim(I('get.search'));
    		$where.=" and (title like '%$search%' or mob_text like '%$search%')";
    	}
    	$BbsArticle=new \Common\Model\BbsArticleModel();
    	$count=$BbsArticle->where($where)->count();
    	$per = 15;
    	if($_GET['p'])
    	{
    		$p=$_GET['p'];
    	}else {
    		$p=1;
    	}
    	// 分页显示输出
    	$Page=new \Common\Model\PageModel();
    	$show= $Page->show($count,$per);
    	$this->assign('page',$show);
    
    	$list = $BbsArticle->where($where)->page($p.','.$per)->order('id desc')->select();
    	$this->assign('list',$list);
    	
    	//获取文章分类列表
    	$BbsBoard=new \Common\Model\BbsBoardModel();
    	$catlist=$BbsBoard->getList();
    	$this->assign('catlist',$catlist);
    	
    	$this->display();
    }
    
    //审核不通过帖子列表
    public function checkRefused()
    {
    	$where="is_check='Y' and check_result='N'";
    
    	//标题
    	if(trim(I('get.search')))
    	{
    		$search=trim(I('get.search'));
    		$where.=" and (title like '%$search%' or mob_text like '%$search%')";
    	}
    	$BbsArticle=new \Common\Model\BbsArticleModel();
    	$count=$BbsArticle->where($where)->count();
    	$per = 15;
    	if($_GET['p'])
    	{
    		$p=$_GET['p'];
    	}else {
    		$p=1;
    	}
    	// 分页显示输出
    	$Page=new \Common\Model\PageModel();
    	$show= $Page->show($count,$per);
    	$this->assign('page',$show);
    
    	$list = $BbsArticle->where($where)->page($p.','.$per)->order('id desc')->select();
    	$this->assign('list',$list);
    	$this->display();
    }
    
    //发帖
    Public function add($board_id='')
    {
    	$this->assign('board_id',$board_id);
    	
    	//获取版块列表
    	$BbsBoard=new \Common\Model\BbsBoardModel();
    	$boardlist=$BbsBoard->getList();
    	$this->assign('boardlist',$boardlist);
    	
    	if(I('post.'))
    	{
    		layout(false);
    		if(I('post.board_id') and trim(I('post.username')))
    		{
    			//所属用户
    			$username=trim(I('post.username'));
    			$User=new \Common\Model\UserModel();
    			$res_user=$User->where("username='$username' or phone='$username' or email='$username'")->find();
    			if($res_user['uid'])
    			{
    				$user_id=$res_user['uid'];
    			}else {
    				$this->error('发布用户不存在！');
    			}
    			
    			$content=$_POST['content'];
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
    						'rootPath'      =>  './Public/Upload/BbsArticle/', //保存根路径
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
    			//上传内容图片
    			if(!empty($_FILES['imglist']['name'][0]))
    			{
    				$config = array(
    						'mimes'         =>  array(), //允许上传的文件MiMe类型
    						'maxSize'       =>  1024*1024*10, //上传的文件大小限制 (0-不做限制)
    						'exts'          =>  array('jpg', 'gif', 'png', 'jpeg'), //允许上传的文件后缀
    						'rootPath'      =>  './Public/Upload/BbsArticle/mob/', //保存根路径
    						'savePath'      =>  '', //保存路径
    						'saveExt'       =>  '', //文件保存后缀，空则使用原后缀
    				);
    				$upload = new \Think\Upload($config);
    				// 上传单个文件
    				$info = $upload->upload(array($_FILES['imglist']));
    				if(!$info) {
    					// 上传错误提示错误信息
    					$this->error($upload->getError());
    				}else{
    					// 上传成功
    					// 文件完成路径
    					$Image=new \Think\Image();
    					foreach ($info as $l)
    					{
    						$filepath_list=$config['rootPath'].$l['savepath'].$l['savename'];
    						$img2[]=substr($filepath_list,1);
    					}
    					$imglist=json_encode($img2);
    				}
    			}else {
    				$imglist='';
    			}
    			//保存到数据库
    			$data=array(
    					'uid'=>$user_id,
    					'board_id'=>I('post.board_id'),
    					'title'=>I('post.title'),
    					'keyword'=>I('post.keyword'),
    					'description'=>I('post.description'),
    					'content'=>$content,
    					'mob_text'=>I('post.mob_text'),
    					'mob_img'=>$imglist,
    					'linkman'=>trim(I('post.linkman')),
    					'contact'=>trim(I('post.contact')),
    					'address'=>trim(I('post.address')),
    					'is_show'=>I('post.is_show'),
    					'is_top'=>I('post.is_top'),
    					'top_day'=>trim(I('post.top_day')),
    					'pubtime'=>date('Y-m-d H:i:s'),
    					'img'=>$img,
    					'clicknum'=>I('post.clicknum'),
    					'is_check'=>'Y',//已审核
    					'check_result'=>'Y',//审核通过
    					'check_time'=>date('Y-m-d H:i:s'),
    					'admin_id'=>$_SESSION['admin_id'],
    					'tb_gid'=>trim(I('post.tb_gid')),
    					'share_num'=>trim(I('post.share_num')),
    			);
    			$BbsArticle=new \Common\Model\BbsArticleModel();
    			if(!$BbsArticle->create($data))
    			{
    				// 如果创建失败 表示验证没有通过 输出错误提示信息
    				// 删除图片
    				if($filepath)
    				{
    					@unlink($filepath);
    				}
    				if($img2)
    				{
    					$num=count($img2);
    					for ($i=0;$i<$num;$i++)
    					{
    						$tmp='.'.$img2[$i];
    						@unlink($tmp);
    					}
    				}
    				$this->error($BbsArticle->getError());
    			}else {
    				// 验证成功
    				$res=$BbsArticle->add($data);
    				if($res!==false)
    				{
    					// 添加成功
    					$this->success('发布帖子成功！',U('checkPass'));
    				}else {
    					//删除文件
    					if($filepath)
    					{
    						@unlink($filepath);
    					}
    					if($img2)
    					{
    						$num=count($img2);
    						for ($i=0;$i<$num;$i++)
    						{
    							$tmp='.'.$img2[$i];
    							@unlink($tmp);
    						}
    					}
    					$this->error('操作失败！');
    				}
    			}
    		}else {
    			$this->error('所属版块、发布用户不能为空！');
    		}
    	}else {
    		$this->display();
    	}
    }
    
    //编辑帖子
    Public function edit($id)
    {
    	//获取版块列表
    	$BbsBoard=new \Common\Model\BbsBoardModel();
    	$boardlist=$BbsBoard->getList();
    	$this->assign('boardlist',$boardlist);
    	
    	//获取帖子信息
    	$BbsArticle=new \Common\Model\BbsArticleModel();
    	$msg=$BbsArticle->getArticleMsg($id);
    	$this->assign('msg',$msg);
    	if(I('post.'))
    	{
    		layout(false);
    		if(I('post.board_id'))
    		{
    			$content=$_POST['content'];
    			//编辑内容
    			//先上传新的内容，再删除原有内容中被删除的文件
    			if (! empty ( $content ) || !empty($_POST['oldcontent']))
    			{
    				$ueditor=new \Admin\Common\Controller\UeditorController;
    				$content=$ueditor->edit($content,$_POST['oldcontent']);
    			}
    			//上传标题图片
    			if(!empty($_FILES['img']['name']))
    			{
    				$config = array(
    						'mimes'         =>  array(), //允许上传的文件MiMe类型
    						'maxSize'       =>  1024*1024*4, //上传的文件大小限制 (0-不做限制)
    						'exts'          =>  array('jpg', 'gif', 'png', 'jpeg'), //允许上传的文件后缀
    						'rootPath'      =>  './Public/Upload/BbsArticle/', //保存根路径
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
    			//上传内容图片
    			if(!empty($_FILES['imglist']['name'][0]))
    			{
    				$config = array(
    						'mimes'         =>  array(), //允许上传的文件MiMe类型
    						'maxSize'       =>  1024*1024*10, //上传的文件大小限制 (0-不做限制)
    						'exts'          =>  array('jpg', 'gif', 'png', 'jpeg'), //允许上传的文件后缀
    						'rootPath'      =>  './Public/Upload/BbsArticle/mob/', //保存根路径
    						'savePath'      =>  '', //保存路径
    						'saveExt'       =>  '', //文件保存后缀，空则使用原后缀
    				);
    				$upload = new \Think\Upload($config);
    				// 上传单个文件
    				$info = $upload->upload(array($_FILES['imglist']));
    				if(!$info) {
    					// 上传错误提示错误信息
    					$this->error($upload->getError());
    				}else{
    					// 上传成功
    					// 文件完成路径
    					$Image=new \Think\Image();
    					foreach ($info as $l)
    					{
    						$filepath_list=$config['rootPath'].$l['savepath'].$l['savename'];
    						$img2[]=substr($filepath_list,1);
    						//生成缩略图
    						//$thumb_file2 =  $config['rootPath'].$l['savepath'].'tmp_'.$l['savename'];
    						//$tmp_img2[]=substr($thumb_file2,1);
    						//$Image -> open( $filepath_list )->thumb(320,320,\Think\Image::IMAGE_THUMB_SCALE)->save($thumb_file2);
    					}
    					if($msg['mob_img']!='' and $msg['mob_img']!='null')
    					{
    						//原来已上传图片，进行合并
    						$old_img=$msg['mob_img_arr'];
    						$newimg=array_merge_recursive($old_img,$img2);
    					}else {
    						//原来没有上传图片，只进行新图片添加
    						$newimg=$img2;
    					}
    					$imglist=json_encode($newimg);
    				}
    			}else {
    				$imglist=$msg['mob_img'];
    			}
    			//保存到数据库
    			$data=array(
    					'board_id'=>I('post.board_id'),
    					'title'=>I('post.title'),
    					'keyword'=>I('post.keyword'),
    					'description'=>I('post.description'),
    					'content'=>$content,
    					'mob_text'=>I('post.mob_text'),
    					'mob_img'=>$imglist,
    					'linkman'=>trim(I('post.linkman')),
    					'contact'=>trim(I('post.contact')),
    					'address'=>trim(I('post.address')),
    					'is_show'=>I('post.is_show'),
    					'is_top'=>I('post.is_top'),
    					'top_day'=>trim(I('post.top_day')),
    					'pubtime'=>date('Y-m-d H:i:s'),
    					'img'=>$img,
    					'clicknum'=>trim(I('post.clicknum')),
    					'is_check'=>'Y',
    					'check_result'=>trim(I('post.check_result')),
    					'check_time'=>date('Y-m-d H:i:s'),
    					'check_reason'=>trim(I('post.check_reason')),
    					'admin_id'=>$_SESSION['admin_id'],
    					'tb_gid'=>trim(I('post.tb_gid')),
    					'share_num'=>trim(I('post.share_num')),
    			);
    			if(!$BbsArticle->create($data))
    			{
    				// 如果创建失败 表示验证没有通过 输出错误提示信息
    				// 删除图片
    				if($filepath)
    				{
    					@unlink($filepath);
    				}
    				if($img2)
    				{
    					$num=count($img2);
    					for ($i=0;$i<$num;$i++)
    					{
    						$tmp='.'.$img2[$i];
    						@unlink($tmp);
    					}
    					//删除缩略图
    					/* for ($i=0;$i<$num;$i++)
    					{
    						$tmp2='.'.$tmp_img2[$i];
    						@unlink($tmp2);
    					} */
    				}
    				$this->error($BbsArticle->getError());
    			}else {
    				// 验证成功
    				$res=$BbsArticle->where("id=$id")->save($data);
    				if($res!==false)
    				{
    					// 修改成功
    					// 原图片存在，并且上传了新图片的情况下，删除原标题图片
    					if(I('post.oldimg') and $img!=I('post.oldimg'))
    					{
    						$oldimg='.'.I('post.oldimg');
    						@unlink($oldimg);
    					}
    					$this->success('修改帖子成功！',U('checkPending'));
    				}else {
    					//删除文件
    					if($filepath)
    					{
    						@unlink($filepath);
    					}
    					if($img2)
    					{
    						$num=count($img2);
    						for ($i=0;$i<$num;$i++)
    						{
    							$tmp='.'.$img2[$i];
    							@unlink($tmp);
    						}
    						//删除缩略图
    						/* for ($i=0;$i<$num;$i++)
    						{
    							$tmp2='.'.$tmp_img2[$i];
    							 @unlink($tmp2);
    						 } */
    					}
    					$this->error('操作失败！');
    				}
    			}
    		}else {
    			$this->error('所属版块不能为空！');
    		}
    	}else {
    		$this->display();
    	}
    }
    
    //删除帖子
    public function del($id)
    {
    	//删除操作
    	$BbsArticle=new \Common\Model\BbsArticleModel();
    	$res=$BbsArticle->del($id);
    	if($res)
    	{
    		echo '1';
    	}else {
    		echo '0';
    	}
    }
    
    //批量删除文章
    public function batchdel($all_id)
    {
    	$all_id=substr($all_id,0,-1);
    	$id_arr=explode(',',$all_id);
    	$num=count($id_arr);
    	$BbsArticle=new \Common\Model\BbsArticleModel();
    	for($i=0;$i<$num;$i++)
    	{
    		$id=$id_arr[$i];
    		$BbsArticle->del($id);
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
    
    //批量转移文章
    public function transfer($all_id,$board_id)
    {
    	$all_id=substr($all_id,0,-1);
    	$update="UPDATE __PREFIX__bbs_article SET board_id=$board_id WHERE id in($all_id)";
    	$res=M()->execute($update);
    	if($res)
    	{
    		echo '1';
    	}else {
    		echo '0';
    	}
    }
    
    //删除原标题图片
    public function deloldimg($id)
    {
    	$BbsArticle=new \Common\Model\BbsArticleModel();
    	$msg=$BbsArticle->getArticleMsg($id);
    	if($msg===false)
    	{
    		echo '0';
    	}else {
    		//修改img为空
    		$data=array(
    				'img'=>''
    		);
    		$res=$BbsArticle->where("id=$id")->save($data);
    		if($res)
    		{
    			if($msg['img'])
    			{
    				$oldimg='.'.$msg['img'];
    				@unlink($oldimg);
    			}
    			echo '1';
    		}else {
    			echo '0';
    		}
    	}
    }
    
    //删除内容中的图片
    public function delmobimg($img,$id)
    {
    	$BbsArticle=new \Common\Model\BbsArticleModel();
    	$msg=$BbsArticle->getArticleMsg($id);
    	if($msg)
    	{
    		$old_img=$msg['mob_img_arr'];
    		foreach( $old_img as $k=>$v)
    		{
    			if($img == $v)
    			{
    				unset($old_img[$k]);
    				//删除图片
    				$del_img='.'.$img;
    				@unlink($del_img);
    			}
    		}
    		$old_img = array_merge($old_img);
    		if(!empty($old_img))
    		{
    			$new_img=json_encode($old_img);
    		}else {
    			$new_img='';
    		}
    		$data=array(
    				'mob_img'=>$new_img
    		);
    		$res=$BbsArticle->where("id='$id'")->save($data);
    		if($res!==false)
    		{
    			echo '1';
    		}else {
    			echo '0';
    		}
    	}else {
    		echo '0';
    	}
    }
    
    //修改文章显示状态
    public function changeshow($id,$status)
    {
    	$data=array(
    			'is_show'=>$status
    	);
    	$BbsArticle=new \Common\Model\BbsArticleModel();
    	if(!$BbsArticle->create($data))
    	{
    		// 验证不通过
    		echo '0';
    	}else {
    		// 验证成功
    		$res=$BbsArticle->where("id=$id")->save($data);
    		if($res===false)
    		{
    			echo '0';
    		}else {
    			echo '1';
    		}
    	}
    }
    
    //爆款商品推送
    public function sendGoods()
    {
    	if(I('post.'))
    	{
    		layout(false);
    		if(trim(I('post.type')) and trim(I('post.goods_id')))
    		{
    			$type=trim(I('post.type'));
    			$goods_id=trim(I('post.goods_id'));
    			switch ($type)
    			{
    				//淘宝
    				case '1':
    					$value='taobao_'.$goods_id;
    					//查询淘宝商品名称
    					Vendor('tbk.tbk','','.class.php');
    					$tbk=new \tbk();
    					$res_goods=$tbk->getItemInfo($goods_id,$platform='2',$ip='');
    					if($res_goods['code']==0)
    					{
    						$content=$res_goods['data']['title'];
    					}else {
    						$this->error('淘宝商品不存在！');
    					}
    					break;
    				//京东
    				case '2':
    					$value='jingdong_'.$goods_id;
    					$url="http://jdapi.vephp.com/iteminfo?skuids=$goods_id";
    					$res_json=https_request($url);
    					$res_g=json_decode($res_json,true);
    					$content=$res_g['data'][0]['goodsName'];
    					break;
    				//拼多多
    				case '3':
    					$value='pdd_'.$goods_id;
    					//获取商品列表
    					Vendor('pdd.pdd','','.class.php');
    					$pdd=new \pdd();
    					$goods_id_str='['.$goods_id.']';
    					$res_goods=$pdd->getGoodsDetail($goods_id_str);
    					if($res_goods['code']==0)
    					{
    						$content=$res_goods['data']['goods_details']['goods_name'];
    					}else {
    						$this->error('拼多多商品不存在！');
    					}
    					break;
    				default:
    					$value='taobao_'.$goods_id;
    					//查询淘宝商品名称
    					Vendor('tbk.tbk','','.class.php');
    					$tbk=new \tbk();
    					$res_goods=$tbk->getItemInfo($goods_id,$platform='2',$ip='');
    					if($res_goods['code']==0)
    					{
    						$content=$res_goods['data']['title'];
    					}else {
    						$this->error('淘宝商品不存在！');
    					}
    					break;
    			}
    			$User=new \Common\Model\UserModel();
    			$list=$User->where("is_freeze='N'")->field('uid')->order('uid desc')->select();
    			Vendor('jpush.jpush','','.class.php');
    			$jpush=new \jpush();
    			$title='爆款商品推荐！';
    			//自定义标题
    			if(trim(I('post.title'))){
    			    $title=trim(I('post.title'));
    			}
    			//$msg_content='测试极光推送内容';
    			//自定义内容
    			if(trim(I('post.content'))){
    			    $content=trim(I('post.content'));
    			}
    			$key='goods';
    			$i=0;
    			$count=0;
    			foreach ($list as $l)
    			{
    				$count++;
    				$i++;
    				$alias[]=$l['uid'];
    				if($i==1000)
    				{
    					//一次最多推送1000个
    					$res_push=$jpush->push($alias,$title,$content,'','','',$key,$value);
    					
    					//推送完成继续推送下一批用户
    					$i=$i-1000;
    					$alias=array();
    				}
    			}
    			if($i<1000)
    			{
    				$res_push=$jpush->push($alias,$title,$content,'','','',$key,$value);
    			}
    			$this->success('成功推送：'.$count);
    		}else {
    			$this->error('商品类型、商品ID不能为空！');
    		}
    	}else {
    		$this->display();
    	}
    }
    
    //文章推送
    public function sendArticle()
    {
        if(I('post.')) {
            layout(false);
            if(trim(I('post.description')) and trim(I('post.url'))) {
                $User=new \Common\Model\UserModel();
                $list=$User->where("is_freeze='N'")->field('uid')->order('uid desc')->select();
                Vendor('jpush.jpush','','.class.php');
                $jpush=new \jpush();
                $title='好文推荐！';
                $key='article';
                $msg_content=trim(I('post.description'));
                $value=trim(I('post.url'));
                if(is_url($value)===false){
                    $this->error('文章链接不是正确的网址，请核对！');
                    exit();
                }
                $i=0;
                $count=0;
                foreach ($list as $l) {
                    $count++;
                    $i++;
                    $alias[]=$l['uid'];
                    if($i==1000) {
                        //一次最多推送1000个
                        $res_push=$jpush->push($alias,$title,$msg_content,'','','',$key,$value);
                        //推送完成继续推送下一批用户
                        $i=$i-1000;
                        $alias=array();
                    }
                }
                if($i<1000) {
                    $res_push=$jpush->push($alias,$title,$msg_content,'','','',$key,$value);
                }
                $this->success('成功推送：'.$count);
            }else {
                $this->error('文章描述、网址不能为空！');
            }
        }else {
            $this->display();
        }
    }
}
?>