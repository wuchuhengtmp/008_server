<?php


/*
* Copyright (c) 2008-2016 vip.com, All Rights Reserved.
*
* Powered by com.vip.osp.osp-idlc-2.5.11.
*
*/

namespace com\vip\xstore\cc\price;

class StoreProdItemPrice {
	
	static $_TSPEC;
	public $companyCode = null;
	public $storeCode = null;
	public $barcode = null;
	public $marketPrice = null;
	public $salePrice = null;
	public $retailPrice = null;
	public $priceTag = null;
	
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
			'var' => 'marketPrice'
			),
			5 => array(
			'var' => 'salePrice'
			),
			6 => array(
			'var' => 'retailPrice'
			),
			7 => array(
			'var' => 'priceTag'
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
			
			
			if (isset($vals['marketPrice'])){
				
				$this->marketPrice = $vals['marketPrice'];
			}
			
			
			if (isset($vals['salePrice'])){
				
				$this->salePrice = $vals['salePrice'];
			}
			
			
			if (isset($vals['retailPrice'])){
				
				$this->retailPrice = $vals['retailPrice'];
			}
			
			
			if (isset($vals['priceTag'])){
				
				$this->priceTag = $vals['priceTag'];
			}
			
			
		}
		
	}
	
	
	public function getName(){
		
		return 'StoreProdItemPrice';
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
			
			
			
			
			if ("marketPrice" == $schemeField){
				
				$needSkip = false;
				$input->readString($this->marketPrice);
				
			}
			
			
			
			
			if ("salePrice" == $schemeField){
				
				$needSkip = false;
				$input->readString($this->salePrice);
				
			}
			
			
			
			
			if ("retailPrice" == $schemeField){
				
				$needSkip = false;
				$input->readString($this->retailPrice);
				
			}
			
			
			
			
			if ("priceTag" == $schemeField){
				
				$needSkip = false;
				$input->readByte($this->priceTag); 
				
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
		
		
		if($this->retailPrice !== null) {
			
			$xfer += $output->writeFieldBegin('retailPrice');
			$xfer += $output->writeString($this->retailPrice);
			
			$xfer += $output->writeFieldEnd();
		}
		
		
		if($this->priceTag !== null) {
			
			$xfer += $output->writeFieldBegin('priceTag');
			$xfer += $output->writeByte($this->priceTag);
			
			$xfer += $output->writeFieldEnd();
		}
		
		
		$xfer += $output->writeFieldStop();
		$xfer += $output->writeStructEnd();
		return $xfer;
	}
	
}

?>