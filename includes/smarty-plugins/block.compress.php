<?php
/*
 * Smarty plugin
 * -------------------------------------------------------------
 * File:     block.compress.php
 * Type:     block
 * Name:     compress
 * Purpose:  
 * -------------------------------------------------------------
 */

function smarty_block_compress($params, $content, &$smarty, &$repeat) {
	
	// only output on the closing tag
	if( ! $repeat){
		$content = str_replace(array("\t","\n"),array('',''),$content);
	}
    return $content;
}
?>