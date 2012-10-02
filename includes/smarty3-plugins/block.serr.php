<?php
/*
 * Smarty plugin
 * -------------------------------------------------------------
 * File:     block.serr.php
 * Type:     block
 * Name:     pad
 * Purpose:  
 * -------------------------------------------------------------
 */

function smarty_block_serr($params, $content, &$smarty, &$repeat) {
	// only output on the closing tag
	if( ! $repeat){
		return '<div class="pegasus_smallerror">'.$content.'</div>';
	}
}
?>