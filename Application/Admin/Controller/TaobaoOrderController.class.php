<?php
/**
 * 值得买管理-淘宝订单兑现管理
 */
namespace Admin\Controller;
use Admin\Common\Controller\AuthController;
class TaobaoOrderController extends AuthController
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
		if(trim(I('get.order_num')))
		{
			$order_num=trim(I('get.order_num'));
			$where.=" and order_num='$order_num'";
		}
		$TaobaoOrder=new \Common\Model\TaobaoOrderModel();
		$count=$TaobaoOrder->where($where)->count();
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
		 
		$list = $TaobaoOrder->where($where)->page($p.','.$per)->order('id desc')->select();
		$this->assign('list',$list);
		 
		$this->display();
	}
	
	//审核
	public function check($id)
	{
		//获取申请信息
		$TaobaoOrder=new \Common\Model\TaobaoOrderModel();
		$msg=$TaobaoOrder->getApplyDetail($id);
		if($msg['is_check']=='Y')
		{
			layout(false);
			$this->error('该提现申请已审核，请勿重复审核');
		}
		$this->assign('msg',$msg);
		
		if(I('post.'))
		{
			layout(false);
			if(I('post.check_result'))
			{
				$check_result=I('post.check_result');
				//根据用户所属用户组，获取其实际佣金
				$uid=$msg['user_id'];
				$sql="select g.* from __PREFIX__user u,__PREFIX__user_group g where u.uid='$uid' and u.group_id=g.id";
				$res_group=M()->query($sql);
				$groupMsg=$res_group[0];
				$money1=trim(I('post.money'))*$groupMsg['fee_user']/100;
				$data=array(
						'is_check'=>'Y',
						'check_result'=>$check_result,
						'check_time'=>date('Y-m-d H:i:s'),
						'admin_id'=>$_SESSION['admin_id'],
						'money'=>round($money1, 2)
				);
				if(!$TaobaoOrder->create($data))
				{
					//验证不通过
					$this->error($TaobaoOrder->getError());
				}else {
					//验证通过
					//开启事务
					$TaobaoOrder->startTrans();
					$res=$TaobaoOrder->where("id='$id'")->save($data);
					if($res!==false)
					{
						//如果审核通过，给会员返利
						if($check_result=='Y')
						{
							if(trim(I('post.money')))
							{
								//给会员返利
								$money=trim(I('post.money'));
								$res=$TaobaoOrder->treat($msg['order_num'], $money);
								if($res!==false)
								{
									//提交事务
									$TaobaoOrder->commit();
									$this->success('审核成功！',U('checkPending'));
								}else {
									//回滚
									$TaobaoOrder->rollback();
									$this->error('审核失败！');
								}
							}else {
								//回滚
								$TaobaoOrder->rollback();
								$this->error('请填写佣金');
							}
						}else {
							//提交事务
							$TaobaoOrder->commit();
							$this->success('审核成功！',U('checkPending'));
						}
					}else {
						//回滚
						$TaobaoOrder->rollback();
						$this->error('审核失败！');
					}
				}
			}else {
				$this->error('请选择审核结果！');
			}
		}else {
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
		if(trim(I('get.order_num')))
		{
			$order_num=trim(I('get.order_num'));
			$where.=" and order_num='$order_num'";
		}
		$TaobaoOrder=new \Common\Model\TaobaoOrderModel();
		$count=$TaobaoOrder->where($where)->count();
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
			
		$list = $TaobaoOrder->where($where)->page($p.','.$per)->order('id desc')->select();
		$this->assign('list',$list);
			
		$this->display();
	}
	
	//已审核申请详情
	public function checkedView($id)
	{
		//获取申请信息
		$TaobaoOrder=new \Common\Model\TaobaoOrderModel();
		$msg=$TaobaoOrder->getApplyDetail($id);
		$this->assign('msg',$msg);
		$this->display();
	}
}