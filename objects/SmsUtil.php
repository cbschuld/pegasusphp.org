<?php
	/**
	 * Used to help with SMS items such as blowing up number lists
	 * @author cbschuld
	 * @package PegasusPHP
	 */
	class SmsUtil {
		/**
		 * Explodes a list of numbers into an array of values.  Also compares and removes duplicates.
		 * @param string $numberList a list of delimited numbers or even a single address
		 * @return array a list of sms numbers
		 */
		public static function explodeNumbers($numberList) {
			$retlist = array();
			$cmplist = array();
			$numbers = preg_split("/[\s,;: ]+/", $numberList);
			foreach($numbers as $number) {
				$number = trim($number);
				if( array_search(strtolower($number), $cmplist) === false ) {
					$retlist[] = $number;
					$cmplist[] = strtolower($number);
				}
			}
			return $retlist;
		}
	}
?>