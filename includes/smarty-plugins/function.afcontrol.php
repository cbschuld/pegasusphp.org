<?php
/*
 * Smarty plugin
 * -------------------------------------------------------------
 * File:     function.afcontrol.php
 * Type:     function
 * Name:     afcontrol (activeform control)
 * Purpose:  produce an activeform object
 * -------------------------------------------------------------
 */
function smarty_function_afcontrol($params, &$smarty) {
	
	Pegasus::addJavaScript('/pegasus/scripts/activeform.js');
	
	$strContent = '';

	if (!in_array('type', array_keys($params))) {
		$smarty->trigger_error("activeform: missing 'type' parameter - define the type of activeform object (text,checkbox)");
    }
    else if (!in_array('name', array_keys($params))) {
		$smarty->trigger_error("activeform: missing 'name' parameter - define the name of activeform object");
    }
    else {
		$labelWidth = isset($smarty->_tpl_vars['activeform']) ? $smarty->_tpl_vars['activeform']->lwidth : '';
		$labelAlign = isset($smarty->_tpl_vars['activeform']) ? $smarty->_tpl_vars['activeform']->lalign : 'left';
		
		$field = new stdClass();
		$field->type          = $params['type']; 
		$field->name          = $params['name']; 
		$field->value         = ( isset( $params['value']  )          ? $params['value']         : '' );
		$field->class         = ( isset( $params['class']  )          ? $params['class']         : '' );
		$field->validation    = ( isset( $params['validation']  )     ? $params['validation']    : '' );
		$field->validationurl = ( isset( $params['validationurl']  )  ? $params['validationurl'] : '' );
		$field->validationmsg = ( isset( $params['validationmsg']  )  ? $params['validationmsg'] : '' );
		$field->footnote      = ( isset( $params['footnote']  )       ? $params['footnote']      : '' );
		$field->size          = ( isset( $params['size']   )          ? $params['size']          : 0 );
		$field->length        = ( isset( $params['length'] )          ? $params['length']        : 0 );
		$field->rows          = ( isset( $params['rows']   )          ? $params['rows']          : 5 );
		$field->cols          = ( isset( $params['cols'] )            ? $params['cols']          : 10 );
		$field->pmux          = (isset( $params['pmux'] )             ? $params['pmux']          : 8 );
		$field->required      = (bool)( isset( $params['required']  ) ? $params['required']      : false );
		$field->verify        = (bool)( isset( $params['verify']  )   ? $params['verify']        : false );
		$field->label         = ( isset( $params['label'] )           ? $params['label']         : '' );
		$field->showlabel     = (bool)( isset( $params['showlabel'] ) ? $params['showlabel']     : ( isset($smarty->_tpl_vars['activeform']) ? $smarty->_tpl_vars['activeform']->showlabel : false ) );
		$field->cleardiv      = (bool)( isset( $params['cleardiv'] )  ? $params['cleardiv']      : true );
		$field->onblur        = ( isset( $params['onblur'] )          ? $params['onblur']        : '' );
		$field->options       = ( isset( $params['options'] )         ? $params['options']       : array() );
		$field->lwidth        = ( isset( $params['lwidth'] )          ? $params['lwidth']        : $labelWidth );
		$field->lalign        = ( isset( $params['lalign'] )          ? $params['lalign']        : $labelAlign );
		$field->readonly      = (bool)( isset( $params['readonly'] )  ? $params['readonly']      : ( isset($smarty->_tpl_vars['activeform']) ? $smarty->_tpl_vars['activeform']->readonly : false ) );
		
		$formName = isset($smarty->_tpl_vars['activeform']) ? $smarty->_tpl_vars['activeform']->name : '';

		// Replace #LB# and #RB# with left and right brace respectively
		$field->onblur = preg_replace("/#LB#/", "{", $field->onblur );
		$field->onblur = preg_replace("/#RB#/", "}", $field->onblur );

		// Replace #VARNAME# with JQuery Name
		$field->onblur = preg_replace("/\#([^\#]*)\#/", "\$('\#".$formName."_\\1')", $field->onblur );
		
		$smarty->assign('field',$field);
		
		switch( $field->type ) {
			default:
				$smarty->trigger_error("activeform: missing active form control type '{$field->type}'.  Check your activeform for valid control types.");
				break;
			case( 'text' ):
			case( 'textbox' ):
				if( $field->length == 0 ) { 
					$smarty->trigger_error("activeform: active form control type '{$field->type}' has declared maximum length of zero (0) -- set length > 0");
				}
				$strContent = $smarty->fetch('pegasus:activeform/textbox.tpl'); 
				break;
			case( 'password' ):
				$strContent = $smarty->fetch('pegasus:activeform/password.tpl'); 
				break;
			case( 'checkbox' ):
				$strContent = $smarty->fetch('pegasus:activeform/checkbox.tpl'); 
				break;
			case( 'dropdown' ):
				$strContent = $smarty->fetch('pegasus:activeform/dropdown.tpl'); 
				break;
			case( 'textarea' ):
				$strContent = $smarty->fetch('pegasus:activeform/textarea.tpl'); 
				break;
			case( 'button' ):
				$strContent = $smarty->fetch('pegasus:activeform/button.tpl'); 
				break;
			case( 'colorpicker' ):
				$strContent = $smarty->fetch('pegasus:activeform/colorpicker.tpl'); 
				break;
			case( 'hidden' ):
				$strContent = $smarty->fetch('pegasus:activeform/hidden.tpl'); 
				break;
		}
    }

    $strContent = preg_replace("/^\s*/","",$strContent);
    $strContent = preg_replace("/\s*$/","",$strContent);
    $strContent = preg_replace("/^$/","",$strContent);
    $strContent = preg_replace("/^\n$/","",$strContent);
    //$strContent = preg_replace("/\n/","",$strContent);
    //$strContent = preg_replace("/\t/","",$strContent);
    
    return "\n".$strContent;
}
?>