<?php

namespace Agent\Common\Controller;
use Think\Controller;

//权限认证
class AuthController extends Controller {
	protected function _initialize(){
		//session不存在时，不允许直接访问
		if(!$_SESSION['agent_id']) {
			layout(false);
			$this->error('还没有登录，正在跳转到登录页',U('Index/index'));
		}
	}
}