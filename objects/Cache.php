<?php
	/**
	 * CacheObject
	 *
	 * @package PegasusPHP
	 */

	class CacheObject {
		private $_expires = null;
		private $_value = array();
		public function getExpiration() { return $this->_expires; }
		public function getValues() { return $this->_value; }
		public function getValue($key) { return isset($this->_value[self::$_cache_key.$key]) ? $this->_value[self::$_cache_key.$key] : null; }
		public function exists($key) { return isset($this->_value[self::$_cache_key.$key]); }
		public function setValue($key,$value) { $this->_value[self::$_cache_key.$key] = $value; }
		public function resetValue($key) { unset($this->_value[self::$_cache_key.$key]); }
		public function setExpiration($dte) { $this->_dte = $dte; }
	}

	/**
	 * Cache
	 *
	 * @package PegasusPHP
	 */
	class Cache {
		
		const DEFAULT_FILENAME = 'pfw_cache.dat';
		const DEFAULT_EXPIRATION = '+1 month';

		private static $_memcached_port = Settings::MEMCACHED_PORT;
		private static $_memcached_server = Settings::MEMCACHED_HOSTNAME;
		private static $_use_memcached = true;
		private static $_memcached = null;
		private static $_filename = null;
		private static $_cache = null;
		private static $_loaded = false;
		private static $_persist_signature = '';
		private static $_expiration = null;
		private static $_cache_key = '';
		
		public static function useMemcached($b=true) { self::$_use_memcached = $b; }
		public static function setMemcachedServer($str) { self::$_memcached_server = $str; }
		public static function setMemcachedPort($str) { self::$_memcached_port = $str; }
		public static function getCacheFilename() { return self::$_filename; }
		public static function getCacheKey() { return self::$_cache_key; }
		public static function setCacheKey($key) { self::$_cache_key = $key; }
		
		public static function setExpiration($dateTimeObject) {
			self::$_expiration = $dateTimeObject;
		}
		
		public static function cacheStorageKey() {
			return md5(serialize(self::$_cache));
		}
		
		private static function load() {
			self::$_loaded = false;
			// memcached mode
			if( self::$_use_memcached ) {
				self::$_memcached->connect(self::$_memcached_server, self::$_memcached_port);
			}
			// file IO mode
			else if( file_exists( self::getCacheFilename() ) ) {
				self::$_cache = unserialize(file_get_contents(self::getCacheFilename()));
				if( ! is_a(self::$_cache,'CacheObject')) {
					self::resetCache();
				}
				else {
					if( self::$_cache->getExpiration() < new DateTime('now') ) {
						
					}
					else {
						self::$_loaded = true;
						self::$_persist_signature = self::cacheStorageKey();
					}
				}
			}
			return self::$_loaded;
		}
		
		private static function cacheDirty() {
			return ( self::$_persist_signature != self::cacheStorageKey() );
		}
		
		private static function persist() {
			if( self::cacheDirty() ) {
				self::$_cache->setExpiration(self::$_expiration);
				if( ! @file_put_contents( self::getCacheFilename(), serialize(self::$_cache) ) ) {
					Pegasus::error('Cache File Write Error', 'Could not write to the cache file: '.self::getCacheFilename(),'Check permissions on the file: '.self::getCacheFilename());
				}
				else {
					self::$_loaded = true;
					self::$_persist_signature = self::cacheStorageKey();
				}
			}
		}
		
		private static function resetCache() {
		 	self::$_cache = new CacheObject();
		 	$dte = new DateTime('now');
		 	$dte->modify('+1 month');
		 	self::$_cache->setExpiration($dte);
		}
		
		private static function init() {
			
			// memcached mode
			if( self::$_use_memcached && ! self::$_memcached ) {
				self::$_memcached = new Memcache();
			}
			// file IO mode
			else if( ! self::$_use_memcached && ! self::$_filename ) {
				self::$_filename = constant('BASE_PATH').'/cache/'.self::DEFAULT_FILENAME;
			} 
			
			if( ! self::$_expiration ) { self::$_expiration = new DateTime( self::DEFAULT_EXPIRATION ); }
			if( ! self::$_cache ) { self::resetCache(); } 
			if( ! self::$_loaded ) { self::load(); }
		}
		
		public static function reset($key) {
			self::init();
			if( self::$_use_memcached ) {
				self::$_memcached->delete(self::$_cache_key.$key);
			}
			else {
				self::$_cache->setValue(self::$_cache_key.$key,'');
				self::persist();
			}
		}

		public static function set($key,$value) {
			self::init();
			if( self::$_use_memcached ) {
				self::$_memcached->set(self::$_cache_key.$key,$value);
			}
			else {
				self::$_cache->setValue(self::$_cache_key.$key,$value);
				self::persist();
			}
		}
		public static function get($key) {
			$retval = null;
			self::init();
			if( self::$_use_memcached ) {
				$retval = self::$_memcached->get(self::$_cache_key.$key);
			}
			else {
				$retval = self::$_cache->getValue(self::$_cache_key.$key);
			}
			return $retval;
		}
		public static function setObject($key,$value) {
			self::init();
			if( self::$_use_memcached ) {
				self::$_memcached->set(self::$_cache_key.$key,serialize($value));
			}
			else {
				self::$_cache->setValue(self::$_cache_key.$key,serialize($value));
				self::persist();
			}
		}
		public static function getObject($key) {
			$retval = null;
			self::init();
			if( self::$_use_memcached ) {
				$retval = unserialize(self::$_memcached->get(self::$_cache_key.$key));
			}
			else {
				$retval = unserialize(self::$_cache->getValue(self::$_cache_key.$key));
			}
			return $retval;
		}
		
		public static function exists($key) {
			$retval = false;
			self::init();
			if( self::$_use_memcached ) {
				$retval = ( self::$_memcached->get(self::$_cache_key.$key) !== false );
			}
			else {
				$retval = self::$_cache->exists(self::$_cache_key.$key);
			}
			return $retval;
		}
	}
?>