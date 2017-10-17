<?php
/*
 * Smarty plugin
 * -------------------------------------------------------------
 * File:     block.smsg.php
 * Type:     block
 * Name:     pad
 * Purpose:  
 * -------------------------------------------------------------
 */

function smarty_block_smsg($params, $content, &$smarty, &$repeat) {
	// only output on the closing tag
	if( ! $repeat){
		return '<div class="pegasus_smallmessage">'.$content.'</div>';
	}
}
?>