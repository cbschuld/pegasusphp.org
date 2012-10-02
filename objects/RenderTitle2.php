<?php
	/**
	 * RenderTitle creates a title object object in XHTML without a general reliance
	 * on CSS to reduce its usage footprint; it was developed for web
	 * applications and not static web sites.
	 *
	 * @package PegasusPHP
	 * @subpackage XHTML
	 */
	class RenderTitle2 {

		private $_iconPathBase = '/pegasus/images/icon/';
		private $_icon = '';
		private $_size = 16;
		private $_fontsize = 0;
		private $_bold = false;
		private $_hide = false;
		private $_clear = false;
		private $_width = 0;
		private $_margin = 1;
		private $_padding = 5;
		private $_float = '';
		private $_bordercolor = Settings::RENDER_TITLE2_BORDER_COLOR;
		private $_gradientcolor = '';
		private $_color = '777';
		private $_fontcolor = 'fff';
		private $_content = '';
		private $_title = '';

		private $_left = false;
		private $_right = true;

		/**
		 * Sets the content of the title object
		 *
		 * @param string $xhtml The content of the title object
		 * @return this (for method chaining)
		 */
		public function setContent($xhtml) {
			$this->_content = $xhtml;
			return $this;
		}

		/**
		 * Returns the content of the title object
		 *
		 * @return string The content of the title object
		 */
		public function getContent() { return $this->_content; }

		public function setLeft($b=true) { $this->_left = $b; }
		public function setRight($b=true) { $this->_right = $b; }

		/**
		 * Sets the title of the title object
		 *
		 * @param string $xhtml The content of the title object
		 * @return this (for method chaining)
		 */
		public function setTitle($xhtml) {
			$this->_title = $xhtml;
			return $this;
		}

		/**
		 * Returns the content of the title object
		 *
		 * @return string The title of the title object
		 */
		public function getTitle() { return $this->_title; }


		/**
		 * Sets whether or not the title object should clear any open floats
		 *
		 * @param bool $clear If true the floats are cleared
		 * @return this (for method chaining)
		 */
		public function setClear($clear) {
			$this->_clear = (bool)$clear;
			return $this;
		}

		/**
		 * Returns the clear of the title object (if true the floats are cleared)
		 *
		 * @return bool The clear value (if true the floats are cleared)
		 */
		public function getClear() { return $this->_clear; }

		/**
		 * Sets whether or not the font should be bold
		 *
		 * @param bool $bold If true the font should be bold
		 * @return this (for method chaining)
		 */
		public function setBold($bold=true) {
			$this->_bold = (bool)$bold;
			return $this;
		}

		/**
		 * Returns the bold value of the font of the title object
		 *
		 * @return bool The bold value (if true the message font is bold)
		 */
		public function getBold() { return $this->_bold; }

		/**
		 * Sets whether or not the title object should be hidden from display
		 *
		 * @param bool $hide If true the title object is hidden
		 * @return this (for method chaining)
		 */
		public function setHidden($hide=true) {
			$this->_hide = (bool)$hide;
			return $this;
		}

		/**
		 * Returns true if the message is hidden
		 *
		 * @return bool Returns true if the title object should be hidden
		 */
		public function getHidden() { return $this->_hide; }

		/**
		 * Set the Icon with either a simple image name like 'information' or a system relative pathname
		 *
		 * @param string $v the name of the icon or pathname
		 * @return this (for method chaining)
		 */
		public function setIcon($v) {
			$this->_icon = $v;
			return $this;
		}

		/**
		 * Return the icon value
		 *
		 * @return the icon value
		 */
		public function getIcon() { return $this->_icon; }

		/**
		 * Set the float value to either '', 'left' or 'right'
		 *
		 * @param string $leftORright either '', 'left' or 'right'
		 * @return this (for method chaining)
		 */
		public function setFloat($leftORright) {
			$this->_float = strtolower($leftORright);
			if( $this->_float != '' && $this->_float != 'left' && $this->_float != 'right' ) {
				die('Error: attempt to call RenderTitle::setFloat() with an invalid value');
			}
			return $this;
		}

		/**
		 * Returns either '' or 'left' or 'right' for the float direction.  If no float
		 * is provided the object will not be seeded with a float value.
		 *
		 * @return string Either '', 'left' or 'right' depending on the float setting
		 */
		public function getFloat() { return $this->_float; }

		/**
		 * Sets the value of the background color in decimal RGB
		 *
		 * @param string $rgb RGB value for the background of the title object
		 * @return this (for method chaining)
		 */
		public function setGradientColor($rgb) {
			$this->_gradientcolor = strtolower(preg_replace('/[^a-zA-Z0-9]/','',$rgb));
			return $this;
		}
		public function getGradientColor() {
			$color = '';
			if( $this->_gradientcolor == '' && Settings::RENDER_TITLE2_GRADIENT_COLOR != '' ) {
				$color = Settings::RENDER_TITLE2_GRADIENT_COLOR;
			}
			else if( $this->_gradientcolor == '' && $this->_color != '' ) {
				$color = $this->getColor();
				$color = WebColor::increment($this->getColor(),Settings::RENDER_TITLE2_GRADIENT_INDEX_VALUE);
			}
			else if( $this->_gradientcolor == '' && $this->_color == '' ) {
				$color = Settings::RENDER2_TITLE_COLOR;
				$color = WebColor::increment($this->getColor(),Settings::RENDER_TITLE2_GRADIENT_INDEX_VALUE);
			}

			else {
				$color = $this->_gradientcolor;
			}
			return WebColor::getRgbColor($color);
		}

		/**
		 * Sets the value of the color in decimal RGB
		 *
		 * @param string $rgb RGB value for the foreground color of the title object
		 * @return this (for method chaining)
		 */
		public function setColor($rgb) {
			$this->_color = strtolower(preg_replace('/[^a-zA-Z0-9]/','',$rgb));
			return $this;
		}

		/**
		 * Sets the value of the color in decimal RGB
		 *
		 * @param string $rgb RGB value for the foreground color of the title object
		 * @return string the foreground color of the title object
		 */
		public function getColor() { return $this->_color; }

		/**
		 * Sets the value of the color in decimal RGB
		 *
		 * @param string $rgb RGB value for the foreground color of the title object
		 * @return this (for method chaining)
		 */
		public function setFontColor($rgb) {
			$this->_fontcolor = strtolower(preg_replace('/[^a-zA-Z0-9]/','',$rgb));
			return $this;
		}

		/**
		 * Sets the value of the color in decimal RGB
		 *
		 * @param string $rgb RGB value for the foreground color of the title object
		 * @return string the foreground color of the title object
		 */
		public function getFontColor() { return $this->_fontcolor; }

		/**
		 * Sets the value of the border color in decimal RGB
		 *
		 * @param string $rgb RGB value for the foreground color of the title object
		 * @return this (for method chaining)
		 */
		public function setBorderColor($rgb) {
			$this->_bordercolor = strtolower(preg_replace('/[^a-zA-Z0-9]/','',$rgb));
			return $this;
		}

		/**
		 * Sets the value of the border color in decimal RGB
		 *
		 * @param string $rgb RGB value for the foreground color of the title object
		 * @return string the foreground color of the title object
		 */
		public function getBorderColor() { return $this->_bordercolor; }


		/**
		 * Sets the width in pixels of the title object
		 *
		 * @param int $pixels the number of pixels
		 * @return this (for method chaining)
		 */
		public function setWidth($pixels) {
			$this->_width = (int)$pixels;
			return $this;
		}

		/**
		 * Returns the width of the rendered title object in pixels
		 *
		 * @return The width of the rendered title object in pixels
		 */
		public function getWidth() { return (int)$this->_width; }

		/**
		 * Sets the margin in pixels of the title object
		 *
		 * @param int $pixels the number of pixels
		 * @return this (for method chaining)
		 */
		public function setMargin($pixels) {
			$this->_margin = (int)$pixels;
			return $this;
		}

		/**
		 * Returns the margin of the rendered title object in pixels
		 *
		 * @return The margin of the rendered title object in pixels
		 */
		public function getMargin() { return (int)$this->_margin; }

		/**
		 * Sets the padding in pixels of the title object
		 *
		 * @param int $pixels the number of pixels
		 * @return this (for method chaining)
		 */
		public function setPadding($pixels) {
			$this->_padding = (int)$pixels;
			return $this;
		}

		/**
		 * Returns the padding of the rendered title object in pixels
		 *
		 * @return The padding of the rendered title object in pixels
		 */
		public function getPadding() { return (int)$this->_padding; }

		/**
		 * Sets the size in pixels of the title object's icon
		 *
		 * @param int $pixels the number of pixels
		 * @return this (for method chaining)
		 */
		public function setSize($pixels) {
			$this->_size = (int)$pixels;
			return $this;
		}

		/**
		 * Returns the size of the rendered title object's icon in pixels
		 *
		 * @return The size of the rendered title object's icon in pixels
		 */
		public function getSize() { return (int)$this->_size; }

		/**
		 * Calculates the full pathname of the icon
		 *
		 * @return the pathname of the icon
		 */
		private function getIconPath() {
			$path = $this->_icon;
			if( $path != '' && strpos($this->_icon,'.') === false && strpos($this->_icon,'/') === false ) {
				$path = preg_replace('/\/\//','/',$this->_iconPathBase.'/'.$this->_size.'x'.$this->_size.'/'.$this->_icon.'.png' );
			}
			return $path;
		}
		/**
		 * Calculates the full pathname of the icon
		 *
		 * @return the pathname of the icon
		 */
		private function getGradientPath() {
			return '/pegasus/lib/images/gradient?height='.Settings::RENDER_TITLE2_GRADIENT_INDEX_VALUE.'&amp;width=5&amp;color='.$this->getGradientColor();
		}

		/**
		 * Calculates the size of the font to use in the display
		 *
		 * @return the size of the font to display in px
		 */
		private function getFontSize($addto=0) {
			$size = $this->_fontsize;
			if( $this->_fontsize == 0 ) {
				$size = ((int)( $this->_size / 1.5 ));
			}
			return $size + $addto;
		}

		/**
		 * Render the title object via XHTML
		 *
		 * @return XHTML to display a title object
		 */
		public function render() {

			$xhtml = '';

    		$minSize = $size;
    		$paddingSize = 5;
    		$iconPaddingSize = $this->_size + 10;

    		if( $this->_hidden ) { $xhtml .= '<div style="display:none;">'; }
    		$xhtml .= '<div style="border-top:2px solid #'.$this->getColor().';border-bottom:1px solid #'.$this->getBorderColor().';margin-bottom:10px;padding-bottom:5px;">';

    		if( $this->_left ) {
	    		$xhtml .= '<div style="padding:2px;margin:0px;height:'.$this->getFontSize(4).'px;';
	    		$xhtml .= 'float:left;color:#'.$this->getFontColor().';';
	    		$xhtml .= 'background:url('.$this->getGradientPath().') repeat-x;">';
	    		$xhtml .= '<div style="padding:0px 2px 0px 2px;';
	    		if( $this->_bold ) { $xhtml .= 'font-weight:bold;'; }
	    		$xhtml .= 'font-size:'.$this->getFontSize().'px;">'.$this->getTitle().'</div></div>';
    			if( $this->getIconPath() != '' ) { $xhtml .= '<img src="'.$this->getIconPath().'" alt="" style="float:left;padding:1px;"/>'; }
    		}

    		if( $this->_right ) {
	    		$xhtml .= '<div style="padding:2px;margin:0px;height:'.$this->getFontSize(4).'px;';
	    		$xhtml .= 'float:right;color:#'.$this->getFontColor().';';
	    		$xhtml .= 'background:url('.$this->getGradientPath().') repeat-x;">';
	    		$xhtml .= '<div style="padding:0px 2px 0px 2px;';
	    		if( $this->_bold ) { $xhtml .= 'font-weight:bold;'; }
	    		$xhtml .= 'font-size:'.$this->getFontSize().'px;">'.$this->getTitle().'</div></div>';
	    		if( $this->getIconPath() != '' ) { $xhtml .= '<img src="'.$this->getIconPath().'" alt="" style="float:right;padding:1px;"/>'; }
    		}

			$xhtml .= '<div style="padding:4px;">'.$this->getContent().'</div>';
			$xhtml .= '</div>';
			if( $this->_hidden ) { $xhtml .= '</div>'; }

	    	return $xhtml;

		}

	}
?>