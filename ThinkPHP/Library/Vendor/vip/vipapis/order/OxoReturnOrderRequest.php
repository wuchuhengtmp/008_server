<?php


/*
* Copyright (c) 2008-2016 vip.com, All Rights Reserved.
*
* Powered by com.vip.osp.osp-idlc-2.5.11.
*
*/

namespace vipapis\order;

class OxoReturnOrderRequest {
	
	static $_TSPEC;
	public $vendor_id = null;
	public $st_query_time = null;
	public $et_query_time = null;
	public $limit = null;
	public $page = null;
	public $order_id = null;
	
	public function __construct($vals=null){
		
		if (!isset(self::$_TSPEC)){
			
			self::$_TSPEC = array(
			1 => array(
			'var' => 'vendor_id'
			),
			2 => array(
			'var' => 'st_query_time'
			),
			3 => array(
			'var' => 'et_query_time'
			),
			4 => array(
			'var' => 'limit'
			),
			5 => array(
			'var' => 'page'
			),
			6 => array(
			'var' => 'order_id'
			),
			
			);
			
		}
		
		if (is_array($vals)){
			
			
			if (isset($vals['vendor_id'])){
				
				$this->vendor_id = $vals['vendor_id'];
			}
			
			
			if (isset($vals['st_query_time'])){
				
				$this->st_query_time = $vals['st_query_time'];
			}
			
			
			if (isset($vals['et_query_time'])){
				
				$this->et_query_time = $vals['et_query_time'];
			}
			
			
			if (isset($vals['limit'])){
				
				$this->limit = $vals['limit'];
			}
			
			
			if (isset($vals['page'])){
				
				$this->page = $vals['page'];
			}
			
			
			if (isset($vals['order_id'])){
				
				$this->order_id = $vals['order_id'];
			}
			
			
		}
		
	}
	
	
	public function getName(){
		
		return 'OxoReturnOrderRequest';
	}
	
	public function read($input){
		
		$input->readStructBegin();
		while(true){
			
			$schemeField = $input->readFieldBegin();
			if ($schemeField == null) break;
			$needSkip = true;
			
			
			if ("vendor_id" == $schemeField){
				
				$needSkip = false;
				$input->readI64($this->vendor_id); 
				
			}
			
			
			
			
			if ("st_query_time" == $schemeField){
				
				$needSkip = false;
				$input->readI64($this->st_query_time); 
				
			}
			
			
			
			
			if ("et_query_time" == $schemeField){
				
				$needSkip = false;
				$input->readI64($this->et_query_time); 
				
			}
			
			
			
			
			if ("limit" == $schemeField){
				
				$needSkip = false;
				$input->readI32($this->limit); 
				
			}
			
			
			
			
			if ("page" == $schemeField){
				
				$needSkip = false;
				$input->readI32($this->page); 
				
			}
			
			
			
			
			if ("order_id" == $schemeField){
				
				$needSkip = false;
				
				$this->order_id = array();
				$_size1 = 0;
				$input->readListBegin();
				while(true){
					
					try{
						
						$elem1 = null;
						$input->readString($elem1);
						
						$this->order_id[$_size1++] = $elem1;
					}
					catch(\Exception $e){
						
						break;
					}
				}
				
				$input->readListEnd();
				
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
		
		$xfer += $output->writeFieldBegin('vendor_id');
		$xfer += $output->writeI64($this->vendor_id);
		
		$xfer += $output->writeFieldEnd();
		
		if($this->st_query_time !== null) {
			
			$xfer += $output->writeFieldBegin('st_query_time');
			$xfer += $output->writeI64($this->st_query_time);
			
			$xfer += $output->writeFieldEnd();
		}
		
		
		if($this->et_query_time !== null) {
			
			$xfer += $output->writeFieldBegin('et_query_time');
			$xfer += $output->writeI64($this->et_query_time);
			
			$xfer += $output->writeFieldEnd();
		}
		
		
		if($this->limit !== null) {
			
			$xfer += $output->writeFieldBegin('limit');
			$xfer += $output->writeI32($this->limit);
			
			$xfer += $output->writeFieldEnd();
		}
		
		
		if($this->page !== null) {
			
			$xfer += $output->writeFieldBegin('page');
			$xfer += $output->writeI32($this->page);
			
			$xfer += $output->writeFieldEnd();
		}
		
		
		if($this->order_id !== null) {
			
			$xfer += $output->writeFieldBegin('order_id');
			
			if (!is_array($this->order_id)){
				
				throw new \Osp\Exception\OspException('Bad type in structure.', \Osp\Exception\OspException::INVALID_DATA);
			}
			
			$output->writeListBegin();
			foreach ($this->order_id as $iter0){
				
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

?>