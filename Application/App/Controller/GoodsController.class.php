<?php
/**
 * 商品管理接口
 */
namespace App\Controller;
use App\Common\Controller\AuthController;

class GoodsController extends AuthController
{
	/**
	 * 获取热门搜索
	 * @param number $num:条数，默认10条
	 * @return array
	 * @return @param code:返回码
	 * @return @param msg:返回码说明
	 * @return @param data:返回数据
	 * @return @param data->list:热门搜索列表
	 */
	public function getHotSearch()
	{
		if(trim(I('post.num')))
		{
			$num=trim(I('post.num'));
		}else {
			$num=10;
		}
		$HotSearch=new \Common\Model\HotSearchModel();
		$list=$HotSearch->limit(0,$num)->order('num desc,id asc')->select();
		if($list!==false)
		{
			$data=array(
					'list'=>$list
			);
			$res=array(
					'code'=>$this->ERROR_CODE_COMMON['SUCCESS'],
					'msg'=>'成功',
					'data'=>$data
			);
		}else {
			//数据库错误
			$res=array(
					'code'=>$this->ERROR_CODE_COMMON['DB_ERROR'],
					'msg'=>$this->ERROR_CODE_COMMON_ZH[$this->ERROR_CODE_COMMON['DB_ERROR']]
			);
		}
		echo json_encode ($res,JSON_UNESCAPED_UNICODE);
	}
	
	/**
	 * 获取商品列表
	 * @param int $cat_id:商品分类ID
	 * @param string $goods_name:商品名称
	 * @param int $p:页码，默认第1页
	 * @param int $per:每页条数，默认10条
	 * @param float $price1:起始价格
	 * @param float $price2:截止价格
	 * @return array
	 * @return @param code:返回码
	 * @return @param msg:返回码说明
	 * @return @param data:返回数据
	 * @return @param data->list:商品列表
	 */
	public function getGoodsList()
	{
		$where="is_show='Y'";
		//商品分类
		if(trim(I('post.cat_id')))
		{
			$cat_id=trim(I('post.cat_id'));
			//获取所有子分类
			$GoodsCat=new \Common\Model\GoodsCatModel();
			$subCatList=$GoodsCat->getSubCatList($cat_id);
			$all_catid=$cat_id.',';
			foreach ($subCatList as $l)
			{
				$all_catid.=$l['cat_id'].',';
			}
			$all_catid=substr($all_catid, 0,-1);
			$where.=" and cat_id in ($all_catid)";
		}
		//商品名称
		if(trim(I('post.goods_name')))
		{
			$goods_name=trim(I('post.goods_name'));
			$where.=" and goods_name like '%$goods_name%'";
			//保存热门搜索
			$HotSearch=new \Common\Model\HotSearchModel();
			$HotSearch->statistics($goods_name);
		}
		//价格区间搜索
		if(trim(I('post.price1')))
		{
			$price1=trim(I('post.price1'))*100;
			$where.=" and price>=$price1";
		}
		if(trim(I('post.price2')))
		{
			$price2=trim(I('post.price2'))*100;
			$where.=" and price<=$price2";
		}
		//分页
		if(trim(I('post.p')))
		{
			$p=trim(I('post.p'));
		}else {
			$p=1;
		}
		if(trim(I('post.per')))
		{
			$per=trim(I('post.per'));
		}else {
			$per=10;
		}
		$Goods=new \Common\Model\GoodsModel();
		$list=$Goods->where($where)->field('goods_id,cat_id,goods_name,goods_code,img,description,brand_id,clicknum,old_price,price,inventory,give_point,sales_volume,virtual_volume,createtime')->order("is_top desc,sort desc,goods_id asc")->page($p,$per)->select();
		if($list!==false)
		{
			$num=count($list);
			for ($i=0;$i<$num;$i++)
			{
				//价格
				$list[$i]['price']=$list[$i]['price']/100;
				//销量
				$list[$i]['sales_volume']=$list[$i]['sales_volume']+$list[$i]['virtual_volume'];
			}
			$data=array(
					'list'=>$list
			);
			$res=array(
					'code'=>$this->ERROR_CODE_COMMON['SUCCESS'],
					'msg'=>'成功',
					'data'=>$data
			);
		}else {
			//数据库错误
			$res=array(
					'code'=>$this->ERROR_CODE_COMMON['DB_ERROR'],
					'msg'=>$this->ERROR_CODE_COMMON_ZH[$this->ERROR_CODE_COMMON['DB_ERROR']]
			);
		}
		echo json_encode ($res,JSON_UNESCAPED_UNICODE);
	}
	
	/**
	 * 获取商品详情
	 * @param int $goods_id:商品ID
	 * @return array
	 * @return @param code:返回码
	 * @return @param msg:返回码说明
	 * @return @param data:返回数据
	 * @return @param data->goodsMsg:商品详情
	 * @return @param data->imglist:商品相册
	 * @return @param data->skulist:商品sku配置
	 * */
	 public function getGoodsMsg()
	 {
	 	if(trim(I('post.goods_id')))
		{
			$goods_id=trim(I('post.goods_id'));
			$Goods=new \Common\Model\GoodsModel();
			$goodsMsg=$Goods->getGoodsDetail($goods_id);
			if($goodsMsg!==false)
			{
				//将内容中的图片替换为绝对路径
				$Ueditor=new \Admin\Common\Controller\UeditorController();
				$goodsMsg['content']=$Ueditor->changeImagePath($goodsMsg['content']);
				//获取商品相册
				$GoodsImg=new \Common\Model\GoodsImgModel();
				$imglist=$GoodsImg->getImgList($goods_id);
				//获取商品sku配置
				$skulist=array();
				/* $GoodsSku=new \Common\Model\GoodsSkuModel();
				$skulist=$GoodsSku->getSkuList($goods_id); */
				$data=array(
						'goodsMsg'=>$goodsMsg,
						'imglist'=>$imglist,
						'skulist'=>$skulist
				);
				$res=array(
						'code'=>$this->ERROR_CODE_COMMON['SUCCESS'],
						'msg'=>'成功',
						'data'=>$data
				);
			}else {
				//数据库错误
				$res=array(
						'code'=>$this->ERROR_CODE_COMMON['DB_ERROR'],
						'msg'=>$this->ERROR_CODE_COMMON_ZH[$this->ERROR_CODE_COMMON['DB_ERROR']]
				);
			}
		}else {
			//参数不正确，参数缺失
			$res=array(
					'code'=>$this->ERROR_CODE_COMMON['PARAMETER_ERROR'],
					'msg'=>$this->ERROR_CODE_COMMON_ZH[$this->ERROR_CODE_COMMON['PARAMETER_ERROR']]
			);
		}
		echo json_encode ($res,JSON_UNESCAPED_UNICODE);
	 }
}
?>