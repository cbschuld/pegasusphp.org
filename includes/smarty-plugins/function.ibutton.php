<?php
/*
 * Smarty plugin
 * -------------------------------------------------------------
 * File:     function.ibutton.php
 * Type:     function
 * Name:     button
 * Purpose:  produce an image button object
 * -------------------------------------------------------------
 */
function smarty_function_ibutton($params, &$smarty) {
	
	// {button type=positive onclick="windowlocation='/order/';" icon=pushpin text="Request John Shannon"}
	
	$xhtml = '';
	
	if( in_array('icon', array_keys($params)) && ! file_exists( constant('FRAMEWORK_PATH') . "/images/icon/16x16/{$params['icon']}.png" ) ) {
		$smarty->trigger_error("button: icon you desired was not found in the framework icon path ({$params['icon']})");
	}
	else {
    	$text    = $params['text'];
    	$icon    = ( isset( $params['icon']  )   ? $params['icon']  : '' );
    	$indent  = ( isset( $params['indent'] )  ? $params['indent'] : 0 );
    	$onclick = ( isset( $params['onclick'] ) ? $params['onclick'] : ( isset( $params['action'] ) ? $params['action'] : '' ) );
    	$href    = ( isset( $params['href']  )   ? $params['href']  : ( isset( $params['url']  ) ? $params['url']  : '#' ) );

    	$iconUrl = '/pegasus/images/icon/16x16/'.$icon.'.png';
    	$xhtml .= '<a href="'.$href.'" onclick="'.$onclick.'">';
    	$xhtml .= '<img src="'.$iconUrl.'" alt="'.$text.'" width="16" height="16" />';
    	$xhtml .= '</a>';
	}    	
    return $xhtml;
}
?>