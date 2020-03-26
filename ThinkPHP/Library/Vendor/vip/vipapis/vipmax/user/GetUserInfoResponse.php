<?php


/*
* Copyright (c) 2008-2016 vip.com, All Rights Reserved.
*
* Powered by com.vip.osp.osp-idlc-2.5.11.
*
*/

namespace vipapis\vipmax\user;

class GetUserInfoResponse {
	
	static $_TSPEC;
	public $open_uid = null;
	
	public function __construct($vals=null){
		
		if (!isset(self::$_TSPEC)){
			
			self::$_TSPEC = array(
			1 => array(
			'var' => 'open_uid'
			),
			
			);
			
		}
		
		if (is_array($vals)){
			
			
			if (isset($vals['open_uid'])){
				
				$this->open_uid = $vals['open_uid'];
			}
			
			
		}
		
	}
	
	
	public function getName(){
		
		return 'GetUserInfoResponse';
	}
	
	public function read($input){
		
		$input->readStructBegin();
		while(true){
			
			$schemeField = $input->readFieldBegin();
			if ($schemeField == null) break;
			$needSkip = true;
			
			
			if ("open_uid" == $schemeField){
				
				$needSkip = false;
				$input->readString($this->open_uid);
				
			}
			
			
			
			if($needSkip){
				
				\Osp\Protocol\ProtocolUtil::skip($input);
			}
			
			$input->readFieldEnd();
		}
		
		$input->readStructEnd();
		
		
		
	}
	
	public function write($output){
		
		$xfer = 0;
		$xfer += $output->writeStructBegin();
		
		if($this->open_uid !== null) {
			
			$xfer += $output->writeFieldBegin('open_uid');
			$xfer += $output->writeString($this->open_uid);
			
			$xfer += $output->writeFieldEnd();
		}
		
		
		$xfer += $output->writeFieldStop();
		$xfer += $output->writeStructEnd();
		return $xfer;
	}
	
}

?>