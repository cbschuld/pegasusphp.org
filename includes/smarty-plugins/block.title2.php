<?php
/*
 * Smarty plugin
 * -------------------------------------------------------------
 * File:     block.title2.php
 * Type:     block
 * Name:     pad
 * Purpose:
 * -------------------------------------------------------------
 */

function smarty_block_title2($params, $content, &$smarty, &$repeat) {

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

		$title = new RenderTitle2();

		$title->setTitle( isset( $params['text']  ) ? $params['text'] : ( isset( $params['label'] ) ? $params['label'] : 'no label or text' ) );

		$title->setLeft( $alignDirection == 'left' );
		$title->setRight( $alignDirection != 'left' );

		$title->setIcon( isset( $params['icon']  ) ? $params['icon'] : '' );
		$title->setGradientColor( isset( $params['gradientcolor']  ) ? $params['gradientcolor'] : Settings::RENDER_TITLE2_GRADIENT_COLOR );
		$title->setSize( isset( $params['size']  ) ? $params['size'] : Settings::RENDER_TITLE2_ICON_SIZE );
		$title->setColor( isset( $params['color']  ) ? $params['color'] : Settings::RENDER_TITLE2_COLOR );
		$title->setFontColor( isset( $params['fontcolor']  ) ? $params['fontcolor'] : Settings::RENDER_TITLE2_FONTCOLOR );
		$title->setContent($content);

		return $title->render();
	}
}
?>