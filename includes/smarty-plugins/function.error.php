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
function smarty_function_error($params, &$smarty) {
	$xhtml = '';
	if( Pegasus::errorSet() ) {
		$note = new RenderNote();
		$note->setIcon('error');
		$note->setBackground('fdd');
		$note->setBorder('1px solid #caa');
		$note->setBold(false);
		$note->setColor('555');
    	$xhtml = $note->setContent(Pegasus::getError())->render();
		Pegasus::clearError();
	}
	else if( isset($params['hide']) ) {
		$xhtml = '<div id="pegasus_error" style="display:none;"></div>';
	}
    return $xhtml;
}
?>