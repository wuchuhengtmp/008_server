<?php


/*
* Copyright (c) 2008-2016 vip.com, All Rights Reserved.
*
* Powered by com.vip.osp.osp-idlc-2.5.11.
*
*/

namespace com\vip\xstore\cc\price;

class PricingReceiptReq {
	
	static $_TSPEC;
	public $reqContext = null;
	public $page = null;
	public $pageSize = null;
	public $startCreateTime = null;
	public $endCreateTime = null;
	
	public function __construct($vals=null){
		
		if (!isset(self::$_TSPEC)){
			
			self::$_TSPEC = array(
			1 => array(
			'var' => 'reqContext'
			),
			2 => array(
			'var' => 'page'
			),
			3 => array(
			'var' => 'pageSize'
			),
			4 => array(
			'var' => 'startCreateTime'
			),
			5 => array(
			'var' => 'endCreateTime'
			),
			
			);
			
		}
		
		if (is_array($vals)){
			
			
			if (isset($vals['reqContext'])){
				
				$this->reqContext = $vals['reqContext'];
			}
			
			
			if (isset($vals['page'])){
				
				$this->page = $vals['page'];
			}
			
			
			if (isset($vals['pageSize'])){
				
				$this->pageSize = $vals['pageSize'];
			}
			
			
			if (isset($vals['startCreateTime'])){
				
				$this->startCreateTime = $vals['startCreateTime'];
			}
			
			
			if (isset($vals['endCreateTime'])){
				
				$this->endCreateTime = $vals['endCreateTime'];
			}
			
			
		}
		
	}
	
	
	public function getName(){
		
		return 'PricingReceiptReq';
	}
	
	public function read($input){
		
		$input->readStructBegin();
		while(true){
			
			$schemeField = $input->readFieldBegin();
			if ($schemeField == null) break;
			$needSkip = true;
			
			
			if ("reqContext" == $schemeField){
				
				$needSkip = false;
				
				$this->reqContext = new \com\vip\xstore\cc\price\ReqContext();
				$this->reqContext->read($input);
				
			}
			
			
			
			
			if ("page" == $schemeField){
				
				$needSkip = false;
				$input->readI32($this->page); 
				
			}
			
			
			
			
			if ("pageSize" == $schemeField){
				
				$needSkip = false;
				$input->readI32($this->pageSize); 
				
			}
			
			
			
			
			if ("startCreateTime" == $schemeField){
				
				$needSkip = false;
				$input->readI64($this->startCreateTime);
				
			}
			
			
			
			
			if ("endCreateTime" == $schemeField){
				
				$needSkip = false;
				$input->readI64($this->endCreateTime);
				
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
		
		$xfer += $output->writeFieldBegin('reqContext');
		
		if (!is_object($this->reqContext)) {
			
			throw new \Osp\Exception\OspException('Bad type in structure.', \Osp\Exception\OspException::INVALID_DATA);
		}
		
		$xfer += $this->reqContext->write($output);
		
		$xfer += $output->writeFieldEnd();
		
		$xfer += $output->writeFieldBegin('page');
		$xfer += $output->writeI32($this->page);
		
		$xfer += $output->writeFieldEnd();
		
		$xfer += $output->writeFieldBegin('pageSize');
		$xfer += $output->writeI32($this->pageSize);
		
		$xfer += $output->writeFieldEnd();
		
		$xfer += $output->writeFieldBegin('startCreateTime');
		$xfer += $output->writeI64($this->startCreateTime);
		
		$xfer += $output->writeFieldEnd();
		
		$xfer += $output->writeFieldBegin('endCreateTime');
		$xfer += $output->writeI64($this->endCreateTime);
		
		$xfer += $output->writeFieldEnd();
		
		$xfer += $output->writeFieldStop();
		$xfer += $output->writeStructEnd();
		return $xfer;
	}
	
}

?>