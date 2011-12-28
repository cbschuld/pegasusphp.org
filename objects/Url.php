<?php
	/**
	 * Url
	 *
	 * @package PegasusPHP
	 */
	class Url {
		public static function format($url,$secureUrl=false) {
			$retval = '';
			if( $url != '' ) {
				$url = str_replace('http://','',trim($url));
				$url = str_replace('https://','',trim($url));
				$retval = $secureUrl ? 'https://'.$url : 'http://'.$url;
			}
			return $retval;  
		}
	}
?>