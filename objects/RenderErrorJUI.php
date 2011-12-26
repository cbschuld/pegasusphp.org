<?php
	/**
	 * RenderNoteJUI creates a note object in XHTML with reliance on a jQuery UI
	 * style sheet to reduce its usage footprint; it was developed for web
	 * applications and not static web sites.
	 *
	 * @package PegasusPHP
	 * @subpackage XHTML
	 */
	class RenderErrorJUI extends RenderNoteJUI {
		public function RenderErrorJUI() {
			$this->_jquery_class = "ui-state-error";
			$this->_jquery_icon_class = "ui-icon-alert";
		}
		
	}
?>