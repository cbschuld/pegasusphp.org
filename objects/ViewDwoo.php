<?php

	/**
	 * View
	 *
	 * @package PegasusPHP
	 */

	require_once(dirname(__FILE__).'/../includes/dwoo-1.0.0beta/dwooAutoload.php');
	
	class View {

		private static $_dwoo = null;
		private static $_dwoo_data = null;
		
		public static function create($cachePath='') {
			if( $cachePath == '' ) { $cachePath = constant('BASE_PATH').'/cache/'; }
			self::$_dwoo = new Dwoo($cachePath,$cachePath);
			self::$_dwoo->getLoader()->addDirectory(constant('FRAMEWORK_PATH').'/includes/dwoo-plugins/');
			self::$_dwoo_data = new Dwoo_Data();
		}
		
		public static function show($template) {
			echo self::fetch($template);
		}
		public static function fetch($template) {
			if( ! self::templateExists($template) ) {
				return 'ERROR: cannot find template: '.$template;
			}
			else {
				return self::$_dwoo->get('templates/'.$template, self::$_dwoo_data);
			}
		}
		
		public static function templateExists($filename) {
			return file_exists( constant('BASE_PATH').'/templates/'.$filename );
		}

		/**
		 * Assign a value to a key in the smarty template
		 *
		 * @param string $key
		 * @param string $value
		 */
		public static function assign($key,$value) {
			self::$_dwoo_data->assign($key,$value);
		}
		
		/**
		 * Assign a value to a key in the smarty template
		 *
		 * @param string $key
		 * @param string $value
		 */
		public static function assignByRef($key,&$value) {
			self::$_dwoo_data->assignByRef($key,$value);
		}
		public static function assign_by_ref($key,&$value) {
			self::$_dwoo_data->assignByRef($key,$value);
		}
		
	}

?>