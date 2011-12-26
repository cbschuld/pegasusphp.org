<?php
/**
* Smarty plugin
* @package Smarty
* @subpackage plugins
*/

/**
* Smarty function plugin
* -------------------------------------------------------------
* Type:     function
* Name:     randnum
* Author:   Rob Ruchte (rob care of elementcstudios dot com)
* Purpose:  returns a random whole number
* -------------------------------------------------------------
* @param int floor optional lower range limit (defaults to 0)
* @param int ceiling optional upper range limit (defaults to 1000)
* @return int
* 
* 
* Example Usage:
* -----------------------------------------------------
* {randnum floor=1 ceiling=4}
* {if $randnum==1}
*     "This is my first quote" -rob
* {elseif $randnum==2}
*     "This is my second quote" -rob
* {elseif $randnum==3}
*     "This is my third quote" -rob
* {else}
*     "Are we there yet?" -rob    
* {/if}
* 
* 
* 
*/
function smarty_function_randnum($params, &$smarty)
{
    $floor = (array_key_exists('floor', $params)) ? $params['floor']:0;
    $ceiling = (array_key_exists('ceiling', $params)) ? $params['ceiling']:1000;

    $result = rand($floor, $ceiling);
    $smarty->assign('randnum', $result);
}