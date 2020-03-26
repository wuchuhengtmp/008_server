<?php

namespace Osp\Header;

use Thrift\Type\TType;
use Thrift\Exception\TProtocolException;

/**
 * 头部实体
 * 
 * @author Jamin.li
 *        
 */
class Header {
	static $_TSPEC;
	public $serviceName = null;
	public $method = null;
	public $version = null;
	public $options = null;
	public $callerId = null;
	public $callerTid = null;
	public $sessionTid = null;
	public $uid = null;
	public $uip = null;
	public $callerSign = null;
	public $cookie = null;
	public $retCode = null;
	public $retMessage = null;
	public $calleeTime1 = null;
	public $calleeTime2 = null;
	public $calleeTid = null;
	public function __construct($vals = null) {
		if (! isset ( self::$_TSPEC )) {
			self::$_TSPEC = array (
					1 => array (
							'var' => 'serviceName',
							'type' => TType::STRING 
					),
					2 => array (
							'var' => 'method',
							'type' => TType::STRING 
					),
					3 => array (
							'var' => 'version',
							'type' => TType::STRING 
					),
					4 => array (
							'var' => 'options',
							'type' => TType::I32 
					),
					6 => array (
							'var' => 'callerId',
							'type' => TType::STRING 
					),
					11 => array (
							'var' => 'callerTid',
							'type' => TType::I64 
					),
					10 => array (
							'var' => 'sessionTid',
							'type' => TType::I64 
					),
					14 => array (
							'var' => 'uid',
							'type' => TType::I64 
					),
					15 => array (
							'var' => 'uip',
							'type' => TType::I32 
					),
					30 => array (
							'var' => 'callerSign',
							'type' => TType::STRING 
					),
					16 => array (
							'var' => 'cookie',
							'type' => TType::MAP,
							'ktype' => TType::STRING,
							'vtype' => TType::STRING,
							'key' => array (
									'type' => TType::STRING 
							),
							'val' => array (
									'type' => TType::STRING 
							) 
					),
					7 => array (
							'var' => 'retCode', 
							'type' => TType::STRING
					),
					8 => array (
							'var' => 'retMessage',
							'type' => TType::STRING 
					),
					21 => array (
							'var' => 'calleeTime1',
							'type' => TType::I32 
					),
					22 => array (
							'var' => 'calleeTime2',
							'type' => TType::I32 
					),
					23 => array (
							'var' => 'calleeTid',
							'type' => TType::I64 
					) 
			);
		}
		if (is_array ( $vals )) {
			if (isset ( $vals ['serviceName'] )) {
				$this->serviceName = $vals ['serviceName'];
			}
			if (isset ( $vals ['method'] )) {
				$this->method = $vals ['method'];
			}
			if (isset ( $vals ['version'] )) {
				$this->version = $vals ['version'];
			}
			if (isset ( $vals ['options'] )) {
				$this->options = $vals ['options'];
			}
			if (isset ( $vals ['callerId'] )) {
				$this->callerId = $vals ['callerId'];
			}
			if (isset ( $vals ['callerTid'] )) {
				$this->callerTid = $vals ['callerTid'];
			}
			if (isset ( $vals ['sessionTid'] )) {
				$this->sessionTid = $vals ['sessionTid'];
			}
			if (isset ( $vals ['uid'] )) {
				$this->uid = $vals ['uid'];
			}
			if (isset ( $vals ['uip'] )) {
				$this->uip = $vals ['uip'];
			}
			if (isset ( $vals ['callerSign'] )) {
				$this->callerSign = $vals ['callerSign'];
			}
			if (isset ( $vals ['cookie'] )) {
				$this->cookie = $vals ['cookie'];
			}
			if (isset ( $vals ['retCode'] )) {
				$this->retCode = $vals ['retCode'];
			}
			if (isset ( $vals ['retMessage'] )) {
				$this->retMessage = $vals ['retMessage'];
			}
			if (isset ( $vals ['calleeTime1'] )) {
				$this->calleeTime1 = $vals ['calleeTime1'];
			}
			if (isset ( $vals ['calleeTime2'] )) {
				$this->calleeTime2 = $vals ['calleeTime2'];
			}
			if (isset ( $vals ['calleeTid'] )) {
				$this->calleeTid = $vals ['calleeTid'];
			}
		}
	}
	public function getName() {
		return 'Header';
	}
	public function read($input) {
		$xfer = 0;
		$fname = null;
		$ftype = 0;
		$fid = 0;
		$xfer += $input->readStructBegin ( $fname );
		while ( true ) {
			$xfer += $input->readFieldBegin ( $fname, $ftype, $fid );
			if ($ftype == TType::STOP) {
				break;
			}
			switch ($fid) {
				case 1 :
					if ($ftype == TType::STRING) {
						$xfer += $input->readString ( $this->serviceName );
					} else {
						$xfer += $input->skip ( $ftype );
					}
					break;
				case 2 :
					if ($ftype == TType::STRING) {
						$xfer += $input->readString ( $this->method );
					} else {
						$xfer += $input->skip ( $ftype );
					}
					break;
				case 3 :
					if ($ftype == TType::STRING) {
						$xfer += $input->readString ( $this->version );
					} else {
						$xfer += $input->skip ( $ftype );
					}
					break;
				case 4 :
					if ($ftype == TType::I32) {
						$xfer += $input->readI32 ( $this->options );
					} else {
						$xfer += $input->skip ( $ftype );
					}
					break;
				case 6 :
					if ($ftype == TType::STRING) {
						$xfer += $input->readString ( $this->callerId );
					} else {
						$xfer += $input->skip ( $ftype );
					}
					break;
				case 11 :
					if ($ftype == TType::I64) {
						$xfer += $input->readI64 ( $this->callerTid );
					} else {
						$xfer += $input->skip ( $ftype );
					}
					break;
				case 10 :
					if ($ftype == TType::I64) {
						$xfer += $input->readI64 ( $this->sessionTid );
					} else {
						$xfer += $input->skip ( $ftype );
					}
					break;
				case 14 :
					if ($ftype == TType::I64) {
						$xfer += $input->readI64 ( $this->uid );
					} else {
						$xfer += $input->skip ( $ftype );
					}
					break;
				case 15 :
					if ($ftype == TType::I32) {
						$xfer += $input->readI32 ( $this->uip );
					} else {
						$xfer += $input->skip ( $ftype );
					}
					break;
				case 30 :
					if ($ftype == TType::STRING) {
						$xfer += $input->readString ( $this->callerSign );
					} else {
						$xfer += $input->skip ( $ftype );
					}
					break;
				case 16 :
					if ($ftype == TType::MAP) {
						$this->cookie = array ();
						$_size0 = 0;
						$_ktype1 = 0;
						$_vtype2 = 0;
						$xfer += $input->readMapBegin ( $_ktype1, $_vtype2, $_size0 );
						for($_i4 = 0; $_i4 < $_size0; ++ $_i4) {
							$key5 = '';
							$val6 = '';
							$xfer += $input->readString ( $key5 );
							$xfer += $input->readString ( $val6 );
							$this->cookie [$key5] = $val6;
						}
						$xfer += $input->readMapEnd ();
					} else {
						$xfer += $input->skip ( $ftype );
					}
					break;
				case 7 :
					if ($ftype == TType::STRING) {
						$xfer += $input->readString ( $this->retCode );
					} else {
						$xfer += $input->skip ( $ftype );
					}
					break;
				case 8 :
					if ($ftype == TType::STRING) {
						$xfer += $input->readString ( $this->retMessage );
					} else {
						$xfer += $input->skip ( $ftype );
					}
					break;
				case 21 :
					if ($ftype == TType::I32) {
						$xfer += $input->readI32 ( $this->calleeTime1 );
					} else {
						$xfer += $input->skip ( $ftype );
					}
					break;
				case 22 :
					if ($ftype == TType::I32) {
						$xfer += $input->readI32 ( $this->calleeTime2 );
					} else {
						$xfer += $input->skip ( $ftype );
					}
					break;
				case 23 :
					if ($ftype == TType::I64) {
						$xfer += $input->readI64 ( $this->calleeTid );
					} else {
						$xfer += $input->skip ( $ftype );
					}
					break;
				default :
					$xfer += $input->skip ( $ftype );
					break;
			}
			$xfer += $input->readFieldEnd ();
		}
		$xfer += $input->readStructEnd ();
		return $xfer;
	}
	public function write($output) {
		$xfer = 0;
		$xfer += $output->writeStructBegin ( 'Header' );
		if ($this->serviceName !== null) {
			$xfer += $output->writeFieldBegin ( 'serviceName', TType::STRING, 1 );
			$xfer += $output->writeString ( $this->serviceName );
			$xfer += $output->writeFieldEnd ();
		}
		if ($this->method !== null) {
			$xfer += $output->writeFieldBegin ( 'method', TType::STRING, 2 );
			$xfer += $output->writeString ( $this->method );
			$xfer += $output->writeFieldEnd ();
		}
		if ($this->version !== null) {
			$xfer += $output->writeFieldBegin ( 'version', TType::STRING, 3 );
			$xfer += $output->writeString ( $this->version );
			$xfer += $output->writeFieldEnd ();
		}
		if ($this->options !== null) {
			$xfer += $output->writeFieldBegin ( 'options', TType::I32, 4 );
			$xfer += $output->writeI32 ( $this->options );
			$xfer += $output->writeFieldEnd ();
		}
		if ($this->callerId !== null) {
			$xfer += $output->writeFieldBegin ( 'callerId', TType::STRING, 6 );
			$xfer += $output->writeString ( $this->callerId );
			$xfer += $output->writeFieldEnd ();
		}
		if ($this->retCode !== null) {
			$xfer += $output->writeFieldBegin ( 'retCode', TType::STRING, 7 );
			$xfer += $output->writeString ( $this->retCode );
			$xfer += $output->writeFieldEnd ();
		}
		if ($this->retMessage !== null) {
			$xfer += $output->writeFieldBegin ( 'retMessage', TType::STRING, 8 );
			$xfer += $output->writeString ( $this->retMessage );
			$xfer += $output->writeFieldEnd ();
		}
		if ($this->sessionTid !== null) {
			$xfer += $output->writeFieldBegin ( 'sessionTid', TType::I64, 10 );
			$xfer += $output->writeI64 ( $this->sessionTid );
			$xfer += $output->writeFieldEnd ();
		}
		if ($this->callerTid !== null) {
			$xfer += $output->writeFieldBegin ( 'callerTid', TType::I64, 11 );
			$xfer += $output->writeI64 ( $this->callerTid );
			$xfer += $output->writeFieldEnd ();
		}
		if ($this->uid !== null) {
			$xfer += $output->writeFieldBegin ( 'uid', TType::I64, 14 );
			$xfer += $output->writeI64 ( $this->uid );
			$xfer += $output->writeFieldEnd ();
		}
		if ($this->uip !== null) {
			$xfer += $output->writeFieldBegin ( 'uip', TType::I32, 15 );
			$xfer += $output->writeI32 ( $this->uip );
			$xfer += $output->writeFieldEnd ();
		}
		if ($this->cookie !== null) {
			if (! is_array ( $this->cookie )) {
				throw new TProtocolException ( 'Bad type in structure.', TProtocolException::INVALID_DATA );
			}
			$xfer += $output->writeFieldBegin ( 'cookie', TType::MAP, 16 );
			{
				$output->writeMapBegin ( TType::STRING, TType::STRING, count ( $this->cookie ) );
				{
					foreach ( $this->cookie as $kiter7 => $viter8 ) {
						$xfer += $output->writeString ( $kiter7 );
						$xfer += $output->writeString ( $viter8 );
					}
				}
				$output->writeMapEnd ();
			}
			$xfer += $output->writeFieldEnd ();
		}
		if ($this->calleeTime1 !== null) {
			$xfer += $output->writeFieldBegin ( 'calleeTime1', TType::I32, 21 );
			$xfer += $output->writeI32 ( $this->calleeTime1 );
			$xfer += $output->writeFieldEnd ();
		}
		if ($this->calleeTime2 !== null) {
			$xfer += $output->writeFieldBegin ( 'calleeTime2', TType::I32, 22 );
			$xfer += $output->writeI32 ( $this->calleeTime2 );
			$xfer += $output->writeFieldEnd ();
		}
		if ($this->calleeTid !== null) {
			$xfer += $output->writeFieldBegin ( 'calleeTid', TType::I64, 23 );
			$xfer += $output->writeI64 ( $this->calleeTid );
			$xfer += $output->writeFieldEnd ();
		}
		if ($this->callerSign !== null) {
			$xfer += $output->writeFieldBegin ( 'callerSign', TType::STRING, 30 );
			$xfer += $output->writeString ( $this->callerSign );
			$xfer += $output->writeFieldEnd ();
		}
		$xfer += $output->writeFieldStop ();
		$xfer += $output->writeStructEnd ();
		return $xfer;
	}
	public function setServiceName($serviceName) {
		$this->serviceName = $serviceName;
	}
	public function getServiceName() {
		return $this->serviceName;
	}
	public function setMethod($method) {
		$this->method = $method;
	}
	public function getMethod() {
		return $this->method;
	}
	public function setVersion($version) {
		$this->version = $version;
	}
	public function getVersion() {
		return $this->version;
	}
	public function setOptions($options) {
		$this->options = $options;
	}
	public function getOptions() {
		return $this->options;
	}
	public function setCallerId($callerId) {
		$this->callerId = $callerId;
	}
	public function getCallerId() {
		return $this->callerId;
	}
	public function setCallerTid($callerTid) {
		$this->callerTid = $callerTid;
	}
	public function getCallerTid() {
		return $this->callerTid;
	}
	public function setSessionTid($sessionTid) {
		$this->sessionTid = $sessionTid;
	}
	public function getSessionTid() {
		return $this->sessionTid;
	}
	public function setUid($uid) {
		$this->uid = $uid;
	}
	public function getUid() {
		return $this->uid;
	}
	public function setUip($uip) {
		$this->uip = $uip;
	}
	public function getUip() {
		return $this->uip;
	}
	public function setCallerSign($callerSign) {
		$this->callerSign = $callerSign;
	}
	public function getCallerSign() {
		return $this->callerSign;
	}
	public function setCookie($cookie) {
		$this->cookie = $cookie;
	}
	public function getCookie() {
		return $this->cookie;
	}
	public function setRetCode($retCode) {
		$this->retCode = $retCode;
	}
	public function getRetCode() {
		return $this->retCode;
	}
	public function setRetMessage($retMessage) {
		$this->retMessage = $retMessage;
	}
	public function getRetMessage() {
		return $this->retMessage;
	}
	public function setCalleeTime1($calleeTime1) {
		$this->calleeTime1 = $calleeTime1;
	}
	public function getCalleeTime1() {
		return $this->calleeTime1;
	}
	public function setCalleeTime2($calleeTime2) {
		$this->calleeTime2 = $calleeTime2;
	}
	public function getCalleeTime2() {
		return $this->calleeTime2;
	}
	public function setCalleeTid($calleeTid) {
		$this->calleeTid = $calleeTid;
	}
	public function getCalleeTid() {
		return $this->calleeTid;
	}
}


