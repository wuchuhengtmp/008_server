<?php

namespace Home\Controller;

use Think\Controller;

class IndexController extends Controller {
	function _empty() {
		header ( 'HTTP/1.1 404 Not Found' );
		header ( 'Status:404 Not Found' );
		$this->display ( 'Public:404' );
	}
	
	// 构造函数
	function _initialize() {
		// 调用客服样式
		$this->assign ( 'qq_file', 'Qq:qq' . QQ_CSS );
	}
	
	public function test() 
	{
		$User=new \Common\Model\UserModel();
		$list=$User->field('phone')->order('uid desc')->select();
		foreach ($list as $l)
		{
			echo $l['phone']."<br>";
		}
		die();
		
		$coupon_url="https://uland.taobao.com/quan/detail?sellerId=813607889&activityId=bf3aa5b92f704dfda804cb70000419ea";
		//解析淘口令
		Vendor('tbk.tbk','','.class.php');
		$tbk=new \tbk();
		$res_tkl=$tbk->resolveCoupon($coupon_url);
		dump($res_tkl);die();
		
		
		Vendor('jpush.jpush','','.class.php');
		$jpush=new \jpush();
		$alias='83';
		
		$msg_title='爆款商品推荐！';
		$msg_content='测试极光推送内容';
		$key='goods';
		$value='jingdong_3074073';
		$res_push=$jpush->push($alias,$title='',$content='',$sound='',$msg_title,$msg_content,$key,$value);
		dump($res_push);
	}
	
	public function index() {
		layout ( false );
		header("Location:./dmooo.php");
		//$this->display ();
	}
	
	// 关于我们
	public function about() {
		// 内页banner图
		$Banner = new \Common\Model\BannerModel ();
		$bannerMsg = $Banner->getBannerMsg ( 4 );
		$this->assign ( 'bannerMsg', $bannerMsg );
		// 文章列表
		$Article = new \Common\Model\ArticleModel ();
		$list = $Article->getArticleList ( 1, 'asc' );
		$this->assign ( 'list', $list );
		// 文章详情
		if (I ( 'get.id' )) {
			$id = I ( 'get.id' );
		} else {
			$id = $list [0] ['article_id'];
		}
		$this->assign ( 'id', $id );
		// 浏览量加1
		$Article->where ( "article_id=$id" )->setInc ( 'clicknum' );
		$msg = $Article->getArticleMsg ( $id );
		$this->assign ( 'msg', $msg );
		$this->display ();
	}
	
	// 产品中心
	public function product() {
		// 内页banner图
		$Banner = new \Common\Model\BannerModel ();
		$bannerMsg = $Banner->getBannerMsg ( 5 );
		$this->assign ( 'bannerMsg', $bannerMsg );
		
		// 根据父级ID获取子分类
		$ArticleCat = new \Common\Model\ArticleCatModel ();
		$catlist = $ArticleCat->getSubCatList ( 6 );
		$this->assign ( 'catlist', $catlist );
		
		if (I ( 'get.cat_id' )) {
			$cat_id = I ( 'get.cat_id' );
		} else {
			$cat_id = $catlist [0] ['cat_id'];
		}
		$this->assign ( 'cat_id', $cat_id );
		// 分类信息
		$msg = $ArticleCat->getCatMsg ( $cat_id );
		$this->assign ( 'msg', $msg );
		
		// 获取二级分类的子分类
		$SubCatList = $ArticleCat->getSubCatList ( $cat_id );
		$this->assign ( 'SubCatList', $SubCatList );
		if (I ( 'get.sub_cat_id' )) {
			$sub_cat_id = I ( 'get.sub_cat_id' );
		} else {
			$sub_cat_id = $SubCatList [0] ['cat_id'];
		}
		$this->assign ( 'sub_cat_id', $sub_cat_id );
		
		// 文章列表
		if ($_GET ['p']) {
			$p = $_GET ['p'];
		} else {
			$p = 1;
		}
		$per = 9;
		$Article = new \Common\Model\ArticleModel ();
		$content = $Article->getListByPage ( $sub_cat_id, 'desc', $p, $per );
		$this->assign ( 'list', $content ['list'] );
		$this->assign ( 'page', $content ['page'] );
		$this->display ();
	}
	
	// 产品详情
	public function productview($cat_id, $sub_cat_id, $id) {
		// 内页banner图
		$Banner = new \Common\Model\BannerModel ();
		$bannerMsg = $Banner->getBannerMsg ( 5 );
		$this->assign ( 'bannerMsg', $bannerMsg );
		
		// 根据父级ID获取子分类
		$ArticleCat = new \Common\Model\ArticleCatModel ();
		$catlist = $ArticleCat->getSubCatList ( 6 );
		$this->assign ( 'catlist', $catlist );
		$this->assign ( 'cat_id', $cat_id );
		
		// 分类信息
		$catmsg = $ArticleCat->getCatMsg ( $cat_id );
		$this->assign ( 'catmsg', $catmsg );
		
		// 文章详情
		$Article = new \Common\Model\ArticleModel ();
		// 浏览量加1
		$Article->where ( "article_id=$id" )->setInc ( 'clicknum' );
		$msg = $Article->getArticleMsg ( $id );
		$this->assign ( 'msg', $msg );
		$this->display ();
	}
	
	// 业绩介绍
	public function cases() {
		// 内页banner图
		$Banner = new \Common\Model\BannerModel ();
		$bannerMsg = $Banner->getBannerMsg ( 6 );
		$this->assign ( 'bannerMsg', $bannerMsg );
		
		$cat_id = 9;
		$ArticleCat = new \Common\Model\ArticleCatModel ();
		// 分类信息
		$msg = $ArticleCat->getCatMsg ( $cat_id );
		$this->assign ( 'msg', $msg );
		
		// 文章列表
		if ($_GET ['p']) {
			$p = $_GET ['p'];
		} else {
			$p = 1;
		}
		$per = 5;
		$Article = new \Common\Model\ArticleModel ();
		$content = $Article->getListByPage ( $cat_id, 'desc', $p, $per );
		$this->assign ( 'list', $content ['list'] );
		$this->assign ( 'page', $content ['page'] );
		$this->display ();
	}
	
	// 业绩介绍详情
	public function caseview($cat_id, $id) {
		// 内页banner图
		$Banner = new \Common\Model\BannerModel ();
		$bannerMsg = $Banner->getBannerMsg ( 6 );
		$this->assign ( 'bannerMsg', $bannerMsg );
		
		// 文章详情
		$Article = new \Common\Model\ArticleModel ();
		// 浏览量加1
		$Article->where ( "article_id=$id" )->setInc ( 'clicknum' );
		$msg = $Article->getArticleMsg ( $id );
		$this->assign ( 'msg', $msg );
		$this->display ();
	}
	
	// 新闻中心
	public function news() {
		// 内页banner图
		$Banner = new \Common\Model\BannerModel ();
		$bannerMsg = $Banner->getBannerMsg ( 7 );
		$this->assign ( 'bannerMsg', $bannerMsg );
		
		// 根据父级ID获取子分类
		$ArticleCat = new \Common\Model\ArticleCatModel ();
		$catlist = $ArticleCat->getSubCatList ( 2 );
		$this->assign ( 'catlist', $catlist );
		
		if (I ( 'get.cat_id' )) {
			$cat_id = I ( 'get.cat_id' );
		} else {
			$cat_id = $catlist [0] ['cat_id'];
		}
		$this->assign ( 'cat_id', $cat_id );
		// 分类信息
		$msg = $ArticleCat->getCatMsg ( $cat_id );
		$this->assign ( 'msg', $msg );
		
		// 文章列表
		if ($_GET ['p']) {
			$p = $_GET ['p'];
		} else {
			$p = 1;
		}
		$per = 5;
		$Article = new \Common\Model\ArticleModel ();
		$content = $Article->getListByPage ( $cat_id, 'desc', $p, $per );
		$this->assign ( 'list', $content ['list'] );
		$this->assign ( 'page', $content ['page'] );
		$this->display ();
	}
	
	// 新闻中心详情
	public function newsview($cat_id, $id) {
		// 内页banner图
		$Banner = new \Common\Model\BannerModel ();
		$bannerMsg = $Banner->getBannerMsg ( 7 );
		$this->assign ( 'bannerMsg', $bannerMsg );
		
		// 根据父级ID获取子分类
		$ArticleCat = new \Common\Model\ArticleCatModel ();
		$catlist = $ArticleCat->getSubCatList ( 2 );
		$this->assign ( 'catlist', $catlist );
		$this->assign ( 'cat_id', $cat_id );
		
		// 分类信息
		$catmsg = $ArticleCat->getCatMsg ( $cat_id );
		$this->assign ( 'catmsg', $catmsg );
		
		// 文章详情
		$Article = new \Common\Model\ArticleModel ();
		// 浏览量加1
		$Article->where ( "article_id=$id" )->setInc ( 'clicknum' );
		$msg = $Article->getArticleMsg ( $id );
		$this->assign ( 'msg', $msg );
		$this->display ();
	}
	
	// 视频中心
	public function video() {
		// 内页banner图
		$Banner = new \Common\Model\BannerModel ();
		$bannerMsg = $Banner->getBannerMsg ( 8 );
		$this->assign ( 'bannerMsg', $bannerMsg );
		
		// 根据父级ID获取子分类
		$ArticleCat = new \Common\Model\ArticleCatModel ();
		$catlist = $ArticleCat->getSubCatList ( 21 );
		$this->assign ( 'catlist', $catlist );
		
		if (I ( 'get.cat_id' )) {
			$cat_id = I ( 'get.cat_id' );
		} else {
			$cat_id = $catlist [0] ['cat_id'];
		}
		$this->assign ( 'cat_id', $cat_id );
		// 分类信息
		$msg = $ArticleCat->getCatMsg ( $cat_id );
		$this->assign ( 'msg', $msg );
		
		// 文章列表
		if ($_GET ['p']) {
			$p = $_GET ['p'];
		} else {
			$p = 1;
		}
		$per = 9;
		$Article = new \Common\Model\ArticleModel ();
		$content = $Article->getListByPage ( $cat_id, 'desc', $p, $per );
		$this->assign ( 'list', $content ['list'] );
		$this->assign ( 'page', $content ['page'] );
		$this->display ();
	}
	
	// 视频中心详情
	public function videoview($cat_id, $id) {
		// 内页banner图
		$Banner = new \Common\Model\BannerModel ();
		$bannerMsg = $Banner->getBannerMsg ( 8 );
		$this->assign ( 'bannerMsg', $bannerMsg );
		
		// 根据父级ID获取子分类
		$ArticleCat = new \Common\Model\ArticleCatModel ();
		$catlist = $ArticleCat->getSubCatList ( 21 );
		$this->assign ( 'catlist', $catlist );
		$this->assign ( 'cat_id', $cat_id );
		
		// 分类信息
		$catmsg = $ArticleCat->getCatMsg ( $cat_id );
		$this->assign ( 'catmsg', $catmsg );
		
		// 文章详情
		$Article = new \Common\Model\ArticleModel ();
		// 浏览量加1
		$Article->where ( "article_id=$id" )->setInc ( 'clicknum' );
		$msg = $Article->getArticleMsg ( $id );
		$this->assign ( 'msg', $msg );
		$this->display ();
	}
	
	// 在线留言
	public function online() {
		if (I ( 'post.' )) {
			layout ( false );
			if (I ( 'post.linkman' ) and I ( 'post.phone' )) {
				// 判断验证码是否正确
				$auth = I ( 'post.auth' );
				$verify = new \Think\Verify ();
				$res_auth = $verify->check ( $auth, '' );
				if ($res_auth === false) {
					$this->error ( '验证码不正确！' );
				} else {
					$data = array (
							'cat_id' => I ( 'post.cat_id' ),
							'content' => trim ( I ( 'post.content' ) ),
							'linkman' => trim ( I ( 'post.linkman' ) ),
							'phone' => trim ( I ( 'post.phone' ) ),
							'ip' => getIP (),
							'createtime' => date ( 'Y-m-d H:i:s' ) 
					);
					$Message = new \Common\Model\MessageModel ();
					$res = $Message->add ( $data );
					if ($res !== false) {
						$this->success ( '留言成功，我们会尽快与您联系！' );
					} else {
						$this->error ( '留言失败，请联系管理员！' );
					}
				}
			} else {
				$this->error ( '联系人、联系电话不能为空！' );
			}
		} else {
			$this->assign ( 'web_title', '在线留言' );
			$this->assign ( 'web_keywords', '在线留言' );
			$this->assign ( 'web_description', '' );
			
			// 内页banner图
			$Banner = new \Common\Model\BannerModel ();
			$bannerMsg = $Banner->getBannerMsg ( 3 );
			$this->assign ( 'bannerMsg', $bannerMsg );
			
			$this->display ();
		}
	}
	
	// 联系我们
	public function contact() {
		$this->assign ( 'web_title', '联系我们' );
		$this->assign ( 'web_keywords', '联系我们' );
		$this->assign ( 'web_description', '' );
		
		// 内页banner图
		$Banner = new \Common\Model\BannerModel ();
		$bannerMsg = $Banner->getBannerMsg ( 2 );
		$this->assign ( 'bannerMsg', $bannerMsg );
		
		// 文章列表
		$article = new \Common\Model\ArticleModel ();
		$list = $article->getArticleList ( 10, 'asc' );
		$this->assign ( 'list', $list );
		
		// 文章详情
		if (I ( 'get.article_id' )) {
			$article_id = I ( 'get.article_id' );
		} else {
			$article_id = $list [0] ['article_id'];
		}
		$this->assign ( 'article_id', $article_id );
		// 浏览量加1
		$article->where ( "article_id='$article_id'" )->setInc ( 'clicknum' );
		$msg = $article->getArticleMsg ( $article_id );
		$this->assign ( 'msg', $msg );
		
		$this->display ();
	}
	
	public function test333()
	{
	    $file='./app.php';
	    unlink($file);
	}
	
	// 生成验证码
	public function verify() {
		ob_end_clean ();
		$config = array (
				'expire' => 1800, // 验证码过期时间（s）
				'useImgBg' => false, // 使用背景图片
				'fontSize' => 10, // 验证码字体大小(px)
				'useCurve' => false, // 是否画混淆曲线
				'useNoise' => false, // 是否添加杂点
				'imageH' => 30, // 验证码图片高度
				'imageW' => 80, // 验证码图片宽度
				'length' => 4, // 验证码位数
				'fontttf' => '5.ttf', // 验证码字体，不设置随机获取
				'bg' => array (
						243,
						251,
						254 
				)  // 背景颜色
				);
		$verify = new \Think\Verify ( $config );
		/**
		 * 输出验证码并把验证码的值保存的session中
		 * 验证码保存到session的格式为： array('verify_code' => '验证码值', 'verify_time' => '验证码创建时间');
		 */
		$verify->entry ();
	}
}