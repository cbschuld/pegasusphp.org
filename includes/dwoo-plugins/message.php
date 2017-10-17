<?php
	class Dwoo_Plugin_message extends Dwoo_Plugin	{ 
	    public function process() {
			$xhtml = '';
			if( Pegasus::messageSet() ) {
				$note = new RenderNote();
		    	$xhtml = $note->setContent(Pegasus::getMessage())->render();
				Pegasus::clearMessage();
			}
//			else if( isset($params['hide']) ) {
//				$xhtml = '<div id="pegasus_message" style="display:none;"></div>';
//			}
		    return $xhtml;
			        
	    } 
	}
?>