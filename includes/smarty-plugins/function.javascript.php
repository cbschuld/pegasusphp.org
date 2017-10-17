<?php
/*
* Smarty plugin
* -------------------------------------------------------------
* File:     function.javascript.php
* Type:     function
* Name:     javascript
* Purpose:  
* -------------------------------------------------------------
*/
function smarty_function_javascript($params, &$smarty) {
	$strContent = "\n";
	Pegasus::sortJavaScript();
	foreach( Pegasus::getJavaScript(false) as $strJS ) {
		if( ! $strJS->getPost() ) {
			$strContent .= "\t<script type=\"text/javascript\" src=\"{$strJS->getFilename()}\"></script>\n";
		}
	}
	return $strContent;
}
?>