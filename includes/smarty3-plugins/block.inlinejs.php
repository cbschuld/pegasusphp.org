<?php
/*
 * Smarty plugin
 * -------------------------------------------------------------
 * File:     block.js.php
 * Type:     block
 * Name:     compress
 * Purpose:  
 * -------------------------------------------------------------
 */

function smarty_block_inlinejs($params, $content, &$smarty, &$repeat) {
	// only output on the closing tag
	if( ! $repeat){
		return "\n<script type=\"text/javascript\">\n\t/*<![CDATA[*/\n".$content."\n\t/*]]>*/\n</script>\n";
	}
}
?>