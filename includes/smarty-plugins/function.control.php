<?php
/*
 * Smarty plugin
 */
function smarty_function_control($params, &$smarty) {
	
   	$name = isset( $params['name'] ) ? $params['name'] : '';
   	$id = isset( $params['id'] ) ? $params['id'] : $name;
   	$type = isset( $params['type'] ) ? $params['type'] : 'text';
   	$size = isset( $params['size'] ) ? $params['size'] : '';
   	$class = isset( $params['class'] ) ? $params['class'] : '';
   	$value = isset( $params['value'] ) ? $params['value'] : '';
   	$maxlength = isset( $params['maxlength'] ) ? $params['maxlength'] : '';
   	$readonly = isset( $params['readonly'] ) ? ( $params['readonly'] ? true : false ) : false;
   	$cols = isset( $params['cols'] ) ? $params['cols'] : '';
   	$rows = isset( $params['rows'] ) ? $params['rows'] : '';
   	
   	$xhtml = '';
   	
   	if( $readonly ) {
		$xhtml .= '<div style="padding:2px;margin:1px;background:#fefefe;border:1px solid #ccc;">'.$value.'</div>';
   	}
   	else {
   		if ($type == 'textarea') {
   			$xhtml = '<textarea ';
   			if( $name != '' ) { $xhtml .= ' name="'.$name.'"'; }
   			if( $id != '' ) { $xhtml .= ' id="'.$id.'"'; }
   			if( $cols != '' ) { $xhtml .= ' cols="'.$cols.'"'; }
   			if( $rows != '' ) { $xhtml .= ' rows="'.$rows.'"'; }
   			if( $custom != '' ) { $xhtml .= ' '.$custom; }
   			$xhtml .= '>';
   			if( $value != '' ) { $xhtml .= $value; }
   			$xhtml .= '</textarea>';
   		} else {
   			$xhtml = '<input type="'.$type.'"';
   			if( $size != '' ) { $xhtml .= ' size="'.$size.'"'; }
   			if( $name != '' ) { $xhtml .= ' name="'.$name.'"'; }
   			if( $id != '' ) { $xhtml .= ' id="'.$id.'"'; }
   			if( $maxlength != '' ) { $xhtml .= ' maxlength="'.$maxlength.'"'; }
   			if( $value != '' ) { $xhtml .= ' value="'.$value.'"'; }
   			if( $custom != '' ) { $xhtml .= ' '.$custom; }
   			$xhtml .='/>';
   		}
   	}
   	return $xhtml;
}
?>