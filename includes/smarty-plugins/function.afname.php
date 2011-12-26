<?php
/*
 * Smarty plugin
 * -------------------------------------------------------------
 * File:     function.afname.php
 * Type:     function
 * Name:     afname (activeform control name)
 * Purpose:  return the name of an activeform control
 * -------------------------------------------------------------
 */
function smarty_function_afname($params, &$smarty) {

	$strRetVal = 'null';
	
    if (!in_array('name', array_keys($params))) {
		$smarty->trigger_error("activeform: missing 'name' parameter - define the name of activeform object");
    }
    else {
		$formName = isset($smarty->_tpl_vars['activeform']) ? $smarty->_tpl_vars['activeform']->name : '';

		$strRetVal = "{$formName}_{$params['name']}";
    }
    return $strRetVal;		
}
?>