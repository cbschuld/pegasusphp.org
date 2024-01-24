<?php
/*
 * Smarty plugin
 * -------------------------------------------------------------
 * File:     function.qbutton.php
 * Type:     function
 * Name:     button
 * Purpose:  produce a simple button object
 * -------------------------------------------------------------
 */
function smarty_function_qbutton($params, &$smarty) {

	// {button type=positive onclick="windowlocation='/order/';" icon=pushpin text="Request John Shannon"}

	$xhtml = '';

	if( in_array('icon', array_keys($params)) && substr( $params['icon'], 0, 1 ) != '/' && ! file_exists( constant('FRAMEWORK_PATH') . "/images/icon/16x16/{$params['icon']}.png" ) ) {
		$smarty->trigger_error("qbutton: icon you desired was not found in the framework icon path ({$params['icon']}.png)");
	}
	else {
    	$text    = ( isset( $params['text'] ) ? $params['text'] : ( isset( $params['label'] ) ? $params['label'] : '&nbsp;' ) );
    	$style   = ( isset( $params['style'] ) ? $params['style'] : (( isset( $params['class'] ) ? $params['class'] : (isset($smarty->_tpl_vars['qbutton_default_style']) ? $smarty->_tpl_vars['qbutton_default_style'] : 'qbutton' )) ));
    	$icon    = ( isset( $params['icon']  ) ? $params['icon']  : '' );
    	//$align   = ( isset( $params['align'] )   ? $params['align'] : 'left' );
    	//$indent  = ( isset( $params['indent'] )  ? $params['indent'] : 0 );
    	//$width   = ( isset( $params['width'] )   ? $params['width'] : 0 );
    	$onclick = ( isset( $params['onclick'] ) ? $params['onclick'] : ( isset( $params['action'] ) ? $params['action'] : '' ) );
    	$href    = ( isset( $params['href']  ) ? $params['href']  : ( isset( $params['url']  ) ? $params['url']  : 'javascript:void(0);' ) );
    	$name    = ( isset( $params['name']  ) ? $params['name'] : ( isset( $params['id'] ) ? $params['id'] : '' ) );
    	$float   = ( isset( $params['float']  ) ? $params['float'] : '' );

		// Replace #LB# and #RB# with left and right brace respectively
		$onclick = preg_replace("/#LB#/", "{", $onclick );
		$onclick = preg_replace("/#RB#/", "}", $onclick );

		if( $icon != '' && substr( $params['icon'], 0, 1 ) != '/' ) {
    		$icon = '/pegasus/images/icon/16x16/'.$icon.'.png';
    	}

		$ahref = '<a href="'.$href.'"';
		if( $onclick != '' ) { $ahref .= ' onclick="'.$onclick.'"'; }
		$ahref .= '>';

		$xhtml  = "\n";
		$xhtml .= '<div';
		if( $name != '' ) { $xhtml .= ' id="'.$name.'"'; }
		if( $float != '' ) { $xhtml .= ' style="float:'.$float.';"'; }
		$xhtml .=' class="'.$style.'">';
		if( $icon != '' ) {
			$xhtml .= $ahref.'<img style="float:left;" src="'.$icon.'" alt="'.$text.'" /></a>';
		}
		$xhtml .= $ahref.$text.'</a>';
		$xhtml .= '</div>';
		$xhtml .= "\n";
	}
    return $xhtml;
}
?>