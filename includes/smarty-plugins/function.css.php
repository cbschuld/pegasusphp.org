<?php
/*
* Smarty plugin
* -------------------------------------------------------------
* File:     function.css.php
* Type:     function
* Name:     css
* Purpose:  
* -------------------------------------------------------------
*/
function smarty_function_css($params, &$smarty) {
	
	$strContent = '';
	
	foreach( Pegasus::getCSS() as $strCSS ) {
		$strContent .= "\n\t<link rel=\"stylesheet\" href=\"{$strCSS}\" type=\"text/css\" media=\"all\" />";
	}
	return $strContent;
}
?>