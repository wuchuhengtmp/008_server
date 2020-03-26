<?php
/**
 * 商品管理-商品SKU配置管理
 */
namespace Admin\Controller;
use Admin\Common\Controller\AuthController;
class GoodsSkuController extends AuthController
{
	public function set($goods_id)
	{
		
		//获取商品属性值列表
		$Goods=new \Common\Model\GoodsModel();
		$GoodsMsg=$Goods->getGoodsDetail($goods_id);
		$this->assign('GoodsMsg',$GoodsMsg);
		//属性值排列组合数组
		$attribute_num=0;
		foreach ($GoodsMsg['sku_arr'] as $l)
		{
			if($l['value_list'])
			{
				$attribute_value_arr[]=$l['value_list'];
				$attribute_num++;
			}
		}
		$attribute_value_arr=getArrSet($attribute_value_arr);
		$this->assign('attribute_value_arr',$attribute_value_arr);
		//属性类别数量
		//$attribute_num=count($GoodsMsg['sku_arr']);
		$this->assign('attribute_num',$attribute_num);
		
		//已配置SKU列表
		$GoodsSku=new \Common\Model\GoodsSkuModel();
		$skuList=$GoodsSku->getSkuList($goods_id);
		$this->assign('skuList',$skuList);
		
		if(I('post.')) {
			layout(false);
			//价格
			$price_arr=$_POST['price'];
			$inventory_arr=$_POST['inventory'];
			$give_point_arr=$_POST['give_point'];
			$deduction_point_arr=$_POST['deduction_point'];
			$num=count($price_arr);
			$config = array(
					'mimes'         =>  array(), //允许上传的文件MiMe类型
					'maxSize'       =>  1024*1024*4, //上传的文件大小限制 (0-不做限制)
					'exts'          =>  array('jpg', 'gif', 'png', 'jpeg'), //允许上传的文件后缀
					'rootPath'      =>  './Public/Upload/GoodsSku/', //保存根路径
					'savePath'      =>  '', //保存路径
					'saveExt'       =>  '', //文件保存后缀，空则使用原后缀
			);
			$upload = new \Think\Upload($config);
			//已上传图片数组
			$uoload_img=array();
			for($i=0;$i<$num;$i++)
			{
				$sku_arr=array();
				for ($j=0;$j<$attribute_num;$j++) {
					$sku_arr[]=array(
							'attribute_id'=>$GoodsMsg['sku_arr'][$j]['attribute_id'],
							'value'=>$attribute_value_arr[$i][$j]
					);
				}
				if($price_arr[$i]>0)
				{
					//价格
					$price=$price_arr[$i];
					//库存
					$inventory=$inventory_arr[$i];
					//赠送积分
					$give_point=$give_point_arr[$i];
					//可抵扣积分
					$deduction_point=$deduction_point_arr[$i];
					//上传图片
					$img='';
					if(!empty($_FILES['img']['name'][$i]))
					{
						$file=array(
								'name'=>$_FILES['img']['name'][$i],
								'type'=>$_FILES['img']['type'][$i],
								'tmp_name'=>$_FILES['img']['tmp_name'][$i],
								'error'=>$_FILES['img']['error'][$i],
								'size'=>$_FILES['img']['size'][$i],
						);
						// 上传单个文件
						$info = $upload->uploadOne($file);
						if(!$info) {
							// 上传错误提示错误信息
							//删除已上传图片
							foreach ($uoload_img as $k=>$tmp)
							{
								@unlink($tmp);
							}
							$this->error($upload->getError());
						}else{
							// 上传成功
							// 文件完成路径
							$filepath=$config['rootPath'].$info['savepath'].$info['savename'];
							$img=substr($filepath,1);
							//已上传图片数组
							$uoload_img[]=$filepath;
						}
					}else {
						//没有上传属性配置对应图片
						//判断该条配置是否已添加过，添加过使用原来的图片
						$sku=json_encode($sku_arr,JSON_UNESCAPED_UNICODE);
						foreach ($skuList as $sl)
						{
							if($sku==$sl['sku'])
							{
								//已配置
								$img=$sl['img'];
								//记录继续使用的属性配置图片
								$use_img[]=$sl['img'];
							}
						}
					}
					$data[]=array(
						'goods_id'=>$goods_id,
						'sku'=>json_encode($sku_arr,JSON_UNESCAPED_UNICODE),
						'price'=>$price*100,
						'inventory'=>$inventory,
						'give_point'=>$give_point,
					    'deduction_point'=>$deduction_point,
						'img'=>$img
					);
				}
			}
			//保存数据
			//先删除该商品所有的SKU配置
			$res_del=$GoodsSku->where("goods_id='$goods_id'")->delete();
			$res=$GoodsSku->addAll($data);
			if($res!==false and $res_del!==false)
			{
				//添加成功
				//删除之前配置的多余图片
				foreach ($skuList as $sl)
				{
					$old_img[]=$sl['img'];
				}
				if($old_img)
				{
					//去掉继续使用的属性配置图片
					$tmp_img=array_diff($old_img, $use_img);
					foreach ($tmp_img as $k=>$v)
					{
						$tmp='.'.$v;
						@unlink($tmp);
					}
				}
				$this->success('配置商品SKU成功！');
			}else {
				//添加失败
				//删除已上传图片
				foreach ($uoload_img as $k=>$tmp)
				{
					@unlink($tmp);
				}
				$this->error('配置商品SKU失败！');
			}
		}else {
			$this->display();
		}
	}
}