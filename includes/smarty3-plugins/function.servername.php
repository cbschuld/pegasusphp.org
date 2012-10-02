<?php
/*
 * Smarty plugin
 * -------------------------------------------------------------
 * File:     function.servername.php
 * Type:     function
 * Name:     servername
 * Purpose:  produce the servername for absolute URI links
 * -------------------------------------------------------------
 */
function smarty_function_servername($params, &$smarty) {
    return Pegasus::getServername();
}
?>