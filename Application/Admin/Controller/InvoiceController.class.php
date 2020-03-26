<?php
/**
 * 商城系统-发票信息管理
*/
namespace Admin\Controller;
use Admin\Common\Controller\AuthController;
class InvoiceController extends AuthController
{
	public function index()
	{
		$where='1';
		//发票类型
		if(I('get.type'))
		{
			$type=I('get.type');
			$where.=" and type='$type'";
		}
		//发票抬头
		if(I('get.purchaser'))
		{
			$purchaser=I('get.purchaser');
			$where.=" and purchaser like '%$purchaser%'";
		}
		//纳税人识别号
		if(I('get.taxpayer_id'))
		{
			$taxpayer_id=I('get.taxpayer_id');
			$where.=" and taxpayer_id like '%$taxpayer_id%'";
		}
		//地址
		if(I('get.address'))
		{
			$address=I('get.address');
			$where.=" and address like '%$address%'";
		}
		//判断用户是否存在
		if(I('get.user_account'))
		{
			$user_account=trim(I('get.user_account'));
			$User=new \Common\Model\UserModel();
			$res_user=$User->where("username='$user_account' or phone='$user_account' or email='$user_account'")->field('uid')->find();
			if($res_user['uid'])
			{
				$user_id=$res_user['uid'];
				$where.=" and user_id='$user_id'";
			}else {
				layout(false);
				$this->error('所属用户不存在，请核对后填写正确的用户账号！');
			}
		}
		$Invoice=new \Common\Model\InvoiceModel();
		$count=$Invoice->where($where)->count();
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
		 
		$list = $Invoice->where($where)->page($p.','.$per)->order('invoice_id desc')->select();
		$this->assign('list',$list);
		 
		$this->display();
	}
	
	//添加发票
	public function add()
	{
		if(I('post.'))
		{
			layout(false);
			if(trim(I('post.user_account')) and trim(I('post.type')) and trim(I('post.purchaser')) and trim(I('post.contact')) and trim(I('post.address')) and trim(I('post.linkman')) and I('post.is_default'))
			{
				$type=I('post.type');
				if($type=='1')
				{
					if(trim(I('post.purchaser'))=='')
					{
						$this->error('请填写企业纳税人识别号！');
					}
				}
				//判断用户是否存在
				$user_account=trim(I('post.user_account'));
				$User=new \Common\Model\UserModel();
				$res_user=$User->where("username='$user_account' or phone='$user_account' or email='$user_account'")->field('uid')->find();
				if($res_user['uid'])
				{
					$uid=$res_user['uid'];
				}else {
					$this->error('所属用户不存在，请核对后填写正确的用户账号！');
				}
				$data=array(
						'user_id'=>$uid,
						'purchaser'=>trim(I('post.purchaser')),
						'taxpayer_id'=>trim(I('post.taxpayer_id')),
						'bank'=>trim(I('post.bank')),
						'account'=>trim(I('post.account')),
						'contact'=>trim(I('post.contact')),
						'address'=>trim(I('post.address')),
						'linkman'=>trim(I('post.linkman')),
						'is_default'=>I('post.is_default'),
						'type'=>$type,
				);
				$Invoice=new \Common\Model\InvoiceModel();
				if(!$Invoice->create($data))
				{
					//验证不通过
					$this->error($Invoice->getError());
				}else {
					//验证通过
					//开启事务
					$Invoice->startTrans();
					$res=$Invoice->add($data);
					if($res!==false)
					{
						//将其他发票修改为非默认
						if(I('post.is_default')=='Y')
						{
							$invoice_id=$res;
							$data2=array(
									'is_default'=>'N'
							);
							$res2=$Invoice->where("invoice_id!=$invoice_id and user_id='$uid'")->save($data2);
							if($res2!==false)
							{
								//提交事务
								$Invoice->commit();
								$this->success('新增发票成功！',U('index'));
							}else {
								//回滚
								$Invoice->rollback();
								$this->error('新增发票失败！');
							}
						}else {
							//提交事务
							$Invoice->commit();
							$this->success('新增发票成功！',U('index'));
						}
					}else {
						//回滚
						$Invoice->rollback();
						$this->error('新增发票失败！');
					}
				}
			}else {
				$this->error('所属用户、发票抬头、联系电话、地址、收件人不能为空！');
			}
		}else {
			$this->display();
		}
	}
	
	//编辑发票
	public function edit($invoice_id)
	{
		//获取发票信息
		$Invoice=new \Common\Model\InvoiceModel();
		$msg=$Invoice->getInvoiceMsg($invoice_id);
		$this->assign('msg',$msg);
	
		if(I('post.'))
		{
			layout(false);
			if(trim(I('post.user_account')) and I('post.type') and trim(I('post.purchaser')) and trim(I('post.contact')) and trim(I('post.address')) and trim(I('post.linkman')) and I('post.is_default'))
			{
				$type=I('post.type');
				if($type=='1')
				{
					if(trim(I('post.purchaser'))=='')
					{
						$this->error('请填写企业纳税人识别号！');
					}
				}
				//判断用户是否存在
				$user_account=trim(I('post.user_account'));
				$User=new \Common\Model\UserModel();
				$res_user=$User->where("username='$user_account' or phone='$user_account' or email='$user_account'")->field('uid')->find();
				if($res_user['uid'])
				{
					$uid=$res_user['uid'];
				}else {
					$this->error('所属用户不存在，请核对后填写正确的用户账号！');
				}
				$data=array(
						'purchaser'=>trim(I('post.purchaser')),
						'taxpayer_id'=>trim(I('post.taxpayer_id')),
						'bank'=>trim(I('post.bank')),
						'account'=>trim(I('post.account')),
						'contact'=>trim(I('post.contact')),
						'address'=>trim(I('post.address')),
						'linkman'=>trim(I('post.linkman')),
						'is_default'=>I('post.is_default'),
						'type'=>$type,
				);
				if(!$Invoice->create($data))
				{
					//验证不通过
					$this->error($Invoice->getError());
				}else {
					//验证通过
					//开启事务
					$Invoice->startTrans();
					$res=$Invoice->where("invoice_id='$invoice_id'")->save($data);
					if($res!==false)
					{
						//将其他发票修改为非默认
						if(I('post.is_default')=='Y')
						{
							$data2=array(
									'is_default'=>'N'
							);
							$res2=$Invoice->where("invoice_id!=$invoice_id and user_id='$uid'")->save($data2);
							if($res2!==false)
							{
								//提交事务
								$Invoice->commit();
								$this->success('编辑发票成功！',U('index'));
							}else {
								//回滚
								$Invoice->rollback();
								$this->error('编辑发票失败！');
							}
						}else {
							//提交事务
							$Invoice->commit();
							$this->success('编辑发票成功！',U('index'));
						}
					}else {
						//回滚
						$Invoice->rollback();
						$this->error('编辑发票失败！');
					}
				}
			}else {
				$this->error('所属用户、发票抬头、联系电话、地址、收件人不能为空！');
			}
		}else {
			$this->display();
		}
	}
	
	//删除发票
	public function del($invoice_id)
	{
		$Invoice=new \Common\Model\InvoiceModel();
		$res=$Invoice->where("invoice_id='$invoice_id'")->delete();
		if($res!==false)
		{
			echo '1';
		}else {
			echo '0';
		}
	}
}