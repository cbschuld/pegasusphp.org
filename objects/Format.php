<?php
	/**
	 * File: Format.php
	 * Author: Chris Schuld (http://chrisschuld.com/)
	 * Last Modified: 2/24/2009
	 * @version 1.0
	 * @package PegasusPHP
	 *  
	 * The Format class is a static class allowing a quick interface for
	 * applications.  Currently it supports formatting the following:
	 * 
	 * US Phone Numbers
	 * US Phone Numbers for Storage
	 * US Dollars
	 * Bytes
	 * KiloBytes
	 * 
	 * Typical Usage:
	 * 
	 * echo Format::phoneNumberForStorage('480-373-6581').'<br/>';
	 * echo Format::phoneNumberForStorage('373-6581').'<br/>';
	 * echo Format::phoneNumber(Format::phoneNumberForStorage('800-959-0045')).'<br/>';
	 * echo Format::phoneNumber('3736581').'<br/>';
	 * echo Format::phoneNumber('3736581','480').'<br/>';
	 * echo Format::usd(56.32).'<br/>';
	 * echo Format::bytes(20375237).'<br/>';
	 * echo Format::kilobytes(20375237).'<br/>';
	 *
	 * @package PegasusPHP
	 */
	class Format {

		const DATETIME_FORMAT = 'm/d/Y g:iA';
		const DATE_FORMAT = 'm/d/Y';
		const TIME_FORMAT = 'g:iA';

		const MINUTES_PER_DAY = 1440;
		const MINUTES_PER_HOUR = 60;

		const SECONDS_PER_DAY = 86400;
		const SECONDS_PER_HOUR = 3600;
		const SECONDS_PER_MINUTE = 60;

		/**
		 * Formats text into a usable filename using safe characters and replacing one or more bad
		 * characters with a dash (-) character
		 * @param string $txtvalue text value to use to convert to a filename
		 * @return string translated filename
		 */
		public static function filename($txtvalue) {
			// remove all non-safe characters and replace one or more in a row with a dash character
			return trim(preg_replace("/-+/","-",preg_replace("/[^A-Za-z0-9-_\.]/","-",$txtvalue))," \t\r\n\0-");
		}
		
		/**
		 * Parses and rebuilds a URL to be correct (tidy for URL)
		 * Based on http://www.hashbangcode.com/blog/tidy-up-a-url-with-php-322.html
		 * 
		 * @param string $url
		 * @return string a clean/correct URL OR false -- if the return value is false the URL was invalid completely
		 */
		public static function url($url){
			$url = trim($url);
			// check for a schema and if there isn't one then add it
			if( substr($url,0,5) != 'https' && substr($url,0,4) != 'http' && substr($url,0,3) != 'ftp' ) {
				$url = 'http://'.$url;
			};
			$parsed = @parse_url($url);
			if( !is_array($parsed) || strpos($url,".") === false ){
				return false;
			}
			// rebuild url
			$url = isset($parsed['scheme']) ? $parsed['scheme'].':'.((strtolower($parsed['scheme']) == 'mailto') ? '' : '//') : '';
			$url .= isset($parsed['user']) ? $parsed['user'].(isset($parsed['pass']) ? ':'.$parsed['pass'] : '').'@' : '';
			$url .= isset($parsed['host']) ? $parsed['host'] : '';
			$url .= isset($parsed['port']) ? ':'.$parsed['port'] : '';
			
			// if no path exists then add a slash
			if( isset($parsed['path']) ) {
				$url .= (substr($parsed['path'],0,1) == '/') ?   $parsed['path'] : ('/'.$parsed['path']);
			}
			else {
				$url .= '/';
			}
			$url .= isset($parsed['query']) ? '?'.$parsed['query'] : '';
			return $url;
		}
				
		/**
		 * Formats an "english" formed comma-separated list with the inclusion of "and"
		 * @example array("one","two","three","four") yeilds one, two, three and four
		 * @param string[] $array An array of of strings to be formatted
		 * @return string A comma-separated list with the inclusion of the word "and"
		 */
		public static function commaList($array) {

			//clean the array
			$clean = array();
			for( $i = 0; is_array($array) && $i < count($array); $i++ ) {
				if( $value = trim($array[$i]) ) {
					$clean[] = $value;
				}
			}
			
    		if( !$clean || !count($clean) ) return ""; 

    		$last = array_pop($clean); 
			if( !count($clean) ) return $last;    
			
			return implode(", ", $clean)." and {$last}";
		} 
		
		/**
		 * Gets a formatted phone numbers
		 * @param $strPhone string contains the phone number to format
		 * @param $strAreaCode string contains the default area code to use if one is not supplied
		 * @return string an unpacked and formatted phone number
		 */
		public static function phoneNumber($strPhone,$strAreaCode='') {
			$strExtension = '';
			$strPhone = strtolower(preg_replace('/[^0-9Xx]/','',$strPhone));
			$extensionPosition = strpos($strPhone,'x');
			if( $extensionPosition != false ) {
				$strExtension = substr($strPhone,$extensionPosition+1);
				$strPhone = substr($strPhone,0,$extensionPosition);
			}
			switch( strlen($strPhone) ) {
				case(10):
					$strPhone = '('.substr($strPhone,0,3).') '.substr($strPhone,3,3).'-'.substr($strPhone,6,4);
					break;
				case(7):
					$strPhone = ($strAreaCode == '' ? '' : '('.$strAreaCode.') ') . substr($strPhone,0,3).'-'.substr($strPhone,3,4);
					break;
				default:
					break;
			}
			return $strPhone . ($strExtension==''?'':' x'.$strExtension);
		}
		/**
		 * Adds an "s" if necessary 
		 * @param $count integer number / count of txt item(s)
		 * @param $text string item description
		 * @return string a properly plural formatted value
		 */
		public static function plural($count,$text) {
			return $count . ( ( $count == 1 ) ? ( " $text" ) : ( " {$text}s" ) );
		}
		
		/**
		 * Calculates the textual version of time between two date/time objects 
		 * @param $dateTimeObject1 datetime object (one of the dates in the date/time span)
		 * @param $dateTimeObject2 datetime object (one of the dates in the date/time span)
		 * @return string a properly formatted description of the difference between two dates
		 */
		public static function betweenDates($dateTimeObject1,$dateTimeObject2) {
		
		    $interval = $dateTimeObject1->diff($dateTimeObject2);
		    
		    $suffix = ( $interval->invert ? ' ago' : '' );
		    if ( $v = $interval->y >= 1 ) return self::plural($interval->y, 'year' ) . $suffix;
		    if ( $v = $interval->m >= 1 ) return self::plural( $interval->m, 'month' ) . $suffix;
		    if ( $v = $interval->d >= 1 ) return self::plural( $interval->d, 'day' ) . $suffix;
		    if ( $v = $interval->h >= 1 ) return self::plural( $interval->h, 'hour' ) . $suffix;
		    if ( $v = $interval->i >= 1 ) return self::plural( $interval->i, 'minute' ) . $suffix;
		    return self::plural( $interval->s, 'second' ) . $suffix;
		}
		
		/**
		 * Gets a string value of a phone number formatted strictly for storage.
		 * It will only contain numbers and an extension marker
		 * @param $value string contains the phone number to pack for storage
		 * @return string a phone number packed for storage
		 */
		public static function phoneNumberForStorage($value) {
			return strtolower(preg_replace('/[^0-9Xx]/','',$value));
		}
		/**
		 * Get a formated US Dollar amount from a double/float/fp
		 * @param $value double/float/fp number
		 * @return string formatted dollar amount
		 */
		public static function usd($value) {
			return self::currency($value);
		}
		
		/**
		 * Get a formated currency amount from a double/float/fp.
		 * Make sure you call setlocale() to lock in your location
		 * Additional information: http://docstore.mik.ua/orelly/webprog/pcook/ch16_07.htm
		 * @param $value double/float/fp number
		 * @return string formatted currency amount
		 */
		public static function currency($amt) {
			// get locale-specific currency formatting information 
			$a = localeconv();
			
			// if 'frac_digits' is 127 then setlocale has not been called
			if( $a['frac_digits'] == 127 ) {
				$amt = sprintf('$%.2f',$amt);
			}
			else {
				
				// compute sign of $amt and then remove it
				if($amt < 0) { $sign = -1; } else { $sign = 1; }
				$amt = abs($amt);
				// format $amt with appropriate grouping, decimal point, and fractional digits 
				$amt = number_format($amt,$a['frac_digits'],$a['mon_decimal_point'], $a['mon_thousands_sep']);
	
				// figure out where to put the currency symbol and positive or negative signs
				$currency_symbol = $a['currency_symbol'];
				// is $amt >= 0 ? 
				if(1 == $sign) {
					$sign_symbol  = 'positive_sign';
					$cs_precedes  = 'p_cs_precedes';
					$sign_posn    = 'p_sign_posn';
					$sep_by_space = 'p_sep_by_space';
				}
				else {
					$sign_symbol  = 'negative_sign';
					$cs_precedes  = 'n_cs_precedes';
					$sign_posn    = 'n_sign_posn';
					$sep_by_space = 'n_sep_by_space';
				}
				if($a[$cs_precedes]) {
					if (3 == $a[$sign_posn]) {
						$currency_symbol = $a[$sign_symbol].$currency_symbol;
					}
					elseif(4 == $a[$sign_posn]) {
						$currency_symbol .= $a[$sign_symbol];
					}
					// currency symbol in front 
					if($a[$sep_by_space]) {
						$amt = $currency_symbol.' '.$amt;
					}
					else {
						$amt = $currency_symbol.$amt;
					}
				}
				else {
					// currency symbol after amount 
					if($a[$sep_by_space]) {
						$amt .= ' '.$currency_symbol;
					}
					else {
						$amt .= $currency_symbol;
					}
				}
				if(0 == $a[$sign_posn]) {
					$amt = "($amt)";
				}
				elseif(1 == $a[$sign_posn]) {
					$amt = $a[$sign_symbol].$amt;
				}
				elseif(2 == $a[$sign_posn]) {
					$amt .= $a[$sign_symbol];
				}
			}
			return $amt;
		}		
		
		/**
		* Get bytes converted to a formatted number 
		* @param int $bytes        bytes 
		* @return string 
		*/
		public static function bytes($bytes) {
	        
			$b = (int)$bytes; 
	        $s = array('B', 'kB', 'MB', 'GB', 'TB'); 
	        
	        if($b <= 0){
	        	return "0 ".$s[0];
	        } 
	        
	        $con = 1024;
	        $e = (int)(log($b,$con)); 
	        return number_format($b/pow($con,$e),2,'.',',').' '.$s[$e]; 
		}
		/**
		* Get kilobytes converted to a formatted number 
		* @param int $kbytes kilobytes 
		* @return string
		*/
		public static function kilobytes($kbytes) {
			
			$kb = (int)$kbytes; 
			$s = array('kB', 'MB', 'GB', 'TB');

			if($kb <= 0){
				return "0 ".$s[0];
			}
			
			$con = 1024;
			$e = (int)(log($kb,$con)); 
			return number_format($kb/pow($con,$e),2,'.',',').' '.$s[$e]; 
		}
		
		
		public static function minutes($minutes,$zeroMinuteText='0 minutes',$separatorCharacter='&nbsp;&middot;&nbsp;') {
			
			$dayLabel = 'day';
			$hourLabel = 'hour';
			$minuteLabel = 'minute';
			
			if( $days = intval($minutes / self::MINUTES_PER_DAY) ) {
				$minutes -= ($days * self::MINUTES_PER_DAY);
				$dayLabel .= ($days != 1 ? 's':'');
			}
			
			if( $hours = intval($minutes / self::MINUTES_PER_HOUR) ) {
				$minutes -= ($hours * self::MINUTES_PER_HOUR);
				$hourLabel .= ($hours != 1 ? 's':'');
			}
			
			$minuteLabel .= ($minutes != 1 ? 's':'');
			
			$retval = ($days > 0 ? "{$days} {$dayLabel}" : "") . '@' . ($hours > 0 ? "{$hours} {$hourLabel}" : "") . '@' . ($minutes > 0 ? "{$minutes} {$minuteLabel}" : "");
			$retval = str_replace('@',$separatorCharacter,str_replace('@@','@',trim($retval,'@')));

			if( $retval == '' ) {
				$retval = $zeroMinuteText;
			}
			return $retval;
		}

		public static function seconds($seconds,$zeroSecondsText='0 seconds',$separatorCharacter='&nbsp;&middot;&nbsp;') {

			$dayLabel = 'day';
			$hourLabel = 'hour';
			$minuteLabel = 'minute';
			$secondLabel = 'second';

			if( $days = intval($seconds / self::SECONDS_PER_DAY) ) {
				$seconds -= ($days * self::SECONDS_PER_DAY);
				$dayLabel .= ($days != 1 ? 's':'');
			}

			if( $hours = intval($seconds / self::SECONDS_PER_HOUR) ) {
				$seconds -= ($hours * self::SECONDS_PER_HOUR);
				$hourLabel .= ($hours != 1 ? 's':'');
			}

			if( $minutes = intval($seconds / self::SECONDS_PER_MINUTE) ) {
				$seconds -= ($minutes * self::SECONDS_PER_MINUTE);
				$minuteLabel .= ($minutes != 1 ? 's':'');
			}

			$secondLabel .= ($seconds != 1 ? 's':'');

			$retval = ($days > 0 ? "{$days} {$dayLabel}" : "") . '@' . ($hours > 0 ? "{$hours} {$hourLabel}" : "") . '@' . ($minutes > 0 ? "{$minutes} {$minuteLabel}" : "") . '@' . ($seconds > 0 ? "{$seconds} {$secondLabel}" : "");
			$retval = str_replace('@',$separatorCharacter,str_replace('@@','@',trim($retval,'@')));

			if( $retval == '' ) {
				$retval = $zeroSecondsText;
			}
			return $retval;
		}

		/**
		 * Truncate the text down to the listed limit.
		 *
		 * @param string $text
		 * @param integer $limit The hard limit to use
		 * @return string The truncated text (if applicable)
		 */
		public static function textLimit($text, $limit) {
			  if(strlen($text) > $limit) {
				$words = str_word_count($text, 2);
				$pos = array_keys($words);
				$text = substr($text, 0, $limit) . '...';
			  }
		      return $text;
		}

	}
?>
