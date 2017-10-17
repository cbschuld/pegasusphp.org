<?php

	require_once( dirname(__FILE__)."/../objects/Slug.php");
	
	
	$_USAGE = "\nUsage: {$argv[0]} -p <path> [-v|--verbose]\n\n";

	$options = getopt( "p:v", array("verbose") );
	
	if( $options == false || ! isset($options["p"]) ) {
		echo $_USAGE;
	}
	else {
		
		$_PATH = $options["p"];

		$_FILES = readFolderDirectory($_PATH);
		
		foreach( $_FILES as $pathname ) {
			$pathparts = pathinfo($pathname);
			$filename = $pathparts["filename"];
			$path = $pathparts["dirname"];
			$ext = $pathparts["extension"];
			
			$newfilename = "{$path}/".Slug::generate($filename).".".$ext;
			if( $newfilename != $pathname ) {
				echo "Renaming: {$pathname}\n";
				$cmd = "mv \"{$pathname}\" \"{$newfilename}\"";
				system($cmd);
			}
			
		}

	}
	
	function readFolderDirectory($dir) { 
        $listDir = array(); 
        if($handler = opendir($dir)) { 
            while (($sub = readdir($handler)) !== FALSE) { 
                if ($sub != "." && $sub != "..") { 
                    if( is_file($dir."/".$sub ) ) {
                        $listDir[] = preg_replace("/\/\//","/",$dir."/".$sub);
                    }
                    elseif( is_dir($dir."/".$sub) ) { 
                        $listDir = array_merge($listDir, readFolderDirectory($dir."/".$sub)); 
                    } 
                } 
            } 
            closedir($handler); 
        } 
        return $listDir; 
    } 

?>