<?php
/*
 * Smarty plugin
 * -------------------------------------------------------------
 * File:     function.input.php
 * Type:     function
 * Name:     clear
 * Purpose:  produce an clear object
 * -------------------------------------------------------------
 */
function smarty_function_input($params, &$smarty) {

	$retval = '';
	
	$type = ( isset( $params['type'] ) ? $params['type'] : 'text' );
	$name = ( isset( $params['name'] ) ? $params['name'] : '' );
	$id   = ( isset( $params['id']   ) ? $params['id']   : $name );
	$size = ( isset( $params['size'] ) ? $params['size'] : '' );
	
	$value = ( isset( $params['value'] ) ? $params['value'] : '' );
	$label = ( isset( $params['label'] ) ? $params['label'] : '' );
	
	$checked = ( isset( $params['checked'] ) ? $params['checked'] : false);
	$readonly = ( isset( $params['readonly'] ) ? ( $params['readonly'] ? true : false ) : false);
	
	$maxlength = ( isset( $params['maxlength'] ) ? $params['maxlength'] : '' );
	
	if( $label != '' ) { $retval .= '<label for="'.$id.'">'; }
	
	$retval .= '<input ';
	$retval .= 'type="'.$type.'" ';
	$retval .= 'name="'.$name.'" ';
	$retval .= 'id="'.$id.'" ';
	
	if( $size != '' ) { $retval .= 'size="'.$size.'" '; }
	if( $maxlength != '' ) { $retval .= 'maxlength="'.$id.'" '; }
	if( $value != '' ) { $retval .= 'value="'.$value.'" '; }
	if( $checked ) { $retval .= 'checked="checked" '; }
	if( $readonly ) { $retval .= 'readonly="readonly" '; }
	
	$retval .= '/>';
	
	if( $label != '' ) { $retval .= ' ' . $label.'</label>'; }
	
	return $retval;
	
}
?>