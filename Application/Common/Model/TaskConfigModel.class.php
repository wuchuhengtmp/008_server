<?php
/**
 * 任务配置
 */
namespace Common\Model;
use Think\Model;

class TaskConfigModel extends Model
{
    //验证规则
    protected $_validate =array();
    
    /**
     * 获取任务配置信息
     * @param int $id ID
     * @return array
     */
    public function getConfig($id)
    {
        $msg=$this->where("id=$id")->find();
        if($msg){
            return $msg;
        }else {
            return array();
        }
    }
}