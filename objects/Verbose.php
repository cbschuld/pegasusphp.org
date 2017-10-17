<?php
	class Verbose {
		private static $_verbose_enabled = false;
		public static function enable($enableVerbose=true) {
			self::$_verbose_enabled = $enableVerbose;
		}
		public static function console($msg) {
			if( self::$_verbose_enabled ) {
				echo "{$msg}\n";
			}
		}
	} 
?>