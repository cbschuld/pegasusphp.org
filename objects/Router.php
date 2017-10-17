<?php
/**
 * 
 * @author cbschuld
 * @package PegasusPHP
 */

	class Router {
		function __construct() {}
		function __destruct() {}
		
		private $_inputFilename = '';
		private $_bLoaded = false;
		private $_policy;

		function setInputFilename($filename) { $this->_inputFilename = $filename; }
		function setAllowCache($val=true) { $this->_allow_cache = $val; }
		
		function process() {
			if( ! $this->_bLoaded ) {
				$this->load();
			}
			if( $this->_bLoaded ) {
				$mname = $aname = '';
				foreach( $this->_policy->module as $module ) {
					$mname = $module->attributes()->name;
					$mclass = $module->attributes()->class;
					$bounce = $module->attributes()->bounce != '' ? $module->attributes()->bounce : '';
					
					if( Request::module() == $mname ) {
						
						$bActionExists = false;
						$bDefaultActionExists = false;
						
						$action = null;
						
						// Locate Called Action
						for($a = 0; $a < count($module->action); $a++ ) {
							if( $module->action[$a]->attributes()->name == Request::action() ) {
								$action = $module->action[$a];
								break;
							}
						}
						
						// Locate Default Action
						if( ! $action ) {
							for($a = 0; $a < count($module->action); $a++ ) {
								if( $module->action[$a]->attributes()->name == 'default' ) {
									$action = $module->action[$a];
									break;
								}
							}
						}
						
						if( $action ) {
							$mode = $action->attributes()->method;
							if( strtolower($mode) == 'both' || ( strtolower($mode) == 'post' && Request::isPost() ) || ( strtolower($mode) == 'get' && ! Request::isPost() ) ) {
								$bRequirements = true;
								
								foreach( $action->require as $require ) {
									foreach( $require->request as $request ) {
										if( ! Request::exists( (string)$request ) ) {
											$bRequirements = false;
											break 2;
										}
									}
									foreach( $require->privilege as $privilege ) {
										if( ! Session::getUser()->hasPrivilege((string)$privilege) ) {
											$bRequirements = false;
											break 2;
										}
									}
								}
	
								if( $bRequirements ) {
									foreach( $action->method as $method ) {
										include_object($mclass);
										call_user_func($mclass.'::'.(string)$method);
									}
									return true;
								}
								else {
									if( $bounce != '' ) {
										Pegasus::bounce($bounce);
									}
								}
							}
							else {
								if( $bounce != '' ) {
									Pegasus::bounce($bounce);
								}
							}
						}
						else {
							if( $bounce != '' ) {
								Pegasus::bounce($bounce);
							}
						}
					}
				}
			}
			return false;
		}
		
		function load() {
			if( file_exists($this->_inputFilename) ) {
				$this->_policy = simplexml_load_file($this->_inputFilename,'RouterPolicy',LIBXML_COMPACT);
				$this->_bLoaded = !($this->_policy === false);
			}
		}

	};
	class RouterPolicy extends SimpleXMLElement {};
	
?>