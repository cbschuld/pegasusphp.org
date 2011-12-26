<?php
	/**
	 * WebColor
	 *
	 * @package PegasusPHP
	 */
	class WebColor {

		public static function getR($rgb) {
			return hexdec( substr( self::getRgbColor($rgb), 0, 2 ) );
		}

		public static function getG($rgb) {
			return hexdec( substr( self::getRgbColor($rgb), 2, 2 ) );
		}

		public static function getB($rgb) {
			return hexdec( substr( self::getRgbColor($rgb), 4, 2 ) );
		}

		public static function getRgbColor($rgb) {
			$retval = '';
			if( strlen($rgb) == 6 ) {
				$retval = $rgb;
			}
			else if( strlen($rgb) == 3 ) {
				$retval  = substr($rgb,0,1).substr($rgb,0,1);
				$retval .= substr($rgb,1,1).substr($rgb,1,1);
				$retval .= substr($rgb,2,1).substr($rgb,2,1);
			}
			else {
				$retval = false;
			}
			return $retval;
		}
		public static function webSafe($rgb) {
			$rgb = self::getRgbColor($rgb);
			if( $rgb === false ) {
				Pegasus::error('Invalid Color','Invalid RGB Color passed to Color::webSafe(): '.$rgb);
			}
			else {
				sscanf($rgb, '%2x%2x%2x', $r, $g, $b);
				$r = round($r / 51) * 51;
				$g = round($r / 51) * 51;
				$b = round($r / 51) * 51;
				$rgb  = str_pad(dechex($r), 2, '0', STR_PAD_LEFT);
				$rgb .= str_pad(dechex($g), 2, '0', STR_PAD_LEFT);
				$rgb .= str_pad(dechex($b), 2, '0', STR_PAD_LEFT);
			}

			return strtolower($rgb);
		}
		public static function webSafeShort($rgb) {
			$rgb = self::getRgbColor($rgb);
			if( $rgb === false ) {
				Pegasus::error('Invalid Color','Invalid RGB Color passed to Color::webSafe(): '.$rgb);
			}
			else {
				sscanf($rgb, '%2x%2x%2x', $r, $g, $b);
				$r = round($r / 51) * 51;
				$g = round($g / 51) * 51;
				$b = round($b / 51) * 51;
				$rgb  = dechex($r);
				$rgb .= dechex($g);
				$rgb .= dechex($b);
			}
			return strtolower($rgb);
		}
		public static function increment($rgb,$increment_int_value) {
			$rgb = self::getRgbColor($rgb);
			if( $rgb === false ) {
				Pegasus::error('Invalid Color','Invalid RGB Color passed to Color::webSafe(): '.$rgb);
			}
			else {
				sscanf($rgb, '%2x%2x%2x', $r, $g, $b);
				$r += (int)$increment_int_value;
				$g += (int)$increment_int_value;
				$b += (int)$increment_int_value;
				if( $r > hexdec('ff') ) { $r = hexdec('ff'); }
				if( $g > hexdec('ff') ) { $g = hexdec('ff'); }
				if( $b > hexdec('ff') ) { $b = hexdec('ff'); }
				$rgb  = str_pad(dechex($r), 2, '0', STR_PAD_LEFT);
				$rgb .= str_pad(dechex($g), 2, '0', STR_PAD_LEFT);
				$rgb .= str_pad(dechex($b), 2, '0', STR_PAD_LEFT);
			}
			return strtolower($rgb);
		}
		public static function decrement($rgb,$increment_int_value) {
			$rgb = self::getRgbColor($rgb);
			if( $rgb === false ) {
				Pegasus::error('Invalid Color','Invalid RGB Color passed to Color::webSafe(): '.$rgb);
			}
			else {
				sscanf($rgb, '%2x%2x%2x', $r, $g, $b);
				$r -= (int)$increment_int_value;
				$g -= (int)$increment_int_value;
				$b -= (int)$increment_int_value;
				if( $r < 0 ) { $r = 0; }
				if( $g < 0 ) { $g = 0; }
				if( $b < 0 ) { $b = 0; }
				$rgb  = str_pad(dechex($r), 2, '0', STR_PAD_LEFT);
				$rgb .= str_pad(dechex($g), 2, '0', STR_PAD_LEFT);
				$rgb .= str_pad(dechex($b), 2, '0', STR_PAD_LEFT);
			}
			return strtolower($rgb);
		}
	}
?>