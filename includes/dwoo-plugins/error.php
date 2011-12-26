<?php
	class Dwoo_Plugin_error extends Dwoo_Plugin	{ 
	    public function process() {
			$xhtml = '';
			if( Pegasus::errorSet() ) {
				$note = new RenderNote();
				$note->setIcon('error');
				$note->setBackground('fdd');
				$note->setBold();
				$note->setColor('555');
		    	$xhtml = $note->setContent(Pegasus::getError())->render();
				Pegasus::clearError();
			}
//			else if( isset($params['hide']) ) {
//				$xhtml = '<div id="pegasus_message" style="display:none;"></div>';
//			}
		    return $xhtml;
			        
	    } 
	}
?>