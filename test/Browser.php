<?php 

	require_once(dirname(__FILE__).'/../objects/Browser.php');
	
	$browser = new Browser();
	
	$useragents = file_get_contents(dirname(__FILE__).'/useragents.txt');

	$auseragents = explode("\n",$useragents);

	echo '<html><body><table style="font-size:90%;">';
	echo '<tr><th>User Agent</th><th>Browser</th><th>Version</th><th>AOL</th></tr>';
	foreach($auseragents as $useragent) {
		$browser->setUserAgent($useragent);
		echo '<tr><td style="white-space:nowrap;">'.$browser->getUserAgent().'</td><td style="white-space:nowrap;">'.$browser->getBrowser().'</td><td style="white-space:nowrap;">'.$browser->getVersion().'</td><td style="white-space:nowrap;">'.($browser->isAol()?($browser->getAolVersion() != Browser::BROWSER_UNKNOWN ? $browser->getAolVersion() : ''):'n/a').'</td></tr>';
	}
	echo '</table></body></html>';

?>