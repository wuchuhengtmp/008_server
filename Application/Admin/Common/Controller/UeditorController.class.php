<?php
/**
 * @author 点墨设计-逐风
 * 网站：http://www.dmooo.com
 * 对ueditor文件进行增删改操作
 * 注意：请建立对应存储文件夹
 */
namespace Admin\Common\Controller;
use Think\Controller;

class UeditorController extends Controller
{
	//新增内容
	//转移ueditor文件：将file和img从ueditor_tmp文件夹中转移到正式目录ueditor中
	public function add($content)
	{
		// 正则表达式查找文件路径
		$pattern_file = '/href=".*?(\/Public\/Upload\/ueditor_temp\/file\/.*?)"/i';
		preg_match_all ( $pattern_file, $content, $res_file );
		$file_num = count ( $res_file [1] );
		for($ii = 0; $ii < $file_num; $ii ++) 
		{
			$ueditor_file = $res_file [1] [$ii];
			// 新建日期文件夹
			$tmp_arr = explode ( '/', $ueditor_file );
			$datefloder = './Public/Upload/ueditor/file/' . $tmp_arr [5];
			if (! is_dir ( $datefloder )) 
			{
				mkdir ( $datefloder, 0777 );
			}
			$tmpfile = '.' . $ueditor_file;
			$newfile = str_replace ( '/ueditor_temp/', '/ueditor/', $tmpfile );
			// 转移文件
			rename ( $tmpfile, $newfile );
		}
		
		// 正则表达式查找视频路径
		$pattern_video = '/src=".*?(\/Public\/Upload\/ueditor_temp\/video\/.*?)"/i';
		preg_match_all ( $pattern_video, $content, $res_video );
		$video_num = count ( $res_video [1] );
		for($ii = 0; $ii < $video_num; $ii ++) 
		{
			$ueditor_video = $res_video [1] [$ii];
			// 新建日期文件夹
			$tmp_arr = explode ( '/', $ueditor_video );
			$datefloder = './Public/Upload/ueditor/video/' . $tmp_arr [5];
			if (! is_dir ( $datefloder )) 
			{
				mkdir ( $datefloder, 0777 );
			}
			$tmpvideo = '.' . $ueditor_video;
			$newvideo = str_replace ( '/ueditor_temp/', '/ueditor/', $tmpvideo );
			// 转移文件
			rename ( $tmpvideo, $newvideo );
		}
		
		// 正则表达式匹配查找图片路径
		$pattern = '/<[img|IMG].*?src=[\'|\"].*?(\/Public\/Upload\/ueditor_temp\/image\/.*?(?:[\.gif|\.jpg|\.jpeg|\.png]))[\'|\"].*?[\/]?>/i';
		preg_match_all ( $pattern, $content, $res );
		$num = count ( $res [1] );
		for($i = 0; $i < $num; $i ++) 
		{
			$ueditor_img = $res [1] [$i];
			// 新建日期文件夹
			$tmp_arr = explode ( '/', $ueditor_img );
			$datefloder = './Public/Upload/ueditor/image/' . $tmp_arr [5];
			if (! is_dir ( $datefloder )) 
			{
				mkdir ( $datefloder, 0777 );
			}
			$tmpimg = '.' . $ueditor_img;
			$newimg = str_replace ( '/ueditor_temp/', '/ueditor/', $tmpimg );
			// 转移图片
			rename ( $tmpimg, $newimg );
		}
		$content = str_replace ( '/ueditor_temp/', '/ueditor/', $content );
		$content = str_replace('/Public/Upload/','http://'.$_SERVER['HTTP_HOST'].'/Public/Upload/',$content);
		return $content;
	}
	
	//编辑内容
	//先上传新的内容，再删除原有内容中被删除的文件
	public function edit($content,$oldcontent)
	{
		if (! empty ( $content )) 
		{
			// 正则表达式查找文件路径
			$pattern_file = '/href=".*?(\/Public\/Upload\/(ueditor_temp|ueditor)\/file\/.*?)"/i';
			preg_match_all ( $pattern_file, $content, $res_file );
			$file_num = count ( $res_file [1] );
			for($ii = 0; $ii < $file_num; $ii ++) 
			{
				$ueditor_file = $res_file [1] [$ii];
				if ($res_file [2] [$ii] == 'ueditor_temp') 
				{
					// 新建日期文件夹
					$tmp_arr = explode ( '/', $ueditor_file );
					$datefloder = './Public/Upload/ueditor/file/' . $tmp_arr [5];
					if (! is_dir ( $datefloder )) 
					{
						mkdir ( $datefloder, 0777 );
					}
					$tmpfile = '.' . $ueditor_file;
					$newfile = str_replace ( '/ueditor_temp/', '/ueditor/', $tmpfile );
					// 转移文件
					rename ( $tmpfile, $newfile );
				} else {
					//除了剪切之后文件路径前面加上网址的
					$pos = stripos ( $ueditor_file, WEB_URL );
					if($pos!==false)
					{
						$ueditor_file2 = str_replace ( WEB_URL, '', $ueditor_file );
						$filearr [] = $ueditor_file2;
					}
					$filearr [] = $ueditor_file;
				}
			}
			
			// 正则表达式查找视频路径
			$pattern_video = '/src=".*?(\/Public\/Upload\/(ueditor_temp|ueditor)\/video\/.*?)"/i';
			preg_match_all ( $pattern_video, $content, $res_video );
			$video_num = count ( $res_video [1] );
			for($ii = 0; $ii < $video_num; $ii ++) 
			{
				$ueditor_video = $res_video [1] [$ii];
				if ($res_video [2] [$ii] == 'ueditor_temp') 
				{
					// 新建日期文件夹
					$tmp_arr = explode ( '/', $ueditor_video );
					$datefloder = './Public/Upload/ueditor/video/' . $tmp_arr [5];
					if (! is_dir ( $datefloder )) 
					{
						mkdir ( $datefloder, 0777 );
					}
					$tmpvideo = '.' . $ueditor_video;
					$newvideo = str_replace ( '/ueditor_temp/', '/ueditor/', $tmpvideo );
					// 转移文件
					rename ( $tmpvideo, $newvideo );
				} else {
					// 除了剪切之后文件路径前面加上网址的
					$pos = stripos ( $ueditor_video, WEB_URL );
					if ($pos !== false) 
					{
						$ueditor_video2 = str_replace ( WEB_URL, '', $ueditor_video );
						$videoarr [] = $ueditor_video2;
					}
					$videoarr [] = $ueditor_video;
				}
			}
			
			// 正则表达式匹配查找图片路径
			$pattern = '/<[img|IMG].*?src=[\'|\"](.*?(?:[\.gif|\.jpg|\.jpeg|\.png]))[\'|\"].*?[\/]?>/i';
			preg_match_all ( $pattern, $content, $res );
			$num = count ( $res [1] );
			for($i = 0; $i < $num; $i ++) 
			{
				$ueditor_img = $res [1] [$i];
				// 判断是否是新上传的图片
				$pos = stripos ( $ueditor_img, "/ueditor_temp/" );
				if ($pos > 0) 
				{
					// 新建日期文件夹
					$tmp_arr = explode ( '/', $ueditor_img );
					$datefloder = './Public/Upload/ueditor/image/' . $tmp_arr [5];
					if (! is_dir ( $datefloder )) 
					{
						mkdir ( $datefloder, 0777 );
					}
					$tmpimg = '.' . $ueditor_img;
					$newimg = str_replace ( '/ueditor_temp/', '/ueditor/', $tmpimg );
					// 转移图片
					rename ( $tmpimg, $newimg );
				} else {
					//除了剪切之后图片路径前面加上网址的
					$pos = stripos ( $ueditor_img, WEB_URL );
					if($pos!==false)
					{
						$ueditor_img2 = str_replace ( WEB_URL, '', $ueditor_img );
						$imgarr [] = $ueditor_img2;
					}
					$imgarr [] = $ueditor_img;
				}
			}
			$content = str_replace ( '/ueditor_temp/', '/ueditor/', $content );
			$content = str_replace('/Public/Upload/','http://'.$_SERVER['HTTP_HOST'].'/Public/Upload/',$content);
		}
		
		// 删除在编辑时被删除的原有文件
		if (! empty ( $oldcontent )) 
		{
			// 正则表达式匹配查找原文件路径
			$pattern_file = '/href=".*?(\/Public\/Upload\/ueditor\/file\/.*?)"/i';
			preg_match_all ( $pattern_file, $oldcontent, $oldres_file );
			$fnum = count ( $oldres_file [1] );
			for($i = 0; $i < $fnum; $i ++) 
			{
				$delfile = $oldres_file [1] [$i];
				if (! in_array ( $delfile, $filearr )) 
				{
					$delfile = '.' . $delfile;
					unlink ( $delfile );
				}
			}
			
			// 正则表达式匹配查找原视频路径
			$pattern_video = '/src=".*?(\/Public\/Upload\/ueditor\/video\/.*?)"/i';
			preg_match_all ( $pattern_video, $oldcontent, $oldres_video );
			$fnum = count ( $oldres_video [1] );
			for($i = 0; $i < $fnum; $i ++) 
			{
				$delvideo = $oldres_video [1] [$i];
				if (! in_array ( $delvideo, $videoarr )) 
				{
					$delvideo = '.' . $delvideo;
					unlink ( $delvideo );
				}
			}
			
			// 正则表达式匹配查找图片路径
			$pattern = '/<[img|IMG].*?src=[\'|\"](.*?(?:[\.gif|\.jpg|\.jpeg|\.png]))[\'|\"].*?[\/]?>/i';
			preg_match_all ( $pattern, $oldcontent, $oldres );
			$num = count ( $oldres [1] );
			for($i = 0; $i < $num; $i ++) 
			{
				$delimg = $oldres [1] [$i];
				if (! in_array ( $delimg, $imgarr )) 
				{
					$delimg = '.' . $delimg;
					unlink ( $delimg );
				}
			}
		}
		return $content;
	}
	
	//删除内容
	public function del($content)
	{
		if (! empty ( $content )) 
		{
			// 正则表达式匹配查找原文件路径
			$pattern_file = '/href=".*?(\/Public\/Upload\/ueditor\/file\/.*?)"/i';
			preg_match_all ( $pattern_file, $content, $res_file );
			$fnum = count ( $res_file [1] );
			for($i = 0; $i < $fnum; $i ++) 
			{
				$ueditor_file = $res_file [1] [$i];
				$delfile = '.' . $ueditor_file;
				unlink ( $delfile );
			}
			
			// 正则表达式匹配查找原视频路径
			$pattern_video = '/src=".*?(\/Public\/Upload\/ueditor\/video\/.*?)"/i';
			preg_match_all ( $pattern_video, $content, $res_video );
			$fnum = count ( $res_video [1] );
			for($i = 0; $i < $fnum; $i ++) 
			{
				$ueditor_video = $res_video [1] [$i];
				$delvideo = '.' . $ueditor_video;
				unlink ( $delvideo );
			}
			
			// 正则表达式匹配查找图片路径
			$pattern = '/<[img|IMG].*?src=[\'|\"].*?(\/Public\/Upload\/ueditor\/image\/.*?(?:[\.gif|\.jpg|\.jpeg|\.png]))[\'|\"].*?[\/]?>/i';
			preg_match_all ( $pattern, $content, $res );
			$num = count ( $res [1] );
			for($j = 0; $j < $num; $j ++) 
			{
				$ueditor_img = $res [1] [$j];
				$oldimg = '.' . $ueditor_img;
				unlink ( $oldimg );
			}
		}
	}
	
	/**
	 * 将内容中的图片替换为绝对路径
	 * @param longtext $content:原内容
	 * @return longtext $new_content:替换为的新内容
	 */
	public function changeImagePath($content)
	{
		//将内容中的图片替换为绝对路径
		//提取图片路径的src的正则表达式
		$pattern = '/<[img|IMG].*?src=[\'|\"].*?(\/Public\/Upload\/ueditor\/image\/.*?(?:[\.gif|\.jpg|\.jpeg|\.png]))[\'|\"].*?[\/]?>/i';
		preg_match_all($pattern,$content,$res);
		$img = array();
		$num = count ( $res [1] );
		for($j = 0; $j < $num; $j ++)
		{
			$ueditor_img = $res [1] [$j];
			$img[] = $ueditor_img;
		}
		if (!empty($img))
		{
			$img_url = WEB_URL;
			$patterns= array();
			$replacements = array();
			foreach($img as $k=>$v)
			{
				$final_imgUrl = $img_url.$v;
				$replacements[] = $final_imgUrl;
				$img_new = "/".preg_replace("/\//i","\/",$v)."/";
				$patterns[] = $img_new;
			}
			//让数组按照key来排序
			ksort($patterns);
			ksort($replacements);
			//替换内容
			$new_content = preg_replace($patterns, $replacements, $content);
			return $new_content;
		}else {
			return $content;
		}
	}
	
	/**
	 * 识别内容中的图片列表
	 * @param longtext $content:原内容
	 * @return array $imglist:内容中的图片列表
	 */
	public function getImgList($content)
	{
		// 正则表达式匹配查找图片路径
		$pattern = '/<[img|IMG].*?src=[\'|\"](.*?(?:[\.gif|\.jpg|\.jpeg|\.png]))[\'|\"].*?[\/]?>/i';
		preg_match_all ( $pattern, $content, $imgres );
		$img_num = count ( $imgres [1] );
		$imglist=array();
		for($j = 0; $j < $img_num; $j ++)
		{
			$imglist[] = $imgres [1] [$j];
		}
		return $imglist;
	}
}