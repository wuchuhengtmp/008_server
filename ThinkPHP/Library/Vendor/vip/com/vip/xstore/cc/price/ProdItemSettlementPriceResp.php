<?php


/*
* Copyright (c) 2008-2016 vip.com, All Rights Reserved.
*
* Powered by com.vip.osp.osp-idlc-2.5.11.
*
*/

namespace com\vip\xstore\cc\price;

class ProdItemSettlementPriceResp {
	
	static $_TSPEC;
	public $settlementPrice = null;
	public $salePrice = null;
	public $deduction = null;
	
	public function __construct($vals=null){
		
		if (!isset(self::$_TSPEC)){
			
			self::$_TSPEC = array(
			1 => array(
			'var' => 'settlementPrice'
			),
			2 => array(
			'var' => 'salePrice'
			),
			3 => array(
			'var' => 'deduction'
			),
			
			);
			
		}
		
		if (is_array($vals)){
			
			
			if (isset($vals['settlementPrice'])){
				
				$this->settlementPrice = $vals['settlementPrice'];
			}
			
			
			if (isset($vals['salePrice'])){
				
				$this->salePrice = $vals['salePrice'];
			}
			
			
			if (isset($vals['deduction'])){
				
				$this->deduction = $vals['deduction'];
			}
			
			
		}
		
	}
	
	
	public function getName(){
		
		return 'ProdItemSettlementPriceResp';
	}
	
	public function read($input){
		
		$input->readStructBegin();
		while(true){
			
			$schemeField = $input->readFieldBegin();
			if ($schemeField == null) break;
			$needSkip = true;
			
			
			if ("settlementPrice" == $schemeField){
				
				$needSkip = false;
				$input->readString($this->settlementPrice);
				
			}
			
			
			
			
			if ("salePrice" == $schemeField){
				
				$needSkip = false;
				$input->readString($this->salePrice);
				
			}
			
			
			
			
			if ("deduction" == $schemeField){
				
				$needSkip = false;
				$input->readString($this->deduction);
				
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
		
		if($this->settlementPrice !== null) {
			
			$xfer += $output->writeFieldBegin('settlementPrice');
			$xfer += $output->writeString($this->settlementPrice);
			
			$xfer += $output->writeFieldEnd();
		}
		
		
		if($this->salePrice !== null) {
			
			$xfer += $output->writeFieldBegin('salePrice');
			$xfer += $output->writeString($this->salePrice);
			
			$xfer += $output->writeFieldEnd();
		}
		
		
		if($this->deduction !== null) {
			
			$xfer += $output->writeFieldBegin('deduction');
			$xfer += $output->writeString($this->deduction);
			
			$xfer += $output->writeFieldEnd();
		}
		
		
		$xfer += $output->writeFieldStop();
		$xfer += $output->writeStructEnd();
		return $xfer;
	}
	
}

?>