<?php
/*
* Smarty plugin
* -------------------------------------------------------------
* File:     function.include_javascript.php
* Type:     function
* Name:     include_javascript
* Purpose:  
* -------------------------------------------------------------
*/
function smarty_function_include_javascript($params, &$smarty) {
	
	if(empty($params['file'])) {
		$smarty->trigger_error("include_javascript: missing 'file' parameter");
		return;
	}
	Pegasus::addJavaScript(	$params['file'],
							( isset($params['priority']) ? $params['priority'] : 0 ),
							( isset($params['post']) ? (strtolower($params['post']) == "true" || strtolower($params['post']) == "yes") : false ),
							( isset($params['name']) ? $params['name'] : '' )
							);
}
?>