<?php
	class SiteController extends Controller {
		function processGet() {
			if( Request::action() == 'gradient' ) {
				$color = RenderGradientImage::DEFAULT_COLOR;
				$height = RenderGradientImage::DEFAULT_HEIGHT;
				$width = RenderGradientImage::DEFAULT_WIDTH;
				if( Request::get('color') ) {
					$color = preg_replace('/[^a-z0-9]/','',strtolower(Request::get('color')));
					if( strlen($color) != 6 ) { $color = RenderGradientImage::DEFAULT_COLOR; }
				}
				if( Request::get('height') ) {
					$height = (int)preg_replace('/[^0-9]/','',strtolower(Request::get('height')));
					if( $height <= 0 ) { $height = RenderGradientImage::DEFAULT_HEIGHT; }
				}
				if( Request::get('width') ) {
					$width = (int)preg_replace('/[^0-9]/','',strtolower(Request::get('width')));
					if( $width <= 0 ) { $width = RenderGradientImage::DEFAULT_WIDTH; }
				}
				$img = RenderGradientImage::getImage($color,$height,$width);
				header("Content-type: image/png");
				imagepng($img);
				imagedestroy($img);
				exit;
			}
		}
	}
?>