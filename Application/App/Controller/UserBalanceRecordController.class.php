<?php
/**
 * 用户余额变动记录管理
 */
namespace App\Controller;
use App\Common\Controller\AuthController;

class UserBalanceRecordController extends AuthController
{
	/**
	 * 获取用户余额变动记录
	 * @param string $token:用户身份令牌
	 * @param int $page:页码，默认第1页
	 * @param int $per:每页条数，默认10条
	 * @return array
	 * @return @param code:返回码
	 * @return @param msg:返回码说明
	 * @return @param data:返回数据
	 * @return @param data->list:用户余额变动记录
	 */
	public function getBalanceRecord()
	{
		if(trim(I('post.token')))
		{
			//判断用户身份
			$token=trim(I('post.token'));
			$User=new \Common\Model\UserModel();
			$res_token=$User->checkToken($token);
			if($res_token['code']!=0)
			{
				//用户身份不合法
				$res=$res_token;
			}else {
				$uid=$res_token['uid'];
				if(trim(I('post.page')))
				{
					$page=trim(I('post.page'));
				}else {
					$page=1;
				}
				if(trim(I('post.per')))
				{
					$per=trim(I('post.per'));
				}else {
					$per=10;
				}
				//获取用户余额变动记录
				$UserBalanceRecord=new \Common\Model\UserBalanceRecordModel();
				$res_record=$UserBalanceRecord->getRecordListByPage($uid,'2','desc',$page,$per);
				if($res_record!==false)
				{
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
	
	/**
	 * 获取佣金/提现收入记录
	 * @param string $token:用户身份令牌
	 * @param string $type:收入类型，1：佣金、2：提现
	 * @param int $page:页码，默认第1页
	 * @param int $per:每页条数，默认10条
	 * @return array
	 * @return @param code:返回码
	 * @return @param msg:返回码说明
	 * @return @param data:返回数据
	 * @return @param data->list:会员码收入记录
	 */
	public function getBalanceRecord2()
	{
		if(trim(I('post.token')) and trim(I('post.type')))
		{
			//判断用户身份
			$token=trim(I('post.token'));
			$User=new \Common\Model\UserModel();
			$res_token=$User->checkToken($token);
			if($res_token['code']!=0)
			{
				//用户身份不合法
				$res=$res_token;
			}else {
				$uid=$res_token['uid'];
				$where="user_id='$uid' and status='2'";
				//收入类型
				$type=trim(I('post.type'));
				switch ($type)
				{
					//佣金收入
					case '1':
						$where.=" and action in('tbk','tbk_r','tbk_r2','tbk_rt','tbk_refund','pdd','pdd_r','pdd_r2','pdd_rt','jd','jd_r','jd_r2','jd_rt')";
						break;
					//提现
					case '2':
						$where.=" and action='draw'";
						break;
					default:
						break;
				}
				//分页
				if(trim(I('post.page')))
				{
					$page=trim(I('post.page'));
				}else {
					$page=1;
				}
				if(trim(I('post.per')))
				{
					$per=trim(I('post.per'));
				}else {
					$per=10;
				}
				//总收入
				$UserBalanceRecord=new \Common\Model\UserBalanceRecordModel();
				$all_balance=$UserBalanceRecord->where($where)->sum('money');
				$all_balance=$all_balance/100;
				
				//获取用户余额变动记录
				$list=$UserBalanceRecord->where($where)->field('id,user_id,money,all_money,pay_time,action')->page($page,$per)->order("id desc")->select();
				if($list!==false)
				{
					$num=count($list);
					for ($i=0;$i<$num;$i++)
					{
						$list[$i]['money']=$list[$i]['money']/100;
						//保留2位小数，四舍五不入
						$list[$i]['money']=substr(sprintf("%.3f",$list[$i]['money']),0,-1);
						//当前余额
						$list[$i]['all_money']=$list[$i]['all_money']/100;
						$list[$i]['all_money']=substr(sprintf("%.3f",$list[$i]['all_money']),0,-1);
						switch ($list[$i]['action'])
						{
							case 'card':
								$action_zh='充值卡兑换增加';
								$action_symbol='+';
								break;
							case 'recharge':
								$action_zh='在线充值';
								$action_symbol='+';
								break;
							case 'tbk':
								$action_zh='淘宝返利';
								$action_symbol='+';
								break;
							case 'tbk_r':
								$action_zh='淘宝一级返利';
								$action_symbol='+';
								break;
							case 'tbk_r2':
								$action_zh='淘宝二级返利';
								$action_symbol='+';
								break;
							case 'tbk_rt':
								$action_zh='淘宝团队返利';
								$action_symbol='+';
								break;
							case 'tbk_refund':
								$action_zh='淘宝订单维权退款扣除';
								$action_symbol='-';
								break;
							case 'pdd':
								$action_zh='拼多多返利';
								$action_symbol='+';
								break;
							case 'pdd_r':
								$action_zh='拼多多一级返利';
								$action_symbol='+';
								break;
							case 'pdd_r2':
								$action_zh='拼多多二级返利';
								$action_symbol='+';
								break;
							case 'pdd_rt':
								$action_zh='拼多多团队返利';
								$action_symbol='+';
								break;
							case 'jd':
								$action_zh='京东返利';
								$action_symbol='+';
								break;
							case 'jd_r':
								$action_zh='京东一级返利';
								$action_symbol='+';
								break;
							case 'jd_r2':
								$action_zh='京东二级返利';
								$action_symbol='+';
								break;
							case 'jd_rt':
								$action_zh='京东团队返利';
								$action_symbol='+';
								break;
							case 'draw':
								$action_zh='提现';
								$action_symbol='-';
								break;
							case 'recommend1':
								$action_zh='一级推荐返利';
								$action_symbol='+';
								break;
							case 'recommend2':
								$action_zh='二级推荐返利';
								$action_symbol='+';
								break;
							case 'recommend3':
								$action_zh='三级推荐返利';
								$action_symbol='+';
								break;
							case 'bonus':
								$action_zh='春节红包';
								$action_symbol='+';
								break;
							default:
								$action_zh='';
								$action_symbol='';
								break;
						}
						$list[$i]['action_zh']=$action_zh;
						$list[$i]['action_symbol']=$action_symbol;
					}
					$data=array(
							'all_balance'=>$all_balance,
							'list'=>$list
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
	
	/**
	 * 获取收益统计
	 * @param string $token:用户身份令牌
	 * @return array
	 * @return @param code:返回码
	 * @return @param msg:返回码说明
	 * @return @param data:返回数据
	 */
	public function statistics()
	{
		if(trim(I('post.token')))
		{
			//判断用户身份
			$token=trim(I('post.token'));
			$User=new \Common\Model\UserModel();
			$res_token=$User->checkToken($token);
			if($res_token['code']!=0)
			{
				//用户身份不合法
				$res=$res_token;
			}else {
				$uid=$res_token['uid'];
				//获取用户余额
				$userMsg=$User->getUserMsg($uid);
				$balance=$userMsg['balance'];
				
				//获取用户累计结算收益
				$UserBalanceRecord=new \Common\Model\UserBalanceRecordModel();
				$amount=$UserBalanceRecord->where("user_id='$uid' and action in('tbk','tbk_r','tbk_r2','tbk_rt','pdd','pdd_r','pdd_r2','pdd_rt','jd','jd_r','jd_r2','jd_rt')")->sum('money');
				$amount=$amount/100;
				$amount=substr(sprintf("%.3f",$amount),0,-1);
				
				//根据用户会员组用户比例做相应扣除
				$sql="select g.fee_user from __PREFIX__user u,__PREFIX__user_group g where u.uid='$uid' and u.group_id=g.id";
				$res_g=M()->query($sql);
				$fee_user=$res_g[0]['fee_user']/100;
				
				$UserBalanceRecordTmp=new \Common\Model\UserBalanceRecordTmpModel();
				//本月预估
				$amount_current=$UserBalanceRecordTmp->where("user_id='$uid' and date_format(create_time,'%Y-%m')=date_format(now(),'%Y-%m')")->sum('money');
				$amount_current=substr(sprintf("%.3f",$amount_current/100),0,-1);
				
				//上月预估
				$amount_last=$UserBalanceRecordTmp->where("user_id='$uid'  and date_format(create_time,'%Y-%m')=date_format(DATE_SUB(curdate(), INTERVAL 1 MONTH),'%Y-%m')")->sum('money');
				$amount_last=substr(sprintf("%.3f",$amount_last/100),0,-1);
				$amount_last_finish=$amount_last;
				
				
				//今日数据统计
				//预估
				$today_amount1=$UserBalanceRecordTmp->where("user_id='$uid' and date_format(create_time,'%Y-%m-%d')=date_format(now(),'%Y-%m-%d')")->sum('money');
				$today_amount1=substr(sprintf("%.3f",$today_amount1/100),0,-1);
				//订单数
				$today_num=$UserBalanceRecordTmp->where("user_id='$uid' and date_format(create_time,'%Y-%m-%d')=date_format(now(),'%Y-%m-%d')")->count();
				//结算
				$today_amount2=$UserBalanceRecord->where("user_id='$uid' and date_format(pay_time,'%Y-%m-%d')=date_format(now(),'%Y-%m-%d') and action!='draw'")->sum('money');
				$today_amount2=substr(sprintf("%.3f",$today_amount2/100),0,-1);
				
				$today=array(
						'num'=>$today_num,
						'amount1'=>$today_amount1,
						'amount2'=>$today_amount2,
				);
				
				//昨日数据统计
				//预估
				$yesterday_amount1=$UserBalanceRecordTmp->where("user_id='$uid' and DATE_SUB(CURDATE(), INTERVAL 1 DAY) = date(create_time)")->sum('money');
				$yesterday_amount1=substr(sprintf("%.3f",$yesterday_amount1/100),0,-1);
				//订单数
				$yesterday_num=$UserBalanceRecordTmp->where("user_id='$uid' and DATE_SUB(CURDATE(), INTERVAL 1 DAY) = date(create_time)")->count();
				//结算
				$yesterday_amount2=$UserBalanceRecord->where("user_id='$uid' and DATE_SUB(CURDATE(), INTERVAL 1 DAY) = date(pay_time) and action!='draw'")->sum('money');
				$yesterday_amount2=substr(sprintf("%.3f",$yesterday_amount2/100),0,-1);
				
				$yesterday=array(
						'num'=>$yesterday_num,
						'amount1'=>$yesterday_amount1,
						'amount2'=>$yesterday_amount2,
				);
				
				//近7日数据统计
				//预估
				$sevenday_amount1=$UserBalanceRecordTmp->where("user_id='$uid' and DATE_SUB(CURDATE(), INTERVAL 7 DAY) <= date(create_time)")->sum('money');
				$sevenday_amount1=substr(sprintf("%.3f",$sevenday_amount1/100),0,-1);
				//订单数
				$sevenday_num=$UserBalanceRecordTmp->where("user_id='$uid' and DATE_SUB(CURDATE(), INTERVAL 7 DAY) <= date(create_time)")->count();
				//结算
				$sevenday_amount2=$UserBalanceRecord->where("user_id='$uid' and DATE_SUB(CURDATE(), INTERVAL 7 DAY) <= date(pay_time) and action!='draw'")->sum('money');
				$sevenday_amount2=substr(sprintf("%.3f",$sevenday_amount2/100),0,-1);
				
				$sevenday=array(
						'num'=>$sevenday_num,
						'amount1'=>$sevenday_amount1,
						'amount2'=>$sevenday_amount2,
				);
				
				//近30日数据统计
				//预估
				$lastmonth_amount1=$UserBalanceRecordTmp->where("user_id='$uid' and DATE_SUB(CURDATE(), INTERVAL 30 DAY) <= date(create_time)")->sum('money');
				$lastmonth_amount1=substr(sprintf("%.3f",$lastmonth_amount1/100),0,-1);
				//订单数
				$lastmonth_num=$UserBalanceRecordTmp->where("user_id='$uid' and DATE_SUB(CURDATE(), INTERVAL 30 DAY) <= date(create_time)")->count();
				//结算
				$lastmonth_amount2=$UserBalanceRecord->where("user_id='$uid' and DATE_SUB(CURDATE(), INTERVAL 30 DAY) <= date(pay_time) and action!='draw'")->sum('money');
				$lastmonth_amount2=substr(sprintf("%.3f",$lastmonth_amount2/100),0,-1);
				
				$lastmonth=array(
						'num'=>$lastmonth_num,
						'amount1'=>$lastmonth_amount1,
						'amount2'=>$lastmonth_amount2,
				);
				
				$data=array(
						'balance'=>$balance,
						'amount'=>$amount,
						'amount_last_finish'=>$amount_last_finish,
						'amount_current'=>$amount_current,
						'today'=>$today,
						'yesterday'=>$yesterday,
						'sevenday'=>$sevenday,
						'lastmonth'=>$lastmonth,
				);
				$res=array(
						'code'=>$this->ERROR_CODE_COMMON['SUCCESS'],
						'msg'=>'成功',
						'data'=>$data
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
	
	/**
	 * 获取会员中心数据统计
	 * @param string $token:用户身份令牌
	 * @return array
	 * @return @param code:返回码
	 * @return @param msg:返回码说明
	 * @return @param data:返回数据
	 * @return @param data->amount:淘宝客统计
	 * @return @param data->referrer:推荐统计
	 */
	public function statistics2()
	{
		if(trim(I('post.token')))
		{
			//判断用户身份
			$token=trim(I('post.token'));
			$User=new \Common\Model\UserModel();
			$res_token=$User->checkToken($token);
			if($res_token['code']!=0)
			{
				//用户身份不合法
				$res=$res_token;
			}else {
				$uid=$res_token['uid'];
				//获取用户余额、经验值、会员组
				$userMsg=$User->getUserMsg($uid);
				$balance=$userMsg['balance'];
				$exp=$userMsg['exp']*1;
				$group_id=$userMsg['group_id'];
				
				//根据用户会员组用户比例做相应扣除
				$sql="select g.fee_user from __PREFIX__user u,__PREFIX__user_group g where u.uid='$uid' and u.group_id=g.id";
				$res_g=M()->query($sql);
				$fee_user=$res_g[0]['fee_user']/100;
				
				//淘宝客统计
				$TbOrder=new \Common\Model\TbOrderModel();
				//拼多多统计
				$PddOrder=new \Common\Model\PddOrderModel();
				//京东统计
				$JingdongOrderDetail=new \Common\Model\JingdongOrderDetailModel();
				
				//累计预估-淘宝
				$tbk_amount=$TbOrder->where("user_id='$uid' and tk_status!='13'")->sum('pub_share_pre_fee');
				$tbk_amount*=$fee_user;
				//累计预估-拼多多
				$pdd_amount=$PddOrder->where("user_id='$uid' and order_status in (0,1,2,3,5)")->sum('promotion_amount');
				$pdd_amount=$pdd_amount*$fee_user/100;
				//累计预估-京东
				$jd_amount=$JingdongOrderDetail->where("user_id='$uid' and validCode in (16,17,18)")->sum('estimateCosPrice');
				$jd_amount=$jd_amount*$fee_user;
				//保留2位小数，四舍五不入
				$amount=$tbk_amount+$pdd_amount+$jd_amount;
				$amount=substr(sprintf("%.3f",$amount),0,-1);
				
				$UserBalanceRecord=new \Common\Model\UserBalanceRecordModel();
				/* //今日收益=结算+预估
				//结算
				$UserBalanceRecord=new \Common\Model\UserBalanceRecordModel();
				$amount_today_finish=$UserBalanceRecord->where("user_id='$uid' and status='2' and date_format(pay_time,'%Y-%m-%d')=date_format(now(),'%Y-%m-%d') and action in ('tbk','tbk_r','tbk_r2','tbk_rt','jd','jd_r','jd_r2','jd_rt','pdd','pdd_r','pdd_r2','pdd_rt')")->sum('money');
				$amount_today_finish=$amount_today_finish/100;
				//今日收益（预估）-淘宝
				$tbk_amount_today=$TbOrder->where("user_id='$uid' and tk_status!='13' and date_format(create_time,'%Y-%m-%d')=date_format(now(),'%Y-%m-%d')")->sum('pub_share_pre_fee');
				$tbk_amount_today*=$fee_user;
				//今日收益（预估）-拼多多
				$pdd_amount_today=$PddOrder->where("user_id='$uid' and order_status in (0,1,2,3,5) and date_format(order_create_time,'%Y-%m-%d')=date_format(now(),'%Y-%m-%d')")->sum('promotion_amount');
				$pdd_amount_today=$pdd_amount_today*$fee_user/100;
				//今日收益（预估）-京东
				$jd_amount_today=$JingdongOrderDetail->where("user_id='$uid' and validCode in (16,17,18)  and date_format(orderTime,'%Y-%m-%d')=date_format(now(),'%Y-%m-%d')")->sum('estimateCosPrice');
				$jd_amount_today=$jd_amount_today*$fee_user;
				//保留2位小数，四舍五不入
				$amount_today=$amount_today_finish+$tbk_amount_today+$pdd_amount_today+$jd_amount_today;
				$amount_today=substr(sprintf("%.3f",$amount_today),0,-1); */
				//今日收益
				$UserBalanceRecordTmp=new \Common\Model\UserBalanceRecordTmpModel();
				$amount_today=$UserBalanceRecordTmp->where("user_id='$uid'  and date_format(create_time,'%Y-%m-%d')=date_format(now(),'%Y-%m-%d')")->sum('money');
				$amount_today=substr(sprintf("%.3f",$amount_today/100),0,-1);
				
				//本月预估-淘宝
				$amount_current=$UserBalanceRecordTmp->where("user_id='$uid'  and date_format(create_time,'%Y-%m')=date_format(now(),'%Y-%m')")->sum('money');
				$amount_current=substr(sprintf("%.3f",$amount_current/100),0,-1);
				
				/* //本月预估-淘宝
				$tbk_amount_current=$TbOrder->where("user_id='$uid' and tk_status!='13' and date_format(create_time,'%Y-%m')=date_format(now(),'%Y-%m')")->sum('pub_share_pre_fee');
				$tbk_amount_current*=$fee_user;
				//本月预估-拼多多
				$pdd_amount_current=$PddOrder->where("user_id='$uid' and order_status in (0,1,2,3,5) and date_format(order_create_time,'%Y-%m')=date_format(now(),'%Y-%m')")->sum('promotion_amount');
				$pdd_amount_current=$pdd_amount_current*$fee_user/100;
				//本月预估-京东
				$jd_amount_current=$JingdongOrderDetail->where("user_id='$uid' and validCode in (16,17,18)  and date_format(orderTime,'%Y-%m')=date_format(now(),'%Y-%m')")->sum('estimateCosPrice');
				$jd_amount_current=$jd_amount_current*$fee_user;
				//保留2位小数，四舍五不入
				$amount_current=$tbk_amount_current+$pdd_amount_current+$jd_amount_current;
				$amount_current=substr(sprintf("%.3f",$amount_current),0,-1); */
				
				//上月预估-淘宝
				$amount_last=$UserBalanceRecordTmp->where("user_id='$uid'  and date_format(create_time,'%Y-%m')=date_format(DATE_SUB(curdate(), INTERVAL 1 MONTH),'%Y-%m')")->sum('money');
				$amount_last=substr(sprintf("%.3f",$amount_last/100),0,-1);
				
				/* //上月预估-淘宝
				$tbk_amount_last=$TbOrder->where("user_id='$uid' and tk_status!='13' and date_format(create_time,'%Y-%m')=date_format(DATE_SUB(curdate(), INTERVAL 1 MONTH),'%Y-%m')")->sum('pub_share_pre_fee');
				$tbk_amount_last*=$fee_user;
				//上月预估-拼多多
				$pdd_amount_last=$PddOrder->where("user_id='$uid' and order_status in (0,1,2,3,5) and date_format(order_create_time,'%Y-%m')=date_format(DATE_SUB(curdate(), INTERVAL 1 MONTH),'%Y-%m')")->sum('promotion_amount');
				$pdd_amount_last=$pdd_amount_last*$fee_user/100;
				//上月预估-京东
				$jd_amount_last=$JingdongOrderDetail->where("user_id='$uid' and validCode in (16,17,18)  and date_format(orderTime,'%Y-%m')=date_format(DATE_SUB(curdate(), INTERVAL 1 MONTH),'%Y-%m')")->sum('estimateCosPrice');
				$jd_amount_last=$jd_amount_last*$fee_user;
				//保留2位小数，四舍五不入
				$amount_last=$tbk_amount_last+$pdd_amount_last+$jd_amount_last;
				$amount_last=substr(sprintf("%.3f",$amount_last),0,-1); */
				
				//上月结算
				$amount_last_finish=$UserBalanceRecord->where("user_id='$uid' and status='2' and date_format(pay_time,'%Y-%m')=date_format(DATE_SUB(curdate(), INTERVAL 1 MONTH),'%Y-%m') and action in ('tbk','tbk_r','tbk_r2','tbk_rt','jd','jd_r','jd_r2','jd_rt','pdd','pdd_r','pdd_r2','pdd_rt')")->sum('money');
				$amount_last_finish=$amount_last_finish/100;
				//保留2位小数，四舍五不入
				$amount_last_finish=substr(sprintf("%.3f",$amount_last_finish),0,-1);
				
				$data=array(
						'balance'=>$balance,
						'exp'=>$exp,
						'group_id'=>$group_id,
						'amount'=>$amount,
						'amount_today'=>$amount_today,
						'amount_current'=>$amount_current,
						'amount_last'=>$amount_last,
						'amount_last_finish'=>$amount_last_finish,
				);
				$res=array(
						'code'=>$this->ERROR_CODE_COMMON['SUCCESS'],
						'msg'=>'成功',
						'data'=>$data
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
	
	/**
	 * 获取团队会员中心收益统计
	 * @param string $token:用户身份令牌
	 * @return array
	 * @return @param code:返回码
	 * @return @param msg:返回码说明
	 * @return @param data:返回数据
	 * @return @param data->amount:淘宝客统计
	 */
	public function teamStatistics()
	{
		if(trim(I('post.token')))
		{
			//判断用户身份
			$token=trim(I('post.token'));
			$User=new \Common\Model\UserModel();
			$res_token=$User->checkToken($token);
			if($res_token['code']!=0)
			{
				//用户身份不合法
				$res=$res_token;
			}else {
				$uid=$res_token['uid'];
				//获取用户余额、经验值、会员组
				$userMsg=$User->getUserMsg($uid);
				$balance=$userMsg['balance'];
				$exp=$userMsg['exp']*1;
				$group_id=$userMsg['group_id'];
				
				//获取整个团队成员
				$teamlist=$User->where("FIND_IN_SET($uid,path)")->order('uid asc')->select();
				$team_uid='';
				foreach ($teamlist as $l)
				{
					$team_uid.=$l['uid'].',';
				}
				$team_uid=substr($team_uid, 0,-1);
				
				//淘宝客统计
				$TbOrder=new \Common\Model\TbOrderModel();
				//拼多多统计
				$PddOrder=new \Common\Model\PddOrderModel();
				//京东统计
				$JingdongOrderDetail=new \Common\Model\JingdongOrderDetailModel();
	
				//累计预估-淘宝
				$tbk_amount=$TbOrder->where("user_id in ($team_uid) and tk_status!='13'")->sum('pub_share_pre_fee');
				//累计预估-拼多多
				$pdd_amount=$PddOrder->where("user_id in ($team_uid) and order_status in (0,1,2,3,5)")->sum('promotion_amount');
				$pdd_amount=$pdd_amount/100;
				//累计预估-京东
				$jd_amount=$JingdongOrderDetail->where("user_id in ($team_uid) and validCode in (16,17,18)")->sum('estimateCosPrice');
				//保留2位小数，四舍五不入
				$amount=$tbk_amount+$pdd_amount+$jd_amount;
				$amount=substr(sprintf("%.3f",$amount),0,-1);
	
				$UserBalanceRecord=new \Common\Model\UserBalanceRecordModel();
				//今日收益
				$UserBalanceRecordTmp=new \Common\Model\UserBalanceRecordTmpModel();
				$amount_today=$UserBalanceRecordTmp->where("user_id in ($team_uid)  and date_format(create_time,'%Y-%m-%d')=date_format(now(),'%Y-%m-%d')")->sum('money');
				$amount_today=substr(sprintf("%.3f",$amount_today/100),0,-1);
	
				//本月预估-淘宝
				$amount_current=$UserBalanceRecordTmp->where("user_id in ($team_uid)  and date_format(create_time,'%Y-%m')=date_format(now(),'%Y-%m')")->sum('money');
				$amount_current=substr(sprintf("%.3f",$amount_current/100),0,-1);
				
				//上月预估-淘宝
				$amount_last=$UserBalanceRecordTmp->where("user_id in ($team_uid)  and date_format(create_time,'%Y-%m')=date_format(DATE_SUB(curdate(), INTERVAL 1 MONTH),'%Y-%m')")->sum('money');
				$amount_last=substr(sprintf("%.3f",$amount_last/100),0,-1);
				
				//上月结算
				$amount_last_finish=$UserBalanceRecord->where("user_id in ($team_uid) and status='2' and date_format(pay_time,'%Y-%m')=date_format(DATE_SUB(curdate(), INTERVAL 1 MONTH),'%Y-%m') and action in ('tbk','tbk_r','tbk_r2','tbk_rt','jd','jd_r','jd_r2','jd_rt','pdd','pdd_r','pdd_r2','pdd_rt')")->sum('money');
				$amount_last_finish=$amount_last_finish/100;
				//保留2位小数，四舍五不入
				$amount_last_finish=substr(sprintf("%.3f",$amount_last_finish),0,-1);
	
				$data=array(
						'balance'=>$balance,
						'exp'=>$exp,
						'group_id'=>$group_id,
						'amount'=>$amount,
						'amount_today'=>$amount_today,
						'amount_current'=>$amount_current,
						'amount_last'=>$amount_last,
						'amount_last_finish'=>$amount_last_finish,
				);
				$res=array(
						'code'=>$this->ERROR_CODE_COMMON['SUCCESS'],
						'msg'=>'成功',
						'data'=>$data
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
	
	/**
	 * 获取可提现统计
	 * @param string $token:用户身份令牌
	 * @return array
	 * @return @param code:返回码
	 * @return @param msg:返回码说明
	 * @return @param data:返回数据
	 * @return @param data->amount:可提现余额
	 * @return @param data->amount_buy:购买返利金额
	 * @return @param data->amount_referrer:推荐返利金额
	 */
	public function drawStatistics()
	{
		if(trim(I('post.token')))
		{
			//判断用户身份
			$token=trim(I('post.token'));
			$User=new \Common\Model\UserModel();
			$res_token=$User->checkToken($token);
			if($res_token['code']!=0)
			{
				//用户身份不合法
				$res=$res_token;
			}else {
				$uid=$res_token['uid'];
				$userMsg=$User->getUserMsg($uid);
				//余额
				$balance=$userMsg['balance'];
				//本月淘宝客、拼多多、京东收入
				$UserBalanceRecord=new \Common\Model\UserBalanceRecordModel();
				$amount_mouth=$UserBalanceRecord->where("user_id='$uid' and status='2' and date_format(pay_time,'%Y-%m')=date_format(now(),'%Y-%m') and action in ('tbk','tbk_r','tbk_r2','tbk_rt','pdd','pdd_r','pdd_r2','pdd_rt','jd','jd_r','jd_r2','jd_rt')")->sum('money');
				$amount_mouth=$amount_mouth/100;
				//可提现金额
				$amount=$balance-$amount_mouth;
				//保留2位小数，四舍五不入
				$amount=substr(sprintf("%.3f",$amount),0,-1);
				
				//购买返利-不包含本月
				$amount_buy=$UserBalanceRecord->where("user_id='$uid' and status='2' and date_format(pay_time,'%Y-%m')!=date_format(now(),'%Y-%m') and action in ('tbk','pdd','jd')")->sum('money');
				$amount_buy=$amount_buy/100;
				//保留2位小数，四舍五不入
				$amount_buy=substr(sprintf("%.3f",$amount_buy),0,-1);
				
				//推荐返利-不包含本月
				$amount_referrer=$UserBalanceRecord->where("user_id='$uid' and status='2' and date_format(pay_time,'%Y-%m')!=date_format(now(),'%Y-%m') and action in ('tbk_r','tbk_r2','tbk_rt','pdd_r','pdd_r2','pdd_rt','jd_r','jd_r2','jd_rt')")->sum('money');
				$amount_referrer=$amount_referrer/100;
				//保留2位小数，四舍五不入
				$amount_referrer=substr(sprintf("%.3f",$amount_referrer),0,-1);
				
				$data=array(
						'amount'=>$amount,
						'amount_buy'=>$amount_buy,
						'amount_referrer'=>$amount_referrer
				);
				$res=array(
						'code'=>$this->ERROR_CODE_COMMON['SUCCESS'],
						'msg'=>'成功',
						'data'=>$data
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
	
	/**
	 * 领取春节/新人红包
	 * @param string $token:用户身份令牌
	 * @return array
	 * @return @param code:返回码
	 * @return @param msg:返回码说明
	 */
	public function receiveBonus()
	{
		//验签
		$Sign=new \Common\Model\SignModel();
		$res_sign=$Sign->checkVerify($_POST);
		if($res_sign!==true)
		{
			//验签错误
			$res=array(
					'code'=>$this->ERROR_CODE_COMMON['SIGN_ERROR'],
					'msg'=>$this->ERROR_CODE_COMMON_ZH[$this->ERROR_CODE_COMMON['SIGN_ERROR']]
			);
			echo json_encode ($res,JSON_UNESCAPED_UNICODE);
			exit();
		}
		
		if(trim(I('post.token')))
		{
			//判断用户身份
			$token=trim(I('post.token'));
			$User=new \Common\Model\UserModel();
			$res_token=$User->checkToken($token);
			if($res_token['code']!=0)
			{
				//用户身份不合法
				$res=$res_token;
			}else {
				$uid=$res_token['uid'];
				//判断用户是否已经领取过红包
				$UserBalanceRecord=new \Common\Model\UserBalanceRecordModel();
				$res_exist=$UserBalanceRecord->where("user_id='$uid' and action in ('bonus','bonus2')")->find();
				if($res_exist)
				{
					//已领取，不准重复领取
					$res=array(
							'code'=>1,
							'msg'=>'只有新人可以领取红包',
							'money'=>$res_exist['money']/100
					);
				}else {
					//未领取，可以领取
					$userMsg=$User->getUserMsg($uid);
					if($userMsg['is_buy']=='Y')
					{
						//已购物的人领取固定的1.88元
						//红包金额
						$money=1.88;
					}else {
						//未购物，领取红包金额随机
						$money_arr=array(1.88,2.68,2.88,3.68,3.88,4.88);
						$i=rand(0, 5);
						//红包金额
						$money=$money_arr[$i];
					}
					//开启事务
					$User->startTrans();
					//给用户增加余额
					$res_balance=$User->where("uid='$uid'")->setInc('balance',$money);
					//增加余额变动记录
					$all_money=$userMsg['balance']+$money;
					$res_balance_record=$UserBalanceRecord->addLog($uid, $money, $all_money, 'bonus2');
					if($res_balance!==false and $res_balance_record!==false)
					{
						//提交事务
						$User->commit();
						$res=array(
								'code'=>$this->ERROR_CODE_COMMON['SUCCESS'],
								'msg'=>'恭喜您成功领取'.$money.'元红包，可在我的余额中查看',
								'money'=>$money
						);
					}else {
						//回滚
						$User->rollback();
						//数据库错误
						$res=array(
								'code'=>$this->ERROR_CODE_COMMON['DB_ERROR'],
								'msg'=>$this->ERROR_CODE_COMMON_ZH[$this->ERROR_CODE_COMMON['DB_ERROR']]
						);
					}
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
	
	/**
	 * 是否领取春节/新人红包
	 * @param string $token:用户身份令牌
	 * @return array
	 * @return @param code:返回码
	 * @return @param msg:返回码说明
	 */
	public function isReceiveBonus()
	{
		//验签
		$Sign=new \Common\Model\SignModel();
		$res_sign=$Sign->checkVerify($_POST);
		if($res_sign!==true)
		{
			//验签错误
			$res=array(
					'code'=>$this->ERROR_CODE_COMMON['SIGN_ERROR'],
					'msg'=>$this->ERROR_CODE_COMMON_ZH[$this->ERROR_CODE_COMMON['SIGN_ERROR']]
			);
			echo json_encode ($res,JSON_UNESCAPED_UNICODE);
			exit();
		}
		
		if(trim(I('post.token')))
		{
			//判断用户身份
			$token=trim(I('post.token'));
			$User=new \Common\Model\UserModel();
			$res_token=$User->checkToken($token);
			if($res_token['code']!=0)
			{
				//用户身份不合法
				$res=$res_token;
			}else {
				$uid=$res_token['uid'];
				//判断用户是否已经领取过红包
				$UserBalanceRecord=new \Common\Model\UserBalanceRecordModel();
				$res_exist=$UserBalanceRecord->where("user_id='$uid' and action in ('bonus','bonus2')")->count();
				if($res_exist)
				{
					//已领取
					$is_receive='Y';
				}else {
					//未领取
					$is_receive='N';
				}
				$data=array(
						'is_receive'=>$is_receive
				);
				$res=array(
						'code'=>$this->ERROR_CODE_COMMON['SUCCESS'],
						'msg'=>'成功',
						'data'=>$data
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
	
	/**
	 * 佣金收益排行榜
	 * @param string $token:用户身份令牌
	 * @return array
	 * @return @param code:返回码
	 * @return @param msg:返回码说明
	 */
	public function ranking()
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
	            //获取佣金收益前10名
	            $UserBalanceRecord=new \Common\Model\UserBalanceRecordModel();
	            $sql="select user_id,sum(money) as all_money from __PREFIX__user_balance_record where action!='draw' group by user_id order by all_money desc limit 0,10";
	            $alist=M()->query($sql);
	            $num=count($alist);
	            $list=array();
	            $i=0;
	            foreach ($alist as $l){
	                $i++;
	                $all_money=$l['all_money']/100;
	                //获取用户头像昵称
	                $tMsg=$User->getUserDetail($l['user_id']);
	                //用户头像
	                $avatar=$tMsg['detail']['avatar'];
	                if($tMsg['detail']['avatar']){
	                    //判断头像是否为第三方应用头像
	                    if(is_url($tMsg['detail']['avatar'])) {
	                        
	                    }else {
	                        $avatar=WEB_URL.$tMsg['detail']['avatar'];
	                    }
	                }
	                //用户昵称
	                if($tMsg['detail']['nickname']){
	                    $nickname=$tMsg['detail']['nickname'];
	                }else if($tMsg['phone']){
	                    //隐藏手机号码
	                    $nickname=substr_replace($tMsg['phone'],'**',4,4);
	                }
	                $list[]=array(
	                    'num'=>$i,
	                    'user_id'=>$l['user_id'],
	                    'all_money'=>$all_money,
	                    'avatar'=>$avatar,
	                    'nickname'=>$nickname,
	                );
	            }
	            //我的头像
	            $UserDetail=new \Common\Model\UserDetailModel();
	            $uDetail=$UserDetail->getUserDetailMsg($uid);
	            //用户头像
	            $u_avatar='';
	            if($uDetail['avatar']){
	                //判断头像是否为第三方应用头像
	                if(is_url($uDetail['avatar'])) {
	                    
	                }else {
	                    $u_avatar=WEB_URL.$uDetail['avatar'];
	                }
	            }
	            //获取用户总收益
	            $sql_u="select sum(money) as all_money from __PREFIX__user_balance_record where user_id=$uid and action!='draw'";
	            $res_u=M()->query($sql_u);
	            $balance=$res_u[0]['all_money'];
	            //获取用户排名
	            $sql_u2="select * from (select sum(money) as all_money from __PREFIX__user_balance_record where action!='draw' group by user_id order by all_money desc) tmp where tmp.all_money>'$balance'";
	            $res_u2=M()->query($sql_u2);
	            $rank=count($res_u2)+1;
	            $uMsg=array(
	                'avatar'=>$u_avatar,
	                'balance'=>$balance,
	                'rank'=>$rank
	            );
	            $data=array(
	                'uMsg'=>$uMsg,
	                'list'=>$list
	            );
	            $res=array(
	                'code'=>$this->ERROR_CODE_COMMON['SUCCESS'],
	                'msg'=>'成功',
	                'data'=>$data
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
}
?>