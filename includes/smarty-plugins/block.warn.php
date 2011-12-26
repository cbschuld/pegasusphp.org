<?php
/**
 * Smarty plugin - warn - renders a warning box (using RenderNote)
 * @package PegasusPHP
 * @subpackage SmartyAddons
 */
function smarty_block_warn($params, $content, &$smarty, &$repeat) {
	// only output on the closing tag
	if( ! $repeat){

		$note = new RenderNote();
		$note->setIcon('warning');
		$note->setBackground('ffd');
		$note->setBorder('1px solid #cca');
		$note->setBold();
		$note->setColor('555');
		
		if( isset( $params['id'] )         ) { $note->setId( $params['id'] ); }
		if( isset( $params['icon'] )       ) { $note->setIcon( $params['icon'] ); }
    	if( isset( $params['size'] )       ) { $note->setSize( $params['size'] ); }
    	if( isset( $params['bold'] )       ) { $note->setBold( $params['bold'] ); }
    	if( isset( $params['clear'] )      ) { $note->setClear( $params['clear'] ); }
    	if( isset( $params['width'] )      ) { $note->setWidth( $params['width'] ); }
    	if( isset( $params['margin'] )     ) { $note->setMargin( $params['margin'] ); }
    	if( isset( $params['float'] )      ) { $note->setFloat( $params['float'] ); }
    	if( isset( $params['border'] )     ) { $note->setBorder( $params['border'] ); }
    	if( isset( $params['background'] ) ) { $note->setBackground( $params['background'] ); }
    	if( isset( $params['fontsize'] )   ) { $note->setFontSize( $params['fontsize'] ); }
    	if( isset( $params['color'] )      ) { $note->setColor( $params['color'] ); }
    	
    	return $note->setContent($content)->render();
	}
}
?>