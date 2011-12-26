<?php
/*
* Smarty plugin
 * -------------------------------------------------------------
 * File:     function.date_field.php
 * Type:     function
 * Name:     button
 * Purpose:  produces a text field and trigger button
 * -------------------------------------------------------------
 */
 /**
 * Smarty date_field function plugin
 *
 * Type:     function<br/>
 * Name:     date_field<br/>
 * Purpose:  create input field for date using string formatted by PHP5.2's DateTime type<br/>
 * Input:<br/>
 *         - string: input date string
 * @author Taras Ilnytskyy (procoding@gmail.com)
 * @param string DateTime object
 * @param string
 * @return string
 */

function smarty_function_date_field($params, &$smarty) {

   	$name = isset( $params['name'] ) ? $params['name'] : '';
   	$class = isset( $params['class'] ) ? $params['class'] : 'fc_';
   	$value = isset( $params['value'] ) ? $params['value'] : '';
   	$label = isset( $params['label'] ) ? $params['label'] : '&nbsp;';
   	$tdl  = isset( $params['tdl'] ) ? $params['tdl'] : $class.'labelcell';
   	$tdd  = isset( $params['tdd'] ) ? $params['tdd'] : $class.'datacell';
   	$tablemode = isset( $params['tm'] ) ? ( $params['tm'] ? true : false ) : false;
   	$readonly = isset( $params['readonly'] ) ? ( $params['readonly'] ? true : false ) : false;
   	$nodiv = isset( $params['nodiv'] ) ? ( $params['nodiv'] ? true : false ) : false;
   	//taras: additional custom params like onClick or other (for example I need to have readonly field, but not a div)
   	$custom = isset( $params['custom'] ) ? $params['custom'] : '';
   	// taras: date format
   	$format = isset( $params['format'] ) ? $params['format'] : 'm/d/Y';
   	$trigger = isset( $params['trigger'] ) ? $params['trigger'] : '...';

   	//taras: to add blank lines when printing a blank document with empty field values
   	//only for readonly mode
   	//{fc} call format: {fc emptyline=true}
   	//output: <input type=text style="border-width:0px;border-bottom-width:1px;">
   	$addemptyline = isset( $params['emptyline'] ) ? $params['emptyline'] : '';
   	//taras: to sett emptyline style, for example: emptylinestyle='width:100%'
   	$addemptylinestyle = isset( $params['emptylinestyle'] ) ? $params['emptylinestyle'] : '';

   	$newlyCreated = false;
   	// creating value
	if( ! $value ) {
//    		if (!$addemptyline) {
  //  			$dte = new DateTime('now');
    //		}
    	//	else
    	$dte = '';
	} elseif( $value instanceof DateTime ) {
    		$dte = clone $value;
	} else {
		$dte = new DateTime($value);
	}
	if (is_object($dte)) $value = $dte->format($format);
	else $value = 'n/a';

	if( $readonly ) {
   		if ($nodiv) {
   			return ( $value != '' ? $value : '' );
   		} else {
   			if (empty($value) && $addemptyline) {
   				$xhtml = empty_line($addemptyline, $addemptylinestyle, $class, '');
   			} else {
   				$xhtml = '<div class="'.$class.'readonly">';
   					if( $value != '' ) $xhtml .= $value;
	   			$xhtml .= '</div>';
   			}
   		}
   	} else {
   		$xhtml = '<input type="text"';
   		$xhtml .= ' size="10"';
   		$xhtml .= ' readonly="readonly"';
		if( $name != '' ) { $xhtml .= ' name="'.$name.'" id="'.$name.'"'; }
   		$xhtml .= ' maxlength="10"';
   		if( $value != '' ) { $xhtml .= ' value="'.$value.'"'; }
   		else { $xhtml .= ' value="n/a"'; }
   		if( $custom != '' ) { $xhtml .= ' '.$custom; }
   		$xhtml .=' style="font-size:100%;" />';
   		if( $name != '' ) {
   			$xhtml .='<button id="trigger_' . $name . '_date">' . $trigger . '</button>';
   		}
   	}
   	if( $tablemode ) { $xhtml = '<tr><td'.($tdl == '' ? '' : ' class="'.$tdl.'"').' style="vertical-align:top;">'.$label.($label == '' ? '' : ':').'</td><td'.($tdd == '' ? '' : ' class="'.$tdd.'"').'>'.$xhtml.'</td></tr>'; }
   	return $xhtml;
}

?>