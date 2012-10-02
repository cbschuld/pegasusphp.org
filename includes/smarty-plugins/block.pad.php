<?php
/*
 * Smarty plugin
 * -------------------------------------------------------------
 * File:     block.pad.php
 * Type:     block
 * Name:     pad
 * Purpose:  
 * -------------------------------------------------------------
 */

function smarty_block_pad($params, $content, &$smarty, &$repeat) {
	// only output on the closing tag
	if( ! $repeat){
		// size is an alias of all
		$a = (int)( isset( $params['size']  )   ? $params['size']    : ( isset( $params['s'] ) ? $params['s'] : 2 ) );
		$a = (int)( isset( $params['all']  )   ? $params['all']    : ( isset( $params['a'] ) ? $params['a'] : $a ) );
		$t = (int)( isset( $params['top']  )   ? $params['top']    : ( isset( $params['t'] ) ? $params['t'] : $a ) );
		$r = (int)( isset( $params['bottom'] ) ? $params['bottom'] : ( isset( $params['b'] ) ? $params['b'] : $a ) );
		$b = (int)( isset( $params['right'] )  ? $params['right']  : ( isset( $params['r'] ) ? $params['r'] : $a ) );
		$l = (int)( isset( $params['left'] )   ? $params['left']   : ( isset( $params['l'] ) ? $params['l'] : $a ) );
		return "\n<div style=\"padding:{$t}px {$r}px {$b}px {$l}px;\">".$content."</div>\n";
	}
}
?>