<?php

	class Language {
		
		const AFRIKAANS				="af";
		const ALBANIAN				="sq";
		const ARABIC				="ar";
		const BELARUSIAN			="be";
		const BULGARIAN				="bg";
		const CATALAN				="ca";
		const CHINESESIMPLIFIED		="zh-CN";
		const CHINESETRADITIONAL	="zh-TW";
		const CROATIAN				="hr";
		const CZECH					="cs";
		const DANISH				="da";
		const DUTCH					="nl";
		const ENGLISH				="en";
		const ESTONIAN				="et";
		const FILIPINO				="tl";
		const FINNISH				="fi";
		const FRENCH				="fr";
		const GALICIAN				="gl";
		const GERMAN				="de";
		const GREEK					="el";
		const HAITIANCREOLE			="ht";
		const HEBREW				="iw";
		const HINDI					="hi";
		const HUNGARIAN				="hu";
		const ICELANDIC				="is";
		const INDONESIAN			="id";
		const IRISH					="ga";
		const ITALIAN				="it";
		const JAPANESE				="ja";
		const LATVIAN				="lv";
		const LITHUANIAN			="lt";
		const MACEDONIAN			="mk";
		const MALAY					="ms";
		const MALTESE				="mt";
		const NORWEGIAN				="no";
		const PERSIAN				="fa";
		const POLISH				="pl";
		const PORTUGUESE			="pt";
		const ROMANIAN				="ro";
		const RUSSIAN				="ru";
		const SERBIAN				="sr";
		const SLOVAK				="sk";
		const SLOVENIAN				="sl";
		const SPANISH				="es";
		const SWAHILI				="sw";
		const SWEDISH				="sv";
		const THAI					="th";
		const TURKISH				="tr";
		const UKRAINIAN				="uk";
		const VIETNAMESE			="vi";
		const WELSH					="cy";
		const YIDDISH				="yi";

		private static $_key = "";
		
		public static function setKey($key) { self::$_key = $key; }
		public static function getKey() { return self::$_key; }

		
		public static function translate( $sourceLanguage, $targetLanguage, $sentence ) {

			$sentenceurl = urlencode($sentence);
			
			$response = file_get_contents("https://www.googleapis.com/language/translate/v2?key=".self::getKey()."&source={$sourceLanguage}&target={$targetLanguage}&q={$sentenceurl}");
			if($response != "") {
				$json = json_decode($response,true);
				if( isset($json["data"]) && isset($json["data"]["translations"]) && isset($json["data"]["translations"][0]["translatedText"]) ) {
					return $json["data"]["translations"][0]["translatedText"];
				}
			}
			return "";
		}
		
		
	}

?>