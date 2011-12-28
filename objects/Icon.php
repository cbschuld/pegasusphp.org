<?php
	/**
	 * Icon
	 *
	 * @package PegasusPHP
	 */
	class Icon {
		private static $_library_path_macro = '/pegasus/images/icon/[WIDTH]x[HEIGHT]/[NAME].png';
		private static $_library_path_macro_secure = '/pegasus/images/icon/[WIDTH]x[HEIGHT]/[NAME].png';
		public static function setLibraryPathMacro($macro) { 
			self::$_library_path_macro = $macro;
		}
		public static function setSecureLibraryPathMacro($macro) { 
			self::$_library_path_macro_secure = $macro;
		}
		public static function url($name,$width,$height=0) {
			if( (int)$height == 0 ) { $height = $width; }
			return str_replace(
								array( '[WIDTH]', '[HEIGHT]', '[NAME]' ),
								array( $width, $height, $name),
								( Pegasus::isSecure() ? self::$_library_path_macro_secure : self::$_library_path_macro )
							);
		}
		public static function html($name,$alt,$width,$height=0) {
			return "<img src=\"".self::url($name,$width,$height)."\" alt=\"{$alt}\"/>";
		}
	}
?>