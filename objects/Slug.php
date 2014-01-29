<?php

	/**
	 * File: Slug.php
	 * Author: Chris Schuld (http://chrisschuld.com/)
	 * Last Modified: November 15, 2008
	 * @version 1.0
	 * @package PegasusPHP
	 * 
	 * Copyright (C) 2008 Chris Schuld  (chris@chrisschuld.com)
	 *
	 * This program is free software; you can redistribute it and/or
	 * modify it under the terms of the GNU General Public License as
	 * published by the Free Software Foundation; either version 2 of
	 * the License, or (at your option) any later version.
	 *
	 * This program is distributed in the hope that it will be useful,
	 * but WITHOUT ANY WARRANTY; without even the implied warranty of
	 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
	 * GNU General Public License for more details at:
	 * http://www.gnu.org/copyleft/gpl.html
	 *
	 *
	 * Typical Usage:
	 * 
	 *   Slug::setMaxLength(64);
	 *   echo Slug::generate('this is an interesting string to create a slug from');
	 *   
	 *   -- or --
	 *   
	 *   Slug::
	 *
	 */

	class Slug {

		private static $_maxlength = 64;
		private static $_fixed_length = false;

		public static function setMaxLength($size) { self::$_maxlength = $size; }
		public static function getMaxLength() { return self::$_maxlength; }
		public static function setFixedLength($bValue=true) { self::$_fixed_length = $bValue; }
		public static function getFixedLength() { return self::$_fixed_length; }

		public static function generate($txt) {
			$slug = '';
			$txt = self::sanitize($txt);
			$lastStop = 0;
			$bGenerated = false;
            if (!self::$_fixed_length && strlen($txt) < self::$_maxlength) {
                $slug = strtolower($txt);
            }
            else {
                for($i = 0; !$bGenerated && $i < strlen($txt); $i++) {
                    if( substr($txt,$i,1) == '-' ) {
                        $lastStop = $i;
                    }
                    if( self::$_fixed_length && $i == self::$_maxlength ) {
                        $slug = substr($txt,0,$i);
                        $bGenerated = true;
                    }
                    else if( ! self::$_fixed_length && $i > self::$_maxlength ) {
                        $slug = substr($txt,0,$lastStop);
                        $bGenerated = true;
                    }
                    else {
                        $slug .= substr($txt,$i,1);
                    }
                }
                if( ! $bGenerated && $slug != '' ) {
                    $slug = strtolower($slug);
                }
            }
			if( $slug == '' ) {
				$slug = md5($txt);
				if(self::$_fixed_length) {
					$slug = substr($slug,0,self::$_maxlength);
				}
			}
			return $slug;
		}

		/**
		 * Cleans the slugs content for the URL per RFC1738
		 * @param $txt String to clean and sanitize
		 * @return string Sanitized string (by RFC1738)
		 */
		private static function sanitize($txt) {
			// URL cleaning: http://www.faqs.org/rfcs/rfc1738.html
			$txt = str_replace("'",'',$txt);
			$txt = str_replace(",",'',$txt);
			// replace all non-alpha-numeric characters, remove multiple dashes and trailing dashes
			$txt = preg_replace('/[^a-z0-9]/', '-', strtolower($txt));
			$txt = preg_replace('/-+/', '-', $txt);
			//$txt = preg_replace('/^-/','',$txt);
			//$txt = preg_replace('/-$/','',$txt);
			return trim($txt,'- ');
		}

	};
