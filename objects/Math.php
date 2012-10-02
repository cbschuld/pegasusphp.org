<?php
	/**
	 * Class Math
	 * @package PegasusPHP
	 */
	class Math {
	
		// Euclidean Algorithm for GCD
		public static function gcd($a, $b) {
			if( $b == 0 ) {
	     		return $a;
	     	}
	     	else {
				return self::gcd($b, $a % $b);	
			}
		}
		
		public static function lcm($a,$b) {
			return ($a / self::gcd($a,$b) ) * $b;
		}
		
	};
?>