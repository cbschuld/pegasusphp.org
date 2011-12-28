<?php
	header("Content-Type: text/css"); 
	echo "@CHARSET \"ISO-8859-1\"\n";

	$modules = array();
	$dir = dirname(__FILE__) .'/';
	if( isset($_GET['m']) && ! isset($_GET['modules']) ) {
		$_GET['modules'] = $_GET['m'];
	}
	if( isset($_GET['modules']) ) {
		$modules = explode(',',$_GET['modules']);
		foreach( $modules as $module ) {
			$cssFile = $dir . $module . '.css';
			if( file_exists($cssFile) ) {
				echo '/* Pegasus CSS Include: ', $module, " */\n";
				echo file_get_contents($cssFile);
			}
			else {
				echo '/* Pegasus CSS Could Not Find Module: ', $module, '(', $cssFile, ") */\n";
			}
		}
	}
?>