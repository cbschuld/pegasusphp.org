<?php
	class DateUtil {
		
		public static function getThisMonth() {
			$now = new DateTime('now');
			return (int)$now->format('n');
		}
		
		public static function getThisYear() {
			$now = new DateTime('now');
			return (int)$now->format('Y');
		}
		
		/**
		 * return an array of months where index 1 is January and index 12 is December (non-zero indexed)
		 * @return array array of months where index 1 is January and index 12 is December (non-zero indexed)
		 */
		public static function getMonthList() {
			/*
			$list = array();
			$dte = new DateTime();
			$dte->setDate((int)$dte->format('Y'),1,1);
			for($m = 1; $m <= 12; $m++ ) {
				$list' $m ' = (string)$dte->format('F');
				$dte->modify('+1 month');
			}
			return $list;
			*/
			return array(
							1  => 'January',
							2  => 'February',
							3  => 'March',
							4  => 'April',
							5  => 'May',
							6  => 'June',
							7  => 'July',
							8  => 'August',
							9  => 'September',
							10 => 'October',
							11 => 'November',
							12 => 'December'
					);
			 
		}
		
		/**
		 * return an array of years where the index is the same as the value with years from start to end
		 * @param int $start the inclusive starting year for the list
		 * @param int $end the inclusive ending year for the list
		 */
		public static function getYearListIndexedFrom($start,$end) {
			$list = array();
			if( $start > $end ) { // reset for the lazy
				list($start,$end) = array($end,$start);
			}
			for( $y = $start; $y <= $end; $y++ ) {
				$list[ $y ] = $y;
			}
			return $list;
		}
		
		/**
		 * return an array of years where the index is the same as the year value based on now minus yearCount number of years
		 * @param int $yearCount
		 */
		public static function getYearListIndexed($yearCount) {
			$now = new DateTime('now');
			$year = (int)$now->format('Y');
			return self::getYearListIndexedFrom($year-$yearCount,$year);
		}
		
	}
?>