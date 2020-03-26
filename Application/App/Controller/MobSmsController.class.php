<?php
/**
 * 短信管理接口
 */
namespace App\Controller;
use App\Common\Controller\AuthController;

class MobSmsController extends AuthController
{
    /**
     * mob短信登录时判断手机号
     * @param string $phone:手机号码
     * @return array
     * @return @param code:返回码
     * @return @param msg:返回码说明
     */
    public function mobPhoneLogin()
    {
        if (trim(I('post.phone')))
        {
            $phone = trim(I('post.phone')); // 手机号码
            if(is_phone($phone))
            {
                // 判断手机是否存在
                $User=new \Common\Model\UserModel();
                $res = $User->where ( "phone='$phone'" )->field ( 'uid' )->find ();
                if ($res ['uid']!='')
                {
                    // 该手机号码已被使用！
                    $res=array(
                        'code'=>$this->ERROR_CODE_USER['PHONE_ALREADY_EXISTS'],
                        'msg'=>$this->ERROR_CODE_USER_ZH[$this->ERROR_CODE_USER['PHONE_ALREADY_EXISTS']]
                    );
                    $re = $User->is_freeze($phone);
                    if ($re['code'] == 106)
                    {
                        $res=array(
                            'code'=>$this->ERROR_CODE_USER['USER_FROZEN'],
                            'msg'=>$this->ERROR_CODE_USER_ZH[$this->ERROR_CODE_USER['USER_FROZEN']]
                        );
                    }
                } else {
                    // 该手机号码尚未注册
                    $res=array(
                        'code'=>$this->ERROR_CODE_USER['PHONE_NON_REGISTERED'],
                        'msg'=>$this->ERROR_CODE_USER_ZH[$this->ERROR_CODE_USER['PHONE_NON_REGISTERED']]
                    );
                }
            } else {
                //手机号码格式不正确
                $res=array(
                    'code'=>$this->ERROR_CODE_COMMON['PARAMETER_ERROR'],
                    'msg'=>'手机号码格式不正确'
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

//    /**
//     * 发送用户注册验证码
//     * @param string $phone:手机号码
//     * @return array
//     * @return @param code:返回码
//     * @return @param msg:返回码说明
//     */
//    public function sendUserRegister()
//    {
//        if (trim(I('post.phone')))
//        {
//            $phone = trim(I('post.phone')); // 手机号码
//            if(is_phone($phone))
//            {
//                // 判断手机是否存在
//                $User=new \Common\Model\UserModel();
//                $res = $User->where ( "phone='$phone'" )->field ( 'uid' )->find ();
//                if ($res ['uid']!='')
//                {
//                    // 该手机号码已被使用！
//                    $res=array(
//                        'code'=>$this->ERROR_CODE_USER['PHONE_ALREADY_EXISTS'],
//                        'msg'=>$this->ERROR_CODE_USER_ZH[$this->ERROR_CODE_USER['PHONE_ALREADY_EXISTS']]
//                    );
//                } else {
//                    //发送手机短信
//                    $sms=new \Common\Model\SmsModel();
//                    $content="@1@=".rand(1000,9999);
//                    $res=$sms->sendMessage($phone, $content, 'default');
//                }
//            } else {
//                //手机号码格式不正确
//                $res=array(
//                    'code'=>$this->ERROR_CODE_COMMON['PARAMETER_ERROR'],
//                    'msg'=>'手机号码格式不正确'
//                );
//            }
//        }else {
//            //参数不正确，参数缺失
//            $res=array(
//                'code'=>$this->ERROR_CODE_COMMON['PARAMETER_ERROR'],
//                'msg'=>$this->ERROR_CODE_COMMON_ZH[$this->ERROR_CODE_COMMON['PARAMETER_ERROR']]
//            );
//        }
//        echo json_encode ($res,JSON_UNESCAPED_UNICODE);
//    }
//
//    /**
//     * 发送用户找回密码验证码
//     * @param string $phone:手机号码
//     * @return array
//     * @return @param code:返回码
//     * @return @param msg:返回码说明
//     */
//    public function sendUserFindpwd()
//    {
//        if (trim(I('post.phone')))
//        {
//            $phone = trim(I('post.phone')); // 手机号码
//            if(is_phone($phone))
//            {
//                // 判断手机是否存在
//                $User=new \Common\Model\UserModel();
//                $res = $User->where ( "phone='$phone'" )->field ( 'uid' )->find ();
//                if ($res ['uid']=='')
//                {
//                    // 该手机号码尚未注册
//                    $res=array(
//                        'code'=>$this->ERROR_CODE_USER['PHONE_NON_REGISTERED'],
//                        'msg'=>$this->ERROR_CODE_USER_ZH[$this->ERROR_CODE_USER['PHONE_NON_REGISTERED']]
//                    );
//                } else {
//                    //发送手机短信
//                    $sms=new \Common\Model\SmsModel();
//                    $content="@1@=".rand(1000,9999);
//                    $res=$sms->sendMessage($phone, $content, 'default');
//                }
//            } else {
//                //手机号码格式不正确
//                $res=array(
//                    'code'=>$this->ERROR_CODE_COMMON['PARAMETER_ERROR'],
//                    'msg'=>'手机号码格式不正确'
//                );
//            }
//        }else {
//            //参数不正确，参数缺失
//            $res=array(
//                'code'=>$this->ERROR_CODE_COMMON['PARAMETER_ERROR'],
//                'msg'=>$this->ERROR_CODE_COMMON_ZH[$this->ERROR_CODE_COMMON['PARAMETER_ERROR']]
//            );
//        }
//        echo json_encode ($res,JSON_UNESCAPED_UNICODE);
//    }
//
//    /**
//     * 发送用户变更手机号验证码
//     * @param string $phone:手机号码
//     * @return array
//     * @return @param code:返回码
//     * @return @param msg:返回码说明
//     */
//    public function sendChangeBanding()
//    {
//        if (trim(I('post.phone')))
//        {
//            $phone = trim(I('post.phone')); // 手机号码
//            if(is_phone($phone))
//            {
//                // 判断手机是否存在
//                $User=new \Common\Model\UserModel();
//                $res = $User->where ( "phone='$phone'" )->field ( 'uid' )->find ();
//                if ($res ['uid']!='')
//                {
//                    // 该手机号码已被使用！
//                    $res=array(
//                        'code'=>$this->ERROR_CODE_USER['PHONE_ALREADY_EXISTS'],
//                        'msg'=>$this->ERROR_CODE_USER_ZH[$this->ERROR_CODE_USER['PHONE_ALREADY_EXISTS']]
//                    );
//                } else {
//                    //发送手机短信
//                    $sms=new \Common\Model\SmsModel();
//                    $content="@1@=".rand(1000,9999);
//                    $res=$sms->sendMessage($phone, $content, 'default');
//                }
//            } else {
//                //手机号码格式不正确
//                $res=array(
//                    'code'=>$this->ERROR_CODE_COMMON['PARAMETER_ERROR'],
//                    'msg'=>'手机号码格式不正确'
//                );
//            }
//        }else {
//            //参数不正确，参数缺失
//            $res=array(
//                'code'=>$this->ERROR_CODE_COMMON['PARAMETER_ERROR'],
//                'msg'=>$this->ERROR_CODE_COMMON_ZH[$this->ERROR_CODE_COMMON['PARAMETER_ERROR']]
//            );
//        }
//        echo json_encode ($res,JSON_UNESCAPED_UNICODE);
//    }
//
//    /**
//     * 验证短信验证码是否正确
//     * @param string $phone:手机号码
//     * @param string $code:验证码
//     * @return array
//     * @return @param code:返回码
//     * @return @param msg:返回码说明
//     */
//    public function checkCode()
//    {
//        if(trim(I('post.phone')) and trim(I('post.code')))
//        {
//            //验证短信是否正确
//            $phone=trim(I('post.phone'));
//            $code=trim(I('post.code'));
//            $sms=new \Common\Model\SmsModel();
//            $res=$sms->checkCode($phone, $code);
//        }else {
//            //参数不正确，参数缺失
//            $res=array(
//                'code'=>$this->ERROR_CODE_COMMON['PARAMETER_ERROR'],
//                'msg'=>$this->ERROR_CODE_COMMON_ZH[$this->ERROR_CODE_COMMON['PARAMETER_ERROR']]
//            );
//        }
//        echo json_encode ($res,JSON_UNESCAPED_UNICODE);
//    }
}