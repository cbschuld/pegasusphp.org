<?php
	/**
	 * SimpleButton allows you to quickly generate a DIV-based link
	 * on a page which feels and looks like an image button.
	 * @package PegasusPHP 
	 */
	class SimpleButton {
		private $_imgUrl = '';
		private $_text = '';
		private $_url = '';
		private $_imgWidth = 16;
		private $_imgHeight = 16;
		private $_padding = '2px';
		private $_indent = 0;
		private $_isLeft = true;
		private $_style = 'Grey';
		private $_id = '';
		private $_action = '';
		private $_clear = false;
		private $_width = 0;
		private $_target = '';
		
		
		

		/**
		 * Set the ID/name of this link.  The ID/name is applied to
		 * the &lt;a&gt; tag within the button structure. 
		 *
		 * @param string $v The ID/name of this button
		 */
		public function setId($v) { $this->_id = (string)$v; }
		
		/**
		 * Get the ID/name of this link.  The ID/name is applied to
		 * the &lt;a&gt; tag within the button structure. 
		 * 		 *
		 * @return string The ID/name of this button
		 */
		public function getId() { return $this->_id; }
		
		/**
		 * Set the width of this button.
		 *
		 * @param string $v The ID/name of this button
		 */
		public function setWidth($v) { $this->_width = (int)$v; }
		
		/**
		 * Get the width of this button. 
		 * 		 *
		 * @return string The ID/name of this button
		 */
		public function getWidth() { return $this->_width; }
		
		/**
		 * Set amount of left indent before the button 
		 *
		 * @param int $v The amount of left indent before the button
		 */
		public function setIndent($v) { $this->_indent = (int)$v; }
		
		/**
		 * Get the amount of left indent before the button 
		 * 
		 * @return int The amount of left indent before the button
		 */
		public function getIndent() { return $this->_indent; }
			
		/**
		 * If set the button includes a float clear at the end of the button markup 
		 *
		 * @param string $v The ID/name of this button
		 */
		public function setClear($v=true) { $this->_id = (string)$v; }
		
		/**
		 * If set the button includes a float clear at the end of the button markup 
		 * 
		 * @return boolean The button will include a clear at the end of the button markup if set
		 */
		public function getClear() { return $this->_id; }
			
		/**
		 * Set the URL Target (such as _blank) 
		 *
		 * @param string $v The URL Target
		 */
		public function setUrlTarget($v=true) { $this->_target = (string)$v; }
		
		/**
		 * Get the URL Target (such as _blank) 
		 * 
		 * @return boolean The URL Target (such as _blank)
		 */
		public function getUrlTarget() { return $this->_target; }

		/**
		 * Set the style name of this button:  used in CSS to to determine the style
		 * of the button (e.x. simpleButton[STYLE_NAME]) 
		 *
		 * @param string $v The style name of the button (used in CSS)
		 */
		public function setStyleName($v) { $this->_style = (string)$v; }
		
		/**
		 * Return the style name of this button
		 *
		 * @return string The style name of this button (defaults to 'Grey')
		 */
		public function getStyleName() { return $this->_style; }
		
		/**
		 * Left align the icon on the button
		 */
		public function setLeftAlign() { $this->_isLeft = true; }
		
		/**
		 * Right align the icon on the button
		 */
		public function setRightAlign() { $this->_isLeft = false; }
		
		/**
		 * Sets the padding for this button.  Padding is applied to the boundries of the link/button.
		 * 
		 * @example Example Inputs: '2px', '2px 2px 0px 0px', '1em'
		 *
		 * @param int $v The height in pixels of the icon
		 */
		public function setPadding($v) { $this->_padding = (int)$v; }
		
		/**
		 * Gets the padding for the button.  Padding is applied to the boundries of the link/button.
		 * 
		 * @return int
		 */
		public function getPadding() { return $this->_padding; }
		
		/**
		 * Sets the onclick action for this button.
		 * 
		 * @example Example Inputs: "alert('You clicked the Simple Button');"
		 *
		 * @param string $v The action for this button
		 */
		public function setAction($v) { $this->_action = (string)$v; }
		
		/**
		 * Gets the action value for the button.
		 * 
		 * @return string
		 */
		public function getAction() { return $this->_action; }
		
		/**
		 * Set the Icon's height in pixels
		 *
		 * @param int $v The height in pixels of the icon
		 */
		public function setIconHeight($v) { $this->_imgHeight = (int)$v; }
		
		/**
		 * Gets the Icon's height in pixels
		 *
		 * @return int
		 */
		public function getIconHeight() { return $this->_imgHeight; }
		
		/**
		 * Set the Icon's Width in pixels
		 *
		 * @param int $v The Width in pixels of the icon
		 */
		public function setIconWidth($v) { $this->_imgWidth = (int)$v; }
		
		/**
		 * Gets the Icon Width in pixels
		 *
		 * @return int
		 */
		public function getIconWidth() { return $this->_imgWidth; }

		/**
		 * Set the Caption/Text value on the button
		 *
		 * @param string $v The caption/text of the button
		 */
		public function setText($v) { $this->_text = (string)$v; }
		
		/**
		 * Gets the Caption/Text value of the button
		 *
		 * @return string
		 */
		public function getText() { return $this->_text; }
		
		/**
		 * Set the icon URL for the icon image on the button
		 *
		 * @param string $v The image URL (absolute URL path - not a relative path)
		 */
		public function setIconUrl($v) { $this->_imgUrl = (string)$v; }
		
		/**
		 * Gets the icon URL for the icon image on the button
		 *
		 * @return string
		 */
		public function getIconUrl() { return $this->_imgUrl; }
		
		/**
		 * Set the destination URL for this button/link
		 *
		 * @param string $v The destination URL for this button/link
		 */
		public function setUrl($v) { $this->_url = (string)$v; }
		
		/**
		 * Gets the destination URL for this button/link
		 *
		 * @return string
		 */
		public function getUrl() { return ( $this->_url == '' ? 'javascript:void(0);' : $this->_url ); }

		public function get() {
			return ( $this->_isLeft ? $this->buildLeft() : $this->buildRight() );
		}
		
		/**
		 * Generate the XHTML (strict) markup for a left aligned button
		 *
		 * @return string XHTML (strict) markup for a left aligned button
		 */
		private function buildLeft() {
			
			// 2007-10-30:CBS: added an inline style to the <a> tag to take precedence over inherited text-decorations 
			
			$retval  = "\n<!-- Simple Button -->\n";
			$retval .= "<div class=\"simpleButton{$this->getStyleName()}\" style=\"padding:{$this->getPadding()};";
			if( $this->_indent != 0 ) { $retval .= 'margin-left:'.$this->_indent.'px;'; }
			if( $this->_width != 0 ) { $retval .= 'width:'.$this->_width.'px;'; }
			$retval .= "\">\n";
			$retval .= "\t<a ";
			if( $this->getId() != '' ) { $retval .= "id=\"{$this->getId()}\" name=\"{$this->getId()}\" "; }
			if( $this->getAction() != '' ) { $retval .= "onclick=\"{$this->getAction()}\" "; }
			if( $this->getUrlTarget() != '' ) { $retval .= " target=\"{$this->getUrlTarget()}\" "; }
			$retval .= "class=\"simpleButton{$this->getStyleName()}\" style=\"text-decoration:none;height:".($this->getIconHeight() + 6 )."px;\" href=\"{$this->getUrl()}\">\n";
			if( $this->getIconUrl() != '' ) {
				$retval .= "\t\t<img class=\"simpleButton{$this->getStyleName()}Left\" src=\"{$this->getIconUrl()}\" alt=\"\"/>\n";
			}
			$retval .= "\t\t<span style=\"line-height:".( $this->getIconHeight() + 6 )."px;text-align:left;margin:0px 10px 0px 4px;\">{$this->getText()}</span>\n";
			$retval .= "\t</a>\n";
			$retval .= "</div>\n";
			if( $this->_clear ) { $retval .= '<div style="clear:both;"></div>'; }
			return $retval;
		}
		
		/**
		 * Generate the XHTML (strict) markup for a right aligned button
		 *
		 * @return string XHTML (strict) markup for a right aligned button
		 */
		private function buildRight() {

			// 2007-10-30:CBS: added an inline style to the <a> tag to take precedence over inherited text-decorations 
			
			$retval  = "\n<!-- Simple Button -->\n";
			$retval .= "<div class=\"simpleButton{$this->getStyleName()}\" style=\"padding:{$this->getPadding()};";
			if( $this->_indent != 0 ) { $retval .= 'margin-left:'.$this->_indent.'px;'; }
			if( $this->_width != 0 ) { $retval .= 'width:'.$this->_width.'px;'; }
			$retval .= "\">\n";
			$retval .= "\t<a ";
			if( $this->getId() != '' ) { $retval .= "id=\"{$this->getId()}\" name=\"{$this->getId()}\" "; }
			if( $this->getAction() != '' ) { $retval .= "onclick=\"{$this->getAction()}\" "; }
			if( $this->getUrlTarget() != '' ) { $retval .= " target=\"{$this->getUrlTarget()}\" "; }
			$retval .= "class=\"simpleButton{$this->getStyleName()}\" style=\"text-decoration:none;height:".($this->getIconHeight() + 6 )."px;\" href=\"{$this->getUrl()}\">\n";
			$retval .= "\t\t<span style=\"float:left;line-height:".( $this->getIconHeight() + 6 )."px;text-align:right;margin:0px 4px 0px 10px ;\">{$this->getText()}</span>\n";
			if( $this->getIconUrl() != '' ) {
				$retval .= "\t\t<img class=\"simpleButton{$this->getStyleName()}Right\" src=\"{$this->getIconUrl()}\" alt=\"\"/>\n";
			}
			$retval .= "\t</a>\n";
			$retval .= "</div>\n";
			if( $this->_clear ) { $retval .= '<div style="clear:both;"></div>'; }
			return $retval;
		}
	}
?>