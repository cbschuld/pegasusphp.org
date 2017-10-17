<?php
	/**
	 * Button
	 *
	 * @package PegasusPHP
	 */
	class Button {
		private $_text = '';
		private $_icon = '';
		private $_width = 0;
		private $_class = '';
		private $_target = '';
		private $_rpad = 0;
		private $_lpad = 0;
		private $_onclick = '';
		private $_href = '';
		private $_name = '';
		private $_clear = false;
		private $_id = '';
		private $_size = 16;
		
		public function getId() { return $this->_id; }
		public function getSize() { return $this->_size; }
		public function getText() { return $this->_text; }
		public function getIcon() { return $this->_icon; }
		public function getOnClick() { return $this->_onclick; }
		public function getHref() { return $this->_href; }
		public function getName() { return $this->_name; }
		public function getClear() { return $this->clear; }
		public function getLeftPadding() { return $this->_lpad; }
		public function getRightPadding() { return $this->_rpad; }
		public function getTarget() { return $this->_target; }
		public function getClass() { return $this->_class; }
		public function getWidth() { return $this->_width; }
		public function setName($v) { $this->_name = $v; return $this; }
		public function setIcon($v) {
		    $imageUri = '/images/icon/'.$this->_size.'x'.$this->_size.'/'.$v.'.png';
    		//$imageAbsolutePath = constant('FRAMEWORK_PATH') . $imageUri;
			if( $v != '' && substr( $v, 0, 1 ) != '/' ) {
    			$v = '/pegasus'.$imageUri;
    		}
			$this->_icon = $v;
			return $this;
		}
		public function setSize($v) { $this->_size = $v; return $this; }
		public function setText($v) { $this->_text = $v; return $this; }
		public function setId($v) { $this->_id = $v; return $this; } 
		public function setWidth($v) { $this->_width = $v; return $this; }
		public function setClass($v) { $this->_class = $v; return $this; }
		public function setTarget($v) { $this->_target = $v; return $this; }
		public function setRightPadding($v) { $this->_rpad = $v; return $this; }
		public function setLeftPadding($v) { $this->_lpad = $v; return $this; }
		public function setOnClick($v) { $this->_onclick = preg_replace('/#LB#/','{',preg_replace('/#RB#/','}',$v)); return $this; }
		public function setHref($v) { $this->_href = $v; return $this; }
		public function setClear($v) { $this->_clear = $v; return $this; }
	    
		public function create() {
			$ahref = '<a href="'.$this->_href.'"';
			if( $this->_onclick != '' ) { $ahref .= ' onclick="'.$this->_onclick.'"'; } 
			if( $this->_target != ''  ) { $ahref .= ' target="'.$this->_target.'"';   } 
			$ahref .= '>';
			
			$xhtml  = "\n";
			$xhtml .= '<div';
			
			$style = '';
			
			if( $this->_width != '' ) { $style .= 'width:'.$this->_width.'px;'; }
			if( $this->_rpad != '' ) { $style .= 'margin-right:'.$this->_rpad.'px;'; }
			if( $this->_lpad != '' ) { $style .= 'margin-left:'.$this->_lpad.'px;'; }
	
			if( $style != '' ) { $xhtml .= ' style="'.$style.'"'; }
			
			if( $this->_name != '' ) { $xhtml .= ' id="'.$this->_name.'"'; } 
			
			$xhtml .=' class="'.$this->_class.'">';
			$xhtml .= $ahref.( $this->_icon != '' ? '<img style="float:left;" src="'.$this->_icon.'" alt="'.$this->_text.'" />' : '' ).$this->_text.'</a>';
			
			$xhtml .= '</div>';
			if( $this->_clear ) { $xhtml .= '<div style="clear:both;"></div>'; }
			
			$xhtml .= "\n";

			return $xhtml;
		}
}
?>