<?php
	/**
	 * Day
	 *
	 * @package PegasusPHP
	 */
	class Day {
		public static function ordinal($cdnl) {
			$test_c = abs($cdnl) % 10;
			$ext = ((abs($cdnl) %100 < 21 && abs($cdnl) %100 > 4) ? 'th' : (($test_c < 4) ? ($test_c < 3) ? ($test_c < 2) ? ($test_c < 1) ? 'th' : 'st' : 'nd' : 'rd' : 'th'));
			return $cdnl.$ext;
		}
	}
?>