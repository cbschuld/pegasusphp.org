<?php
/*
 * Smarty plugin
 * -------------------------------------------------------------
 * File:     function.sitemappath.php
 * Type:     function
 * Name:     clear
 * Purpose:  produce an clear object
 * -------------------------------------------------------------
 */
function smarty_function_sitemappath($params, &$smarty) {
	$retval = '';
	$siteMapPath = isset($smarty->_tpl_vars['sitemappath']) ? $smarty->_tpl_vars['sitemappath'] : array();
    foreach( $siteMapPath as $path ) {
    	if( $retval != '' ) { $retval .= ' &raquo; ' ; }
    	$retval .= $path->getMarkup();
    }
    return $retval;
}
?>