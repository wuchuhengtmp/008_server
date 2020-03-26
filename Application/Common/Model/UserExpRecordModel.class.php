<?php
/**
 * 用户经验值变更记录管理
 */
namespace Common\Model;
use Think\Model;

class UserExpRecordModel extends Model
{
	//验证规则
	protected $_validate =array(
		array('user_id','require','会员不能为空！',self::EXISTS_VALIDATE),  //存在验证，必填
		array('user_id','is_positive_int','会员不存在',self::EXISTS_VALIDATE,'function'),  //存在验证，必须是正整数
		array('exp','require','经验值不能为空！',self::EXISTS_VALIDATE),  //存在验证，必填
		//array('exp','is_natural_num','经验值必须为大于零的整数！',self::EXISTS_VALIDATE,'function'),  //存在验证 ，必须是自然数	
		//array('all_exp','is_natural_num','经验值存量必须为大于零的整数！',self::VALUE_VALIDATE,'function'),  //值不为空的时候验证，必须是自然数
		array('create_time','require','时间不能为空！',self::EXISTS_VALIDATE),  //存在验证，必填
		array('create_time','is_datetime','时间格式不正确！',self::EXISTS_VALIDATE,'function'),  //存在验证，必须是正确的时间格式
	    array('action',array('register','recommend_register','complete_info','signin','buy','buy_r','buy_tb','buy_tb_r','buy_pdd','buy_pdd_r','buy_jd','buy_jd_r'),'操作类型不正确！',self::VALUE_VALIDATE,'in'),  //存在验证
	);
	
	/**
	 * 添加经验值变动记录
	 * @param int $user_id:用户ID
	 * @param int $point:变动经验值
	 * @param int $all_point:经验值存量
	 * @param string $action:操作类型
	 * @return boolean
	 */
	public function addLog($user_id,$exp,$all_exp=0,$action)
	{
		$data=array(
			'user_id'=>$user_id,
		    'exp'=>$exp,
		    'all_exp'=>$all_exp,
			'create_time'=>date('Y-m-d H:i:s'),
			'action'=>$action
		);
		if(!$this->create($data)) {
			return false;
		}else {
			$res_add=$this->add($data);
			if($res_add!==false) {
				return true;
			}else {
				return false;
			}
		}
	}
	
	/**
	 * 获取操作类型描述
	 * @param string $action:操作类型
	 * @return array
	 */
	public function getActionZh($action)
	{
	    switch ($action) {
	        case 'recommend_register':
	            $action_zh='推荐注册获取';
	            $action_symbol='+';
	            break;
	        case 'buy_first_r':
	            $action_zh='推荐首次购物获取';
	            $action_symbol='+';
	            break;
	        case 'register':
	            $action_zh='注册获取';
	            $action_symbol='+';
	            break;
	        case 'complete_info':
	            $action_zh='完善资料获取';
	            $action_symbol='+';
	            break;
	        case 'auth':
	            $action_zh='实名认证获取';
	            $action_symbol='+';
	            break;
	        case 'signin':
	            $action_zh='签到获取';
	            $action_symbol='+';
	            break;
	        case 'buy':
	            $action_zh='商城购物获取';
	            $action_symbol='+';
	            break;
	        case 'buy_r':
	            $action_zh='推荐商城购物获取';
	            $action_symbol='+';
	            break;
	        case 'buy_tb':
	            $action_zh='淘宝购物获取';
	            $action_symbol='+';
	            break;
	        case 'buy_tb_r':
	            $action_zh='推荐淘宝购物获取';
	            $action_symbol='+';
	            break;
	        case 'buy_pdd':
	            $action_zh='拼多多购物获取';
	            $action_symbol='+';
	            break;
	        case 'buy_pdd_r':
	            $action_zh='推荐拼多多购物获取';
	            $action_symbol='+';
	            break;
	        case 'buy_jd':
	            $action_zh='京东购物获取';
	            $action_symbol='+';
	            break;
	        case 'buy_jd_r':
	            $action_zh='推荐京东购物获取';
	            $action_symbol='+';
	            break;
	        default:
	            $action_zh='';
	            $action_symbol='';
	            break;
	    }
	    return array(
	        'action_zh'=>$action_zh,
	        'action_symbol'=>$action_symbol
	    );
	}
	
	/**
	 * 获取会员经验值变动记录
	 * @param int $user_id:会员ID
	 * @return array|boolean
	 */
	public function getRecordList($user_id)
	{
		$list=$this->where("user_id='$user_id'")->select();
		if($list!==false) {
			$num=count($list);
			for($i=0;$i<$num;$i++) {
			    $list[$i]['exp']*=1;
			    $list[$i]['all_exp']*=1;
			    //获取操作类型描述
			    $res_action=$this->getActionZh($list[$i]['action']);
			    $list[$i]['action_zh']=$res_action['action_zh'];
			    $list[$i]['action_symbol']=$res_action['action_symbol'];
			}
			return $list;
		}else {
			return false;
		}
	}
	
	/**
	 * 获取分页会员经验值变动记录
	 * @param int $user_id:会员ID
	 * @param string $order:排序规则 desc降序 asc升序
	 * @param int $p:当前页码，默认1
	 * @param int $per:每页显示条数，默认15
	 * @return array|boolean
	 * @return array $list:会员积分变动记录
	 * @return array $page:分页条
	 */
	public function getRecordListByPage($user_id,$order='desc',$p=1,$per=15)
	{
		//列表
		$where="user_id='$user_id'";
		$list = $this->where($where)->page($p.','.$per)->order("id $order")->select();
		if($list!==false) {
			$num=count($list);
			for($i=0;$i<$num;$i++) {
			    $list[$i]['exp']*=1;
			    $list[$i]['all_exp']*=1;
			    //获取操作类型描述
			    $res_action=$this->getActionZh($list[$i]['action']);
			    $list[$i]['action_zh']=$res_action['action_zh'];
			    $list[$i]['action_symbol']=$res_action['action_symbol'];
			}
			//总数
			$count=$this->where($where)->count();
			//分页
			$Page=new \Common\Model\PageModel();
			$show=$Page->show($count, $per);
			$content=array(
					'list'=>$list,
					'page'=>$show
			);
			return $content;
		}else {
			return false;
		}
	}
}