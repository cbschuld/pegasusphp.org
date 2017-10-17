<?php
/*
 * Smarty plugin
 * -------------------------------------------------------------
 * File:     function.clear.php
 * Type:     function
 * Name:     clear
 * Purpose:  produce an clear object
 * -------------------------------------------------------------
 */
function smarty_function_clear($params, &$smarty) {
    return '<div style="clear:both;'.( isset( $params['pad'] ) ? 'padding:'.$params['pad'].'px;' : '' ).'"></div>';
}
?>