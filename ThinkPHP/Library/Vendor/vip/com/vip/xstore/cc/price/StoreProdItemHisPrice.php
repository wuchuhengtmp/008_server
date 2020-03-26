<?php


/*
* Copyright (c) 2008-2016 vip.com, All Rights Reserved.
*
* Powered by com.vip.osp.osp-idlc-2.5.11.
*
*/

namespace com\vip\xstore\cc\price;

class StoreProdItemHisPrice {
	
	static $_TSPEC;
	public $companyCode = null;
	public $storeCode = null;
	public $barcode = null;
	public $retailPrice = null;
	public $retailPriceEffectiveTime = null;
	public $marketPrice = null;
	public $salePrice = null;
	public $salePriceEffectiveTime = null;
	
	public function __construct($vals=null){
		
		if (!isset(self::$_TSPEC)){
			
			self::$_TSPEC = array(
			1 => array(
			'var' => 'companyCode'
			),
			2 => array(
			'var' => 'storeCode'
			),
			3 => array(
			'var' => 'barcode'
			),
			4 => array(
			'var' => 'retailPrice'
			),
			5 => array(
			'var' => 'retailPriceEffectiveTime'
			),
			6 => array(
			'var' => 'marketPrice'
			),
			7 => array(
			'var' => 'salePrice'
			),
			8 => array(
			'var' => 'salePriceEffectiveTime'
			),
			
			);
			
		}
		
		if (is_array($vals)){
			
			
			if (isset($vals['companyCode'])){
				
				$this->companyCode = $vals['companyCode'];
			}
			
			
			if (isset($vals['storeCode'])){
				
				$this->storeCode = $vals['storeCode'];
			}
			
			
			if (isset($vals['barcode'])){
				
				$this->barcode = $vals['barcode'];
			}
			
			
			if (isset($vals['retailPrice'])){
				
				$this->retailPrice = $vals['retailPrice'];
			}
			
			
			if (isset($vals['retailPriceEffectiveTime'])){
				
				$this->retailPriceEffectiveTime = $vals['retailPriceEffectiveTime'];
			}
			
			
			if (isset($vals['marketPrice'])){
				
				$this->marketPrice = $vals['marketPrice'];
			}
			
			
			if (isset($vals['salePrice'])){
				
				$this->salePrice = $vals['salePrice'];
			}
			
			
			if (isset($vals['salePriceEffectiveTime'])){
				
				$this->salePriceEffectiveTime = $vals['salePriceEffectiveTime'];
			}
			
			
		}
		
	}
	
	
	public function getName(){
		
		return 'StoreProdItemHisPrice';
	}
	
	public function read($input){
		
		$input->readStructBegin();
		while(true){
			
			$schemeField = $input->readFieldBegin();
			if ($schemeField == null) break;
			$needSkip = true;
			
			
			if ("companyCode" == $schemeField){
				
				$needSkip = false;
				$input->readString($this->companyCode);
				
			}
			
			
			
			
			if ("storeCode" == $schemeField){
				
				$needSkip = false;
				$input->readString($this->storeCode);
				
			}
			
			
			
			
			if ("barcode" == $schemeField){
				
				$needSkip = false;
				$input->readString($this->barcode);
				
			}
			
			
			
			
			if ("retailPrice" == $schemeField){
				
				$needSkip = false;
				$input->readString($this->retailPrice);
				
			}
			
			
			
			
			if ("retailPriceEffectiveTime" == $schemeField){
				
				$needSkip = false;
				$input->readI64($this->retailPriceEffectiveTime);
				
			}
			
			
			
			
			if ("marketPrice" == $schemeField){
				
				$needSkip = false;
				$input->readString($this->marketPrice);
				
			}
			
			
			
			
			if ("salePrice" == $schemeField){
				
				$needSkip = false;
				$input->readString($this->salePrice);
				
			}
			
			
			
			
			if ("salePriceEffectiveTime" == $schemeField){
				
				$needSkip = false;
				$input->readI64($this->salePriceEffectiveTime);
				
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
		
		if($this->companyCode !== null) {
			
			$xfer += $output->writeFieldBegin('companyCode');
			$xfer += $output->writeString($this->companyCode);
			
			$xfer += $output->writeFieldEnd();
		}
		
		
		if($this->storeCode !== null) {
			
			$xfer += $output->writeFieldBegin('storeCode');
			$xfer += $output->writeString($this->storeCode);
			
			$xfer += $output->writeFieldEnd();
		}
		
		
		if($this->barcode !== null) {
			
			$xfer += $output->writeFieldBegin('barcode');
			$xfer += $output->writeString($this->barcode);
			
			$xfer += $output->writeFieldEnd();
		}
		
		
		if($this->retailPrice !== null) {
			
			$xfer += $output->writeFieldBegin('retailPrice');
			$xfer += $output->writeString($this->retailPrice);
			
			$xfer += $output->writeFieldEnd();
		}
		
		
		if($this->retailPriceEffectiveTime !== null) {
			
			$xfer += $output->writeFieldBegin('retailPriceEffectiveTime');
			$xfer += $output->writeI64($this->retailPriceEffectiveTime);
			
			$xfer += $output->writeFieldEnd();
		}
		
		
		if($this->marketPrice !== null) {
			
			$xfer += $output->writeFieldBegin('marketPrice');
			$xfer += $output->writeString($this->marketPrice);
			
			$xfer += $output->writeFieldEnd();
		}
		
		
		if($this->salePrice !== null) {
			
			$xfer += $output->writeFieldBegin('salePrice');
			$xfer += $output->writeString($this->salePrice);
			
			$xfer += $output->writeFieldEnd();
		}
		
		
		if($this->salePriceEffectiveTime !== null) {
			
			$xfer += $output->writeFieldBegin('salePriceEffectiveTime');
			$xfer += $output->writeI64($this->salePriceEffectiveTime);
			
			$xfer += $output->writeFieldEnd();
		}
		
		
		$xfer += $output->writeFieldStop();
		$xfer += $output->writeStructEnd();
		return $xfer;
	}
	
}

?>