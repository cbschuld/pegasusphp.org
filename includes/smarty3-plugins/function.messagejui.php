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
function smarty_function_messagejui($params, &$smarty) {
	$xhtml = '';
	if( Pegasus::messageSet() ) {
		$note = new RenderNoteJUI();
		$note->setHidden(isset($params['hide']));
    	$xhtml = $note->setContent(Pegasus::getMessage())->render();
		Pegasus::clearMessage();
	}
    return $xhtml;
}
?>