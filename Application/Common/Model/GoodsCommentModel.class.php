<?php
/**
 * 商品/订单评论管理类
 */
namespace Common\Model;
use Think\Model;

class GoodsCommentModel extends Model
{
	//验证规则
	protected $_validate =array(
			array('user_id','require','评论用户不能为空！',self::EXISTS_VALIDATE),  //存在验证，必填
			array('user_id','is_positive_int','评论用户不存在',self::EXISTS_VALIDATE,'function'),  //存在验证，必须是正整数
			array('order_id','require','请选择要评论的订单！',self::EXISTS_VALIDATE),  //存在验证，必填
			array('order_id','is_positive_int','评论的订单不存在',self::EXISTS_VALIDATE,'function'),  //存在验证，必须是正整数
			array('goods_id','require','请选择要评论的商品！',self::EXISTS_VALIDATE),  //存在验证，必填
			array('goods_id','is_positive_int','评论的商品不存在',self::EXISTS_VALIDATE,'function'),  //存在验证，必须是正整数
			array('score','is_natural_num','评分为不小于零的整数！',self::VALUE_VALIDATE,'function'),  //值不为空的时候验证，必须是自然数
			array('grade',array(1,3),'评论等级不正确！',self::VALUE_VALIDATE,'between'),  //值不为空的时候验证，只能是 1好评 2中评 3差评
			array('content','1,600','评论内容不超过600个字符！',self::VALUE_VALIDATE,'length'),  //值不为空的时候验证 ，不超过600个字符
			array('comment_time','require','评论时间不能为空！',self::EXISTS_VALIDATE),  //存在验证，必填
			array('comment_time','is_datetime','评论时间格式不正确！',self::EXISTS_VALIDATE,'function'),  //存在验证，必须是正确的时间格式
			array('have_img',array('Y','N'),'请选择是否拥有评论图片！',self::EXISTS_VALIDATE,'in'),  //存在验证，只能是Y有 N没有
	);
	
	/**
	 * 获取评论信息
	 * @param int $id:ID
	 * @return array|boolean
	 */
	public function getCommentMsg($id)
	{
	    $msg=$this->where("goods_comment_id=$id")->find();
	    if($msg){
	        return $msg;
	    }else {
	        return false;
	    }
	}
	
	/**
	 * 获取商品评论
	 * @param int $goods_id:商品ID
	 * @return array|boolean
	 */
	public function getGoodsComment($goods_id)
	{
		$list=$this->where("goods_id='$goods_id'")->select();
		if($list!==false)
		{
			//评论用户
			$num=count($list);
			$User=new \Common\Model\UserModel();
			for($i=0;$i<$num;$i++)
			{
				//评论图片
				$list[$i]['img_arr']=json_decode($list[$i]['img'],true);
				//获取用户信息
				$UserMsg=$User->getUserDetail($list[$i]['user_id']);
				//用户头像
				$list[$i]['user_avatar']=$UserMsg['detail']['avatar'];
				//用户昵称
				if($UserMsg['detail']['nickname'])
				{
					$nickname=$UserMsg['detail']['nickname'];
				}else {
					$nickname=$UserMsg['account'];
				}
				$list[$i]['user_nickname']=$nickname;
			}
			return $list;
		}else {
			return false;
		}
	}
	
	/**
	 * 获取指定条数商品评论
	 * @param int $goods_id:商品ID
	 * @param int $start:开始位置，默认0
	 * @param int $num:查询条数，默认10
	 * @return array|boolean
	 */
	public function getGoodsCommentByLimit($goods_id,$start=0,$num=10)
	{
		$list=$this->where("goods_id='$goods_id'")->order("goods_comment_id desc")->limit($start,$num)->select();
		if($list!==false)
		{
			//评论用户
			$num=count($list);
			$User=new \Common\Model\UserModel();
			for($i=0;$i<$num;$i++)
			{
				//评论图片
				$list[$i]['img_arr']=json_decode($list[$i]['img'],true);
				//获取用户信息
				$UserMsg=$User->getUserDetail($list[$i]['user_id']);
				//用户头像
				$list[$i]['user_avatar']=$UserMsg['detail']['avatar'];
				//用户昵称
				if($UserMsg['detail']['nickname'])
				{
					$nickname=$UserMsg['detail']['nickname'];
				}else {
					$nickname=$UserMsg['account'];
				}
				$list[$i]['user_nickname']=$nickname;
			}
			return $list;
		}else {
			return false;
		}
	}
	
	/**
	 * 获取分页商品评论列表
	 * @param int $goods_id:商品ID
	 * @param int $status:评论状态，1好评、2中评、3差评、4晒图，默认全部
	 * @param int $p:页码，默认第1页
	 * @param int $per:每页条数，默认6条
	 * @return array|boolean
	 */
	public function getGoodsCommentByPage($goods_id,$status='',$p=1,$per=6)
	{
		$where="goods_id='$goods_id'";
		if($status)
		{
			switch ($status)
			{
				//好评
				case '1':
					$where.=" and grade='1'";
					break;
					//中评
				case '2':
					$where.=" and grade='2'";
					break;
					//差评
				case '3':
					$where.=" and grade='3'";
					break;
					//晒图
				case '4':
					$where.=" and have_img='Y'";
					break;
				default:
					$where.='';
					break;
			}
		}
		$list=$this->where($where)->order("goods_comment_id desc")->page($p,$per)->select();
		if($list!==false)
		{
			//评论用户
			$num=count($list);
			$User=new \Common\Model\UserModel();
			for($i=0;$i<$num;$i++)
			{
				//评论图片
				$list[$i]['img_arr']=json_decode($list[$i]['img'],true);
				//获取用户信息
				$UserMsg=$User->getUserDetail($list[$i]['user_id']);
				//用户头像
				$list[$i]['user_avatar']=$UserMsg['detail']['avatar'];
				//用户昵称
				if($UserMsg['detail']['nickname'])
				{
					$nickname=$UserMsg['detail']['nickname'];
				}else {
					$nickname=$UserMsg['account'];
				}
				$list[$i]['user_nickname']=$nickname;
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
	 * 获取商品评论总数量
	 * @param int $goods_id:商品ID
	 * @return number|boolean
	 */
	public function getGoodsCommentAllnum($goods_id)
	{
		//总数
		$num=$this->where("goods_id='$goods_id'")->count();
		if($num!==false)
		{
			return $num;
		}else {
			return false;
		}
	}
	
	/**
	 * 获取商品评论数量
	 * @param int $goods_id:商品ID
	 * @return array|boolean
	 */
	public function getGoodsCommentStatistics($goods_id)
	{
		//总数
		$allnum=$this->where("goods_id='$goods_id'")->count();
		//好评
		$lvl1_num=$this->where("goods_id='$goods_id' and grade='1'")->count();
		//中评
		$lvl2_num=$this->where("goods_id='$goods_id' and grade='2'")->count();
		//差评
		$lvl3_num=$this->where("goods_id='$goods_id' and grade='3'")->count();
		//晒图
		$have_img_num=$this->where("goods_id='$goods_id' and have_img='Y'")->count();
		//好评率
		$lvl1_cent=$lvl1_num/$allnum*100;
		//中评率
		$lvl2_cent=$lvl2_num/$allnum*100;
		//差评率
		$lvl3_cent=$lvl3_num/$allnum*100;
		//晒图率
		$have_img_cent=$have_img_num/$allnum*100;
		$res=array(
				'allnum'=>$allnum,
				'lvl1_num'=>$lvl1_num,
				'lvl2_num'=>$lvl2_num,
				'lvl3_num'=>$lvl3_num,
				'have_img_num'=>$have_img_num,
				'lvl1_cent'=>$lvl1_cent,
				'lvl2_cent'=>$lvl2_cent,
				'lvl3_cent'=>$lvl3_cent,
				'have_img_cent'=>$have_img_cent,
		);
		return $res;
	}
}
?>