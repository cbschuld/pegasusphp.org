<?php
/*
 * Smarty plugin
 * -------------------------------------------------------------
 * File:     block.left.php
 * Type:     block
 * Name:     left
 * Purpose:  
 * -------------------------------------------------------------
 */

function smarty_block_left($params, $content, &$smarty, &$repeat) {
	// only output on the closing tag
	if( ! $repeat){
		return "\n<div style=\"float:left;\">".$content."</div>\n";
	}
}
?>