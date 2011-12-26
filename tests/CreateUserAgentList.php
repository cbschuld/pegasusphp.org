<?php
	$useragents = file_get_contents(dirname(__FILE__)."/newuseragents.txt");
	$useragents = explode("\n",$useragents);

	$output = "";

	foreach($useragents as $useragent) {
		$useragent = trim($useragent);
		$output .= "\t\$_USER_AGENTS[] = new UserAgentInfo(\n";
		$output .= "\t                           \"{$useragent}\",\n";
		$output .= "\t                           Browser::PLATFORM_UNKNOWN,\n";
		$output .= "\t                           Browser::BROWSER_UNKNOWN,\n";
		$output .= "\t                           \"\");\n\n";
	}

	file_put_contents(dirname(__FILE__)."/useragents-php.txt",$output);

?>
