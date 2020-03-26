<?php
/**
 * 商城系统-商品管理
 */
namespace Admin\Controller;
use Admin\Common\Controller\AuthController;
class GoodsController extends AuthController
{
    public function index($cat_id) 
    {
    	$this->assign('cat_id',$cat_id);
    	//分类名称
    	$GoodsCat=new \Common\Model\GoodsCatModel();
    	$catMsg=$GoodsCat->getCatMsg($cat_id);
    	$this->assign('cname',$catMsg['cat_name']);
    	//获取商品分类列表
    	$catlist=$GoodsCat->getCatList();
    	$this->assign('catlist',$catlist);
    	
    	$where="is_delete='N'"; 
    	if(trim(I('get.search')))
    	{
    		$search=I('get.search');
    		$where.=" and goods_name like '%$search%' and cat_id=$cat_id";
    	}else {
    		$where.=" and cat_id=$cat_id";
    	}
    	$Goods=new \Common\Model\GoodsModel();
    	$count=$Goods->where($where)->count();
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
    	
    	$goodslist = $Goods->where($where)->page($p.','.$per)->order('sort desc,goods_id desc')->select();
    	$this->assign('goodslist',$goodslist);
        $this->display();
    } 
    
    //添加商品
    public function add($cat_id)
    {
    	if(I('post.')) {
    		layout(false);
    		if(trim(I('post.goods_name'))) {
    			$content=$_POST['content'];
    			//新增内容
    			//转移ueditor文件：将file和img从ueditor_tmp文件夹中转移到正式目录ueditor中
    			if(!empty($content)) {
    				$ueditor=new \Admin\Common\Controller\UeditorController;
    				$content=$ueditor->add($content);
    			}
    			//上传标题图片
    			if(!empty($_FILES['img']['name'])) {
    				$config = array(
    						'mimes'         =>  array(), //允许上传的文件MiMe类型
    						'maxSize'       =>  1024*1024*4, //上传的文件大小限制 (0-不做限制)
    						'exts'          =>  array('jpg', 'gif', 'png', 'jpeg'), //允许上传的文件后缀
    						'rootPath'      =>  './Public/Upload/Goods/', //保存根路径
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
    					//生成缩略图
    					$Image=new \Think\Image();
    					$thumb_file =  $config['rootPath'].$info['savepath'].'tmp_'.$info['savename'];
    					$Image -> open( $filepath )->thumb(300,300,\Think\Image::IMAGE_THUMB_SCALE)->save($thumb_file);
    					$tmp_img=substr($thumb_file,1);
    				}
    			}else {
    				$img='';
    				$tmp_img='';
    			}
    			//上传视频
    			$video='';
    			if(!empty($_FILES['video']['name'])) {
    			    $config = array(
    			        'mimes'         =>  array(), //允许上传的文件MiMe类型
    			        'maxSize'       =>  1024*1024*10, //上传的文件大小限制 (0-不做限制)
    			        'rootPath'      =>  './Public/Upload/Goods/video/', //保存根路径
    			        'savePath'      =>  '', //保存路径
    			        'saveExt'       =>  '', //文件保存后缀，空则使用原后缀
    			    );
    			    $upload = new \Think\Upload($config);
    			    // 上传单个文件
    			    $info = $upload->uploadOne($_FILES['video']);
    			    if(!$info) {
    			        // 上传错误提示错误信息
    			        $this->error($upload->getError());
    			    }else{
    			        // 上传成功
    			        // 文件完成路径
    			        $video_filepath=$config['rootPath'].$info['savepath'].$info['savename'];
    			        $video=substr($video_filepath,1);
    			    }
    			}
    			//属性配置
    			if(I('post.is_sku')=='Y')
    			{
    				$attribute_arr=$_POST['attribute'];
    				foreach ($attribute_arr as $k=>$v)
    				{
    					$attribute_value_list=array();
    					$attribute_value_list_diy=array();
    					//属性ID
    					$attribute_id=$k;
    					//属性值列表
    					$attribute_value_list=$v;
    					//自定义属性值
    					if($_POST['attribute_diy'][$k])
    					{
    						$attribute_value_list_diy=explode('-', $_POST['attribute_diy'][$k]);
    					}
    					//合并属性值
    					$attribute_value_list=array_merge($attribute_value_list,$attribute_value_list_diy);
    					//去重
    					$attribute_value_list=array_unique($attribute_value_list);
    					//删除数组中的第一个元素
    					array_shift($attribute_value_list);
    					if($attribute_value_list)
    					{
    						$sku[]=array(
    								'attribute_id'=>$attribute_id,
    								'value_list'=>$attribute_value_list
    						);
    					}
    				}
    				$sku_str=json_encode($sku,JSON_UNESCAPED_UNICODE);
    			}else {
    				$sku_str='';
    			}
    			//保存到数据库
    			$data=array(
    				'cat_id'=>I('post.cat_id'),
    				'goods_name'=>trim(I('post.goods_name')),
    				'goods_code'=>trim(I('post.goods_code')),
    				'img'=>$img,
    				'tmp_img'=>$tmp_img,
    			    'video'=>$video,
    				'description'=>I('post.description'),
    				'content'=>$content,
    				'brand_id'=>I('post.brand_id'),
    				'is_show'=>I('post.is_show'),
    				'is_top'=>I('post.is_top'),
    				'is_sale'=>I('post.is_sale'),
    				'is_discount'=>I('post.is_discount'),
    				'sort'=>trim(I('post.sort')),
    				'clicknum'=>trim(I('post.clicknum')),
    				'old_price'=>trim(I('post.old_price')),
    				'price'=>trim(I('post.price'))*100,
    				'inventory'=>trim(I('post.inventory')),
    				'give_point'=>trim(I('post.give_point')),
    			    'deduction_point'=>trim(I('post.deduction_point')),
    				'is_sku'=>I('post.is_sku'),
    				'sku_str'=>$sku_str,
    				'virtual_volume'=>trim(I('post.virtual_volume')),
    				'createtime'=>date('Y-m-d H:i:s'),
    				'is_delete'=>'N'
    			);
    			$Goods=new \Common\Model\GoodsModel();
    			if(!$Goods->create($data)) {
    				// 验证不通过
    				// 删除图片
    			    if($filepath) {
    					@unlink($filepath);
    					@unlink($thumb_file);
    				}
    				if($video_filepath){
    				    @unlink($video_filepath);
    				}
    				$this->error($Goods->getError());
    			}else {
    				// 验证成功
    				$res_add=$Goods->add($data);
    				if($res_add!==false) {
    					$this->success('新增商品成功！',U('index',array('cat_id'=>$cat_id)));
    				}else {
    					// 删除图片
    					if($filepath) {
    					    @unlink($filepath);
    					    @unlink($thumb_file);
    					}
    					if($video_filepath){
    					    @unlink($video_filepath);
    					}
    					$this->error('新增商品失败！');
    				}
    			}
    		}else {
    			$this->error('商品名称不能为空！');
    		}
    	}else {
    	    $this->assign('cat_id',$cat_id);
    	    //获取商品分类列表
    	    $GoodsCat=new \Common\Model\GoodsCatModel();
    	    $catlist=$GoodsCat->getCatList();
    	    $this->assign('catlist',$catlist);
    	    
    	    //获取品牌列表
    	    $Brand=new \Common\Model\BrandModel();
    	    $BrandList=$Brand->getBrandList('asc','Y');
    	    $this->assign('BrandList',$BrandList);
    	    
    	    //获取商户分类属性列表
    	    $GoodsAttribute=new \Common\Model\GoodsAttributeModel();
    	    $AttributeList=$GoodsAttribute->getListDetail2($cat_id);
    	    $this->assign('AttributeList',$AttributeList);
    	    
    		$this->display();
    	}
    }
    
    //编辑商品
    public function edit($goods_id)
    {
    	//获取商品信息
    	$Goods=new \Common\Model\GoodsModel();
    	$msg=$Goods->getGoodsMsg($goods_id);
    	
    	if(I('post.')) {
    		layout(false);
    		if(trim(I('post.goods_name'))) {
    			$content=$_POST['content'];
    			//编辑内容
    			//先上传新的内容，再删除原有内容中被删除的文件
    			if (! empty ( $content ) || !empty($_POST['oldcontent'])) {
    				$ueditor=new \Admin\Common\Controller\UeditorController;
    				$content=$ueditor->edit($content,$_POST['oldcontent']);
    			}
    			//上传标题图片
    			if(!empty($_FILES['img']['name'])) {
    				$config = array(
    						'mimes'         =>  array(), //允许上传的文件MiMe类型
    						'maxSize'       =>  1024*1024*4, //上传的文件大小限制 (0-不做限制)
    						'exts'          =>  array('jpg', 'gif', 'png', 'jpeg'), //允许上传的文件后缀
    						'rootPath'      =>  './Public/Upload/Goods/', //保存根路径
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
    					//生成缩略图
    					$Image=new \Think\Image();
    					$thumb_file =  $config['rootPath'].$info['savepath'].'tmp_'.$info['savename'];
    					$Image -> open( $filepath )->thumb(300,300,\Think\Image::IMAGE_THUMB_SCALE)->save($thumb_file);
    					$tmp_img=substr($thumb_file,1);
    				}
    			}else {
    				$img=I('post.oldimg');
    				$tmp_img=$msg['tmp_img'];
    			}
    			//上传视频
    			if(!empty($_FILES['video']['name']))
    			{
    			    $config = array(
    			        'mimes'         =>  array(), //允许上传的文件MiMe类型
    			        'maxSize'       =>  1024*1024*10, //上传的文件大小限制 (0-不做限制)
    			        'rootPath'      =>  './Public/Upload/Goods/video/', //保存根路径
    			        'savePath'      =>  '', //保存路径
    			        'saveExt'       =>  '', //文件保存后缀，空则使用原后缀
    			    );
    			    $upload = new \Think\Upload($config);
    			    // 上传单个文件
    			    $info = $upload->uploadOne($_FILES['video']);
    			    if(!$info) {
    			        // 上传错误提示错误信息
    			        $this->error($upload->getError());
    			    }else{
    			        // 上传成功
    			        // 文件完成路径
    			        $video_filepath=$config['rootPath'].$info['savepath'].$info['savename'];
    			        $video=substr($video_filepath,1);
    			    }
    			}else {
    			    $video=$msg['video'];
    			}
    			//属性配置
    			if(I('post.is_sku')=='Y')
    			{
    				$attribute_arr=$_POST['attribute'];
    				foreach ($attribute_arr as $k=>$v)
    				{
    					$attribute_value_list=array();
    					$attribute_value_list_diy=array();
    					//属性ID
    					$attribute_id=$k;
    					//属性值列表
    					$attribute_value_list=$v;
    					//自定义属性值
    					if($_POST['attribute_diy'][$k])
    					{
    						$attribute_value_list_diy=explode('-', $_POST['attribute_diy'][$k]);
    					}
    					//合并属性值
    					$attribute_value_list=array_merge($attribute_value_list,$attribute_value_list_diy);
    					//去重
    					$attribute_value_list=array_unique($attribute_value_list);
    					//删除数组中的第一个元素
    					array_shift($attribute_value_list);
    					if($attribute_value_list)
    					{
    						$sku[]=array(
    								'attribute_id'=>$attribute_id,
    								'value_list'=>$attribute_value_list
    						);
    					}
    				}
    				$sku_str=json_encode($sku,JSON_UNESCAPED_UNICODE);
    			}else {
    				$sku_str='';
    			}
    			//保存到数据库
    			$data=array(
    				'cat_id'=>I('post.cat_id'),
    				'goods_name'=>trim(I('post.goods_name')),
    				'goods_code'=>trim(I('post.goods_code')),
    				'img'=>$img,
    				'tmp_img'=>$tmp_img,
    			    'video'=>$video,
    				'description'=>I('post.description'),
    				'content'=>$content,
    				'brand_id'=>I('post.brand_id'),
    				'is_show'=>I('post.is_show'),
    				'is_top'=>I('post.is_top'),
    				'is_sale'=>I('post.is_sale'),
    				'is_discount'=>I('post.is_discount'),
    				'sort'=>trim(I('post.sort')),
    				'clicknum'=>trim(I('post.clicknum')),
    				'old_price'=>trim(I('post.old_price')),
    				'price'=>trim(I('post.price'))*100,
    				'inventory'=>trim(I('post.inventory')),
    				'give_point'=>trim(I('post.give_point')),
    			    'deduction_point'=>trim(I('post.deduction_point')),
    				'is_sku'=>I('post.is_sku'),
    				'sku_str'=>$sku_str,
    				'virtual_volume'=>trim(I('post.virtual_volume')),
    				'createtime'=>date('Y-m-d H:i:s')
    			);
    			if(!$Goods->create($data)) {
    				// 验证不通过
    				// 删除图片
    				if($filepath) {
    					@unlink($filepath);
    					@unlink($thumb_file);
    				}
    				if($video_filepath){
    				    @unlink($video_filepath);
    				}
    				$this->error($Goods->getError());
    			}else {
    				// 验证成功
    				$res_add=$Goods->where("goods_id=$goods_id")->save($data);
    				if($res_add!==false) {
    					// 修改成功
    					// 原图片存在，并且上传了新图片的情况下，删除原标题图片
    					if(I('post.oldimg') and $img!=I('post.oldimg')) {
    						$oldimg='.'.I('post.oldimg');
    						@unlink($oldimg);
    						//删除缩略图
    						$oldtmp_img='.'.$msg['tmp_img'];
    						@unlink($oldtmp_img);
    					}
    					$this->success('修改商品成功！',U('index',array('cat_id'=>$msg['cat_id'])));
    				}else {
    					// 删除图片
    					if($filepath) {
    						@unlink($filepath);
    						@unlink($thumb_file);
    					}
    					if($video_filepath){
    					    @unlink($video_filepath);
    					}
    					$this->error('修改商品失败！');
    				}
    			}
    		}else {
    			$this->error('商品名称不能为空！');
    		}
    	}else {
    	    $this->assign('msg',$msg);
    	    
    	    //获取商品分类列表
    	    $GoodsCat=new \Common\Model\GoodsCatModel();
    	    $catlist=$GoodsCat->getCatList();
    	    $this->assign('catlist',$catlist);
    	    
    	    //获取品牌列表
    	    $Brand=new \Common\Model\BrandModel();
    	    $BrandList=$Brand->getBrandList('asc','Y');
    	    $this->assign('BrandList',$BrandList);
    	    
    	    //获取商户分类属性列表
    	    $GoodsAttribute=new \Common\Model\GoodsAttributeModel();
    	    $AttributeList=$GoodsAttribute->getListDetail2($msg['cat_id']);
    	    $this->assign('AttributeList',$AttributeList);
    	    
    		$this->display();
    	}
    }
    
    //删除商品
    public function del($id)
    {
    	//只做逻辑删除，不做物理删除
    	$Goods=new \Common\Model\GoodsModel();
    	$data=array(
    			'is_show'=>'N',
    			'is_delete'=>'Y'
    	);
    	$res=$Goods->where("goods_id='$id'")->save($data);
    	if($res!==false)
    	{
    		echo '1';
    	}else {
    		echo '0';
    	}
    }
    
    //彻底删除商品
    public function del2($goods_id)
    {
    	//删除商品
    	$Goods=new \Common\Model\GoodsModel();
    	$res=$Goods->del($goods_id);
    	if($res!==false)
    	{
    		echo '1';
    	}else {
    		echo '0';
    	}
    }
    
    //批量转移商品
    public function transfer($all_id,$cat_id)
    {
    	$all_id=substr($all_id,0,-1);
    	$update="UPDATE __PREFIX__goods SET cat_id=$cat_id WHERE goods_id in($all_id)";
    	$res=M()->execute($update);
    	if($res!==false)
    	{
    		echo '1';
    	}else {
    		echo '0';
    	}
    }
    
    //修改商品上下架状态
    public function changeshow($id,$status)
    {
    	$data=array(
    			'is_show'=>$status
    	);
    	$Goods=new \Common\Model\GoodsModel();
    	if(!$Goods->create($data))
    	{
    		// 验证不通过
    		echo '0';
    	}else {
    		// 验证成功
    		$res=$Goods->where("goods_id=$id")->save($data);
    		if($res===false)
    		{
    			echo '0';
    		}else {
    			echo '1';
    		}
    	}
    }
    
    //修改商品推荐状态
    public function changetop($id,$status)
    {
    	$data=array(
    			'is_top'=>$status
    	);
    	$Goods=new \Common\Model\GoodsModel();
    	if(!$Goods->create($data))
    	{
    		// 验证不通过
    		echo '0';
    	}else {
    		// 验证成功
    		$res=$Goods->where("goods_id=$id")->save($data);
    		if($res===false)
    		{
    			echo '0';
    		}else {
    			echo '1';
    		}
    	}
    }
    
    //修改商品特价状态
    public function changesale($id,$status)
    {
    	$data=array(
    			'is_sale'=>$status
    	);
    	$Goods=new \Common\Model\GoodsModel();
    	if(!$Goods->create($data))
    	{
    		// 验证不通过
    		echo '0';
    	}else {
    		// 验证成功
    		$res=$Goods->where("goods_id=$id")->save($data);
    		if($res===false)
    		{
    			echo '0';
    		}else {
    			echo '1';
    		}
    	}
    }
    
    //批量修改排序
    public function changesort($cat_id)
    {
    	$sort_array=I('post.sort');
    	$ids = implode(',', array_keys($sort_array));
    	$sql = "UPDATE __PREFIX__goods SET sort = CASE goods_id ";
    	foreach ($sort_array as $id => $sort) {
    		$sql .= sprintf("WHEN %d THEN %d ", $id, $sort);
    	}
    	$sql.= "END WHERE goods_id IN ($ids)";
    	$res = M()->execute($sql);
    	layout(false);
    	if($res===false)
    	{
    		$this->error('操作失败!');
    	}else {
    		$this->success('排序成功!');
    	}
    }
    
    //删除原视频
    public function deloldvideo($goods_id)
    {
        $Goods=new \Common\Model\GoodsModel();
        $msg=$Goods->getGoodsMsg($goods_id);
        if($msg===false) {
            echo '0';
        }else {
            $oldvideo=$msg['video'];
            //修改video为空
            $data=array(
                'video'=>''
            );
            $res_save=$Goods->where("goods_id=$goods_id")->save($data);
            if($res_save!==false) {
                $oldvideo='.'.$oldvideo;
                @unlink($oldvideo);
                echo '1';
            }else {
                echo '0';
            }
        }
    }
    
    //商品回收站
    public function recycle()
    {
    	if(I('get.search'))
    	{
    		$search=I('get.search');
    		$where="goods_name like '%$search%' and is_delete='Y'";
    	}else {
    		$where="is_delete='Y'";
    	}
    	$Goods=new \Common\Model\GoodsModel();
    	$count=$Goods->where($where)->count();
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
    	 
    	$goodslist = $Goods->where($where)->page($p.','.$per)->order('is_top desc,sort desc,goods_id desc')->select();
    	$this->assign('goodslist',$goodslist);
    	$this->display();
    }
    
    //回收站商品详情
    public function recycleMsg($goods_id)
    {
    	//获取商品分类列表
    	$GoodsCat=new \Common\Model\GoodsCatModel();
    	$catlist=$GoodsCat->getCatList();
    	$this->assign('catlist',$catlist);
    	//获取商品信息
    	$Goods=new \Common\Model\GoodsModel();
    	$msg=$Goods->getGoodsMsg($goods_id);
    	$this->assign('msg',$msg);
    	$this->display();
    }
    
    //恢复商品
    public function restore($id)
    {
    	$data=array(
    			'is_delete'=>'N',
    			'is_show'=>'Y'
    	);
    	$Goods=new \Common\Model\GoodsModel();
    	if(!$Goods->create($data))
    	{
    		// 验证不通过
    		echo '0';
    	}else {
    		// 验证成功
    		$res=$Goods->where("goods_id=$id")->save($data);
    		if($res===false)
    		{
    			echo '0';
    		}else {
    			echo '1';
    		}
    	}
    }
    
    //获取商品列表
    public function getGoodsList($cat_id)
    {
    	$Goods=new \Common\Model\GoodsModel();
    	$list=$Goods->where("cat_id='$cat_id' and is_show='Y' and is_delete='N'")->select();
    	if($list!==false)
    	{
    		echo json_encode($list);
    	}else {
    		echo '0';
    	}
    }
}