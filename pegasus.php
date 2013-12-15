<?php
	
	define( 'PEGASUS_VERSION', '0.9.0 BETA' );

	/**
	 * Pegasus PHP Application Framework
	 * 
	 * The Pegasus PHP Application Framework...
	 *
	 * @copyright  TBA
	 * @license    TBA
	 * @version    CVS: $Id: pegasus.php,v 1.37 2012/10/05 21:34:15 cbschuld Exp $
	 * @link       
	 * @since      
	 */

	// Smarty requires magic_quotes_runtime to be turned off to operate properly
	if( get_magic_quotes_runtime() != 0 ) {
		exit( 'ERROR: PegasusPHP requires magic_quotes_runtime to be turned off to operate properly' );
	}
	
	if( ! defined('PROJECT_NAME') )				{ define( 'PROJECT_NAME', 'pegasus_app' ); }
	if( ! defined('BASE_PATH') )				{ define( 'BASE_PATH', dirname(__FILE__) ); }
	if( ! defined('FRAMEWORK_PATH') )			{ define( 'FRAMEWORK_PATH', dirname(__FILE__) . '/' ); }
	if( ! defined('CACHE_PATH') )				{ define( 'CACHE_PATH', constant('BASE_PATH') . '/cache/' ); }
	if( ! defined('URL_PATH') )					{ define( 'URL_PATH', '/pegasus' ); }
	if( ! defined('URL_VAR') )					{ define( 'URL_VAR', 'pq' ); }
	if( ! defined('USER_VAR') )					{ define( 'USER_VAR', 'user' ); }
	if( ! defined('USER_STATUS_VAR') ) 	        { define( 'USER_STATUS_VAR', 'pegasusUserLoggedIn' ); }
	if( ! defined('OFFICE_VAR') )				{ define( 'OFFICE_VAR', 'office' ); }
	if( ! defined('COMPANY_VAR') )				{ define( 'COMPANY_VAR', 'company' ); }
	if( ! defined('ASSERT_ENABLED') )			{ define( 'ASSERT_ENABLED', true ); }
	if( ! defined('SMARTY_CONTAINER_PAGE') )	{ define( 'SMARTY_CONTAINER_PAGE', 'container.tpl' ); }
	if( ! defined('SMARTY_CONTAINER_VALUE') ) 	{ define( 'SMARTY_CONTAINER_VALUE', 'content' ); }
	if( ! defined('DISABLE_PROPEL') ) 			{ define( 'DISABLE_PROPEL', false );	}
	if( ! defined('PROPEL_NAME') )				{ define( 'PROPEL_NAME', strtolower( constant('PROJECT_NAME') ) );	}
	if( ! defined('PROPEL_BUILD_PATH') )		{ define( 'PROPEL_BUILD_PATH',  constant('BASE_PATH') . '/propel/build' ); }
	if( ! defined('PROPEL_CONF_NAME') )			{ define( 'PROPEL_CONF_NAME', strtolower(constant('PROPEL_NAME')) . '-conf.php' ); }
	if( ! defined('FPDF_FONTPATH') )            { define( 'FPDF_FONTPATH', constant('FRAMEWORK_PATH') . '/includes/fpdf153/font/'); }
	if( ! defined('TTF_DIR' ) )                 { define( 'TTF_DIR', constant('FRAMEWORK_PATH') . '/includes/fonts/'); }

	if( ! defined('DATELONG' ) )				{ define( 'DATELONG', 'l, F jS, Y'); }
	if( ! defined('TIMELONG' ) )				{ define( 'TIMELONG', 'g:i A'); }
	
	date_default_timezone_set("America/Phoenix");
	spl_autoload_register("include_object");

	require_once( constant('FRAMEWORK_PATH') . 'objects/Object.php' );
	require_once( constant('FRAMEWORK_PATH') . 'objects/Pegasus.php' );

	Pegasus::timeStart();
	
	if( Pegasus::isDebug() ) {
		$__DEBUG = new Debug();
	}

	// -------------------------------------------------------------------------
	// Validate our Propel Configuration file is in the defined location
	//	
	if( ! constant('DISABLE_PROPEL') ) {

		if( ! file_exists(	constant('PROPEL_BUILD_PATH') . '/conf/' . constant('PROPEL_CONF_NAME'))) {
			Pegasus::error(
				'Propel Include Error',
				'Unable to locate Propel Configuration File (' . constant('PROPEL_BUILD_PATH') . '/conf/' . constant('PROPEL_CONF_NAME') . ')',
				"(You may desire to disable propel for this project.  If so add the following to your project: define('DISABLE_PROPEL',true);  Or you can alter the value of constant 'PROPEL_BUILD_PATH' to point to the propel 'build' directory in your project.)"
			);
		}
		else {
	
			// -------------------------------------------------------------------------
			// NOTE: Internally propel assumes all of their runtime objects will be including using relative paths based on the include_path.
			set_include_path(	get_include_path() .
								constant('PATH_SEPARATOR') .
								constant('PROPEL_BUILD_PATH') . '/classes/'
							);
				
			if( !defined('PROPEL_RUNTIME_PATH') || constant( 'PROPEL_RUNTIME_PATH' ) == '' ) {
				define( 'PROPEL_RUNTIME_PATH','propel' );
			}
			require_once( constant('PROPEL_RUNTIME_PATH').'/Propel.php' );
			Propel::init( constant('PROPEL_BUILD_PATH') . '/conf/' . constant('PROPEL_CONF_NAME') );
		}
	}

	// Request, Session and View require access to the global _PEGASUS object
	require_once( constant('FRAMEWORK_PATH') . 'objects/Request.php' );
	require_once( constant('FRAMEWORK_PATH') . 'objects/Session.php' );
	
	if( defined('USE_DWOO') && constant('USE_DWOO') ) {
		require_once( constant('FRAMEWORK_PATH') . 'objects/ViewDwoo.php' );
	}
	else if( defined('USE_SMARTY3') && constant('USE_SMARTY3') ) {
		require_once(dirname(__FILE__).'/includes/Smarty-3.1.15/libs/Smarty.class.php');
		require_once(constant('FRAMEWORK_PATH') . 'objects/ViewSmarty3.php');
	}
	else {
		require_once(dirname(__FILE__).'/includes/Smarty-2.6.28/libs/Smarty.class.php');
		require_once(constant('FRAMEWORK_PATH') . 'objects/ViewSmarty.php');
	}
	
	/*
	 * Convenience include function which allows users to easily include their
	 * objects within the framework.  The include_object() function
	 * looks a specific include locations in order to determine where the object
	 * exists.  When the object is found it is included.  The function looks for
	 * objects in the following order:
	 *
	 * 1) Within the framework under the FRAMEWORK_PATH/objects/<br/>
	 * 
	 * NOTE: All objects within the framework must have the following naming
	 * convention: [class_or_object_name].class.php
	 *
	 * @param string $strObjectName The name of the object to include
	 * @param boolean $bDisplayError If True the execution will stop and
	 * display an error.  If False no message is displayed and the object
	 * was not included.  (If False check return value).
	 * @return boolean True if the object was included, otherwise false.
	 */
	function include_object($strObjectName) {
		
		global $__INCLUDES;
		if( ! is_array($__INCLUDES) ) {
			$__INCLUDES = array();
		}
		
		$classObjectName = str_replace('\\', '/', $strObjectName);

		if( ! array_key_exists($classObjectName, $__INCLUDES) ) {
			$aIncludePath = array();
			$aIncludePath[] = constant('BASE_PATH') . '/objects/' . $classObjectName . '.php';
			$aIncludePath[] = constant('FRAMEWORK_PATH') . '/objects/' . $classObjectName . '.php';
			// Check each path/filename for a valid include
			for( $i = 0; $i < count($aIncludePath); $i++ ) {
				if( file_exists( $aIncludePath[$i] ) ) {
					require_once($aIncludePath[$i]);
					$__INCLUDES[$classObjectName] = true;
					return true;
				}
			}
		}
		return false;
	}

	
	/**
	 * Convenience function to dump debug information into the browser based on
	 * the results of var_dump().
	 */
	function dump($mixed) {
		echo '<pre>';
		var_dump( $mixed );
		echo '</pre>';
	}

	/**
	 * assertWorker is an assert callback to manage the errors within the
	 * framework.
	 * @param string $file The filename that caused the popped assert
	 * @param integer $line The line number where the popped assert is at
	 * @param string $code The code that caused the assert to pop
	 *
	 */
	function assertWorker($file, $line, $strTrigger) {
		if( $strTrigger == '' ) {
			$strTrigger = "WARNING: do not forget to wrap your assert " .
						  " conditions with single quotes.  " .
						  "ex assert('\$bValue')";
		}
		
		$strMessage = 	"ASSERT Failed at line {$line} in '{$file}' ( {$strTrigger} )";
		if( Pegasus::isDebug() ) {
			Pegasus::error( 'ASSERT Failed', $strMessage );
		}
		else {
			die( $strMessage );
		}
	}

	// Activate assert and make it quiet
	assert_options(ASSERT_ACTIVE, 		constant('ASSERT_ENABLED') );
	assert_options(ASSERT_WARNING, 		constant('ASSERT_ENABLED') );
	assert_options(ASSERT_QUIET_EVAL, 	constant('ASSERT_ENABLED') );

	// Set up the assert callback
	assert_options(ASSERT_CALLBACK, 'assertWorker');
	
?>
