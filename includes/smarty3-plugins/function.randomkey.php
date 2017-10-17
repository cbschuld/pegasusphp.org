<?php
/*
 * Smarty plugin
 * -------------------------------------------------------------
 * File:     function.randomkey.php
 * Type:     function
 * Name:     randomkey
 * Purpose:  produce an randomkey
 * -------------------------------------------------------------
 */
function smarty_function_randomkey($params, &$smarty) {
	$dte = new DateTime("now");
	$keyload = $dte->format("c");
	$key = md5($keyload);
    return $key;
}
?>