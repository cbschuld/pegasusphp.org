<?php
	class Dwoo_Plugin_inlinejs extends Dwoo_Block_Plugin {

		public function init() {}
	    public function process(){
	    	return "\n<script type=\"text/javascript\">\n\t/*<![CDATA[*/\n".$this->buffer."\n\t/*]]>*/\n</script>\n"; 
	    } 
	}
?>