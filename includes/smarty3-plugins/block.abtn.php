<?php
/*
 * Smarty plugin
 * -------------------------------------------------------------
 * File:     block.btn.php
 * Type:     block
 * Name:     btn
 * Purpose:  
 * -------------------------------------------------------------
 */
function smarty_block_abtn($params, $content, &$smarty, &$repeat) {

	if( ! $repeat){
		$xhtml = '';
	
		if( in_array('icon', array_keys($params)) && substr( $params['icon'], 0, 1 ) != '/' && ! file_exists( constant('FRAMEWORK_PATH') . "/images/icon/16x16/{$params['icon']}.png" ) ) {
			$smarty->trigger_error("btn: icon you desired was not found in the framework icon path ({$params['icon']}.png)");
		}
		else {
	    	$text  = ( isset( $params['text'] ) ? $params['text'] : ( isset( $params['label'] ) ? $params['label'] : '&nbsp;' ) );
			$width = ( isset( $params['width'] ) ? $params['width'] : '');
	    	
	    	// Style can be either 'style', 'class', or a standard template var of 'btn_default_style'
	    	$class = ( isset( $params['style'] ) && $params['style'] != '' ) ? $params['style'] : '';
	    	if( $class == '' ) {
	    		$class = ( isset( $params['class'] ) && $params['class'] != '' ) ? $params['class'] : 'btn';
	    	}
	    	if( $class == '' ) {
	    		$class = ( isset( $smarty->_tpl_vars['btn_default_style'] ) && $smarty->_tpl_vars['btn_default_style'] != '' ) ? $smarty->_tpl_vars['btn_default_style'] : 'pgButton';
	    	}
		    
	    	$icon    = ( isset( $params['icon']  ) ? $params['icon']  : '' );
	    	$onclick = ( isset( $params['onclick'] ) ? $params['onclick'] : ( isset( $params['action'] ) ? $params['action'] : '' ) );
	    	$href    = $content;
	    	$name    = ( isset( $params['name']  ) ? $params['name'] : ( isset( $params['id'] ) ? $params['id'] : '' ) );
	    	$clear   = isset($params['clear']);
	    	
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
			
			if( $width != '' ) { $xhtml .= ' style="width:'.$width.'px"'; } 
			if( $name != '' ) { $xhtml .= ' id="'.$name.'"'; } 
			
			$xhtml .=' class="'.$class.'">';
			$xhtml .= $ahref.( $icon != '' ? '<img style="float:left;" src="'.$icon.'" alt="'.$text.'" />' : '' ).$text.'</a>';
			
			$xhtml .= '</div>';
			if( $clear ) { $xhtml .= '<div style="clear:both;"></div>'; }
			
			$xhtml .= "\n";
		}    	
	    return $xhtml;
	}
}
?>