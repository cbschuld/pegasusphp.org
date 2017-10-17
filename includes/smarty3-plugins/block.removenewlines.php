<?php
/*
 * Smarty plugin
 * -------------------------------------------------------------
 * File:     block.removenewlines.php
 * Type:     block
 * Name:     pad
 * Purpose:  
 * -------------------------------------------------------------
 */

function smarty_block_removenewlines($params, $content, &$smarty, &$repeat) {
	// only output on the closing tag
	if( ! $repeat){
		// size is an alias of all
		
		$content = str_replace("\n", " ", $content);
		$content = str_replace("\r", " ", $content);
		$content = str_replace("<br/>", " ", $content);
		$content = str_replace("<br />", " ", $content);
		$content = str_replace("<br>", " ", $content);
		$content = str_replace("<BR>", " ", $content);
		$content = str_replace("<BR/>", " ", $content);
		$content = str_replace("<BR />", " ", $content);
		$content = str_replace("<p>", " ", $content);
		$content = str_replace("</p>", " ", $content);
		$content = str_replace("<P>", " ", $content);
		$content = str_replace("</P>", " ", $content);
		
		return $content;
	}
}
?>