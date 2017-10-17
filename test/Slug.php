<?php

require_once(dirname(__FILE__).'/../objects/Slug.php');

$strings = array();

$strings[1] = 'This is an interesting string';
$strings[2] = 'This is an interesting string to create a slug from';
$strings[3] = 'How to install "dig": -bash: /usr/bin/dig: No such file or directory';
$strings[4] = 'Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Ut semper, eros et egestas congue, elit massa lacinia tellus, rhoncus feugiat sapien elit sit amet felis.';

echo '<html><body><span style="font-family:Courier New, Courier, monospace;">';
echo '<h1>Slug Examples:</h1>';
echo '<p>Strings:<br/>';
foreach($strings as $key=>$value) {
	echo '<strong>'.$key.'</strong> - '.$value.'<br/>';	
}

echo '<br/>';

echo '<br/><hr/><span style="color:#a00;font-size:80%;font-weight:bold;">Settings: Max Length set at 32, Fixed Length == false, Use Max Length == false</span><br/><br/>';
echo '<span style="color:#0a0;font-size:100%;font-weight:bold;">0&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;1&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;2&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;3&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;4<br/>';
echo '1234567890123456789012345678901234567890</span><br/><br/>';
Slug::setMaxLength(32);
foreach($strings as $key=>$value) {
	echo '<span style="color:#00a;font-size:80%;font-weight:bold;">String '.$key.':</span><br/>';
	echo Slug::generate($value).'<br/>';
}

echo '<br/><hr/><span style="color:#a00;font-size:80%;font-weight:bold;">Settings: Max Length set at 32, Fixed Length == TRUE, Use Max Length == false</span><br/><br/>';
echo '<span style="color:#0a0;font-size:100%;font-weight:bold;">0&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;1&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;2&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;3&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;4<br/>';
echo '1234567890123456789012345678901234567890</span><br/><br/>';
Slug::setFixedLength(true);
//Slug::setUseMaxLength(false);
foreach($strings as $key=>$value) {
	echo '<span style="color:#00a;font-size:80%;font-weight:bold;">String '.$key.':</span><br/>';
	echo Slug::generate($value).'<br/>';
}

echo '<br/><hr/><span style="color:#a00;font-size:80%;font-weight:bold;">Settings: Max Length set at 32, Fixed Length == false, Use Max Length == TRUE</span><br/><br/>';
echo '<span style="color:#0a0;font-size:100%;font-weight:bold;">0&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;1&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;2&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;3&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;4<br/>';
echo '1234567890123456789012345678901234567890</span><br/><br/>';
Slug::setFixedLength(false);
//Slug::setUseMaxLength(true);
foreach($strings as $key=>$value) {
	echo '<span style="color:#00a;font-size:80%;font-weight:bold;">String '.$key.':</span><br/>';
	echo Slug::generate($value).'<br/>';
}

echo '<br/><hr/><span style="color:#a00;font-size:80%;font-weight:bold;">Settings: Max Length set at 32, Fixed Length == TRUE, Use Max Length == TRUE</span><br/><br/>';
echo '<span style="color:#0a0;font-size:100%;font-weight:bold;">0&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;1&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;2&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;3&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;4<br/>';
echo '1234567890123456789012345678901234567890</span><br/><br/>';
Slug::setFixedLength(true);
//Slug::setUseMaxLength(true);
foreach($strings as $key=>$value) {
	echo '<span style="color:#00a;font-size:80%;font-weight:bold;">String '.$key.':</span><br/>';
	echo Slug::generate($value).'<br/>';
}


echo '</span></body></html>';

?>