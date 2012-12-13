<?php
	/**
	 * Pegasus
	 *
	 * Timezone List is available from : http://php.net/manual/en/timezones.php
	 *
	 * @package PegasusPHP
	 */
	
	class Pegasus {
	
		const SESSION_ERROR_VARIABLE = 'pegasusNVError';
		const SESSION_MESSAGE_VARIABLE = 'pegasusNVMessage';

		private static $_timeStart = 0;
		private static $_timeLastVerboseCall = 0;
		private static $_timeProcessingStart = 0;
		private static $_timeProcessingEnd = 0;
	
		private static $_strClientTimeZone = 'America/Phoenix';
		private static $_strServerTimeZone = 'America/Phoenix';
	
		private static $_timeClient = 0;
	
		private static $_settings;
	
		private static $_bErrorShow = true;
		private static $_bErrorLog = true;
	
		private static $_aJavaScriptIncludes = array();
		private static $_aCSSIncludes = array();
	
		private static $_request = null;
		private static $_view    = null;
		private static $_session = null;
	
		public static function returnJson( $array ) {
			echo json_encode( $array );
			exit;
		}
		
		public static function getSettings() { return self::$_settings; }
		public static function loadSettings($filename) {
			self::$_settings = simplexml_load_file($filename);
			View::assign('settings',self::$_settings);
		}
	
		public static function getError() {
			if( ! Session::created() ) { Session::create(); }
			return nl2br(Session::get(self::SESSION_ERROR_VARIABLE));
		}
		public static function setError($v) {
			if( ! Session::created() ) { Session::create(); }
			Session::set(self::SESSION_ERROR_VARIABLE,$v);
		}
		public static function clearError() {
			if( ! Session::created() ) { Session::create(); }
			Session::reset(self::SESSION_ERROR_VARIABLE);
		}
		public static function errorSet() {
			if( ! Session::created() ) { Session::create(); }
			return Session::exists(self::SESSION_ERROR_VARIABLE);
		}
		public static function getMessage() {
			if( ! Session::created() ) { Session::create(); }
			return nl2br(Session::get(self::SESSION_MESSAGE_VARIABLE));
		}
		public static function setMessage($v) {
			if( ! Session::created() ) { Session::create(); }
			Session::set(self::SESSION_MESSAGE_VARIABLE,$v);
		}
		public static function clearMessage() {
			if( ! Session::created() ) { Session::create(); }
			Session::reset(self::SESSION_MESSAGE_VARIABLE);
		}
		public static function messageSet() {
			if( ! Session::created() ) { Session::create(); }
			return Session::exists(self::SESSION_MESSAGE_VARIABLE);
		}
	
		private static function getCacheKey() {
			if( Pegasus::getSession()->exists('user') ) {
				print '';
			}
		}
		
		public static function isSSL() { return $_SERVER['SERVER_PORT'] == 443; }
		public static function forceSSL($bForce=true) {
			if( $bForce && ! self::isSSL() ) {
				self::bounce('https://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']);
			}
			else if( !$bForce && self::isSSL() ) {
				self::bounce('http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']);
			}
		}
	
		public static function thisUri() { return $_SERVER['REQUEST_URI']; }
	
		public static function getUserIp() { return $_SERVER['REMOTE_ADDR']; }
		public static function getUserHostname() { return gethostbyaddr($_SERVER['REMOTE_ADDR']); }
		public static function getServername() { return $_SERVER['SERVER_NAME']; }

		public static function isCached() {
			$cachePath = '/tmp/';
			$filename = md5( serialize( Pegasus::getRequest() ) ) . '.cache';
			return file_exists( $cachePath . $filename );
		}
	
		public static function getCache() {
			$cachePath = '/tmp/';
			$filename = md5( serialize( Pegasus::getRequest() ) ) . '.cache';
			return file_get_contents( $cachePath . $filename );
		}
	
		public static function setCache($v) {
			$cachePath = '/tmp/';
			$filename = md5( serialize( Pegasus::getRequest() ) ) . '.cache';
			return file_put_contents( $cachePath . $filename, $v );
		}
		
		public static function isSecure() { return ( ( ! isset($_SERVER['HTTPS']) || $_SERVER['HTTPS'] != 'on' || ! isset($_SERVER['HTTP_X_FORWARDED_PROTO']) || strtolower($_SERVER['HTTP_X_FORWARDED_PROTO']) != 'https' ) ? false : true ); }
	
	
	
		private static function pushJavaScript($javaScriptObj) {
			$found = false;
			$index = 0;
			while( !$found && $index < count(self::$_aJavaScriptIncludes) ) {
				$found = ( self::$_aJavaScriptIncludes[$index]->getFilename() == $javaScriptObj->getFilename() );
				if( $found && $javaScriptObj->getPriority() > self::$_aJavaScriptIncludes[$index]->getPriority() ) {
					self::$_aJavaScriptIncludes[$index]->setPriority($javaScriptObj->getPriority());
				}
				$index++;
			}
			if( !$found ) {
				array_unshift( self::$_aJavaScriptIncludes, $javaScriptObj );
			}
		}
	
		/**
		 * Private function for adding JavaScript and CSS includes so there are
		 * no duplicate entries.
		 *
		 * @param array $array The array to push the value on to
		 * @param mixed $pushvalue The value being pushed onto the array
		 */
		private static function array_push_nodup(&$array,$pushvalue) {
			$bInsert = true;
			foreach($array as $key => $v) {
				if($v == $pushvalue) {
					$bInsert = false;
				}
			}
			if( $bInsert ) {
				array_push( $array, $pushvalue );
			}
		}
	
		public static function sortJavaScript() {
			usort(self::$_aJavaScriptIncludes, array("JavaScriptFile", "compare"));
		}
	
		public static function addJavaScript( $filename, $priority=0, $post=false, $name='' ) {
			$js = new JavaScriptFile();
			$js->setFilename($filename)->setPriority($priority)->setPost($post)->setName($name);
			self::pushJavaScript($js);
		}
	
		public static function addCSS( $strFile ) {
			self::array_push_nodup( self::$_aCSSIncludes, $strFile );
		}
	
		public static function getJavaScript() { return self::$_aJavaScriptIncludes; }
		public static function getCSS() { return self::$_aCSSIncludes; }
	
	
	
		public static function isDebug() {
			return( defined('DEBUG') && DEBUG );
		}
	
		public static function getVersion() { return defined('PEGASUS_VERSION') ? constant('PEGASUS_VERSION') : 'n/a'; }
		public static function getAppVersion() { return defined('VERSION') ? constant('VERSION') : 'n/a'; }
	
		public static function getClientTimeZone() { return self::$_strClientTimeZone; }
		public static function setClientTimeZone( $v ) { self::$_strClientTimeZone = $v; }
	
		public static function getServerDateTimeGmt() { return self::$_serverDateTimeGmt; }
		public static function getServerDayLightSavings() { return self::$_bServerDayLightSavings; }
		public static function getServerTimeZone() { return self::$_strServerTimeZone; }
	
		public static function setServerDateTimeGmt( $v ) { self::$_serverDateTimeGmt = $v; }
		public static function setServerDayLightSavings( $v ) { self::$_bServerDayLightSavings = $v; }
		public static function setServerTimeZone( $v ) { self::$_strServerTimeZone = $v; }
	
		public static function createRequest() { self::$_request = new Request(); return self::$_request; }
		public static function getRequest() { if( self::$_request == null ) { Pegasus::createRequest(); } return self::$_request; }
		public static function setRequest( $v ) { self::$_request = &$v; }
	
		public static function createSession() { self::$_session = new Session(); return self::$_session; }
		public static function getSession() { if( self::$_session == null ) { Pegasus::createSession(); } return self::$_session; }
		public static function setSession( $v ) { self::$_session = &$v; }
	
	
		public static function getRelativePath() {
			$strPath = str_replace(	$_SERVER["QUERY_STRING"], '', $_SERVER["REQUEST_URI"] );
			$strRelative = str_replace('?', '', $strPath);
			return str_replace('?', '', $strPath);
		}
	
		public static function getAbsolutePath() {
			$strPath = str_replace(	$_SERVER["QUERY_STRING"], '', $_SERVER["REQUEST_URI"] );
			$strRelative = str_replace('?', '', $strPath);
			return "http://{$_SERVER["HTTP_HOST"]}{$strRelative}";
		}
	
		public static function start() {
			self::$_timeStart = microtime(true);
			self::$_timeLastVerboseCall = microtime(true);
		}
		public static function timeStart() {
			self::$_timeProcessingStart = microtime(true);
		}
		public static function timeEnd() {
			self::$_timeProcessingEnd = microtime(true);
		}
		public static function verbose($message) {
			$size = memory_get_usage(true);
			$units = array('B','KB','MB','GB','TB','PB');
			$memory = @round($size/pow(1024,($i=floor(log($size,1024)))),2).$units[$i];
			echo $message." (".number_format(microtime(true) - self::$_timeLastVerboseCall,3)."sec, ".number_format(microtime(true)-self::$_timeStart,3)."sec total, ".$memory.")\n";
			self::$_timeLastVerboseCall = microtime(true);
		}
		public static function log($strTitle, $strMessage='', $strFinePrint='', $bForce=false) {
			if( Pegasus::isDebug() || $bForce ) {
				error_log( "{$strTitle}: {$strMessage} " . ($strFinePrint != "" ? "({$strFinePrint})" : "" ), 0 );
			}
		}
	
		public static function fastlog($message) {
			$size = memory_get_usage(true);
			$units = array('B','KB','MB','GB','TB','PB');
			$memory = @round($size/pow(1024,($i=floor(log($size,1024)))),2).$units[$i];
			error_log( $message." (".number_format(microtime(true) - self::$_timeLastVerboseCall,3)."sec, ".number_format(microtime(true)-self::$_timeStart,3)."sec total, ".$memory.")" );
			self::$_timeLastVerboseCall = microtime(true);
		}
	
		public static function logex($strTitle, $strMessage='', $strFinePrint='') {
			error_log( "{$strTitle}: {$strMessage} " . ($strFinePrint != "" ? "({$strFinePrint})" : "" ), 0 );
		}
	
		
		public static function getProcessingTime() {
			if( self::$_timeProcessingEnd == 0 ) {
				return ( microtime(true) - self::$_timeProcessingStart );
			}
			return self::$_timeProcessingEnd - self::$_timeProcessingStart;
		}
		public static function getProcessingSeconds() {
			return number_format( self::getProcessingTime(), 4 );
		}
	
		public static function getServerDateTime($dateTime='now') {
			return new DateTime($dateTime, new DateTimeZone(self::$_strServerTimeZone));
		}
	
		public static function getClientDateTime($dateTime='now') {
			$dte = new DateTime($dateTime, new DateTimeZone(self::$_strServerTimeZone) );
			$dte->setTimeZone( new DateTimeZone(self::$_strClientTimeZone) );
			return $dte;
		}
	
		public static function error($strTitle,$strMessage,$strFinePrint='') {
			if( self::$_bErrorShow && self::isDebug() ) {
				Debug::displayError( $strTitle, $strMessage, $strFinePrint );
			}
			if( self::$_bErrorLog || ! self::isDebug() ) {
				error_log( "{$strTitle}: {$strMessage} " . ($strFinePrint != "" ? "({$strFinePrint})" : "" ), 0 );
			}
			if( session_id() != '' ) { session_write_close(); }
			die();
		}
	
		public static function sendFile($strPathFileToSend, $strDownloadFilename, $strMimeType='application/octet-stream', $strDisposition='attachment') {
			$bRetVal = false;
	
			// make sure the file exists before sending headers
			if( $fdl = @fopen( $strPathFileToSend, 'rb' ) ) {
				header("Cache-Control: ");// leave blank to avoid IE errors
				header("Pragma: ");// leave blank to avoid IE errors
				header("Content-type: {$strMimeType}");
				header("Content-Disposition: {$strDisposition}; filename=\"".$strDownloadFilename."\"");
				header("Content-length:".(string)(filesize($strPathFileToSend)));
				sleep(1);
				fpassthru($fdl);
	
				$bRetVal = true;
			}
			return( $bRetVal );
		}
	
		public static function bounce() {
	
			// bounce() accepts a single url location (string), a url location string
			// and an associative array (for building an encoded request object), or
			// it can accept a single associative array (for building an encoded
			// request object)
	
			$strLocation = '/';
	
			$args = func_get_args();
	
			// Single String Argument
			if( func_num_args() == 1 && !is_array( $args[0] ) ) {
				$strLocation = $args[0];
			}
			// Single Array Argument
			else if( func_num_args() == 1 && is_array( $args[0] ) ) {
				$strLocation = '?' . Request::createRequest( $args[0] );
			}
			// Single Array Argument
			else if( func_num_args() == 2 && !is_array( $args[0] ) && is_array( $args[1] ) ) {
				$strLocation = $args[0] . '?' . Request::createRequest( $args[1] );
			}
			else if( func_num_args() == 0 ) {
				$strLocation = $_SERVER['SCRIPT_URL'];
			}
			else {
				Pegasus::error('Invalid Bounce', 'An invalid HTTP bounce request was called');
			}
	
			header("Location: $strLocation");
	
			// Write back the session due to the reflection
			//if( $this->session instanceOf Session ) {
				//Session::flush();
			//}
			Session::flush();
	
			// NOTES:
			// Session data is usually stored after your script terminated
			// without the need to call session_write_close(), but as session
			// data is locked to prevent concurrent writes only one script may
			// operate on a session at any time. When using framesets together
			// with sessions you will experience the frames loading one by one
			// due to this locking. You can reduce the time needed to load all
			// the frames by ending the session as soon as all changes to
			// session variables are done.
	
			exit();
		}
	
	}

	/**
	 * JavaScriptFile
	 *
	 * @package PegasusPHP
	 */
	class JavaScriptFile {
		private $_filename = '';
		private $_name = '';
		private $_priority = 0;
		private $_post = false;
		public function getFilename(){ return $this->_filename; }
		public function setFilename($v){ $this->_filename = $v; return $this;}
		public function getName(){ return $this->_name; }
		public function setName($v){ $this->_name = $v; return $this;}
		public function getPriority(){ return $this->_priority; }
		public function setPriority($v){ $this->_priority = $v; return $this;}
		public function getPost(){ return $this->_post; }
		public function setPost($v){ $this->_post = $v; return $this;}
		public static function compare($a,$b) {
			return( $a->_priority < $b->_priority ? +1 : -1 );
		}
	}

	
	Pegasus::start();
	
?>
