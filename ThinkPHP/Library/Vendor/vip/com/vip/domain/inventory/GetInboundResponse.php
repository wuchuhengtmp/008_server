<?php


/*
* Copyright (c) 2008-2016 vip.com, All Rights Reserved.
*
* Powered by com.vip.osp.osp-idlc-2.5.11.
*
*/

namespace com\vip\domain\inventory;

class GetInboundResponse {
	
	static $_TSPEC;
	public $detail_count = null;
	public $items = null;
	
	public function __construct($vals=null){
		
		if (!isset(self::$_TSPEC)){
			
			self::$_TSPEC = array(
			1 => array(
			'var' => 'detail_count'
			),
			2 => array(
			'var' => 'items'
			),
			
			);
			
		}
		
		if (is_array($vals)){
			
			
			if (isset($vals['detail_count'])){
				
				$this->detail_count = $vals['detail_count'];
			}
			
			
			if (isset($vals['items'])){
				
				$this->items = $vals['items'];
			}
			
			
		}
		
	}
	
	
	public function getName(){
		
		return 'GetInboundResponse';
	}
	
	public function read($input){
		
		$input->readStructBegin();
		while(true){
			
			$schemeField = $input->readFieldBegin();
			if ($schemeField == null) break;
			$needSkip = true;
			
			
			if ("detail_count" == $schemeField){
				
				$needSkip = false;
				$input->readI32($this->detail_count); 
				
			}
			
			
			
			
			if ("items" == $schemeField){
				
				$needSkip = false;
				
				$this->items = array();
				$_size1 = 0;
				$input->readListBegin();
				while(true){
					
					try{
						
						$elem1 = null;
						
						$elem1 = new \com\vip\domain\inventory\InboundItemInfo();
						$elem1->read($input);
						
						$this->items[$_size1++] = $elem1;
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
		
		$xfer += $output->writeFieldBegin('detail_count');
		$xfer += $output->writeI32($this->detail_count);
		
		$xfer += $output->writeFieldEnd();
		
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
		
		$xfer += $output->writeFieldStop();
		$xfer += $output->writeStructEnd();
		return $xfer;
	}
	
}

?>