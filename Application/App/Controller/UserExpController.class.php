<?php
/**
 * 用户经验值管理
 */
namespace App\Controller;
use App\Common\Controller\AuthController;

class UserExpController extends AuthController
{
    /**
     * 获取用户经验值变动记录
     * @param string $token:用户身份令牌
     * @param int $page:页码，默认第1页
     * @param int $per:每页条数，默认10条
     * @return array
     * @return @param code:返回码
     * @return @param msg:返回码说明
     * @return @param data:返回数据
     * @return @param data->list:用户经验值变动记录
     */
    public function getRecord()
    {
        if(trim(I('post.token'))) {
            //判断用户身份
            $token=trim(I('post.token'));
            $User=new \Common\Model\UserModel();
            $res_token=$User->checkToken($token);
            if($res_token['code']!=0) {
                //用户身份不合法
                $res=$res_token;
            }else {
                $uid=$res_token['uid'];
                if(trim(I('post.page'))) {
                    $page=trim(I('post.page'));
                }else {
                    $page=1;
                }
                if(trim(I('post.per'))) {
                    $per=trim(I('post.per'));
                }else {
                    $per=10;
                }
                //获取用户经验值变动记录
                $UserExpRecord=new \Common\Model\UserExpRecordModel();
                $res_record=$UserExpRecord->getRecordListByPage($uid,'desc',$page,$per);
                if($res_record!==false) {
                    $data=array(
                        'list'=>$res_record['list']
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
}
?>