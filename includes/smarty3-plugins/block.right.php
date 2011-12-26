<?php
/*
 * Smarty plugin
 * -------------------------------------------------------------
 * File:     block.right.php
 * Type:     block
 * Name:     right
 * Purpose:  
 * -------------------------------------------------------------
 */

function smarty_block_right($params, $content, &$smarty, &$repeat) {
	// only output on the closing tag
	if( ! $repeat){
		return "\n<div style=\"float:right;\">".$content."</div>\n";
	}
}
?>