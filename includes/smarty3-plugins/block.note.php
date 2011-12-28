<?php
/**
 * Smarty plugin - note - renders a note box (using RenderNote)
 * @package PegasusPHP
 * @subpackage SmartyAddons
 */
function smarty_block_note($params, $content, &$smarty, &$repeat) {
	// only output on the closing tag
	if( ! $repeat){

		$note = new RenderNote();
		
		if( isset( $params['id'] )         ) { $note->setId( $params['id'] ); }
		if( isset( $params['icon'] )       ) { $note->setIcon( $params['icon'] ); }
		if( isset( $params['size'] )       ) { $note->setSize( $params['size'] ); }
    	if( isset( $params['bold'] )       ) { $note->setBold( $params['bold'] ? true : false ); }
    	if( isset( $params['clear'] )      ) { $note->setClear( $params['clear'] ); }
    	if( isset( $params['width'] )      ) { $note->setWidth( $params['width'] ); }
    	if( isset( $params['margin'] )     ) { $note->setMargin( $params['margin'] ); }
    	if( isset( $params['float'] )      ) { $note->setFloat( $params['float'] ); }
    	if( isset( $params['background'] ) ) { $note->setBackground( $params['background'] ); }
    	if( isset( $params['border'] )     ) { $note->setBorder( $params['border'] ); }
    	if( isset( $params['fontsize'] )   ) { $note->setFontSize( $params['fontsize'] ); }
    	if( isset( $params['color'] )      ) { $note->setColor( $params['color'] ); }
    	
    	return $note->setContent($content)->render();
	}
}
?>