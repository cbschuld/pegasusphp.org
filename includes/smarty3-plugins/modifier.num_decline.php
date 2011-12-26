<?php
/**
 * Smarty plugin
 * @package Smarty
 * @subpackage plugins
 */


/**
 * Smarty replace modifier plugin
 *
 * Type:     modifier<br/>
 * Name:     num_decline<br/>
 * @param string
 * @param string
 * @param string
 * @param string
 * @return string
 */
function smarty_modifier_num_decline($num,  $sing,  $plur)
{
    if($num == 1){
        return $sing;
    }else{
        return $plur;
    }
}

/* vim: set expandtab: */

?> 