<?php
/*
 * Smarty plugin
 * -------------------------------------------------------------
 * File:     block.requirejs.php
 * Type:     block
 * Name:     requirejs
 * Purpose:  
 * -------------------------------------------------------------
 */

function smarty_block_requirejs($params, $content, &$smarty, &$repeat) {
	// only output on the closing tag
	if( ! $repeat){
		
		$retval = '';
		
		$id = md5($content);
		$js = "$('#jsOff_{$id}').hide();$('#jsOn_{$id}').show();";
		
		$retval .= '<div id="jsOff_'.$id.'"><p style="color:#ff0000;font-weight:bold;font-style:italic;">';
		$retval .= 'This web page requires JavaScript. Your browser does not support JavaScript functions ';
		$retval .= 'which are used to implement features on this page; or JavaScript is currently disabled.  ';
		$retval .= 'Please enable JavaScript on your browser and refresh this web page.</p></div>';
		$retval .= '<div id="jsOn_'.$id.'" style="display:none;">'.$content.'</div>';
		$retval .= "\n<script type=\"text/javascript\">\n\t/*<![CDATA[*/\n".$js."\n\t/*]]>*/\n</script>\n";
		
		return $retval;
	}
}
?>