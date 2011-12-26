<?php
	/**
	 * Class controller manages the operation and user requests of the MVC
	 * model in the framwork.  The controller reacts to different user requests
	 * and provides the correct view(s) back to the user.
	 *
	 * The controller class has direct access to the REQUEST object, the VIEW
	 * object, all of the data objects (Propel Objects).
	 *
	 * @var $view View
	 * @package PegasusPHP
	 */
	class Controller {

		/**
		 * Empty Constructor
		 */
		public function __construct() {}
		
		/**
		 * Empty Destructor
		 */
		public function __destruct() {}

		/**
		 * Process is the primary processing function, it calls processGet() or
		 * processPost() based on what mode the request object is in.
		 */
		public function process() {
			if( Request::isPost() ) {
				$this->processPost();
			} else {
				$this->processGet();
			}
		}

		/**
		 * Each controller should override this method so they can each control
		 * user requests.  The proper way to override this function is to only
		 * call the parent when the request is not handled / processed by
		 * extended controller.
		 *
		 * This function is called by process() when the request is in a
		 * GET mode of processing.
		 *
		 * The default operation of this processGet() is to check to see if the
		 * user wants to display the phpinfo() information.  Next, a value of
		 * 't' on the request line will do a "template show" based on the value
		 * of the 't' option:
		 * <br/>
		 * Example:
		 * <br/>
		 * To show a template called 'someinformation.tpl' make the following
		 * call:
		 * <br/>
		 * <code>http://MYSITE/?t=someinformation</code>
		 * <br/>
		 * <br/>
		 * If the 't' parameter is not defined the processGet() will look for
		 * "main" templates to display in the following order:<br/>
		 * <br/>
		 * main.tpl, content.tpl, index.tpl
		 * <br/>
		 * <br/>
		 * If all of these responses are not processed or found an error is
		 * displayed.
		 *
		 */
		public function processGet() {

			// A call to SITE/?phpinfo will dump the PHP info ONLY if the
			// constant DEBUG is set.

			if( defined('DEBUG') && constant('DEBUG') && Request::exists('phpinfo') ) {
				phpinfo();
			}
			else {
				
				if( Request::module() == 'show' ) {

					$strTemplate = Request::action() . '.tpl';

					if( View::template_exists($strTemplate) ) {
						View::assign( constant('SMARTY_CONTAINER_VALUE'), View::fetch($strTemplate) );
					}
				}
				else {
					if( View::template_exists('main.tpl') ) {
						View::assign(constant('SMARTY_CONTAINER_VALUE'), View::fetch('main.tpl') );
					}
					else if( View::template_exists('content.tpl') ) {
						View::assign(constant('SMARTY_CONTAINER_VALUE'), View::fetch('content.tpl') );
					}
					else if( View::template_exists('index.tpl') ) {
						View::assign(constant('SMARTY_CONTAINER_VALUE'), View::fetch('index.tpl') );
					}
				}
			}
		}

		/**
		 * Each controller should override this method so they can each control
		 * user requests.  The proper way to override this function is to only
		 * call the parent when the request is not handled / processed by
		 * extended controller.
		 *
		 * This function is called by process() when the request is in a
		 * POST mode of processing.
		 *
		 * This "parent" function does nothing but display an error.
		 *
		 */
		public function processPost() {
			return false;
		}
		
		/**
		 * bounce() allows you to redirect the specific user to a new location
		 * based on the location passed in as a parameter.
		 * 
		 * Example Calls:
		 * <code>
		 * $controllerObject->bounce('/mypath');
		 * $controllerObject->bounce( array( 'module' => 'mymod', 'action' => 'myaction' ) );
		 * $controllerObject->bounce( '/mypath/myaction', array( 'id' => 17, 'subid' => 37 ) );
		 * </code>
		 *
		 * @param string $strLocation A specific location to reflect the user
		 * too.  In most cases this will be index.php so we have a default value
		 * of ''.
		 *
		 */
		public function bounce() {
			
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
	
	class SiteMapBranch {
		private $_title;
		private $_url;
		public function SiteMapBranch($title,$url) { $this->_title = $title; $this->_url = $url; }
		public function getTitle() { return $this->_title; }
		public function setTitle($v) { $this->_title = $v; }
		public function getUrl() { return $this->_url; }
		public function setUrl($v) { $this->_url = $v; }
		public function getMarkup() { if( $this->_url == '' ) { return $this->_title; } else { return "<a href=\"{$this->_url}\">{$this->_title}</a>"; } }
	}
?>