<?php
/**
 * mob-mob接口
 * 2020-03-09
 */


class mob
{
    //mob appKey
    protected $appKey='2e468ef441da0';
    //mob appSecret
    protected $appSecret='50400baeda0c059753d0738c064d3652';
    //mob短信验证接口地址
    protected $sms_url='https://webapi.sms.mob.com/sms/verify';
    //mob秒验验证接口地址
    protected $sec_verify_url='http://identify.verify.mob.com/auth/auth/sdkClientFreeLogin';
    //mob秒验参数
    protected $md5='811251dbb9c56b39e2a2d89702be6524';//android必须要填写，例：e4caa1a08ba0570b5c1290b1a0bc9252
    //mob短信验证错误码
    protected $sms_error_code=array(
        200 => '验证成功',
        405 => 'AppKey为空',
        406 => 'AppKey无效',
        456 => '国家代码或手机号码为空',
        457 => '手机号码格式错误',
        466 => '请求校验的验证码为空',
        467 => '请求校验验证码频繁（5分钟内同一个号码最多只能校验三次）',
        468 => '验证码错误',
        474 => '没有打开服务端验证开关',
    );
    //mob秒验验证错误码
    protected $sec_verify_error_code=array(
        5119104 => '解密失败',
        5119105 => '服务错误',
        4119301 => '数据校验失败',
        4119302 => '数据不存在',
        4119303 => '数据已经存在',
        4119310 => 'token未找到',
        4119311 => 'token非法',
        4119330 => 'App没有初始化',
        4119331 => 'AppSecret错误',
        5119341 => '余额不足',
        5119501 => '未知的运营商类型',
        5119511 => 'appkey每分钟验证次数超过限制',
        5119513 => '未审核的包名每天验证数量超过限制',
        4119521 => '包名没有配置',
        5119531 => 'appkey在黑名单中',
        5119546 => '[免密登录][APP每分钟]超限',
        5119507 => '免密登录失败',
        5119509 => '免密获取TOKEN失败',
        4119342 => '签名错误',
        4119343 => 'timestamp错误',
        5119601 => '未设置价格',
    );

    /**
     * mob短信验证码是否正确
     * @param string $mobile:手机号码
     * @param string $code:验证码
     * @param int $timeout 超时时间
     * @return array
     * @return @param code:返回码
     * @return @param msg:返回码说明
     */
    public function checkSmsCode($mobile,$code,$timeout=30)
    {
        $params=array(
            'appkey' => $this->appKey,
            'phone' => $mobile,
            'zone' => '86',
            'code' => $code
        );
        $ch = curl_init();
        curl_setopt( $ch, CURLOPT_URL, $this->sms_url );
        // 以返回的形式接收信息
        curl_setopt( $ch, CURLOPT_RETURNTRANSFER, 1 );
        // 设置为POST方式
        curl_setopt( $ch, CURLOPT_POST, 1 );
        curl_setopt( $ch, CURLOPT_POSTFIELDS, http_build_query( $params ) );
        // 不验证https证书
        curl_setopt( $ch, CURLOPT_SSL_VERIFYPEER, 0 );
        curl_setopt( $ch, CURLOPT_SSL_VERIFYHOST, 0 );
        curl_setopt( $ch, CURLOPT_TIMEOUT, $timeout );
        curl_setopt( $ch, CURLOPT_HTTPHEADER, array(
            'Content-Type: application/x-www-form-urlencoded;charset=UTF-8',
            'Accept: application/json',
        ) );
        // 发送数据
        $response = curl_exec( $ch );
        // 不要忘记释放资源
        curl_close( $ch );
        $res=json_decode($response,true);
        $result=array(
            'code'=>$res['status'],
            'msg'=>$this->sms_error_code[$res['status']],
        );
        return $result;
    }

    /**
     * mob秒验服务端验证
     * @param string $token:客户端的token
     * @param string $opToken:客户端返回的运营商token
     * @param String $operator 客户端返回的运营商，CMCC:中国移动通信, CUCC:中国联通通讯, CTCC:中国电信
     * @return array
     * @return @param code:返回码
     * @return @param msg:返回码说明
     */
    public function checkSecVerify($token,$opToken,$operator)
    {
        //设置post数据
        $post_data=array(
            'appkey' => $this->appKey,
            "token" => $token,
            "opToken" => $opToken,
            'operator'=> $operator,
            'timestamp'=> get_millistime()
        );
        if ($this->md5 != '') {
            $post_data['md5'] = $this->md5;
        }
        $post_data['sign'] = $this->getSign($post_data);
        $jsonStr = json_encode($post_data);

        //初始化
        $curl = curl_init();
        //设置抓取的url
        curl_setopt($curl, CURLOPT_URL, $this->sec_verify_url);
        //设置头文件的信息作为数据流输出
        curl_setopt($curl, CURLOPT_HEADER, 0);
        //设置获取的信息以文件流的形式返回，而不是直接输出。
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        //设置post方式提交
        curl_setopt($curl, CURLOPT_POST, 0);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $jsonStr);
        curl_setopt($curl, CURLOPT_HTTPHEADER, array(
                'Content-Type: application/json; charset=utf-8',
                'Content-Length: ' . strlen($jsonStr)
            )
        );
        //执行命令
        $data = curl_exec($curl);
        // 不要忘记释放资源
        curl_close( $curl );

        $res = json_decode($data, true);
        $result=array(
            'code'=>$res['status'],
            'msg'=>$this->sec_verify_error_code[$res['status']],
        );
        if ($res['status'] == 200) {
            $d = openssl_decrypt($res['res'], "DES-CBC", $this->appSecret, 0, "00000000");
            $result['data'] = json_decode($d, true);
        }
        return $result;
    }

    /**
     * mob秒验MD5签名
     * @param array $data
     * @return string
     */
    public function getSign($data) {
        ksort($data);
        $str = '';
        foreach ($data as $k => $v ) {
            $str .= "$k=$v&";
        }
        $str = substr($str, 0, -1);
        return md5($str.$this->appSecret);
    }
}
?>