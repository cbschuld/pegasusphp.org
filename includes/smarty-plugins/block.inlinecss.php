<?php
/*
 * Smarty plugin
 * -------------------------------------------------------------
 * File:     block.css.php
 * Type:     block
 * Name:     compress
 * Purpose:  
 * -------------------------------------------------------------
 */

function smarty_block_inlinecss($params, $content, &$smarty, &$repeat) {
	// only output on the closing tag
	if( ! $repeat){
//		return "\n<style type=\"text/css\">\n\t/*<![CDATA[*/\n".$content."\n\t/*]]>*/\n</style>\n";
		return "\n<style type=\"text/css\">\n<!--".$content."\n//--></style>\n";
	}
}
?>