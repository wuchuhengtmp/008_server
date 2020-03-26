<?php
/**
 * 淘宝推荐商品管理
 */
namespace Agent\Controller;
use Agent\Common\Controller\AuthController;
class TbGoodsController extends AuthController
{
    public function index()
    {
        $agent_id=$_SESSION['agent_id'];
        $where="agent_id=$agent_id";
    	if(trim(I('get.goods_name'))) {
    		$goods_name=trim(I('get.goods_name'));
    		$where.=" and goods_name like '%$goods_name%'";
    	}
    	$TbGoods=new \Common\Model\TbGoodsModel();
    	$count=$TbGoods->where($where)->count();
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
    	 
    	$list = $TbGoods->where($where)->page($p.','.$per)->order('sort desc,id desc')->select();
    	$this->assign('list',$list);
    	 
    	$this->display();
    }
    
    //添加淘宝商品
    public function add()
    {
    	if($_POST) {
    		layout(false);
    		if(trim(I('post.goods_id')) and trim(I('post.goods_name')))
    		{
    			$goods_id=trim(I('post.goods_id'));
    			//查询淘宝商品详情
    			Vendor('tbk.tbk','','.class.php');
    			$tbk=new \tbk();
    			$res_goods=$tbk->getItemInfo($goods_id,'2','');
    			if($res_goods['code']==0) {
    			    $goodsMsg=$res_goods['data'];
    			    //商品价格
    			    $zk_final_price=$goodsMsg['zk_final_price'];
    			    //商品主图
    			    $pict_url=$goodsMsg['pict_url'];
    			    //商品相册
    			    $small_images=json_encode($goodsMsg['small_images']['string']);
    			    //销量
    			    $volume=$goodsMsg['volume'];
    			}else {
    			    $this->error($res_goods['msg']);
    			}
    			//查询淘宝商品优惠券及佣金信息
    			$gy_data=$tbk->getCouponCommission($goods_id);
    			//优惠券信息
    			if($gy_data['coupon_info'])
    			{
    				//优惠券面额
    				$pos1=strpos($gy_data['coupon_info'],'减');
    				$pos2=strripos($gy_data['coupon_info'],'元');
    				$coupon_amount=substr($gy_data['coupon_info'], $pos1+3,$pos2-$pos1-3);
    			}else {
    				$coupon_amount=0;
    			}
    			//保存到数据库
    			$data=array(
    			    'goods_id'=>trim(I('post.goods_id')),
    			    'goods_name'=>trim(I('post.goods_name')),
    			    'zk_final_price'=>$zk_final_price,
    			    'pict_url'=>$pict_url,
    			    'small_images'=>$small_images,
    			    'description'=>trim(I('post.description')),
    			    'coupon_amount'=>$coupon_amount,
    			    'commission_rate'=>$gy_data['commission_rate'],
    			    'volume'=>$volume,
    			    'sort'=>trim(I('post.sort')),
    			    //'type'=>trim(I('post.type')),
    			    'create_time'=>date('Y-m-d H:i:s'),
    			    'agent_id'=>$_SESSION['agent_id']
    			);
    			$TbGoods=new \Common\Model\TbGoodsModel();
    			if(!$TbGoods->create($data)) {
    				// 验证不通过
    				$this->error($TbGoods->getError());
    			}else {
    				// 验证成功
    				$res_add=$TbGoods->add($data);
    				if($res_add!==false) {
    					$this->success('新增淘宝商品成功！',U('index'));
    				}else {
    					$this->error('操作失败！');
    				}
    			}
    		}else {
    			$this->error('商品ID、名称不能为空！');
    		}
    	}else {
    		$this->display();
    	}
    }
    
    //编辑淘宝商品分类
    public function edit($id)
    {
    	//获取淘宝官方商品分类信息
    	$TbGoods=new \Common\Model\TbGoodsModel();
    	$msg=$TbGoods->getGoodsMsg($id);
    	$this->assign('msg',$msg);
    	
    	if(I('post.'))
    	{
    		layout(false);
    		if(trim(I('post.goods_id')) and trim(I('post.goods_name')))
    		{
    			$goods_id=trim(I('post.goods_id'));
    			//查询淘宝商品详情
    			Vendor('tbk.tbk','','.class.php');
    			$tbk=new \tbk();
    			$res_goods=$tbk->getItemInfo($goods_id,'2','');
    			if($res_goods['code']==0) {
    			    $goodsMsg=$res_goods['data'];
    			    //商品价格
    			    $zk_final_price=$goodsMsg['zk_final_price'];
    			    //商品主图
    			    $pict_url=$goodsMsg['pict_url'];
    			    //商品相册
    			    $small_images=json_encode($goodsMsg['small_images']['string']);
    			    //销量
    			    $volume=$goodsMsg['volume'];
    			}else {
    			    $this->error($res_goods['msg']);
    			}
    			//查询淘宝商品优惠券及佣金信息
    			$gy_data=$tbk->getCouponCommission($goods_id);
    			//优惠券信息
    			if($gy_data['coupon_info'])
    			{
    				//优惠券面额
    				$pos1=strpos($gy_data['coupon_info'],'减');
    				$pos2=strripos($gy_data['coupon_info'],'元');
    				$coupon_amount=substr($gy_data['coupon_info'], $pos1+3,$pos2-$pos1-3);
    			}else {
    				$coupon_amount=0;
    			}
    			//保存到数据库
    			$data=array(
    			    'goods_id'=>trim(I('post.goods_id')),
    			    'goods_name'=>trim(I('post.goods_name')),
    			    'zk_final_price'=>$zk_final_price,
    			    'pict_url'=>$pict_url,
    			    'small_images'=>$small_images,
    			    'description'=>trim(I('post.description')),
    			    'coupon_amount'=>$coupon_amount,
    			    'commission_rate'=>$gy_data['commission_rate'],
    			    'volume'=>$volume,
    			    'sort'=>trim(I('post.sort')),
    			    //'type'=>trim(I('post.type')),
    			    'create_time'=>date('Y-m-d H:i:s')
    			);
    			if(!$TbGoods->create($data))
    			{
    				// 验证不通过
    				$this->error($TbGoods->getError());
    			}else {
    				// 验证成功
    				$res=$TbGoods->where("id='$id'")->save($data);
    				if($res!==false)
    				{
    					$this->success('编辑淘宝商品成功！',U('index'));
    				}else {
    					$this->error('操作失败！');
    				}
    			}
    		}else {
    			$this->error('商品ID、名称不能为空！');
    		}
    	}else {
    		$this->display();
    	}
    }
    
    //删除商品分类
    public function del($id)
    {
    	$TbGoods=new \Common\Model\TbGoodsModel();
    	$res=$TbGoods->where("id='$id'")->delete();
    	if($res!==false)
    	{
    		echo '1';
    	}else {
    		echo '0';
    	}
    }
    
    //批量删除
    public function batchdel($all_id)
    {
    	$all_id=substr($all_id,0,-1);
    	$TbGoods=new \Common\Model\TbGoodsModel();
    	$res=$TbGoods->where("id in ($all_id)")->delete();
    	if($res!==false)
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
    	$sql = "UPDATE __PREFIX__tb_goods SET sort = CASE id ";
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
    		$this->success('排序成功!',U('index'),3);
    	}
    }
}
?>