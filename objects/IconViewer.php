<?php
	/**
	 * IconViewer
	 *
	 * @package PegasusPHP
	 */
	class IconViewer {
		public static function view($directory,$url,$columns=4) {
			if( is_dir($directory) ) {
				if( $dh = opendir($directory) ) {
					echo '<table style="border-collapse:collapse;background:#fff;border-left:1px solid #ccc;border-top:1px solid #ccc;">';
					$count = 0;
					$files = array();
					while( ( $file = readdir($dh)) !== false ) {
						array_push($files,$file);
					}
					asort($files);
					foreach($files as $file) {
						if( is_file($directory . $file) ) {
							if( $count++ % $columns == 0 ) { echo '<tr>'; }
							echo '<td style="border-bottom:1px solid #ccc;"><img src="'.$url.$file.'" /></td><td style="border-bottom:1px solid #ccc;border-right:1px solid #ccc;">'.$file.'</td>';
						}
					}
					echo '<table>';
					closedir($dh);
				}
			}
		}
		
		public static function read($directory,$url,$columns=4) {
			if( is_dir($directory) ) {
				if( $dh = opendir($directory) ) {
					$strOut = '';
					$strOut .= '<table style="border-collapse:collapse;background:#fff;border-left:1px solid #ccc;border-top:1px solid #ccc;">';
					$count = 0;
					$files = array();
					while( ( $file = readdir($dh)) !== false ) {
						array_push($files,$file);
					}
					asort($files);
					foreach($files as $file) {
						if( is_file($directory . $file) ) {
							if( $count++ % $columns == 0 ) { $strOut .= '<tr>'; }
							$strOut .= '<td style="border-bottom:1px solid #ccc;"><img src="'.$url.$file.'" /></td><td style="border-bottom:1px solid #ccc;border-right:1px solid #ccc;">'.$file.'</td>';
						}
					}
					$strOut .= '<table>';
					closedir($dh);
					return $strOut;
				}
			}
		}
		
		public static function readAsArray($directory,$url,$withExtension=0) {
			if( is_dir($directory) ) {
				if( $dh = opendir($directory) ) {
					$aryOut = array();
					$count = 0;
					$files = array();
					while( ( $file = readdir($dh)) !== false ) {
						array_push($files,$file);
					}
					asort($files);
					foreach($files as $file) {
						if( is_file($directory . $file) ) {
							$aryOut[$file] = $withExtension ? $file : substr($file, 0, strrpos($file, '.')); ;
						}
					}
					closedir($dh);
					return $aryOut;
				}
			}
		}
		
	}
?>