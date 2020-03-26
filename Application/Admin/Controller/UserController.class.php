<?php
/**
 * 会员管理
 * 会员管理
 */
namespace Admin\Controller;
use Admin\Common\Controller\AuthController;
class UserController extends AuthController
{
    public function index()
    {
    	//获取用户组列表
    	$UserGroup=new \Common\Model\UserGroupModel();
    	$glist=$UserGroup->getGroupList();
    	$this->assign('glist',$glist);
    	
    	$where='1';
    	if(trim(I('get.group_id'))) {
    		$group_id=trim(I('get.group_id'));
    		$this->assign('group_id',$group_id);
    		$where.=" and group_id='$group_id'";
    	}
    	
    	if(trim(I('get.search'))) {
    		$search=trim(I('get.search'));
    		$where.=" and (username='$search' or email='$search' or phone='$search')";
    	}
    	//备注姓名
    	if(trim(I('get.remark'))) {
    		$remark=trim(I('get.remark'));
    		$where.=" and remark like '%$remark%'";
    	}
    	//城市
    	if(trim(I('get.city'))) {
    		$city=trim(I('get.city'));
    		$where.=" and phone_city='$city'";
    	}
    	$User=new \Common\Model\UserModel();
    	//推荐人手机
    	if(trim(I('get.referrer_phone'))) {
    		$referrer_phone=trim(I('get.referrer_phone'));
    		$res_referrer=$User->where("phone='$referrer_phone'")->find();
    		if($res_referrer['uid']) {
    			$referrer_id=$res_referrer['uid'];
    			$where.=" and referrer_id='$referrer_id'";
    		}else {
    			layout(false);
    			$this->error('推荐人不存在！');
    		}
    	}
    	$count=$User->where($where)->count();
    	$per = 30;
    	if($_GET['p']) {
    		$p=$_GET['p'];
    	}else {
    		$p=1;
    	}
    	$Page=new \Common\Model\PageModel();
    	$show= $Page->show($count,$per);// 分页显示输出
    	$this->assign('page',$show);
    	 
    	$list = $User->where($where)->page($p.','.$per)->order('uid desc')->select();
    	$this->assign('list',$list);
        $this->display();
    }
    
    //活跃会员统计
    public function index2()
    {
    	//备注姓名
    	if(trim(I('get.referrer_num'))) {
    		$referrer_num=trim(I('get.referrer_num'));
    	}else {
    		$referrer_num=10;
    	}
    	$sql="select referrer_id,count(*) as referrer_num from __PREFIX__user where referrer_id!='' group by referrer_id having referrer_num>=$referrer_num order by referrer_num asc";
    	$list=M()->query($sql);
    	$num=count($list);
    	$User=new \Common\Model\UserModel();
    	for($i=0;$i<$num;$i++) {
    		$referrerMsg=$User->getUserMsg($list[$i]['referrer_id']);
    		$referrerMsg['referrer_num']=$list[$i]['referrer_num'];
    		$list2[]=$referrerMsg;
    	}
    	$this->assign('list',$list2);
    	$this->display();
    	//dump($list);die();
    	
    }
    
    //新增会员
    public function add()
    {
    	$group_id=I('get.group_id');
    	$this->assign('group_id',$group_id);
    	
    	//获取用户组列表
    	$UserGroup=new \Common\Model\UserGroupModel();
    	$glist=$UserGroup->getGroupList('N');
    	$this->assign('glist',$glist);
    	
    	if(I('post.')) {
    		$User=new \Common\Model\UserModel();
    		//判断用户名是否正确
    		if(I('post.username')) {
    			$username=I('post.username');
    			$res_username=$User->where("username='$username'")->find();
    			if($res_username) {
    				$str='X该用户名已存在';
    				echo $str;
    				exit();
    			}else {
    				$str='';
    				$a='a';
    			}
    		}
    		
    		//判断密码是否正确
    		if(I('post.password') and I('post.password2')) {
    			$password=I('post.password');
    			$password2=I('post.password2');
    			if (strlen($password2)<=5) {
    				$str='X密码不少于6位';
    				echo $str;
    				exit();
    			}else {
    				if($password!=$password2) {
    					$str='X两次密码不相同';
    					echo $str;
    					exit();
    				}else {
    					$str='';
    					$a.='a';
    				}
    			}
    		}
    		
    		//判断邮箱是否正确
    		if(I('post.email')) {
    			$email=I('post.email');
    			if(is_email($email)) {
    				//判断邮箱是否已存在
    				$res_email=$User->where("email='$email'")->find();
    				if($res_email) {
    					$str='X该邮箱已被使用';
    					echo $str;
    					exit();
    				}else {
    					$str='';
    				}
    			}else {
    				$str='X邮箱格式不正确';
    				echo $str;
    				exit();
    			}
    		}
    		
    		//判断手机号码是否正确
    		if(I('post.phone')) {
    			$phone=I('post.phone');
    			if(is_phone($phone)) {
    				//判断手机号是否已存在
    				$res_phone=$User->where("phone='$phone'")->find();
    				if($res_phone) {
    					$str='X该手机号已被使用';
    					echo $str;
    					exit();
    				}else {
    					$str='';
    					//查询手机归属地
    					$result_phone=queryPhoneOwner($phone);
    					$phone_province=$result_phone['data']['province'];
    					$phone_city=$result_phone['data']['city'];
    				}
    			}else {
    				$str='X手机号码格式不正确';
    				echo $str;
    				exit();
    			}
    		}
    		
    		//判断推荐人是否存在
    		if(trim(I('post.referrer_phone'))) {
    			$referrer_phone=trim(I('post.referrer_phone'));
    			$res_referrer=$User->where("username='$referrer_phone' or phone='$referrer_phone' or email='$referrer_phone'")->find();
    			if($res_referrer) {
    				$referrer_id=$res_referrer['uid'];
    			}else {
    				//推荐人不存在
    				$str='X推荐人不存在';
    				echo $str;
    				exit();
    			}
    		}else {
    			$referrer_id=null;
    		}
    		
    		if($a=='aa') {
    			$password2=I('post.password2');
    			//加密
    			$pwd=$User->encrypt($password2);
    			$data=array(
    					'username'=>trim(I('post.username')),
    					'password'=>$pwd,
    					'email'=>trim(I('post.email')),
    					'phone'=>trim(I('post.phone')),
    					'group_id'=>I('post.group_id'),
    					'is_freeze'=>I('post.is_freeze'),
    					'register_time'=>date('Y-m-d H:i:s'),
    					'register_ip'=>getIP(),
    					'last_login_time'=>date('Y-m-d H:i:s'),
    					'referrer_id'=>$referrer_id,
    					'remark'=>trim(I('post.remark')),
    					'phone_province'=>$phone_province,
    					'phone_city'=>$phone_city,
    			);
    			
    			if(!$User->create($data)) {
    				// 验证不通过
    				echo '0';
    			}else {
    				// 验证成功
    				// 开启事务
    				$User->startTrans();
    				$res_add=$User->add($data);
    				if($res_add!==false) {
    				    $uid=$res_add;
    					//修改用户团队路径
    					$path=$User->getPath($uid,array());
    					//绑定邀请码
    					$UserAuthCode=new \Common\Model\UserAuthCodeModel();
    					//查询第一个未使用的邀请码
    					$codeMsg=$UserAuthCode->where("is_used='N'")->order('id asc')->find();
    					$data=array(
    							'path'=>$path,
    							'auth_code'=>$codeMsg['auth_code']
    					);
    					$res_path=$User->where("uid='$uid'")->save($data);
    					//修改邀请码状态
    					$data_code=array(
    							'is_used'=>'Y',
    							'user_id'=>$uid
    					);
    					$code_id=$codeMsg['id'];
    					$res_code=$UserAuthCode->where("id='$code_id'")->save($data_code);
    					if($res_path!==false and $res_code!==false) {
    						//新增用户详情记录
    						$data_d=array(
    								'user_id'=>$uid
    						);
    						$UserDetail=new \Common\Model\UserDetailModel();
    						$res_detail=$UserDetail->add($data_d);
    						//判断注册是否赠送积分
    						$UserPointRecord=new \Common\Model\UserPointRecordModel();
    						if(POINT_REGISTER>0) {
    							//保存积分变动记录
    							$res_point_record=$UserPointRecord->addLog($uid, POINT_REGISTER,POINT_REGISTER, 'register');
    						}else {
    							$res_point_record=true;
    						}
    						if($res_detail!==false and $res_point_record!==false) {
    							//判断是否赠送推荐注册积分
    							if($referrer_id) {
    								//给推荐人增加经验值
    								$res_referrer_exp=$User->where("uid='$referrer_id'")->setInc('exp',USER_UPGRADE_REGISTER);
    								if(POINT_RECOMMEND_REGISTER>0) {
    									//给推荐人赠送积分
    									$res_referrer_point=$User->where("uid='$referrer_id'")->setInc('point',POINT_RECOMMEND_REGISTER);
    									//保存积分变动记录
    									$UserPointRecord=new \Common\Model\UserPointRecordModel();
    									//推荐人积分存量
    									$allpoint=$res_referrer['point']+POINT_RECOMMEND_REGISTER;
    									$res_referrer_point_record=$UserPointRecord->addLog($referrer_id,POINT_RECOMMEND_REGISTER,$allpoint,'recommend_register');
    								}else {
    									$res_referrer_point=true;
    									$res_referrer_point_record=true;
									}
    								if($res_referrer_exp!==false and $res_referrer_point!==false and $res_referrer_point_record!==false)
    								{
    									//判断推荐人是否可以升级为VIP
    									$referrerMsg=$User->getUserMsg($referrer_id);
    									$new_exp=$referrerMsg['exp'];
    									$new_group_id='';
    									if($referrerMsg['group_id']==1 and $new_exp>=20) {
    										//是成长熊，并且经验值达到了20，升级为奋斗熊
    										$new_group_id=2;
    									}
    									if($referrerMsg['group_id']==2 and $new_exp>=200) {
    										//是奋斗熊，并且经验值达到了200，升级为精英熊
    										$new_group_id=3;
    									}
    									if($referrerMsg['group_id']==3 and $new_exp>=500) {
    										//是精英熊，并且经验值达到了500，升级为汇客熊
    										$new_group_id=4;
    									}
    									if($new_group_id) {
    										//会员升级
    										$data_referrer=array(
    												'group_id'=>$new_group_id
    										);
    										$res_referrer_g=$User->where("uid='$referrer_id'")->save($data_referrer);
    									}else {
    										$res_referrer_g=true;
    									}
    									if($res_referrer_g!==false) {
    										//提交
    										$User->commit();
    										echo '1';
    									}else {
    										//注册失败
    										//回滚
    										$User->rollback();
    										echo '0';
    									}
    								}else {
    									//注册失败
    									//回滚
    									$User->rollback();
    									echo '0';
    								}
    							}else {
    								//提交事务
    								$User->commit();
    								echo '1';
    							}
    						}else {
    							//回滚
    							$User->rollback();
    							echo '0';
    						}
    					}else {
    						//回滚
    						$User->rollback();
    						echo '0';
    					}
    				}else {
    					//回滚
    					$User->rollback();
    					echo '0';
    				}
    			}
    		}
    	}else {
    		$this->display();
    	}
    }
    
    //编辑会员
    public function edit($uid)
    {
    	//获取用户组列表
    	$UserGroup=new \Common\Model\UserGroupModel();
    	$glist=$UserGroup->getGroupList('N');
    	$this->assign('glist',$glist);
    	
    	//获取会员信息
    	$User=new \Common\Model\UserModel();
    	$Msg=$User->getUserMsg($uid);
    	$this->assign('msg',$Msg);
    	
    	if(I('post.')) {
    		layout(false);
    		//检查用户是否正确
    		if(I('post.username')) {
    			$username=trim(I('post.username'));
    			$res_username=$User->where("username='$username' and uid!='$uid'")->find();
    			if($res_username) {
    				$this->error('该用户名已存在！');
    			}
    		}
    		
    		//检查邮箱是否正确
    		$email=trim(I('post.email'));
    		if($email) {
    			if(is_email($email)!==true) {
    				$this->error('邮箱格式不正确！');
    			}else {
    				$oldemail=I('post.oldemail');
    				if($email!=$oldemail) {
    					//判断邮箱是否被其他会员使用
    					$res_email=$User->where("email='$email' and uid!=$uid")->find();
    					if($res_email) {
    						$this->error('该邮箱已被使用！');
    					}
    				}
    			}
    		}
    		
    		//检查手机是否正确
    		$phone=trim(I('post.phone'));
    		if($phone) {
    			if(is_phone($phone)!==true) {
    				$this->error('手机号码格式不正确！');
    			}else {
    				$oldphone=I('post.oldphone');
    				if($phone!=$oldphone) {
    					//判断手机号是否被其他会员使用
    					$res_phone=$User->where("phone='$phone' and uid!=$uid")->find();
    					if($res_phone) {
    						$this->error('该手机号已被使用！');
    					}
    				}
    				//查询手机归属地
    				$result_phone=queryPhoneOwner($phone);
    				$phone_province=$result_phone['data']['province'];
    				$phone_city=$result_phone['data']['city'];
    			}
    		}
    		
    		$group_id=I('post.group_id');
    		if(trim(I('post.expiration_date'))!='' and trim(I('post.expiration_date'))!='0000-00-00 00:00:00')
    		{
    			$expiration_date=trim(I('post.expiration_date'));
    		}else {
    			$expiration_date='0000-00-00 00:00:00';
    		}
    		$data=array(
    			'username'=>$username,
    			'phone'=>$phone,
    			'email'=>$email,
    			'balance'=>trim(I('post.balance')),
    			'point'=>trim(I('post.point')),
    			'exp'=>trim(I('post.exp')),
    			'group_id'=>$group_id,
    			'is_freeze'=>I('post.is_freeze'),
    			'remark'=>trim(I('post.remark')),
    			'tb_uid'=>trim(I('post.tb_uid')),
    			'tb_pid'=>trim(I('post.tb_pid')),
    			'tb_pid_master'=>trim(I('post.tb_pid_master')),
    			'is_forever'=>I('post.is_forever'),
    			'is_agent'=>I('post.is_agent'),
    			'expiration_date'=>$expiration_date,
    			'phone_province'=>$phone_province,
    			'phone_city'=>$phone_city,
    		    'is_share_vip'=>trim(I('post.is_share_vip')),
    		    'auth_code'=>trim(I('post.auth_code')),
    		);
    		if(I('post.password')) {
    			$password=trim(I('post.password'));
    			$data['password']=$User->encrypt($password);
    		}
    		//判断推荐人是否存在
    		if(trim(I('post.referrer_phone'))) {
    			$referrer_phone=trim(I('post.referrer_phone'));
    			$res_referrer=$User->where("phone='$referrer_phone'")->find();
    			if($res_referrer) {
    				//判断推荐人必须早于用户注册
    				if($res_referrer['uid']<$uid) {
    					$data['referrer_id']=$res_referrer['uid'];
    				}else {
    					//推荐人晚于用户注册，会造成用户推荐关系的混乱
    					$this->error('推荐关系错误：推荐人必须早于用户注册！','',15);
    				}
    			}else {
    				//推荐人不存在
    				$this->error('推荐人不存在');
    			}
    		}else {
    			$data['referrer_id']='';
    		}
    		if(!$User->create($data)) {
    			// 验证不通过
    			$this->error($User->getError());
    		}else {
    			// 验证成功
    			$res=$User->where("uid=$uid")->save($data);
    			if($res!==false) {
    				$path=$User->getPath($uid,array());
    				//修改用户团队路径
    				$data=array(
    						'path'=>$path
    				);
    				$res_path=$User->where("uid='$uid'")->save($data);
    				if($res_path!==false) {
    					//提交事务
    					$User->commit();
    					$this->success('编辑会员成功！',U('index',array('group_id'=>$group_id)),10);
    				}else {
    					//回滚
    					$User->rollback();
    					$this->error('编辑会员失败！','',10);
    				}
    			}else {
    				$this->error('编辑会员失败！');
    			}
    		}
    	}else {
    		$this->display();
    	}
    }
    
    //彻底删除用户
    public function del($uid)
    {
    	$User=new \Common\Model\UserModel();
    	$res_del=$User->where("uid='$uid'")->delete();
    	$UserDetail=new \Common\Model\UserDetailModel();
    	$res_del2=$UserDetail->where("user_id='$uid'")->delete();
    	//删除用户第三方授权
    	$UserOauth=new \Common\Model\UserOauthModel();
    	$res_del_oauth=$UserOauth->where("user_id='$uid'")->delete();
    	if($res_del!==false and $res_del2!==false and $res_del_oauth!==false) {
    		echo '1';
    	}else {
    		echo '0';
    	}
    }
    
    //修改会员状态
    public function changestatus($uid,$status)
    {
    	$data=array(
    			'is_freeze'=>$status
    	);
    	$User=new \Common\Model\UserModel();
    	if(!$User->create($data)) {
    		// 验证不通过
    		echo '0';
    	}else {
    		$res=$User->where("uid='$uid'")->save($data);
    		if($res!==false) {
    			echo '1';
    		}else {
    			echo '0';
    		}
    	}
    }
    
    //批量导入会员
    public function import()
    {
    	if(I('post.')) {
    		layout(false);
    		if(!empty($_FILES['file']['name'])) {
    			//判断文件格式
    			$type=getFileExt($_FILES ['file'] ['name']);
    			if($type!='.csv') {
    				$this->error('文件格式不正确，必须为CSV文件！');
    			}else {
    				//读取CSV文件
    				$list=readCSV($_FILES ['file'] ['tmp_name']);
    
    				$User=new \Common\Model\UserModel();
    				$UserDetail=new \Common\Model\UserDetailModel();
    				
    				$count=0;
    				foreach ($list as $l) {
    					//会员账号
    					$username=trim($l[0]);
    					//密码
    					if(trim($l[1])) {
    						$pwd=trim($l[1]);
    					}else {
    						$pwd='123456';
    					}
    					//手机号码
    					$phone=trim($l[2]);
    					//邮箱
    					$email=trim($l[3]);
    					//余额
    					$balance=trim($l[4]);
    					//积分
    					$point=trim($l[5]);
    					//判断推荐人是否存在
    					$referrer_phone=trim($l[6]);
    					if($referrer_phone) {
    						$res_referrer=$User->where("username='$referrer_phone' or phone='$referrer_phone' or email='$referrer_phone'")->find();
    						if($res_referrer) {
    							$referrer_id=$res_referrer['uid'];
    						}else {
    							//推荐人不存在
    							$next=$count+1;
    							$err='已成功导入'.$count.'条，第'.$next.'条数据错误原因：推荐人不存在';
    							$this->error($err);
    						}
    					}else {
    						$referrer_id=null;
    					}
    					//判断会员账号是否存在
    					$res_username=$User->checkUsername($username);
    					if($res_username['code']!=0) {
    						$next=$count+1;
    						$err='已成功导入'.$count.'条，第'.$next.'条数据错误原因：'.$res_username['msg'];
    						$this->error($err);
    					}else {
    						//判断手机号码是否存在
    						$res_phone=$User->checkPhone($phone);
    						if($res_phone['code']!=0) {
    							$next=$count+1;
    							$err='已成功导入'.$count.'条，第'.$next.'条数据错误原因：'.$res_phone['msg'];
    							$this->error($err);
    						}else {
    							//判断邮箱是否存在
    							$res_email=$User->where("email='$email'")->find();
    							if($res_email) {
    								$next=$count+1;
    								$err='已成功导入'.$count.'条，第'.$next.'条数据错误原因：邮箱已存在！';
    								$this->error($err);
    							}
    							$data=array(
    									'group_id'=>'1',
    									'username'=>$username,
    									'password'=>$User->encrypt($pwd),
    									'phone'=>$phone,
    									'email'=>$email,
    									'balance'=>$balance,
    									'point'=>$point,
    									'register_time'=>date('Y-m-d H:i:s'),
    									'register_ip'=>getIP(),
    									'referrer_id'=>$referrer_id
    							);
    							if(!$User->create($data)) {
    								// 验证不通过，返回错误信息
    								$next=$count+1;
    								$err='已成功导入'.$count.'条，第'.$next.'条数据错误原因：'.$User->getError();
    								$this->error($err);
    							}else {
    								// 验证通过
    								//开启事务
    								$User->startTrans();
    								$res=$User->add($data);
    								if($res!==false)
    								{
    									$user_id=$res;
    									//修改用户团队路径
    									$path=$User->getPath($user_id,array());
    									$data=array(
    											'path'=>$path
    									);
    									$res_path=$User->where("uid='$user_id'")->save($data);
    									if($res_path!==false)
    									{
    										//保存用户详情
    										//昵称
    										$nickname=trim($l[7]);
    										//真实姓名
    										$truename=trim($l[8]);
    										//性别
    										$sex_str=trim($l[9]);
    										switch ($sex_str)
    										{
    											case '男':
    												$sex='1';
    												break;
    											case '女':
    												$sex='2';
    												break;
    											case '保密':
    												$sex='3';
    												break;
    											default:
    												$sex='3';
    												break;
    										}
    										//身高
    										$height=trim($l[10]);
    										//体重
    										$weight=trim($l[11]);
    										//血型
    										$blood_str=trim($l[12]);
    										switch ($blood_str)
    										{
    											case 'A':
    												$blood='1';
    												break;
    											case 'B':
    												$blood='2';
    												break;
    											case 'AB':
    												$blood='3';
    												break;
    											case 'O':
    												$blood='4';
    												break;
    											case '其他':
    												$blood='5';
    												break;
    											default:
    												$blood='5';
    												break;
    										}
    										//出生日期
    										$birthday=trim($l[13]);
    										//qq
    										$qq=trim($l[14]);
    										//微信
    										$weixin=trim($l[15]);
    										//省
    										$province=trim($l[16]);
    										//市
    										$city=trim($l[17]);
    										//县/区域
    										$county=trim($l[18]);
    										//详细地址
    										$detail_address=trim($l[19]);
    										//个性签名
    										$signature=trim($l[20]);
    										$data_detail=array(
    												'user_id'=>$user_id,
    												'nickname'=>$nickname,
    												'truename'=>$truename,
    												'sex'=>$sex,
    												'height'=>$height,
    												'weight'=>$weight,
    												'blood'=>$blood,
    												'birthday'=>$birthday,
    												'qq'=>$qq,
    												'weixin'=>$weixin,
    												'province'=>$province,
    												'city'=>$city,
    												'county'=>$county,
    												'detail_address'=>$detail_address,
    												'signature'=>$signature,
    										);
    										if(!$UserDetail->create($data_detail))
    										{
    											//验证不通过
    											//回滚
    											$User->rollback();
    											$next=$count+1;
    											$err='已成功导入'.$count.'条，第'.$next.'条数据错误原因：'.$UserDetail->getError();
    											$this->error($err);
    										}else {
    											$res_detail=$UserDetail->add($data_detail);
    											if($res_detail!==false)
    											{
    												//提交事务
    												$User->commit();
    												$count++;
    												continue;
    											}else {
    												//回滚
    												$User->rollback();
    												$next=$count+1;
    												$err='已成功导入'.$count.'条，第'.$next.'条数据错误原因：保存用户详情失败！';
    												$this->error($err);
    											}
    										}
    									}else {
    										//回滚
    										$User->rollback();
    										$next=$count+1;
    										$err='已成功导入'.$count.'条，第'.$next.'条数据错误原因：修改团队路径错误！';
    										$this->error($err);
    									}
    								}else {
    									//回滚
    									$User->rollback();
    									$next=$count+1;
    									$err='已成功导入'.$count.'条，第'.$next.'条数据错误原因：数据库错误';
    									$this->error($err);
    								}
    							}
    						}
    					}
    				}
    				$success_msg='批量导入会员成功，共导入'.$count.'条';
    				$this->success($success_msg,U('index'),20);
    			}
    		}else {
    			$this->error('请选择需要导入的会员列表文件');
    		}
    	}else {
    		$this->display();
    	}
    }
    
    //导出会员列表
    public function export($group_id='')
    {
    	if(I('post.'))
    	{
    		$where='1';
    		if(I('post.group_id'))
    		{
    			$group_id=I('post.group_id');
    			$where.=" and group_id='$group_id'";
    		}
    		//会员名
    		if(trim(I('post.username')))
    		{
    			$username=I('post.username');
    			$where.=" and username='$username'";
    		}
    		//手机号码
    		if(trim(I('post.phone')))
    		{
    			$phone=I('post.phone');
    			$where.=" and phone='$phone'";
    		}
    		//邮箱
    		if(trim(I('post.email')))
    		{
    			$email=I('post.email');
    			$where.=" and email='$email'";
    		}
    		//日期
    		if(I('post.begin_time') and I('post.end_time'))
    		{
    			$begin_time=I('post.begin_time');
    			$end_time=I('post.end_time');
    			$where.=" and date(register_time) BETWEEN '$begin_time' AND '$end_time'";
    		}
    		
    		$user=new \Common\Model\UserModel();
    		$list=$user->where($where)->select();
    		$UserDetail=new \Common\Model\UserDetailModel();
    		
    		import("Org.Util.PHPExcel");
    		import("Org.Util.PHPExcel.Worksheet.Drawing");
    		import("Org.Util.PHPExcel.Writer.Excel2007");
    		$objPHPExcel = new \PHPExcel();
    		 
    		$objWriter = new \PHPExcel_Writer_Excel2007($objPHPExcel);
    		 
    		$objActSheet = $objPHPExcel->getActiveSheet();
    		$objActSheet->setCellValue('A1', 'ID');
    		$objActSheet->setCellValue('B1', '登录账号');
    		$objActSheet->setCellValue('C1', '手机号码');
    		$objActSheet->setCellValue('D1', '电子邮箱');
    		$objActSheet->setCellValue('E1', '余额');
    		$objActSheet->setCellValue('F1', '积分');
    		$objActSheet->setCellValue('G1', '注册时间');
    		$objActSheet->setCellValue('H1', '是否冻结');
    		$objActSheet->setCellValue('I1', '真实姓名');
    		$objActSheet->setCellValue('J1', '性别');
    		$objActSheet->setCellValue('K1', '身高');
    		$objActSheet->setCellValue('L1', '体重');
    		$objActSheet->setCellValue('M1', '血型');
    		$objActSheet->setCellValue('N1', '生日');
    		$objActSheet->setCellValue('O1', 'QQ');
    		$objActSheet->setCellValue('P1', '微信');
    		$objActSheet->setCellValue('Q1', '省');
    		$objActSheet->setCellValue('R1', '市');
    		$objActSheet->setCellValue('S1', '县');
    		$objActSheet->setCellValue('T1', '详细地址');
    		$objActSheet->setCellValue('U1', '个性签名');
    		
    		// 设置个表格宽度
    		$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(6);
    		$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(15);
    		$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(15);
    		$objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(20);
    		$objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(10);
    		$objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(10);
    		$objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(20);
    		$objPHPExcel->getActiveSheet()->getColumnDimension('H')->setWidth(15);
    		$objPHPExcel->getActiveSheet()->getColumnDimension('I')->setWidth(15);
    		$objPHPExcel->getActiveSheet()->getColumnDimension('J')->setWidth(8);
    		$objPHPExcel->getActiveSheet()->getColumnDimension('K')->setWidth(10);
    		$objPHPExcel->getActiveSheet()->getColumnDimension('L')->setWidth(10);
    		$objPHPExcel->getActiveSheet()->getColumnDimension('M')->setWidth(10);
    		$objPHPExcel->getActiveSheet()->getColumnDimension('N')->setWidth(15);
    		$objPHPExcel->getActiveSheet()->getColumnDimension('O')->setWidth(15);
    		$objPHPExcel->getActiveSheet()->getColumnDimension('P')->setWidth(15);
    		$objPHPExcel->getActiveSheet()->getColumnDimension('Q')->setWidth(10);
    		$objPHPExcel->getActiveSheet()->getColumnDimension('R')->setWidth(10);
    		$objPHPExcel->getActiveSheet()->getColumnDimension('S')->setWidth(10);
    		$objPHPExcel->getActiveSheet()->getColumnDimension('T')->setWidth(30);
    		$objPHPExcel->getActiveSheet()->getColumnDimension('U')->setWidth(30);
    		
    		// 水平居中（位置很重要，建议在最初始位置）
    		$objPHPExcel->setActiveSheetIndex(0)->getStyle('A')->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
    		$objPHPExcel->setActiveSheetIndex(0)->getStyle('B')->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
    		$objPHPExcel->setActiveSheetIndex(0)->getStyle('C')->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
    		$objPHPExcel->setActiveSheetIndex(0)->getStyle('D')->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
    		$objPHPExcel->setActiveSheetIndex(0)->getStyle('E')->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
    		$objPHPExcel->setActiveSheetIndex(0)->getStyle('F')->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
    		$objPHPExcel->setActiveSheetIndex(0)->getStyle('G')->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
    		$objPHPExcel->setActiveSheetIndex(0)->getStyle('H')->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
    		$objPHPExcel->setActiveSheetIndex(0)->getStyle('I')->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
    		$objPHPExcel->setActiveSheetIndex(0)->getStyle('J')->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
    		$objPHPExcel->setActiveSheetIndex(0)->getStyle('K')->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
    		$objPHPExcel->setActiveSheetIndex(0)->getStyle('L')->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
    		$objPHPExcel->setActiveSheetIndex(0)->getStyle('M')->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
    		$objPHPExcel->setActiveSheetIndex(0)->getStyle('N')->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
    		$objPHPExcel->setActiveSheetIndex(0)->getStyle('O')->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
    		$objPHPExcel->setActiveSheetIndex(0)->getStyle('P')->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
    		$objPHPExcel->setActiveSheetIndex(0)->getStyle('Q')->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
    		$objPHPExcel->setActiveSheetIndex(0)->getStyle('R')->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
    		$objPHPExcel->setActiveSheetIndex(0)->getStyle('S')->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
    		$objPHPExcel->setActiveSheetIndex(0)->getStyle('T')->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
    		$objPHPExcel->setActiveSheetIndex(0)->getStyle('U')->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
    		
    		$k=1;
    		foreach($list as $l)
    		{
    			$k +=1;
    			
    			$user_id=$l['uid'];
    			$username=$l['username'];
    			$phone=$l['phone'];
    			$email=$l['email'];
    			$balance=$l['balance'];
    			$point=$l['point'];
    			$register_time=$l['register_time'];
    			$is_freeze=$l['is_freeze'];
    			if($is_freeze=='N')
    			{
    				$is_freeze_str='正常使用';
    			}else {
    				$is_freeze_str='已被冻结';
    			}
    			//会员详细信息
    			$detail=$UserDetail->getUserDetailMsg($user_id);
    			$truename=$detail['truename'];
    			switch ($detail['sex'])
    			{
    				case '1':
    					$sex_str='男';
    					break;
    				case '2':
    					$sex_str='女';
    					break;
    				case '3':
    					$sex_str='保密';
    					break;
    			}
    			$height=$detail['height'];
    			$weight=$detail['weight'];
    			switch ($detail['blood'])
    			{
    				case '1':
    					$blood='A型';
    					break;
    				case '2':
    					$blood='B型';
    					break;
    				case '3':
    					$blood='AB型';
    					break;
    				case '4':
    					$blood='O型';
    					break;
    				case '5':
    					$blood='其它';
    					break;
    				default:
    					$blood='其它';
    					break;
    			}
    			$birthday=$detail['birthday'];
    			$qq=$detail['qq'];
    			$weixin=$detail['weixin'];
    			$province=$detail['province'];
    			$city=$detail['city'];
    			$county=$detail['county'];
    			$detail_address=$detail['detail_address'];
    			$signature=$detail['signature'];
    			
    			$objActSheet->setCellValue('A'.$k, $user_id);
    			$objActSheet->setCellValue('B'.$k, $username);
    			$objActSheet->setCellValue('C'.$k, $phone);
    			$objActSheet->setCellValue('D'.$k, $email);
    			$objActSheet->setCellValue('E'.$k, $balance);
    			$objActSheet->setCellValue('F'.$k, $point);
    			$objActSheet->setCellValue('G'.$k, $register_time);
    			$objActSheet->setCellValue('H'.$k, $is_freeze_str);
    			$objActSheet->setCellValue('I'.$k, $truename);
    			$objActSheet->setCellValue('J'.$k, $sex_str);
    			$objActSheet->setCellValue('K'.$k, $height);
    			$objActSheet->setCellValue('L'.$k, $weight);
    			$objActSheet->setCellValue('M'.$k, $blood);
    			$objActSheet->setCellValue('N'.$k, $birthday);
    			$objActSheet->setCellValue('O'.$k, $qq);
    			$objActSheet->setCellValue('P'.$k, $weixin);
    			$objActSheet->setCellValue('Q'.$k, $province);
    			$objActSheet->setCellValue('R'.$k, $city);
    			$objActSheet->setCellValue('S'.$k, $county);
    			$objActSheet->setCellValue('T'.$k, $detail_address);
    			$objActSheet->setCellValue('U'.$k, $signature);
    		}
    		$fileName = '会员列表.xls';
    		$fileName = iconv("utf-8", "gb2312", $fileName);
    		
    		header('Content-Type: application/vnd.ms-excel');
    		header("Content-Disposition: attachment;filename=\"$fileName\"");
    		header('Cache-Control: max-age=0');
    		
    		$objWriter = \PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
    		$objWriter->save('php://output'); //文件通过浏览器下载
    	}else {
    		$this->assign('group_id',$group_id);
    		if($group_id)
    		{
    			$where="group_id='$group_id'";
    		}else {
    			$where='1';
    		}
    		
    		//获取用户组列表
    		$UserGroup=new \Common\Model\UserGroupModel();
    		$glist=$UserGroup->getGroupList('N');
    		$this->assign('glist',$glist);
    		
    		$this->display();
    	}
    }
    
    //获取会员列表
    public function getUserList($group_id)
    {
    	$User=new \Common\Model\UserModel();
    	$list=$User->where("group_id='$group_id' and is_freeze='N'")->select();
    	if($list!==false)
    	{
    		echo json_encode($list);
    	}else {
    		echo '0';
    	}
    }
    
    //每日注册会员统计
    public function everyday()
    {
    	$sql="select count(uid) as all_num,date(register_time) as register_date from __PREFIX__user where 1 group by date(register_time) order by register_time desc limit 0,50";
    	$res=M()->query($sql);
    	$this->assign('list',$res);
    	
    	$this->display();
    }
    
    //修改所有会员手机归属地
    public function changePhone()
    {
    	$User=new \Common\Model\UserModel();
    	$list=$User->select();
    	$count=0;
    	foreach ($list as $l)
    	{
    		$uid=$l['uid'];
    		$phone=$l['phone'];
    		//查询手机归属地
    		$result_phone=queryPhoneOwner($phone);
    		$phone_province=$result_phone['data']['province'];
    		$phone_city=$result_phone['data']['city'];
    		$data=array(
    				'phone_province'=>$phone_province,
    				'phone_city'=>$phone_city,
    		);
    		$res=$User->where("uid='$uid'")->save($data);
    		$count++;
    	}
    	echo $count;
    }
    
    //统计
    public function statistics()
    {
    	//会员总余额
    	$User=new \Common\Model\UserModel();
    	$all_balance=$User->where('1')->sum('balance');
    	$this->assign('all_balance',$all_balance);
    	//推荐注册收益总额
    	$UserBalanceRecord=new \Common\Model\UserBalanceRecordModel();
    	$recommend_amount=$UserBalanceRecord->where("status='2' and action in ('recommend1','recommend2','recommend3')")->sum('money');
    	$recommend_amount/=100;
    	$this->assign('recommend_amount',$recommend_amount);
    	
    	//淘宝返利总收益
    	$tb_amount=$UserBalanceRecord->where("status='2' and action in ('tbk','tbk_r','tbk_r2','tbk_rt')")->sum('money');
    	$tb_amount/=100;
    	$this->assign('tb_amount',$tb_amount);
    	//淘宝本月返利总额
    	$tb_amount_month=$UserBalanceRecord->where("status='2' and action in ('tbk','tbk_r','tbk_r2','tbk_rt') and date_format(pay_time,'%Y-%m')=date_format(now(),'%Y-%m')")->sum('money');
    	$tb_amount_month/=100;
    	$this->assign('tb_amount_month',$tb_amount_month);
    	
    	//京东返利总收益
    	$jd_amount=$UserBalanceRecord->where("status='2' and action in ('jd','jd_r','jd_r2','jd_rt')")->sum('money');
    	$jd_amount/=100;
    	$this->assign('jd_amount',$jd_amount);
    	
    	//拼多多返利总收益
    	$pdd_amount=$UserBalanceRecord->where("status='2' and action in ('pdd','pdd_r','pdd_r2','pdd_rt')")->sum('money');
    	$pdd_amount/=100;
    	$this->assign('pdd_amount',$pdd_amount);

        //唯品会返利总收益
        $vip_amount=$UserBalanceRecord->where("status='2' and action in ('vip','vip_r','vip_r2','vip_rt')")->sum('money');
        $vip_amount/=100;
        $this->assign('vip_amount',$vip_amount);
    	
    	//返利总收益
    	$amount=$tb_amount+$jd_amount+$pdd_amount+$vip_amount;
    	$this->assign('amount',$amount);
    	
    	//用户提现总额
    	$draw_amount=$UserBalanceRecord->where("status='2' and action='draw'")->sum('money');
    	$draw_amount/=100;
    	$this->assign('draw_amount',$draw_amount);
    	
    	//本月返利总额
    	$amount_month=$UserBalanceRecord->where("status='2' and action in ('tbk','tbk_r','tbk_r2','tbk_rt','jd','jd_r','jd_r2','jd_rt','pdd','pdd_r','pdd_r2','pdd_rt','vip','vip_r','vip_r2','vip_rt') and date_format(pay_time,'%Y-%m')=date_format(now(),'%Y-%m')")->sum('money');
    	$amount_month/=100;
    	$this->assign('amount_month',$amount_month);
    	//可提现总额
    	$amount2=$all_balance-$amount_month;
    	$this->assign('amount2',$amount2);
    	
    	//淘宝购物总人数
    	$sql="select count(distinct user_id) as num from __PREFIX__tb_order where user_id!=''";
    	$res_tb=M()->query($sql);
    	$tb_order_num=$res_tb[0]['num'];
    	$this->assign('tb_order_num',$tb_order_num);
    	
    	//淘宝结算总人数
    	$sql2="select count(distinct user_id) as num from __PREFIX__tb_order where user_id!='' and tk_status='3'";
    	$res_tb2=M()->query($sql2);
    	$tb_order_num2=$res_tb2[0]['num'];
    	$this->assign('tb_order_num2',$tb_order_num2);
    	
    	//淘宝官方返利总佣金
    	$TbOrder=new \Common\Model\TbOrderModel();
    	$all_tb_amount=$TbOrder->where("tk_status='3'")->sum('total_commission_fee');
    	$this->assign('all_tb_amount',$all_tb_amount);
    	
    	//淘宝官方本月返利总佣金
    	$all_tb_amount_month=$TbOrder->where("tk_status='3' and date_format(earning_time,'%Y-%m')=date_format(now(),'%Y-%m')")->sum('total_commission_fee');
    	$this->assign('all_tb_amount_month',$all_tb_amount_month);
    	
    	$this->display();
    }
}
?>