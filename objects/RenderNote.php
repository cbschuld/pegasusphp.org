<?php
	/**
	 * RenderNote creates a note object in XHTML without a general reliance
	 * on CSS to reduce its usage footprint; it was developed for web
	 * applications and not static web sites.
	 *
	 * @package PegasusPHP
	 * @subpackage XHTML
	 */
	class RenderNote {
		private $_iconPathBase = Settings::RENDER_NOTE_ICON_PATH;
		private $_icon = Settings::RENDER_NOTE_DEFAULT_ICON;
		private $_size = Settings::RENDER_NOTE_DEFAULT_ICON_SIZE;
		private $_fontsize = 0;
		private $_bold = false;
		private $_hide = false;
		private $_clear = false;
		private $_width = 0;
		private $_minwidth = 200;
		private $_margin = 1;
		private $_padding = 5;
		private $_float = '';
		private $_id = '';
		private $_opacity = '1.0';
		private $_border = Settings::RENDER_NOTE_BORDER;
		private $_background = Settings::RENDER_NOTE_BACKGROUND;
		private $_color = Settings::RENDER_NOTE_COLOR;
		private $_content = '';
		private $_cornerRadius = Settings::RENDER_NOTE_CORNER_RADIUS;
	
		/**
		 * Sets the content of the note
		 *
		 * @param string $xhtml The content of the note
		 * @return this (for method chaining)
		 */
		public function setContent($xhtml) {
			$this->_content = $xhtml;
			return $this;
		}
	
		/**
		 * Returns the content of the note
		 *
		 * @return string The content of the note
		 */
		public function getContent() { return $this->_content; }
	
		/**
		 * Sets whether or not the note should clear any open floats
		 *
		 * @param bool $clear If true the floats are cleared
		 * @return this (for method chaining)
		 */
		public function setClear($clear) {
			$this->_clear = (bool)$clear;
			return $this;
		}
	
		/**
		 * Returns the clear of the note (if true the floats are cleared)
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
		 * Returns the bold value of the font of the note
		 *
		 * @return bool The bold value (if true the message font is bold)
		 */
		public function getBold() { return $this->_bold; }
	
		/**
		 * Sets whether or not the note should be hidden from display
		 *
		 * @param bool $hide If true the note is hidden
		 * @return this (for method chaining)
		 */
		public function setHidden($hide=true) {
			$this->_hide = (bool)$hide;
			return $this;
		}
	
		/**
		 * Returns true if the message is hidden
		 *
		 * @return bool Returns true if the note should be hidden
		 */
		public function getHidden() { return $this->_hide; }
	
		/**
		 * Set the ID of the resulting DIV
		 *
		 * @param string $v the id of the resulting div
		 * @return this (for method chaining)
		 */
		public function setId($v) {
			$this->_id = $v;
			return $this;
		}
	
		/**
		 * Return the id value
		 *
		 * @return the id value
		 */
		public function getId() { return $this->_id; }
				
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
				die('Error: attempt to call RenderNote::setFloat() with an invalid value');
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
		 * @param string $rgb RGB value for the background of the note
		 * @return this (for method chaining)
		 */
		public function setBackground($rgb) {
			$this->_background = strtolower(preg_replace('/[^a-zA-Z0-9]/','',$rgb));
			return $this;
		}
	
		/**
		 * Sets the value of the border in CSS
		 *
		 * @param string $cssBorderStatement CSS border statement for the border
		 * @return this (for method chaining)
		 */
		public function setBorder($cssBorderStatement='1px solid #ccc') {
			$this->_border = strtolower(preg_replace('/[^a-zA-Z0-9# ]/','',$cssBorderStatement));
			return $this;
		}
	
		/**
		 * Sets the value of the color in decimal RGB
		 *
		 * @param string $rgb RGB value for the foreground color of the note
		 * @return this (for method chaining)
		 */
		public function setColor($rgb) {
			$this->_color = strtolower(preg_replace('/[^a-zA-Z0-9]/','',$rgb));
			return $this;
		}
	
		/**
		 * Sets the value of the color in decimal RGB
		 *
		 * @return string the foreground color of the note
		 */
		public function getColor() { return $this->_color; }
	
		/**
		 * Sets the value of the opacity in alpha (0.00 - 1.00)
		 *
		 * @param string $alpha value of the opacity in alpha (0.00 - 1.00)
		 * @return this (for method chaining)
		 */
		public function setOpacity($alpha) {
			$this->_opacity = $alpha;
			return $this;
		}
	
		/**
		 * Sets the value of the opacity in alpha (0.00 - 1.00)
		 *
		 * @param string $rgb RGB value for the foreground color of the note
		 * @return string value of the opacity in alpha (0.00 - 1.00)
		 */
		public function getOpacity() { return $this->_opacity; }
	
		/**
		 * Sets the width in pixels of the note
		 *
		 * @param int $pixels the number of pixels
		 * @return this (for method chaining)
		 */
		public function setWidth($pixels) {
			$this->_width = (int)$pixels;
			if( $this->_width < $this->_minwidth ) {
				$this->_minwidth = (int)$pixels;
			}
			return $this;
		}
	
		/**
		 * Returns the miniumum width of the rendered note in pixels
		 *
		 * @return The miniumum width of the rendered note in pixels
		 */
		public function getMinimumWidth() {
			if( $this->_width < $this->_minwidth ) {
				$this->_minwidth = $this->_width;
			}
			return (int)$this->_minwidth;
		}
	
	
		/**
		 * Sets the miniumum width in pixels of the note
		 *
		 * @param int $pixels the miniumum number of pixels
		 * @return this (for method chaining)
		 */
		public function setMinimumWidth($pixels) {
			$this->_minwidth = (int)$pixels;
			if( $this->_minwidth > $this->_width ) {
				$this->_width = (int)$pixels;
			}
			return $this;
		}
	
		/**
		 * Returns the width of the rendered note in pixels
		 *
		 * @return The width of the rendered note in pixels
		 */
		public function getWidth() {
			if( $this->_minwidth > $this->_width ) {
				$this->_width = $this->_minwidth;
			}
			return (int)$this->_width;
		}
	
	
		/**
		 * Sets the margin in pixels of the note
		 *
		 * @param int $pixels the number of pixels
		 * @return this (for method chaining)
		 */
		public function setMargin($pixels) {
			$this->_margin = (int)$pixels;
			return $this;
		}
	
		/**
		 * Returns the margin of the rendered note in pixels
		 *
		 * @return The margin of the rendered note in pixels
		 */
		public function getMargin() { return (int)$this->_margin; }
	
		/**
		 * Sets the padding in pixels of the note
		 *
		 * @param int $pixels the number of pixels
		 * @return this (for method chaining)
		 */
		public function setPadding($pixels) {
			$this->_padding = (int)$pixels;
			return $this;
		}
	
		/**
		 * Returns the padding of the rendered note in pixels
		 *
		 * @return The padding of the rendered note in pixels
		 */
		public function getPadding() { return (int)$this->_padding; }
	
		/**
		 * Sets the size in pixels of the note's icon
		 *
		 * @param int $pixels the number of pixels
		 * @return this (for method chaining)
		 */
		public function setSize($pixels) {
			$this->_size = (int)$pixels;
			return $this;
		}
	
		/**
		 * Returns the size of the rendered note's icon in pixels
		 *
		 * @return The size of the rendered note's icon in pixels
		 */
		public function getSize() { return (int)$this->_size; }
	
		/**
		 * Calculates the full pathname of the icon
		 *
		 * @return the pathname of the icon
		 */
		private function getIconPath() {
			$path = $this->_icon;
			if( strpos($this->_icon,'.') === false && strpos($this->_icon,'/') === false ) {
				$path = preg_replace('/\/\//','\/',$this->_iconPathBase.'/'.$this->_size.'x'.$this->_size.'/'.$this->_icon.'.png' );
			}
			return $path;
		}
	
	
	
		/**
		 * Calculates the size of the font to use in the display
		 *
		 * @return the size of the font to display in px
		 */
		private function getFontSize() {
			$size = $this->_fontsize;
			if( $this->_fontsize == 0 ) {
				$size = ((int)( $this->_size / 1.3 ));
			}
			return $size;
		}
	
		public function setFontSize($fs) {
			$this->_fontsize = $fs;
		}
	
		/**
		 * Render the note via XHTML
		 *
		 * @return XHTML to display a note
		 */
		public function render() {
			$xhtml = '';
				
			$minSize = $this->_size;
			$paddingSize = 5;
			$iconPaddingSize = $this->_size + 10;
			 
			$xhtml  = '<div style="position:relative;text-align:left;';
			$xhtml .= 'background: url('.$this->getIconPath().') #'.$this->_background.' no-repeat 5px 5px;';
			$xhtml .= 'color:#'.$this->_color.';';
			$xhtml .= 'opacity:'.$this->_opacity.';';
			$xhtml .= 'font-weight:'.( $this->_bold ? 'bold' : 'normal' ).';';
			if( $this->_width != 0) { $xhtml .= 'width:'.$this->getWidth().'px;'; }
			if( $this->_minwidth != 0) { $xhtml .= 'min-width:'.$this->getMinimumWidth().'px;'; }
			if( $this->_float != '' ) { $xhtml .= 'float:'.$this->_float.';'; }
			if( $this->_hide ) { $xhtml .= 'display:none;'; }
	
			$xhtml .= 'margin:'.$this->_margin.'px;';
	
			$xhtml .= 'padding:'.$this->_padding.'px '.$this->_padding.'px '.$this->_padding.'px '.$iconPaddingSize.'px;';
			$xhtml .= 'min-height:'.$this->_size.'px;';
			$xhtml .= 'font-size:'.$this->getFontSize().'px;';
			$xhtml .= 'border:'.$this->_border.';';
			if( $this->_cornerRadius > 0 ) {
				// TODO: after they finish and support CSS3 fix this...
				$xhtml .= 'border-radius:'.$this->_cornerRadius.'px;';
				$xhtml .= '-moz-border-radius:'.$this->_cornerRadius.'px;';
				$xhtml .= '-webkit-border-radius:'.$this->_cornerRadius.'px;';
			}
			$xhtml .= '"';
			if( $this->_id != '' ) {
				$xhtml .= ' id="'.$this->_id.'"';
			}
			$xhtml .= '>'.$this->_content.'</div>';
			if( $this->_clear ) { $xhtml .= '<div style="clear:both;"></div>'; }
			return $xhtml;
				
		}
	
	}
?>