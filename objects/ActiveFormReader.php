<?php
	/**
	 * ActiveFormReader
	 *
	 * @package PegasusPHP
	 */
	class ActiveFormReader {
		// Call as a Static::read('name_of_form',$object);
		public static function read($formName,&$object ) {
			if( $object ) {
				foreach( Request::getKeys() as $key ) {
					if( strpos($key,$formName.'_') === 0 ) {
						$method = 'set' . str_replace($formName.'_','',$key);
						
						if( method_exists($object,$method)) {
							$object->$method(Request::get($key));
						}
					}
				}
			}
		}
		
	}
?>