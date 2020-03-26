<?php
/**
 * 样式自定义-功能模块
 */
namespace Common\Model;
use Think\Model;

class DiyModuleModel extends Model
{
    //验证规则
    protected $_validate =array(
        array('name','require','功能模块名称不能为空！',self::EXISTS_VALIDATE),  //存在验证，必填
        array('name','1,20','功能模块名称不超过20个字符！',self::EXISTS_VALIDATE,'length'),  //存在验证，不超过20个字符
        array('icon','1,255','功能模块图标路径不正确！',self::VALUE_VALIDATE,'length'),  //值不为空的时候验证 ，不超过255个字符
        array('sort','is_natural_num','排序必须为不小于零的整数！',self::VALUE_VALIDATE,'function'),  //值不为空的时候验证 ，必须是自然数
        array('is_index_show','require','请选择首页是否显示！',self::EXISTS_VALIDATE),  //存在验证，必填
        array('is_index_show',array('Y','N'),'请选择首页是否显示！',self::EXISTS_VALIDATE,'in'),  //存在验证，只能是Y是 N否
    );
    
    /**
     * 获取功能模块类型
     * @param int $id:ID
     * @return string
     */
    public function getType($id)
    {
        switch ($id) {
            case 1:
                $type='淘宝';
                break;
            case 2:
                $type='天猫';
                break;
            case 3:
                $type='淘宝-9.9包邮';
                break;
            case 4:
                $type='淘宝-淘抢购';
                break;
            case 5:
                $type='淘宝-聚划算';
                break;
            case 6:
                $type='天猫超市';
                break;
            case 7:
                $type='淘宝-为你推荐';
                break;
            case 8:
                $type='淘宝-排行榜';
                break;
            case 9:
                $type='淘宝-超级券';
                break;
            case 10:
                $type='拼多多';
                break;
            case 11:
                $type='京东';
                break;
            case 12:
                $type='自营商城';
                break;
            case 13:
                $type='新人课堂';
                break;
            case 14:
                $type='拉新活动';
                break;
            case 15:
                $type='0元购';
                break;
            case 16:
                $type='新人红包';
                break;
            case 17:
                $type='签到';
                break;
            case 18:
                $type='信用卡';
                break;
            case 19:
                $type='淘宝-视频商品';
                break;
            case 20:
                $type='天猫国际';
                break;
            case 21:
                $type='淘宝-品牌';
                break;
            case 22:
                $type='生活券';
                break;
            case 23:
                $type='任务中心';
                break;
            case 24:
                $type='佣金收益排行榜';
                break;
            case 25:
                $type='天猫美妆';
                break;
            case 26:
                $type='飞猪旅行';
                break;
            case 27:
                $type='淘宝-抖券';
                break;
            case 28:
                $type='唯品会';
                break;
            default:
                $type='';
                break;
        }
        return $type;
    }
    
    /**
     * 获取功能模块信息
     * @param int $id:ID
     * @return array|boolean
     */
    public function getModuleMsg($id)
    {
        $msg=$this->where("id=$id")->find();
        if($msg){
            $msg['type']=$this->getType($id);
            return $msg;
        }else {
            return false;
        }
    }
    
    /**
     * 获取功能模块列表
     * @param string $is_index_show:是否首页显示 Y是 N否
     * @return array|boolean
     */
    public function getModuleList($is_index_show='')
    {
        $where='1';
        if($is_index_show){
            $where.=" and is_index_show='$is_index_show'";
        }
        $list=$this->where($where)->order('sort desc,id asc')->select();
        if($list){
            $num=count($list);
            for ($i=0;$i<$num;$i++){
                $list[$i]['type']=$this->getType($list[$i]['id']);
            }
            return $list;
        }else {
            return false;
        }
    }
}