<?php
/*
 * Smarty plugin
 * -------------------------------------------------------------
 * File:     block.activeform.php
 * Type:     block
 * Name:     activeform
 * Purpose:  
 * -------------------------------------------------------------
 */

function smarty_block_activeform($params, $content, &$smarty, &$repeat) {
	
	$strContent = $content;

	if (!in_array('name', array_keys($params))) {
		$smarty->trigger_error("activeform (block wrapper): missing 'name' parameter - define the name of your activeform (form name)");
    }
	$formname = isset( $params['name'] )   ? $params['name']   : 'af';

	$activeform = new stdClass();
	$activeform->name = $formname;
   	$activeform->submitname   = isset( $params['submitname'] )  ? $params['submitname'] : 'active_form___please_note___set_submit_name_to_enable_submit_checking';
   	$activeform->lwidth       = isset( $params['lwidth'] )      ? $params['lwidth'] : '50';
   	$activeform->lalign       = isset( $params['lalign'] )      ? $params['lalign'] : 'right';
   	$activeform->readonly     = isset( $params['readonly'] )    ? (bool)$params['readonly'] : false;
   	$activeform->showlabel    = isset( $params['showlabel'] )   ? (bool)$params['showlabel'] : (isset( $params['showlabels'] ) ? (bool)$params['showlabels'] : true );
   	$activeform->validation   = isset( $params['validation'] )  ? (bool)$params['validation'] : true;

	// only output on the opening tag
	if($repeat){
    	$smarty->assign('activeform',$activeform);
	}
    // only output on the closing tag
    else {
        if( $activeform->validation && isset($content) ) {
        	$strContent = 	"\n<script type=\"text/javascript\">\n\t".
        					"/*<![CDATA[*/\n\t".
        					"var validationArray{$formname} = new Array();\n\t".
        					"/*]]>*/\n".
        					"</script>\n".
        					$content;
        }
    }
    
    return $strContent;
}
?>