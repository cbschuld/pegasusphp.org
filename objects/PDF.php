<?php
/**
 * Class PDF 
 * @package PegasusPHP
 */
class PDF {
	public static function getPageCount($filename) {
		$retVal = -1;
		
		if( file_exists($filename) ) {
			$cmd = 'identify -format %n '.$filename;
			$output = `$cmd`;
			$matches = array();
			if( preg_match('/^([0-9]+)/',$output,$matches) ) {
				$retVal = (int)$matches[1];
			}
		}
		return $retVal;
	}
}
?>