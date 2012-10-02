<?php
/**
 * Smarty plugin
 * @package Smarty
 * @subpackage plugins
 */


/**
 * Smarty array modifier plugin
 *
 * Type:     modifier<br/>
 * Name:     array<br/>
 * Date:     Sep 27, 2005
 * Purpose:  transform coma-separated string to array
 * Input:    separator default "," (coma )
 * Example:  {$var|array:"~"}
 * @author   Alex Blex <alex at aztecsoftware dot net>
 * @version 1.0
 * @param string
 * @param string
 * @return array
 */
function smarty_modifier_array($string, $delim=",")
{
	return explode($delim,$string);
}

/* vim: set expandtab: */

?>