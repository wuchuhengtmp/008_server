<?php
/**
 * 商品类
 */
namespace Common\Model;
use Think\Model;

class GoodsModel extends Model
{
	//验证规则
	protected $_validate =array(
		array('cat_id','require','商品分类名称不能为空！',self::EXISTS_VALIDATE),  //存在验证，必填
		array('cat_id','is_positive_int','请选择正确的商品分类',self::EXISTS_VALIDATE,'function'),  //存在验证，必须是正整数
		array('goods_name','require','商品名称不能为空！',self::EXISTS_VALIDATE),  //存在验证，必填
		array('goods_name','1,200','商品名称不超过200个字符！',self::EXISTS_VALIDATE,'length'),  //存在验证，不超过200个字符
		array('goods_code','1,30','商品编码不超过30个字符！',self::VALUE_VALIDATE,'length'),  //值不为空的时候验证，不超过30个字符
		array('img','1,255','商品图片路径不正确！',self::VALUE_VALIDATE,'length'),  //值不为空的时候验证 ，不超过255个字符
		array('description','1,500','简要说明不超过500个字符！',self::VALUE_VALIDATE,'length'),  //值不为空的时候验证 ，不超过500个字符
		array('brand_id','is_positive_int','请选择正确的品牌',self::VALUE_VALIDATE,'function'),  //值不为空的时候验证，必须是正整数	
		array('is_show','require','请选择是否上架！',self::EXISTS_VALIDATE),  //存在验证，必填
		array('is_show',array('Y','N'),'请选择是否上架！',self::EXISTS_VALIDATE,'in'),  //存在验证，只能是Y是 N否
		array('is_top',array('Y','N'),'请选择是否为推荐商品！',self::EXISTS_VALIDATE,'in'),  //存在验证，只能是Y是 N否
		array('is_sale',array('Y','N'),'请选择是否为特价商品！',self::EXISTS_VALIDATE,'in'),  //存在验证，只能是Y是 N否	
		array('is_discount','require','请选择是否会员等级折扣！',self::EXISTS_VALIDATE),  //存在验证，必填
		array('is_discount',array('Y','N'),'请选择是否会员等级折扣！',self::EXISTS_VALIDATE,'in'),  //存在验证，只能是Y是 N否		
		array('sort','is_natural_num','排序必须为自然数！',self::VALUE_VALIDATE,'function'),  //值不为空的时候验证 ，必须是自然数
		array('clicknum','is_natural_num','浏览量必须为不小于零的整数！',self::VALUE_VALIDATE,'function'),  //值不为空的时候验证 ，必须是自然数
		array('old_price','currency','参考价格不是正确的货币格式！',self::VALUE_VALIDATE),  //值不为空的时候验证 ，必须是货币格式
		//array('price','currency','实际价格不是正确的货币格式！',self::VALUE_VALIDATE),  //值不为空的时候验证 ，必须是货币格式
		array('price','is_natural_num','实际价格不是正确的货币格式！',self::VALUE_VALIDATE,'function'),  //值不为空的时候验证 ，必须是自然数
		array('inventory','is_natural_num','库存数量必须为不小于零的整数！',self::VALUE_VALIDATE,'function'),  //值不为空的时候验证 ，必须是自然数
		array('give_point','is_natural_num','赠送积分必须为不小于零的整数！',self::VALUE_VALIDATE,'function'),  //值不为空的时候验证 ，必须是自然数
	    array('deduction_point','is_natural_num','可抵扣积分必须为不小于零的整数！',self::VALUE_VALIDATE,'function'),  //值不为空的时候验证 ，必须是自然数
	    array('is_sku',array('Y','N'),'请选择是否开启商品属性配置！',self::EXISTS_VALIDATE,'in'),  //存在验证，只能是Y是 N否
		array('sales_volume','is_natural_num','销售量必须为不小于零的整数！',self::EXISTS_VALIDATE,'function'),  //存在验证，必须是自然数
		array('virtual_volume','is_natural_num','虚拟销售量必须为不小于零的整数！',self::VALUE_VALIDATE,'function'),  //存在验证，必须是自然数
		array('createtime','require','创建时间不能为空！',self::EXISTS_VALIDATE),  //存在验证，必填
		array('createtime','is_datetime','创建时间格式不正确！',self::EXISTS_VALIDATE,'function'),  //存在验证，必须是正确的时间格式
		array('is_delete','require','请选择是否删除商品！',self::EXISTS_VALIDATE),  //存在验证，必填
		array('is_delete',array('Y','N'),'请选择是否删除商品',self::EXISTS_VALIDATE,'in'),  //存在验证，必须是Y已删除 N未删除
	);
	
	/**
	 * 获取商品列表
	 * @param int $cat_id:分类ID
	 * @param string $order:排序规则 desc降序 asc升序
	 * @param string $is_show:是否显示 Y显示 N不显示
	 * @return array|false
	 */
	public function getGoodsList($cat_id,$order='desc',$is_show='')
	{
		if($is_show)
		{
			$where="cat_id='$cat_id' and is_show='$is_show'";
		}else {
			$where="cat_id='$cat_id'";
		}
		$list=$this->where($where)->order("is_top desc,sort desc,goods_id $order")->select();
		if($list!==false)
		{
			$num=count($list);
			for ($i=0;$i<$num;$i++)
			{
				//单价
				$list[$i]['price']=$list[$i]['price']/100;
			}
			return $list;
		}else {
			return false;
		}
	}
	
	/**
	 * 获取指定商品列表
	 * @param int $cat_id:商品分类ID
	 * @param string $order:排序规则 desc降序 asc升序
	 * @param int $start:开始条数，默认从0开始
	 * @param int $num:条数，默认10条
	 * @return array|boolean
	 */
	public function getListByLimit($cat_id,$order='desc',$start=0,$num=10)
	{
		//列表
		$where="cat_id='$cat_id' and is_show='Y'";
		$list = $this->where($where)->limit($start,$num)->order("is_top desc,sort desc,goods_id $order")->select();
		if($list!==false)
		{
			$num=count($list);
			for ($i=0;$i<$num;$i++)
			{
				//单价
				$list[$i]['price']=$list[$i]['price']/100;
			}
			return $list;
		}else {
			return false;
		}
	}
	
	/**
	 * 获取分页商品列表
	 * @param int $cat_id:商品分类ID
	 * @param string $order:排序规则 desc降序 asc升序
	 * @param int $p:当前页码
	 * @param int $per:每页显示条数
	 * @return array|boolean
	 * @return array $list:当前页文章列表
	 * @return array $page:分页条
	 */
	public function getListByPage($cat_id,$order='desc',$p=1,$per)
	{
		//列表
		$where="cat_id='$cat_id' and is_show='Y'";
		$list = $this->where($where)->page($p.','.$per)->order("is_top desc,sort desc,goods_id $order")->select();
		if($list!==false)
		{
			$num=count($list);
			for ($i=0;$i<$num;$i++)
			{
				//单价
				$list[$i]['price']=$list[$i]['price']/100;
			}
			//总数
			$count=$this->where($where)->count();
			//分页
			$Page=new \Common\Model\PageModel();
			$show=$Page->show($count, $per);
			$content=array(
					'list'=>$list,
					'page'=>$show
			);
			return $content;
		}else {
			return false;
		}
	}
	
	/**
	 * 获取商品信息
	 * @param int $goods_id:商品ID
	 * @return array|false
	 */
	public function getGoodsMsg($goods_id)
	{
		$msg=$this->where("goods_id='$goods_id'")->find();
		if($msg!==false)
		{
			//单价
			$msg['price']=$msg['price']/100;
			return $msg;
		}else {
			return false;
		}
	}
	
	/**
	 * 获取商品详情
	 * @param int $goods_id:商品ID
	 * @return array|false
	 */
	public function getGoodsDetail($goods_id)
	{
		$msg=$this->getGoodsMsg($goods_id);
		if($msg!==false)
		{
			//所属品牌
			$Brand=new \Common\Model\BrandModel();
			$brandMsg=$Brand->getBrandMsg($msg['brand_id']);
			$msg['brand_name']=$brandMsg['name'];
			//属性配置
			$msg['sku_arr']=array();
			if($msg['sku_str'])
			{
				$sku_arr=json_decode($msg['sku_str'],true);
				$num=count($sku_arr);
				$GoodsAttribute=new \Common\Model\GoodsAttributeModel();
				for ($i=0;$i<$num;$i++)
				{
					//属性ID
					$attribute_id=$sku_arr[$i]['attribute_id'];
					$GoodsAttributeMsg=$GoodsAttribute->getMsg($attribute_id);
					$sku_arr[$i]['attribute_name']=$GoodsAttributeMsg['goods_attribute_name'];
				}
				$msg['sku_arr']=$sku_arr;
			}
			return $msg;
		}else {
			return false;
		}
	}
	
	/**
	 * 获取推荐商品列表
	 * @param int $start:开始条数，默认从0开始
	 * @param int $num:条数，默认10条
	 * @param string $order:排序规则 desc降序 asc升序
	 * @return array|boolean
	 */
	public function getTopList($start=0,$num=10,$order='desc')
	{
		$list=$this->where("is_top='Y' and is_show='Y'")->limit($start,$num)->order("sort desc,goods_id $order")->select();
		if($list!==false)
		{
			$num=count($list);
			for ($i=0;$i<$num;$i++)
			{
				//单价
				$list[$i]['price']=$list[$i]['price']/100;
			}
			return $list;
		}else {
			return false;
		}
	}
	
	/**
	 * 获取特价商品列表
	 * @param int $start:开始条数，默认从0开始
	 * @param int $num:条数，默认10条
	 * @param string $order:排序规则 desc降序 asc升序
	 * @return array|boolean
	 */
	public function getSaleList($start=0,$num=10,$order='desc')
	{
		$list=$this->where("is_sale='Y' and is_show='Y'")->limit($start,$num)->order("is_top desc,sort desc,goods_id $order")->select();
		if($list!==false)
		{
			$num=count($list);
			for ($i=0;$i<$num;$i++)
			{
				//单价
				$list[$i]['price']=$list[$i]['price']/100;
			}
			return $list;
		}else {
			return false;
		}
	}
	
	/**
	 * 获取销量排行商品列表
	 * @param int $num:商品数量
	 * @return array|boolean
	 */
	public function getSalesVolumeList($num)
	{
		$list=$this->where("is_show='Y'")->limit(0,$num)->order('sales_volume desc,goods_id desc')->select();
		if($list!==false)
		{
			$num=count($list);
			for ($i=0;$i<$num;$i++)
			{
				//单价
				$list[$i]['price']=$list[$i]['price']/100;
			}
			return $list;
		}else {
			return false;
		}
	}
	
	/**
	 * 删除商品
	 * 同步删除商品的标题图片、列表图片以及内容中的图片和文件
	 * @param int $id:商品ID
	 * @return number
	 */
	public function del($goods_id)
	{
		//获取商品信息
		$msg=$this->getGoodsMsg($goods_id);
		$res=$this->where("goods_id='$goods_id'")->delete();
		if($res)
		{
			if(!empty($msg['img']))
			{
				$img='.'.$msg['img'];
				@unlink($img);
				//删除缩略图
				$tmp_img='.'.$msg['tmp_img'];
				@unlink($tmp_img);
			}
			if(!empty($msg['video']))
			{
			    $video='.'.$msg['video'];
			    @unlink($video);
			}
			// 删除内容中的图片和文件
			if (! empty ( $msg['content'] )) 
			{
				$content=htmlspecialchars_decode(html_entity_decode($msg['content']));
				$ueditor=new \Admin\Common\Controller\UeditorController;
				$ueditor->del($content);
			}
			//删除商品下的图片列表
			$GoodsImg=new \Common\Model\GoodsImgModel();
			$imgList=$GoodsImg->getImgList($goods_id);
			$img_num=count($imgList);
			if($img_num>0)
			{
				$i=0;
				for($i=0;$i<$img_num;$i++)
				{
					$goods_img_id=$imgList[$i]['goods_img_id'];
					$res_img=$GoodsImg->where("goods_img_id='$goods_img_id'")->delete();
					if($res_img!==false)
					{
						//删除图片
						if(!empty($imgList[$i]['img']))
						{
							$goods_img='.'.$imgList[$i]['img'];
							@unlink($goods_img);
						}
						//继续
						continue;
					}else {
						return false;
					}
				}
			}
			return true;
		}else {
			return false;
		}
	}
}
?>