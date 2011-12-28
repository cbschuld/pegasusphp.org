<?php
/*
 * Smarty plugin
 * -------------------------------------------------------------
 * File:     block.msg.php
 * Type:     block
 * Name:     pad
 * Purpose:  
 * -------------------------------------------------------------
 */

function smarty_block_msg($params, $content, &$smarty, &$repeat) {
	// only output on the closing tag
	if( ! $repeat){
		return '<div class="pegasus_message">'.$content.'</div>';
	}
}
?>