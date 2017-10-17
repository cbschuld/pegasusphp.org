<?php
/**
 * Class Request holds all of the HTTP GET and POST requests from the users
 * within the framework.  All controller objects within the framework will
 * have access to the common request object which stores protected values
 * of all the GET and POST objects.
 *
 * @package PegasusPHP
 *
 */

if( ! defined('URL_VAR') ) { define( 'URL_VAR', 'pq' ); }

class Request {
	private static $_pRequest = array();
	private static $_pOriginalRequest = array();

	/**
	 * Obtains all of GET or POST values from the user and loads them into
	 * this instance.
	 */
	public static function create() {
		if( get_magic_quotes_gpc() ) {
			array_walk( $_POST, array('Request','array_stripslashes' ) );
			array_walk( $_GET, array('Request','array_stripslashes' ) );
			array_walk( $_COOKIE, array('Request','array_stripslashes' ) );
		}
		self::set('module','');
		self::set('action','');

		// -----------------------------------------
		// Determine our request mode
		if( isset($_SERVER['REQUEST_METHOD']) && $_SERVER['REQUEST_METHOD'] == 'POST' ) {
			// Using array_merge allows for post REQUEST to have GET values on the URL
			//
			// array_merge() merges the elements of one or more arrays together
			// so that the values of one are appended to the end of the previous
			// one. It returns the resulting array.
			//
			// If the input arrays have the same string keys, then the later
			// value for that key will overwrite the previous one. If, however,
			// the arrays contain numeric keys, the later value will not
			// overwrite the original value, but will be appended.
			//
			self::$_pRequest = array_merge($_GET,$_POST);
			self::$_pOriginalRequest = array_merge($_GET,$_POST);
		} else {
			self::$_pRequest =& $_GET;
			self::$_pOriginalRequest =& $_GET;
		}

		// -----------------------------------------------------------------
		// The first goal is to process any SEO URL items.  SEO URL items are
		// parsed and delivered via Apache's MOD_REWRITE using a similar set
		// of commands to this example:
		//
		//     RewriteEngine on
		//     RewriteCond $1 !^/?(index\.php|(.*)\.css|images|scripts|robots\.txt)
		//     RewriteRule ^(.*)$ /index.php/$1 [L]
		//
		//
		if( isset($_SERVER['PATH_INFO']) ) {

			$segmentList = explode('/', $_SERVER['PATH_INFO']);

			// Any value will yeild a single entry
			if( count( $segmentList ) > 1 ) {

				// When the PATH_INFO explodes the the first value at
				// location zero will contain an empty string.
				assert( '' === (string)$segmentList[0]);
				//
				// Segment 0 is the 'module' value
				// Segment 1 is the 'action' value
				// Segment 2+ are the values after module/action
				//
                $count = count($segmentList);
				if( $count > 1 ) {
					self::set('segment0', $segmentList[1] );
					self::set('module', $segmentList[1] );
				}
				if( $count > 2 ) {
					self::set('segment1', $segmentList[2] );
					self::set('action', $segmentList[2] );
				}
				for( $iSegment = 3, $count = count($segmentList); $count > $iSegment; $iSegment++ ) {
					self::set('segment' . ($iSegment - 1 /* -1 due to loading slash */ ), $segmentList[$iSegment] );
				}
			}
		}

		//
		// -----------------------------------------------------------------
		// Uncompress the framework URL variable if it exists and setup
		// all of the properties of the request
			
		// BASE64 NOTES: In the URL and Filename safe variant,
		// character 62 (0x3E) is replaced with a "-" (minus sign)
		// and character 63 (0x3F) is replaced with a "_" (underscore).
			
		foreach( self::$_pRequest as $key => $value ) {
			if( $key == constant('URL_VAR') ) {
				$aOAF = unserialize( gzuncompress( base64_decode( str_replace("_", "/", str_replace("-", "+", urldecode($value) ) ) ) ) );
				if( is_array($aOAF) ) {
					foreach( $aOAF as $aKey => $aValue ) {
						self::set($aKey,$aValue);
					}
				}
			} else {

				// NOTE:
				// Using Multiple Selects there is a chance you may recieve
				// a key/value with a value of an array.
				if( is_array($value) ) {
					self::set($key,$value);
				}
				else {
					self::set($key,strip_tags($value));
				}
			}
		}

		// -----------------------------------------------------------------
		// Setup our Module and Action Values
		if( self::exists('m') && self::module() == '' ) {
			self::set('module',self::get('m'));
		}
		if( self::exists('a') && self::action() == '' ) {
			self::set('action',self::get('a'));
		}
	}
	
	public static function getOriginalArray() {
		return self::$_pOriginalRequest;
	}
	
	private static function array_stripslashes( &$item, $key ) {
		if( is_array($item) ) {
			array_walk($item, array('Request','array_stripslashes' ) );
		}
		elseif( ! is_object( $item ) ) {
			$item = stripslashes( $item );
		}
	}
	
	/**
	 * Returns a list of all of the Request Keys included in this object
	 * @return array An array of all of the reqest keys in this object
	 */
	public static function getKeys() {
		return array_keys(self::$_pRequest);
	}

	/**
	 * Constructs a request parameter based on the key/value array given
	 * by the caller.  Example:
	 *
	 * <pre>Request::createRequest(array('module'=>'login'));</pre>
	 *
	 * @param array $params Key/Value pair used to build the request.
	 * @return string The encoded request string (ex. oaf=398AB33ACB)
	 */
	public static function createRequest($params=array()) {

		// BASE64 NOTES: In the URL and Filename safe variant,
		// character 62 (0x3E) is replaced with a "-" (minus sign)
		// and character 63 (0x3F) is replaced with a "_" (underscore).

		$strRetVal = '';

		if( is_array($params) && count($params) > 0 ) {
			$aParamList = array();
			foreach( $params as $key => $value ) {
				$aParamList[$key] = $value;
			}
			$encodedObj = urlencode( str_replace("+", "-", str_replace("/", "_", base64_encode( gzcompress( serialize($aParamList) ) ) ) ) );
			$strRetVal = constant('URL_VAR') . "={$encodedObj}";
		}
		return( $strRetVal );
	}

	public static function exists($k) {
		return( array_key_exists($k,self::$_pRequest) );
	}

	public static function isEmpty($k) {
		return(  ! self::exists($k) || ( self::get($k) == '' ) );
	}

	public static function get($k,$bRaw=false) {
		$retVal = null;
		if( self::exists($k) ) {
			$retVal = ( $bRaw ? $_REQUEST[$k] : self::$_pRequest[$k] );
		}
		return( $retVal );
	}
	public static function set($k,$v) {
		self::$_pRequest[$k] = $v;
	}
	public static function explode($k) {
		return explode(',',self::get($k));
	}
	public static function isGet() {
		return ( $_SERVER['REQUEST_METHOD'] == 'GET');
	}
	public static function isPost() {
		return ( $_SERVER['REQUEST_METHOD'] == 'POST');
	}
	public static function isPut() {
		return ( $_SERVER['REQUEST_METHOD'] == 'PUT');
	}
	public static function isDelete() {
		return ( $_SERVER['REQUEST_METHOD'] == 'DELETE');
	}
	
	public static function getObject() { return self::$_pRequest; }
	
	public static function module($v=null) {
		if( $v != null ) { self::set('module',$v); } 
		return self::get('module');
	}  
	public static function action($v=null) {
		if( $v != null ) { self::set('action',$v); } 
		return self::get('action');
	}  
}

?>