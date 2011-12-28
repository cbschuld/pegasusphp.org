<?php

function pegasus_get_template ($tpl_name, &$tpl_source, &$smarty_obj) {
	if( file_exists( constant('FRAMEWORK_PATH') . '/templates/' . $tpl_name ) ) {
		$tpl_source = file_get_contents( constant('FRAMEWORK_PATH') . '/templates/' . $tpl_name );
		return true;
	}
	return false;
}

function pegasus_get_timestamp($tpl_name, &$tpl_timestamp, &$smarty_obj) {
	if( file_exists( constant('FRAMEWORK_PATH') . '/templates/' . $tpl_name ) ) {
		$tpl_timestamp = filemtime( constant('FRAMEWORK_PATH') . '/templates/' . $tpl_name );
		return true;
	}
	return false;
}

function pegasus_get_secure($tpl_name, &$smarty_obj) {
	// assume all templates are secure
	return true;
}

function pegasus_get_trusted($tpl_name, &$smarty_obj) {
	// not used for templates
}
/**
 * 
 * @author cbschuld
 * @package PegasusPHP
 */
class RequestPHP4WrapperForSmarty {
	public function get($k) { return Request::get($k); }
	public function explode($k) { return Request::explode($k); }
	public function set($k,$v) { return Request::set($k,$v); }
	public function exists($k) { return Request::exists($k); }
	public function action() { return Request::action(); }
	public function module() { return Request::module(); }
}
/**
 * 
 * @author cbschuld
 * @package PegasusPHP
 */
class SessionPHP4WrapperForSmarty {
	public function get($k) { return Session::get($k); }
	public function set($k,$v) { return Session::set($k,$v); }
	public function exists($k) { return Session::exists($k); }
	public function userLoggedIn() { return Session::userLoggedIn(); }
}

/**
 * 
 * @author cbschuld
 * @package PegasusPHP
 */
class View {

	private static $_smarty = null;
	private static $_smartyViewWrapper;
	
	private static $_smartyContainerFilename;
	private static $_bShowContainer = true;

	private static $_bCompileInMemory = false;
	
	private static $_requestPHP4WrapperForSmarty;
	private static $_sessionPHP4WrapperForSmarty;
	
	
	public static function created() { return self::$_smarty != null; }
	
	public static function create() {
		
		self::$_smarty = new Smarty();
		// register the resource name "pegasus"
		self::$_smarty->register_resource("pegasus", array("pegasus_get_template", "pegasus_get_timestamp", "pegasus_get_secure","pegasus_get_trusted"));
		self::$_smarty->plugins_dir = array( 'plugins', constant('FRAMEWORK_PATH') . '/includes/smarty-plugins/');							
		
		self::$_smartyContainerFilename = constant('SMARTY_CONTAINER_PAGE');

		// Map PHP's stripslashes function to a Smarty modifier
		self::$_smarty->register_modifier( 'stripslashes', 'stripslashes' );
		
		self::$_smarty->assign('lbr', '{'); // lbr stands for left (opening) bracket
		self::$_smarty->assign('rbr', '}'); // rbr stands for right (closing) bracket
 		
		// Pegasus is a global variable instance and referenced directly here
		self::$_smarty->assign_by_ref('pegasus', new Pegasus() );
		
		if( Pegasus::isDebug() ) {
			Debug::$debugPost->setObject($_POST);
			Debug::$debugGet->setObject($_GET);
			Debug::$debugSession->setObject($_SESSION);
			Debug::$debugServer->setObject($_SERVER);
//			if( Session::created() ) {
//				if( Session::exists('legacyPegasusSession') ) {
//					Debug::$debugLegacyRequest->setObject(Session::getObject('legacyPegasusSession'));
//				}
//			}
//			Debug::$debugRequest->setObject(Request::getObject());
		}

		
		self::$_requestPHP4WrapperForSmarty = new RequestPHP4WrapperForSmarty();
		self::$_sessionPHP4WrapperForSmarty = new SessionPHP4WrapperForSmarty();
		self::$_smarty->assign_by_ref('request',self::$_requestPHP4WrapperForSmarty);
		self::$_smarty->assign_by_ref('session',self::$_sessionPHP4WrapperForSmarty);
		

		// If the user has been encapsulated setup the existence in the view
		if( Session::created() && Session::userLoggedIn() ) {
			self::assign(constant('USER_VAR'), Session::get(constant('USER_VAR')));
		}
		else {
			self::assign(constant('USER_VAR'), null);
		}
		
		// Assign and set the standard module and action values
		self::assign('module', Request::get('module') );
		self::assign('action', Request::get('action') );
		
	}

	public static function getSmarty() {
		return self::$_smarty;
	}
	
	public static function compile_dir($v=null) {
		if( $v != null ) { self::$_smarty->compile_dir = $v; } 
		return self::$_smarty->compile_dir;
	}  
	public static function template_dir($v=null) {
		if( $v != null ) { self::$_smarty->template_dir = $v; } 
		return self::$_smarty->template_dir;
	}  
	
	
	public static function setCompileInMemory($bValue=true) {
		self::$_bCompileInMemory = $bValue;
	}
	public static function append($k,$v) {
		self::$_smarty->append($k,$v);
	}
	/**
	 * Assign a value to a key in the smarty template
	 *
	 * @param string $key
	 * @param string $value
	 */
	public static function assign($key,$value) {
		self::$_smarty->assign($key,$value);
	}
	
	public static function getDebugBacktrace() {
		$dbgTrace = debug_backtrace();
		$dbgMsg = "";
		foreach($dbgTrace as $dbgIndex => $dbgInfo) {
			$dbgMsg .= "\t at {$dbgIndex} ";
			if( isset($dbgInfo['file']) ) {
				$dbgMsg .= "{$dbgInfo['file']} ";
			}
			if( isset($dbgInfo['line']) ) {
				$dbgMsg .= "(line {$dbgInfo['line']})";
			}
			$dbgMsg .= "-> {$dbgInfo['function']}(";
			$dbgMsg .= ")\n";
		}
		$dbgMsg .= "\n";
		return $dbgMsg;
	}
		
	/**
	 * Assign a template result to a key in the smarty template
	 *
	 * @param string $key
	 * @param string $t
	 */
	public static function assignTemplate($key,$t) {
		self::assign($key,self::fetch($t));
	}
	
	/**
	 * Assign a value to a key in the smarty template
	 *
	 * @param string $key
	 * @param string $value
	 */
	public static function assign_by_ref($key,&$value) {
		self::$_smarty->assign_by_ref($key,$value);
	}
	public static function template_exists($t) {
		return self::$_smarty->template_exists($t);
	}
	
	public static function templateExists($t) {
		return self::$_smarty->template_exists($t);
	}
	
	/**
	 * setContainerTemplate() allows for the altering of the master
	 * container for the site.  By default at class initiation the default
	 * template is set to the constant 'SMARTY_CONTAINER_PAGE'.  This
	 * method allow the caller to alter the value of the 'master' container.
	 *
	 * @param string $strTemplate Name of the template to use as the master
	 * container for the next call to showContainer()
	 */
	public static function setContainerTemplate( $strTemplate ) {
		if( self::$_smarty->template_exists( $strTemplate ) ) {
			self::$_smartyContainerFilename = $strTemplate;
		}
	}
	
	/**
	 * setShowContainer() allows you to suspend the main container from being
	 * displayed.
	 * @param bool $bShowContainer If set the main container is shown otherwise
	 * it is not shown
	 */
	public static function setShowContainer($bShowContainer=true) {
		self::$_bShowContainer = $bShowContainer;
	}
	
	/**
	 * overloads smarty's fetch to allow for a "memory" compile (a raw eval on the content) to
	 * allow for error messages, built in demos/tests which do not have access to a writable path
	 */
	public static function fetch($resource_name, $cache_id = null, $compile_id = null, $display = false) {
		$ret = null;
		$compiled = null;

		if( self::$_bCompileInMemory ) {

			// No templates_c (compile_dir) is available - drop the template
			// into a variable and compute it via eval()
			$params = array('resource_name' => $resource_name, 'quiet'=>true, 'get_source'=>true );
			self::$_smarty->_fetch_resource_info($params);
			self::$_smarty->_compile_source($resource_name, $params['source_content'], $compiled);
			
			ob_start();
			self::$_smarty->_eval('?>' . $compiled);
			$ret = ob_get_contents();
			ob_end_clean();
		}
		else {
			$ret = self::$_smarty->fetch($resource_name, $cache_id, $compile_id, $display);
		}
		return $ret;
	}
	
	/**
	 * FinalizeTemplates performs the final assigns and clean up necessary
	 * to display the container.  This is autmomatically called by
	 * showContainer().
	 */
	public static function finalizeTemplates() {

		if( Pegasus::isDebug() ) {
//			Debug::$debugPost->setObject($_POST);
//			Debug::$debugGet->setObject($_GET);
//			Debug::$debugRequest->setObject($this->_request);
			Debug::$debugSession->setObject($_SESSION);
//			Debug::$debugServer->setObject($_SERVER);
//			
//			if( Session::active() ) {
//				if( Session::exists('legacyPegasusSession') ) {
//					Debug::$debugLegacyRequest->setObject(Session::getObject('legacyPegasusSession'));
//				}
//				$this->_session->setObject('legacyPegasusSession', $this->_request );
//			}
		}
		Pegasus::timeEnd();
	}

	/**
	 * Displays a give template page within the container.
	 *
	 * @param string $strTemplateName The filename of the template to
	 *                                display
	 */
	public static function displayPage($strTemplateName) {
		self::$_smarty->assign( SMARTY_CONTAINER_VALUE, self::fetch($strTemplateName));
		self::showContainer();
	}

	/**
	 * Displays the container template set the caller.
	 */
	public static function showContainer() {
		self::finalizeTemplates();
		if( self::$_bShowContainer ) {
			echo self::fetch( self::$_smartyContainerFilename );
		}
		else {
			echo self::$_smarty->get_template_vars( 'content' );
		}
	}
}

?>