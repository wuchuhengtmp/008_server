<?php


/*
* Copyright (c) 2008-2016 vip.com, All Rights Reserved.
*
* Powered by com.vip.osp.osp-idlc-2.5.11.
*
*/

namespace com\vip\sce\vlg\osp\wms\service;

class OrderBatchDetailInfo {
	
	static $_TSPEC;
	public $oderSn = null;
	public $seqNo = null;
	
	public function __construct($vals=null){
		
		if (!isset(self::$_TSPEC)){
			
			self::$_TSPEC = array(
			1 => array(
			'var' => 'oderSn'
			),
			2 => array(
			'var' => 'seqNo'
			),
			
			);
			
		}
		
		if (is_array($vals)){
			
			
			if (isset($vals['oderSn'])){
				
				$this->oderSn = $vals['oderSn'];
			}
			
			
			if (isset($vals['seqNo'])){
				
				$this->seqNo = $vals['seqNo'];
			}
			
			
		}
		
	}
	
	
	public function getName(){
		
		return 'OrderBatchDetailInfo';
	}
	
	public function read($input){
		
		$input->readStructBegin();
		while(true){
			
			$schemeField = $input->readFieldBegin();
			if ($schemeField == null) break;
			$needSkip = true;
			
			
			if ("oderSn" == $schemeField){
				
				$needSkip = false;
				$input->readString($this->oderSn);
				
			}
			
			
			
			
			if ("seqNo" == $schemeField){
				
				$needSkip = false;
				$input->readString($this->seqNo);
				
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
		
		$xfer += $output->writeFieldBegin('oderSn');
		$xfer += $output->writeString($this->oderSn);
		
		$xfer += $output->writeFieldEnd();
		
		$xfer += $output->writeFieldBegin('seqNo');
		$xfer += $output->writeString($this->seqNo);
		
		$xfer += $output->writeFieldEnd();
		
		$xfer += $output->writeFieldStop();
		$xfer += $output->writeStructEnd();
		return $xfer;
	}
	
}

?>