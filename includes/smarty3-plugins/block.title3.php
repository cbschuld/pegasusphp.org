<?php
/*
 * Smarty plugin
 * -------------------------------------------------------------
 * File:     block.title3.php
 * Type:     block
 * Name:     pad
 * Purpose:
 * -------------------------------------------------------------
 */

function smarty_block_title3($params, $content, &$smarty, &$repeat) {

	// only output on the closing tag
	if( ! $repeat){
//		$text  = ( isset( $params['text']  ) ? $params['text'] : ( isset( $params['label'] ) ? $params['label'] : 'no label or text' ) );
//		$icon  = ( isset( $params['icon']  ) ? $params['icon'] : '' );
//		$class = ( isset( $params['class'] ) ? $params['class'] : 'pegasus_title' );
//		$size  = ( isset( $params['size']  ) ? $params['size'] : 24 );
//
//    	if( $icon != '' && substr( $params['icon'], 0, 1 ) != '/' && ! file_exists( constant('FRAMEWORK_PATH') . "/images/icon/{$size}x{$size}/{$params['icon']}.png" ) ) {
//			$smarty->trigger_error('title: icon you desired was not found in the framework icon path ('.constant('FRAMEWORK_PATH') . "/images/icon/{$size}x{$size}/{$params['icon']}.png".')');
//		}
//    	else if( $icon != '' && substr( $params['icon'], 0, 1 ) != '/' ) {
//    		$icon = '/pegasus/images/icon/'.$size.'x'.$size.'/'.$icon.'.png';
//    	}
//
//		return "\n<div class=\"{$class}\">".($icon != '' ? "<img src=\"{$icon}\" alt=\"\" style=\"float:left;\"/>" : '')."<div class=\"{$class}_header\">{$text}</div><div class=\"{$class}_content\">{$content}</div></div>\n";

		$alignDirection = isset( $params['align']  ) ? $params['align'] : 'left';

		$title = new RenderTitle3();

		$title->setTitle( isset( $params['text']  ) ? $params['text'] : ( isset( $params['label'] ) ? $params['label'] : 'no label or text' ) );

		$title->setIcon( isset( $params['icon']  ) ? $params['icon'] : '' );
		$title->setLineColor( isset( $params['linecolor']  ) ? $params['linecolor'] : Settings::RENDER_TITLE_LINE_COLOR );
		$title->setSize( isset( $params['size']  ) ? $params['size'] : Settings::RENDER_TITLE_ICON_SIZE );
		$title->setColor( isset( $params['color']  ) ? $params['color'] : Settings::RENDER_TITLE_COLOR );
		$title->setContent($content);

		return $title->render();
	}
}
?>