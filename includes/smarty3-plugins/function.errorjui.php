<?php
/*
 * Smarty plugin
 * -------------------------------------------------------------
 * File:     function.error.php
 * Type:     function
 * Name:     error
 * Purpose:  
 * -------------------------------------------------------------
 */
function smarty_function_errorjui($params, &$smarty) {
	$xhtml = '';
	if( Pegasus::errorSet() ) {
		$note = new RenderErrorJUI();
		$note->setHidden(isset($params['hide']));
    	$xhtml = $note->setContent(Pegasus::getError())->render();
		Pegasus::clearError();
	}
    return $xhtml;
}
?>