<?php
/*
 * Smarty plugin
 * -------------------------------------------------------------
 * File:     function.icon.php
 * Type:     function
 * Name:     clear
 * Purpose:  produce an clear object
 * -------------------------------------------------------------
 */
function smarty_function_icon($params, &$smarty) {
	if( in_array('icon', array_keys($params)) && ! file_exists( constant('FRAMEWORK_PATH') . "/images/icon/16x16/{$params['icon']}.png" ) ) {
		$smarty->trigger_error("icon: the icon you desired was not found in the framework icon path ({$params['icon']})");
	}
	return '<img src="/pegasus/images/icon/16x16/'.$params['icon'].'.png" alt="" height="16" width="16"/>';
}
?>