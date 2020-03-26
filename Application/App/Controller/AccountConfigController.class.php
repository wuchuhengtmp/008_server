<?php
/**
 *  AccountConfig/获取配置账号接口
 */
namespace App\Controller;
use App\Common\Controller\AuthController;

class AccountConfigController extends AuthController
{
    /**
     * 获取Config/淘宝客账号
     * @return array
     * @return @param code:返回码
     * @return @param msg:返回码说明
     * @return @param data:返回数据
     * @return @param data:tbk_/配置账号数据
     */
    public function getAccountConfig()
    {

        $data=array(
            'tbk_appkey' => TBK_APPKEY,
            'tbk_appsecret' => TBK_APPSECRET,
            'tbk_adzone_id' => ADZONE_ID,
            'tbk_auth_code' => AUTH_CODE,
        );
        $res=array(
            'code'=>$this->ERROR_CODE_COMMON['SUCCESS'],
            'msg'=>'成功',
            'data'=>$data
        );
        echo json_encode ($res,JSON_UNESCAPED_UNICODE);
    }

}
?>