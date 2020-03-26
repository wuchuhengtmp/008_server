<?php
/**
 * 内容管理-文章管理
 */
namespace Common\Model;
use Think\Model;

class ArticleModel extends Model
{
	//验证规则
	protected $_validate =array(
			array('cat_id','require','文章分类名称不能为空！',self::EXISTS_VALIDATE),  //存在验证，必填
			array('cat_id','is_positive_int','请选择正确的文章分类',self::EXISTS_VALIDATE,'function'),  //存在验证，必须是正整数
			array('title','require','文章标题不能为空！',self::EXISTS_VALIDATE),  //存在验证，必填
			array('title','1,150','文章标题不超过150个字符！',self::EXISTS_VALIDATE,'length'),  //存在验证，不超过150个字符
			array('title_font_color','1,10','标题颜色不超过10个字符！',self::VALUE_VALIDATE,'length'),  //值不为空的时候验证，不超过10个字符
			array('author','1,100','作者不超过100个字符！',self::VALUE_VALIDATE,'length'),  //值不为空的时候验证，不超过100个字符
			array('keywords','1,255','关键词不超过255个字符！',self::VALUE_VALIDATE,'length'),  //值不为空的时候验证 ，不超过255个字符
			array('description','1,500','简要说明不超过500个字符！',self::VALUE_VALIDATE,'length'),  //值不为空的时候验证 ，不超过500个字符
			array('is_show','require','请选择是否显示！',self::EXISTS_VALIDATE),  //存在验证，必填
			array('is_show',array('Y','N'),'请选择是否显示！',self::EXISTS_VALIDATE,'in'),  //存在验证，只能是Y是 N否
			array('is_top','require','请选择是否推荐/置顶！',self::EXISTS_VALIDATE),  //存在验证，必填
			array('is_top',array('Y','N'),'请选择是否推荐/置顶！',self::EXISTS_VALIDATE,'in'),  //存在验证，只能是Y是 N否
			array('img','1,255','图片路径不正确！',self::VALUE_VALIDATE,'length'),  //值不为空的时候验证 ，不超过255个字符
			array('bigimg','1,255','大图片路径不正确！',self::VALUE_VALIDATE,'length'),  //值不为空的时候验证 ，不超过255个字符
			array('file','1,255','文件路径不正确！',self::VALUE_VALIDATE,'length'),  //值不为空的时候验证 ，不超过255个字符
			array('sort','is_natural_num','排序必须为不小于零的整数！',self::VALUE_VALIDATE,'function'),  //值不为空的时候验证 ，必须是自然数
			array('clicknum','is_natural_num','点击量必须为不小于零的整数！',self::VALUE_VALIDATE,'function'),  //值不为空的时候验证 ，必须是自然数
			array('href','url','不是正确的网址格式！',self::VALUE_VALIDATE),  //值不为空的时候验证 ，URL地址格式验证
			array('pubtime','require','创建时间不能为空！',self::EXISTS_VALIDATE),  //存在验证，必填
			array('pubtime','is_datetime','创建时间格式不正确！',self::EXISTS_VALIDATE,'function'),  //存在验证，必须是正确的时间格式
	);
	
	/**
	 * 根据分类ID获取文章列表
	 * @param int $cat_id:文章分类ID
	 * @param string $order:排序规则 desc降序 asc升序
	 * @param string $is_show:是否显示 Y显示 N不显示，默认显示全部
	 * @return array
	 */
	public function getArticleList($cat_id,$order='desc',$is_show='')
	{
		if($is_show)
		{
			$where="cat_id=$cat_id and is_show='$is_show'";
		}else {
			$where="cat_id=$cat_id";
		}
		$res=$this->where($where)->order("sort desc,article_id $order")->select();
		if($res!==false)
		{
			return $res;
		}else {
			return false;
		}
	}
	
	/**
	 * 获取分页文章列表
	 * @param int $cat_id:文章分类ID
	 * @param string $order:排序规则 desc降序 asc升序
	 * @param int $p:当前页码
	 * @param int $per:每页显示条数
	 * @return array|boolean
	 * @return array $list:当前页文章列表
	 * @return array $page:分页条
	 */
	public function getListByPage($cat_id,$order='desc',$p='1',$per)
	{
		//列表
		$where="cat_id=$cat_id and is_show='Y'";
		$list = $this->where($where)->page($p.','.$per)->order("is_top desc,sort desc,article_id $order")->select();
		if($list!==false)
		{
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
	 * 获取指定文章列表
	 * @param int $cat_id:文章分类ID
	 * @param string $order:排序规则 desc降序 asc升序
	 * @param int $start:开始条数
	 * @param int $num:条数
	 * @return array|boolean
	 */
	public function getListByLimit($cat_id,$order='desc',$start=0,$num)
	{
		//列表
		$where="cat_id=$cat_id and is_show='Y'";
		$list = $this->where($where)->limit($start,$num)->order("is_top desc,sort desc,article_id $order")->select();
		if($list!==false)
		{
			return $list;
		}else {
			return false;
		}
	}
	
	/**
	 * 获取指定文章列表-包含子分类
	 * @param int $cat_id:文章分类ID
	 * @param string $order:排序规则 desc降序 asc升序
	 * @param int $start:开始条数
	 * @param int $num:条数
	 * @return array|boolean
	 */
	public function getSubListByLimit($cat_id,$order='desc',$start=0,$num)
	{
		$ArticleCat=new \Common\Model\ArticleCatModel();
		$sublist=$ArticleCat->getSubCatList($cat_id);
		if($sublist)
		{
			foreach ($sublist as $sl)
			{
				$all_catid.=$sl['cat_id'].',';
			}
			if($all_catid!='')
			{
				$all_catid=$cat_id.','.$all_catid;
				$all_catid=substr($all_catid, 0,-1);
				$where="cat_id in ($all_catid) and is_show='Y'";
			}else {
				$where="cat_id=$cat_id and is_show='Y'";
			}
		}else {
			$where="cat_id=$cat_id and is_show='Y'";
		}
		//列表
		$list = $this->where($where)->limit($start,$num)->order("sort desc,article_id $order")->select();
		if($list!==false)
		{
			return $list;
		}else {
			return false;
		}
	}
	
	/**
	 * 获取文章信息
	 * @param int $article_id:文章ID
	 * @return array
	 */
	public function getArticleMsg($article_id)
	{
		$res=$this->where("article_id=$article_id")->find();
		if($res!==false)
		{
			return $res;
		}else {
			return false;
		}
	}
	
	/**
	 * 获取文章信息，包含图片列表
	 * @param int $article_id:文章ID
	 * @return array|boolean
	 */
	public function getArticleDetail($article_id)
	{
		$res=$this->where("article_id=$article_id")->find();
		if($res!==false)
		{
			$ArticleImg=new \Common\Model\ArticleImgModel();
			$imglist=$ArticleImg->getImgList($article_id);
			if($imglist!==false)
			{
				$res['imglist']=$imglist;
				return $res;
			}else {
				return false;
			}
		}else {
			return false;
		}
	}
	
	/**
	 * 获取上一篇、下一篇文章
	 * @param int $article_id:文章ID
	 * @return array
	 */
	public function getPreAndNextArticle($article_id)
	{
		//上一篇
		$res_pre=$this->where("is_show='Y' and article_id<$article_id")->field('article_id,title')->order("is_top desc,sort desc")->find();
		//下一篇
		$res_next=$this->where("is_show='Y' and article_id>$article_id")->field('article_id,title')->order("is_top desc,sort desc")->find();
		$msg=array(
				'pre_id'=>$res_pre['article_id'],
				'pre_title'=>$res_pre['title'],
				'next_id'=>$res_next['article_id'],
				'next_title'=>$res_next['title'],
		);
		return $msg;
	}
	
	/**
	 * 删除文章
	 * 同步删除文章的标题图片、大图片、文件、列表图片以及内容中的图片和文件
	 * @param int $id:文章ID
	 * @return boolean
	 */
	public function del($id)
	{
		//获取文章信息
		$res=$this->getArticleMsg($id);
		//删除文章的同时需要删除图片和文件
		$img=$res['img'];
		$bigimg=$res['bigimg'];
		$file=$res['file'];
		$content=htmlspecialchars_decode(html_entity_decode($res['content']));
		$res2=$this->where("article_id=$id")->delete();
		if($res2)
		{
			if(!empty($img))
			{
				$img='.'.$img;
				unlink($img);
			}
			if(!empty($bigimg))
			{
				$bigimg='.'.$bigimg;
				unlink($bigimg);
			}
			if(!empty($file))
			{
				$file='.'.$file;
				unlink($file);
			}
			// 删除内容中的图片和文件
			if (! empty ( $content )) 
			{
				$ueditor=new \Admin\Common\Controller\UeditorController;
				$ueditor->del($content);
			}
			//删除文章下的图片列表
			$ArticleImg=new \Common\Model\ArticleImgModel();
			$res_img=$ArticleImg->where("article_id='$id'")->select();
			$imgnum=count($res_img);
			if($imgnum>0)
			{
				$i=0;
				for($i=0;$i<$imgnum;$i++)
				{
					$id=$res_img[$i]['id'];
					$res1=$ArticleImg->getImgMsg($id);
					$img=$res1['img'];
					$res=$ArticleImg->where("id=$id")->delete();
					if($res)
					{
						//删除图片
						if(!empty($img))
						{
							$img='.'.$img;
							unlink($img);
						}
					}
				}
			}
			return true;
		}else {
			return false;
		}
	}
}