<?php
/*
 * Smarty plugin
 * -------------------------------------------------------------
 * File:     function.message.php
 * Type:     function
 * Name:     message
 * Purpose:  
 * -------------------------------------------------------------
 */
function smarty_function_message($params, &$smarty) {
	$xhtml = '';
	if( Pegasus::messageSet() ) {
		$note = new RenderNote();
    	$xhtml = $note->setContent(Pegasus::getMessage())->render();
		Pegasus::clearMessage();
	}
	else if( isset($params['hide']) ) {
		$xhtml = '<div id="pegasus_message" style="display:none;"></div>';
	}
    return $xhtml;
}
?>