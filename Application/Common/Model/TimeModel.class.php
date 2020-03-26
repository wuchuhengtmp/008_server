<?php
/**
 * 时间类
 */
namespace Common\Model;

class TimeModel
{
	public $one_minute=60;//一分钟60秒
	public $one_hour=3600;//一小时3600秒
	public $one_day=86400;//一天86400秒
	
    /**
     * 校验日期格式是否正确
     * @param date $date:日期
     * @param string $formats:需要检验的格式数组
     * @return boolean
     */
    public function checkDateIsValid($date, $formats = array("Y-m-d", "Y/m/d")) 
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
     * 校验时间格式是否正确
     * @param datetime $datetime:时间
     * @param string $formats:需要检验的格式数组
     * @return boolean
     */
    public function checkDateTimeIsValid($datetime, $formats = array("Y-m-d H:i:s", "Y/m/d H:i:s"))
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
     * 获取给定日期/时间的年份
     * @param date/datetime $time:日期/时间格式
     * @return $array
     * @return @param y：2位的年号，如18
     * @return @param Y：4位的年号，如2018
     * @return @param L：是否为闰年 如果是闰年为 1，否则为 0
     * @return @param o：ISO-8601 格式年份数字
     */
    public function getYear($time='')
    {
    	if($time=='')
    	{
    		//当前时间
    		$time=date('Y-m-d');
    	}else {
    		//校验日期/时间格式
    		if($this->checkDateIsValid($time)===false and $this->checkDateTimeIsValid($time)===false)
    		{
    			//不是正确的日期/时间格式
    			return false;
    		}
    	}
    	$res=array(
    			'y'=>date('y', strtotime($time)),
    			'Y'=>date('Y', strtotime($time)),
    			'L'=>date('L', strtotime($time)),
    			'o'=>date('o', strtotime($time)),
    	);
    	return $res;
    	
    }
    
    /**
     * 获取给定时间的月份
     * @param date/datetime $time:日期/时间格式
     * @return $array
     * @return @param F：月份的英文全称，如January - December
     * @return @param M：月份的英文简称，如Jan - Dec
     * @return @param m：带前导0月份，如01-12
     * @return @param n：不带前导0月份，如1-12
     * @return @param t：给定月份所应有的天数，28 到 31
     */
    public function getMonth($time='')
    {
        if ($time=='') 
        {
        	//当前时间
            $time=date('Y-m-d');
        }else {
        	//校验日期/时间格式
        	if($this->checkDateIsValid($time)===false and $this->checkDateTimeIsValid($time)===false)
        	{
        		//不是正确的日期/时间格式
        		return false;
        	}
        }
        $res=array(
        		'F'=>date("F", strtotime($time)),
        		'M'=>date("M", strtotime($time)),
        		'm'=>date("m", strtotime($time)),
        		'n'=>date("n", strtotime($time)),
        		't'=>date("t", strtotime($time)),
    	);  
        return $res;
    }
    
    /**
     * 获取给定时间的星期数
     * @param date/datetime $time:日期/时间格式
     * @return $array
     * @return @param l：一周中的某天，如Monday - Sunday
     * @return @param D：一周中的某天，如Mon - Sun
     * @return @param w：星期几(数字)，如0-6
     * @return @param N：ISO-8601 格式数字表示的星期中的第几天， 1（星期一）到 7（星期天） 
     * @return @param W：ISO-8601 格式年份中的第几周，每周从星期一开始 42（当年的第 42 周）
     */
    public function getWeekday($time='')
    {
    	if ($time=='')
    	{
    		//当前时间
    		$time=date('Y-m-d');
    	}else {
    		//校验日期/时间格式
    		if($this->checkDateIsValid($time)===false and $this->checkDateTimeIsValid($time)===false)
    		{
    			//不是正确的日期/时间格式
    			return false;
    		}
    	}
    	$week=date("w", strtotime($time));
    	switch($week)
    	{
    		case 0: 
    			$week="星期天";
    			break;
    		case 1: 
    			$week="星期一";
    			break;
    		case 2: 
    			$week="星期二";
    			break;
    		case 3: 
    			$week="星期三";
    			break;
    		case 4: 
    			$week="星期四";
    			break;
    		case 5: 
    			$week="星期五";
    			break;
    		case 6: 
    			$week="星期六";
    			break;
    		default: 
    			$week="出错了!";
    			break;
    	}
    	$res=array(
    			'l'=>date("l", strtotime($time)),
    			'D'=>date("D", strtotime($time)),
    			'w'=>date("w", strtotime($time)),
    			'N'=>date("N", strtotime($time)),
    			'W'=>date("W", strtotime($time)),
    			'w_zh'=>$week
    	);
    	return $res;
    }
    
    /**
     * 获取给定时间的日
     * @param date/datetime $time:日期/时间格式
     * @return $array
     * @return @param d：一个月中的第几天，带前导零，如01-31
     * @return @param j：一个月中的第几天，不带前导零，如1-31
     * @return @param S：每月天数后面的英文后缀，2 个字符 st，nd，rd 或者 th。可以和 j 一起用 
     * @return @param z：年份中的第几天，0到366
     */
    public function getDay($time='')
    {
    	if ($time=='')
    	{
    		//当前时间
    		$time=date('Y-m-d');
    	}else {
    		//校验日期/时间格式
    		if($this->checkDateIsValid($time)===false and $this->checkDateTimeIsValid($time)===false)
    		{
    			//不是正确的日期/时间格式
    			return false;
    		}
    	}
        $res=array(
        		'd'=>date("d", strtotime($time)),
        		'j'=>date("j", strtotime($time)),
        		'S'=>date("S", strtotime($time)),
        		'z'=>date("z", strtotime($time)),
        );
        return $res;
    }
    
    /**
     * 获取给定时间的小时
     * @param datetime $time:时间格式
     * @return $array
     * @return @param H：24 小时制，带前导零，如01-23
     * @return @param G：24 小时制，不带前导零，如1-23
     * @return @param h：12 小时制，带前导零，如01-12
     * @return @param g：12 小时制，不带前导零，如1-12
     */
    public function getHour($time='')
    {
        if ($time=='')
        {
        	//当前时间
        	$time=date('Y-m-d H:i:s');
        }else {
        	//校验时间格式
        	if($this->checkDateTimeIsValid($time)===false)
        	{
        		//不是正确的日期/时间格式
        		return false;
        	}
        }
        $res=array(
        		'H'=>date("H", strtotime($time)),
        		'G'=>date("G", strtotime($time)),
        		'h'=>date("h", strtotime($time)),
        		'g'=>date("g", strtotime($time)),
        );
        return $res;
    }
    
    /**
     * 获取给定时间的分钟数
     * @param datetime $time:时间格式
     * @return $array
     * @return @param i：分，带前导零，如01-59
     */
    public function getMinute($time='')
    {
    	if ($time=='')
    	{
    		//当前时间
    		$time=date('Y-m-d H:i:s');
    	}else {
    		//校验时间格式
    		if($this->checkDateTimeIsValid($time)===false)
    		{
    			//不是正确的日期/时间格式
    			return false;
    		}
    	}
        $res=array(
        		'i'=>date("i", strtotime($time))
        );
        return $res;
    }
    
    /**
     * 获取给定时间的秒数
     * @param datetime $time:时间格式
     * @return $array
     * @return @param s ：秒，带前导零，如01-59
     */
    public function getSecond($time='')
    {
    	if ($time=='')
    	{
    		//当前时间
    		$time=date('Y-m-d H:i:s');
    	}else {
    		//校验时间格式
    		if($this->checkDateTimeIsValid($time)===false)
    		{
    			//不是正确的日期/时间格式
    			return false;
    		}
    	}
    	$res=array(
    			's'=>date("s", strtotime($time))
        );
        return $res;
    }
    
    /**
     * 获取指定日期所在年份的第一天和最后一天
     * @param date/datetime $time:日期/时间格式
     * @return array
     * @return @param firstday ：所在年份的第一天
     * @return @param lastday ：所在年份的最后一天
     */
    public function getFirstAndLastDayOfYear($time='')
    {
    	if($time=='')
    	{
    		//当前时间
    		$time=date('Y-m-d');
    	}else {
    		//校验日期/时间格式
    		if($this->checkDateIsValid($time)===false and $this->checkDateTimeIsValid($time)===false)
    		{
    			//不是正确的日期/时间格式
    			return false;
    		}
    	}
    	$firstday = date("Y-01-01", strtotime($time));
    	$lastday = date("Y-m-d", strtotime("$firstday +1 year -1 day"));
    	$res=array(
    			'firstday'=>$firstday,
    			'lastday'=>$lastday
    	);
    	return $res;
    }
    
    /**
     * 获取指定日期所在月的第一天和最后一天
     * @param date/datetime $time:日期/时间格式
     * @return array
     * @return @param firstday ：所在月的第一天
     * @return @param lastday ：所在月的最后一天
     */
    public function getFirstAndLastDayOfMonth($time='')
    {
    	if($time=='')
    	{
    		//当前时间
    		$time=date('Y-m-d');
    	}else {
    		//校验日期/时间格式
    		if($this->checkDateIsValid($time)===false and $this->checkDateTimeIsValid($time)===false)
    		{
    			//不是正确的日期/时间格式
    			return false;
    		}
    	}
    	$firstday = date("Y-m-01", strtotime($time));
    	$lastday = date("Y-m-d", strtotime("$firstday +1 month -1 day"));
    	$res=array(
    			'firstday'=>$firstday,
    			'lastday'=>$lastday
    	);
    	return $res;
    }
    
    /**
     * 获取指定日期所在周的第一天和最后一天
     * @param date/datetime $time:日期/时间格式
     * @return $array
     * @return @param firstday ：所在周的第一天
     * @return @param lastday ：所在周的最后一天
     */
    public function getFirstAndLastDayOfWeek($time='')
    {
    	if($time=='')
    	{
    		//当前时间
    		$time=date('Y-m-d');
    	}else {
    		//校验日期/时间格式
    		if($this->checkDateIsValid($time)===false and $this->checkDateTimeIsValid($time)===false)
    		{
    			//不是正确的日期/时间格式
    			return false;
    		}
    	}
    	$firstday = date('Y-m-d', strtotime("$time -1 Monday"));
    	$lastday = date('Y-m-d', strtotime("$time  Sunday"));
    	$res=array(
    			'firstday'=>$firstday,
    			'lastday'=>$lastday
    	);
    	return $res;
    }
    
    /**
     * 获取指定间隔后的日期/时间
     * @param string $time:日期/时间
     * @param string $type:类型，1日期、2时间
     * @param string $year:年份增减，+1、-1，带符号
     * @param string $month:月份增减
     * @param string $day:天数增减
     * @param string $hour:小时增减
     * @param string $minute:分钟增减
     * @param string $second:秒增减
     * @param string $week:星期增减
     */
    public function getAfterDateTime($time='',$type='1',$year='',$month='',$day='',$hour='',$minute='',$second='',$week='')
    {
    	if($type=='1')
    	{
    		//日期
    		if($time=='')
    		{
    			//当前日期
    			$time=date('Y-m-d');
    		}else {
    			//校验日期格式
    			if($this->checkDateIsValid($time)===false)
    			{
    				//不是正确的日期格式
    				return false;
    			}
    		}
    		$format='Y-m-d';
    	}else {
    		//时间
    		if($time=='')
    		{
    			//当前时间
    			$time=date('Y-m-d H:i:s');
    		}else {
    			//校验时间格式
    			if($this->checkDateTimeIsValid($time)===false)
    			{
    				//不是正确的时间格式
    				return false;
    			}
    		}
    		$format='Y-m-d H:i:s';
    	}
    	//英文时间格式
    	$time_str='';
    	if($year)
    	{
    		$time_str.=" $year year";
    	}
    	if($month)
    	{
    		$time_str.=" $month month";
    	}
    	if($day)
    	{
    		$time_str.=" $day day";
    	}
    	if($hour)
    	{
    		$time_str.=" $hour hour";
    	}
    	if($minute)
    	{
    		$time_str.=" $minute minute";
    	}
    	if($second)
    	{
    		$time_str.=" $second second";
    	}
    	if($week)
    	{
    		$time_str.=" $week week";
    	}
    	$afterDateTime = date($format, strtotime("$time $time_str"));
    	return $afterDateTime;
    }
    
    /**
     * 比较两个时间的大小
     * @param date/datetime $time1:日期/时间1
     * @param date/datetime $time2:日期/时间2
     * @return number
     */
    public function compare($time1, $time2)
    {
    	$diff=strtotime($time1) - strtotime($time2);
    	if ($diff>0)
        {
        	//日期/时间1大
        	return 1;
        } else if ($diff<0){
            //日期/时间2大
        	return 2;
        }else {
        	//相等
        	return 3;
        }
    }
    
    /**
     * 获取两个时间的差值
     * @param $time1,$time2 时间格式
     * @return $array
     * @return @param year:以年为单位，相差的年份
     * @return @param month:以月为单位，相差的月份
     * @return @param day:以天为单位，相差的天数
     * @return @param hour:以小时为单位，相差的小时
     * @return @param minute:以分钟为单位，相差的分钟
     * @return @param second:以秒为单位，相差的秒
     * @return @param diff:时间差
     * @return @param diff->year:相差的年份
     * @return @param diff->month:相差的月份
     * @return @param diff->day:相差的天数
     * @return @param diff->hour:相差的小时
     * @return @param diff->minute:相差的分钟
     * @return @param diff->second:相差的秒
     */
    public function diff($time1 = '', $time2 = '')
    {
        if ($time1 == '')
        {
            $time1 = date("Y-m-d H:i:s");
        }else {
        	//校验日期/时间格式
        	if($this->checkDateIsValid($time1)===false and $this->checkDateTimeIsValid($time1)===false)
        	{
        		//不是正确的日期/时间格式
        		return false;
        	}
        }
        if ($time2 == '')
        {
            $time2 = date("Y-m-d H:i:s");
        }else {
        	//校验日期/时间格式
        	if($this->checkDateIsValid($time2)===false and $this->checkDateTimeIsValid($time2)===false)
        	{
        		//不是正确的日期/时间格式
        		return false;
        	}
        }
        $date1 = strtotime($time1);
        $date2 = strtotime($time2);
        if ($date1 > $date2) {
            $diff = $date1 - $date2;
        } else {
            $diff = $date2 - $date1;
        }
        //具体的时间差
        //相差的年份
        $year=floor($diff / ($this->one_day*365));
        $diff_year=$diff-$year*$this->one_day*365;
        //相差的月份
        $month=floor($diff_year / ($this->one_day*30));
        $diff_month=$diff_year-$month*$this->one_day*30;
        //相差的天数
        $day=floor($diff_month / $this->one_day);
        $diff_day=$diff_month-$day*$this->one_day;
        //相差的小时
        $hour=floor($diff_day / $this->one_hour);
        $diff_hour=$diff_day-$hour*$this->one_hour;
        //相差的分钟
        $minute=floor($diff_hour / $this->one_minute);
        $diff_minute=$diff_hour-$minute*$this->one_minute;
        //相差的秒
        $second=$diff_minute;
        $res=array(
        		'year'=>$diff / ($this->one_day*365),
        		'month'=>$diff / ($this->one_day*30),
        		'day'=>$diff / $this->one_day,
        		'hour'=>$diff / $this->one_hour,
        		'minute'=>$diff / $this->one_minute,
        		'second'=>$diff,
        		'diff'=>array(
        				'year'=>$year,
        				'month'=>$month,
        				'day'=>$day,
        				'hour'=>$hour,
        				'minute'=>$minute,
        				'second'=>$second
        		)
        );
        return $res;
    }
    
    /**
     * 将秒数转换为时间
     * @param int $time:秒数，比如86400
     * @return $array
     * @return @param year:以年为单位，相差的年份
     * @return @param month:以月为单位，相差的月份
     * @return @param day:以天为单位，相差的天数
     * @return @param hour:以小时为单位，相差的小时
     * @return @param minute:以分钟为单位，相差的分钟
     * @return @param second:以秒为单位，相差的秒
     * @return @param diff:时间差
     * @return @param diff->year:相差的年份
     * @return @param diff->month:相差的月份
     * @return @param diff->day:相差的天数
     * @return @param diff->hour:相差的小时
     * @return @param diff->minute:相差的分钟
     * @return @param diff->second:相差的秒
     */
    public function convertSecondsToTime($time)
    {
    	//相差的年份
    	$year=floor($time / ($this->one_day*365));
    	$diff_year=$time-$year*$this->one_day*365;
    	//相差的月份
    	$month=floor($diff_year / ($this->one_day*30));
    	$diff_month=$diff_year-$month*$this->one_day*30;
    	//相差的天数
    	$day=floor($diff_month / $this->one_day);
    	$diff_day=$diff_month-$day*$this->one_day;
    	//相差的小时
    	$hour=floor($diff_day / $this->one_hour);
    	$diff_hour=$diff_day-$hour*$this->one_hour;
    	//相差的分钟
    	$minute=floor($diff_hour / $this->one_minute);
    	$diff_minute=$diff_hour-$minute*$this->one_minute;
    	//相差的秒
    	$second=$diff_minute;
    	$res=array(
    			'year'=>$time / ($this->one_day*365),
    			'month'=>$time / ($this->one_day*30),
    			'day'=>$time / $this->one_day,
    			'hour'=>$time / $this->one_hour,
    			'minute'=>$time / $this->one_minute,
    			'second'=>$time,
    			'diff'=>array(
    					'year'=>$year,
    					'month'=>$month,
    					'day'=>$day,
    					'hour'=>$hour,
    					'minute'=>$minute,
    					'second'=>$second
    			)
    	);
    	return $res;
    }
}
?>