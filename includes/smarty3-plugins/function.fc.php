<?php
/*
 * Smarty plugin
 * -------------------------------------------------------------
 * File:     function.fc.php
 * Type:     function
 * Name:     button
 * Purpose:  produces a simple button object
 * -------------------------------------------------------------
 */
function smarty_function_fc($params, &$smarty) {

   	$name = isset( $params['name'] ) ? $params['name'] : '';
   	$type = isset( $params['type'] ) ? $params['type'] : 'text';
   	$size = isset( $params['size'] ) ? $params['size'] : '';
   	$class = isset( $params['class'] ) ? $params['class'] : 'fc_';
   	$style = isset( $params['style'] ) ? $params['style'] : '';
   	$value = isset( $params['value'] ) ? $params['value'] : '';
   	$label = isset( $params['label'] ) ? $params['label'] : '&nbsp;';
   	$tdl  = isset( $params['tdl'] ) ? $params['tdl'] : $class.'labelcell';
   	$tdd  = isset( $params['tdd'] ) ? $params['tdd'] : $class.'datacell';
   	$tablemode = isset( $params['tm'] ) ? ( $params['tm'] ? true : false ) : false;
   	$maxlength = isset( $params['maxlength'] ) ? $params['maxlength'] : '';
   	$readonly = isset( $params['readonly'] ) ? ( $params['readonly'] ? true : false ) : false;
   	$separator = isset( $params['separator'] ) ? ( $params['separator'] ? true : false ) : false;
   	$nodiv = isset( $params['nodiv'] ) ? ( $params['nodiv'] ? true : false ) : false;
   	//taras: textarea
   	$cols = isset( $params['cols'] ) ? $params['cols'] : '';
   	$rows = isset( $params['rows'] ) ? $params['rows'] : '';
   	$wrap = isset( $params['wrap'] ) ? $params['wrap'] : 'virtual';
   	//taras: select
   	$options = isset( $params['options'] ) ? $params['options'] : array();
   	// we'll use $value instead of $selected to simplify things:
   	//$selected = isset( $params['selected'] ) ? $params['selected'] : '';
   	$length = isset( $params['length'] ) ? $params['length'] : '';
   	$multiple = isset( $params['multiple'] ) ? ( $params['multiple'] ? true : false ) : false;
   	$checked = isset( $params['checked'] ) ? ( $params['checked'] ? true : false ) : false;

   	//taras: additional custom params like onClick or other (for example I need to have readonly field, but not a div)
   	$custom = isset( $params['custom'] ) ? $params['custom'] : '';

   	//taras: to add blank lines when printing a blank document with empty field values
   	//only for readonly mode
   	//{fc} call format: {fc emptyline=true}
   	//output: <input type=text style="border-width:0px;border-bottom-width:1px;">
   	$addemptyline = isset( $params['emptyline'] ) ? $params['emptyline'] : '';
   	//taras: to sett emptyline style, for example: emptylinestyle='width:100%'
   	$addemptylinestyle = isset( $params['emptylinestyle'] ) ? $params['emptylinestyle'] : '';

   	if ($separator) {
   		if( $tablemode ) return '<tr><td colspan="2" '.($tdl == '' ? '' : ' class="'.$tdl.'"').'>&nbsp;</td></tr>';
   		else return '<div>&nbsp;</div>';
   	}

   	if( $readonly ) {
   		if ($type == 'hidden') return;
   		if ($nodiv) {
   			if ($type == 'select') return (isset($options[$value]) ? $options[$value] : '');
   			else return $value;
   		} else {
   			if ($type == 'select') {
   				if (isset($options[$value]) && !empty($options[$value])) {
   					$xhtml = '<div class="'.$class.'readonly"';
   					if ($style != '') $xhtml .= ' style="' . $style . '"';
   					$xhtml .= '>';
   					$xhtml .= $options[$value];
   					$xhtml .= '</div>';
   				} else {
   						$xhtml = empty_line($addemptyline, $addemptylinestyle, $class, $style);
   				}
   				//$xhtml .= (isset($options[$value]) ? $options[$value] : '');
   			} else {
   				if (!empty($value)) {
   					$xhtml = '<div class="'.$class.'readonly"';
   					if ($style != '') $xhtml .= ' style="' . $style . '"';
   					$xhtml .= '>';
   					$xhtml .= $value;
   					$xhtml .= '</div>';
   				} else {
   					$xhtml = empty_line($addemptyline, $addemptylinestyle, $class, $style);
   				}
   				// $xhtml .= $value;
   			}
   		}
   		$xhtml .= '<input type="hidden"';
   		if( $name != '' ) { $xhtml .= ' name="'.$name.'" id="'.$name.'"'; }
   		if( $value != '' ) { $xhtml .= ' value="'.$value.'"'; }
   		$xhtml .=' />';
   		
   	}
   	else {
   		if ($type == 'textarea') {
   			$xhtml = '<textarea ';
   			if( $name != '' ) { $xhtml .= ' name="'.$name.'" id="'.$name.'"'; }
   			if( $cols != '' ) { $xhtml .= ' cols="'.$cols.'"'; }
   			if( $rows != '' ) { $xhtml .= ' rows="'.$rows.'"'; }
   			if( $wrap != '' ) { $xhtml .= ' wrap="'.$wrap.'"'; }
   			if( $custom != '' ) { $xhtml .= ' '.$custom; }
			if ($style != '') $xhtml .= ' style="' . $style . '"';
   			$xhtml .= '>';
   			if( $value != '' ) { $xhtml .= $value; }
   			$xhtml .= '</textarea>';
   		} elseif($type == 'select') {
   			$xhtml = '<select';
   			if( $name != '' ) { $xhtml .= ' name="'.$name.'" id="'.$name.'"'; }
   			if( $custom != '' ) { $xhtml .= ' '.$custom; }
   			if( $size != '' ) { $xhtml .= ' size="'.$size.'"'; }
   			if( $multiple ) { $xhtml .= ' multiple="multiple"'; }
			if ($style != '') $xhtml .= ' style="' . $style . '"';
   			$xhtml .=  '>' . "\n";
   			foreach ($options as $key => $val) {
   				$xhtml .= '<option value="' . $key . '"' . ($key == $value ? ' selected' : '') . '>' . $val . '</option>' . "\n";
   			}
   			$xhtml .=  '</select>' . "\n";
   		} else {
   			$xhtml = '<input type="'.$type.'"';
   			if( $size != '' ) { $xhtml .= ' size="'.$size.'"'; }
   			if( $name != '' ) { $xhtml .= ' name="'.$name.'" id="'.$name.'"'; }
   			if( $maxlength != '' ) { $xhtml .= ' maxlength="'.$maxlength.'"'; }
   			if( $value != '' ) { $xhtml .= ' value="'.$value.'"'; }
   			if( $custom != '' ) { $xhtml .= ' '.$custom; }
			if ($style != '') $xhtml .= ' style="' . $style . '"';
			if ($checked != '')  $xhtml .= ' checked="' . $checked . '"';
   			$xhtml .=' />';
   			//echo htmlspecialchars($xhtml);
   		}
   	}
   	if( $tablemode ) { $xhtml = '<tr><td'.($tdl == '' ? '' : ' class="'.$tdl.'"').' style="vertical-align:top;">'.$label.($label == '' ? '' : ':').'</td><td'.($tdd == '' ? '' : ' class="'.$tdd.'"').'>'.$xhtml.'</td></tr>'; }
   	return $xhtml;
}

//taras: added for emptyline // is it allowed to have a rutine func here?
function empty_line($addemptyline, $addemptylinestyle, $regularclass, $regularstyle) {
	$xhtml = '';
   					if ($addemptyline) {
   						$xhtml .= '<input type="text" size="32" style="width:100%;background:transparent;border-width:0px;border-bottom:1px solid #000;';
   						if ($addemptylinestyle != '') $xhtml .= '' . $addemptylinestyle . '';
   						$xhtml .= '" />';
   					} else {
							$xhtml .= '<div class="'.$regularclass.'readonly"';
		   					if ($regularstyle != '') $xhtml .= ' style="' . $regularstyle . '"';
		   					$xhtml .= '>&nbsp;';
		   					$xhtml .= '</div>';
   					}
   	return $xhtml;
}
?>