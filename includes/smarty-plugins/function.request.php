<?php
/*
* Smarty plugin
* -------------------------------------------------------------
* File:     function.request.php
* Type:     function
* Name:     request
* Purpose:  creates a standard request/oaf stamp for module
*           and action in the URL
* -------------------------------------------------------------
*/
function smarty_function_request($params, &$smarty)
{
	$aParamList = array();
	foreach( $params as $key => $value ) {
		$aParamList[$key] = $value;
    }
    
	// BASE64 NOTES: In the URL and Filename safe variant,
	// character 62 (0x3E) is replaced with a "-" (minus sign)
	// and character 63 (0x3F) is replaced with a "_" (underscore).
	    
   	$encodedObj = urlencode(
   							str_replace("+", "-",
	   							str_replace("/", "_",
							  			base64_encode(
							   				gzcompress(
							  					serialize($aParamList)
							  				)
							 			)
							   		)
								)	
							);
    return( constant('URL_VAR') . "={$encodedObj}" );
}
?>