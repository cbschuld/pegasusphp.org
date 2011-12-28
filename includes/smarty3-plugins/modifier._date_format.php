<?php
/**
 * Smarty _date_format modifier plugin
 *
 * Type:     modifier<br/>
 * Name:     _date_format<br/>
 * Purpose:  format datestamps via PHP5.2's DateTime type<br/>
 * Input:<br/>
 *         - string: input date string
 *         - format: strftime format for output
 * @author Chris Schuld (cbschuld@gmail.com)
 * @param string DateTime object
 * @param string
 * @return string
 */
function smarty_modifier__date_format($value, $format = DATE_RFC822) {
    if( ! $value ) {
    	$value = 'now';
    }
    if( $value instanceof DateTime ) {
    	$dte = clone $value;
    }
    else {
	    try {
	    	$dte = new DateTime($value);
	    }
	    catch (Exception $e) {
	    	try { 
	    		$dte = new DateTime("@$value");
	    	}
	    	catch (Exception $e) {
	    		$dte = new DateTime();
	    	}
    	}
    }
    return $dte->format($format );
}

/* vim: set expandtab: */
?>