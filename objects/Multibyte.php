<?php
	/**
	 * Helps work with Multibyte strings
	 * mainly an encapsulator
	 *
	 */
	class Multibyte {
		public static function fixEncoding($str) {
			if( mb_detect_encoding($str) == "UTF-8" && mb_check_encoding($str,"UTF-8") ) {
				return $str;
			}
			else {
				return utf8_encode($str);
			}
		}
	}
?>
