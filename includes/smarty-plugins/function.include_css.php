<?php
/*
* Smarty plugin
* -------------------------------------------------------------
* File:     function.include_css.php
* Type:     function
* Name:     include_css
* Purpose:  
* -------------------------------------------------------------
*/
function smarty_function_include_css($params, &$smarty) {
	
	if(empty($params['file'])) {
		$smarty->trigger_error("include_css: missing 'file' parameter");
		return;
	}

	Pegasus::addCSS( $params['file'] );
}
?>