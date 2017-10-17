<?php
	class RGBColor {
		public static function lighten($hexValue,$factor = 30) { 
			
			$hexValue = str_replace("#","",$hexValue);
			
			$returnHex = '';
			
			$base['R'] = hexdec($hexValue{0}.$hexValue{1}); 
			$base['G'] = hexdec($hexValue{2}.$hexValue{3}); 
			$base['B'] = hexdec($hexValue{4}.$hexValue{5}); 
		    
			foreach ($base as $k => $v) { 
		    	$amount = 255 - $v; 
				$amount = $amount / 100; 
				$amount = round($amount * $factor); 
				$newDecimal = $v + $amount; 
				    
				$returnHex_component = dechex($newDecimal); 
				if(strlen($returnHex_component) < 2) {
					$returnHex_component = "0".$returnHex_component;
				} 
				$returnHex .= $returnHex_component; 
			}
			return $returnHex;     
    	}
		public static function darken($hex,$factor = 30) {
	        $new_hex = '';
	        
	        $base['R'] = hexdec($hex{0}.$hex{1});
	        $base['G'] = hexdec($hex{2}.$hex{3});
	        $base['B'] = hexdec($hex{4}.$hex{5});
	        
	        foreach ($base as $k => $v) {
	                $amount = $v / 100;
	                $amount = round($amount * $factor);
	                $new_decimal = $v - $amount;
	        
	                $new_hex_component = dechex($new_decimal);
	                if(strlen($new_hex_component) < 2) { $new_hex_component = "0".$new_hex_component; }
	                $new_hex .= $new_hex_component;
	        }
	                
	        return $new_hex;        
	    }
	}
?>