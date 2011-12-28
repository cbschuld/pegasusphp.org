<?php
/*
 * Smarty plugin
 * -------------------------------------------------------------
 * File:     block.ibullet.php
 * Type:     block
 * Name:     ibullet
 * Purpose:  
 * -------------------------------------------------------------
 */

function smarty_block_ibullet($params, $content, &$smarty, &$repeat) {
	// only output on the closing tag
	if( ! $repeat){

		$xhtml = '';
		
		if( in_array('icon', array_keys($params)) && substr( $params['icon'], 0, 1 ) != '/' && ! file_exists( constant('FRAMEWORK_PATH') . "/images/icon/16x16/{$params['icon']}.png" ) ) {
			$smarty->trigger_error("button: icon you desired was not found in the framework icon path ({$params['icon']})");
		}
		else {
    		$icon    = ( isset( $params['icon']    ) ? $params['icon']    : '' );
    		$alt     = ( isset( $params['alt']     ) ? $params['alt']     : '' );
    		$size    = ( isset( $params['size']    ) ? $params['size']    : 16 );
    		$padsize = ( isset( $params['padsize'] ) ? $params['padsize'] : 5 );
    		
    		if( substr( $params['icon'], 0, 1 ) != '/' ) {
    			$icon = '/pegasus/images/icon/16x16/'.$icon.'.png';
    		}
    		
    		$marginleft = (int)($size + $padsize);

    		$xhtml = "\n";
    		$xhtml .= '<img style="float:left;padding:0px '.$padsize.'px 0px 0px;" src="'.$icon.'" alt="'.$alt.'" />';
    		$xhtml .= '<div style="margin:0px 0px 0px '.$marginleft.'px;">';
    		$xhtml .= $content;
    		$xhtml .= "</div>\n";
		}
		return $xhtml;
	}
}
?>