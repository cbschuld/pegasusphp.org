<?php
/*
 * Smarty plugin
 * -------------------------------------------------------------
 * File:     function.date_field_trigger.php
 * Type:     function
 * Name:     date_field_trigger
 * Purpose:  creates a js trigger for date_field
 * -------------------------------------------------------------
 */
 /**
 * Smarty date_field_trigger function plugin
 *
 * Type:     function<br/>
 * Name:     date_field_trigger<br/>
 * Purpose:  creates a js trigger for date_field<br/>
 * Input:<br/>
 *         - string: field name
 * @author Taras Ilnytskyy (procoding@gmail.com)
 * @param string
 * @return string
 */

function smarty_function_date_field_trigger($params, &$smarty) {

   	$name = isset( $params['name'] ) ? $params['name'] : '';
   	$readonly = isset( $params['readonly'] ) ? ( $params['readonly'] ? true : false ) : false;

   	// JS Calendar API legacy:
   	//$onClose = isset( $params['onclose'] ) ? $params['onclose'] : '';
   	//$onSelect = isset( $params['onselect'] ) ? $params['onselect'] : '';
   	$onUpdate = isset( $params['onUpdate'] ) ? $params['onUpdate'] : '';

   	if( $readonly ) {
   		$xhtml = '';
	} else {
		$xhtml = 'if ($(addJslashes(\'#' . $name . '\')).length) { Calendar.setup({' . "\n"
   			.'inputField     :    "' . $name . '",' . "\n"
			.'ifFormat       :    "%m/%d/%Y",' . "\n"
			.'button         :    "trigger_' . $name . '_date",' . "\n"
			.'align          : "br",' . "\n"
			.'date           : $(addJslashes(\'#' . $name . '\')).val(),' . "\n"
			//. ($onSelect != '' ? 'onSelect           : ' . $onSelect . ',' . "\n" : '')
			//. ($onClose  != '' ? 'onClose            : ' . $onClose . ',' . "\n" : '')
			. ($onUpdate  != '' ? 'onUpdate            : ' . $onUpdate . ',' . "\n" : '')
			.'zIndex       : "8"' . "\n"
	 		.'});}' . "\n";
   	}
   	return $xhtml;
   	return $xhtml;
}
?>