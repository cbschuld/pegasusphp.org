<?php
	/**
	 * 
	 * @author cbschuld
	 * @package PegasusPHP
	 *
	 */
	class Sitemap {
		
		const XML_HEADER = "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<urlset xmlns=\"http://www.sitemaps.org/schemas/sitemap/0.9\">\n";
		const XML_FOOTER = "</urlset>";
		
		const CHANGE_ALWAYS = 'always';
		const CHANGE_HOURLY = 'hourly';
		const CHANGE_DAILY = 'daily';
		const CHANGE_WEEKLY = 'weekly';
		const CHANGE_MONTHLY = 'monthly';
		const CHANGE_YEARLY = 'yearly';
		const CHANGE_NEVER = 'never';
		
		private $_location = array();
		private $_base_url = '';
		
		public function setBaseUrl($url) { $this->_base_url = $url; }
		public function getBaseUrl() { return $this->_base_url; }
		
		public function addLocation($location_url,$last_mod_datetimeobject,$change_frequency=self::CHANGE_MONTHLY,$priority=0.5) {
			$l = new SitemapLocation();
			$l->setLocation($this->_base_url.$location_url);
			$l->setLastModification($last_mod_datetimeobject);
			$l->setFrequence($change_frequency);
			$l->setPriority($priority);
			$this->_location[] = $l;
		}
		
		public function getXml() {
			$xml = self::XML_HEADER;
			foreach( $this->_location as $location ) {
				$xml .= $location->getXml();
			}
			$xml .= self::XML_FOOTER;
			return $xml;
		}
	}
?>