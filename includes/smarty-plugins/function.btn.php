<?php
/*
 * Smarty plugin
 * -------------------------------------------------------------
 * File:     function.btn.php
 * Type:     function
 * Name:     button
 * Purpose:  produces a simple button object
 * -------------------------------------------------------------
 */
function smarty_function_btn($params, &$smarty) {

	// {btn class=vista onclick="windowlocation='/order/';" icon=pushpin text="Request John Shannon"}

	$xhtml = '';

    $size  = ( isset( $params['size'] ) ? $params['size'] : 16 );
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
	
	$urlencode = ( isset( $params['urlencode']  ) ? $params['urlencode']  : false );
	$icon      = ( isset( $params['icon']  ) ? $params['icon']  : '' );
	$target    = ( isset( $params['target']  ) ? $params['target']  : '' );
	$rel       = ( isset( $params['rel']   ) ? $params['rel'] : '' );
	$rpad      = ( isset( $params['rpad']  ) ? $params['rpad']  : '' );
	$lpad      = ( isset( $params['lpad']  ) ? $params['lpad']  : '' );
	$fontsize  = ( isset( $params['fontsize']  ) ? $params['fontsize']  : 0 );
	$onclick = ( isset( $params['onclick'] ) ? $params['onclick'] : ( isset( $params['action'] ) ? $params['action'] : '' ) );
	$href    = ( isset( $params['href']  ) ? $params['href']  : ( isset( $params['url']  ) ? $params['url']  : 'javascript:;' ) );
	$name    = ( isset( $params['name']  ) ? $params['name'] : ( isset( $params['id'] ) ? $params['id'] : '' ) );
	$clear   = isset($params['clear']);
	
	$rpad = str_replace('px','',$rpad);
	$lpad = str_replace('px','',$lpad);
	
	// Replace #LB#,[LB] and #RB#,[RB] with left and right brace respectively
	$onclick = str_replace("#LB#", "{", $onclick );
	$onclick = str_replace("#RB#", "}", $onclick );
	$onclick = str_replace("[LB]", "{", $onclick );
	$onclick = str_replace("[RB]", "}", $onclick );
	
	// Replace the use of [request param1=value1 param2=value2] in href to be an expanded request value
	$requestArray = array();
	if( strpos($href,'[request ') !== false ) {
		preg_match('/\[request ([^\]]+)\]/', $href, $matches);
		if( $matches && count($matches) > 1 ) {
			$keywords = preg_split( "/([^ ]*=[\\\"'][^\\\"']+[\\\"'][\s]*)|[\s]+/", $matches[1], 0, PREG_SPLIT_DELIM_CAPTURE );
			foreach( $keywords as $keyword ) {
				if( $keyword != '' ) {
					if( count($equation = preg_split('/=/',$keyword)) == 2 ) {
						$pattern = '/^[\'"](.*)[\'"]/';
						$replace = '${1}';
						$requestArray[$equation[0]] = preg_replace($pattern,$replace,$equation[1]);
					}
				}
			}
			$href = str_replace($matches[0],Request::createRequest($requestArray),$href);
		}
	}
	
	// Replace the use of [request param1=value1 param2=value2] in onclick to be an expanded request value
	$requestArray = array();
	if( strpos($onclick,'[request ') !== false ) {
		preg_match('/\[request ([^\]]+)\]/', $onclick, $matches);
		if( $matches && count($matches) > 1 ) {
			$keywords = preg_split( "/([^ ]*=[\\\"'][^\\\"']+[\\\"'][\s]*)|[\s]+/", $matches[1], 0, PREG_SPLIT_DELIM_CAPTURE );
			foreach( $keywords as $keyword ) {
				if( $keyword != '' ) {
					if( count($equation = preg_split('/=/',$keyword)) == 2 ) {
						$requestArray[$equation[0]] = $equation[1];
					}
				}
			}
			$onclick = str_replace($matches[0],Request::createRequest($requestArray),$onclick);
		}
	}
	
	if( $icon != '' && substr( $params['icon'], 0, 1 ) != '/' ) {
		$icon = Icon::url($icon,$size,$size);
	}
	
	if( $fontsize == 0 ) { $fontsize = $size - 4; }
	
	$ahref = '<a ';
	$ahref .= 'style="clear:both;font-size:' . $fontsize . 'px;';
	
	$pad = ( $size - $fontsize - 4 );
	if( $pad > 0 ) {
		$ahref .= 'padding:'.($pad+2).'px;';
	}
	
	$ahref .= '"';
	$ahref .= ' href="'.$href.'"';
	if( $onclick != '' ) { $ahref .= ' onclick="'.$onclick.'"'; }
	if( $target != ''  ) { $ahref .= ' target="'.$target.'"';   }
	if( $rel != '' ) { $ahref .= ' rel="'.$rel.'"'; }
	$ahref .= '>';
	
	$xhtml  = "\n";
	$xhtml .= '<div';
	
	$style = '';
	
	if( $width != '' ) { $style .= 'width:'.$width.'px;'; }
	if( $rpad != '' ) { $style .= 'margin-right:'.$rpad.'px;'; }
	if( $lpad != '' ) { $style .= 'margin-left:'.$lpad.'px;'; }
	
	if( $style != '' ) { $xhtml .= ' style="'.$style.'"'; }
	
	if( $name != '' ) { $xhtml .= ' id="'.$name.'"'; }
	
	$xhtml .=' class="'.$class.'">';
	$xhtml .= $ahref.( $icon != '' ? '<img style="float:left;" src="'.$icon.'" alt="'.$text.'" />' : '' ).$text.'</a>';
	
	$xhtml .= '</div>';
	if( $clear ) { $xhtml .= '<div style="clear:both;"></div>'; }
	
	$xhtml .= "\n";
	
	return $urlencode ? urlencode($xhtml) : $xhtml;
}
?>