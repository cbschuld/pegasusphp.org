<?php
/*
* Smarty plugin
* -------------------------------------------------------------
* File:     function.year.php
* Type:     function
* Name:     year
* Purpose:  returns the current year
* -------------------------------------------------------------
*/
function smarty_function_year($params, &$smarty) {
	$dte = new DateTime('now');
	return $dte->format('Y');
}
?>