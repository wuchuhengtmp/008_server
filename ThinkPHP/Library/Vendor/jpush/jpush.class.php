<?php
/**
 * 极光推送
 * 2018-02-08
 * @author 葛阳
 */
require 'jpush/autoload.php';

class jpush
{
	private $app_key=JPUSH_KEY;
	private $master_secret=JPUSH_SECRET;
	
	public function __construct()
	{
		
	}
	
	/**
	 * 推送消息
	 * @param string $alias:别名
	 * @param string $title:消息标题
	 * @param string $content:消息内容
	 * @param string $sound:通知提示声音
	 * @param string $msg_title:自定义消息，消息标题
	 * @param string $msg_content:自定义消息，消息内容本身
	 * @return multitype:number \JPush\Exceptions\APIRequestException
	 */
	public function push($alias,$title='',$content='',$sound='',$msg_title='',$msg_content='',$key='',$value='')
	{
	    if($sound=='') {
	        $sound='default';
	    }
	    $client = new \JPush\Client($this->app_key, $this->master_secret);
	    try {
	        $response = $client->push()->setPlatform(array('android', 'ios', 'winphone'))->addAlias($alias)
	        ->setNotificationAlert($title)
	        ->iosNotification($content, array(
	            'sound' => $sound,
	            // 'badge' => '+1',
	            // 'content-available' => true,
	            // 'mutable-content' => true,
	            'category' => 'jiguang',
	            'extras' => array(
	                'key' => $key,
	                'value' => $value,
	            ),
	        ))
	        ->androidNotification($content, array(
	            'title' => $title,
	            // 'builder_id' => 2,//通知栏样式 ID
	            'extras' => array(
	                'key' => $key,
	                'value' => $value,
	            ),
	        ))
	        //自定义消息
	        /* ->message($msg_content, array(
	         'title' => $msg_title,
	         'content_type' => 'text',
	         'extras' => array(
	         'key' => $key,
	         'value' => $value,
	         ),
	         )) */
	        ->options(array(
	            // sendno: 表示推送序号，纯粹用来作为 API 调用标识，
	            // API 返回时被原样返回，以方便 API 调用方匹配请求与返回
	            // 这里设置为 100 仅作为示例
	            // 'sendno' => 100,
	            
	            // time_to_live: 表示离线消息保留时长(秒)，
	            // 推送当前用户不在线时，为该用户保留多长时间的离线消息，以便其上线时再次推送。
	            // 默认 86400 （1 天），最长 10 天。设置为 0 表示不保留离线消息，只有推送当前在线的用户可以收到
	            // 这里设置为 1 仅作为示例
	            // 'time_to_live' => 1,
	            
	            // apns_production: 表示APNs是否生产环境，
	            // True 表示推送生产环境，False 表示要推送开发环境；如果不指定则默认为推送生产环境
	            'apns_production' => True,
	            //'apns_production' => False,
	            
	            // big_push_duration: 表示定速推送时长(分钟)，又名缓慢推送，把原本尽可能快的推送速度，降低下来，
	            // 给定的 n 分钟内，均匀地向这次推送的目标用户推送。最大值为1400.未设置则不是定速推送
	            // 这里设置为 1 仅作为示例
	            
	            // 'big_push_duration' => 1
	        ))
	        ->send();
	        if($response['http_code']=='200')
	        {
	            $res=array(
	                'code'=>0,
	                'msg'=>'成功'
	            );
	        }
	    } catch (\JPush\Exceptions\APIConnectionException $e) {
	        $res=array(
	            'code'=>1,
	            'msg'=>$e
	        );
	    } catch (\JPush\Exceptions\APIRequestException $e) {
	        $res=array(
	            'code'=>1,
	            'msg'=>$e
	        );
	    }
	    return $res;
	}
}
?>