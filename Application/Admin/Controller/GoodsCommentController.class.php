<?php
/**
 * 商品评论管理
 */
namespace Admin\Controller;
use Admin\Common\Controller\AuthController;
class GoodsCommentController extends AuthController
{
	public function index($goods_id)
	{
		//获取商品信息
		$Goods=new \Common\Model\GoodsModel();
		$msg=$Goods->getGoodsMsg($goods_id);
		$this->assign('msg',$msg);
		 
		$where="goods_id='$goods_id'";
		if(I('get.content'))
		{
			$content=I('get.content');
			$where.=" and content like '%$content%'";
		}
		$GoodsComment=new \Common\Model\GoodsCommentModel();
		$count=$GoodsComment->where($where)->count();
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
		 
		$list = $GoodsComment->where($where)->page($p.','.$per)->order('goods_comment_id asc')->select();
		$this->assign('list',$list);
		 
		$this->display();
	}
	
	//修改评论显示状态
	public function changeshow($id,$status)
	{
		$data=array(
				'is_show'=>$status
		);
		$GoodsComment=new \Common\Model\GoodsCommentModel();
		if(!$GoodsComment->create($data))
		{
			echo '0';
		}else {
			// 验证成功
			$res=$GoodsComment->where("id=$id")->save($data);
			if($res===false)
			{
				echo '0';
			}else {
				echo '1';
			}
		}
	}
	
	//删除评论
	public function del($id)
	{
		$GoodsComment=new \Common\Model\GoodsCommentModel();
		$msg=$GoodsComment->getCommentMsg($id);
		$res=$GoodsComment->where("goods_comment_id='$id'")->delete();
		if($res!==false)
		{
			//删除图片
			if($msg['img'])
			{
				$img_arr=json_decode($msg['img'],true);
				foreach ($img_arr as $k=>$v)
				{
					$tmp='.'.$v;
					@unlink($tmp);
				}
			}
			echo '1';
		}else {
			echo '0';
		}
	}
}