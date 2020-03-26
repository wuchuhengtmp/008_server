<?php
/**
 * 文章管理接口
 */
namespace App\Controller;
use App\Common\Controller\AuthController;

class ArticleController extends AuthController
{
	/**
	 * 获取文章内容
	 * @param int $article_id:文章ID
	 * @return array
	 * @return @param code:返回码
	 * @return @param msg:返回码说明
	 * @return @param data:返回数据
	 * @return @param data->article_msg:文章内容
	 */
	public function getArticleMsg()
	{
		if(trim(I('post.article_id')))
		{
			$article_id=trim(I('post.article_id'));
			$Article=new \Common\Model\ArticleModel();
			$ArticleMsg=$Article->getArticleMsg($article_id);
			if($ArticleMsg!==false)
			{
				//将内容中的图片替换为绝对路径
				$Ueditor=new \Admin\Common\Controller\UeditorController();
				$ArticleMsg['content']=$Ueditor->changeImagePath($ArticleMsg['content']);
				
				//浏览量加1
				$Article->where("article_id=$article_id")->setInc('clicknum');
				
				$data=array(
						'article_msg'=>$ArticleMsg
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
	
	/**
	 * 获取文章列表
	 * @param int $cat_id:文章分类ID
	 * @param int $search:搜索内容
	 * @param int $p:页码，默认第1页
	 * @param int $per:每页条数，默认6条
	 * @return array
	 * @return @param code:返回码
	 * @return @param msg:返回码说明
	 * @return @param data:返回数据
	 * @return @param data->list:文章列表
	 */
	public function getArticleList()
	{
		$where="is_show='Y'";
		if(trim(I('post.cat_id')))
		{
			$cat_id=trim(I('post.cat_id'));
			//$where.=" and cat_id='$cat_id'";
			//获取所有子分类列表
			$ArticleCat=new \Common\Model\ArticleCatModel();
			$sublist=$ArticleCat->getSubCatList($cat_id);
			$cat_allid=$cat_id.',';
			foreach ($sublist as $sl)
			{
				$cat_allid.=$sl['cat_id'].',';
			}
			$cat_allid=substr($cat_allid, 0,-1);
			$where.=" and cat_id in ($cat_allid)";
		}
		if(trim(I('post.search')))
		{
			$search=trim(I('post.search'));
			$where.=" and (title like '%$search%' or keywords like '%$search%' or description like '%$search%' or content like '%$search%')";
		}
		if(trim(I('post.p'))) {
			$p=trim(I('post.p'));
		}else {
			$p=1;
		}
		if(trim(I('post.per'))) {
			$per=trim(I('post.per'));
		}else {
			$per=6;
		}
		$Article=new \Common\Model\ArticleModel();
		$list=$Article->where($where)->field('article_id,cat_id,title,author,keywords,description,pubtime,img,bigimg,file,clicknum,href')->page($p,$per)->order("is_top desc,sort desc,article_id desc")->select();
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
	 * 获取文章子分类列表
	 * @param int $pid:父级分类ID
	 * @return array
	 * @return @param code:返回码
	 * @return @param msg:返回码说明
	 * @return @param data:返回数据
	 * @return @param data->sublist:文章子分类列表
	 */
	public function getSubCatList()
	{
		if(trim(I('post.pid')))
		{
			$pid=trim(I('post.pid'));
			$ArticleCat=new \Common\Model\ArticleCatModel();
			$sublist=$ArticleCat->getSubCatList($pid,'asc','Y');
			if($sublist!==false)
			{
				$data=array(
						'sublist'=>$sublist
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
	
	/**
	 * 常见问题
	 * @return array
	 * @return @param code:返回码
	 * @return @param msg:返回码说明
	 * @return @param data:返回数据
	 */
	public function questions()
	{
		//获取常见问题子分类
		$ArticleCat=new \Common\Model\ArticleCatModel();
		$catlist=$ArticleCat->where("is_show='Y' and parent_id=2")->field('cat_id,cat_name,img')->order('sort desc,cat_id asc')->select();
		//获取分类下文章列表
		$Article=new \Common\Model\ArticleModel();
		$num=count($catlist);
		for($i=0;$i<$num;$i++)
		{
			$cat_id=$catlist[$i]['cat_id'];
			$articleList=$Article->where("is_show='Y' and cat_id=$cat_id")->field('article_id,title')->order('is_top desc,sort desc,article_id asc')->select();
			$catlist[$i]['articleList']=$articleList;
		}
		$data=array(
				'list'=>$catlist
		);
		$res=array(
				'code'=>$this->ERROR_CODE_COMMON['SUCCESS'],
				'msg'=>'成功',
				'data'=>$data
		);
		echo json_encode ($res,JSON_UNESCAPED_UNICODE);
	}
	
	/**
	 * 获取版本信息
	 * @return array
	 * @return @param code:返回码
	 * @return @param msg:返回码说明
	 * @return @param data:返回数据
	 */
	public function version()
	{
		$data=array(
			'down_url'=>WEB_URL.'/wap.php/Index/down',
			'version_ios'=>VERSION_IOS,
			'version_android'=>VERSION_ANDROID,
			'down_android'=>DOWN_ANDROID,
			'down_ios'=>DOWN_IOS,
			'content'=>UPDATE_CONTENT_ANDROID,
			'share_url'=>SHARE_URL,//分享淘宝商品网址
			'share_url_register'=>SHARE_URL_REGISTER,//分享注册下载网址
		    'share_url_vip'=>SHARE_URL_VIP,//VIP专用分享网址
			'vy_url_s'=>'http://tmp.vephp.com',//维易超级搜索接口地址
		    'vy_url_c'=>'http://tmp.vephp.com',//维易高佣接口地址
		);
		$res=array(
				'code'=>0,
				'msg'=>'成功',
				'data'=>$data
		);
		echo json_encode ($res,JSON_UNESCAPED_UNICODE);
	}
}
?>