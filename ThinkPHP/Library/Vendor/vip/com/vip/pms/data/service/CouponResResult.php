<?php


/*
* Copyright (c) 2008-2016 vip.com, All Rights Reserved.
*
* Powered by com.vip.osp.osp-idlc-2.5.11.
*
*/

namespace com\vip\pms\data\service;

class CouponResResult {
	
	static $_TSPEC;
	public $status = null;
	public $errorCode = null;
	public $errorMessage = null;
	
	public function __construct($vals=null){
		
		if (!isset(self::$_TSPEC)){
			
			self::$_TSPEC = array(
			1 => array(
			'var' => 'status'
			),
			2 => array(
			'var' => 'errorCode'
			),
			3 => array(
			'var' => 'errorMessage'
			),
			
			);
			
		}
		
		if (is_array($vals)){
			
			
			if (isset($vals['status'])){
				
				$this->status = $vals['status'];
			}
			
			
			if (isset($vals['errorCode'])){
				
				$this->errorCode = $vals['errorCode'];
			}
			
			
			if (isset($vals['errorMessage'])){
				
				$this->errorMessage = $vals['errorMessage'];
			}
			
			
		}
		
	}
	
	
	public function getName(){
		
		return 'CouponResResult';
	}
	
	public function read($input){
		
		$input->readStructBegin();
		while(true){
			
			$schemeField = $input->readFieldBegin();
			if ($schemeField == null) break;
			$needSkip = true;
			
			
			if ("status" == $schemeField){
				
				$needSkip = false;
				$input->readI32($this->status); 
				
			}
			
			
			
			
			if ("errorCode" == $schemeField){
				
				$needSkip = false;
				$input->readI32($this->errorCode); 
				
			}
			
			
			
			
			if ("errorMessage" == $schemeField){
				
				$needSkip = false;
				$input->readString($this->errorMessage);
				
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
		
		$xfer += $output->writeFieldBegin('status');
		$xfer += $output->writeI32($this->status);
		
		$xfer += $output->writeFieldEnd();
		
		if($this->errorCode !== null) {
			
			$xfer += $output->writeFieldBegin('errorCode');
			$xfer += $output->writeI32($this->errorCode);
			
			$xfer += $output->writeFieldEnd();
		}
		
		
		if($this->errorMessage !== null) {
			
			$xfer += $output->writeFieldBegin('errorMessage');
			$xfer += $output->writeString($this->errorMessage);
			
			$xfer += $output->writeFieldEnd();
		}
		
		
		$xfer += $output->writeFieldStop();
		$xfer += $output->writeStructEnd();
		return $xfer;
	}
	
}

?>