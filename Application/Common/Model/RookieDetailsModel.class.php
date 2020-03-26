<?php
/**
 * 拉新活动
 */
namespace Common\Model;
use Think\Model;

class RookieDetailsModel extends Model
{
    public $ERROR_CODE_COMMON = array();     // 公共返回码
    public $ERROR_CODE_COMMON_ZH = array();  // 公共返回码中文描述
    public $ERROR_CODE_ROOKIE = array();       // 拉新活动返回码
    public $ERROR_CODE_ROOKIE_ZH = array();    // 拉新活动返回码中文描述

    // 初始化
    protected function _initialize()
    {
        $this->ERROR_CODE_COMMON = json_decode(error_code_common,true);
        $this->ERROR_CODE_COMMON_ZH = json_decode(error_code_common_zh,true);
        $this->ERROR_CODE_ROOKIE = json_decode(error_code_rookie,true);
        $this->ERROR_CODE_ROOKIE_ZH = json_decode(error_code_rookie_zh,true);
    }

    // 验证规则
    protected $_validate = array(
        array('lv','is_positive_int','等级必须为正整数',2,'function'), // 值不为空验证，等级必须为正整数
        array('reward_type',array('1','2'),'请选择奖励方式',1,'in'), // 值不为空验证，等级必须为正整数
    );
    
    /**
     * 获取活动奖项设置
     * @param int $rid:活动ID
     * @param string $order:排序方式，asc、desc
     * @return array|boolean
     */
    public function getRewardSet($rid,$order='asc')
    {
        $list = $this->where("rid = $rid")->order("id $order")->select();
        if($list!==false){
            return $list;
        }else {
            return false;
        }
    }

    /**
     * 根据拉新人数和设置的奖励获取奖励
     * @param int $rid:活动id
     * @param int $num:拉新人数
     * @return array 奖励信息
     * @return string $unit 奖励单位
     * @return string $reward_num 奖励数量
     * @return string $reward_type 奖励方式
     */
    public function getReward($rid,$num)
    {
        // 查询活动奖励设置信息
        $Rooki=new \Common\Model\RookieModel();
        $activityMsg=$Rooki->getActivityDetail($rid,'desc');
        $rewardSet = $activityMsg['rewardSet'];
        if ($rewardSet) {
            // 无限大的0置成mediumint的最大值16777215
            $rewardSet[0]['end_interval'] = 16777215;
            $reward_num=0;//奖励数量
            $reward_type=1;//奖励形式
            $unit = '元';//单位
            $reward_allnum=0;//总奖励金额
            $rnum=count($rewardSet);
            //判断处于哪个奖项区间
            for ($i=0; $i < $rnum; $i++) {
                if ($rewardSet[$i]['start_interval']<=$num && $num<=$rewardSet[$i]['end_interval']) {
                    // type转换成单位
                    if ($rewardSet[$i]['reward_type'] == 1) {
                        $unit = '元';
                    }
                    if ($rewardSet[$i]['reward_type'] == 2) {
                        $unit = '积分';
                    }
                    $reward_num = $rewardSet[$i]['reward_num'];
                    $reward_type = $rewardSet[$i]['reward_type'];
                    break;
                }
            }
            //获取总奖励金额
            if($activityMsg['ex_type']==1){
                //按个人*奖励数量
                $reward_allnum=$reward_num*$num;
            }else {
                //直接给奖励数量
                $reward_allnum=$reward_num;
            }
            return array(
                'reward_num'=>$reward_num,
                'reward_type'=>$reward_type,
                'unit'=>$unit,
                'reward_allnum'=>$reward_allnum
            );
        } else {
            return array(
                'reward_num'=>0,
                'reward_type'=>'1',
                'unit'=>'元',
                'reward_allnum'=>0
            );
        }
    }

    /**
     * 用户兑换奖励
     */
    public function reward($uid,$rid)
    {
        // 实例化模型
        $User = new \Common\Model\UserModel();
        $RookieUser = new \Common\Model\RookieUserModel();

        // 查询用户的兑换信息
        $is_ex = $this->queryExchangeInfo($uid,$rid);

        if ($is_ex === 'Y') {
            // 已经兑换
            $res = [
                'code'=>$this->ERROR_CODE_ROOKIE['ROOKIE_YES_EX'],
                'msg'=>$this->ERROR_CODE_ROOKIE_ZH[$this->ERROR_CODE_ROOKIE['ROOKIE_YES_EX']]
            ];
        } else if ($is_ex === 'N') {
            // 查询是否在兑换时间内
            $is_time = $this->isTimeWith($rid);
            if ($is_time === ture) {
                // 开启事务
                $RookieUser->startTrans();
                // 修改兑换状态,加入兑换时间
                $RookieUser->is_ex = 'Y';
                $RookieUser->exchange = date("Y-m-d H:i:s");
                $res = $RookieUser->where("user_id = $uid and rid = $rid")->save();
                if ($res!==false) {
                    // 加入用户余额/积分
                    $res = $this->setBalanceOrPoint($uid,$rid);
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
            // 奖励设置不存在
            $res = [
                'code'=>$this->ERROR_CODE_ROOKIE['REWARD_NULL'],
                'msg'=>$this->ERROR_CODE_ROOKIE_ZH[$this->ERROR_CODE_ROOKIE['REWARD_NULL']]
            ];
        }
    }

    /**
     * 查询兑换信息、
     * @return 'Y','N',null
     */
    public function queryExchangeInfo($uid,$rid)
    {
        $RookieUser = new \Common\Model\RookieUserModel();
        $is_ex = $RookieUser->where("user_id = $uid and rid = $rid")->getField('is_ex');
        if ($is_ex) {
            return $is_ex;
        } else {
            return null;
        }
    }

    /**
     * 判断是否在兑换时间内
     * @param int $rid 活动ID
     * @param int sTime 兑换开始时间戳
     * @param int eTime 兑换结束时间戳
     * @param int now_time 当前时间戳
     * @return bool
     */
    public function isTimeWith($rid)
    {
        $Rookie = new \Common\Model\RookieModel();
        // 查询兑换时间
        $time = $Rookie->field('exs_time,exe_time')->where("id = $rid")->find();
        $sTime = strtotime($time['exs_time']); // 转换时间戳
        $eTime = strtotime($time['exe_time']); // 转换时间戳
        // 拿当前时间戳
        $now_time = time();
        if ($sTime<=$now_time && $now_time<=$eTime) {
            return ture;
        } else {
            return false;
        }
    }

    /**
     * 给用户加入余额/积分
     * @param string $ex_type 计算奖励方式
     * @param string $num 拉新人数
     * @param array $reward 奖励信息
     * @param string $type 奖励方式
     * @param string $reward_num 奖励数量
     * @return bool
     */
    public function setBalanceOrPoint($uid,$rid)
    {
        // 获取计算奖励方式
        $User = new \Common\Model\UserModel();
        $Rookie = new \Common\Model\RookieModel();
        $RookieUser = new \Common\Model\RookieUserModel();

        // 查询计算奖励方式
        $ex_type = $Rookie->where("id = $rid")->getField('ex_type');

        // 查询拉新人数
        $num = $RookieUser->where("user_id = $uid and rid = $rid")->getField('num');

        // 根据计算奖励方式调用不同的方法
        if ($ex_type == 1) {
            $reward = $this->getPeopleReward($rid, $num);
        } else if ($ex_type == 2) {
            $reward = $this->getReward($rid, $num);
        } else {
            return false;
        }

        // 判断余额/积分
        $type = $reward[1];
        $reward_num = $reward[0];
        if ($type == 1) {
            $res = $User->where("uid = $uid")->setInc('balance',$reward_num);
        } else if ($type == 2) {
            $res = $User->where("uid = $uid")->setInc('point',$reward_num);
        } else {
            return false;
        }
        if ($res) {
            return ture;
        } else {
            return false;
        }
    }
}
