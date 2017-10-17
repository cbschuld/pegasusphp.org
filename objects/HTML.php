<?php
	class HTML {
		public static function link($title,$url,$onclick='',$class='') {
			return '<a href="'.$url.'"'.($onclick==''?'':' onclick="'.$onclick.'"').($class==''?'':' class="'.$class.'"').'>'.$title.'</a>';
		}
	} 
?>