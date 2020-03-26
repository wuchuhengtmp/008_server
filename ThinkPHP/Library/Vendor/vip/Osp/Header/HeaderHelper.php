<?php

namespace Osp\Header;

/**
 * 头部处理辅助类
 * 
 * @author Jamin
 *        
 */
class HeaderHelper {
	public static $OBJ = null;
	public static function getInstance() {
		if (HeaderHelper::$OBJ == null) {
			HeaderHelper::$OBJ = new HeaderHelper ();
		}
		return HeaderHelper::$OBJ;
	}
	public function write($header, $output) {
		$header->write ( $output );
	}
	public function read($header, $input) {
		$header->read ( $input );
	}
}