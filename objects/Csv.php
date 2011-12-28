<?php
	/**
	 * A class for working with CSV files
	 * @author cbschuld
	 * @package PegasusPHP
	 */
	class Csv {

		/**
		 * Send CSV output from a two dimensional array to the user's browser via
		 * a mime type of text/csv (via RFC4180)
		 * @param $csvData2DArray mixed two dimensional array containing the CSV data to present the user
		 * @param $filename string the filename to show at the browser level
		 * @return n/a (no return value at -- script exits)
		 */
		public static function sendFile($csvData2DArray,$filename) {
			
			// create a 5MB memory space & file handle
			$csv = fopen('php://temp/maxmemory:'. (5*1024*1024), 'r+');
	
			// write the content of the 2D array to the handle and rewind
			for($index = 0; $index < count($csvData2DArray); $index++ ) {
				fputcsv($csv,$csvData2DArray[$index]);
			}
			$size = ftell($csv);
			rewind($csv);
	
			// The outgoing mime type is text/csv according to RFC 4180
			// (RFC4180: Common Format and MIME Type for Comma-Separated Values (CSV) Files).
			// Send CSV content to the user:			
			header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
			header("Content-type: text/csv");
			header("Content-Disposition: attachment; filename=\"$filename\"");
			header("Content-Length: {$size}");
			fpassthru($csv);
			exit;
		}
		
		public static function saveFile($csvData2DArray,$filename) {
			$fp = fopen($filename, 'w');
			for($index = 0; $index < count($csvData2DArray); $index++ ) {
				fputcsv($fp,$csvData2DArray[$index]);
			}
			fclose($fp);			
		}
		
		public static function saveToFileHandle($csvData2DArray,$filehandle) {
			for($index = 0; $index < count($csvData2DArray); $index++ ) {
				fputcsv($filehandle,$csvData2DArray[$index]);
			}
		}
		
		public static function fileToArray($filename,$delimiter=",") {
		    if(!file_exists($filename) || !is_readable($filename))
		        return false;
		
		    $header = null;
		    $data = array();
		    
		    if(($handle = fopen($filename, 'r')) !== false) {
		        while(($row = fgetcsv($handle, 4096, $delimiter)) !== false) {
		            if( ! $header ) {
		                $header = $row;
		            }
		            else {
		            	if( count($header) != count($row) ) {
		            		$headerDump = print_r($header,true);
		            		$rowDump = print_r($row,true);
		            		error_log( "CSV::fileToArray() failed on a call to array_combine");
		            		error_log( "Header: {$headerDump}");
		            		error_log( "Row: {$rowDump}");
		            	}
		            	$data[] = array_combine($header, $row);
		            }
		        }
		        fclose($handle);
		    }
		    return $data;
		}
				
		public static function fileToArrayWithHeader($filename,$header,$delimiter=",") {
		    if(!file_exists($filename) || !is_readable($filename))
		        return false;
		
		    $data = array();
		    
		    if(($handle = fopen($filename, 'r')) !== false) {
		        while(($row = fgetcsv($handle, 4096, $delimiter)) !== false) {
	                $data[] = array_combine($header, $row);
		        }
		        fclose($handle);
		    }
		    return $data;
		}
				
	}
?>