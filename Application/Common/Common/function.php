<?php
/* 公用函数库文件 */

//保留2位小数，四舍五不入
function keepTwoDecimalPlaces($money)
{
    $money=substr(sprintf("%.3f",$money),0,-1);
    return $money;
}

/**
 * 截取中文字符串
 * $suffix:后缀
 */
function msubstr($str,$start=0,$length,$suffix=true,$charset="utf-8")
{
	//mb_substr在于php中是默认不被支持的
	//需要在在windows目录下找到php.ini打开编辑，搜索mbstring.dll，找到;extension=php_mbstring.dll把前面的;号去掉才可以使用
	//mb_substr是按字来切分字符，而mb_strcut是按字节来切分字符
	if(function_exists("mb_substr"))
	{
		if ($suffix && mb_strlen($str, $charset)>$length)
		{
			return mb_substr($str, $start, $length, $charset).'...';
		}else{
			return mb_substr($str, $start, $length, $charset);
		}
	}elseif(function_exists('iconv_substr')) {
		//iconv_substr是按照字符而非占用字节来计算
		if ($suffix && strlen($str)>$length)
		{
			return iconv_substr($str,$start,$length,$charset)."...";
		}else{
			return iconv_substr($str,$start,$length,$charset);
		}
	}
	$re['utf-8']   = "/[\x01-\x7f]|[\xc2-\xdf][\x80-\xbf]|[\xe0-\xef][\x80-\xbf]{2}|[\xf0-\xff][\x80-\xbf]{3}/";
	$re['gb2312'] = "/[\x01-\x7f]|[\xb0-\xf7][\xa0-\xfe]/";
	$re['gbk']    = "/[\x01-\x7f]|[\x81-\xfe][\x40-\xfe]/";
	$re['big5']   = "/[\x01-\x7f]|[\x81-\xfe]([\x40-\x7e]|\xa1-\xfe])/";
	preg_match_all($re[$charset], $str, $match);
	$slice = join("",array_slice($match[0], $start, $length));
	if($suffix) return $slice."…";
	return $slice;
}

//处理URL请求
function https_request($url,$data=null)
{
	$curl=curl_init();
	curl_setopt($curl,CURLOPT_URL,$url); //设置需要抓取的URL

	//curl_setopt($curl, CURLOPT_NOSIGNAL, 1);     //注意，毫秒超时一定要设置这个
	//curl_setopt($curl, CURLOPT_CONNECTTIMEOUT_MS, 0); //尝试连接等待的时间，以毫秒为单位。设置为0，则无限等待。
	//curl_setopt($curl, CURLOPT_TIMEOUT_MS, 0);    //设置cURL允许执行的最长毫秒数
	//curl_setopt($curl, CURLOPT_TIMEOUT, 0);//设置超时时间，0为不限制，秒为单位
	//curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, 0);//发起连接前等待的时间，0为不限制，秒为单位

	curl_setopt($curl,CURLOPT_SSL_VERIFYPEER,FALSE);
	curl_setopt($curl,CURLOPT_SSL_VERIFYHOST,FALSE);//校验当前的域名是否与公用名(common name)匹配
	if(!empty($data))
	{
		curl_setopt($curl,CURLOPT_POST,1); //以post方式传递数据
		curl_setopt($curl,CURLOPT_POSTFIELDS,$data);//传递一个作为HTTP "POST"操作的所有数据的字符串
	}
	curl_setopt($curl,CURLOPT_RETURNTRANSFER,1); //设置是否将响应结果存入变量，1是存入，0是直接输出；
	$output=curl_exec($curl); //运行cURL，请求网页
	curl_close($curl);  //关闭URL请求
	return $output;
}

/**
 * 生成随机字符串
 * $length:需要的长度
 * $type:
 * 1:纯数字（默认）
 * 2:纯字母（小写）
 * 3:纯字母（大写）
 * 4:数字与字母组合（只小写）
 * 5:数字与字母组合（只大写）
 * 6:数字与字母组合（大小写）
 */
function createRandom($length = 6, $type = '1')
{
	if (! ($length > 0))
		return false;
	$returnstr = '';
	switch ($type) {
		case 1 :
			for($i = 0; $i < $length; $i ++) {
				$returnstr .= rand ( 0, 9 );
			}
			break;
		case 2 :
			$strarr = array_merge ( range ( 'a', 'z' ) );
			shuffle ( $strarr );
			shuffle ( $strarr );
			$returnstr = implode ( '', array_slice ( $strarr, 0, $length ) );
			break;
		case 3 :
			$strarr = array_merge ( range ( 'A', 'Z' ) );
			shuffle ( $strarr );
			shuffle ( $strarr );
			$returnstr = implode ( '', array_slice ( $strarr, 0, $length ) );
			break;
		case 4 :
			$strarr = array_merge ( range ( 0, 9 ), range ( 'a', 'z' ) );
			shuffle ( $strarr );
			shuffle ( $strarr );
			$returnstr = implode ( '', array_slice ( $strarr, 0, $length ) );
			break;
		case 5 :
			$strarr = array_merge ( range ( 0, 9 ), range ( 'A', 'Z' ) );
			shuffle ( $strarr );
			shuffle ( $strarr );
			$returnstr = implode ( '', array_slice ( $strarr, 0, $length ) );
			break;
		case 6 :
			$strarr = array_merge ( range ( 0, 9 ), range ( 'a', 'z' ), range ( 'A', 'Z' ) );
			shuffle ( $strarr );
			shuffle ( $strarr );
			$returnstr = implode ( '', array_slice ( $strarr, 0, $length ) );
			break;
	}
	return $returnstr;
}

/**
 * 获取文件扩展名
 * $filename:文件名
 * 返回结果：后缀名，如.jpg
 */
function getFileExt($filename)
{
	//strrchr() 函数查找字符串在另一个字符串中最后一次出现的位置，并返回从该位置到字符串结尾的所有字符。
	return strrchr($filename, '.');
}

/**
 * 上传base64图片
 * @param string $base64:文件base64编码
 * @param string $path:文件保存路径
 * @param array $exts:允许上传的文件后缀
 * @param string $method:数据传输方式 POST GET
 * @return string|code
 * @return 成功返回文件完整路径
 * @return code:0成功 1不是正确的图片文件 2文件上传失败
 */
function base64_upload($base64,$path,$method='post')
{
	//post的数据里面，加号会被替换为空格，需要重新替换回来；如果不是post的数据，则注释掉这一行
	if($method=='post')
	{
		$base64_file = str_replace(' ', '+', $base64);
	}
	if (preg_match('/^(data:\s*image\/(\w+);base64,)/', $base64_file, $result))
	{
		//匹配成功
		$file_name = uniqid().rand(100, 999).'.'.$result[2];
		$save_file = $path.$file_name;
		//服务器文件存储路径
		if (file_put_contents($save_file, base64_decode(str_replace($result[1], '', $base64_file))))
		{
			$res=array(
					'code'=>0,
					'msg'=>'成功',
					'file'=>$save_file
			);
		}else{
			$res=array(
					'code'=>2,
					'msg'=>'文件上传失败'
			);
		}
	}else{
		$res=array(
				'code'=>1,
				'msg'=>'不是正确的图片文件'
		);
	}
	return $res;
}

//获取毫秒时间戳
function get_millistime()
{
	$comps = explode(' ', microtime());
	return  sprintf('%d%03d', $comps[1], $comps[0] * 1000);
}

/**
 * 验证汉字
 * @param string $keyword:要验证的字符串
 * @return boolean:包含汉字返回false，不存在返回true
 */
function preg_match_chinese($keyword)
{
	$pattern='/[\x{4e00}-\x{9fa5}]/u';
	preg_match($pattern,$keyword,$res);
	if(!empty($res))
	{
		return false;
	}else {
		return true;
	}
}

/**
 * 验证是否为正整数
 * @param string $keyword:要验证的字符串
 * @return boolean:是正整数返回true，否则返回false
 */
function is_positive_int($keyword)
{
	$pattern='/^[1-9]\d*$/';
	preg_match($pattern,$keyword,$res);
	if(!empty($res))
	{
		return true;
	}else {
		return false;
	}
}

/**
 * 验证是否为自然数
 * @param string $keyword:要验证的字符串
 * @return boolean:是自然数返回true，否则返回false
 */
function is_natural_num ($keyword)
{
	$pattern='/^\d*$/';
	preg_match($pattern,$keyword,$res);
	if(!empty($res))
	{
		return true;
	}else {
		return false;
	}
}

/**
 * 验证是否为带小数两位
 * @param string $keyword:要验证的字符串
 * @return boolean:是返回true,否则返回false
 */
function is_decimal($keyword)
{
	$pattern = '/^(0\.)\d*[1-9]\d*$/';
	preg_match($pattern, $keyword,$res);
	if(!empty($res))
	{
		return true;
	}else{
		return false;
	}
}

/**
 * 验证是否为货币格式
 * @param string $keyword:要验证的字符串
 * @return boolean:是返回true,否则返回false
 */
function is_currency($keyword)
{
	$pattern = '/^\d+(\.\d+)?$/';
	preg_match($pattern, $keyword,$res);
	if(!empty($res))
	{
		return true;
	}else{
		return false;
	}
}

/**
 * 验证是否为时间格式
 * @param string $datetime:时间字符串
 * @param string $formats:需要检验的格式数组
 * @return boolean:是时间格式返回true，否则返回false
 */
function is_datetime($datetime, $formats = array("Y-m-d H:i:s", "Y/m/d H:i:s"))
{
	$unixTime = strtotime($datetime);
	if (!$unixTime)
	{
		//strtotime转换不对，日期格式显然不对。
		return false;
	}
	//校验日期的有效性，只要满足其中一个格式就OK
	foreach ($formats as $format)
	{
		if (date($format, $unixTime) == $datetime)
		{
			return true;
		}
	}
	return false;
}

/**
 * 验证是否为日期格式
 * @param string $date:日期字符串
 * @param string $formats:需要检验的格式数组
 * @return boolean:是时间格式返回true，否则返回false
 */
function is_date($date, $formats = array("Y-m-d", "Y/m/d")) 
{
	$unixTime = strtotime($date);
	if (!$unixTime)
	{
		//strtotime转换不对，日期格式显然不对。
		return false;
	}
	//校验日期的有效性，只要满足其中一个格式就OK
	foreach ($formats as $format)
	{
		if (date($format, $unixTime) == $date)
		{
			return true;
		}
	}
	return false;
}

/**
 * 验证是否为手机号码格式
 * @param string $keyword:要验证的字符串
 * @return boolean:是返回true,否则返回false
 */
function is_phone($keyword)
{
	if(strlen($keyword)==11)
	{
		$pattern = '/1[3578]\d{9}|1[47|66|77|88|91|98|99]\d{8}/';
		if (preg_match ( $pattern, $keyword ))
		{
			return true;
		}else {
			return false;
		}
	}else {
		return false;
	}
}

/**
 * 验证是否为邮箱格式
 * @param string $keyword:要验证的字符串
 * @return boolean:是返回true,否则返回false
 */
function is_email($keyword)
{
	$pattern = '/^[\dA-Za-z]+[\-_\.]?[\dA-Za-z]*@([\da-zA-Z]+[\-_]?[\dA-Za-z]*\.[a-z]{2,3}(\.[a-z]{2})?)$/i';
	if (preg_match ( $pattern, $keyword ))
	{
		return true;
	}else {
		return false;
	}
}

/**
 * 验证是否为网址
 * @param string $keyword:要验证的字符串
 * @return boolean:是返回true,否则返回false
 */
function is_url($keyword)
{
	$pattern = '/^http(s?):\/\/(?:[A-za-z0-9-]+\.)+[A-za-z]{2,4}(:\d+)?(?:[\/\?#][\/=\?%\-&~`@[\]\':+!\.#\w]*)?$/';
	preg_match($pattern, $keyword,$res);
	if(!empty($res))
	{
		return true;
	}else{
		return false;
	}
}

/**
 * 判断是否有emojo表情
 * @param string $str:要判断的字符串
 * @return boolean
 */
function haveEmojiChar($str)
{
    $mbLen = mb_strlen($str);
    
    $strArr = [];
    for ($i = 0; $i < $mbLen; $i++) {
        $strArr[] = mb_substr($str, $i, 1, 'utf-8');
        if (strlen($strArr[$i]) >= 4) {
            return true;
        }
    }
    return false;
}

/**
 * 判断字符串是否经过base64编码
 * @param string $str:要判断的字符串
 * @return boolean
 */
function is_base64($str)
{
    if($str==base64_encode(base64_decode($str))){
        return true;
    }else{
        return false;
    }
}

//敏感词过滤
function sensitive_word($content)
{
	//载入配置文件
	$str=file_get_contents('./Public/inc/sensitive_word.txt');
	$msg=trim($str);
	$badword=explode('，',$msg);
	$badword1 = array_combine($badword,array_fill(0,count($badword),'**'));
	$content = strtr($content, $badword1);
	return $content ;
}

/**
 * 记录日志文件
 * @param $content:内容
 * @param $filename:文件名
 * @param $dirname:文件夹名，用于区分不同的日志分类
 * */
function writeLog($content='', $filename='',$dirname='')
{
	if (! $content) 
	{
		return false;
	}
	$dir = getcwd () . DIRECTORY_SEPARATOR . 'Public/logs' . DIRECTORY_SEPARATOR . $dirname;
	if (! is_dir ( $dir )) 
	{
		if (! mkdir ( $dir )) 
		{
			return false;
		}
	}
	if(!empty($filename))
	{
		$filename=iconv("UTF-8", "GB2312//IGNORE", $filename);
		$filename = $dir . DIRECTORY_SEPARATOR . $filename . '.log';
	}else {
		$filename = $dir . DIRECTORY_SEPARATOR . date ('Ymd',time ()) . '.log';
	}
	$str = 'Time:'.date ( "Y-m-d H:i:s" )."\r\n".'内容:'. $content . "\r\n";
	if (! $fp = @fopen ( $filename, "a" )) 
	{
		return false;
	}
	if (! fwrite ( $fp, $str ))
		return false;
	fclose ( $fp );
	return true;
}

/**
 * 二维数组按指定的键值排序
 * $array:数组
 * $key:排序键值
 * $type:排序方式
 * */
function array_sort($arr, $keys, $type = 'desc') 
{
	$keysvalue = $new_array = array();
	//先取出需要排序的键
	foreach ($arr as $k => $v) 
	{
		$keysvalue[$k] = $v[$keys];
	}
	//将键重新排序
	if ($type == 'asc') 
	{
		asort($keysvalue);
	} else {
		arsort($keysvalue);
	}
	reset($keysvalue);
	//根据排序后的键顺序得到新的数组
	foreach ($keysvalue as $k => $v) 
	{
		$new_array[$k] = $arr[$k];
	}
	return $new_array;
}

/**
 * 查找数组中重复数据
 * @param $array:需要查找的数组
 * 存在返回重复数据的新数组
 * 不存在返回空
 */
function repeatArray($array)
{
	//array_unique():去除数组中的重复数据，并返回不重复的新数组
	//array_diff_assoc():比较两个数组的不同，并返回不同部分的新数组
	$repeat = array_diff_assoc ( $array,  array_unique ( $array ) );
	$repeat=array_values($repeat);
	return $repeat;
}

/**
 * 计算路程--四舍五入
 */
function getRange($range,$space = true)
{
	if($range < 1000)
	{
		return $range.($space ? ' ' : '').'m';
	}else{
		return floatval(round($range/1000,2)).($space ? ' ' : '').'km';
	}
}

/**
 * 获取请求ip
 */
function getIP()
{
	if(getenv('HTTP_CLIENT_IP') && strcasecmp(getenv('HTTP_CLIENT_IP'), 'unknown')) 
	{
		$ip = getenv('HTTP_CLIENT_IP');
	} elseif(getenv('HTTP_X_FORWARDED_FOR') && strcasecmp(getenv('HTTP_X_FORWARDED_FOR'), 'unknown')) {
		$ip = getenv('HTTP_X_FORWARDED_FOR');
	} elseif(getenv('REMOTE_ADDR') && strcasecmp(getenv('REMOTE_ADDR'), 'unknown')) {
		$ip = getenv('REMOTE_ADDR');
	} elseif(isset($_SERVER['REMOTE_ADDR']) && $_SERVER['REMOTE_ADDR'] && strcasecmp($_SERVER['REMOTE_ADDR'], 'unknown')) {
		$ip = $_SERVER['REMOTE_ADDR'];
	}
	return preg_match ( '/[\d\.]{7,15}/', $ip, $matches ) ? $matches [0] : '';
}

/**
 * 根据IP获取访问用户城市信息
 */
function getCityByIp()
{
	$ip = getIP(); //获取当前用户的IP
	if($ip!=='127.0.0.1')
	{
		//调用淘宝IP地址库
		$url = "http://ip.taobao.com/service/getIpInfo.php?ip=$ip";
		$data = file_get_contents($url); //调用淘宝接口获取信息
		return $data;
	}else {
		return false;
	}
}

/**
 * 获取当前页面完整URL地址
 */
function getUrl()
{
	$sys_protocal = isset($_SERVER['SERVER_PORT']) && $_SERVER['SERVER_PORT'] == '443' ? 'https://' : 'http://';
	$php_self = $_SERVER['PHP_SELF'] ? safe_replace($_SERVER['PHP_SELF']) : safe_replace($_SERVER['SCRIPT_NAME']);
	$path_info = isset($_SERVER['PATH_INFO']) ? safe_replace($_SERVER['PATH_INFO']) : '';
	$relate_url = isset($_SERVER['REQUEST_URI']) ? safe_replace($_SERVER['REQUEST_URI']) : $php_self.(isset($_SERVER['QUERY_STRING']) ? '?'.safe_replace($_SERVER['QUERY_STRING']) : $path_info);
	return $sys_protocal.(isset($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : '').$relate_url;
}

/**
 * 获取网址上的参数集
 * @param string $url:要解析的网址
 * @return array:参数数组
 */
function getUrlParam($url)
{
	//获取网址上的参数
	$param=parse_url($url,PHP_URL_QUERY);
	//将参数组合成数组
	parse_str($param,$url_arr);
	return $url_arr;
}

/**
 * 从11位手机号码中隐藏指定数字为*
 * @param string $phone 手机号码
 * @param ing $strat 开始位数
 * @param ing $length 隐藏长度
 * @return string $hide_phone 隐藏后的手机号码
 */
function hide_phone($phone, $strat, $length)
{
    // 判断要隐藏的长度
    for ($i=0; $i < $length; $i++) {
        $str .= '*';
    }
    $hide_phone = substr_replace($phone, $str, $strat-1, $length);
    return $hide_phone;
}

/**
 * 安全过滤函数
 */
function safe_replace($string) 
{
	$string = str_replace ( '%20', '', $string );
	$string = str_replace ( '%27', '', $string );
	$string = str_replace ( '%2527', '', $string );
	$string = str_replace ( '*', '', $string );
	$string = str_replace ( '"', '&quot;', $string );
	$string = str_replace ( "'", '', $string );
	$string = str_replace ( '"', '', $string );
	$string = str_replace ( ';', '', $string );
	$string = str_replace ( '<', '&lt;', $string );
	$string = str_replace ( '>', '&gt;', $string );
	$string = str_replace ( "{", '', $string );
	$string = str_replace ( '}', '', $string );
	return $string;
}

//循环删除目录和文件
function delDirAndFile($dirName)
{
	if ( ($handle = opendir( "$dirName" )) ==true ) 
	{
		while ( false !== ( $item = readdir( $handle ) ) ) 
		{
			if ( $item != "." && $item != ".." ) 
			{
				if ( is_dir( "$dirName/$item" ) ) 
				{
					delDirAndFile( "$dirName/$item" );
				} else {
					unlink("$dirName/$item");
				}
			}
		}
		closedir( $handle );
		rmdir($dirName);
	}
}

//仅删除指定目录下的文件，不删除目录文件夹
function delFileUnderDir( $dirName )
{
	if ( ($handle = opendir( "$dirName" ))==true ) 
	{
		while ( false !== ( $item = readdir( $handle ) ) ) 
		{
			if ( $item != "." && $item != ".." ) 
			{
				if ( is_dir( "$dirName/$item" ) ) 
				{
					delFileUnderDir( "$dirName/$item" );
				} else {
					unlink( "$dirName/$item" );
				}
			}
		}
		closedir( $handle );
	}
}

/**
 * 复制文件夹
 * @param string $src:原目录
 * @param string $dst:复制到的目录
 */
function recurse_copy($src,$dst)
{
	if(is_dir($src)===false)
	{
		// 原目录不存在
		return false;
	}else {
		$dir = opendir($src);
		if(is_dir($dst)===false)
		{
			// 复制到的目录不存在则新建文件夹
			mkdir($dst);
		}
		while( ( $file = readdir($dir)) !== false )
		{
			if (( $file != '.' ) && ( $file != '..' ))
			{
				if ( is_dir($src . '/' . $file) )
				{
					recurse_copy($src . '/' . $file,$dst . '/' . $file);
				}else {
					copy($src . '/' . $file,$dst . '/' . $file);
				}
			}
		}
		closedir($dir);
		return true;
	}
}

/**
 * 将数组中值GBK编码转化为UTF8
 */
function transArrGBKtoUTF8($array)
{
	$array = iconv('UTF-8','GBK//IGNORE',var_export($array,true));
	return $array;
}

/**
 * 去除BOM头
 */
function clearBom($content)
{
	return trim($content,chr(239).chr(187).chr(191));
}

/**
 * 读取CSV文件内容
 * 返回去掉第一行栏目的数组
 * fgetcsv() 函数从文件指针中读入一行并解析 CSV 字段
 * var_export() 输出或返回一个变量的字符串表示
 */
function readCSV($file)
{
	$file = fopen($file,'r');
	while (($data = fgetcsv($file))!==false)
	{ //每次读取CSV里面的一行内容
		$array[] = $data;
	}
	unset($array[0]);  //删除第一行栏目
	$array=array_values($array); //重新排序，改变数字下标
	//解决中文乱码
	$array = eval('return '.iconv('gbk','utf-8',var_export($array,true)).';');
	return $array;
}

/**
 * 使用PHPMailer发送邮件
 * @param string $to:收件人邮箱
 * @param string $title:邮件主题
 * @param string $content:邮件内容
 * @return boolean
 */
function sendMail($to, $title, $content) 
{
	Vendor ('PHPMailer/PHPMailerAutoload','','.php');
	//Vendor('PHPMailer.PHPMailerAutoload');
	$mail = new \PHPMailer(); //实例化
	$mail->IsSMTP(); // 启用SMTP
	$mail->Host=C('MAIL_HOST'); //smtp服务器的名称
	$mail->SMTPAuth = C('MAIL_SMTPAUTH'); //启用smtp认证
	$mail->Username = C('MAIL_USERNAME'); //邮箱名
	$mail->Password = C('MAIL_PASSWORD') ; //邮箱密码
	$mail->From = C('MAIL_FROM'); //发件人地址（也就是你的邮箱地址）
	$mail->FromName = C('MAIL_FROMNAME'); //发件人姓名
	$mail->AddAddress($to,"尊敬的用户");
	$mail->WordWrap = 50; //设置每行字符长度
	$mail->IsHTML(C('MAIL_ISHTML')); // 是否HTML格式邮件
	$mail->CharSet=C('MAIL_CHARSET'); //设置邮件编码
	$mail->Subject =$title; //邮件主题
	$mail->Body = $content; //邮件内容
	$mail->AltBody = "这是一个纯文本的身体在非营利的HTML电子邮件客户端"; //邮件正文不支持HTML的备用显示
	return($mail->Send());
}

/**
 * 格式化字节大小
 * @param  number $size      字节数
 * @param  string $delimiter 数字和单位分隔符
 * @return string            格式化后的带单位的大小
 * @author 麦当苗儿 <zuojiazi@vip.qq.com>
 */
function format_bytes($size, $delimiter = '')
{
	$units = array('B', 'KB', 'MB', 'GB', 'TB', 'PB');
	for ($i = 0; $size >= 1024 && $i < 5; $i++) $size /= 1024;
	return round($size, 2) . $delimiter . $units[$i];
}

/**
 * 数据加密
 * @param string $data:要加密的数据字符串
 * @param string $key:私钥
 * @return string:返回加密后的字符串
 */
/* function encrypt($data, $key)
{
	// 使用MD5加密-也可以使用sha1
	$key = md5($key);
	$x = 0;
	$len = strlen($data);
	$l = strlen($key);
	for ($i = 0; $i < $len; $i++)
	{
		if ($x == $l)
		{
			$x = 0;
		}
		$char .= $key.$x;
		$x++;
	}
	for ($i = 0; $i < $len; $i++)
	{
		// ord()返回字符串的ASCII值
		// chr()从不同的 ASCII 值返回字符：十进制、八进制（0）、十六进制（0x）
		$str .= chr(ord($data[$i]) + (ord($char[$i])) % 256);
	}
	return base64_encode($str);
} */

/**
 * 数据解密
 * @param string $data:要解密的数据字符串
 * @param string $key:私钥
 * @return string:返回加密前的字符串
 */
/* function decrypt($data, $key)
{
	// 使用MD5加密，也可以使用sha1-这里的方法需要与上面的加密方法encrypt中使用的一致
	$key = md5($key);
	$x = 0;
	$data = base64_decode($data);
	$len = strlen($data);
	$l = strlen($key);
	for ($i = 0; $i < $len; $i++)
	{
		if ($x == $l)
		{
			$x = 0;
		}
		$char .= substr($key, $x, 1);
		$x++;
	}
	for ($i = 0; $i < $len; $i++)
	{
		if (ord(substr($data, $i, 1)) < ord(substr($char, $i, 1)))
		{
			$str .= chr((ord(substr($data, $i, 1)) + 256) - ord(substr($char, $i, 1)));
		}else {
			$str .= chr(ord(substr($data, $i, 1)) - ord(substr($char, $i, 1)));
		}
	}
	return $str;
} */

/**
 * 从数组中删除指定元素
 * @param array $data:数组
 * @param string $key:元素下标
 * @return array:删除元素后的数组
 */
function array_remove($data, $key)
{
    if(!array_key_exists($key, $data))
    { 
        return $data;  
    }
    $keys = array_keys($data);  
    $index = array_search($key, $keys);  
    if($index !== FALSE)
    {
        array_splice($data, $index, 1);  
    }
    return $data;
} 

function taoid($taourl) {//根据淘宝地址获取淘宝商品id
	$strurl = strtolower ( $taourl);
	if (strpos ( $strurl, 'id' ) !== false) {
		$arr = explode ( '?', $taourl );
		$arr = explode ( '&', $arr [1] );
		$NO = 0;
		foreach ( $arr as $k => $v ) {
			if (is_string ( $v )) {
				if (strpos ( $v, 'id' ) !== false) {
					if (strpos ( $v, 'item' ) !== false || strpos ( $v, 'num' ) !== false) {
						//echo $v,'<br/>';
						$i = strrpos ( $v, '=' );
						$str = substr ( $v, $i + 1 );
						if (is_numeric ( $str )) {
							return $NO = $str;
						}
					} else {
						//echo $v,'<br/>';
						$i = strrpos ( $v, '=' );
						$str = substr ( $v, $i + 1 );
						$x = strlen ( $str );
						if (is_numeric ( $str )) {
							if ($x ==11) {
								$NO = $str;
							} else if ($NO == 0 || ($x > 9 && $x < 11)) {
								$NO = $str;
							}
						}
					}
				}
			}
		}
		return $NO;
	}
}

function getArrSet($arrs,$_current_index=-1)
{
	//总数组
	static $_total_arr;
	//总数组下标计数
	static $_total_arr_index;
	//输入的数组长度
	static $_total_count;
	//临时拼凑数组
	static $_temp_arr;

	//进入输入数组的第一层，清空静态数组，并初始化输入数组长度
	if($_current_index<0)
	{
		$_total_arr=array();
		$_total_arr_index=0;
		$_temp_arr=array();
		$_total_count=count($arrs)-1;
		getArrSet($arrs,0);
	}else {
		//循环第$_current_index层数组
		foreach($arrs[$_current_index] as $v)
		{
			//如果当前的循环的数组少于输入数组长度
			if($_current_index<$_total_count)
			{
				//将当前数组循环出的值放入临时数组
				$_temp_arr[$_current_index]=$v;
				//继续循环下一个数组
				getArrSet($arrs,$_current_index+1);
			}
			//如果当前的循环的数组等于输入数组长度(这个数组就是最后的数组)
			else if($_current_index==$_total_count)
			{
				//将当前数组循环出的值放入临时数组
				$_temp_arr[$_current_index]=$v;
				//将临时数组加入总数组
				$_total_arr[$_total_arr_index]=$_temp_arr;
				//总数组下标计数+1
				$_total_arr_index++;
			}
		}
	}
	return $_total_arr;
}

/**
 * 查询手机归属地
 * @param string $phone:手机号码
 * @return array
 */
function queryPhoneOwner($phone)
{
	//调用百度api
	$url = 'http://mobsec-dianhua.baidu.com/dianhua_api/open/location?tel='.$phone;
	$res_json=https_request($url);
	$result=json_decode($res_json,true);
	$data=array(
			'province'=>'',
			'city'=>'',
	);
	if($result['response'][$phone]['detail'])
	{
		$data=array(
				'province'=>$result['response'][$phone]['detail']['province'],
				'city'=>$result['response'][$phone]['detail']['area'][0]['city'],
		);
		$res=array(
				'code'=>0,
				'msg'=>'成功！',
				'data'=>$data
		);
	}else {
		$res=array(
				'code'=>1,
				'msg'=>'失败！',
				'data'=>$data
		);
	}
	return $res;
}
function queryPhoneOwner2($phone)
{
	//调用百度api
	$url="http://sp0.baidu.com/8aQDcjqpAAV3otqbppnN2DJv/api.php?query={$phone}&resource_id=6004&ie=utf8&oe=utf8&format=json";
	$res_json=https_request($url);
	$result=json_decode($res_json,true);
	$data=array(
			'province'=>'',
			'city'=>'',
	);
	if($result['status']=='0')
	{
		$data=array(
				'province'=>$result['data'][0]['prov'],
				'city'=>$result['data'][0]['city'],
		);
		$res=array(
				'code'=>0,
				'msg'=>'成功！',
				'data'=>$data
		);
	}else {
		$res=array(
				'code'=>1,
				'msg'=>'失败！',
				'data'=>$data
		);
	}
	return $res;
}
?>