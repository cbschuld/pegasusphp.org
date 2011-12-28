<?php
	/**
	 * 
	 * @author cbschuld
	 * @package PegasusPHP
	 *
	 */
	class SitemapLocation {
		private $_location = '';
		private $_frequency = '';
		private $_last_mod = null;
		private $_priority = 0.5;
		
		public function getLocation() { return $this->_location; }
		public function getFrequency() { return $this->_frequency; }
		public function getLastModification() { return $this->_last_mod; }
		public function getPriority() { return $this->_priority; }
		
		public function setLocation($location) { $this->_location = htmlentities( $location ); }
		public function setFrequence($frequency) { $this->_frequency = $frequency; }
		public function setLastModification($datetime) { $this->_last_mod = $datetime; }
		public function setPriority($priority) { $this->_priority; }
		
		public function getXml() {
			return "\t<url>\n\t\t<loc>{$this->_location}</loc>\n\t\t<lastmod>{$this->_last_mod->format('Y-m-d')}</lastmod>\n\t\t<changefreq>{$this->_frequency}</changefreq>\n\t\t<priority>{$this->_priority}</priority>\n\t</url>\n";
		}
	}
?>