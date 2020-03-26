<?php
namespace Home\Controller;
use Think\Controller;
class EmptyController extends Controller {
	function _empty()
	{
		header('HTTP/1.1 404 Not Found');
		header('Status:404 Not Found');
		$this->display ( 'Public:404' );
	}
}