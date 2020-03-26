<?php
/**
 * 论坛帖子管理接口
 */
namespace App\Controller;
use App\Common\Controller\AuthController;

class BbsArticleController extends AuthController
{
	/**
	 * 获取每日爆款商品列表
	 * @param int $p:页码，默认第1页
	 * @param int $per:每页条数，默认6条
	 * @return array
	 * @return @param code:返回码
	 * @return @param msg:返回码说明
	 * @return @param data:返回数据
	 * @return @param data->list:商品列表
	 */
	public function getGoodsList()
	{
		$where="is_show='Y' and is_check='Y' and check_result='Y' and board_id=1";
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
			$per=6;
		}
		$BbsArticle=new \Common\Model\BbsArticleModel();
		$list=$BbsArticle->where($where)->field('id,uid,title,description,pubtime,share_num,tb_gid')->page($p,$per)->order("is_top desc,id desc")->select();
		if($list!==false)
		{
			$num=count($list);
			$User=new \Common\Model\UserModel();
			//淘宝客类
			Vendor('tbk.tbk','','.class.php');
			$tbk=new \tbk();
			for($i=0;$i<$num;$i++)
			{
				//获取用户信息
				$uid=$list[$i]['uid'];
				$userMsg=$User->getUserDetail($uid);
				//用户昵称
				if($userMsg['detail']['nickname'])
				{
					$nickname=$userMsg['detail']['nickname'];
				}else {
					$nickname=$userMsg['account'];
				}
				$list[$i]['nickname']=$nickname;
				//用户头像
				//判断头像是否为第三方应用头像
				if($userMsg['detail']['avatar'])
				{
					//判断头像是否为第三方应用头像
					if(is_url($userMsg['detail']['avatar']))
					{
						$list[$i]['avatar']=$userMsg['detail']['avatar'];
					}else {
						$list[$i]['avatar']=WEB_URL.$userMsg['detail']['avatar'];
					}
				}else {
					$list[$i]['avatar']='';
				}
				
				//获取商品详情
				$num_iid=$list[$i]['tb_gid'];
				//$ip=getIP();
				$ip='';
				$res_tbk=$tbk->getItemDetail($num_iid,$platform='2',$ip,$pid='');
				//商品名称
				$list[$i]['goods_name']=$res_tbk['data']['title'];
				//商品相册
				$list[$i]['small_images']=$res_tbk['data']['small_images'];
				//商品折扣价格
				$list[$i]['zk_final_price']=$res_tbk['data']['zk_final_price'];
				//优惠券面额
				$list[$i]['coupon_amount']=$res_tbk['data']['coupon_amount'];
				//佣金
				$commission=$res_tbk['data']['commission']*0.9;
				//保留2位小数，四舍五不入
				$commission=substr(sprintf("%.3f",$commission),0,-1);
				$list[$i]['commission']=$commission;
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
	 * 更新分享次数
	 * @param int $id:帖子ID
	 * @return array
	 * @return @param code:返回码
	 * @return @param msg:返回码说明
	 */
	public function updateShareNum()
	{
		if(trim(I('post.id')))
		{
			$id=trim(I('post.id'));
			$BbsArticle=new \Common\Model\BbsArticleModel();
			//浏览量加1
			$res_share=$BbsArticle->where("id=$id")->setInc('share_num');
			if($res_share!==false)
			{
				$res=array(
						'code'=>$this->ERROR_CODE_COMMON['SUCCESS'],
						'msg'=>'成功',
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
	
	/**
	 * 获取帖子文章列表
	 * @param int $board_id:论坛版块ID
	 * @param int $search:搜索内容
	 * @param int $p:页码，默认第1页
	 * @param int $per:每页条数，默认6条
	 * @return array
	 * @return @param code:返回码
	 * @return @param msg:返回码说明
	 * @return @param data:返回数据
	 * @return @param data->list:帖子文章列表
	 */
	public function getArticleList()
	{
		$where="is_show='Y' and is_check='Y' and check_result='Y'";
		if(trim(I('post.board_id')))
		{
			$board_id=trim(I('post.board_id'));
			$where.=" and board_id='$board_id'";
		}
		if(trim(I('post.search')))
		{
			$search=trim(I('post.search'));
			$where.=" and (title like '%$search%' or keyword like '%$search%' or description like '%$search%' or content like '%$search%' or mob_text like '%$search%')";
		}
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
			$per=6;
		}
		$BbsArticle=new \Common\Model\BbsArticleModel();
		$list=$BbsArticle->where($where)->field('id,uid,board_id,title,keyword,description,img,mob_text,mob_img,clicknum,pubtime,share_num')->page($p,$per)->order("is_top desc,id desc")->select();
		if($list!==false)
		{
			$num=count($list);
			$User=new \Common\Model\UserModel();
			for($i=0;$i<$num;$i++)
			{
				//获取用户信息
				$uid=$list[$i]['uid'];
				$userMsg=$User->getUserDetail($uid);
				//用户昵称
				if($userMsg['detail']['nickname'])
				{
					$nickname=$userMsg['detail']['nickname'];
				}else {
					$nickname=$userMsg['account'];
				}
				$list[$i]['nickname']=$nickname;
				//用户头像
				//判断头像是否为第三方应用头像
				if($userMsg['detail']['avatar'])
				{
					//判断头像是否为第三方应用头像
					if(is_url($userMsg['detail']['avatar']))
					{
						$list[$i]['avatar']=$userMsg['detail']['avatar'];
					}else {
						$list[$i]['avatar']=WEB_URL.$userMsg['detail']['avatar'];
					}
				}else {
					$list[$i]['avatar']='';
				}
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
}
?>