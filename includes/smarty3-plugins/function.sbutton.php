<?php
/*
 * Smarty plugin
 * -------------------------------------------------------------
 * File:     function.sbutton.php
 * Type:     function
 * Name:     button
 * Purpose:  produce a simple button object
 * -------------------------------------------------------------
 */
function smarty_function_sbutton($params, &$smarty) {
	
	// {button type=positive onclick="windowlocation='/order/';" icon=pushpin text="Request John Shannon"}
	
	$xhtml = '';

	if( in_array('icon', array_keys($params)) && ! file_exists( constant('FRAMEWORK_PATH') . "/images/icon/16x16/{$params['icon']}.png" ) ) {
		$smarty->trigger_error("button: icon you desired was not found in the framework icon path ({$params['icon']})");
	}
	else {
    	$text    = ( isset( $params['text'] ) ? $params['text'] : ( isset( $params['label'] ) ? $params['label'] : '&nbsp;' ) );
    	$icon    = ( isset( $params['icon']  )   ? $params['icon']  : '' );
    	$align   = ( isset( $params['align'] )   ? $params['align'] : 'left' );
    	$indent  = ( isset( $params['indent'] )  ? $params['indent'] : 0 );
    	$width   = ( isset( $params['width'] )   ? $params['width'] : 0 );
    	$onclick = ( isset( $params['onclick'] ) ? $params['onclick'] : ( isset( $params['action'] ) ? $params['action'] : '' ) );
    	$href    = ( isset( $params['href']  )   ? $params['href']  : ( isset( $params['url']  ) ? $params['url']  : 'javascript:void(0);' ) );
    	$name    = ( isset( $params['name']  )    ? $params['name'] : ( isset( $params['id'] ) ? $params['id'] : '' ) );
    	
		// Replace #LB# and #RB# with left and right brace respectively
		$onclick = preg_replace("/#LB#/", "{", $onclick );
		$onclick = preg_replace("/#RB#/", "}", $onclick );

    	include_object('SimpleButton');
    	
    	$sb = new SimpleButton();
    	
    	$sb->setId($name);
    	$sb->setText($text);
    	$sb->setUrl($href);
    	$sb->setAction($onclick);
    	$sb->setIndent($indent);
    	if( $icon != '' ) { $sb->setIconUrl('/pegasus/images/icon/16x16/'.$icon.'.png'); }
    	if( strtolower($align) == 'right' ) { $sb->setRightAlign(); }
    	$xhtml = $sb->get();
    }    	
   
    return $xhtml;
}
?>