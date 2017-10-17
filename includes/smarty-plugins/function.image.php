<?php
/*
 * Smarty plugin
 * -------------------------------------------------------------
 * File:     function.image.php
 * Type:     function
 * Name:     clear
 * Purpose:  produce an clear object
 * -------------------------------------------------------------
 */
function smarty_function_image($params, &$smarty) {
	$src = str_replace('../', ' not allowed ', $params['src']);
	if( in_array('image', array_keys($params)) && ! file_exists( constant('FRAMEWORK_PATH') . "/images/$src" ) ) {
		$smarty->trigger_error("icon: the icon you desired was not found in the framework icon path ($src)");
	}
	return '<img src="/pegasus/images/'.$src.'" alt="" ' . $params['params'] . ' />';
}
?>