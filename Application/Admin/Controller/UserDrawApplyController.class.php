<?php
/**
 * 资金管理-用户提现管理
 */
namespace Admin\Controller;
use Admin\Common\Controller\AuthController;
class UserDrawApplyController extends AuthController
{
	//待审核申请
	public function checkPending()
	{
		$where="is_check='N'";
		if(I('get.phone'))
		{
			$phone=I('get.phone');
			$User=new \Common\Model\UserModel();
			$UserMsg=$User->where("phone like '%$phone%'")->find();
			if($UserMsg)
			{
				$user_id=$UserMsg['uid'];
				$where.=" and user_id='$user_id'";
			}else {
				layout(false);
				$this->error('用户不存在！');
			}
		}
		$UserDrawApply=new \Common\Model\UserDrawApplyModel();
		$count=$UserDrawApply->where($where)->count();
		$per = 15;
		if($_GET['p'])
		{
			$p=$_GET['p'];
		}else {
			$p=1;
		}
		$Page=new \Common\Model\PageModel();
		$show= $Page->show($count,$per);// 分页显示输出
		$this->assign('page',$show);
		 
		$list = $UserDrawApply->where($where)->page($p.','.$per)->order('user_draw_apply_id desc')->select();
		$this->assign('list',$list);
		 
		$this->display();
	}
	
	//审核
	public function check($user_draw_apply_id)
	{
		//获取申请信息
		$UserDrawApply=new \Common\Model\UserDrawApplyModel();
		$msg=$UserDrawApply->getApplyDetail($user_draw_apply_id);
		if($msg['is_check']=='Y') {
			$this->error('该提现申请已审核，请勿重复审核');
		}
		
		if($_POST) {
			layout(false);
			if(I('post.check_result') and trim(I('post.money'))) {
				if($msg['money']!=trim(I('post.money'))) {
					$this->error('提现金额不正确！');
					exit();
				}
				$check_result=I('post.check_result');
				$data=array(
						'is_check'=>'Y',
						'check_result'=>$check_result,
						'check_time'=>date('Y-m-d H:i:s'),
						'admin_id'=>$_SESSION['admin_id']
				);
				if(!$UserDrawApply->create($data)) {
					//验证不通过
					$this->error($UserDrawApply->getError());
				}else {
					//验证通过
					//开启事务
					$UserDrawApply->startTrans();
					$res=$UserDrawApply->where("user_draw_apply_id='$user_draw_apply_id'")->save($data);
					if($res!==false) {
						//如果审核通过，修改用户余额
						if($check_result=='Y') {
							//判断用户余额是否足够
							$user_id=$msg['user_id'];
							$User=new \Common\Model\UserModel();
							$UserMsg=$User->getUserMsg($user_id);
							//余额
							$balance=$UserMsg['balance'];
							//本月佣金收入
							$UserBalanceRecord=new \Common\Model\UserBalanceRecordModel();
							$amount_mouth=$UserBalanceRecord->where("user_id='$user_id' and status='2' and date_format(pay_time,'%Y-%m')=date_format(now(),'%Y-%m') and (action='tbk' or action='tbk_r' or action='tbk_r2' or action='pdd' or action='pdd_r' or action='pdd_r2')")->sum('money');
							$amount_mouth=$amount_mouth/100;
							//可提现金额
							$amount=$balance-$amount_mouth;
							
							$money=$msg['money'];
							if($amount>=$money) {
								//修改用户余额
								$res_balance=$User->where("uid='$user_id'")->setDec('balance',$money);
								//记录用户余额变动记录
								$UserBalanceRecord=new \Common\Model\UserBalanceRecordModel();
								$all_money=$UserMsg['balance']-$money;
								//保留2位小数，四舍五入
								$all_money=round($all_money, 2);
								$res_record=$UserBalanceRecord->addLog($user_id, $money, $all_money, 'draw','2');
								if($res_balance!==false and $res_record!==false) {
								    //根据提现方式进行处理
								    switch (DRAW_METHOD){
								        //人工审核
								        case '1':
								            //提交事务
								            $UserDrawApply->commit();
								            $this->success('审核成功！',U('checkPending'));
								            break;
								        //后台审核
								        case '2':
								            if($money>DRAW_AUTO_MONEY){
								                //大额提现，判断是否自动转账
								                if(DRAW_AUTO_TYPE=='Y'){
								                    //后台审核通过后自动转账
								                    //单笔转账到支付宝账号
								                    //获取支付宝请求参数
								                    Vendor('pay.alipayApp','','.class.php');
								                    $alipayApp=new \alipayApp();
								                    $out_biz_no=time().'_'.$user_draw_apply_id;
								                    $payee_account=$msg['account'];
								                    $amount=$money;
								                    $payer_show_name=APP_NAME;//付款方姓名
								                    $payee_real_name=trim(I('post.truename'));//收款方真实姓名
								                    $res_ali=$alipayApp->fundTransToaccountTransfer($out_biz_no, $payee_account, $amount,$payer_show_name,$payee_real_name);
								                    if($res_ali['code']==0) {
								                        //提交事务
								                        $UserDrawApply->commit();
								                        $this->success('审核成功！',U('checkPending'));
								                        break;
								                    }else {
								                        //回滚
								                        $UserDrawApply->rollback();
								                        //转账失败
								                        $error_msg='支付宝转账失败：'.$res_ali['msg'].',账号：'.$payee_account.'，姓名：'.$payee_real_name;
								                        $this->error($error_msg,'',20);
								                        break;
								                    }
								                }else {
								                    //后台审核通过后不自动转账，线下转账
								                    //提交事务
								                    $UserDrawApply->commit();
								                    $this->success('审核成功！',U('checkPending'));
								                    break;
								                }
								            }else {
								                //后台审核-小额提现直接自动转账
								                //单笔转账到支付宝账号
								                //获取支付宝请求参数
								                Vendor('pay.alipayApp','','.class.php');
								                $alipayApp=new \alipayApp();
								                $out_biz_no=time().'_'.$user_draw_apply_id;
								                $payee_account=$msg['account'];
								                $amount=$money;
								                $payer_show_name=APP_NAME;//付款方姓名
								                $payee_real_name=trim(I('post.truename'));//收款方真实姓名
								                $res_ali=$alipayApp->fundTransToaccountTransfer($out_biz_no, $payee_account, $amount,$payer_show_name,$payee_real_name);
								                if($res_ali['code']==0) {
								                    //提交事务
								                    $UserDrawApply->commit();
								                    $this->success('审核成功！',U('checkPending'));
								                    break;
								                }else {
								                    //回滚
								                    $UserDrawApply->rollback();
								                    //转账失败
								                    $error_msg='支付宝转账失败：'.$res_ali['msg'].',账号：'.$payee_account.'，姓名：'.$payee_real_name;
								                    $this->error($error_msg,'',20);
								                    break;
								                }
								            }
								        //自动转账
								        case '3':
								            if(DRAW_AUTO_TYPE=='Y'){
								                //后台审核通过后自动转账
								                //单笔转账到支付宝账号
								                //获取支付宝请求参数
								                Vendor('pay.alipayApp','','.class.php');
								                $alipayApp=new \alipayApp();
								                $out_biz_no=time().'_'.$user_draw_apply_id;
								                $payee_account=$msg['account'];
								                $amount=$money;
								                $payer_show_name=APP_NAME;//付款方姓名
								                $payee_real_name=trim(I('post.truename'));//收款方真实姓名
								                $res_ali=$alipayApp->fundTransToaccountTransfer($out_biz_no, $payee_account, $amount,$payer_show_name,$payee_real_name);
								                if($res_ali['code']==0) {
								                    //提交事务
								                    $UserDrawApply->commit();
								                    $this->success('审核成功！',U('checkPending'));
								                    break;
								                }else {
								                    //回滚
								                    $UserDrawApply->rollback();
								                    //转账失败
								                    $error_msg='支付宝转账失败：'.$res_ali['msg'].',账号：'.$payee_account.'，姓名：'.$payee_real_name;
								                    $this->error($error_msg,'',20);
								                    break;
								                }
								            }else {
								                //后台审核通过后不自动转账，线下转账
								                //提交事务
								                $UserDrawApply->commit();
								                $this->success('审核成功！',U('checkPending'));
								                break;
								            }
								        default:
								            break;
								    }
									
								}else {
									//回滚
									$UserDrawApply->rollback();
									$this->error('修改用户余额失败！');
								}
							}else {
								//回滚
								$UserDrawApply->rollback();
								$this->error('用户余额不足！');
							}
						}else {
							//提交事务
							$UserDrawApply->commit();
							$this->success('审核成功！',U('checkPending'));
						}
					}else {
						//回滚
						$UserDrawApply->rollback();
						$this->error('审核失败！');
					}
				}
			}else {
				$this->error('请选择审核结果、输入提现金额！');
			}
		}else {
		    $this->assign('msg',$msg);
		    
			$this->display();
		}
	}
	
	//已审核申请
	public function checked()
	{
		$where="is_check='Y'";
		if(I('get.phone'))
		{
			$phone=I('get.phone');
			$User=new \Common\Model\UserModel();
			$UserMsg=$User->where("phone like '%$phone%'")->find();
			if($UserMsg)
			{
				$user_id=$UserMsg['uid'];
				$where.=" and user_id='$user_id'";
			}else {
				layout(false);
				$this->error('用户不存在！');
			}
		}
		//根据时间查询
		if(I('get.begin_time') and I('get.end_time'))
		{
			$begin_time=I('get.begin_time');
			$end_time=I('get.end_time').' 23:59:59';
			$where="check_time BETWEEN '$begin_time' AND '$end_time'";
		}
		if(I('get.check_result'))
		{
			$check_result=I('get.check_result');
			$where="check_result='$check_result'";
		}
		$UserDrawApply=new \Common\Model\UserDrawApplyModel();
		//总数
		$count=$UserDrawApply->where($where)->count();
		//总金额
		$allmoney=$UserDrawApply->where($where)->sum('money');
		$this->assign('allnum',$count);
		$this->assign('allmoney',$allmoney);
		
		$per = 15;
		if($_GET['p'])
		{
			$p=$_GET['p'];
		}else {
			$p=1;
		}
		$Page=new \Common\Model\PageModel();
		$show= $Page->show($count,$per);// 分页显示输出
		$this->assign('page',$show);
			
		$list = $UserDrawApply->where($where)->page($p.','.$per)->order('user_draw_apply_id desc')->select();
		$this->assign('list',$list);
			
		$this->display();
	}
	
	//已审核申请详情
	public function checkedView($user_draw_apply_id)
	{
		//获取申请信息
		$UserDrawApply=new \Common\Model\UserDrawApplyModel();
		$msg=$UserDrawApply->getApplyDetail($user_draw_apply_id);
		$this->assign('msg',$msg);
		$this->display();
	}
}