<?php
	/**
	 * Used to help with email items such as blowing up address lists or digging out addresses from Outlook/Windows formats
	 * @author cbschuld
	 * @package PegasusPHP
	 */
	class EmailUtil {
		/**
		 * Explodes a list of addresses into an array of values.  Also compares and removes duplicates based on a lower case compare.
		 * @param string $addressList a list of delimited addresses or even a single address
		 * @return array a list of email addresses
		 */
		public static function explodeAddresses($addressList) {
			$retlist = array();
			$cmplist = array();
			$addresses = preg_split("/[\s,;: ]+/", $addressList);
			foreach($addresses as $address) {
				$address = trim($address);
				if(filter_var($address, FILTER_VALIDATE_EMAIL) !== false && array_search(strtolower($address), $cmplist) === false ) {
					$retlist[] = $address;
					$cmplist[] = strtolower($address);
				}
			}
			return $retlist;
		}

		/**
		 * Returns a valid email address if an email address can be removed from the Outlook representation of "name (email)"
		 * or from "name <email>".  Otherwise FALSE is returned
		 * @param string $emailAddress The email address to attempt to parse
		 * @return string a parsed email address from the input or FALSE if it could not be parsed
		 */
		public static function getEmailFromOutlookEmailFormat($emailAddress) {
			if( preg_match("/\((.*)\)/",$emailAddress,$matches) && isset($matches[1]) ) {
				return $matches[1];
			}
			else if( preg_match("/<(.*)>/",$emailAddress,$matches) && isset($matches[1]) ) {
				return $matches[1];
			}
			return false;
		}
	}
?>