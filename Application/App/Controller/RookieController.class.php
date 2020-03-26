<?php
/**
 * 拉新活动接口
 */
namespace App\Controller;
use App\Common\Controller\AuthController;

class RookieController extends AuthController
{
    /**
     * 获取活动详情，如果参数错误或者参数为零则获取第一个活动
     * @param string $id 活动ID
     * @param array $data 活动详情数据
     * @return string $code 返回码
     * @return string $msg 返回码说明
     * @return array $data 活动详情数据
     */
    public function getRookie()
    {
        // 实例化模型
        $Rookie = new \Common\Model\RookieModel();
        // 如果id变量存在
        if (trim(I("POST.id"))) {
            // 如果存在活动ID
            $id = I('POST.id');
            $data = $Rookie->where("id = $id")->find();
            if ($data === null) {
                // 未查询到数据
                $res = [
                    'code'=>$this->ERROR_CODE_ROOKIE['ROOKIE_NULL'],
                    'msg'=>$this->ERROR_CODE_ROOKIE_ZH[$this->ERROR_CODE_ROOKIE['ROOKIE_NULL']]
                ];
            } else {
                // 查询成功
                $res = [
                    'code'=>$this->ERROR_CODE_COMMON['SUCCESS'],
                    'msg'=>'成功',
                    'data'=>$data,
                ];
            }

        } else {
            // 如果id为空，则查询第一条数据
            $data = $Rookie->find();
            if ($data === false) {
                // 未查询到数据
                $res = [
                    'code'=>$this->ERROR_CODE_ROOKIE['ROOKIE_NULL'],
                    'msg'=>$this->ERROR_CODE_ROOKIE_ZH[$this->ERROR_CODE_ROOKIE['ROOKIE_NULL']]
                ];
            } else {
                // 查询成功
                $res = [
                    'code'=>$this->ERROR_CODE_COMMON['SUCCESS'],
                    'msg'=>'成功',
                    'data'=>$data,
                ];
            }
        }

        echo json_encode($res, JSON_UNESCAPED_UNICODE);
    }

    /**
     * 获取活动奖励设置
     * @param string $id 活动ID
     * @param array $data 奖励设置数据
     * @return string $code 返回码
     * @return string $msg 返回码说明
     * @return array $data 奖励设置数据
     */
    public function getRookieDetails()
    {
        // 实例化模型
        $RookieDetails = new \Common\Model\RookieDetailsModel();

        if (trim(I('POST.id'))) {
            $id = trim(I('POST.id'));
            $data = $RookieDetails->where("rid = $id")->select();

            if (empty($data)) {
                // 未查询到数据
                $res = [
                    'code'=>$this->ERROR_CODE_ROOKIE['ROOKIE_REWARD_NULL'],
                    'msg'=>$this->ERROR_CODE_ROOKIE_ZH[$this->ERROR_CODE_ROOKIE['ROOKIE_REWARD_NULL']]
                ];
            } else {
                // 查询成功
                $res = [
                    'code'=>$this->ERROR_CODE_COMMON['SUCCESS'],
                    'msg'=>'成功',
                    'data'=>$data,
                ];
            }

        } else {
            //参数不正确，参数缺失
            $res = [
                'code'=>$this->ERROR_CODE_COMMON['PARAMETER_ERROR'],
                'msg'=>$this->ERROR_CODE_COMMON_ZH[$this->ERROR_CODE_COMMON['PARAMETER_ERROR']]
            ];
        }

        echo json_encode($res, JSON_UNESCAPED_UNICODE);
    }

    /**
     * 兑现奖励
     */
    public function reward()
    {
        // 实例化模型
        $User = new \Common\Model\UserModel();
        $RookieUser = new \Common\Model\RookieUserModel();
        $RookieDetails = new \Common\Model\RookieDetailsModel();

        if (trim(I('POST.token')) and trim(I('POST.rid'))) {
            $token = trim(I('POST.token'));
            $rid = trim(I('POST.rid'));

            $res_token = $User->checkToken($token);
            if ($res_token['code']!=0) {
                // 用户身份不合法
                $res = $res_token;
            } else {
                // 获取用户ID
                $uid = $res_token['uid'];

                // 查询用户的兑换信息
                $is_ex = $RookieDetails->queryExchangeInfo($uid,$rid);

                if ($is_ex === 'Y') {
                    // 已经兑换
                    $res = [
                        'code'=>$this->ERROR_CODE_ROOKIE['ROOKIE_YES_EX'],
                        'msg'=>$this->ERROR_CODE_ROOKIE_ZH[$this->ERROR_CODE_ROOKIE['ROOKIE_YES_EX']]
                    ];
                } else if ($is_ex === 'N') {
                    // 查询是否在兑换时间内
                    $is_time = $RookieDetails->isTimeWith($rid);
                    if ($is_time === ture) {
                        // 开启事务
                        $RookieUser->startTrans();
                        // 修改兑换状态,加入兑换时间
                        $RookieUser->is_ex = 'Y';
                        $RookieUser->exchange = date("Y-m-d H:i:s");
                        $res = $RookieUser->where("user_id = $uid and rid = $rid")->save();
                        if ($res!==false) {
                            // 加入用户余额/积分
                            $res = $RookieDetails->setBalanceOrPoint($uid,$rid);
                            if ($res === ture) {
                                // 数据库提交
                                $RookieUser->commit();
                                // 兑换成功
                                $res = [
                                    'code'=>$this->ERROR_CODE_COMMON['SUCCESS'],
                                    'msg'=>'成功',
                                ];
                            } else if ($res === false) {
                                // 数据库回滚
                                $RookieUser->rollback();
                                $res = [
                                    'code'=>$this->ERROR_CODE_COMMON['DB_ERROR'],
                                    'msg'=>$this->ERROR_CODE_COMMON_ZH[$this->ERROR_CODE_COMMON['DB_ERROR']]
                                ];
                            }
                        } else {
                            // 数据库回滚
                            $RookieUser->rollback();
                            $res = [
                                'code'=>$this->ERROR_CODE_COMMON['DB_ERROR'],
                                'msg'=>$this->ERROR_CODE_COMMON_ZH[$this->ERROR_CODE_COMMON['DB_ERROR']]
                            ];
                        }
                    } else {
                        // 不在兑换时间内
                        $res = [
                            'code'=>$this->ERROR_CODE_ROOKIE['ROOKIE_NO_TIME'],
                            'msg'=>$this->ERROR_CODE_ROOKIE_ZH[$this->ERROR_CODE_ROOKIE['ROOKIE_NO_TIME']]
                        ];
                    }
                } else if ($is_ex === null) {
                    // 用户兑换不存在
                    $res = [
                        'code'=>$this->ERROR_CODE_ROOKIE['REWARD_NULL'],
                        'msg'=>$this->ERROR_CODE_ROOKIE_ZH[$this->ERROR_CODE_ROOKIE['REWARD_NULL']]
                    ];
                }
            }

        } else {
            //参数不正确，参数缺失
            $res=[
                'code'=>$this->ERROR_CODE_COMMON['PARAMETER_ERROR'],
                'msg'=>$this->ERROR_CODE_COMMON_ZH[$this->ERROR_CODE_COMMON['PARAMETER_ERROR']]
            ];
        }

        echo json_encode($res, JSON_UNESCAPED_UNICODE);
    }
}
