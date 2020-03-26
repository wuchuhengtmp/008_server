<?php


/*
* Copyright (c) 2008-2016 vip.com, All Rights Reserved.
*
* Powered by com.vip.osp.osp-idlc-2.5.11.
*
*/

namespace com\vip\xstore\cc\price;
interface InternalPriceServiceIf{
	
	
	public function batchGetPrice(\com\vip\xstore\cc\price\BatchGetPriceReq $req);
	
	public function batchGetProdItemBaseCostPrice(\com\vip\xstore\cc\price\ReqContext $context, $items);
	
	public function batchGetProdItemHisPrice(\com\vip\xstore\cc\price\ReqContext $context, $companyCode, $storeProdItems, $queryTime);
	
	public function batchGetProdItemNewestPrice(\com\vip\xstore\cc\price\ReqContext $context, $companyCode, $storeProdItems);
	
	public function batchGetProdItemNewestRetailPrice(\com\vip\xstore\cc\price\ReqContext $context, $companyCode, $barcodes);
	
	public function batchGetProdItemNewestSalePrice(\com\vip\xstore\cc\price\ReqContext $context, $companyCode, $storeProdItems);
	
	public function getPrice(\com\vip\xstore\cc\price\GetPriceReq $req);
	
	public function getPricingReceipt(\com\vip\xstore\cc\price\PricingReceiptReq $req);
	
	public function getPricingReceiptItem(\com\vip\xstore\cc\price\PricingReceiptItemReq $req);
	
	public function getProdItemNewestPrice(\com\vip\xstore\cc\price\ReqContext $context, $companyCode, $storeCode, $barcode);
	
	public function getProdItemNewestRetailPrice(\com\vip\xstore\cc\price\ReqContext $context, $companyCode, $barcode);
	
	public function getProdItemNewestSalePrice(\com\vip\xstore\cc\price\ReqContext $context, $companyCode, $storeCode, $barcode);
	
	public function getProdItemRecentNTimesSalePrice(\com\vip\xstore\cc\price\ReqContext $context, $companyCode, $storeCode, $barcode, $nTimes);
	
	public function getProdItemSettlementPrice(\com\vip\xstore\cc\price\ProdItemSettlementPriceReq $req);
	
	public function healthCheck();
	
}

class _InternalPriceServiceClient extends \Osp\Base\OspStub implements \com\vip\xstore\cc\price\InternalPriceServiceIf{
	
	public function __construct(){
		
		parent::__construct("com.vip.xstore.cc.price.InternalPriceService", "1.0.0");
	}
	
	
	public function batchGetPrice(\com\vip\xstore\cc\price\BatchGetPriceReq $req){
		
		$this->send_batchGetPrice( $req);
		return $this->recv_batchGetPrice();
	}
	
	public function send_batchGetPrice(\com\vip\xstore\cc\price\BatchGetPriceReq $req){
		
		$this->initInvocation("batchGetPrice");
		$args = new \com\vip\xstore\cc\price\InternalPriceService_batchGetPrice_args();
		
		$args->req = $req;
		
		$this->send_base($args);
	}
	
	public function recv_batchGetPrice(){
		
		$result = new \com\vip\xstore\cc\price\InternalPriceService_batchGetPrice_result();
		$this->receive_base($result);
		if ($result->success !== null){
			
			return $result->success;
		}
		
	}
	
	
	public function batchGetProdItemBaseCostPrice(\com\vip\xstore\cc\price\ReqContext $context, $items){
		
		$this->send_batchGetProdItemBaseCostPrice( $context, $items);
		return $this->recv_batchGetProdItemBaseCostPrice();
	}
	
	public function send_batchGetProdItemBaseCostPrice(\com\vip\xstore\cc\price\ReqContext $context, $items){
		
		$this->initInvocation("batchGetProdItemBaseCostPrice");
		$args = new \com\vip\xstore\cc\price\InternalPriceService_batchGetProdItemBaseCostPrice_args();
		
		$args->context = $context;
		
		$args->items = $items;
		
		$this->send_base($args);
	}
	
	public function recv_batchGetProdItemBaseCostPrice(){
		
		$result = new \com\vip\xstore\cc\price\InternalPriceService_batchGetProdItemBaseCostPrice_result();
		$this->receive_base($result);
		if ($result->success !== null){
			
			return $result->success;
		}
		
	}
	
	
	public function batchGetProdItemHisPrice(\com\vip\xstore\cc\price\ReqContext $context, $companyCode, $storeProdItems, $queryTime){
		
		$this->send_batchGetProdItemHisPrice( $context, $companyCode, $storeProdItems, $queryTime);
		return $this->recv_batchGetProdItemHisPrice();
	}
	
	public function send_batchGetProdItemHisPrice(\com\vip\xstore\cc\price\ReqContext $context, $companyCode, $storeProdItems, $queryTime){
		
		$this->initInvocation("batchGetProdItemHisPrice");
		$args = new \com\vip\xstore\cc\price\InternalPriceService_batchGetProdItemHisPrice_args();
		
		$args->context = $context;
		
		$args->companyCode = $companyCode;
		
		$args->storeProdItems = $storeProdItems;
		
		$args->queryTime = $queryTime;
		
		$this->send_base($args);
	}
	
	public function recv_batchGetProdItemHisPrice(){
		
		$result = new \com\vip\xstore\cc\price\InternalPriceService_batchGetProdItemHisPrice_result();
		$this->receive_base($result);
		if ($result->success !== null){
			
			return $result->success;
		}
		
	}
	
	
	public function batchGetProdItemNewestPrice(\com\vip\xstore\cc\price\ReqContext $context, $companyCode, $storeProdItems){
		
		$this->send_batchGetProdItemNewestPrice( $context, $companyCode, $storeProdItems);
		return $this->recv_batchGetProdItemNewestPrice();
	}
	
	public function send_batchGetProdItemNewestPrice(\com\vip\xstore\cc\price\ReqContext $context, $companyCode, $storeProdItems){
		
		$this->initInvocation("batchGetProdItemNewestPrice");
		$args = new \com\vip\xstore\cc\price\InternalPriceService_batchGetProdItemNewestPrice_args();
		
		$args->context = $context;
		
		$args->companyCode = $companyCode;
		
		$args->storeProdItems = $storeProdItems;
		
		$this->send_base($args);
	}
	
	public function recv_batchGetProdItemNewestPrice(){
		
		$result = new \com\vip\xstore\cc\price\InternalPriceService_batchGetProdItemNewestPrice_result();
		$this->receive_base($result);
		if ($result->success !== null){
			
			return $result->success;
		}
		
	}
	
	
	public function batchGetProdItemNewestRetailPrice(\com\vip\xstore\cc\price\ReqContext $context, $companyCode, $barcodes){
		
		$this->send_batchGetProdItemNewestRetailPrice( $context, $companyCode, $barcodes);
		return $this->recv_batchGetProdItemNewestRetailPrice();
	}
	
	public function send_batchGetProdItemNewestRetailPrice(\com\vip\xstore\cc\price\ReqContext $context, $companyCode, $barcodes){
		
		$this->initInvocation("batchGetProdItemNewestRetailPrice");
		$args = new \com\vip\xstore\cc\price\InternalPriceService_batchGetProdItemNewestRetailPrice_args();
		
		$args->context = $context;
		
		$args->companyCode = $companyCode;
		
		$args->barcodes = $barcodes;
		
		$this->send_base($args);
	}
	
	public function recv_batchGetProdItemNewestRetailPrice(){
		
		$result = new \com\vip\xstore\cc\price\InternalPriceService_batchGetProdItemNewestRetailPrice_result();
		$this->receive_base($result);
		if ($result->success !== null){
			
			return $result->success;
		}
		
	}
	
	
	public function batchGetProdItemNewestSalePrice(\com\vip\xstore\cc\price\ReqContext $context, $companyCode, $storeProdItems){
		
		$this->send_batchGetProdItemNewestSalePrice( $context, $companyCode, $storeProdItems);
		return $this->recv_batchGetProdItemNewestSalePrice();
	}
	
	public function send_batchGetProdItemNewestSalePrice(\com\vip\xstore\cc\price\ReqContext $context, $companyCode, $storeProdItems){
		
		$this->initInvocation("batchGetProdItemNewestSalePrice");
		$args = new \com\vip\xstore\cc\price\InternalPriceService_batchGetProdItemNewestSalePrice_args();
		
		$args->context = $context;
		
		$args->companyCode = $companyCode;
		
		$args->storeProdItems = $storeProdItems;
		
		$this->send_base($args);
	}
	
	public function recv_batchGetProdItemNewestSalePrice(){
		
		$result = new \com\vip\xstore\cc\price\InternalPriceService_batchGetProdItemNewestSalePrice_result();
		$this->receive_base($result);
		if ($result->success !== null){
			
			return $result->success;
		}
		
	}
	
	
	public function getPrice(\com\vip\xstore\cc\price\GetPriceReq $req){
		
		$this->send_getPrice( $req);
		return $this->recv_getPrice();
	}
	
	public function send_getPrice(\com\vip\xstore\cc\price\GetPriceReq $req){
		
		$this->initInvocation("getPrice");
		$args = new \com\vip\xstore\cc\price\InternalPriceService_getPrice_args();
		
		$args->req = $req;
		
		$this->send_base($args);
	}
	
	public function recv_getPrice(){
		
		$result = new \com\vip\xstore\cc\price\InternalPriceService_getPrice_result();
		$this->receive_base($result);
		if ($result->success !== null){
			
			return $result->success;
		}
		
	}
	
	
	public function getPricingReceipt(\com\vip\xstore\cc\price\PricingReceiptReq $req){
		
		$this->send_getPricingReceipt( $req);
		return $this->recv_getPricingReceipt();
	}
	
	public function send_getPricingReceipt(\com\vip\xstore\cc\price\PricingReceiptReq $req){
		
		$this->initInvocation("getPricingReceipt");
		$args = new \com\vip\xstore\cc\price\InternalPriceService_getPricingReceipt_args();
		
		$args->req = $req;
		
		$this->send_base($args);
	}
	
	public function recv_getPricingReceipt(){
		
		$result = new \com\vip\xstore\cc\price\InternalPriceService_getPricingReceipt_result();
		$this->receive_base($result);
		if ($result->success !== null){
			
			return $result->success;
		}
		
	}
	
	
	public function getPricingReceiptItem(\com\vip\xstore\cc\price\PricingReceiptItemReq $req){
		
		$this->send_getPricingReceiptItem( $req);
		return $this->recv_getPricingReceiptItem();
	}
	
	public function send_getPricingReceiptItem(\com\vip\xstore\cc\price\PricingReceiptItemReq $req){
		
		$this->initInvocation("getPricingReceiptItem");
		$args = new \com\vip\xstore\cc\price\InternalPriceService_getPricingReceiptItem_args();
		
		$args->req = $req;
		
		$this->send_base($args);
	}
	
	public function recv_getPricingReceiptItem(){
		
		$result = new \com\vip\xstore\cc\price\InternalPriceService_getPricingReceiptItem_result();
		$this->receive_base($result);
		if ($result->success !== null){
			
			return $result->success;
		}
		
	}
	
	
	public function getProdItemNewestPrice(\com\vip\xstore\cc\price\ReqContext $context, $companyCode, $storeCode, $barcode){
		
		$this->send_getProdItemNewestPrice( $context, $companyCode, $storeCode, $barcode);
		return $this->recv_getProdItemNewestPrice();
	}
	
	public function send_getProdItemNewestPrice(\com\vip\xstore\cc\price\ReqContext $context, $companyCode, $storeCode, $barcode){
		
		$this->initInvocation("getProdItemNewestPrice");
		$args = new \com\vip\xstore\cc\price\InternalPriceService_getProdItemNewestPrice_args();
		
		$args->context = $context;
		
		$args->companyCode = $companyCode;
		
		$args->storeCode = $storeCode;
		
		$args->barcode = $barcode;
		
		$this->send_base($args);
	}
	
	public function recv_getProdItemNewestPrice(){
		
		$result = new \com\vip\xstore\cc\price\InternalPriceService_getProdItemNewestPrice_result();
		$this->receive_base($result);
		if ($result->success !== null){
			
			return $result->success;
		}
		
	}
	
	
	public function getProdItemNewestRetailPrice(\com\vip\xstore\cc\price\ReqContext $context, $companyCode, $barcode){
		
		$this->send_getProdItemNewestRetailPrice( $context, $companyCode, $barcode);
		return $this->recv_getProdItemNewestRetailPrice();
	}
	
	public function send_getProdItemNewestRetailPrice(\com\vip\xstore\cc\price\ReqContext $context, $companyCode, $barcode){
		
		$this->initInvocation("getProdItemNewestRetailPrice");
		$args = new \com\vip\xstore\cc\price\InternalPriceService_getProdItemNewestRetailPrice_args();
		
		$args->context = $context;
		
		$args->companyCode = $companyCode;
		
		$args->barcode = $barcode;
		
		$this->send_base($args);
	}
	
	public function recv_getProdItemNewestRetailPrice(){
		
		$result = new \com\vip\xstore\cc\price\InternalPriceService_getProdItemNewestRetailPrice_result();
		$this->receive_base($result);
		if ($result->success !== null){
			
			return $result->success;
		}
		
	}
	
	
	public function getProdItemNewestSalePrice(\com\vip\xstore\cc\price\ReqContext $context, $companyCode, $storeCode, $barcode){
		
		$this->send_getProdItemNewestSalePrice( $context, $companyCode, $storeCode, $barcode);
		return $this->recv_getProdItemNewestSalePrice();
	}
	
	public function send_getProdItemNewestSalePrice(\com\vip\xstore\cc\price\ReqContext $context, $companyCode, $storeCode, $barcode){
		
		$this->initInvocation("getProdItemNewestSalePrice");
		$args = new \com\vip\xstore\cc\price\InternalPriceService_getProdItemNewestSalePrice_args();
		
		$args->context = $context;
		
		$args->companyCode = $companyCode;
		
		$args->storeCode = $storeCode;
		
		$args->barcode = $barcode;
		
		$this->send_base($args);
	}
	
	public function recv_getProdItemNewestSalePrice(){
		
		$result = new \com\vip\xstore\cc\price\InternalPriceService_getProdItemNewestSalePrice_result();
		$this->receive_base($result);
		if ($result->success !== null){
			
			return $result->success;
		}
		
	}
	
	
	public function getProdItemRecentNTimesSalePrice(\com\vip\xstore\cc\price\ReqContext $context, $companyCode, $storeCode, $barcode, $nTimes){
		
		$this->send_getProdItemRecentNTimesSalePrice( $context, $companyCode, $storeCode, $barcode, $nTimes);
		return $this->recv_getProdItemRecentNTimesSalePrice();
	}
	
	public function send_getProdItemRecentNTimesSalePrice(\com\vip\xstore\cc\price\ReqContext $context, $companyCode, $storeCode, $barcode, $nTimes){
		
		$this->initInvocation("getProdItemRecentNTimesSalePrice");
		$args = new \com\vip\xstore\cc\price\InternalPriceService_getProdItemRecentNTimesSalePrice_args();
		
		$args->context = $context;
		
		$args->companyCode = $companyCode;
		
		$args->storeCode = $storeCode;
		
		$args->barcode = $barcode;
		
		$args->nTimes = $nTimes;
		
		$this->send_base($args);
	}
	
	public function recv_getProdItemRecentNTimesSalePrice(){
		
		$result = new \com\vip\xstore\cc\price\InternalPriceService_getProdItemRecentNTimesSalePrice_result();
		$this->receive_base($result);
		if ($result->success !== null){
			
			return $result->success;
		}
		
	}
	
	
	public function getProdItemSettlementPrice(\com\vip\xstore\cc\price\ProdItemSettlementPriceReq $req){
		
		$this->send_getProdItemSettlementPrice( $req);
		return $this->recv_getProdItemSettlementPrice();
	}
	
	public function send_getProdItemSettlementPrice(\com\vip\xstore\cc\price\ProdItemSettlementPriceReq $req){
		
		$this->initInvocation("getProdItemSettlementPrice");
		$args = new \com\vip\xstore\cc\price\InternalPriceService_getProdItemSettlementPrice_args();
		
		$args->req = $req;
		
		$this->send_base($args);
	}
	
	public function recv_getProdItemSettlementPrice(){
		
		$result = new \com\vip\xstore\cc\price\InternalPriceService_getProdItemSettlementPrice_result();
		$this->receive_base($result);
		if ($result->success !== null){
			
			return $result->success;
		}
		
	}
	
	
	public function healthCheck(){
		
		$this->send_healthCheck();
		return $this->recv_healthCheck();
	}
	
	public function send_healthCheck(){
		
		$this->initInvocation("healthCheck");
		$args = new \com\vip\xstore\cc\price\InternalPriceService_healthCheck_args();
		
		$this->send_base($args);
	}
	
	public function recv_healthCheck(){
		
		$result = new \com\vip\xstore\cc\price\InternalPriceService_healthCheck_result();
		$this->receive_base($result);
		if ($result->success !== null){
			
			return $result->success;
		}
		
	}
	
	
}




class InternalPriceService_batchGetPrice_args {
	
	static $_TSPEC;
	public $req = null;
	
	public function __construct($vals=null){
		
		if (!isset(self::$_TSPEC)){
			
			self::$_TSPEC = array(
			1 => array(
			'var' => 'req'
			),
			
			);
			
		}
		
		if (is_array($vals)){
			
			
			if (isset($vals['req'])){
				
				$this->req = $vals['req'];
			}
			
			
		}
		
	}
	
	
	public function read($input){
		
		
		
		
		if(true) {
			
			
			$this->req = new \com\vip\xstore\cc\price\BatchGetPriceReq();
			$this->req->read($input);
			
		}
		
		
		
		
		
		
	}
	
	public function write($output){
		
		$xfer = 0;
		$xfer += $output->writeStructBegin();
		
		$xfer += $output->writeFieldBegin('req');
		
		if (!is_object($this->req)) {
			
			throw new \Osp\Exception\OspException('Bad type in structure.', \Osp\Exception\OspException::INVALID_DATA);
		}
		
		$xfer += $this->req->write($output);
		
		$xfer += $output->writeFieldEnd();
		
		$xfer += $output->writeFieldStop();
		$xfer += $output->writeStructEnd();
		return $xfer;
	}
	
}




class InternalPriceService_batchGetProdItemBaseCostPrice_args {
	
	static $_TSPEC;
	public $context = null;
	public $items = null;
	
	public function __construct($vals=null){
		
		if (!isset(self::$_TSPEC)){
			
			self::$_TSPEC = array(
			1 => array(
			'var' => 'context'
			),
			2 => array(
			'var' => 'items'
			),
			
			);
			
		}
		
		if (is_array($vals)){
			
			
			if (isset($vals['context'])){
				
				$this->context = $vals['context'];
			}
			
			
			if (isset($vals['items'])){
				
				$this->items = $vals['items'];
			}
			
			
		}
		
	}
	
	
	public function read($input){
		
		
		
		
		if(true) {
			
			
			$this->context = new \com\vip\xstore\cc\price\ReqContext();
			$this->context->read($input);
			
		}
		
		
		
		
		if(true) {
			
			
			$this->items = array();
			$_size0 = 0;
			$input->readListBegin();
			while(true){
				
				try{
					
					$elem0 = null;
					
					$elem0 = new \com\vip\xstore\cc\price\ProdItemId();
					$elem0->read($input);
					
					$this->items[$_size0++] = $elem0;
				}
				catch(\Exception $e){
					
					break;
				}
			}
			
			$input->readListEnd();
			
		}
		
		
		
		
		
		
	}
	
	public function write($output){
		
		$xfer = 0;
		$xfer += $output->writeStructBegin();
		
		$xfer += $output->writeFieldBegin('context');
		
		if (!is_object($this->context)) {
			
			throw new \Osp\Exception\OspException('Bad type in structure.', \Osp\Exception\OspException::INVALID_DATA);
		}
		
		$xfer += $this->context->write($output);
		
		$xfer += $output->writeFieldEnd();
		
		if($this->items !== null) {
			
			$xfer += $output->writeFieldBegin('items');
			
			if (!is_array($this->items)){
				
				throw new \Osp\Exception\OspException('Bad type in structure.', \Osp\Exception\OspException::INVALID_DATA);
			}
			
			$output->writeListBegin();
			foreach ($this->items as $iter0){
				
				
				if (!is_object($iter0)) {
					
					throw new \Osp\Exception\OspException('Bad type in structure.', \Osp\Exception\OspException::INVALID_DATA);
				}
				
				$xfer += $iter0->write($output);
				
			}
			
			$output->writeListEnd();
			
			$xfer += $output->writeFieldEnd();
		}
		
		
		$xfer += $output->writeFieldStop();
		$xfer += $output->writeStructEnd();
		return $xfer;
	}
	
}




class InternalPriceService_batchGetProdItemHisPrice_args {
	
	static $_TSPEC;
	public $context = null;
	public $companyCode = null;
	public $storeProdItems = null;
	public $queryTime = null;
	
	public function __construct($vals=null){
		
		if (!isset(self::$_TSPEC)){
			
			self::$_TSPEC = array(
			1 => array(
			'var' => 'context'
			),
			2 => array(
			'var' => 'companyCode'
			),
			3 => array(
			'var' => 'storeProdItems'
			),
			4 => array(
			'var' => 'queryTime'
			),
			
			);
			
		}
		
		if (is_array($vals)){
			
			
			if (isset($vals['context'])){
				
				$this->context = $vals['context'];
			}
			
			
			if (isset($vals['companyCode'])){
				
				$this->companyCode = $vals['companyCode'];
			}
			
			
			if (isset($vals['storeProdItems'])){
				
				$this->storeProdItems = $vals['storeProdItems'];
			}
			
			
			if (isset($vals['queryTime'])){
				
				$this->queryTime = $vals['queryTime'];
			}
			
			
		}
		
	}
	
	
	public function read($input){
		
		
		
		
		if(true) {
			
			
			$this->context = new \com\vip\xstore\cc\price\ReqContext();
			$this->context->read($input);
			
		}
		
		
		
		
		if(true) {
			
			$input->readString($this->companyCode);
			
		}
		
		
		
		
		if(true) {
			
			
			$this->storeProdItems = array();
			$_size1 = 0;
			$input->readListBegin();
			while(true){
				
				try{
					
					$elem1 = null;
					
					$elem1 = new \com\vip\xstore\cc\price\StoreProdItemIdentity();
					$elem1->read($input);
					
					$this->storeProdItems[$_size1++] = $elem1;
				}
				catch(\Exception $e){
					
					break;
				}
			}
			
			$input->readListEnd();
			
		}
		
		
		
		
		if(true) {
			
			$input->readI64($this->queryTime);
			
		}
		
		
		
		
		
		
	}
	
	public function write($output){
		
		$xfer = 0;
		$xfer += $output->writeStructBegin();
		
		$xfer += $output->writeFieldBegin('context');
		
		if (!is_object($this->context)) {
			
			throw new \Osp\Exception\OspException('Bad type in structure.', \Osp\Exception\OspException::INVALID_DATA);
		}
		
		$xfer += $this->context->write($output);
		
		$xfer += $output->writeFieldEnd();
		
		if($this->companyCode !== null) {
			
			$xfer += $output->writeFieldBegin('companyCode');
			$xfer += $output->writeString($this->companyCode);
			
			$xfer += $output->writeFieldEnd();
		}
		
		
		$xfer += $output->writeFieldBegin('storeProdItems');
		
		if (!is_array($this->storeProdItems)){
			
			throw new \Osp\Exception\OspException('Bad type in structure.', \Osp\Exception\OspException::INVALID_DATA);
		}
		
		$output->writeListBegin();
		foreach ($this->storeProdItems as $iter0){
			
			
			if (!is_object($iter0)) {
				
				throw new \Osp\Exception\OspException('Bad type in structure.', \Osp\Exception\OspException::INVALID_DATA);
			}
			
			$xfer += $iter0->write($output);
			
		}
		
		$output->writeListEnd();
		
		$xfer += $output->writeFieldEnd();
		
		if($this->queryTime !== null) {
			
			$xfer += $output->writeFieldBegin('queryTime');
			$xfer += $output->writeI64($this->queryTime);
			
			$xfer += $output->writeFieldEnd();
		}
		
		
		$xfer += $output->writeFieldStop();
		$xfer += $output->writeStructEnd();
		return $xfer;
	}
	
}




class InternalPriceService_batchGetProdItemNewestPrice_args {
	
	static $_TSPEC;
	public $context = null;
	public $companyCode = null;
	public $storeProdItems = null;
	
	public function __construct($vals=null){
		
		if (!isset(self::$_TSPEC)){
			
			self::$_TSPEC = array(
			1 => array(
			'var' => 'context'
			),
			2 => array(
			'var' => 'companyCode'
			),
			3 => array(
			'var' => 'storeProdItems'
			),
			
			);
			
		}
		
		if (is_array($vals)){
			
			
			if (isset($vals['context'])){
				
				$this->context = $vals['context'];
			}
			
			
			if (isset($vals['companyCode'])){
				
				$this->companyCode = $vals['companyCode'];
			}
			
			
			if (isset($vals['storeProdItems'])){
				
				$this->storeProdItems = $vals['storeProdItems'];
			}
			
			
		}
		
	}
	
	
	public function read($input){
		
		
		
		
		if(true) {
			
			
			$this->context = new \com\vip\xstore\cc\price\ReqContext();
			$this->context->read($input);
			
		}
		
		
		
		
		if(true) {
			
			$input->readString($this->companyCode);
			
		}
		
		
		
		
		if(true) {
			
			
			$this->storeProdItems = array();
			$_size0 = 0;
			$input->readListBegin();
			while(true){
				
				try{
					
					$elem0 = null;
					
					$elem0 = new \com\vip\xstore\cc\price\StoreProdItemIdentity();
					$elem0->read($input);
					
					$this->storeProdItems[$_size0++] = $elem0;
				}
				catch(\Exception $e){
					
					break;
				}
			}
			
			$input->readListEnd();
			
		}
		
		
		
		
		
		
	}
	
	public function write($output){
		
		$xfer = 0;
		$xfer += $output->writeStructBegin();
		
		$xfer += $output->writeFieldBegin('context');
		
		if (!is_object($this->context)) {
			
			throw new \Osp\Exception\OspException('Bad type in structure.', \Osp\Exception\OspException::INVALID_DATA);
		}
		
		$xfer += $this->context->write($output);
		
		$xfer += $output->writeFieldEnd();
		
		if($this->companyCode !== null) {
			
			$xfer += $output->writeFieldBegin('companyCode');
			$xfer += $output->writeString($this->companyCode);
			
			$xfer += $output->writeFieldEnd();
		}
		
		
		$xfer += $output->writeFieldBegin('storeProdItems');
		
		if (!is_array($this->storeProdItems)){
			
			throw new \Osp\Exception\OspException('Bad type in structure.', \Osp\Exception\OspException::INVALID_DATA);
		}
		
		$output->writeListBegin();
		foreach ($this->storeProdItems as $iter0){
			
			
			if (!is_object($iter0)) {
				
				throw new \Osp\Exception\OspException('Bad type in structure.', \Osp\Exception\OspException::INVALID_DATA);
			}
			
			$xfer += $iter0->write($output);
			
		}
		
		$output->writeListEnd();
		
		$xfer += $output->writeFieldEnd();
		
		$xfer += $output->writeFieldStop();
		$xfer += $output->writeStructEnd();
		return $xfer;
	}
	
}




class InternalPriceService_batchGetProdItemNewestRetailPrice_args {
	
	static $_TSPEC;
	public $context = null;
	public $companyCode = null;
	public $barcodes = null;
	
	public function __construct($vals=null){
		
		if (!isset(self::$_TSPEC)){
			
			self::$_TSPEC = array(
			1 => array(
			'var' => 'context'
			),
			2 => array(
			'var' => 'companyCode'
			),
			3 => array(
			'var' => 'barcodes'
			),
			
			);
			
		}
		
		if (is_array($vals)){
			
			
			if (isset($vals['context'])){
				
				$this->context = $vals['context'];
			}
			
			
			if (isset($vals['companyCode'])){
				
				$this->companyCode = $vals['companyCode'];
			}
			
			
			if (isset($vals['barcodes'])){
				
				$this->barcodes = $vals['barcodes'];
			}
			
			
		}
		
	}
	
	
	public function read($input){
		
		
		
		
		if(true) {
			
			
			$this->context = new \com\vip\xstore\cc\price\ReqContext();
			$this->context->read($input);
			
		}
		
		
		
		
		if(true) {
			
			$input->readString($this->companyCode);
			
		}
		
		
		
		
		if(true) {
			
			
			$this->barcodes = array();
			$_size1 = 0;
			$input->readListBegin();
			while(true){
				
				try{
					
					$elem1 = null;
					$input->readString($elem1);
					
					$this->barcodes[$_size1++] = $elem1;
				}
				catch(\Exception $e){
					
					break;
				}
			}
			
			$input->readListEnd();
			
		}
		
		
		
		
		
		
	}
	
	public function write($output){
		
		$xfer = 0;
		$xfer += $output->writeStructBegin();
		
		$xfer += $output->writeFieldBegin('context');
		
		if (!is_object($this->context)) {
			
			throw new \Osp\Exception\OspException('Bad type in structure.', \Osp\Exception\OspException::INVALID_DATA);
		}
		
		$xfer += $this->context->write($output);
		
		$xfer += $output->writeFieldEnd();
		
		if($this->companyCode !== null) {
			
			$xfer += $output->writeFieldBegin('companyCode');
			$xfer += $output->writeString($this->companyCode);
			
			$xfer += $output->writeFieldEnd();
		}
		
		
		if($this->barcodes !== null) {
			
			$xfer += $output->writeFieldBegin('barcodes');
			
			if (!is_array($this->barcodes)){
				
				throw new \Osp\Exception\OspException('Bad type in structure.', \Osp\Exception\OspException::INVALID_DATA);
			}
			
			$output->writeListBegin();
			foreach ($this->barcodes as $iter0){
				
				$xfer += $output->writeString($iter0);
				
			}
			
			$output->writeListEnd();
			
			$xfer += $output->writeFieldEnd();
		}
		
		
		$xfer += $output->writeFieldStop();
		$xfer += $output->writeStructEnd();
		return $xfer;
	}
	
}




class InternalPriceService_batchGetProdItemNewestSalePrice_args {
	
	static $_TSPEC;
	public $context = null;
	public $companyCode = null;
	public $storeProdItems = null;
	
	public function __construct($vals=null){
		
		if (!isset(self::$_TSPEC)){
			
			self::$_TSPEC = array(
			1 => array(
			'var' => 'context'
			),
			2 => array(
			'var' => 'companyCode'
			),
			3 => array(
			'var' => 'storeProdItems'
			),
			
			);
			
		}
		
		if (is_array($vals)){
			
			
			if (isset($vals['context'])){
				
				$this->context = $vals['context'];
			}
			
			
			if (isset($vals['companyCode'])){
				
				$this->companyCode = $vals['companyCode'];
			}
			
			
			if (isset($vals['storeProdItems'])){
				
				$this->storeProdItems = $vals['storeProdItems'];
			}
			
			
		}
		
	}
	
	
	public function read($input){
		
		
		
		
		if(true) {
			
			
			$this->context = new \com\vip\xstore\cc\price\ReqContext();
			$this->context->read($input);
			
		}
		
		
		
		
		if(true) {
			
			$input->readString($this->companyCode);
			
		}
		
		
		
		
		if(true) {
			
			
			$this->storeProdItems = array();
			$_size1 = 0;
			$input->readListBegin();
			while(true){
				
				try{
					
					$elem1 = null;
					
					$elem1 = new \com\vip\xstore\cc\price\StoreProdItemIdentity();
					$elem1->read($input);
					
					$this->storeProdItems[$_size1++] = $elem1;
				}
				catch(\Exception $e){
					
					break;
				}
			}
			
			$input->readListEnd();
			
		}
		
		
		
		
		
		
	}
	
	public function write($output){
		
		$xfer = 0;
		$xfer += $output->writeStructBegin();
		
		$xfer += $output->writeFieldBegin('context');
		
		if (!is_object($this->context)) {
			
			throw new \Osp\Exception\OspException('Bad type in structure.', \Osp\Exception\OspException::INVALID_DATA);
		}
		
		$xfer += $this->context->write($output);
		
		$xfer += $output->writeFieldEnd();
		
		if($this->companyCode !== null) {
			
			$xfer += $output->writeFieldBegin('companyCode');
			$xfer += $output->writeString($this->companyCode);
			
			$xfer += $output->writeFieldEnd();
		}
		
		
		$xfer += $output->writeFieldBegin('storeProdItems');
		
		if (!is_array($this->storeProdItems)){
			
			throw new \Osp\Exception\OspException('Bad type in structure.', \Osp\Exception\OspException::INVALID_DATA);
		}
		
		$output->writeListBegin();
		foreach ($this->storeProdItems as $iter0){
			
			
			if (!is_object($iter0)) {
				
				throw new \Osp\Exception\OspException('Bad type in structure.', \Osp\Exception\OspException::INVALID_DATA);
			}
			
			$xfer += $iter0->write($output);
			
		}
		
		$output->writeListEnd();
		
		$xfer += $output->writeFieldEnd();
		
		$xfer += $output->writeFieldStop();
		$xfer += $output->writeStructEnd();
		return $xfer;
	}
	
}




class InternalPriceService_getPrice_args {
	
	static $_TSPEC;
	public $req = null;
	
	public function __construct($vals=null){
		
		if (!isset(self::$_TSPEC)){
			
			self::$_TSPEC = array(
			1 => array(
			'var' => 'req'
			),
			
			);
			
		}
		
		if (is_array($vals)){
			
			
			if (isset($vals['req'])){
				
				$this->req = $vals['req'];
			}
			
			
		}
		
	}
	
	
	public function read($input){
		
		
		
		
		if(true) {
			
			
			$this->req = new \com\vip\xstore\cc\price\GetPriceReq();
			$this->req->read($input);
			
		}
		
		
		
		
		
		
	}
	
	public function write($output){
		
		$xfer = 0;
		$xfer += $output->writeStructBegin();
		
		$xfer += $output->writeFieldBegin('req');
		
		if (!is_object($this->req)) {
			
			throw new \Osp\Exception\OspException('Bad type in structure.', \Osp\Exception\OspException::INVALID_DATA);
		}
		
		$xfer += $this->req->write($output);
		
		$xfer += $output->writeFieldEnd();
		
		$xfer += $output->writeFieldStop();
		$xfer += $output->writeStructEnd();
		return $xfer;
	}
	
}




class InternalPriceService_getPricingReceipt_args {
	
	static $_TSPEC;
	public $req = null;
	
	public function __construct($vals=null){
		
		if (!isset(self::$_TSPEC)){
			
			self::$_TSPEC = array(
			1 => array(
			'var' => 'req'
			),
			
			);
			
		}
		
		if (is_array($vals)){
			
			
			if (isset($vals['req'])){
				
				$this->req = $vals['req'];
			}
			
			
		}
		
	}
	
	
	public function read($input){
		
		
		
		
		if(true) {
			
			
			$this->req = new \com\vip\xstore\cc\price\PricingReceiptReq();
			$this->req->read($input);
			
		}
		
		
		
		
		
		
	}
	
	public function write($output){
		
		$xfer = 0;
		$xfer += $output->writeStructBegin();
		
		$xfer += $output->writeFieldBegin('req');
		
		if (!is_object($this->req)) {
			
			throw new \Osp\Exception\OspException('Bad type in structure.', \Osp\Exception\OspException::INVALID_DATA);
		}
		
		$xfer += $this->req->write($output);
		
		$xfer += $output->writeFieldEnd();
		
		$xfer += $output->writeFieldStop();
		$xfer += $output->writeStructEnd();
		return $xfer;
	}
	
}




class InternalPriceService_getPricingReceiptItem_args {
	
	static $_TSPEC;
	public $req = null;
	
	public function __construct($vals=null){
		
		if (!isset(self::$_TSPEC)){
			
			self::$_TSPEC = array(
			1 => array(
			'var' => 'req'
			),
			
			);
			
		}
		
		if (is_array($vals)){
			
			
			if (isset($vals['req'])){
				
				$this->req = $vals['req'];
			}
			
			
		}
		
	}
	
	
	public function read($input){
		
		
		
		
		if(true) {
			
			
			$this->req = new \com\vip\xstore\cc\price\PricingReceiptItemReq();
			$this->req->read($input);
			
		}
		
		
		
		
		
		
	}
	
	public function write($output){
		
		$xfer = 0;
		$xfer += $output->writeStructBegin();
		
		$xfer += $output->writeFieldBegin('req');
		
		if (!is_object($this->req)) {
			
			throw new \Osp\Exception\OspException('Bad type in structure.', \Osp\Exception\OspException::INVALID_DATA);
		}
		
		$xfer += $this->req->write($output);
		
		$xfer += $output->writeFieldEnd();
		
		$xfer += $output->writeFieldStop();
		$xfer += $output->writeStructEnd();
		return $xfer;
	}
	
}




class InternalPriceService_getProdItemNewestPrice_args {
	
	static $_TSPEC;
	public $context = null;
	public $companyCode = null;
	public $storeCode = null;
	public $barcode = null;
	
	public function __construct($vals=null){
		
		if (!isset(self::$_TSPEC)){
			
			self::$_TSPEC = array(
			1 => array(
			'var' => 'context'
			),
			2 => array(
			'var' => 'companyCode'
			),
			3 => array(
			'var' => 'storeCode'
			),
			4 => array(
			'var' => 'barcode'
			),
			
			);
			
		}
		
		if (is_array($vals)){
			
			
			if (isset($vals['context'])){
				
				$this->context = $vals['context'];
			}
			
			
			if (isset($vals['companyCode'])){
				
				$this->companyCode = $vals['companyCode'];
			}
			
			
			if (isset($vals['storeCode'])){
				
				$this->storeCode = $vals['storeCode'];
			}
			
			
			if (isset($vals['barcode'])){
				
				$this->barcode = $vals['barcode'];
			}
			
			
		}
		
	}
	
	
	public function read($input){
		
		
		
		
		if(true) {
			
			
			$this->context = new \com\vip\xstore\cc\price\ReqContext();
			$this->context->read($input);
			
		}
		
		
		
		
		if(true) {
			
			$input->readString($this->companyCode);
			
		}
		
		
		
		
		if(true) {
			
			$input->readString($this->storeCode);
			
		}
		
		
		
		
		if(true) {
			
			$input->readString($this->barcode);
			
		}
		
		
		
		
		
		
	}
	
	public function write($output){
		
		$xfer = 0;
		$xfer += $output->writeStructBegin();
		
		$xfer += $output->writeFieldBegin('context');
		
		if (!is_object($this->context)) {
			
			throw new \Osp\Exception\OspException('Bad type in structure.', \Osp\Exception\OspException::INVALID_DATA);
		}
		
		$xfer += $this->context->write($output);
		
		$xfer += $output->writeFieldEnd();
		
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
		
		
		$xfer += $output->writeFieldStop();
		$xfer += $output->writeStructEnd();
		return $xfer;
	}
	
}




class InternalPriceService_getProdItemNewestRetailPrice_args {
	
	static $_TSPEC;
	public $context = null;
	public $companyCode = null;
	public $barcode = null;
	
	public function __construct($vals=null){
		
		if (!isset(self::$_TSPEC)){
			
			self::$_TSPEC = array(
			1 => array(
			'var' => 'context'
			),
			2 => array(
			'var' => 'companyCode'
			),
			3 => array(
			'var' => 'barcode'
			),
			
			);
			
		}
		
		if (is_array($vals)){
			
			
			if (isset($vals['context'])){
				
				$this->context = $vals['context'];
			}
			
			
			if (isset($vals['companyCode'])){
				
				$this->companyCode = $vals['companyCode'];
			}
			
			
			if (isset($vals['barcode'])){
				
				$this->barcode = $vals['barcode'];
			}
			
			
		}
		
	}
	
	
	public function read($input){
		
		
		
		
		if(true) {
			
			
			$this->context = new \com\vip\xstore\cc\price\ReqContext();
			$this->context->read($input);
			
		}
		
		
		
		
		if(true) {
			
			$input->readString($this->companyCode);
			
		}
		
		
		
		
		if(true) {
			
			$input->readString($this->barcode);
			
		}
		
		
		
		
		
		
	}
	
	public function write($output){
		
		$xfer = 0;
		$xfer += $output->writeStructBegin();
		
		$xfer += $output->writeFieldBegin('context');
		
		if (!is_object($this->context)) {
			
			throw new \Osp\Exception\OspException('Bad type in structure.', \Osp\Exception\OspException::INVALID_DATA);
		}
		
		$xfer += $this->context->write($output);
		
		$xfer += $output->writeFieldEnd();
		
		if($this->companyCode !== null) {
			
			$xfer += $output->writeFieldBegin('companyCode');
			$xfer += $output->writeString($this->companyCode);
			
			$xfer += $output->writeFieldEnd();
		}
		
		
		if($this->barcode !== null) {
			
			$xfer += $output->writeFieldBegin('barcode');
			$xfer += $output->writeString($this->barcode);
			
			$xfer += $output->writeFieldEnd();
		}
		
		
		$xfer += $output->writeFieldStop();
		$xfer += $output->writeStructEnd();
		return $xfer;
	}
	
}




class InternalPriceService_getProdItemNewestSalePrice_args {
	
	static $_TSPEC;
	public $context = null;
	public $companyCode = null;
	public $storeCode = null;
	public $barcode = null;
	
	public function __construct($vals=null){
		
		if (!isset(self::$_TSPEC)){
			
			self::$_TSPEC = array(
			1 => array(
			'var' => 'context'
			),
			2 => array(
			'var' => 'companyCode'
			),
			3 => array(
			'var' => 'storeCode'
			),
			4 => array(
			'var' => 'barcode'
			),
			
			);
			
		}
		
		if (is_array($vals)){
			
			
			if (isset($vals['context'])){
				
				$this->context = $vals['context'];
			}
			
			
			if (isset($vals['companyCode'])){
				
				$this->companyCode = $vals['companyCode'];
			}
			
			
			if (isset($vals['storeCode'])){
				
				$this->storeCode = $vals['storeCode'];
			}
			
			
			if (isset($vals['barcode'])){
				
				$this->barcode = $vals['barcode'];
			}
			
			
		}
		
	}
	
	
	public function read($input){
		
		
		
		
		if(true) {
			
			
			$this->context = new \com\vip\xstore\cc\price\ReqContext();
			$this->context->read($input);
			
		}
		
		
		
		
		if(true) {
			
			$input->readString($this->companyCode);
			
		}
		
		
		
		
		if(true) {
			
			$input->readString($this->storeCode);
			
		}
		
		
		
		
		if(true) {
			
			$input->readString($this->barcode);
			
		}
		
		
		
		
		
		
	}
	
	public function write($output){
		
		$xfer = 0;
		$xfer += $output->writeStructBegin();
		
		$xfer += $output->writeFieldBegin('context');
		
		if (!is_object($this->context)) {
			
			throw new \Osp\Exception\OspException('Bad type in structure.', \Osp\Exception\OspException::INVALID_DATA);
		}
		
		$xfer += $this->context->write($output);
		
		$xfer += $output->writeFieldEnd();
		
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
		
		
		$xfer += $output->writeFieldStop();
		$xfer += $output->writeStructEnd();
		return $xfer;
	}
	
}




class InternalPriceService_getProdItemRecentNTimesSalePrice_args {
	
	static $_TSPEC;
	public $context = null;
	public $companyCode = null;
	public $storeCode = null;
	public $barcode = null;
	public $nTimes = null;
	
	public function __construct($vals=null){
		
		if (!isset(self::$_TSPEC)){
			
			self::$_TSPEC = array(
			1 => array(
			'var' => 'context'
			),
			2 => array(
			'var' => 'companyCode'
			),
			3 => array(
			'var' => 'storeCode'
			),
			4 => array(
			'var' => 'barcode'
			),
			5 => array(
			'var' => 'nTimes'
			),
			
			);
			
		}
		
		if (is_array($vals)){
			
			
			if (isset($vals['context'])){
				
				$this->context = $vals['context'];
			}
			
			
			if (isset($vals['companyCode'])){
				
				$this->companyCode = $vals['companyCode'];
			}
			
			
			if (isset($vals['storeCode'])){
				
				$this->storeCode = $vals['storeCode'];
			}
			
			
			if (isset($vals['barcode'])){
				
				$this->barcode = $vals['barcode'];
			}
			
			
			if (isset($vals['nTimes'])){
				
				$this->nTimes = $vals['nTimes'];
			}
			
			
		}
		
	}
	
	
	public function read($input){
		
		
		
		
		if(true) {
			
			
			$this->context = new \com\vip\xstore\cc\price\ReqContext();
			$this->context->read($input);
			
		}
		
		
		
		
		if(true) {
			
			$input->readString($this->companyCode);
			
		}
		
		
		
		
		if(true) {
			
			$input->readString($this->storeCode);
			
		}
		
		
		
		
		if(true) {
			
			$input->readString($this->barcode);
			
		}
		
		
		
		
		if(true) {
			
			$input->readI32($this->nTimes); 
			
		}
		
		
		
		
		
		
	}
	
	public function write($output){
		
		$xfer = 0;
		$xfer += $output->writeStructBegin();
		
		$xfer += $output->writeFieldBegin('context');
		
		if (!is_object($this->context)) {
			
			throw new \Osp\Exception\OspException('Bad type in structure.', \Osp\Exception\OspException::INVALID_DATA);
		}
		
		$xfer += $this->context->write($output);
		
		$xfer += $output->writeFieldEnd();
		
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
		
		
		$xfer += $output->writeFieldBegin('nTimes');
		$xfer += $output->writeI32($this->nTimes);
		
		$xfer += $output->writeFieldEnd();
		
		$xfer += $output->writeFieldStop();
		$xfer += $output->writeStructEnd();
		return $xfer;
	}
	
}




class InternalPriceService_getProdItemSettlementPrice_args {
	
	static $_TSPEC;
	public $req = null;
	
	public function __construct($vals=null){
		
		if (!isset(self::$_TSPEC)){
			
			self::$_TSPEC = array(
			1 => array(
			'var' => 'req'
			),
			
			);
			
		}
		
		if (is_array($vals)){
			
			
			if (isset($vals['req'])){
				
				$this->req = $vals['req'];
			}
			
			
		}
		
	}
	
	
	public function read($input){
		
		
		
		
		if(true) {
			
			
			$this->req = new \com\vip\xstore\cc\price\ProdItemSettlementPriceReq();
			$this->req->read($input);
			
		}
		
		
		
		
		
		
	}
	
	public function write($output){
		
		$xfer = 0;
		$xfer += $output->writeStructBegin();
		
		$xfer += $output->writeFieldBegin('req');
		
		if (!is_object($this->req)) {
			
			throw new \Osp\Exception\OspException('Bad type in structure.', \Osp\Exception\OspException::INVALID_DATA);
		}
		
		$xfer += $this->req->write($output);
		
		$xfer += $output->writeFieldEnd();
		
		$xfer += $output->writeFieldStop();
		$xfer += $output->writeStructEnd();
		return $xfer;
	}
	
}




class InternalPriceService_healthCheck_args {
	
	static $_TSPEC;
	
	public function __construct($vals=null){
		
		if (!isset(self::$_TSPEC)){
			
			self::$_TSPEC = array(
			
			);
			
		}
		
		if (is_array($vals)){
			
			
		}
		
	}
	
	
	public function read($input){
		
		
		
		
		
		
	}
	
	public function write($output){
		
		$xfer = 0;
		$xfer += $output->writeStructBegin();
		
		$xfer += $output->writeFieldStop();
		$xfer += $output->writeStructEnd();
		return $xfer;
	}
	
}




class InternalPriceService_batchGetPrice_result {
	
	static $_TSPEC;
	public $success = null;
	
	public function __construct($vals=null){
		
		if (!isset(self::$_TSPEC)){
			
			self::$_TSPEC = array(
			0 => array(
			'var' => 'success'
			),
			
			);
			
		}
		
		if (is_array($vals)){
			
			
			if (isset($vals['success'])){
				
				$this->success = $vals['success'];
			}
			
			
		}
		
	}
	
	
	public function read($input){
		
		
		
		
		if(true) {
			
			
			$this->success = array();
			$_size0 = 0;
			$input->readListBegin();
			while(true){
				
				try{
					
					$elem0 = null;
					
					$elem0 = new \com\vip\xstore\cc\price\ProdItemPrice();
					$elem0->read($input);
					
					$this->success[$_size0++] = $elem0;
				}
				catch(\Exception $e){
					
					break;
				}
			}
			
			$input->readListEnd();
			
		}
		
		
		
		
		
		
	}
	
	public function write($output){
		
		$xfer = 0;
		$xfer += $output->writeStructBegin();
		
		if($this->success !== null) {
			
			$xfer += $output->writeFieldBegin('success');
			
			if (!is_array($this->success)){
				
				throw new \Osp\Exception\OspException('Bad type in structure.', \Osp\Exception\OspException::INVALID_DATA);
			}
			
			$output->writeListBegin();
			foreach ($this->success as $iter0){
				
				
				if (!is_object($iter0)) {
					
					throw new \Osp\Exception\OspException('Bad type in structure.', \Osp\Exception\OspException::INVALID_DATA);
				}
				
				$xfer += $iter0->write($output);
				
			}
			
			$output->writeListEnd();
			
			$xfer += $output->writeFieldEnd();
		}
		
		
		$xfer += $output->writeFieldStop();
		$xfer += $output->writeStructEnd();
		return $xfer;
	}
	
}




class InternalPriceService_batchGetProdItemBaseCostPrice_result {
	
	static $_TSPEC;
	public $success = null;
	
	public function __construct($vals=null){
		
		if (!isset(self::$_TSPEC)){
			
			self::$_TSPEC = array(
			0 => array(
			'var' => 'success'
			),
			
			);
			
		}
		
		if (is_array($vals)){
			
			
			if (isset($vals['success'])){
				
				$this->success = $vals['success'];
			}
			
			
		}
		
	}
	
	
	public function read($input){
		
		
		
		
		if(true) {
			
			
			$this->success = array();
			$_size1 = 0;
			$input->readListBegin();
			while(true){
				
				try{
					
					$elem1 = null;
					
					$elem1 = new \com\vip\xstore\cc\price\ProdItemBaseCostPrice();
					$elem1->read($input);
					
					$this->success[$_size1++] = $elem1;
				}
				catch(\Exception $e){
					
					break;
				}
			}
			
			$input->readListEnd();
			
		}
		
		
		
		
		
		
	}
	
	public function write($output){
		
		$xfer = 0;
		$xfer += $output->writeStructBegin();
		
		if($this->success !== null) {
			
			$xfer += $output->writeFieldBegin('success');
			
			if (!is_array($this->success)){
				
				throw new \Osp\Exception\OspException('Bad type in structure.', \Osp\Exception\OspException::INVALID_DATA);
			}
			
			$output->writeListBegin();
			foreach ($this->success as $iter0){
				
				
				if (!is_object($iter0)) {
					
					throw new \Osp\Exception\OspException('Bad type in structure.', \Osp\Exception\OspException::INVALID_DATA);
				}
				
				$xfer += $iter0->write($output);
				
			}
			
			$output->writeListEnd();
			
			$xfer += $output->writeFieldEnd();
		}
		
		
		$xfer += $output->writeFieldStop();
		$xfer += $output->writeStructEnd();
		return $xfer;
	}
	
}




class InternalPriceService_batchGetProdItemHisPrice_result {
	
	static $_TSPEC;
	public $success = null;
	
	public function __construct($vals=null){
		
		if (!isset(self::$_TSPEC)){
			
			self::$_TSPEC = array(
			0 => array(
			'var' => 'success'
			),
			
			);
			
		}
		
		if (is_array($vals)){
			
			
			if (isset($vals['success'])){
				
				$this->success = $vals['success'];
			}
			
			
		}
		
	}
	
	
	public function read($input){
		
		
		
		
		if(true) {
			
			
			$this->success = array();
			$_size1 = 0;
			$input->readListBegin();
			while(true){
				
				try{
					
					$elem1 = null;
					
					$elem1 = new \com\vip\xstore\cc\price\StoreProdItemHisPrice();
					$elem1->read($input);
					
					$this->success[$_size1++] = $elem1;
				}
				catch(\Exception $e){
					
					break;
				}
			}
			
			$input->readListEnd();
			
		}
		
		
		
		
		
		
	}
	
	public function write($output){
		
		$xfer = 0;
		$xfer += $output->writeStructBegin();
		
		if($this->success !== null) {
			
			$xfer += $output->writeFieldBegin('success');
			
			if (!is_array($this->success)){
				
				throw new \Osp\Exception\OspException('Bad type in structure.', \Osp\Exception\OspException::INVALID_DATA);
			}
			
			$output->writeListBegin();
			foreach ($this->success as $iter0){
				
				
				if (!is_object($iter0)) {
					
					throw new \Osp\Exception\OspException('Bad type in structure.', \Osp\Exception\OspException::INVALID_DATA);
				}
				
				$xfer += $iter0->write($output);
				
			}
			
			$output->writeListEnd();
			
			$xfer += $output->writeFieldEnd();
		}
		
		
		$xfer += $output->writeFieldStop();
		$xfer += $output->writeStructEnd();
		return $xfer;
	}
	
}




class InternalPriceService_batchGetProdItemNewestPrice_result {
	
	static $_TSPEC;
	public $success = null;
	
	public function __construct($vals=null){
		
		if (!isset(self::$_TSPEC)){
			
			self::$_TSPEC = array(
			0 => array(
			'var' => 'success'
			),
			
			);
			
		}
		
		if (is_array($vals)){
			
			
			if (isset($vals['success'])){
				
				$this->success = $vals['success'];
			}
			
			
		}
		
	}
	
	
	public function read($input){
		
		
		
		
		if(true) {
			
			
			$this->success = array();
			$_size1 = 0;
			$input->readListBegin();
			while(true){
				
				try{
					
					$elem1 = null;
					
					$elem1 = new \com\vip\xstore\cc\price\StoreProdItemPrice();
					$elem1->read($input);
					
					$this->success[$_size1++] = $elem1;
				}
				catch(\Exception $e){
					
					break;
				}
			}
			
			$input->readListEnd();
			
		}
		
		
		
		
		
		
	}
	
	public function write($output){
		
		$xfer = 0;
		$xfer += $output->writeStructBegin();
		
		if($this->success !== null) {
			
			$xfer += $output->writeFieldBegin('success');
			
			if (!is_array($this->success)){
				
				throw new \Osp\Exception\OspException('Bad type in structure.', \Osp\Exception\OspException::INVALID_DATA);
			}
			
			$output->writeListBegin();
			foreach ($this->success as $iter0){
				
				
				if (!is_object($iter0)) {
					
					throw new \Osp\Exception\OspException('Bad type in structure.', \Osp\Exception\OspException::INVALID_DATA);
				}
				
				$xfer += $iter0->write($output);
				
			}
			
			$output->writeListEnd();
			
			$xfer += $output->writeFieldEnd();
		}
		
		
		$xfer += $output->writeFieldStop();
		$xfer += $output->writeStructEnd();
		return $xfer;
	}
	
}




class InternalPriceService_batchGetProdItemNewestRetailPrice_result {
	
	static $_TSPEC;
	public $success = null;
	
	public function __construct($vals=null){
		
		if (!isset(self::$_TSPEC)){
			
			self::$_TSPEC = array(
			0 => array(
			'var' => 'success'
			),
			
			);
			
		}
		
		if (is_array($vals)){
			
			
			if (isset($vals['success'])){
				
				$this->success = $vals['success'];
			}
			
			
		}
		
	}
	
	
	public function read($input){
		
		
		
		
		if(true) {
			
			
			$this->success = array();
			$_size1 = 0;
			$input->readListBegin();
			while(true){
				
				try{
					
					$elem1 = null;
					
					$elem1 = new \com\vip\xstore\cc\price\ProdItemRetailPrice();
					$elem1->read($input);
					
					$this->success[$_size1++] = $elem1;
				}
				catch(\Exception $e){
					
					break;
				}
			}
			
			$input->readListEnd();
			
		}
		
		
		
		
		
		
	}
	
	public function write($output){
		
		$xfer = 0;
		$xfer += $output->writeStructBegin();
		
		if($this->success !== null) {
			
			$xfer += $output->writeFieldBegin('success');
			
			if (!is_array($this->success)){
				
				throw new \Osp\Exception\OspException('Bad type in structure.', \Osp\Exception\OspException::INVALID_DATA);
			}
			
			$output->writeListBegin();
			foreach ($this->success as $iter0){
				
				
				if (!is_object($iter0)) {
					
					throw new \Osp\Exception\OspException('Bad type in structure.', \Osp\Exception\OspException::INVALID_DATA);
				}
				
				$xfer += $iter0->write($output);
				
			}
			
			$output->writeListEnd();
			
			$xfer += $output->writeFieldEnd();
		}
		
		
		$xfer += $output->writeFieldStop();
		$xfer += $output->writeStructEnd();
		return $xfer;
	}
	
}




class InternalPriceService_batchGetProdItemNewestSalePrice_result {
	
	static $_TSPEC;
	public $success = null;
	
	public function __construct($vals=null){
		
		if (!isset(self::$_TSPEC)){
			
			self::$_TSPEC = array(
			0 => array(
			'var' => 'success'
			),
			
			);
			
		}
		
		if (is_array($vals)){
			
			
			if (isset($vals['success'])){
				
				$this->success = $vals['success'];
			}
			
			
		}
		
	}
	
	
	public function read($input){
		
		
		
		
		if(true) {
			
			
			$this->success = array();
			$_size1 = 0;
			$input->readListBegin();
			while(true){
				
				try{
					
					$elem1 = null;
					
					$elem1 = new \com\vip\xstore\cc\price\StoreProdItemSalePrice();
					$elem1->read($input);
					
					$this->success[$_size1++] = $elem1;
				}
				catch(\Exception $e){
					
					break;
				}
			}
			
			$input->readListEnd();
			
		}
		
		
		
		
		
		
	}
	
	public function write($output){
		
		$xfer = 0;
		$xfer += $output->writeStructBegin();
		
		if($this->success !== null) {
			
			$xfer += $output->writeFieldBegin('success');
			
			if (!is_array($this->success)){
				
				throw new \Osp\Exception\OspException('Bad type in structure.', \Osp\Exception\OspException::INVALID_DATA);
			}
			
			$output->writeListBegin();
			foreach ($this->success as $iter0){
				
				
				if (!is_object($iter0)) {
					
					throw new \Osp\Exception\OspException('Bad type in structure.', \Osp\Exception\OspException::INVALID_DATA);
				}
				
				$xfer += $iter0->write($output);
				
			}
			
			$output->writeListEnd();
			
			$xfer += $output->writeFieldEnd();
		}
		
		
		$xfer += $output->writeFieldStop();
		$xfer += $output->writeStructEnd();
		return $xfer;
	}
	
}




class InternalPriceService_getPrice_result {
	
	static $_TSPEC;
	public $success = null;
	
	public function __construct($vals=null){
		
		if (!isset(self::$_TSPEC)){
			
			self::$_TSPEC = array(
			0 => array(
			'var' => 'success'
			),
			
			);
			
		}
		
		if (is_array($vals)){
			
			
			if (isset($vals['success'])){
				
				$this->success = $vals['success'];
			}
			
			
		}
		
	}
	
	
	public function read($input){
		
		
		
		
		if(true) {
			
			
			$this->success = new \com\vip\xstore\cc\price\ProdItemPrice();
			$this->success->read($input);
			
		}
		
		
		
		
		
		
	}
	
	public function write($output){
		
		$xfer = 0;
		$xfer += $output->writeStructBegin();
		
		if($this->success !== null) {
			
			$xfer += $output->writeFieldBegin('success');
			
			if (!is_object($this->success)) {
				
				throw new \Osp\Exception\OspException('Bad type in structure.', \Osp\Exception\OspException::INVALID_DATA);
			}
			
			$xfer += $this->success->write($output);
			
			$xfer += $output->writeFieldEnd();
		}
		
		
		$xfer += $output->writeFieldStop();
		$xfer += $output->writeStructEnd();
		return $xfer;
	}
	
}




class InternalPriceService_getPricingReceipt_result {
	
	static $_TSPEC;
	public $success = null;
	
	public function __construct($vals=null){
		
		if (!isset(self::$_TSPEC)){
			
			self::$_TSPEC = array(
			0 => array(
			'var' => 'success'
			),
			
			);
			
		}
		
		if (is_array($vals)){
			
			
			if (isset($vals['success'])){
				
				$this->success = $vals['success'];
			}
			
			
		}
		
	}
	
	
	public function read($input){
		
		
		
		
		if(true) {
			
			
			$this->success = new \com\vip\xstore\cc\price\PricingReceiptResp();
			$this->success->read($input);
			
		}
		
		
		
		
		
		
	}
	
	public function write($output){
		
		$xfer = 0;
		$xfer += $output->writeStructBegin();
		
		if($this->success !== null) {
			
			$xfer += $output->writeFieldBegin('success');
			
			if (!is_object($this->success)) {
				
				throw new \Osp\Exception\OspException('Bad type in structure.', \Osp\Exception\OspException::INVALID_DATA);
			}
			
			$xfer += $this->success->write($output);
			
			$xfer += $output->writeFieldEnd();
		}
		
		
		$xfer += $output->writeFieldStop();
		$xfer += $output->writeStructEnd();
		return $xfer;
	}
	
}




class InternalPriceService_getPricingReceiptItem_result {
	
	static $_TSPEC;
	public $success = null;
	
	public function __construct($vals=null){
		
		if (!isset(self::$_TSPEC)){
			
			self::$_TSPEC = array(
			0 => array(
			'var' => 'success'
			),
			
			);
			
		}
		
		if (is_array($vals)){
			
			
			if (isset($vals['success'])){
				
				$this->success = $vals['success'];
			}
			
			
		}
		
	}
	
	
	public function read($input){
		
		
		
		
		if(true) {
			
			
			$this->success = new \com\vip\xstore\cc\price\PricingReceiptItemResp();
			$this->success->read($input);
			
		}
		
		
		
		
		
		
	}
	
	public function write($output){
		
		$xfer = 0;
		$xfer += $output->writeStructBegin();
		
		if($this->success !== null) {
			
			$xfer += $output->writeFieldBegin('success');
			
			if (!is_object($this->success)) {
				
				throw new \Osp\Exception\OspException('Bad type in structure.', \Osp\Exception\OspException::INVALID_DATA);
			}
			
			$xfer += $this->success->write($output);
			
			$xfer += $output->writeFieldEnd();
		}
		
		
		$xfer += $output->writeFieldStop();
		$xfer += $output->writeStructEnd();
		return $xfer;
	}
	
}




class InternalPriceService_getProdItemNewestPrice_result {
	
	static $_TSPEC;
	public $success = null;
	
	public function __construct($vals=null){
		
		if (!isset(self::$_TSPEC)){
			
			self::$_TSPEC = array(
			0 => array(
			'var' => 'success'
			),
			
			);
			
		}
		
		if (is_array($vals)){
			
			
			if (isset($vals['success'])){
				
				$this->success = $vals['success'];
			}
			
			
		}
		
	}
	
	
	public function read($input){
		
		
		
		
		if(true) {
			
			
			$this->success = new \com\vip\xstore\cc\price\StoreProdItemPrice();
			$this->success->read($input);
			
		}
		
		
		
		
		
		
	}
	
	public function write($output){
		
		$xfer = 0;
		$xfer += $output->writeStructBegin();
		
		if($this->success !== null) {
			
			$xfer += $output->writeFieldBegin('success');
			
			if (!is_object($this->success)) {
				
				throw new \Osp\Exception\OspException('Bad type in structure.', \Osp\Exception\OspException::INVALID_DATA);
			}
			
			$xfer += $this->success->write($output);
			
			$xfer += $output->writeFieldEnd();
		}
		
		
		$xfer += $output->writeFieldStop();
		$xfer += $output->writeStructEnd();
		return $xfer;
	}
	
}




class InternalPriceService_getProdItemNewestRetailPrice_result {
	
	static $_TSPEC;
	public $success = null;
	
	public function __construct($vals=null){
		
		if (!isset(self::$_TSPEC)){
			
			self::$_TSPEC = array(
			0 => array(
			'var' => 'success'
			),
			
			);
			
		}
		
		if (is_array($vals)){
			
			
			if (isset($vals['success'])){
				
				$this->success = $vals['success'];
			}
			
			
		}
		
	}
	
	
	public function read($input){
		
		
		
		
		if(true) {
			
			
			$this->success = new \com\vip\xstore\cc\price\ProdItemRetailPrice();
			$this->success->read($input);
			
		}
		
		
		
		
		
		
	}
	
	public function write($output){
		
		$xfer = 0;
		$xfer += $output->writeStructBegin();
		
		if($this->success !== null) {
			
			$xfer += $output->writeFieldBegin('success');
			
			if (!is_object($this->success)) {
				
				throw new \Osp\Exception\OspException('Bad type in structure.', \Osp\Exception\OspException::INVALID_DATA);
			}
			
			$xfer += $this->success->write($output);
			
			$xfer += $output->writeFieldEnd();
		}
		
		
		$xfer += $output->writeFieldStop();
		$xfer += $output->writeStructEnd();
		return $xfer;
	}
	
}




class InternalPriceService_getProdItemNewestSalePrice_result {
	
	static $_TSPEC;
	public $success = null;
	
	public function __construct($vals=null){
		
		if (!isset(self::$_TSPEC)){
			
			self::$_TSPEC = array(
			0 => array(
			'var' => 'success'
			),
			
			);
			
		}
		
		if (is_array($vals)){
			
			
			if (isset($vals['success'])){
				
				$this->success = $vals['success'];
			}
			
			
		}
		
	}
	
	
	public function read($input){
		
		
		
		
		if(true) {
			
			
			$this->success = new \com\vip\xstore\cc\price\StoreProdItemSalePrice();
			$this->success->read($input);
			
		}
		
		
		
		
		
		
	}
	
	public function write($output){
		
		$xfer = 0;
		$xfer += $output->writeStructBegin();
		
		if($this->success !== null) {
			
			$xfer += $output->writeFieldBegin('success');
			
			if (!is_object($this->success)) {
				
				throw new \Osp\Exception\OspException('Bad type in structure.', \Osp\Exception\OspException::INVALID_DATA);
			}
			
			$xfer += $this->success->write($output);
			
			$xfer += $output->writeFieldEnd();
		}
		
		
		$xfer += $output->writeFieldStop();
		$xfer += $output->writeStructEnd();
		return $xfer;
	}
	
}




class InternalPriceService_getProdItemRecentNTimesSalePrice_result {
	
	static $_TSPEC;
	public $success = null;
	
	public function __construct($vals=null){
		
		if (!isset(self::$_TSPEC)){
			
			self::$_TSPEC = array(
			0 => array(
			'var' => 'success'
			),
			
			);
			
		}
		
		if (is_array($vals)){
			
			
			if (isset($vals['success'])){
				
				$this->success = $vals['success'];
			}
			
			
		}
		
	}
	
	
	public function read($input){
		
		
		
		
		if(true) {
			
			
			$this->success = array();
			$_size0 = 0;
			$input->readListBegin();
			while(true){
				
				try{
					
					$elem0 = null;
					
					$elem0 = new \com\vip\xstore\cc\price\StoreProdItemHisSalePrice();
					$elem0->read($input);
					
					$this->success[$_size0++] = $elem0;
				}
				catch(\Exception $e){
					
					break;
				}
			}
			
			$input->readListEnd();
			
		}
		
		
		
		
		
		
	}
	
	public function write($output){
		
		$xfer = 0;
		$xfer += $output->writeStructBegin();
		
		if($this->success !== null) {
			
			$xfer += $output->writeFieldBegin('success');
			
			if (!is_array($this->success)){
				
				throw new \Osp\Exception\OspException('Bad type in structure.', \Osp\Exception\OspException::INVALID_DATA);
			}
			
			$output->writeListBegin();
			foreach ($this->success as $iter0){
				
				
				if (!is_object($iter0)) {
					
					throw new \Osp\Exception\OspException('Bad type in structure.', \Osp\Exception\OspException::INVALID_DATA);
				}
				
				$xfer += $iter0->write($output);
				
			}
			
			$output->writeListEnd();
			
			$xfer += $output->writeFieldEnd();
		}
		
		
		$xfer += $output->writeFieldStop();
		$xfer += $output->writeStructEnd();
		return $xfer;
	}
	
}




class InternalPriceService_getProdItemSettlementPrice_result {
	
	static $_TSPEC;
	public $success = null;
	
	public function __construct($vals=null){
		
		if (!isset(self::$_TSPEC)){
			
			self::$_TSPEC = array(
			0 => array(
			'var' => 'success'
			),
			
			);
			
		}
		
		if (is_array($vals)){
			
			
			if (isset($vals['success'])){
				
				$this->success = $vals['success'];
			}
			
			
		}
		
	}
	
	
	public function read($input){
		
		
		
		
		if(true) {
			
			
			$this->success = new \com\vip\xstore\cc\price\ProdItemSettlementPriceResp();
			$this->success->read($input);
			
		}
		
		
		
		
		
		
	}
	
	public function write($output){
		
		$xfer = 0;
		$xfer += $output->writeStructBegin();
		
		if($this->success !== null) {
			
			$xfer += $output->writeFieldBegin('success');
			
			if (!is_object($this->success)) {
				
				throw new \Osp\Exception\OspException('Bad type in structure.', \Osp\Exception\OspException::INVALID_DATA);
			}
			
			$xfer += $this->success->write($output);
			
			$xfer += $output->writeFieldEnd();
		}
		
		
		$xfer += $output->writeFieldStop();
		$xfer += $output->writeStructEnd();
		return $xfer;
	}
	
}




class InternalPriceService_healthCheck_result {
	
	static $_TSPEC;
	public $success = null;
	
	public function __construct($vals=null){
		
		if (!isset(self::$_TSPEC)){
			
			self::$_TSPEC = array(
			0 => array(
			'var' => 'success'
			),
			
			);
			
		}
		
		if (is_array($vals)){
			
			
			if (isset($vals['success'])){
				
				$this->success = $vals['success'];
			}
			
			
		}
		
	}
	
	
	public function read($input){
		
		
		
		
		if(true) {
			
			
			$this->success = new \com\vip\hermes\core\health\CheckResult();
			$this->success->read($input);
			
		}
		
		
		
		
		
		
	}
	
	public function write($output){
		
		$xfer = 0;
		$xfer += $output->writeStructBegin();
		
		if($this->success !== null) {
			
			$xfer += $output->writeFieldBegin('success');
			
			if (!is_object($this->success)) {
				
				throw new \Osp\Exception\OspException('Bad type in structure.', \Osp\Exception\OspException::INVALID_DATA);
			}
			
			$xfer += $this->success->write($output);
			
			$xfer += $output->writeFieldEnd();
		}
		
		
		$xfer += $output->writeFieldStop();
		$xfer += $output->writeStructEnd();
		return $xfer;
	}
	
}




?>