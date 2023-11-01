<?php

/**
 * Class Session is a container class for all of the data stored within
 * $_SESSION.  Class Session allows access to the session in a framework
 * consistent manner.  All controller objects within the framework will
 * have access to the common session object.
 *
 * @package PegasusPHP
 */
class Session {

	public static function id($id='') { return ( $id == '' ? session_id() : session_id($id) ); }
	public static function created() { return ( session_id() != '' ); }
	
	/**
	 * Sets up this Session object
	 * @param string $optionalSessionName name of session
	 */
	public static function create($optionalSessionName="") {
		if( $optionalSessionName != "" ) {
			session_name("s_{$optionalSessionName}");
		}
		session_start();
	}
	
	public static function getDebug() {
		global $_SESSION;
		var_dump($_SESSION);
	}
	
	/**
	 * The functions setObject() and getObject() solve one of the issues php
	 * has when it persisting objects in the session.  If you load or
	 * start the session before you include the definitions for objects
	 * PHP will get confused when you attempt to use one of object methods
	 * or properties.
	 *
	 * The functions setObject() and getObject() get around this by storing
	 * the objects in a serialized fashion internally and allow the user to
	 * store and retrieve objects later than session_start() -- usually
	 * after the object definitions have been loaded.
	 *
	 * The setObject() stores an object in the session under the given name.
	 * @param string $k The name of the object - or how the object
	 * @param object $object The object to persist in the session
	 * will be stored within the session.  Use getObject(name) to retrieve
	 * the object.
	 */
	public static function setObject($k,$object) {
		self::set($k,serialize($object));
	}

	/**
	 * The functions setObject() and getObject() solve one of the issues php
	 * has when it persisting objects in the session.  If you load or
	 * start the session before you include the definitions for objects
	 * PHP will get confused when you attempt to use one of object methods
	 * or properties.
	 *
	 * The functions setObject() and getObject() get around this by storing
	 * the objects in a serialized fashion internally and allow the user to
	 * store and retrieve objects later than session_start() -- usually
	 * after the object definitions have been loaded.
	 *
	 * The setObject() stores an object in the session under the given name.
	 * @param string $k The name of the object to get.  Use
	 * setObject(name) to store or set the object.
	 * @param object $defaultObject The default object to return if the
	 * key name does not exist in this session.  This is helpful when
	 * checking to see if you did persist and object -- in this case you
	 * call getObject('name',new Object()) and you are garanteed a valid
	 * object.
	 * @return object The object you are 'getting'
	 */
	public static function getObject($k,$defaultObject=null) {
		
		$obj = $defaultObject;
		if( self::exists( $k ) && is_string( self::get($k) ) ) {
			$obj = unserialize(self::get($k));
		}
		return( $obj );
	}

	public static function removeObject($k) {
		self::reset($k);
	}

	public static function exists($k) {
		return( isset($_SESSION) && array_key_exists($k,$_SESSION) );
	}

	public static function isEmpty($k) {
		return( ! self::exists($k) || ( self::exists($k) && $_SESSION[$k] == '' ));
	}

	public static function get($k) {
		$ret = null;
		if( isset($_SESSION) && is_array($_SESSION) && array_key_exists($k,$_SESSION) ) {
			$ret = $_SESSION[$k];
		}
		return( $ret );
	}

	/**
	 * Sets the specific value in the session
	 *
	 * @param string $k The name of the key you want to set
	 * @param mixed $v The value you want to set the session value at
	 *                     strKey too
	 */
	public static function set($k,$v) {
		$_SESSION[$k] = $v;
	}

	/**
	 * The value at $strKey is reset or unset
	 * @param string $k The key value of the item to reset
	 */
	public static function reset($k) {
		unset($_SESSION[$k]);
	}

	/**
	 * The flush() method makes a call to close the session.
	 */
	public static function flush() {
		session_write_close();
	}
		
	/**
	 * The destroy() method closes the session.
	 */
	public static function destroy() {
		if(isset($_SESSION) && is_array($_SESSION)) {
			session_destroy();
			session_write_close();
			foreach ($_SESSION as $strName => $value)  {
				unset($_SESSION[$strName]);
			}
		}
	}

	public static function userLoggedIn() {
		return self::get( constant('USER_STATUS_VAR') ) === true;
	}
	
	public static function loginUser($user=null) {
		self::set( constant('USER_STATUS_VAR'), true );
		if( $user != null ) {
			self::setUser($user);
		}
	}
	
	public static function logoutUser() {
		self::set( constant('USER_STATUS_VAR'), true );
		self::destroy();
	}
	
	public static function setUser($u) { self::set( constant('USER_VAR'), $u ); }
	public static function getUser() { return self::get( constant('USER_VAR') ); }
	public static function setOffice($o) { self::set( constant('OFFICE_VAR'), $o ); }
	public static function getOffice() { return self::get( constant('OFFICE_VAR') ); }
	public static function setCompany($c) { self::set( constant('COMPANY_VAR'), $c ); }
	public static function getCompany() { return self::get( constant('COMPANY_VAR') ); }
	
			
}
