<?php
/*
 * Smarty plugin
 * -------------------------------------------------------------
 * File:     function.image.php
 * Type:     function
 * Name:     clear
 * Purpose:  produce an clear object
 * -------------------------------------------------------------
 */
function smarty_function_time_dropdown($params, &$smarty) {

	$xhtml = 'error';

	if( ! in_array('start', array_keys($params) ) ) {
		$smarty->trigger_error("time_dropdown: requires a start hour parameter");
	}
	else if( ! in_array('end', array_keys($params) ) ) {
		$smarty->trigger_error("time_dropdown: requires an end hour parameter");
	}
	else if( ! in_array('skip', array_keys($params) ) ) {
		$smarty->trigger_error("time_dropdown: requires an skip minute parameter");
	}
	else {
		$values = strtolower( isset( $params['values'] ) ? $params['values'] : 'text' );
		$name   = ( isset( $params['name'] ) ? $params['name'] : '' );
		$id     = ( isset( $params['id'] ) ? $params['id'] : '' );
		$id     = ( isset( $params['onchange'] ) ? $params['onchange'] : '' );
		$format = ( isset( $params['format'] ) ? $params['format'] : TIMELONG );
		$selected = ( isset( $params['selected'] ) ? $params['selected'] : 0 );

		$startTime = new DateTime('now');
		$endTime = new DateTime('now');
		$startTime->setTime($params['start'],0);
		$endTime->setTime($params['end'],0);

		$iterator = 0;

		$options = array();

		while( $endTime > $startTime ) {
			switch($values) {
				case('text'): default:
					$options[ $startTime->format('H:i:s') ] = $startTime->format($format);
					break;
				case('min'): case('minute'):
					$options[ $iterator ] = $startTime->format($format);
					break;
			}
			$iterator += (int)$params['skip'];
			$startTime->modify( '+ '.$params['skip'].' Minute');
		}

		$p = array();
		$p['name'] = $name;
		$p['id'] = $id;
		$p['options'] = $options;
		$p['selected'] = $selected;
		$xhtml = smarty_function_html_options($p,$smarty);
	}

	return $xhtml;
}
?>