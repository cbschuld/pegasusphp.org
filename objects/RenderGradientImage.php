<?php
	/**
	 *
	 *
	 * @package PegasusPHP
	 * @subpackage Images
	 */
	class RenderGradientImage {
		const DEFAULT_COLOR = 'cccccc';
		const DEFAULT_HEIGHT = 100;
		const DEFAULT_WIDTH = 5;
		const ALPHA_MAX = 127;

		public static function getImage($color='cccccc',$height=100,$width=5) {

			sscanf(WebColor::getRgbColor($color), "%2x%2x%2x", $rbase, $gbase, $bbase);

			$img = imagecreatetruecolor($width, $height);
  			imagealphablending($img, false);
  			imagesavealpha($img, true);

            $ct = imagecolorallocatealpha($img, 0, 0, 0, self::ALPHA_MAX);
            imagefill($img, 0, 0, $ct);
			$runningHeight = 0;
			$totalStrokeSize = 0;

			$step = self::ALPHA_MAX / $height;

			for( $h = 0; $h < self::ALPHA_MAX; $h++ ) {
				$strokeSize = round( ( $height - $totalStrokeSize ) / ( self::ALPHA_MAX - $h ) );
				$c = imagecolorallocatealpha($img,$rbase,$gbase,$bbase,$h);
				imagefilledrectangle($img,0,$totalStrokeSize,$width,$totalStrokeSize + $strokeSize,$c);
				$totalStrokeSize += $strokeSize;
			}
			return $img;
		}
		public static function render($color='cccccc',$height=100,$width=5) {
			header("Content-type: image/png");
			$im = self::getImage($color,$height,$width);
			imagepng($im);
			imagedestroy($im);
		}
	}
?>