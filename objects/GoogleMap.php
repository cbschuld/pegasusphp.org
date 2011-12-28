<?php

	/**
	 * GoogleMap
	 *
	 * @package PegasusPHP
	 */
	class GoogleMap {
		
		private $_apiKey = '';
		
		private $_address_line = '';
		private $_address_city  = '';
		private $_address_state = '';
		private $_address_zip   = '';
		private $_address_county = '';
		private $_address_latitude = 0;
		private $_address_longitude = 0;
		private $_accuracy = 0;
		private $_status = 0;
		private $_message = '';
		private $_address_used = '';
		private $_last_response = '';
		private $_json_response = '';
		private $_language = "en";
		
		public function setApiKey($value) { $this->_apiKey = $value; }
		public function getApiKey() { return $this->_apiKey; }
		public function setStatus($value) { $this->_status = $value; }
		public function getStatus() { return $this->_status; }
		public function getLastResponse() { return $this->_last_response; }
		public function setJSONResponse($value) { $this->_json_response = $value; }
		public function getJSONResponse() { return $this->_json_response; }
		public function setMessage($value) { $this->_message = $value; }
		public function getMessage() { return $this->_message; }
		public function getAddressUsed() { return $this->_address_used; }
		public function setAddressLine($value) { $this->_address_line = $value; }
		public function setAddressCity($value) { $this->_address_city = $value; }
		public function setAddressState($value) { $this->_address_state = $value; }
		public function setAddressZip($value) { $this->_address_zip = $value; }
		public function setLatitude($value) { $this->_address_latitude = $value; }
		public function setLongitude($value) { $this->_address_longitude = $value; }
		public function setLanguage($value) { $this->_language = $value; }
		
		public function getAddressLine() { return $this->_address_line; }
		public function getAddressCity() { return $this->_address_city; }
		public function getAddressState() { return $this->_address_state; }
		public function getAddressZip() { return $this->_address_zip; }
		public function getAddressCounty() { return $this->_address_county; }
		public function getLatitude() { return $this->_address_latitude; }
		public function getLongitude() { return $this->_address_longitude; }
		public function getAddressAccuracy() { return $this->_accuracy; }
		public function getLanguage() { return $this->_language; }
		
		public function verifyAddress() {

			$strAddress = $this->_address_line . ', ' . $this->_address_city . ',' . $this->_address_state . ' ' . $this->_address_zip;
			
			// Prepare Address
			// - Condense Whitespace
			// - Replace spaces with '+' signs
			// - Remove Apartment / Suite Numbers
			// - Remove # signs
			$strAddress = preg_replace( '/\s\s+/', ' ', $strAddress );
			$strAddress = preg_replace( '/ /', '+', $strAddress );
			$strAddress = preg_replace( '/\#\s*[0-9A-Za-z]+/', '', $strAddress );
			$strAddress = preg_replace( '/\#/', '', $strAddress );
			
			$this->_address_used = $strAddress;
			
			// Desired address
			$strURL = "http://maps.googleapis.com/maps/api/geocode/json?address={$strAddress}&language={$this->_language}&sensor=false";
			
			// Retrieve the URL contents
			$page = file_get_contents($strURL);
			
			$this->setJSONResponse($page);
			
			if( $page != "" ) { $data = @json_decode($page); }
		
			$this->_status = 0;
			if( isset($data->status) ) {
				$this->_status = $data->status;
			} 
			
			switch( $this->_status ) { 
				case( "ZERO_RESULTS" ):
					$this->_message = "No results for: " . $strAddress;
					break;
				case( "OVER_QUERY_LIMIT" ):
					$this->_message = "Over query limit.";
					break;
				case( "REQUEST_DENIED" ):
					$this->_message = "Request denied. Please check for sensor parameter.";
					break;
				case( "INVALID_REQUEST" ):
					$this->_message = "Invalid request. Please check the address or coordinates parameters.";
					break;
				case( "OK" ):
					$this->_message = "Successfully validated address.";
					
					$_street_number = "";
					$_street_name = "";
					$_city = "";
					$_state = "";
					$_zip = "";
					$_county = "";
					
					if( isset($data->results[0]) && isset($data->results[0]->address_components) ) { 
						foreach( $data->results[0]->address_components as $addressComponent ) {
							if( isset($addressComponent->types) && is_array($addressComponent->types) ) {
								foreach( $addressComponent->types as $type ) {
									switch( $type ) {
										case "street_number":
											// Street number
											$_street_number = $addressComponent->long_name;
										break;
										case "route":
											// Street name
											$_street_name = $addressComponent->long_name;
										break;
										case "locality":
										case "sublocality":
											// City / Town
											$_city = $addressComponent->long_name;
										break;
										case "administrative_area_level_1":
											// State Abbreviation
											$_state = $addressComponent->short_name;
										break;
										case "postal_code":
											// Zip
											$_zip = $addressComponent->short_name;
										break;
										case "administrative_area_level_2":
											// County
											$_county = $addressComponent->long_name;
										break;
									}
								}
							}
						}
						
						if( isset($data->results[0]->geometry) && isset($data->results[0]->geometry->location) ) {
							$this->_address_latitude 	= $data->results[0]->geometry->location->lat;
							$this->_address_longitude 	= $data->results[0]->geometry->location->lng;
						}
						
						$this->_address_line 	= $_street_number . " " . $_street_name;
						$this->_address_city 	= $_city;
						$this->_address_state 	= $_state;
						$this->_address_zip 	= $_zip;
						$this->_address_county 	= $_county;						
						
					}
				break;
			}			
			
			return $this->_status == "OK";	
		}
		
	}

?>