<?php 
	/**
	 * @package PegasusPHP
	 */
	class Grid4Icon {
		private $_bShow = true;
		private $_title;
		private $_url;
		private $_onclick;
		private $_confirmMessage = '';
		private $_icon;
		private $_id = '';
		private $_target = '';
		private $_linkCssClass = '';
		private $_iconCssClass = '';

		public function Grid4Icon($title,$icon,$url='',$onclick='') {
			$this->setTitle($title);
			$this->setIcon($icon);
			$this->setUrl($url);
			$this->setOnclick($onclick);
			return $this;
		}

		public function setShow($bVal) { $this->_bShow = $bVal; }
		public function setTitle($str) { $this->_title = $str; }
		public function setUrl($str) { $this->_url = $str; }
		public function setOnclick($str) { $this->_onclick = $str; }
		public function setIcon($str) { $this->_icon = $str; }
		public function setId($id) { $this->_id = $id; }
		public function setConfirmMessage($msg) { $this->_confirmMessage = str_replace("'",'', $msg); }
		public function setTarget($target) { $this->_target = $target; }
		public function setLinkCssClass($class) { $this->_linkCssClass = $class; }
		public function setIconCssClass($class) { $this->_iconCssClass = $class; }

		public function getTitle() { return $this->_title; }
		public function getUrl() {
			if(is_array($this->_id)) {
				$patt = array();
				$repl = array();
				foreach ($this->_id as $k => $v) {
					$patt[] = '/\{' . $k . '\}/';
					$repl[] = $v;
				}
				return preg_replace($patt, $repl, $this->_url);
			} else {
				$url = str_replace( '{ID}', $this->_id, $this->_url );
				if( preg_match( "/{request ([^}]+)}/", $url, $requestmatches) ) {
					if( preg_match_all('/ ?([^=]+)=([^ }]+)/', $requestmatches[1], $matches) ) {
						$requestItems = array();
						for( $matchCount = 0; $matchCount < count($matches[1]); $matchCount++ ) {
							$requestItems[ $matches[1][$matchCount] ] = $matches[2][$matchCount];
						}
						$request = Request::createRequest($requestItems);
					}
					return preg_replace("/{request ([^}]+)}/", $request, $url);
				}
				return $url;
			}
		}
		public function getOnclick() { return str_replace( '{ID}', $this->_id, $this->_onclick ); }
		public function getIcon() { return $this->_icon; }
		public function getId() { return $this->_id; }
		public function getShow() { return $this->_bShow; }
		public function getConfirmMessage() { return $this->_confirmMessage; }
		public function getTarget() { return $this->_target; }
		public function getLinkCssClass() { return $this->_linkCssClass; }
		public function getIconCssClass() { return $this->_iconCssClass; }

		public function getMarkup() {
			$xhtml = '';
			if( $this->getShow() ) {
				$xhtml .= '<a style="display:inline-block;" title="'.$this->getTitle().'" ';
				if( $this->_confirmMessage != '' && $this->_onclick == "" ) {
					$xhtml .= 'onclick="if( confirm(\''.$this->getConfirmMessage().'\') ) window.location=\''.$this->getUrl().'\';return false;" ';
				}
				else if( $this->_confirmMessage != '' && $this->_onclick != "" ) {
					$xhtml .= 'onclick="if( confirm(\''.$this->getConfirmMessage().'\') ) { '.$this->getOnclick().' }" ';
				}				
				else {
					if( $this->_onclick != '' ) { $xhtml .= 'onclick="'.$this->getOnclick().'" '; }
				}
				if( $this->_url != '' ) { $xhtml .= 'href="' . $this->getUrl() . '" '; } else { $xhtml .= 'href="javascript:;" '; }
				if ($this->_target != '') { $xhtml .= 'target="' . $this->_target . '" '; }
				if ($this->_linkCssClass != '') { $xhtml .= ' class="' . $this->_linkCssClass . '" '; }
				$xhtml .= '>';
				$xhtml .= '<img src="'.$this->getIcon().'" alt="'.$this->getTitle().'"';
				$xhtml .= ( $this->_iconCssClass != "" ? " {$this->_iconCssClass}" : "" ) . '"';
				$xhtml .= '/>';
				$xhtml .= '</a>';
			}
			return $xhtml;
		}
	}
?>