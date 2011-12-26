<?php
/*
* Smarty plugin
* -------------------------------------------------------------
* File:     function.debuginfo.php
* Type:     function
* Name:     debuginfo
* Purpose:  
* -------------------------------------------------------------
*/
function smarty_function_debuginfo($params, &$smarty) {

	$strContent = '';
	
	if( Pegasus::isDebug() ) {
		$strContent = Debug::getContent(true);
	}

	return $strContent;
}
?>