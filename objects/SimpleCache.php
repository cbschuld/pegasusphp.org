<?php
	/**
	 * 
	 * @author cbschuld
	 * @package PegasusPHP
	 *
	 */
	class SimpleCache {
		private static function key($label,$key) {
			$label = ( $label == '' ? 'cache' : $label );
			return md5( $label . $key );
		}
		private static function filename($label,$key) {
			return constant('CACHE_PATH') .'/'. $label .'.'. self::key($label,$key) . '.cache';
		}
		public static function set($label,$key,$value) {
			return( file_put_contents( self::filename($label,$key), $value ) > 0 );
		}
		public static function get($label,$key) {
			return file_get_contents( self::filename($label,$key));
		}
		public static function exists($label,$key) {
			return file_exists(self::filename($label,$key));
		}
		public static function reset($label,$key) {
			return unlink(self::filename($label,$key));  
		}
	}
?>