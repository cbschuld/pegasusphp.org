<?php
/*
 * Smarty plugin
 * -------------------------------------------------------------
 * File:     function.button.php
 * Type:     function
 * Name:     button
 * Purpose:  produce an button object
 * -------------------------------------------------------------
 */
function smarty_function_button($params, &$smarty) {
	
	// {button type=positive onclick="windowlocation='/order/';" icon=pushpin text="Request John Shannon"}
	
	$xhtml = '';

	if( in_array('icon', array_keys($params)) && ! file_exists( constant('FRAMEWORK_PATH') . "/images/icon/16x16/{$params['icon']}.png" ) ) {
		$smarty->trigger_error("button: icon you desired was not found in the framework icon path ({$params['icon']})");
	}
	else {
		
    	$text		= ( isset( $params['text'] ) ? $params['text'] : ( isset( $params['label'] ) ? $params['label'] : '&nbsp;' ) );
		$leftmargin = ( isset( $params['left']  )    ? $params['left'] : ''     );
    	$pad        = ( isset( $params['pad']  )     ? $params['pad']  : ''     );
    	$type       = ( isset( $params['type']  )    ? $params['type'] : ''     );
    	$onclick    = ( isset( $params['onclick'] )  ? $params['onclick'] : ''  );
    	$icon       = ( isset( $params['icon']  )    ? $params['icon'] : ''     );
    	$navigate   = ( isset( $params['navigate'] ) ? $params['navigate'] : '' );
    	$name       = ( isset( $params['name']  )    ? $params['name'] : ( isset( $params['id'] ) ? $params['id'] : '' ) );
    	
    	$xhtml = "<div class=\"buttons\" style=\"";
    	if( $leftmargin != '' ) { $xhtml .= "margin-left:{$leftmargin};"; }
    	if( $pad != '' ) { $xhtml .= "padding:{$pad};"; }
    	$xhtml .= "\"><button";
    	
    	if( $name != '' )     { $xhtml .= " name=\"{$name}\" id=\"{$name}\""; }
    	if( $type != '' )     { $xhtml .= " class=\"{$type}\""; }
    	if( $navigate != '' ) { $xhtml .= " onclick=\"window.location='{$navigate}';\""; }
    	if( $onclick != '' )  { $xhtml .= " onclick=\"{$onclick}\""; }
    	$xhtml .= ">";
    	if( $icon != '' ) { $xhtml .= "<img src=\"/pegasus/images/icon/16x16/{$icon}.png\" alt=\"\"/>"; }
    	$xhtml .= $text;
    	$xhtml .= "</button></div>";
    }    	
   
    return $xhtml;
}
?>