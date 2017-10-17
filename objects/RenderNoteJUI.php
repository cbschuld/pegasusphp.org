<?php
	/**
	 * RenderNoteJUI creates a note object in XHTML with reliance on a jQuery UI
	 * style sheet to reduce its usage footprint; it was developed for web
	 * applications and not static web sites.
	 *
	 * @package PegasusPHP
	 * @subpackage XHTML
	 */
	class RenderNoteJUI {
		private $_bold = false;
		private $_hide = false;
		private $_clear = false;
		private $_width = 0;
		private $_minwidth = 200;
		private $_margin = 1;
		private $_padding = 5;
		private $_float = '';
		private $_id = '';
		private $_content = '';
		protected $_jquery_class = 'ui-state-highlight';
		protected $_jquery_icon_class = 'ui-icon-info';
		
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
		 * Render the note via XHTML
		 *
		 * @return XHTML to display a note
		 */
		public function render() {
			$xhtml = '<div class="ui-widget '.$this->_jquery_class.' ui-corner-all" style="position:relative;text-align:left;padding: 0pt 0.7em;';
			$xhtml .= 'font-weight:'.( $this->_bold ? 'bold' : 'normal' ).';';
			if( $this->_width != 0) { $xhtml .= 'width:'.$this->getWidth().'px;'; }
			if( $this->_minwidth != 0) { $xhtml .= 'min-width:'.$this->getMinimumWidth().'px;'; }
			if( $this->_float != '' ) { $xhtml .= 'float:'.$this->_float.';'; }
			if( $this->_hide ) { $xhtml .= 'display:none;'; }
			$xhtml .= '"';
			if( $this->_id != '' ) {
				$xhtml .= ' id="'.$this->_id.'"';
			}
			$xhtml .= '>';
			$xhtml .= "<span style=\"float: left; margin-right: 0.3em;\" class=\"ui-icon {$this->_jquery_icon_class}\"></span>";
			$xhtml .= '<p>'.$this->_content.'</p></div>';
			if( $this->_clear ) { $xhtml .= '<div style="clear:both;"></div>'; }
			return $xhtml;
		}
	
	}
?>