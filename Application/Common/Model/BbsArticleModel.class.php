<?php
/**
 * 论坛帖子管理
 */
namespace Common\Model;
use Think\Model;

class BbsArticleModel extends Model
{
	//验证规则
	protected $_validate =array(
			array('uid','require','发表用户不能为空！',self::EXISTS_VALIDATE),  //存在验证，必填
			array('uid','is_positive_int','发表用户不存在',self::EXISTS_VALIDATE,'function'),  //存在验证，必须是正整数
			array('board_id','require','所属版块不能为空！',self::EXISTS_VALIDATE),  //存在验证，必填
			array('board_id','is_positive_int','请选择正确的版块',self::EXISTS_VALIDATE,'function'),  //存在验证，必须是正整数
			//array('title','require','帖子标题不能为空！',self::EXISTS_VALIDATE),  //存在验证，必填
			array('title','1,150','帖子标题不超过150个字符！',self::VALUE_VALIDATE,'length'),  //值不为空的时候验证，不超过150个字符
			array('keyword','1,200','关键词不超过200个字符！',self::VALUE_VALIDATE,'length'),  //值不为空的时候验证 ，不超过200个字符
			array('description','1,1000','简要说明不超过1000个字符！',self::VALUE_VALIDATE,'length'),  //值不为空的时候验证 ，不超过1000个字符
			array('img','1,255','封面图片路径不正确！',self::VALUE_VALIDATE,'length'),  //值不为空的时候验证 ，不超过255个字符
			array('linkman','1,20','联系人不超过20个字符！',self::VALUE_VALIDATE,'length'),  //值不为空的时候验证 ，不超过20个字符
			array('contact','1,30','联系电话不超过30个字符！',self::VALUE_VALIDATE,'length'),  //值不为空的时候验证 ，不超过30个字符
			array('address','1,50','地址不超过50个字符！',self::VALUE_VALIDATE,'length'),  //值不为空的时候验证 ，不超过50个字符
			array('lng_lat','1,100','经纬度不超过100个字符！',self::VALUE_VALIDATE,'length'),  //值不为空的时候验证 ，不超过100个字符
			array('lng','1,50','经度不超过50个字符！',self::VALUE_VALIDATE,'length'),  //值不为空的时候验证 ，不超过50个字符
			array('lat','1,50','纬度不超过50个字符！',self::VALUE_VALIDATE,'length'),  //值不为空的时候验证 ，不超过50个字符
			array('is_show','require','请选择是否显示！',self::EXISTS_VALIDATE),  //存在验证，必填
			array('is_show',array('Y','N'),'请选择是否显示！',self::EXISTS_VALIDATE,'in'),  //存在验证，只能是Y是 N否
			array('is_top','require','请选择是否推荐/置顶！',self::EXISTS_VALIDATE),  //存在验证，必填
			array('is_top',array('Y','N'),'请选择是否推荐/置顶！',self::EXISTS_VALIDATE,'in'),  //存在验证，只能是Y是 N否
			array('clicknum','is_natural_num','点击量必须为自然数！',self::VALUE_VALIDATE,'function'),  //值不为空的时候验证 ，必须是自然数
			array('pubtime','require','创建时间不能为空！',self::EXISTS_VALIDATE),  //存在验证，必填
			array('pubtime','is_datetime','创建时间格式不正确！',self::EXISTS_VALIDATE,'function'),  //存在验证，必须是正确的时间格式
			array('is_check','require','请选择是否审核！',self::EXISTS_VALIDATE),  //存在验证，必填
			array('is_check',array('Y','N'),'请选择是否审核！',self::EXISTS_VALIDATE,'in'),  //存在验证，只能是Y是 N否
			array('check_result',array('Y','N'),'请选择审核结果！',self::EXISTS_VALIDATE,'in'),  //存在验证，只能是Y审核通过 N审核不通过
			array('check_time','is_datetime','审核时间格式不正确！',self::VALUE_VALIDATE,'function'),  //值不为空的时候验证，必须是正确的时间格式
			array('check_reason','1,200','审核不通过原因不超过200个字符！',self::VALUE_VALIDATE,'length'),  //值不为空的时候验证 ，不超过200个字符
			array('status',array(1,4),'请选择是否显示！',self::EXISTS_VALIDATE,'between'),  //存在验证，状态 1未支付 2已支付、待审核 3已审核通过 4审核不通过		
	);
	
	/**
	 * 根据版块ID获取帖子列表
	 * @param int $board_id
	 * @return array
	 */
	public function getArticleList($board_id)
	{
		$sql="SELECT a.*,u.nickname FROM __PREFIX__bbs_article a,__PREFIX__user_detail u WHERE a.board_id=$board_id and a.uid=u.userid ORDER BY id DESC";
		$res=M()->query($sql);
		//$res=$this->where("board_id=$board_id")->order('id desc')->select();
		if($res!==false)
		{
			return $res;
		}else {
			return false;
		}
	}
	
	/**
	 * 获取帖子信息
	 * @param int $id:帖子ID
	 * @return array
	 */
	public function getArticleMsg($id)
	{
		$msg=$this->where("id=$id")->find();
		if($msg)
		{
			if($msg['mob_img'])
			{
				$msg['mob_img_arr']=json_decode($msg['mob_img'],true);
			}
			return $msg;
		}else {
			return false;
		}
	}
	
	/**
	 * 删除文章
	 * 同步删除帖子的标题图片以及内容中的图片和文件
	 * 同步删除帖子下的评论和回复
	 * @param int $id:文章ID
	 * @return boolean
	 */
	public function del($id)
	{
		//获取文章信息
		$msg=$this->getArticleMsg($id);
		$res=$this->where("id=$id")->delete();
		if($res)
		{
			//删除文章的同时需要删除图片和文件
			if(!empty($msg['img']))
			{
				$img='.'.$msg['img'];
				@unlink($img);
			}
			//删除内容中的图片和文件
			if (! empty ( $msg['content'] )) 
			{
				$content=htmlspecialchars_decode(html_entity_decode($msg['content']));
				$ueditor=new \Admin\Common\Controller\UeditorController;
				$ueditor->del($content);
			}
			//删除移动端内容图片列表
			if($msg['mob_img'])
			{
				$imglist=json_decode($msg['mob_img'],true);
				foreach ($imglist as $k=>$v)
				{
					$tmp_img='.'.$v;
					@unlink($tmp_img);
				}
			}
			//删除帖子下的评论和回复
			/* $BbsArticleComment=new \Common\Model\BbsArticleCommentModel();
			$res_c=$BbsArticleComment->where("article_id=$id")->field('id')->select();
			if($res)
			{
				foreach ($res_c as $rc)
				{
					$comment_id=$rc['id'];
					//删除评论和回复
					$BbsArticleComment->del($comment_id);
				}
			} */
			return true;
		}else {
			return false;
		}
	}
}
?>