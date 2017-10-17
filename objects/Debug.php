<?php

	/**
	 * DebugInfo
	 *
	 * @package PegasusPHP
	 */
	class DebugInfo {
		
		private $_strTitle;
		private $_strName;
		private $_object;

		public function setTitle($strValue) { $this->_strTitle = $strValue; }
		public function setName($strValue) { $this->_strName = $strValue; }
		public function setObject(&$obj) { $this->_object = &$obj; }

		public function getTitle() { return $this->_strTitle; }
		public function getName() { return $this->_strName; }
		public function getObject() { return $this->_object; }
		
		public function getFormattedObject() {
			return $this->_strName . Debug::varDump($this->_object);
		}
	}

	/**
	 * Class Debug is a convenience class that contains all of the debugging
	 * code within the framework.  The class is only used when ASSERT_ENABLED is
	 * enabled in the framework.  NOTE that it is enabled by default.
	 * 
	 * @package PegasusPHP
	 */
	class Debug {
		
		public static $debugPost;
		public static $debugGet;
		public static $debugSession;
		public static $debugServer;
		public static $debugRequest;
		public static $debugLegacyRequest;
		
		private static $_debugInfo = array();
		
		public static function getDebugInfo() {
			return $this->_debugInfo();
		}
		
		public static function getNewDebugInfo() {
			self::$_debugInfo[] = new DebugInfo();
			return self::$_debugInfo[ count( self::$_debugInfo ) - 1 ];
		}
		
		public function __construct() {
			self::$debugPost = new DebugInfo();
			self::$debugGet = new DebugInfo();
			
			self::$debugSession = new DebugInfo();
			self::$debugServer = new DebugInfo();
			self::$debugRequest = new DebugInfo();
			self::$debugLegacyRequest = new DebugInfo();

			self::$debugPost->setTitle('POST Information');
			self::$debugGet->setTitle('GET Information');
			
			self::$debugSession->setTitle('SESSION Information');
			self::$debugServer->setTitle('PHP SERVER Information');
			self::$debugRequest->setTitle('REQUEST Information');
			self::$debugLegacyRequest->setTitle('PREVIOUS REQUEST Information (The Previous Request)');
		}
		
		public static function getContent($dynamicContent=false) {

			if( ! View::created() ) { View::create(); }
			
			View::assign('dynamicDebug', $dynamicContent);
			View::assign('debugGet',self::$debugGet );
			View::assign('debugPost',self::$debugPost );
			View::assign('debugObjects', array_merge(
							array(	self::$debugSession,
									self::$debugServer,
									self::$debugRequest,
									self::$debugLegacyRequest,
								),
								self::$_debugInfo
							)
						);
							
			$strFile = file_get_contents( constant('FRAMEWORK_PATH') . '/templates/debug/debug.tpl' );

			View::getSmarty()->_compile_source('evaluated template debug getContent', $strFile, $compiled);

			ob_start();
			View::getSmarty()->_eval('?>' . $compiled);
			$strContents = ob_get_contents();
			ob_end_clean();

			return $strContents;
		}
		
		/**
		 * Dumps in text the given variable using var_dump() -- used to show
		 * values in the framework debug system.
		 *
		 * @param mixed $mixed The variable to dump
		 * @return string The output of var_dump on the variable $mixed
		 */
		public static function varDump($mixed = null) {
			$strContent = var_export($mixed, true);
			$strContent = htmlentities($strContent);
			return $strContent;
		}
		
		public static function displayError($strTitle,$strMessage,$strFinePrint) {
			echo "<strong>{$strTitle}</strong><br/>{$strMessage}<br/><small>{$strFinePrint}</small>\n";
		}


		
		/**
		 * The function debugBacktrace() moves back through the call stack and
		 * reports all of the call sequences to aid in the debugging process.
		 * @return string The XHTML compliant backtrace for user display
		 */
		private static function getDebugBacktrace() {

			if( ! class_exists('Smarty') ) {
				// Setup Smarty: Class Smarty does not exist because the view object has not been parsed; therefore smarty should be loaded directly.
				require_once( constant('FRAMEWORK_PATH') . '/includes/Smarty-2.6.18/libs/Smarty.class.php' );
			}

			$smarty = new Smarty();

			$strFile = file_get_contents( constant('FRAMEWORK_PATH') . '/templates/debug/debug.backtrace.tpl' );

			$debugArray = debug_backtrace();
			
			// Three trace calls build up from the error framework - remove them
			array_shift($debugArray);
			array_shift($debugArray);
			array_shift($debugArray);
				
			$strRetVal = '';

			foreach( $debugArray as $da ) {
				foreach( $da['args'] as &$arg ) {
					if( ! is_string( $arg ) && ! is_int( $arg ) && ! method_exists($arg,'__toString') ) {
						$arg = '[type ' . gettype($arg) . '] missing __toString()';
					}
				}
				$smarty->_compile_source('evaluated template', $strFile, $compiled);
				$smarty->assign('debugBacktrace',$da);
				ob_start();
				$smarty->_eval('?>' . $compiled);
				$strRetVal .= ob_get_contents();
				ob_end_clean();
			}

			return $strRetVal;
		}
	}

?>