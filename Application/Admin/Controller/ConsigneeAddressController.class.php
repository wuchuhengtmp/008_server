<?php
/**
 * 收货地址管理
 */
namespace Admin\Controller;
use Think\Controller;
class ConsigneeAddressController extends Controller
{
	public function index()
	{
		$where='1';
		//地区
		if(I('get.province'))
		{
			$province=I('get.province');
			$where.=" and province='$province'";
		}
		if(I('get.city'))
		{
			$city=I('get.city');
			$where.=" and city='$city'";
		}
		if(I('get.county'))
		{
			$county=I('get.county');
			$where.=" and county='$county'";
		}
		//详细地址
		if(I('get.detail_address'))
		{
			$detail_address=I('get.detail_address');
			$where.=" and detail_address like '%$detail_address%'";
		}
		//收件人
		if(I('get.consignee'))
		{
			$consignee=I('get.consignee');
			$where.=" and consignee like '%$consignee%'";
		}
		//联系电话
		if(I('get.contact_number'))
		{
			$contact_number=I('get.contact_number');
			$where.=" and contact_number like '%$contact_number%'";
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
		$ConsigneeAddress=new \Common\Model\ConsigneeAddressModel();
		$count=$ConsigneeAddress->where($where)->count();
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
			
		$list = $ConsigneeAddress->where($where)->page($p.','.$per)->order('id desc')->select();
		$this->assign('list',$list);
			
		$this->display();
	}
	
	//新增收货地址
	public function add()
	{
		if(I('post.'))
		{
			layout(false);
			if(trim(I('post.user_account')) and trim(I('post.consignee')) and trim(I('post.contact_number')) and I('post.province') and I('post.city') and I('post.county') and trim(I('post.detail_address')))
			{
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
						'province'=>I('post.province'),
						'city'=>I('post.city'),
						'county'=>I('post.county'),
						'detail_address'=>trim(I('post.detail_address')),
						'company'=>trim(I('post.company')),
						'consignee'=>trim(I('post.consignee')),
						'contact_number'=>trim(I('post.contact_number')),
						'postcode'=>trim(I('post.postcode')),
						'is_default'=>I('post.is_default')
				);
				$ConsigneeAddress=new \Common\Model\ConsigneeAddressModel();
				if(!$ConsigneeAddress->create($data))
				{
					//验证不通过
					$this->error($ConsigneeAddress->getError());
				}else {
					//验证通过
					//开启事务
					$ConsigneeAddress->startTrans();
					$res=$ConsigneeAddress->add($data);
					if($res!==false)
					{
						//将其他地址修改为非默认地址
						if(I('post.is_default')=='Y')
						{
							$id=$res;
							$data2=array(
									'is_default'=>'N'
							);
							$res2=$ConsigneeAddress->where("id!=$id and user_id='$uid'")->save($data2);
							if($res2!==false)
							{
								//提交事务
								$ConsigneeAddress->commit();
								$this->success('添加收货地址成功！',U('index'));
							}else {
								//回滚
								$ConsigneeAddress->rollback();
								$this->error('添加收货地址失败！');
							}
						}else {
							//提交事务
							$ConsigneeAddress->commit();
							$this->success('添加收货地址成功！',U('index'));
						}
					}else {
						//回滚
						$ConsigneeAddress->rollback();
						$this->error('添加收货地址失败！');
					}
				}
			}else {
				$this->error('所属用户、省、市、县、详细地址、收件人 、联系电话不能为空！');
			}
		}else {
			$this->display();
		}
	}
	
	//编辑收货地址
	public function edit($id)
	{
		//获取收货地址信息
		$ConsigneeAddress=new \Common\Model\ConsigneeAddressModel();
		$msg=$ConsigneeAddress->getMsg($id);
		$this->assign('msg',$msg);
		
		if(I('post.'))
		{
			layout(false);
			if(trim(I('post.user_account')) and trim(I('post.consignee')) and trim(I('post.contact_number')) and I('post.province') and I('post.city') and I('post.county') and trim(I('post.detail_address')))
			{
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
						'province'=>I('post.province'),
						'city'=>I('post.city'),
						'county'=>I('post.county'),
						'detail_address'=>trim(I('post.detail_address')),
						'company'=>trim(I('post.company')),
						'consignee'=>trim(I('post.consignee')),
						'contact_number'=>trim(I('post.contact_number')),
						'postcode'=>trim(I('post.postcode')),
						'is_default'=>I('post.is_default')
				);
				if(!$ConsigneeAddress->create($data))
				{
					//验证不通过
					$this->error($ConsigneeAddress->getError());
				}else {
					//验证通过
					//开启事务
					$ConsigneeAddress->startTrans();
					$res=$ConsigneeAddress->where("id='$id'")->save($data);
					if($res!==false)
					{
						//将其他地址修改为非默认地址
						if(I('post.is_default')=='Y')
						{
							$data2=array(
									'is_default'=>'N'
							);
							$res2=$ConsigneeAddress->where("id!='$id' and user_id='$uid'")->save($data2);
							if($res2!==false)
							{
								//提交事务
								$ConsigneeAddress->commit();
								$this->success('编辑收货地址成功！',U('index'));
							}else {
								//回滚
								$ConsigneeAddress->rollback();
								$this->error('编辑收货地址失败！');
							}
						}else {
							//提交事务
							$ConsigneeAddress->commit();
							$this->success('编辑收货地址成功！',U('index'));
						}
					}else {
						//回滚
						$ConsigneeAddress->rollback();
						$this->error('编辑收货地址失败！');
					}
				}
			}else {
				$this->error('所属用户、省、市、县、详细地址、收件人 、联系电话不能为空！');
			}
		}else {
			$this->display();
		}
	}
	
	//删除收货地址
	public function del($id)
	{
		$ConsigneeAddress=new \Common\Model\ConsigneeAddressModel();
		$res=$ConsigneeAddress->where("id='$id'")->delete();
		if($res!==false)
		{
			echo '1';
		}else {
			echo '0';
		}
	}
}